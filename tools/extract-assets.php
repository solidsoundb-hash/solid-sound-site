<?php
/**
 * Build step: moves large static <style>/<script> blocks out of a PHP file into
 * separate asset files. Each block is replaced, at the same place and under the
 * same PHP conditions, by a call that prints a cached <link>/<script src>.
 * Behaviour is unchanged (same order, same pages, sync scripts); only delivery changes.
 *
 * Usage: php extract-assets.php <in.php> <out.php> <raw-assets-dir> [min-bytes]
 * Writes <raw-assets-dir>/manifest.json (name => {type, bytes}).
 */
[$_, $in, $out, $dir] = $argv; $min = (int)($argv[4] ?? 800);
$src = file_get_contents($in);
$tokens = token_get_all($src);
@mkdir($dir, 0777, true);
$manifest = []; $used = []; $res = '';
$slug = function ($s) { return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($s)), '-'); };
foreach ($tokens as $t) {
  if (!is_array($t) || $t[0] !== T_INLINE_HTML) { $res .= is_array($t) ? $t[1] : $t; continue; }
  $html = $t[1]; $line = $t[2];
  $html = preg_replace_callback(
    '#<(style|script)\b([^>]*)>(.*?)</\1>#s',
    function ($m) use (&$manifest, &$used, $dir, $min, $line, $slug) {
      [$all, $tag, $attrs, $body] = $m;
      if (strlen($body) < $min) return $all;
      if ($tag === 'script') {
        if (preg_match('/\bsrc\s*=/i', $attrs)) return $all;
        if (preg_match('/\btype\s*=\s*["\']?([^"\'\s>]+)/i', $attrs, $ty) && !preg_match('#javascript|module#i', $ty[1])) return $all;
        if (preg_match('/\btype\s*=\s*["\']?module/i', $attrs)) return $all;
      }
      if (preg_match('/\bmedia\s*=/i', $attrs)) return $all;
      $id = preg_match('/\bid\s*=\s*["\']([^"\']+)/', $attrs, $mm) ? $mm[1] : null;
      $name = $slug($id ?: ($tag === 'style' ? 'css' : 'js') . '-l' . $line);
      $base = $name; $k = 2; while (isset($used[$name])) $name = $base . '-' . $k++;
      $used[$name] = 1;
      $ext = $tag === 'style' ? 'css' : 'js';
      file_put_contents("$dir/$name.$ext", $body);
      $manifest[$name] = ['type' => $ext, 'bytes' => strlen($body), 'id' => $id, 'line' => $line];
      return "<?php ss_asset('" . $name . "'); ?>";
    }, $html);
  $res .= $html;
}
file_put_contents($out, $res);
file_put_contents("$dir/manifest.json", json_encode($manifest, JSON_PRETTY_PRINT));
fprintf(STDERR, "extracted %d blocks, %d KB\n", count($manifest), array_sum(array_column($manifest, 'bytes')) / 1024);
