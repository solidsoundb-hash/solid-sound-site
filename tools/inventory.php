<?php
// Inventory of a WordPress PHP file: each top-level function / hook registration,
// its line range, and how much inline <style>/<script> it prints.
$src = file_get_contents($argv[1]);
$t = token_get_all($src);
$n = count($t);
$line = fn($i) => is_array($t[$i]) ? $t[$i][2] : null;
// map named functions -> [start,end]
$funcs = []; $blocks = [];
$depth = 0; $cur = null;
for ($i = 0; $i < $n; $i++) {
  $tok = $t[$i];
  if (is_array($tok) && $tok[0] === T_FUNCTION && $depth === 0) {
    $j = $i + 1; while (is_array($t[$j]) && $t[$j][0] === T_WHITESPACE) $j++;
    $name = is_array($t[$j]) && $t[$j][0] === T_STRING ? $t[$j][1] : null;
    if ($name) $cur = ['name' => $name, 'start' => $tok[2], 'i' => $i];
  }
  if ($tok === '{' || (is_array($tok) && in_array($tok[0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES]))) $depth++;
  if ($tok === '}') { $depth--; if ($depth === 0 && $cur) { $cur['end'] = $i; $funcs[$cur['name']] = $cur; $cur = null; } }
}
// collect hook registrations at top level: add_action('hook', callback, prio)
$rows = [];
for ($i = 0; $i < $n; $i++) {
  if (!is_array($t[$i]) || $t[$i][0] !== T_STRING || !in_array($t[$i][1], ['add_action', 'add_filter', 'add_shortcode'])) continue;
  $j = $i + 1; while ($t[$j] !== '(') $j++;
  // first arg
  $k = $j + 1; while (is_array($t[$k]) && $t[$k][0] === T_WHITESPACE) $k++;
  $hook = is_array($t[$k]) ? trim($t[$k][1], "'\"") : '?';
  // find callback: either closure (function) or string name; and end of call
  $p = 1; $m = $j + 1; $cbStart = null; $cbName = null; $endI = null;
  for (; $m < $n; $m++) {
    $x = $t[$m];
    if ($x === '(' ) $p++;
    if ($x === ')') { $p--; if ($p === 0) { $endI = $m; break; } }
    if ($p === 1 && $x === ',' && $cbStart === null) $cbStart = $m + 1;
  }
  $cbText = ''; for ($q = $cbStart; $q < $endI; $q++) $cbText .= is_array($t[$q]) ? $t[$q][1] : $t[$q];
  if (preg_match("/^\s*['\"]([A-Za-z0-9_]+)['\"]/", $cbText, $mm)) { $cbName = $mm[1]; }
  if ($cbName && isset($funcs[$cbName])) { $f = $funcs[$cbName]; $body = ''; for ($q = $f['i']; $q <= $f['end']; $q++) $body .= is_array($t[$q]) ? $t[$q][1] : $t[$q]; $from = $f['start']; }
  else { $body = $cbText; $from = $t[$i][2]; $cbName = $cbName ?: 'closure'; }
  $css = 0; $js = 0;
  if (preg_match_all('#<style[^>]*>(.*?)</style>#s', $body, $a)) foreach ($a[1] as $s) $css += strlen($s);
  if (preg_match_all('#<script(?![^>]*\bsrc=)[^>]*>(.*?)</script>#s', $body, $a)) foreach ($a[1] as $s) $js += strlen($s);
  $cond = [];
  if (preg_match_all('/\b(is_front_page|is_home|is_page|is_singular|is_product|is_shop|is_product_category|is_cart|is_checkout|is_account_page|is_admin|is_user_logged_in|is_search|is_tax|is_404|is_wc_endpoint_url)\s*\(([^)]{0,40})\)/', $body, $a))
    foreach ($a[0] as $c) $cond[preg_replace('/\s+/', '', $c)] = 1;
  $intervals = substr_count($body, 'setInterval');
  $rows[] = sprintf("%5d %-34s %-24s css=%6d js=%6d int=%d %s", $from, substr($hook,0,34), substr($cbName,0,24), $css, $js, $intervals, substr(implode(' ', array_keys($cond)),0,90));
}
echo implode("\n", $rows), "\n";
echo "functions: ", count($funcs), "\n";
