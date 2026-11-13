// QA bot: injected into every page before the site's own scripts.
// - remembers every audio/video element that ever played (also `new Audio()` objects that are not in the page)
// - counts DOM changes (to tell a "dead" button from a working one)
// - lets the bot ask "what is audible right now?"
(() => {
  const M = window.__qaMedia = new Set();
  const P = HTMLMediaElement.prototype; const op = P.play;
  P.play = function () { M.add(this); return op.apply(this, arguments); };
  window.__qaAudible = () => {
    const out = []; const seen = new Set();
    const add = (m) => {
      if (seen.has(m)) return; seen.add(m);
      if (!m.paused && !m.ended && !m.muted && m.volume > 0) out.push({ src: decodeURIComponent((m.currentSrc || m.src || '').split('/').pop()).slice(0, 80), t: +m.currentTime.toFixed(2) });
    };
    M.forEach(add); document.querySelectorAll('audio,video').forEach(add);
    return out;
  };
  window.__qaStopAll = () => { M.forEach(m => { try { m.pause(); } catch (e) {} }); document.querySelectorAll('audio,video').forEach(m => { try { m.pause(); } catch (e) {} }); };
  window.__qaMut = 0;
  const start = () => { try { new MutationObserver(l => { window.__qaMut += l.length; }).observe(document.documentElement, { subtree: true, childList: true, attributes: true, characterData: true }); } catch (e) {} };
  if (document.documentElement) start(); else document.addEventListener('DOMContentLoaded', start);
  // the bot must not trigger AI search (paid) — keep the search in plain mode
  try { localStorage.setItem('ssAiOn', '0'); } catch (e) {}
})();
