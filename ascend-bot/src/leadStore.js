// Handles what happens to a completed lead.
// Ships with a console/file log fallback so the bot works out of the box.
// Swap saveLead() for a real Google Sheets / Airtable / CRM call when ready.

const fs = require("fs");
const path = require("path");
const { sendText } = require("./whatsappClient");

const LOG_FILE = path.join(__dirname, "..", "leads.log.json");

async function saveLead(phone, leadData) {
  const record = {
    phone,
    ...leadData,
    capturedAt: new Date().toISOString(),
  };

  // --- Fallback: append to a local JSON log file -------------------------
  let existing = [];
  try {
    existing = JSON.parse(fs.readFileSync(LOG_FILE, "utf8"));
  } catch (_) {
    existing = [];
  }
  existing.push(record);
  fs.writeFileSync(LOG_FILE, JSON.stringify(existing, null, 2));

  // --- TODO: replace with a real Google Sheets append -------------------
  // Example using googleapis (npm i googleapis):
  //
  // const { google } = require("googleapis");
  // const sheets = google.sheets({ version: "v4", auth });
  // await sheets.spreadsheets.values.append({
  //   spreadsheetId: process.env.GOOGLE_SHEET_ID,
  //   range: "Leads!A:F",
  //   valueInputOption: "USER_ENTERED",
  //   requestBody: { values: [[record.phone, record.name, record.buyerType, record.location, record.interest, record.capturedAt]] },
  // });

  // --- Notify sales team on WhatsApp, if configured -----------------------
  const salesNumber = process.env.SALES_TEAM_WHATSAPP_NUMBER;
  if (salesNumber) {
    const summary = `New lead — ${record.interest || "General"}
Name: ${record.name || "-"}
Type: ${record.buyerType || "-"}
Location: ${record.location || "-"}
Phone: ${phone}`;
    await sendText(salesNumber, summary);
  }

  return record;
}

module.exports = { saveLead };
