/* Client account + Creator space (owner 19.11.2026): tabs switch like a slider (swipe on phones, ←/→ on computers),
   small "?" hints next to each section, a one-time "swipe" hint on phones. No timers, no libraries. */
(function () {
  var TIPS = {
    'My projects': 'Studio orders (mixing, mastering…). Follow every step, play each revision and leave notes at any moment of the track.',
    'My downloads': 'Everything you bought. Download again anytime while the link is active.',
    'Purchases': 'Every order with its receipt. Open one for details and the invoice.',
    'Favourites': 'Products you saved with the heart. Kept on this device.',
    'Quick actions': 'Shortcuts to what people use most.',
    'Account': 'Name, email, password, billing details and saved cards.',
    'Last 30 days': 'Sales, your earnings and preview plays, day by day.',
    'My products': 'Live = on sale. In review = we check it within 24 hours. Draft = only you see it.',
    'Best sellers': 'Your top products by sales, then by preview plays.',
    'Payouts': 'Your share is paid monthly once it reaches $25. Add PayPal or bank details to get paid.',
    'Profile strength': 'A full profile sells more: photo, cover, short bio, music links, country and payout method.',
    'Your profile page': 'Your public page. Share the link in your bio, under videos and in Discord.',
    'Active projects': 'Studio orders still in work',
    'Delivered': 'Finished studio orders',
    'Downloads': 'Files you can download',
    'Products live': 'On sale right now',
    'In review': 'We check new products within 24 hours',
    'Sales · last 7 days': 'Compared with the 7 days before',
    'Your earnings · last 7 days': 'Your share after the shop fee, compared with the 7 days before'
  };
  var NO = 'input,textarea,select,button,.ssa-nav,.rev,canvas,svg,table,[data-noswipe],.select2-container,.wp-editor-wrap';
  function txt(el) { return (el.textContent || '').replace(/\s+/g, ' ').trim(); }

  function tips(root) {
    root.querySelectorAll('h2').forEach(function (h) {
      if (h.querySelector('.cab-tip')) return;
      var t = TIPS[txt(h)];
      if (!t) return;
      var b = document.createElement('button');
      b.type = 'button'; b.className = 'cab-tip'; b.setAttribute('aria-label', 'What is this?'); b.setAttribute('aria-expanded', 'false');
      b.innerHTML = '?<span role="tooltip"></span>'; b.lastChild.textContent = t;
      h.appendChild(b);
    });
    root.querySelectorAll('.st > span').forEach(function (s) {
      var t = TIPS[txt(s)];
      if (t && !s.parentNode.title) s.parentNode.title = t;
    });
  }
  document.addEventListener('click', function (e) {
    var b = e.target.closest ? e.target.closest('.cab-tip') : null;
    document.querySelectorAll('.cab-tip.open').forEach(function (x) { if (x !== b) { x.classList.remove('open'); x.setAttribute('aria-expanded', 'false'); } });
    if (!b) return;
    e.preventDefault(); e.stopPropagation();
    var o = b.classList.toggle('open'); b.setAttribute('aria-expanded', o ? 'true' : 'false');
  }, true);

  function go(nav, list, i, dir) {
    var a = list[i]; if (!a) return;
    document.body.classList.add('cab-go', dir > 0 ? 'cab-l' : 'cab-r');
    list.forEach(function (x) { x.classList.remove('on'); }); a.classList.add('on');
    a.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
    setTimeout(function () { location.href = a.href; }, 160);
  }

  function init(nav) {
    if (nav.getAttribute('data-cab')) return; nav.setAttribute('data-cab', '1');
    var list = [].slice.call(nav.querySelectorAll('a:not(.out)')), i = -1;
    list.forEach(function (a, k) { if (a.classList.contains('on')) i = k; });
    var on = list[i];
    if (on) nav.scrollLeft = on.offsetLeft - (nav.clientWidth - on.offsetWidth) / 2;
    [list[i - 1], list[i + 1]].forEach(function (a) { if (a) { var l = document.createElement('link'); l.rel = 'prefetch'; l.href = a.href; document.head.appendChild(l); } });
    var edge = function () { nav.classList.toggle('cab-ml', nav.scrollLeft > 4); nav.classList.toggle('cab-mr', nav.scrollLeft + nav.clientWidth < nav.scrollWidth - 4); };
    edge(); nav.addEventListener('scroll', edge, { passive: true });
    tips(document);
    if (i < 0) return;

    /* swipe: horizontal, fast, not on players/inputs/scrollable rows */
    var x0 = 0, y0 = 0, t0 = 0, ok = false;
    document.addEventListener('touchstart', function (e) {
      ok = false; if (e.touches.length !== 1) return;
      var el = e.target;
      if (el.closest && el.closest(NO)) return;
      for (var n = el; n && n !== document.body; n = n.parentElement) { if (n.scrollWidth > n.clientWidth + 2 && /auto|scroll/.test(getComputedStyle(n).overflowX)) return; }
      x0 = e.touches[0].clientX; y0 = e.touches[0].clientY; t0 = Date.now(); ok = true;
    }, { passive: true });
    document.addEventListener('touchend', function (e) {
      if (!ok) return; ok = false;
      var dx = e.changedTouches[0].clientX - x0, dy = e.changedTouches[0].clientY - y0;
      if (Math.abs(dx) < 70 || Math.abs(dy) * 1.8 > Math.abs(dx) || Date.now() - t0 > 700) return;
      if (dx < 0 && i < list.length - 1) go(nav, list, i + 1, 1);
      if (dx > 0 && i > 0) go(nav, list, i - 1, -1);
    }, { passive: true });
    /* computers: ← / → when nothing is being typed */
    document.addEventListener('keydown', function (e) {
      if (e.altKey || e.ctrlKey || e.metaKey || e.shiftKey) return;
      var a = document.activeElement; if (a && /INPUT|TEXTAREA|SELECT/.test(a.tagName) || (a && a.isContentEditable)) return;
      if (e.key === 'ArrowRight' && i < list.length - 1) go(nav, list, i + 1, 1);
      if (e.key === 'ArrowLeft' && i > 0) go(nav, list, i - 1, -1);
    });
    /* phones: once, a small hint */
    var seen = '1'; try { seen = localStorage.getItem('ssCabHint'); } catch (x) {}
    if (!seen && matchMedia('(pointer:coarse)').matches) {
      var h = document.createElement('div'); h.className = 'cab-hint';
      h.innerHTML = '<span>Swipe left or right to switch tabs</span><button type="button" aria-label="Got it">Got it</button>';
      nav.insertAdjacentElement('afterend', h);
      var bye = function () { h.remove(); try { localStorage.setItem('ssCabHint', '1'); } catch (x) {} };
      h.querySelector('button').addEventListener('click', bye);
      document.addEventListener('touchend', function f() { if (document.body.classList.contains('cab-go')) bye(); }, { passive: true });
    }
  }

  function find() { var n = document.querySelector('.ssa-nav'); if (n) { init(n); return true; } return false; }
  if (!find()) {
    var mo = new MutationObserver(function () { if (find()) mo.disconnect(); });
    mo.observe(document.body, { childList: true, subtree: true });
    setTimeout(function () { mo.disconnect(); }, 15000);
  }
  window.addEventListener('pageshow', function (e) { if (e.persisted) document.body.classList.remove('cab-go', 'cab-l', 'cab-r'); });
})();
