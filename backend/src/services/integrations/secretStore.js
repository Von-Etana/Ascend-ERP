const crypto = require('crypto');

const algorithm = 'aes-256-gcm';

const getKeyMaterial = (env = process.env) => {
  const source = env.INTEGRATION_ENCRYPTION_KEY || env.JWT_SECRET || '';
  if (!source) return null;
  return crypto.createHash('sha256').update(String(source)).digest();
};

const encryptJson = (value, env = process.env) => {
  const key = getKeyMaterial(env);
  if (!key) {
    throw new Error('Missing INTEGRATION_ENCRYPTION_KEY or JWT_SECRET for integration secret storage');
  }
  const iv = crypto.randomBytes(12);
  const cipher = crypto.createCipheriv(algorithm, key, iv);
  const plaintext = Buffer.from(JSON.stringify(value || {}), 'utf8');
  const ciphertext = Buffer.concat([cipher.update(plaintext), cipher.final()]);
  const authTag = cipher.getAuthTag();
  return {
    version: 'v1',
    iv: iv.toString('base64'),
    authTag: authTag.toString('base64'),
    ciphertext: ciphertext.toString('base64'),
  };
};

const decryptJson = (payload, env = process.env) => {
  if (!payload?.ciphertext || !payload?.iv || !payload?.authTag) return {};
  const key = getKeyMaterial(env);
  if (!key) {
    throw new Error('Missing INTEGRATION_ENCRYPTION_KEY or JWT_SECRET for integration secret storage');
  }
  const decipher = crypto.createDecipheriv(
    algorithm,
    key,
    Buffer.from(payload.iv, 'base64')
  );
  decipher.setAuthTag(Buffer.from(payload.authTag, 'base64'));
  const plaintext = Buffer.concat([
    decipher.update(Buffer.from(payload.ciphertext, 'base64')),
    decipher.final(),
  ]);
  return JSON.parse(plaintext.toString('utf8') || '{}');
};

const maskSecret = (value = '') => {
  const stringValue = String(value || '');
  if (!stringValue) return '';
  if (stringValue.length <= 8) return '••••••••';
  return `${stringValue.slice(0, 3)}••••${stringValue.slice(-3)}`;
};

module.exports = {
  encryptJson,
  decryptJson,
  maskSecret,
};
