// Simple in-memory session store, keyed by WhatsApp phone number (E.164, no +).
// Swap this for Redis if you need sessions to survive restarts or scale
// across multiple server instances.

const sessions = new Map();

const SESSION_TTL_MS = 1000 * 60 * 60 * 6; // 6 hours of inactivity = reset

function getSession(phone) {
  const existing = sessions.get(phone);
  if (existing && Date.now() - existing.updatedAt < SESSION_TTL_MS) {
    return existing;
  }
  const fresh = {
    state: "START",
    data: {}, // collected lead info: name, buyerType, location, interest
    updatedAt: Date.now(),
  };
  sessions.set(phone, fresh);
  return fresh;
}

function saveSession(phone, session) {
  session.updatedAt = Date.now();
  sessions.set(phone, session);
}

function resetSession(phone) {
  sessions.delete(phone);
}

module.exports = { getSession, saveSession, resetSession };
