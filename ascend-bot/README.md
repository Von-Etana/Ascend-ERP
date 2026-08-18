# Ascend Systems WhatsApp Bot

Menu-driven WhatsApp bot for Ascend Systems: routes visitors to solar, security,
automation, or financing info, then captures a lead and hands it to the sales team.

## Stack

- Node.js + Express (webhook server)
- WhatsApp Cloud API (official, free tier)
- In-memory session store (swap for Redis if you need it to survive restarts)
- Lead log: local JSON file by default, with a Google Sheets stub ready to wire in

## Project structure

```
src/
  index.js          Express server — webhook verification + message handling
  flow.js           All menu copy and conversation branching logic
  sessionStore.js   Tracks each user's position in the conversation
  whatsappClient.js Sends messages via the WhatsApp Cloud API
  leadStore.js       Saves completed leads + notifies the sales team
leads.log.json      Local log of captured leads (created automatically)
```

## Setup

1. **Install dependencies**
   ```
   npm install
   ```

2. **Create a Meta Developer app**
   - Go to https://developers.facebook.com → create an app → add the WhatsApp product
   - Under WhatsApp → API Setup, grab your **temporary access token** and **phone number ID**
   - For production, generate a permanent token (System User token) instead of the temporary one

3. **Configure environment variables**
   ```
   cp .env.example .env
   ```
   Fill in:
   - `WHATSAPP_TOKEN` — from the Meta dashboard
   - `WHATSAPP_PHONE_NUMBER_ID` — from the Meta dashboard
   - `WHATSAPP_VERIFY_TOKEN` — any string you choose; you'll enter this same value in Meta's webhook setup
   - `SALES_TEAM_WHATSAPP_NUMBER` — optional, the number that gets a message when a lead completes

4. **Run locally**
   ```
   npm start
   ```
   Server runs on `http://localhost:3000` by default.

5. **Expose it publicly for testing** (Meta needs a public HTTPS URL)
   - Use `ngrok http 3000` for local testing, or deploy straight to Render/Railway (see below)

6. **Set the webhook in Meta's dashboard**
   - Callback URL: `https://your-domain.com/webhook`
   - Verify token: same value as `WHATSAPP_VERIFY_TOKEN`
   - Subscribe to the `messages` field

## Deploying to Render

1. Push this project to a GitHub repo
2. In Render: New → Web Service → connect the repo
3. Build command: `npm install`
4. Start command: `npm start`
5. Add the same environment variables from `.env` in Render's dashboard
6. Once deployed, use the Render URL + `/webhook` as your Meta webhook callback

## Wiring up real lead storage

Right now leads are appended to `leads.log.json` and (optionally) forwarded to the
sales team's WhatsApp number. To push to Google Sheets instead, open
`src/leadStore.js` — there's a commented-out example using the `googleapis`
package. Install it with:

```
npm install googleapis
```

and follow Google's service account setup guide to get credentials.

## Editing the conversation

All menu text and branching logic lives in `src/flow.js` — that's the only
file you need to touch to change copy, add a new menu option, or adjust the
lead-capture questions.
