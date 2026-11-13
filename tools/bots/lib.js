// Shared helpers for the weekly growth bots (SEO, analytics, products, competitors, promos, security).
'use strict';
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');

const BASE = 'https://solid-sound.net';
const OUT = process.env.BOTS_OUT || 'reports/weekly';
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundBots/1.0';
const sleep = (ms) => new Promise(r => setTimeout(r, ms));
const isBlock = (t) => /Захищена сторінка|<title>\s*403 Forbidden/.test(t || '');

// GET with retries (the hosting answers 429 / an anti-bot page to bursts)
async function get(url, opts = {}) {
  let last = '';
  for (let i = 0; i < (opts.tries || 4); i++) {
    try {
      const r = await fetch(url, { headers: { 'User-Agent': UA, ...(opts.headers || {}) }, signal: AbortSignal.timeout(opts.timeout || 60000) });
      const t = await r.text();
      if (r.status === 429 || isBlock(t)) { last = 'HTTP ' + r.status + ' (защита от частых запросов)'; await sleep(10000 * (i + 1)); continue; }
      return { status: r.status, text: t, url: r.url, json: () => JSON.parse(t) };
    } catch (e) { last = e.message; await sleep(5000 * (i + 1)); }
  }
  return { status: 0, text: '', error: last, json: () => { throw new Error(last); } };
}

// Google service account -> access token (no extra packages). Secret GOOGLE_SA_JSON = the downloaded key file.
async function googleToken(scopes) {
  const raw = process.env.GOOGLE_SA_JSON; if (!raw) return null;
  const sa = JSON.parse(raw);
  const b64 = (o) => Buffer.from(typeof o === 'string' ? o : JSON.stringify(o)).toString('base64url');
  const now = Math.floor(Date.now() / 1000);
  const unsigned = b64({ alg: 'RS256', typ: 'JWT' }) + '.' + b64({ iss: sa.client_email, scope: scopes.join(' '), aud: 'https://oauth2.googleapis.com/token', iat: now, exp: now + 3600 });
  const sig = crypto.createSign('RSA-SHA256').update(unsigned).sign(sa.private_key).toString('base64url');
  const r = await fetch('https://oauth2.googleapis.com/token', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams({ grant_type: 'urn:ietf:params:oauth:grant-type:jwt-bearer', assertion: unsigned + '.' + sig }) });
  const j = await r.json();
  if (!j.access_token) throw new Error('Google не выдал доступ: ' + (j.error_description || j.error || r.status));
  return { token: j.access_token, email: sa.client_email };
}
async function gpost(url, token, body) {
  const r = await fetch(url, { method: 'POST', headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' }, body: JSON.stringify(body) });
  const j = await r.json().catch(() => ({}));
  if (!r.ok) throw new Error((j.error && j.error.message) || 'HTTP ' + r.status);
  return j;
}
async function gget(url, token) {
  const r = await fetch(url, { headers: { Authorization: 'Bearer ' + token } });
  const j = await r.json().catch(() => ({}));
  if (!r.ok) throw new Error((j.error && j.error.message) || 'HTTP ' + r.status);
  return j;
}

// state between weekly runs (to say "new since last week")
function loadState(name) { try { return JSON.parse(fs.readFileSync(path.join(OUT, 'state', name + '.json'), 'utf8')); } catch (e) { return null; } }
function saveState(name, data) { fs.mkdirSync(path.join(OUT, 'state'), { recursive: true }); fs.writeFileSync(path.join(OUT, 'state', name + '.json'), JSON.stringify(data, null, 1)); }

const day = (d) => d.toISOString().slice(0, 10);
const daysAgo = (n) => { const d = new Date(); d.setUTCDate(d.getUTCDate() - n); return d; };
const pct = (a, b) => (b ? Math.round((a - b) / b * 100) : (a ? 100 : 0));
const sign = (n) => (n > 0 ? '+' + n : String(n));
const esc = (s) => String(s == null ? '' : s).replace(/\|/g, '/').replace(/\n/g, ' ');

// numbers each bot reports for the main robot's week-by-week analytics (reports/weekly/metrics.json)
const metrics = {};

module.exports = { metrics, BASE, OUT, UA, sleep, get, googleToken, gpost, gget, loadState, saveState, day, daysAgo, pct, sign, esc };
