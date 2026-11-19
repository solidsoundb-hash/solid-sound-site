/* SS Audio — Solid Sound's own audio analysis (owner 19.11.2026: «разработай наш Essentia.js»).
 * No third-party code, runs in the browser (and in Node for tests). Everything is computed from the decoded audio:
 *   SSAudio.key(samples, sampleRate)      → { key:'Am', tonic:'A', mode:'minor', confidence:0..1, alt:'C', chroma:[12] }
 *   SSAudio.loudness(samples, sampleRate) → { lufs, peak_db, lra }   (integrated loudness, BS.1770-style gating)
 *   SSAudio.analyze(audioBuffer)          → { key, loudness, duration }
 * Key: harmonic part only (median-filter HPSS removes drums and noise) → peak-picked spectrum → tuning-corrected
 * chroma (bass weighted, it carries the tonic in electronic music) → correlation with three key profiles
 * (classical, pop/rock, electronic). Confidence is the gap between the best and the next key that is not
 * its relative major/minor, so the form only fills the key in when the music is clear about it. */
(function (root) {
  'use strict';
  var NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
  var FLAT = { 'C#': 'Db', 'D#': 'Eb', 'G#': 'Ab', 'A#': 'Bb', 'F#': 'F#' };

  /* ---------- helpers ---------- */
  function mono(buf) { /* AudioBuffer → Float32Array */
    var n = buf.length, c = buf.numberOfChannels, out = new Float32Array(n), k, i, d;
    for (k = 0; k < c; k++) { d = buf.getChannelData(k); for (i = 0; i < n; i++) out[i] += d[i] / c; }
    return out;
  }
  function decimate(x, sr, target) { /* box low-pass + integer decimation: enough for chroma below 2.5 kHz */
    var f = Math.max(1, Math.floor(sr / target)); if (f === 1) return { x: x, sr: sr };
    var n = Math.floor(x.length / f), y = new Float32Array(n), i, j, s;
    for (i = 0; i < n; i++) { s = 0; for (j = 0; j < f; j++) s += x[i * f + j]; y[i] = s / f; }
    return { x: y, sr: sr / f };
  }
  function fft(re, im) { /* in-place radix-2 */
    var n = re.length, i, j = 0, k, m, t, ang, wr, wi, cr, ci, a, b, tr, ti;
    for (i = 1; i < n; i++) { k = n >> 1; while (j & k) { j ^= k; k >>= 1; } j |= k; if (i < j) { t = re[i]; re[i] = re[j]; re[j] = t; t = im[i]; im[i] = im[j]; im[j] = t; } }
    for (m = 2; m <= n; m <<= 1) {
      ang = -2 * Math.PI / m; wr = Math.cos(ang); wi = Math.sin(ang);
      for (i = 0; i < n; i += m) { cr = 1; ci = 0;
        for (k = 0; k < m / 2; k++) { a = i + k; b = a + m / 2; tr = re[b] * cr - im[b] * ci; ti = re[b] * ci + im[b] * cr; re[b] = re[a] - tr; im[b] = im[a] - ti; re[a] += tr; im[a] += ti; t = cr * wr - ci * wi; ci = cr * wi + ci * wr; cr = t; }
      }
    }
  }
  function median(a) { var s = Array.prototype.slice.call(a).sort(function (p, q) { return p - q; }); return s[s.length >> 1]; }
  function corr(a, b) {
    var n = a.length, ma = 0, mb = 0, i, sa = 0, sb = 0, s = 0;
    for (i = 0; i < n; i++) { ma += a[i]; mb += b[i]; } ma /= n; mb /= n;
    for (i = 0; i < n; i++) { s += (a[i] - ma) * (b[i] - mb); sa += (a[i] - ma) * (a[i] - ma); sb += (b[i] - mb) * (b[i] - mb); }
    return sa && sb ? s / Math.sqrt(sa * sb) : 0;
  }

  /* ---------- key profiles (index 0 = tonic): [major, minor] ---------- */
  var BASE = {
    kk: [[6.35, 2.23, 3.48, 2.33, 4.38, 4.09, 2.52, 5.19, 2.39, 3.66, 2.29, 2.88], [6.33, 2.68, 3.52, 5.38, 2.60, 3.53, 2.54, 4.75, 3.98, 2.69, 3.34, 3.17]],
    tp: [[0.748, 0.060, 0.488, 0.082, 0.670, 0.460, 0.096, 0.715, 0.104, 0.366, 0.057, 0.400], [0.712, 0.084, 0.474, 0.618, 0.049, 0.460, 0.105, 0.747, 0.404, 0.067, 0.133, 0.330]],
    /* electronic dance music: median note profiles of Beatport tracks, least relevant notes zeroed (Faraldo et al., published data) */
    bg: [[1.00, 0, 0.42, 0, 0.53, 0.37, 0, 0.77, 0, 0.38, 0.21, 0.30], [1.00, 0, 0.36, 0.39, 0, 0.38, 0, 0.74, 0.27, 0, 0.42, 0.23]],
    ed: [[1.00, 0.29, 0.50, 0.40, 0.60, 0.56, 0.32, 0.80, 0.31, 0.45, 0.42, 0.39], [1.00, 0.31, 0.44, 0.58, 0.33, 0.49, 0.29, 0.78, 0.43, 0.29, 0.53, 0.32]]
  };
  /* "polyphonic" version of a profile: what a chroma of real instruments looks like when the notes have overtones
     (harmonic h lands 12·log2(h) semitones up, weaker by slope^(h-1)) — our chroma hears overtones too */
  function poly(p, nh, slope) {
    var out = new Array(12).fill(0), n, h;
    for (n = 0; n < 12; n++) for (h = 1; h <= nh; h++) out[(n + Math.round(12 * Math.log2(h))) % 12] += p[n] * Math.pow(slope, h - 1);
    return out;
  }
  function profiles(opt) {
    var set = (opt.prof || 'kk,tp,ed').split(','), nh = opt.nh == null ? 4 : opt.nh, sl = opt.slope == null ? 0.6 : opt.slope;
    return set.map(function (k) { return [poly(BASE[k][0], nh, sl), poly(BASE[k][1], nh, sl)]; });
  }

  /* ---------- chroma from the harmonic part ---------- */
  function chroma(x, sr, opt) {
    opt = opt || {};
    var d = decimate(x, sr, 11025); x = d.x; sr = d.sr;
    var N = opt.N || 8192, H = opt.H || N / 2, nb = N / 2, win = new Float32Array(N), i, j, f;
    for (i = 0; i < N; i++) win[i] = 0.5 - 0.5 * Math.cos(2 * Math.PI * i / (N - 1));
    /* at most ~3 minutes, spread over the whole track */
    var frames = Math.max(0, Math.floor((x.length - N) / H) + 1), step = Math.max(1, Math.ceil(frames / (opt.maxF || 480)));
    var lo = Math.floor((opt.lo || 70) * N / sr), hi = Math.min(nb - 2, Math.ceil((opt.hi || 2400) * N / sr));
    var S = [], re = new Float32Array(N), im = new Float32Array(N), rms = [];
    for (f = 0; f < frames; f += step) {
      var o = f * H, e = 0;
      for (i = 0; i < N; i++) { var v = x[o + i]; re[i] = v * win[i]; im[i] = 0; e += v * v; }
      rms.push(Math.sqrt(e / N)); fft(re, im);
      var m = new Float32Array(hi + 2);
      for (j = lo - 1; j <= hi + 1; j++) m[j] = Math.sqrt(Math.sqrt(re[j] * re[j] + im[j] * im[j])); /* |X|^0.5: tames loud bass */
      S.push(m);
    }
    var T = S.length; if (!T) return null;
    var gate = median(rms) * 0.1;
    /* HPSS: harmonic = median over time (±4 frames), percussive = median over frequency (±8 bins) */
    var acc = new Float64Array(12), dev = new Float64Array(100), peaks = [], t, k, w = [];
    for (t = 0; t < T; t++) {
      if (rms[t] < gate) continue;
      var Hm = new Float32Array(hi + 2), col = [];
      for (j = lo; j <= hi; j++) {
        col.length = 0; for (k = Math.max(0, t - (opt.ht || 4)); k <= Math.min(T - 1, t + (opt.ht || 4)); k++) col.push(S[k][j]);
        var hm = median(col);
        col.length = 0; for (k = Math.max(lo, j - 8); k <= Math.min(hi, j + 8); k++) col.push(S[t][k]);
        var pm = median(col);
        Hm[j] = hm * hm / (hm * hm + pm * pm + 1e-12) * S[t][j];
      }
      /* spectral peaks of the harmonic part, with a local-mean threshold (whitening) */
      var fp = [];
      for (j = lo + 1; j < hi; j++) {
        var a = Hm[j]; if (a <= Hm[j - 1] || a < Hm[j + 1]) continue;
        var loc = 0, c = 0; for (k = Math.max(lo, j - 20); k <= Math.min(hi, j + 20); k++) { loc += Hm[k]; c++; } loc /= c;
        if (a < loc * (opt.pk || 1.4)) continue;
        var p = 0.5 * (Hm[j - 1] - Hm[j + 1]) / (Hm[j - 1] - 2 * a + Hm[j + 1] || 1e-12); /* parabolic interpolation */
        var hz = (j + p) * sr / N, midi = 69 + 12 * Math.log2(hz / 440);
        fp.push([midi, a - loc, hz]);
        var cents = Math.round((midi - Math.round(midi)) * 100) + 50; dev[Math.max(0, Math.min(99, cents))] += a - loc;
      }
      peaks.push(fp);
    }
    /* global tuning (A=440 ± 50 cents) */
    var best = 0, bi = 50; for (i = 0; i < 100; i++) { var s3 = dev[i] + (dev[i - 1] || 0) + (dev[i + 1] || 0); if (s3 > best) { best = s3; bi = i; } }
    var tune = (bi - 50) / 100;
    /* sustained notes only: a peak counts when the same note (±30 cents) is also there in the neighbouring frames —
       pads, bass and leads hold their notes, noise, risers and voice-over do not */
    var per = opt.persist == null ? 2 : opt.persist;
    function has(fp, m) { if (!fp) return false; for (var q = 0; q < fp.length; q++) if (Math.abs(fp[q][0] - m) < 0.3) return true; return false; }
    for (t = 0; t < peaks.length; t++) {
      var fr = new Float64Array(12), mx = 0;
      peaks[t].forEach(function (pk) {
        if (per && !(has(peaks[t - 1], pk[0]) || has(peaks[t + 1], pk[0]))) return;
        if (per > 1 && !(has(peaks[t - 1], pk[0]) && has(peaks[t + 1], pk[0]))) return;
        var m2 = pk[0] - tune, n = Math.round(m2), off = Math.abs(m2 - n); if (off > 0.4) return;
        var wgt = pk[1] * Math.cos(off * Math.PI / 0.8) * (pk[2] < 260 ? (opt.bass || 1.6) : pk[2] > 1200 ? 0.6 : 1); /* bass carries the tonic */
        fr[((n % 12) + 12) % 12] += wgt;
      });
      for (i = 0; i < 12; i++) if (fr[i] > mx) mx = fr[i];
      if (mx > 0) for (i = 0; i < 12; i++) acc[i] += fr[i] / mx; /* every frame counts the same */
    }
    var sum = 0; for (i = 0; i < 12; i++) sum += acc[i];
    return sum ? Array.prototype.map.call(acc, function (v) { return v / sum; }) : null;
  }

  /* ---------- the bass line, like a listener hears it (owner 19.11: «слушал как человек и определял по тонике») ----------
     Short windows (0.25 s, step 0.1 s) between 55 and 260 Hz; the strongest bass pitch of each step; a note counts only
     when it HOLDS (same pitch ±35 cents for ≥ 3 steps = 0.3 s) — a kick's thump falls in pitch and dies, a bass note stays.
     Weighted by how long it is held: the note the bass keeps returning to and resting on is the tonic. */
  function bassline(x, sr, opt) {
    opt = opt || {};
    var d = decimate(x, sr, 2756); x = d.x; sr = d.sr;
    var W = Math.round(0.25 * sr), N = 4096, H = Math.round(0.1 * sr), i, j, f;
    var win = new Float32Array(W); for (i = 0; i < W; i++) win[i] = 0.5 - 0.5 * Math.cos(2 * Math.PI * i / (W - 1));
    var lo = Math.floor((opt.blo || 55) * N / sr), hi = Math.ceil((opt.bhi || 260) * N / sr);
    var re = new Float32Array(N), im = new Float32Array(N), P = [], E = [];
    var frames = Math.floor((x.length - W) / H), step = Math.max(1, Math.ceil(frames / 2400));
    for (f = 0; f < frames; f += step) {
      var o = f * H; re.fill(0); im.fill(0);
      for (i = 0; i < W; i++) re[i] = x[o + i] * win[i];
      fft(re, im);
      var best = 0, bj = -1, tot = 0, m = new Float32Array(hi + 2);
      for (j = lo - 1; j <= hi + 1; j++) m[j] = Math.sqrt(re[j] * re[j] + im[j] * im[j]);
      for (j = lo; j <= hi; j++) { tot += m[j]; if (m[j] > best && m[j] >= m[j - 1] && m[j] >= m[j + 1]) { best = m[j]; bj = j; } }
      if (bj < 0) { P.push(null); E.push(0); continue; }
      var p = 0.5 * (m[bj - 1] - m[bj + 1]) / (m[bj - 1] - 2 * best + m[bj + 1] || 1e-12);
      var hz = (bj + p) * sr / N;
      /* prefer the fundamental: if half the frequency also has a strong peak, that is the note */
      var hj = Math.round(bj / 2); if (hj >= lo && m[hj] > best * 0.5) hz = hz / 2;
      P.push(69 + 12 * Math.log2(hz / 440)); E.push(best / (tot / (hi - lo + 1) + 1e-9));
    }
    var hist = new Float64Array(12), held = 0, run = [], k;
    function flush() { if (run.length >= (opt.hold || 3)) { var s = 0; run.forEach(function (v) { s += v; }); var mid = s / run.length, pc = ((Math.round(mid) % 12) + 12) % 12; hist[pc] += run.length; held += run.length; } run = []; }
    for (k = 0; k < P.length; k++) {
      if (P[k] == null || E[k] < (opt.peaky || 3)) { flush(); continue; }
      if (run.length && Math.abs(P[k] - run[run.length - 1]) > 0.35) flush();
      run.push(P[k]);
    }
    flush();
    var sum = 0; for (i = 0; i < 12; i++) sum += hist[i];
    return { hist: sum ? Array.prototype.map.call(hist, function (v) { return v / sum; }) : null, held: held * step * 0.1 };
  }

  /* ---------- tempo (owner 19.11: «темп и длительность тоже должны быть точными») ----------
     Onset strength = positive spectral flux of the log spectrum (kick weighted), local mean removed; tempo = the lag whose
     autocorrelation (plus its 2× and 4× multiples, i.e. bars) is strongest under a prior around opt.center BPM; then a fine
     search ±0.6 BPM over 16 beats; rounded to 0.5 and snapped to a whole BPM when within 0.3. */
  function tempo(x, sr, opt) {
    opt = opt || {};
    var d = decimate(x, sr, 22050); x = d.x; sr = d.sr;
    var max = (opt.sec || 100) * sr; if (x.length > max) { var st = Math.floor((x.length - max) * (x.length > 150 * sr ? 0.35 : 0.5)); x = x.subarray(st, st + max); }
    var N = 1024, H = opt.H || 256, w = new Float32Array(N), nb = N / 2, i, f, frames = Math.floor((x.length - N) / H);
    for (i = 0; i < N; i++) w[i] = 0.5 - 0.5 * Math.cos(2 * Math.PI * i / (N - 1));
    if (frames < 64) return { bpm: 0, conf: 0 };
    var prev = new Float32Array(nb), env = new Float32Array(frames), re = new Float32Array(N), im = new Float32Array(N), maxBin = Math.floor(8000 / (sr / N));
    for (f = 0; f < frames; f++) {
      var o = f * H; for (i = 0; i < N; i++) { re[i] = x[o + i] * w[i]; im[i] = 0; }
      fft(re, im);
      var flux = 0; for (i = 1; i < maxBin; i++) { var mag = Math.log(1 + 100 * Math.sqrt(re[i] * re[i] + im[i] * im[i])), dd = mag - prev[i]; if (dd > 0) flux += dd * (i < 12 ? (opt.kick || 2) : 1); prev[i] = mag; }
      env[f] = flux;
    }
    var fps = sr / H, win = Math.round(fps * 0.4), cs = new Float64Array(frames + 1), e2 = new Float32Array(frames);
    for (f = 0; f < frames; f++) cs[f + 1] = cs[f] + env[f];
    for (f = 0; f < frames; f++) { var a0 = Math.max(0, f - win), b0 = Math.min(frames, f + win + 1), m = (cs[b0] - cs[a0]) / (b0 - a0); e2[f] = Math.max(0, env[f] - m); }
    function ac(lag) { var s = 0, L = Math.floor(lag), fr = lag - L; for (var t = 0; t + L + 1 < frames; t++) s += e2[t] * (e2[t + L] * (1 - fr) + e2[t + L + 1] * fr); return s; }
    var center = opt.center || 125, sd = opt.sd || 0.45, best = 0, bestBpm = 0, sc = [];
    for (var bpm = 60; bpm <= 200; bpm += 0.25) {
      var lag = 60 * fps / bpm, s = ac(lag) + 0.5 * ac(2 * lag) + 0.25 * ac(4 * lag);
      var pr = Math.exp(-0.5 * Math.pow(Math.log2(bpm / center) / sd, 2)), v = s * pr; sc.push(v);
      if (v > best) { best = v; bestBpm = bpm; }
    }
    var srt = sc.slice().sort(function (p, q) { return p - q; }), conf = best / (srt[srt.length >> 1] || 1);
    var fb = bestBpm, fs = -1;
    for (var bb = bestBpm - 0.6; bb <= bestBpm + 0.6; bb += 0.02) { var lg = 60 * fps / bb, ss = 0; for (var kk = 1; kk <= 16; kk *= 2) ss += ac(kk * lg) / Math.sqrt(kk); if (ss > fs) { fs = ss; fb = bb; } }
    var r = Math.round(fb * 2) / 2; if (Math.abs(fb - Math.round(fb)) < 0.3) r = Math.round(fb);
    return { bpm: r, raw: +fb.toFixed(2), conf: +conf.toFixed(2) };
  }

  /* ---------- fingerprint (copy protection, owner 19.11 «защиту от копий делай») ----------
     Every ~0.19 s: the two strongest notes (100–2000 Hz) → a code 0..143. Three codes in a row (t, t+2, t+4) make one hash.
     The set of hashes survives re-encoding, a lower bitrate, a different loudness and cutting the start; two different
     tracks share few of them. Returned as a sorted array of unique numbers (at most 3000). */
  function fingerprint(x, sr) {
    var d = decimate(x, sr, 11025); x = d.x; sr = d.sr;
    var N = 4096, H = 2048, i, j, f, win = new Float32Array(N);
    for (i = 0; i < N; i++) win[i] = 0.5 - 0.5 * Math.cos(2 * Math.PI * i / (N - 1));
    var lo = Math.floor(100 * N / sr), hi = Math.ceil(2000 * N / sr), pcOf = new Int8Array(hi + 1);
    for (j = lo; j <= hi; j++) pcOf[j] = ((Math.round(69 + 12 * Math.log2(j * sr / N / 440)) % 12) + 12) % 12;
    var re = new Float32Array(N), im = new Float32Array(N), codes = [];
    var frames = Math.floor((x.length - N) / H);
    for (f = 0; f < frames; f++) {
      var o = f * H, e = 0; for (i = 0; i < N; i++) { var v = x[o + i]; re[i] = v * win[i]; im[i] = 0; e += v * v; }
      if (e / N < 1e-7) { codes.push(-1); continue; } /* silence */
      fft(re, im);
      var c = new Float64Array(12); for (j = lo; j <= hi; j++) c[pcOf[j]] += Math.sqrt(re[j] * re[j] + im[j] * im[j]);
      var a = 0, b = 1; if (c[b] > c[a]) { a = 1; b = 0; }
      for (i = 2; i < 12; i++) { if (c[i] > c[a]) { b = a; a = i; } else if (c[i] > c[b]) b = i; }
      codes.push(a * 12 + b);
    }
    var set = {}, out = [];
    for (f = 0; f + 4 < codes.length; f++) { if (codes[f] < 0 || codes[f + 2] < 0 || codes[f + 4] < 0) continue; set[codes[f] * 20736 + codes[f + 2] * 144 + codes[f + 4]] = 1; }
    for (var k in set) out.push(+k);
    out.sort(function (p, q) { return p - q; });
    if (out.length > 3000) { var st = out.length / 3000, s2 = []; for (i = 0; i < 3000; i++) s2.push(out[Math.floor(i * st)]); out = s2; }
    return out;
  }
  /* share of the smaller set found in the other one (0..1) */
  function fpMatch(a, b) {
    var i = 0, j = 0, n = 0; while (i < a.length && j < b.length) { if (a[i] === b[j]) { n++; i++; j++; } else if (a[i] < b[j]) i++; else j++; }
    return Math.min(a.length, b.length) ? n / Math.min(a.length, b.length) : 0;
  }

  /* ---------- the vote (owner 19.11: «по многим способам определишь среднее») ----------
     Two ways of hearing (full range with the bass / without the bass) × four key profiles (classical listening tests,
     scale statistics, pop, electronic) = 8 voters, plus the bass listener (tonic it rests on + minor/major by the third)
     with double weight. The key with most votes wins; confidence = share of the votes. */
  function best(ch, prof) {
    var b = null, i, m, r;
    for (i = 0; i < 12; i++) for (m = 0; m < 2; m++) {
      var rot = []; for (r = 0; r < 12; r++) rot.push(ch[(r + i) % 12]);
      var s = corr(rot, prof[m]); if (!b || s > b.s) b = { t: i, m: m, s: s };
    }
    return b;
  }
  function vote(x, sr, opt) {
    opt = opt || {};
    var P = { kk: profiles({ prof: 'kk' })[0], tp: profiles({ prof: 'tp' })[0], bg: profiles({ prof: 'bg' })[0], ed: profiles({ prof: 'ed' })[0] };
    var hear = [chroma(x, sr, {}), chroma(x, sr, { lo: 200 })], votes = {}, total = 0, who = [];
    function add(t, m, w, by) { var k = NAMES[t] + (m ? 'm' : ''); votes[k] = (votes[k] || 0) + w; total += w; who.push(by + ':' + k); }
    hear.forEach(function (ch, hi) { if (!ch) return; Object.keys(P).forEach(function (p) { var b = best(ch, P[p]); add(b.t, b.m, 1, (hi ? 'nobass-' : 'full-') + p); }); });
    var bl = bassline(x, sr, opt), ch0 = hear[0];
    if (bl.hist && bl.held >= 6 && ch0) {
      var t = 0; for (var i = 1; i < 12; i++) if (bl.hist[i] > bl.hist[t]) t = i;
      var minor = ch0[(t + 3) % 12] + ch0[(t + 8) % 12] + ch0[(t + 10) % 12], major = ch0[(t + 4) % 12] + ch0[(t + 9) % 12] + ch0[(t + 11) % 12];
      add(t, minor >= major ? 1 : 0, 2, 'bass');
    }
    var ks = Object.keys(votes).sort(function (a, b) { return votes[b] - votes[a]; });
    if (!ks.length) return null;
    var k = ks[0], m = /m$/.test(k), tn = k.replace(/m$/, ''), ti = NAMES.indexOf(tn), rel = NAMES[m ? (ti + 3) % 12 : (ti + 9) % 12] + (m ? '' : 'm');
    return { key: k, tonic: tn, mode: m ? 'minor' : 'major', confidence: +(votes[k] / total).toFixed(2), alt: rel,
      alt_close: votes[rel] ? +((votes[k] - votes[rel]) / total).toFixed(2) : 1, flat: (FLAT[tn] || tn) + (m ? 'm' : ''),
      votes: votes, voters: who, chroma: ch0 };
  }

  function key(x, sr, opt) {
    if (!opt || !opt.single) return vote(x, sr, opt);
    opt = opt || {}; var ch = chroma(x, sr, opt); if (!ch) return null; var PROF = profiles(opt);
    if (opt.thr) { var mx0 = Math.max.apply(null, ch); ch = ch.map(function (v) { return v < mx0 * opt.thr ? 0 : v; }); }
    var sc = [], i, m, p, r;
    for (i = 0; i < 12; i++) for (m = 0; m < 2; m++) {
      var rot = []; for (r = 0; r < 12; r++) rot.push(ch[(r + i) % 12]);
      var s = 0; for (p = 0; p < PROF.length; p++) s += corr(rot, PROF[p][m]);
      sc.push({ t: i, m: m, s: s / PROF.length });
    }
    /* tonic first, like a listener: the note the bass holds longest pulls its keys up (bw = how much we trust the bass) */
    var bw = opt.bw == null ? 0.35 : opt.bw;
    if (bw > 0) {
      var bl = bassline(x, sr, opt);
      if (bl.hist && bl.held >= 6) {
        var bmax = Math.max.apply(null, bl.hist);
        sc.forEach(function (c) { c.s += bw * (bl.hist[c.t] / bmax - 0.5); });
      }
    }
    sc.sort(function (a, b) { return b.s - a.s; });
    var top = sc[0], rel = top.m ? (top.t + 3) % 12 : (top.t + 9) % 12;
    var other = sc.filter(function (c) { return !(c.t === top.t && c.m === top.m) && !(c.t === rel && c.m !== top.m); })[0];
    var relS = sc.filter(function (c) { return c.t === rel && c.m !== top.m; })[0];
    var conf = Math.max(0, Math.min(1, (top.s - other.s) / 0.25)) * Math.max(0, Math.min(1, top.s / 0.6));
    var name = NAMES[top.t] + (top.m ? 'm' : ''), altName = NAMES[rel] + (top.m ? '' : 'm');
    return { key: name, tonic: NAMES[top.t], mode: top.m ? 'minor' : 'major', confidence: +conf.toFixed(2), score: +top.s.toFixed(3),
      alt: altName, alt_close: relS ? +(top.s - relS.s).toFixed(3) : 1, flat: (FLAT[NAMES[top.t]] || NAMES[top.t]) + (top.m ? 'm' : ''), chroma: ch };
  }

  /* ---------- loudness (ITU BS.1770 K-weighting, 400 ms blocks, absolute + relative gating) ---------- */
  function biquad(x, b0, b1, b2, a1, a2) { var y = new Float32Array(x.length), x1 = 0, x2 = 0, y1 = 0, y2 = 0, i, v; for (i = 0; i < x.length; i++) { v = b0 * x[i] + b1 * x1 + b2 * x2 - a1 * y1 - a2 * y2; x2 = x1; x1 = x[i]; y2 = y1; y1 = v; y[i] = v; } return y; }
  function kweight(x, sr) {
    var f0 = 1681.974450955533, G = 3.999843853973347, Q = 0.7071752369554196, K = Math.tan(Math.PI * f0 / sr), Vh = Math.pow(10, G / 20), Vb = Math.pow(Vh, 0.4996667741545416), a0 = 1 + K / Q + K * K;
    x = biquad(x, (Vh + Vb * K / Q + K * K) / a0, 2 * (K * K - Vh) / a0, (Vh - Vb * K / Q + K * K) / a0, 2 * (K * K - 1) / a0, (1 - K / Q + K * K) / a0);
    f0 = 38.13547087602444; Q = 0.5003270373238773; K = Math.tan(Math.PI * f0 / sr); a0 = 1 + K / Q + K * K;
    return biquad(x, 1, -2, 1, 2 * (K * K - 1) / a0, (1 - K / Q + K * K) / a0);
  }
  function loudness(chs, sr) { /* chs: array of Float32Array (1 or 2 channels) */
    var kw = chs.map(function (c) { return kweight(c, sr); }), n = chs[0].length, B = Math.round(0.4 * sr), Hs = Math.round(0.1 * sr), z = [], i, j, s, peak = 0;
    chs.forEach(function (c) { for (i = 0; i < n; i++) { var a = Math.abs(c[i]); if (a > peak) peak = a; } });
    for (i = 0; i + B <= n; i += Hs) { s = 0; kw.forEach(function (c) { for (j = i; j < i + B; j++) s += c[j] * c[j]; }); z.push(s / B); }
    var L = function (v) { return -0.691 + 10 * Math.log10(v + 1e-20); };
    var abs = z.filter(function (v) { return L(v) > -70; }); if (!abs.length) return { lufs: -70, peak_db: -Infinity };
    var m = abs.reduce(function (a, b) { return a + b; }, 0) / abs.length, rel = abs.filter(function (v) { return L(v) > L(m) - 10; });
    var I = L(rel.reduce(function (a, b) { return a + b; }, 0) / rel.length);
    return { lufs: +I.toFixed(1), peak_db: +(20 * Math.log10(peak + 1e-20)).toFixed(1) };
  }

  function analyze(buf) {
    var chs = []; for (var c = 0; c < Math.min(2, buf.numberOfChannels); c++) chs.push(buf.getChannelData(c));
    return { key: key(mono(buf), buf.sampleRate), loudness: loudness(chs, buf.sampleRate), duration: buf.duration };
  }

  var API = { key: key, vote: vote, tempo: tempo, fingerprint: fingerprint, fpMatch: fpMatch, chroma: chroma, bassline: bassline, loudness: loudness, analyze: analyze, mono: mono, version: '1.0' };
  if (typeof module === 'object' && module.exports) module.exports = API; else root.SSAudio = API;
})(typeof window !== 'undefined' ? window : this);
