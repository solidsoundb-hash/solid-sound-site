// End-to-end test of the creator journey (owner ok 15.11.2026: "давай все от начала и до конца сделаем"):
//  1) a brand-new creator signs up on /en/sell/ (one-time e-mail @example.com + random password, never stored),
//  2) the test admin approves the account if the site asks for approval,
//  3) the creator opens Add Product, fills it with a test product "ZZ E2E TEST — delete me" + cover/ZIP/MP3, sends it for review,
//  4) checks the product is listed for the creator and is "pending" for the admin,
//  5) CLEAN-UP as admin: deletes the product, its media files and the test user. Every step is screenshotted.
// Secrets: TEST_ADMIN_USER / TEST_ADMIN_PASS. Output: e2e-out/ -> private artifact.
const { chromium } = require('playwright');
const fs = require('fs');
const crypto = require('crypto');
const OUT = 'e2e-out', BASE = 'https://solid-sound.net', A = process.argv[2] || 'assets-e2e';
const sleep = ms => new Promise(r => setTimeout(r, ms));
const log = []; const L = (...a) => { const s = a.join(' '); log.push(s); console.log(s); };
let shotN = 0;
const shot = async (p, name, full = true) => { shotN++; await p.screenshot({ path: `${OUT}/${String(shotN).padStart(2, '0')}_${name}.jpg`, type: 'jpeg', quality: 70, fullPage: full }).catch(() => {}); };
const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36 SolidSoundQA/1.0';

