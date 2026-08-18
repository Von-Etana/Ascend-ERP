// Thin wrapper around the WhatsApp Cloud API "send message" endpoint.
// Docs: https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages

const GRAPH_VERSION = "v19.0";

function apiUrl() {
  const phoneNumberId = process.env.WHATSAPP_PHONE_NUMBER_ID;
  return `https://graph.facebook.com/${GRAPH_VERSION}/${phoneNumberId}/messages`;
}

async function sendText(toPhoneNumber, body) {
  const res = await fetch(apiUrl(), {
    method: "POST",
    headers: {
      Authorization: `Bearer ${process.env.WHATSAPP_TOKEN}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      messaging_product: "whatsapp",
      to: toPhoneNumber,
      type: "text",
      text: { body },
    }),
  });

  if (!res.ok) {
    const errText = await res.text();
    console.error("WhatsApp send failed:", res.status, errText);
  }

  return res.ok;
}

module.exports = { sendText };
