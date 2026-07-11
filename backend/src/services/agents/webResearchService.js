const dns = require('node:dns').promises;
const net = require('node:net');

const isPrivateIp = (address) => {
  if (!address) return true;
  if (net.isIPv4(address)) {
    const [a, b] = address.split('.').map(Number);
    return a === 10 || a === 127 || a === 0 || (a === 169 && b === 254) || (a === 172 && b >= 16 && b <= 31) || (a === 192 && b === 168);
  }
  return address === '::1' || address.startsWith('fc') || address.startsWith('fd') || address.startsWith('fe80');
};

const assertPublicUrl = async (value, { lookup = dns.lookup } = {}) => {
  const url = new URL(value);
  if (!['http:', 'https:'].includes(url.protocol)) throw new Error('Only HTTP and HTTPS research sources are allowed');
  if (url.username || url.password) throw new Error('Authenticated URLs are not allowed');
  const records = await lookup(url.hostname, { all: true });
  if (!records.length || records.some((record) => isPrivateIp(record.address))) throw new Error('Private or local network targets are not allowed');
  return url;
};

const fetchPublicPage = async (value, { fetchImpl = global.fetch, lookup, maxBytes = 1000000 } = {}) => {
  const url = await assertPublicUrl(value, { lookup });
  const robotsUrl = new URL('/robots.txt', url.origin);
  const robots = await fetchImpl(robotsUrl, { headers: { 'User-Agent': 'IDURAR-Agent/1.0' }, redirect: 'error' }).catch(() => null);
  const robotsText = robots?.ok ? await robots.text() : '';
  if (/User-agent:\s*\*[\s\S]*?Disallow:\s*\/\s*(?:\r?\n|$)/i.test(robotsText)) throw new Error('This website disallows automated research');
  const response = await fetchImpl(url, { headers: { 'User-Agent': 'IDURAR-Agent/1.0' }, redirect: 'error' });
  if (!response.ok) throw new Error(`Research source returned HTTP ${response.status}`);
  const type = response.headers.get('content-type') || '';
  if (!type.includes('text/html') && !type.includes('text/plain')) throw new Error('Unsupported research content type');
  const text = (await response.text()).slice(0, maxBytes);
  return { title: text.match(/<title[^>]*>([^<]+)<\/title>/i)?.[1]?.trim() || url.hostname, url: url.toString(), text: text.replace(/<script[\s\S]*?<\/script>/gi, ' ').replace(/<style[\s\S]*?<\/style>/gi, ' ').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim(), fetchedAt: new Date() };
};

module.exports = { isPrivateIp, assertPublicUrl, fetchPublicPage };