async function adminCtx(browser) {
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, userAgent: UA });
  await ctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
  const p = await ctx.newPage();
  await p.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
  await p.fill('#user_login', process.env.TEST_ADMIN_USER); await p.fill('#user_pass', process.env.TEST_ADMIN_PASS);
  await Promise.all([p.waitForNavigation({ timeout: 90000 }).catch(() => {}), p.click('#wp-submit')]);
  await sleep(1500);
  return { ctx, p };
}
async function api(p, method, path, body) {
  return p.evaluate(async ({ method, path, body }) => {
    const n = (await (await fetch('/wp-admin/admin-ajax.php?action=rest-nonce', { credentials: 'same-origin' })).text()).trim();
    const r = await fetch('/wp-json/' + path, { method, credentials: 'same-origin', headers: { 'X-WP-Nonce': n, 'Content-Type': 'application/json' }, body: body ? JSON.stringify(body) : undefined });
    let j = null; try { j = await r.json(); } catch (e) {}
    return { s: r.status, j };
  }, { method, path, body });
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  if (!process.env.TEST_ADMIN_USER || !process.env.TEST_ADMIN_PASS) { L('no TEST_ADMIN secrets'); fs.writeFileSync(`${OUT}/report.txt`, log.join('\n')); return; }
  const ts = Date.now().toString(36);
  const email = `ss-e2e-${ts}@example.com`, pass = 'E2e!' + crypto.randomBytes(9).toString('base64url');
  const store = `ZZ E2E ${ts}`, pname = `ZZ E2E TEST ${ts} — delete me`;
  const browser = await chromium.launch({ channel: process.env.PW_CHANNEL || undefined });
  let uid = 0, pid = 0;
  try {
    // ---------- 1. sign up ----------
    const vctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, userAgent: UA });
    await vctx.addCookies([{ name: 'ss_cc', value: '1', domain: 'solid-sound.net', path: '/' }]);
    const v = await vctx.newPage(); const verr = []; v.on('pageerror', e => verr.push(e.message));
    await v.goto(BASE + '/en/sell/', { waitUntil: 'domcontentloaded', timeout: 90000 }); await sleep(2000);
    const f = v.locator('#wcfm_membership_registration_form');
    await f.locator('[name=first_name]').fill('Zz'); await f.locator('[name=last_name]').fill('E2e');
    await f.locator('[name=user_email]').fill(email); await f.locator('[name=store_name]').fill(store);
    await f.locator('.sssell-chips label').first().click().catch(() => {});
    await f.locator('[name=passoword]').fill(pass); await f.locator('[name=confirm_pwd]').fill(pass);
    await f.locator('#terms').check({ force: true }).catch(() => {});
    await shot(v, 'signup_filled');
    await f.locator('input[type=submit]').first().click();
    await sleep(9000);
    const msg = await v.evaluate(() => (document.querySelector('.wcfm-message, .wcfm-success, .wcfm_membership_thankyou, .woocommerce-message') || {}).innerText || '');
    L(`1 signup → ${v.url()} | message: ${msg.replace(/\s+/g, ' ').slice(0, 200)} | errors: ${JSON.stringify(verr.slice(0, 3))}`);
    await shot(v, 'signup_after');

    // ---------- 2. admin: find + approve ----------
    const ad = await adminCtx(browser);
    const us = await api(ad.p, 'GET', 'wp/v2/users?search=' + encodeURIComponent(email) + '&context=edit');
    const u = us.j && us.j[0]; uid = u ? u.id : 0;
    L(`2 user created: ${uid ? 'yes id ' + uid + ' roles ' + JSON.stringify(u.roles) : 'NO (' + us.s + ')'}`);
    if (!uid) throw new Error('signup did not create a user');
    if (!(u.roles || []).includes('wcfm_vendor')) {
      // site asks for approval → approve like the owner would (role "Store Vendor")
      const r = await api(ad.p, 'POST', 'wp/v2/users/' + uid, { roles: ['wcfm_vendor'] });
      L(`2 approve (role → wcfm_vendor): ${r.s} ${r.j && JSON.stringify(r.j.roles)}`);
    }

    // ---------- 3. creator logs in and adds a product ----------
    await v.goto(BASE + '/wp-login.php', { waitUntil: 'domcontentloaded', timeout: 90000 });
    if (await v.locator('#user_login').count()) {
      await v.fill('#user_login', email); await v.fill('#user_pass', pass);
      await Promise.all([v.waitForNavigation({ timeout: 90000 }).catch(() => {}), v.click('#wp-submit')]); await sleep(2000);
    }
    await v.goto(BASE + '/en/store-manager/', { waitUntil: 'domcontentloaded', timeout: 90000 }); await sleep(2000);
    const menu = await v.evaluate(() => [...document.querySelectorAll('#wcfm_menu a.wcfm_menu_item')].map(a => a.innerText.replace(/\s+/g, ' ').trim()).filter(Boolean));
    L(`3 creator dashboard → ${v.url()} | menu: ${JSON.stringify(menu)}`);
    await shot(v, 'creator_dashboard');
    for (const pg of ['ss-products', 'ss-earnings', 'ss-payouts', 'ss-reviews', 'ss-settings', 'ss-help']) {
      await v.goto(BASE + '/en/store-manager/' + pg + '/', { waitUntil: 'domcontentloaded', timeout: 90000 }).catch(() => {}); await sleep(1200);
      await shot(v, 'creator_' + pg);
    }
    await v.goto(BASE + '/en/store-manager/ss-add-product/', { waitUntil: 'domcontentloaded', timeout: 90000 }); await sleep(3000);
    await shot(v, 'add_empty');
    // fill every visible required field with the first sensible value
    await v.evaluate(({ pname }) => {
      const form = document.getElementById('solid-sound-upload-form'); const $ = window.jQuery;
      const set = (s, val) => { s.value = val; if ($) $(s).trigger('change'); else s.dispatchEvent(new Event('change', { bubbles: true })); };
      const fmt = form.querySelector('.ss-ap-format select');
      if (fmt) { const o = [...fmt.options].find(o => /templ/i.test(o.text)) || [...fmt.options].find(o => o.value); if (o) set(fmt, o.value); }
      else { const r = form.querySelector('.ss-ap-format input'); if (r) r.click(); }
      form.querySelector('[name=product_name]').value = pname;
      form.querySelector('[name=product_description]').value = 'Automatic end-to-end test product. It is deleted right after the test. Please ignore.';
      form.querySelector('[name=product_price]').value = '9';
      form.querySelectorAll('.acf-field').forEach(w => {
        if (w.offsetParent === null || w.classList.contains('ss-ap-mp3')) return;
        const sel = w.querySelector('select'); if (sel && ![...sel.options].some(o => o.selected && o.value)) { const o = [...sel.options].find(o => o.value); if (o) set(sel, o.value); return; }
        const cb = w.querySelector('input[type=checkbox], input[type=radio]'); if (cb && !w.querySelector('input:checked')) { cb.click(); return; }
        const t = w.querySelector('input[type=text], input[type=number]'); if (t && !t.value) { t.value = /length/i.test(w.className) ? '5:30' : '120'; t.dispatchEvent(new Event('input', { bubbles: true })); }
      });
      form.dispatchEvent(new Event('change', { bubbles: true }));
    }, { pname });
    const multi = v.locator('#solid-sound-upload-form input[type=file][multiple]');
    await multi.setInputFiles([`${A}/cover.jpg`, `${A}/project.zip`, `${A}/demo.mp3`]);
    await sleep(12000); // MP3 goes to the media library first
    await shot(v, 'add_filled');
    const before = await v.evaluate(() => ({ pc: (document.querySelector('.ss-wm-ai .pc') || {}).textContent, files: (document.querySelector('.ss-wm-files') || {}).innerText }));
    L(`3 form filled: completeness ${before.pc} | files: ${(before.files || '').replace(/\s+/g, ' ')}`);
    await Promise.all([v.waitForNavigation({ timeout: 120000 }).catch(() => {}), v.locator('.ss-wm-save').click()]);
    await sleep(4000);
    const errs = await v.evaluate(() => [...document.querySelectorAll('.ss-ap-err')].map(e => e.textContent));
    L(`3 sent → ${v.url()} | form errors: ${JSON.stringify(errs)} | page: ${(await v.evaluate(() => (document.querySelector('.wcfm-message, .woocommerce-message, .ss-notice, .sscp-notice, h1, h2') || {}).innerText || '')).replace(/\s+/g, ' ').slice(0, 160)}`);
    await shot(v, 'add_sent');
    await v.goto(BASE + '/en/store-manager/ss-products/', { waitUntil: 'domcontentloaded', timeout: 90000 }); await sleep(1500);
    const listed = await v.evaluate(n => [...document.querySelectorAll('tr')].filter(r => r.innerText.includes(n)).map(r => r.innerText.replace(/\s+/g, ' ')), pname.slice(0, 20));
    L(`4 creator Products: ${JSON.stringify(listed)}`);
    await shot(v, 'creator_products_after');
    await vctx.close();

    // ---------- 4. admin sees it pending ----------
    const ps = await api(ad.p, 'GET', 'wc/v3/products?status=any&per_page=20&search=' + encodeURIComponent('ZZ E2E TEST ' + ts));
    const prod = ps.j && ps.j[0]; pid = prod ? prod.id : 0;
    L(`4 admin: product ${pid ? 'found id ' + pid + ' status ' + prod.status + ' price ' + prod.regular_price + ' downloads ' + (prod.downloads || []).length + ' image ' + ((prod.images || [])[0] ? 'yes' : 'no') : 'NOT found (' + ps.s + ')'}`);
    await ad.p.goto(BASE + '/wp-admin/edit.php?post_type=product&post_status=pending', { waitUntil: 'domcontentloaded', timeout: 90000 }); await sleep(1500);
    await shot(ad.p, 'admin_pending', false);
    ad.p.__keep = true; global.AD = ad;
  } catch (e) {
    L('STOPPED: ' + e.message);
  } finally {
    // ---------- 5. clean-up ----------
    try {
      const ad = global.AD || await adminCtx(browser);
      if (!pid) { const ps = await api(ad.p, 'GET', 'wc/v3/products?status=any&per_page=20&search=' + encodeURIComponent('ZZ E2E TEST ' + ts)); if (ps.j && ps.j[0]) pid = ps.j[0].id; }
      if (!uid) { const us = await api(ad.p, 'GET', 'wp/v2/users?search=' + encodeURIComponent(email) + '&context=edit'); if (us.j && us.j[0]) uid = us.j[0].id; }
      if (uid) {
        const media = await api(ad.p, 'GET', 'wp/v2/media?per_page=50&author=' + uid);
        for (const m of (media.j || [])) { const r = await api(ad.p, 'DELETE', 'wp/v2/media/' + m.id + '?force=true'); L(`5 delete media ${m.id} (${m.source_url}) → ${r.s}`); }
      }
      if (pid) {
        const pr = await api(ad.p, 'GET', 'wc/v3/products/' + pid);
        for (const im of ((pr.j && pr.j.images) || [])) { const r = await api(ad.p, 'DELETE', 'wp/v2/media/' + im.id + '?force=true'); L(`5 delete product image ${im.id} → ${r.s}`); }
        const r = await api(ad.p, 'DELETE', 'wc/v3/products/' + pid + '?force=true'); L(`5 delete product ${pid} → ${r.s}`);
      }
      if (uid) {
        const me = await api(ad.p, 'GET', 'wp/v2/users/me');
        const r = await api(ad.p, 'DELETE', 'wp/v2/users/' + uid + '?force=true&reassign=' + (me.j ? me.j.id : 1)); L(`5 delete test user ${uid} → ${r.s}`);
      }
      const left = await api(ad.p, 'GET', 'wc/v3/products?status=any&search=' + encodeURIComponent('ZZ E2E'));
      L(`5 leftovers "ZZ E2E" products: ${(left.j || []).length}`);
      await ad.ctx.close();
    } catch (e) { L('CLEAN-UP PROBLEM: ' + e.message + ' — check WP admin for "ZZ E2E"'); }
    await browser.close();
    fs.writeFileSync(`${OUT}/report.txt`, log.join('\n') + '\n');
  }
})();
