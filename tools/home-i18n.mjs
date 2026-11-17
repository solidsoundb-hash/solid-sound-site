// Home app translations (owner 11.11: translations available everywhere; 17.11: new home on all languages).
// extract(tpl) -> list of English UI texts in the PATCHED template (text between tags + quoted UI phrases);
// apply(tpl, dict) -> template with those texts replaced. Texts that home.js / ai-search.js / home.css use
// as selectors or matchers are left in English (SKIP), so behaviour is the same on every language.
const TXT = />([^<>{}]*[A-Za-z]{2,}[^<>{}]*)</g;
const Q = /'((?:[^'\\\n]|\\.)*[A-Za-z]{2,}(?:[^'\\\n]|\\.)*)'/g;
const isUi = (s) => /[A-Za-z]{2,}/.test(s) && !/^[\w.-]+$/.test(s) && !/https?:|\/[a-z]|[{}<>=;]|\b(?:px|rgba|var\(|function)\b|^#|\.(?:jpg|png|mp3|webp)/i.test(s) && !/^[a-z]/.test(s.trim()) && !/^\s*[,):+.\]]/.test(s) && !/^M[\d.]/.test(s) && !/^[\d.]+(px)?\s|^\d+ \d/.test(s) && !/\?\s*$|^\s*:|\.test\(|\bs\.\w|\|\||!!/.test(s);
export function extract(tpl, skip = new Set()) {
  const out = new Set();
  for (const m of tpl.matchAll(TXT)) { const t = m[1].trim(); if (t.length > 1 && isUi(t) && !skip.has(t)) out.add(t); }
  for (const m of tpl.matchAll(Q)) { const t = m[1].replace(/\\'/g, "'").trim(); if (t.length > 2 && /\s/.test(t) && isUi(t) && !skip.has(t)) out.add(t); }
  return [...out];
}
export function apply(tpl, dict) {
  const look = (k) => dict[k] || dict[k.replace(/&amp;/g, '&')];
  const keep = (t, v) => { const m = t.match(/^(\s*)[\s\S]*?(\s*)$/); return m[1] + v + m[2]; };
  tpl = tpl.replace(TXT, (all, t) => { const v = look(t.trim()); return v ? '>' + keep(t, /&amp;/.test(t) ? v.replace(/&(?!amp;)/g, '&amp;') : v) + '<' : all; });
  tpl = tpl.replace(Q, (all, t) => { const raw = t.replace(/\\'/g, "'"); const v = look(raw.trim()); return v && /\s/.test(raw.trim()) || (v && raw !== raw.trim()) ? "'" + keep(raw, v).replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "'" : all; });
  // single-word menu labels: T('Favorites', …), mk('Sell', …), label: 'Examples'
  tpl = tpl.replace(/(\b(?:T|mk)\(|\b(?:label|name|group|desc|title|tag|kicker|cta|badge):\s*)'([^'\\\n]+)'/g, (all, pre, t) => { const v = look(t); return v ? pre + "'" + v.replace(/'/g, "\\'") + "'" : all; });
  // pairs: ['mix', 'Mixing'], ['Upload', 'done'] — only values that start with a capital letter (ids stay)
  tpl = tpl.replace(/\['([^'\\\n]+)',(\s*)'([^'\\\n]+)'\]/g, (all, x, sp, y) => { const f = (t) => (/^[A-Z]/.test(t) && look(t)) ? look(t).replace(/'/g, "\\'") : t; return "['" + f(x) + "'," + sp + "'" + f(y) + "']"; });
  return tpl;
}
