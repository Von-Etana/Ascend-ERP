require("dotenv").config();
const express = require("express");
const { getSession, saveSession } = require("./sessionStore");
const { route } = require("./flow");
const { sendText } = require("./whatsappClient");
const { saveLead } = require("./leadStore");

const app = express();
app.use(express.json());

// --- Webhook verification (Meta calls this once when you set the webhook URL) ---
app.get("/webhook", (req, res) => {
  const mode = req.query["hub.mode"];
  const token = req.query["hub.verify_token"];
  const challenge = req.query["hub.challenge"];

  if (mode === "subscribe" && token === process.env.WHATSAPP_VERIFY_TOKEN) {
    console.log("Webhook verified.");
    return res.status(200).send(challenge);
  }
  return res.sendStatus(403);
});

// --- Incoming messages ---------------------------------------------------
app.post("/webhook", async (req, res) => {
  // Always ack immediately — WhatsApp retries if it doesn't get a fast 200.
  res.sendStatus(200);

  try {
    const entry = req.body.entry?.[0];
    const change = entry?.changes?.[0];
    const value = change?.value;
    const message = value?.messages?.[0];

    if (!message) return; // e.g. a delivery/read status update, not a message

    const from = message.from; // sender's phone number, no "+"
    const text = message.text?.body || "";

    const session = getSession(from);
    const { reply, session: updatedSession, leadComplete } = route(session, text);
    saveSession(from, updatedSession);

    await sendText(from, reply);

    if (leadComplete) {
      await saveLead(from, updatedSession.data);
    }
  } catch (err) {
    console.error("Error handling incoming message:", err);
  }
});

app.get("/", (_req, res) => {
  res.send("Ascend Systems WhatsApp bot is running.");
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Ascend bot listening on port ${PORT}`);
});
