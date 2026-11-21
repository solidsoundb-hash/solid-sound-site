"""Post-production before/after pairs from the owner's SoundCloud (old post.solid-sound.net) → home-page format
(owner 21.11.2026: «всё с кнопкой до/после, как на главной с музыкой»).

Each pair → one mp3 (before, then after, equal length) in plugin src/media/ab/<key>.mp3 and two waveform strings in
src/media/ab/peaks.json ([before, after], 56 bars each, chr(48 + level 1..40)) — exactly like the music examples.
Run (needs ffmpeg + yt-dlp): python3 tools/ab-sc.py
"""
import json, os, subprocess, tempfile

AB = 'plugin/solid-sound-custom/src/media/ab'
PAIRS = {  # key: (before track id, after track id)
    'post-voice-radio': ('419751680', '419751676'),
    'post-declip': ('423699429', '423695187'),
    'post-ad-voiceover': ('459976563', '459976653'),
    'post-cleanup': ('462271863', '462271899'),
    'post-dynamics': ('462895656', '462897129'),
}
SR, BARS = 44100, 56


def run(*a):
    subprocess.run(a, check=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)


def fetch(tid, d):
    out = os.path.join(d, tid + '.%(ext)s')
    run('yt-dlp', '-q', '-x', '--audio-format', 'wav', '-o', out, f'https://api.soundcloud.com/tracks/{tid}')
    return os.path.join(d, tid + '.wav')


def pcm(path, secs):
    raw = subprocess.run(['ffmpeg', '-v', 'error', '-i', path, '-t', str(secs), '-ac', '1', '-ar', '8000', '-f', 's16le', '-'], check=True, capture_output=True).stdout
    import array
    a = array.array('h'); a.frombytes(raw)
    return a


def bars(samples, peak):
    n = max(1, len(samples) // BARS); s = ''
    for i in range(BARS):
        seg = samples[i * n:(i + 1) * n] or [0]
        rms = (sum(x * x for x in seg) / len(seg)) ** 0.5
        s += chr(48 + max(1, min(40, round(rms / peak * 40))))
    return s


def dur(p):
    return float(subprocess.run(['ffprobe', '-v', 'error', '-show_entries', 'format=duration', '-of', 'csv=p=0', p], check=True, capture_output=True, text=True).stdout)


def main():
    pk_path = os.path.join(AB, 'peaks.json')
    peaks = json.load(open(pk_path))
    with tempfile.TemporaryDirectory() as d:
        for key, (b, a) in PAIRS.items():
            fb, fa = fetch(b, d), fetch(a, d)
            L = round(min(dur(fb), dur(fa)), 2)
            out = os.path.join(AB, key + '.mp3')
            run('ffmpeg', '-v', 'error', '-y', '-t', str(L), '-i', fb, '-t', str(L), '-i', fa,
                '-filter_complex', '[0:a]aresample=44100,aformat=channel_layouts=stereo[x];[1:a]aresample=44100,aformat=channel_layouts=stereo[y];[x][y]concat=n=2:v=0:a=1',
                '-b:a', '160k', out)
            sb, sa = pcm(fb, L), pcm(fa, L)
            peak = max(1, max((abs(x) for x in sb), default=1), max((abs(x) for x in sa), default=1)) * 0.6
            peaks[key] = [bars(sb, peak), bars(sa, peak)]
            print(key, L, 's each half', os.path.getsize(out) // 1024, 'KB')
    json.dump(peaks, open(pk_path, 'w'), separators=(',', ':'))


if __name__ == '__main__':
    main()
