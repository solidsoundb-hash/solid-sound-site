<?php
/**
 * Smart search (owner 19.11.2026: «давай 1 2 8, но если можно кодом — пиши кодом, чтобы быть максимально автономным»).
 *
 * Works in three layers; every layer is optional except the first, so search never breaks:
 *  1. CODE (always): the query is split into filters (type, genre, BPM, key, Ableton version, price, free, plugins,
 *     mood, studio services) by dictionaries in EN/UA/RU/DE/FR/ES/IT/PT, then a word search with typo tolerance
 *     (BM25-like, title > tags > text).
 *  2. MEANING (Cloudflare bge-m3, multilingual): every product gets a "meaning fingerprint" once (cron, on product save);
 *     the query fingerprint is cached for a week. Finds "sad piano for a documentary" without shared words.
 *  3. LLM (Cloudflare llama, rare): only for long free-form phrases where the code found almost nothing; cached 30 days.
 * Daily caps keep the free Cloudflare plan safe; past the cap layers 2–3 switch off until tomorrow, layer 1 keeps working.
 * Same fingerprints drive "Similar sounds" on the product page (similar.php).
 *
 * REST: GET /wp-json/ss/v1/find?q=…  → {items:[…], svcs:[…], f:{…}, ai:{sem,llm}}
 * Off switch: option ss_ssx_off = 1 (search falls back to the old in-browser ranking).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

const SS_SSX_EMB_MODEL = '@cf/baai/bge-m3';
const SS_SSX_LLM_MODEL = '@cf/meta/llama-3.1-8b-instruct';
const SS_SSX_CAP_EMB   = 4000; /* query fingerprints a day (~0.02 neuron each) */
const SS_SSX_CAP_LLM   = 150;  /* LLM parses a day (~12 neurons each) */

function ss_ssx_on() { return ! get_option( 'ss_ssx_off' ); }

/* ---------- text helpers ---------- */
function ss_ssx_low( $s ) { return mb_strtolower( (string) $s, 'UTF-8' ); }
function ss_ssx_tokens( $s ) {
	$s = ss_ssx_low( $s );
	$s = preg_replace( '/[^\p{L}\p{N}#]+/u', ' ', $s );
	$out = array();
	foreach ( preg_split( '/\s+/u', trim( $s ) ) as $w ) {
		if ( mb_strlen( $w ) < 2 || isset( ss_ssx_stop()[ $w ] ) ) continue;
		$out[] = ss_ssx_stem( $w );
	}
	return $out;
}
/* light stemmer: plural/case endings in EN and Slavic, enough for "presets/пресеты/пресетів" → one token */
function ss_ssx_stem( $w ) {
	if ( mb_strlen( $w ) <= 4 ) return $w;
	if ( preg_match( '/\p{Cyrillic}/u', $w ) ) return preg_replace( '/(ами|ями|ов|ев|ів|ей|ам|ям|ах|ях|ою|ею|ий|ый|ой|ая|яя|ое|ее|ые|ие|ую|юю|а|я|ы|и|і|у|ю|е|о)$/u', '', $w );
	return preg_replace( '/(ies|es|s)$/', '', $w );
}
function ss_ssx_stop() {
	static $s = null;
	if ( null === $s ) {
		$s = array_flip( preg_split( '/\s+/', 'a an the and or for with like me my i need want some sound sounds track tracks song songs music something make get from in of to by is it on at this that please want looking find search
			и в на с для мне нужно хочу что то как по из у к а або чи мені потрібно треба шукаю знайди найди хочу щось що з до від для
			der die das und mit für ich ein eine le la les et pour avec un une el los las y con para una il lo gli e per con uno o com para um uma' ) );
	}
	return $s;
}
function ss_ssx_lev_ok( $a, $b ) {
	$la = mb_strlen( $a ); $lb = mb_strlen( $b );
	if ( $la < 5 || abs( $la - $lb ) > 2 ) return false;
	$max = $la >= 8 ? 2 : 1;
	if ( preg_match( '/[^\x00-\x7f]/', $a . $b ) ) { /* levenshtein() is byte-based: compare via a 1-byte map */
		$map = array(); $enc = function ( $s ) use ( &$map ) { $o = ''; foreach ( preg_split( '//u', $s, -1, PREG_SPLIT_NO_EMPTY ) as $c ) { if ( ! isset( $map[ $c ] ) ) $map[ $c ] = chr( 33 + count( $map ) % 200 ); $o .= $map[ $c ]; } return $o; };
		$a = $enc( $a ); $b = $enc( $b );
	}
	return levenshtein( $a, $b ) <= $max;
}

/* ---------- 1. CODE: query → filters ---------- */
function ss_ssx_dict() {
	return array(
		'genre' => array(
			'melodic techno' => 'melodic|мелодичн|мелодійн|afterlife|anyma|tale of us|artbat|massano|innellea|mathame|camelphat|monolink|stephan bodzin|колыбел',
			'dark techno'    => 'dark|темн|тёмн|industrial|индастри|індастрі|raw|hard techno|warehouse|hypnotic|гипнот|гіпнот|amelie lens|i hate models|charlotte de witte|adam beyer|kobosil|vtss|sara landry',
			'peak time techno' => 'peak|driving|драйв|maggiorana|enrico sangiuliano|reinier zonneveld',
			'techno'         => 'techno|техно|tekno|technó',
			'tech house'     => 'tech house|техх?аус|тех хаус|fisher|chris lake|john summit|mau p|groove|грув',
			'house'          => 'house|хаус|deep house|дип хаус',
			'progressive'    => 'progressive|прогрес',
			'trance'         => 'trance|транс',
			'drum and bass'  => 'dnb|d&b|drum and bass|drum n bass|драм|днб',
			'hip hop'        => 'hip hop|hiphop|хип|хіп|trap|трэп|треп|rap|рэп|реп|boom bap',
			'cinematic'      => 'cinematic|film|movie|кино|кіно|фильм|фільм|documentary|документ|trailer|трейлер|epic|эпич|епіч|soundtrack|саундтрек|filmmusik|cinéma|cine',
			'piano'          => 'piano|пиан|піан|фортеп|klavier',
			'ambient'        => 'ambient|эмбиент|ембієнт|амбиент|chill|чил|lofi|lo fi|лофи',
			'pop'            => 'pop|поп',
		),
		'type' => array(
			'templates'    => 'template|project|als\b|шаблон|проект|projekt|vorlage|plantilla|proyecto|modello|progetto|modèle|projet|modelo',
			'presets'      => 'preset|пресет|patch|патч|serum|vital|sylenth|massive|diva|pigments|omnisphere|voreinstellung',
			'music'        => 'music for|background music|stock music|royalty|роялти|роялті|ліценз|лиценз|музыка для|музика для|музыку для|музику для|для видео|для відео|for video|for youtube|для ютуб|for ads|для реклам',
			'sample-packs' => 'sample|семпл|сэмпл|loop|луп|kit|кит|one shot|ваншот',
		),
		'svc' => array( /* index in ss_srch_services() */
			0 => 'mix|свед|свест|зведен|звести|mixdown|микс|мікс',
			1 => 'master|мастер|loudness|громк|гучн',
			2 => 'release|релиз|реліз|distribution|дистриб|spotify|beatport|label|лейбл',
			3 => 'remix|ремикс|ремікс',
			4 => 'remake|ремейк|recreate',
			5 => 'ghost|гост|написать трек|написати трек|write my track',
			6 => 'feedback|фидбек|фідбек|review|отзыв|відгук|разбор|розбір|coaching|lesson|урок|научи|навчи',
			7 => 'video|видео|відео|podcast|подкаст|post production|постпрод|озвуч|foley|sound design|саунд дизайн',
		),
		'mood' => array(
			'emotional reflective' => 'sad|груст|сумн|печал|emotional|эмоц|емоц|melanch|меланх|nostalg|ностал',
			'uplifting joyful positive' => 'happy|весел|радіс|радост|uplift|positive|позитив|joy|bright|светл|світл',
			'calm relaxing peaceful' => 'calm|спокой|спокій|relax|расслаб|розслаб|peace|мирн|soft|нежн|ніжн',
			'energetic driving' => 'energ|энерг|енерг|power|мощн|потужн|aggress|агресс|агрес|hard|жестк|жорстк',
			'dark hypnotic' => 'dark|темн|mystic|мистик|містик|hypno|гипно|гіпно',
		),
	);
}

function ss_ssx_parse( $q ) {
	$l = ' ' . ss_ssx_low( $q ) . ' ';
	$f = array( 'type' => '', 'genres' => array(), 'bpm' => 0, 'bmin' => 0, 'bmax' => 0, 'key' => '', 'abl' => 0, 'pmax' => 0, 'free' => false, 'native' => false, 'plugins' => array(), 'moods' => array(), 'svcs' => array() );
	$d = ss_ssx_dict();
	foreach ( $d['type'] as $k => $re ) { if ( preg_match( '/' . $re . '/u', $l ) ) { $f['type'] = $k; break; } }
	foreach ( $d['genre'] as $k => $re ) { if ( preg_match( '/' . $re . '/u', $l ) ) $f['genres'][] = $k; }
	if ( array_intersect( array( 'melodic techno', 'dark techno', 'peak time techno' ), $f['genres'] ) ) $f['genres'] = array_values( array_diff( $f['genres'], array( 'techno' ) ) );
	if ( in_array( 'tech house', $f['genres'], true ) ) $f['genres'] = array_values( array_diff( $f['genres'], array( 'house' ) ) );
	foreach ( $d['svc'] as $k => $re ) { if ( preg_match( '/' . $re . '/u', $l ) ) $f['svcs'][] = $k; }
	foreach ( $d['mood'] as $k => $re ) { if ( preg_match( '/' . $re . '/u', $l ) ) $f['moods'][] = $k; }
	/* "music for video" is music, not the video post-production service, unless they ask to mix/edit the video */
	if ( 'music' === $f['type'] ) $f['svcs'] = array_values( array_diff( $f['svcs'], array( 7 ) ) );
	/* BPM: "125-130", "125 to 130 bpm", "128 bpm", "128бпм", a bare 70..190 number that is not a price */
	if ( preg_match( '/(\d{2,3})\s*(?:-|–|to|до|bis|à|a)\s*(\d{2,3})\s*(?:bpm|бпм|уд)?/u', $l, $m ) && $m[1] >= 60 && $m[2] <= 200 && $m[1] < $m[2] ) {
		$f['bmin'] = (int) $m[1]; $f['bmax'] = (int) $m[2]; $f['bpm'] = (int) round( ( $m[1] + $m[2] ) / 2 );
	} elseif ( preg_match( '/(\d{2,3})\s*(?:bpm|бпм|уд)/u', $l, $m ) ) {
		$f['bpm'] = (int) $m[1];
	} elseif ( preg_match( '/(?<![\d$€£.,])(\d{2,3})(?![\d.,]*\s*(?:\$|€|£|usd|eur|грн|uah|руб|euro|евро|євро|dollar|долл|дол))/u', $l, $m ) && $m[1] >= 70 && $m[1] <= 190 && ! preg_match( '/(?:live|ableton|лайв|under|below|до|менее|меньше|less than)\s*' . $m[1] . '/u', $l ) ) {
		$f['bpm'] = (int) $m[1];
	}
	/* key: "A minor", "Am", "F#m", "ля минор", "key of C" */
	$sol = array( 'до' => 'c', 'ре' => 'd', 'ми' => 'e', 'фа' => 'f', 'соль' => 'g', 'сіль' => 'g', 'ля' => 'a', 'си' => 'b', 'сі' => 'b' );
	if ( preg_match( '/\s(до|ре|ми|фа|соль|сіль|ля|си|сі)(?:\s*(диез|дієз|бемоль))?\s*(минор|мінор|мажор)/u', $l, $m ) ) {
		$f['key'] = $sol[ $m[1] ] . ( empty( $m[2] ) ? '' : ( 'бемоль' === $m[2] ? 'b' : '#' ) ) . ( 'мажор' === $m[3] ? '' : 'm' );
	} elseif ( preg_match( '/[\s,(]([a-g])\s?(#|♯|b|♭|sharp|flat)?\s*(m|min|minor|moll|maj|major|dur)(?=[\s,)])/u', $l, $m ) || preg_match( '/key(?: of)?\s+([a-g])(#|b)?\s*(m|minor|major)?(?=[\s,)])/u', $l, $m ) ) {
		/* bare "am"/"em" is also an English word: accept it only written as "Am" or next to other music words */
		if ( ( $m[3] ?? '' ) === 'm' && empty( $m[2] ) && ! preg_match( '/(?<![a-z])' . strtoupper( $m[1] ) . 'm(?![a-z])/', $q ) && ! preg_match( '/bpm|бпм|techno|техно|house|хаус|key|template|шаблон|preset|пресет/u', $l ) ) $m = null;
		if ( $m ) {
		$acc = isset( $m[2] ) ? str_replace( array( '♯', 'sharp', '♭', 'flat' ), array( '#', '#', 'b', 'b' ), $m[2] ) : '';
		$min = isset( $m[3] ) && in_array( $m[3], array( 'm', 'min', 'minor', 'moll' ), true );
		$f['key'] = $m[1] . $acc . ( $min ? 'm' : '' );
		}
	}
	if ( preg_match( '/(?:ableton|live|лайв|аблетон)\s*(?:live\s*)?(9|10|11|12)\b/u', $l, $m ) ) $f['abl'] = (int) $m[1];
	if ( preg_match( '/(?:under|below|less than|cheaper than|до|менее|меньше|дешевле|дешевше|unter|moins de|menos de|sotto)\s*[$€£]?\s*(\d{1,4})/u', $l, $m ) ) $f['pmax'] = (int) $m[1];
	if ( preg_match( '/free|бесплат|безкошт|gratis|kostenlos|gratuit|gratuito|даром|халяв/u', $l ) ) $f['free'] = true;
	if ( preg_match( '/no (?:external |3rd party |third party )?plugins|without plugins|native|stock plugins|без плагин|без сторонн|без сторонніх|лише стандарт|только стандарт/u', $l ) ) $f['native'] = true;
	if ( preg_match_all( '/\b(serum|vital|sylenth|massive|diva|pigments|omnisphere|kontakt|fabfilter|valhalla|soundtoys)\b/u', $l, $m ) ) $f['plugins'] = array_values( array_unique( $m[1] ) );
	return $f;
}
function ss_ssx_signals( $f ) {
	return ( $f['type'] ? 1 : 0 ) + count( $f['genres'] ) + ( $f['bpm'] ? 1 : 0 ) + ( $f['key'] ? 1 : 0 ) + count( $f['moods'] ) + count( $f['svcs'] ) + ( $f['free'] ? 1 : 0 ) + ( $f['abl'] ? 1 : 0 );
}

/* ---------- daily caps ---------- */
function ss_ssx_cap( $kind, $use = false ) {
	$b = get_option( 'ss_ssx_budget', array() );
	$day = gmdate( 'Y-m-d' );
	if ( ! is_array( $b ) || ( $b['d'] ?? '' ) !== $day ) $b = array( 'd' => $day, 'emb' => 0, 'llm' => 0, 'err' => 0 );
	$cap = 'llm' === $kind ? SS_SSX_CAP_LLM : SS_SSX_CAP_EMB;
	if ( (int) $b['err'] >= 20 ) return false; /* Cloudflare keeps failing today (limit hit / key revoked) → stop asking */
	if ( (int) $b[ $kind ] >= $cap ) return false;
	if ( $use ) { $b[ $kind ]++; update_option( 'ss_ssx_budget', $b, false ); }
	return true;
}
function ss_ssx_fail() {
	$b = get_option( 'ss_ssx_budget', array() );
	if ( is_array( $b ) && ( $b['d'] ?? '' ) === gmdate( 'Y-m-d' ) ) { $b['err'] = (int) ( $b['err'] ?? 0 ) + 1; update_option( 'ss_ssx_budget', $b, false ); }
}
function ss_ssx_ai_ready() { return function_exists( 'ss_cf_ai' ) && get_option( 'ss_cf_account' ) && get_option( 'ss_cf_token' ); }

/* ---------- 2. MEANING: fingerprints ---------- */
function ss_ssx_embed( $texts, $timeout = 20 ) {
	if ( ! ss_ssx_ai_ready() ) return null;
	$r = ss_cf_ai( SS_SSX_EMB_MODEL, array( 'text' => array_values( $texts ) ), $timeout );
	if ( is_wp_error( $r ) ) { ss_ssx_fail(); return null; }
	$rows = isset( $r['data'] ) ? $r['data'] : ( isset( $r['response'] ) ? $r['response'] : null );
	if ( ! is_array( $rows ) || count( $rows ) !== count( $texts ) ) return null;
	return array_map( 'ss_ssx_norm', $rows );
}
function ss_ssx_norm( $v ) {
	$n = 0.0; foreach ( $v as $x ) $n += $x * $x;
	$n = $n > 0 ? 1 / sqrt( $n ) : 0;
	return array_map( function ( $x ) use ( $n ) { return $x * $n; }, $v );
}
function ss_ssx_pack( $v ) { return base64_encode( pack( 'g*', ...$v ) ); }
function ss_ssx_unpack( $s ) { return array_values( unpack( 'g*', base64_decode( $s ) ) ); }
function ss_ssx_dot( $a, $b ) { $s = 0.0; $n = min( count( $a ), count( $b ) ); for ( $i = 0; $i < $n; $i++ ) $s += $a[ $i ] * $b[ $i ]; return $s; }
function ss_ssx_vecs() {
	static $v = null;
	if ( null === $v ) {
		$v = array();
		$o = get_option( 'ss_ssx_vec', array() );
		if ( is_array( $o ) && ! empty( $o['v'] ) ) foreach ( $o['v'] as $id => $s ) $v[ (int) $id ] = ss_ssx_unpack( $s );
	}
	return $v;
}
function ss_ssx_qvec( $q ) {
	$k = 'ss_ssx_q_' . md5( ss_ssx_low( $q ) );
	$c = get_transient( $k );
	if ( is_string( $c ) ) return ss_ssx_unpack( $c );
	if ( ! ss_ssx_ai_ready() || ! ss_ssx_cap( 'emb' ) ) return null;
	ss_ssx_cap( 'emb', true );
	$r = ss_ssx_embed( array( $q ), 4 );
	if ( ! $r ) return null;
	set_transient( $k, ss_ssx_pack( $r[0] ), WEEK_IN_SECONDS );
	return $r[0];
}
/* cosine between two products (for "Similar sounds"); null when fingerprints are not there yet */
function ss_ssx_cos( $a, $b ) {
	$v = ss_ssx_vecs();
	return ( isset( $v[ $a ], $v[ $b ] ) ) ? ss_ssx_dot( $v[ $a ], $v[ $b ] ) : null;
}

/* ---------- 3. LLM: rare fallback parser ---------- */
function ss_ssx_llm( $q ) {
	$k = 'ss_ssx_l_' . md5( ss_ssx_low( $q ) );
	$c = get_transient( $k );
	if ( is_array( $c ) ) return $c;
	if ( ! ss_ssx_ai_ready() || ! ss_ssx_cap( 'llm' ) ) return null;
	ss_ssx_cap( 'llm', true );
	$sys = 'You turn a music-shop search (any language) into JSON. Shop sells Ableton templates, synth presets, royalty-free music, sample packs and studio services (mixing, mastering, feedback, ghost production). '
		. 'Reply with JSON only: {"type":"templates|presets|music|sample-packs|","genres":["…"],"bpm":0,"key":"","moods":["…"],"services":["mixing|mastering|release|remix|ghost|feedback|video"],"keywords":["3-6 English words"]}';
	$r = ss_cf_ai( SS_SSX_LLM_MODEL, array( 'max_tokens' => 140, 'temperature' => 0, 'messages' => array( array( 'role' => 'system', 'content' => $sys ), array( 'role' => 'user', 'content' => mb_substr( $q, 0, 300 ) ) ) ), 6 );
	if ( is_wp_error( $r ) ) { ss_ssx_fail(); return null; }
	$t = isset( $r['response'] ) ? ( is_array( $r['response'] ) ? wp_json_encode( $r['response'] ) : $r['response'] ) : '';
	if ( ! preg_match( '/\{.*\}/s', $t, $m ) ) return null;
	$j = json_decode( $m[0], true );
	if ( ! is_array( $j ) ) return null;
	set_transient( $k, $j, 30 * DAY_IN_SECONDS );
	return $j;
}
function ss_ssx_merge_llm( $f, $j ) {
	$types = array( 'templates', 'presets', 'music', 'sample-packs' );
	if ( ! $f['type'] && in_array( $j['type'] ?? '', $types, true ) ) $f['type'] = $j['type'];
	$gd = array_keys( ss_ssx_dict()['genre'] );
	foreach ( (array) ( $j['genres'] ?? array() ) as $g ) { $g = ss_ssx_low( $g ); if ( in_array( $g, $gd, true ) && ! in_array( $g, $f['genres'], true ) ) $f['genres'][] = $g; }
	if ( ! $f['bpm'] && (int) ( $j['bpm'] ?? 0 ) >= 60 && (int) $j['bpm'] <= 200 ) $f['bpm'] = (int) $j['bpm'];
	$sm = array( 'mixing' => 0, 'mastering' => 1, 'release' => 2, 'remix' => 3, 'ghost' => 5, 'feedback' => 6, 'video' => 7 );
	foreach ( (array) ( $j['services'] ?? array() ) as $s ) { if ( isset( $sm[ $s ] ) && ! in_array( $sm[ $s ], $f['svcs'], true ) ) $f['svcs'][] = $sm[ $s ]; }
	$f['extra'] = implode( ' ', array_map( 'strval', array_merge( (array) ( $j['moods'] ?? array() ), (array) ( $j['keywords'] ?? array() ) ) ) );
	return $f;
}

/* ---------- index (built by code; fingerprints added by cron) ---------- */
function ss_ssx_doc( $id ) {
	$p = wc_get_product( $id );
	if ( ! $p || ! $p->is_visible() ) return null;
	$tn = function ( $tax ) use ( $id ) { $t = get_the_terms( $id, $tax ); return ( $t && ! is_wp_error( $t ) ) ? wp_list_pluck( $t, 'name' ) : array(); };
	$content = get_post_field( 'post_content', $id );
	$body = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( get_post_field( 'post_excerpt', $id ) . ' ' . strip_shortcodes( $content ) ) ) );
	$bpm = 0; foreach ( $tn( 'pa_bpm' ) as $b ) { if ( preg_match( '/\d+/', $b, $m ) ) { $bpm = (int) $m[0]; break; } }
	$key = ''; foreach ( $tn( 'pa_root-key' ) as $k ) { $key = ss_ssx_low( str_replace( array( ' minor', ' major', 'min' ), array( 'm', '', 'm' ), $k ) ); break; }
	$ver = 0; foreach ( $tn( 'product-version' ) as $v ) { if ( preg_match( '/(9|10|11|12)/', $v, $m ) ) $ver = max( $ver, (int) $m[1] ); }
	$fmt = function_exists( 'ss_pd_format' ) ? ss_pd_format( $id ) : '';
	$mp3 = preg_match( '/https?:[^\s"\'<>]+?\.mp3/i', $content, $m ) ? $m[0] : '';
	$genres = $tn( 'product-genre' );
	$tags = array_merge( $tn( 'product_tag' ), $tn( 'product_cat' ), $tn( 'pa_genre' ), $tn( 'external-plugin' ) );
	$img = get_the_post_thumbnail_url( $id, 'woocommerce_thumbnail' );
	return array(
		'id' => $id, 'n' => $p->get_name(), 'u' => get_permalink( $id ), 'i' => $img ? $img : '', 'pr' => (float) $p->get_price(), 'ph' => html_entity_decode( wp_strip_all_tags( wc_price( $p->get_price(), array( 'decimals' => 0 ) ) ), ENT_QUOTES, 'UTF-8' ),
		'f' => $fmt, 'g' => $genres, 'tg' => $tags, 'b' => $bpm, 'k' => $key, 'v' => $ver, 'nat' => function_exists( 'ss_pd_native' ) ? (bool) ss_pd_native( $id ) : false,
		'mp3' => $mp3, 's' => (int) get_post_meta( $id, 'total_sales', true ),
		'tt' => ss_ssx_tokens( $p->get_name() ), 'tk' => ss_ssx_tokens( implode( ' ', array_merge( $genres, $tags, array( $fmt ) ) ) ), 'bt' => ss_ssx_tokens( mb_substr( $body, 0, 2500 ) ),
		'emb' => mb_substr( $p->get_name() . '. ' . $fmt . '. ' . implode( ', ', $genres ) . '. ' . implode( ', ', $tags ) . ( $bpm ? ". {$bpm} BPM" : '' ) . ( $key ? ". Key {$key}" : '' ) . '. ' . $body, 0, 1500 ),
	);
}
function ss_ssx_build() {
	$ids = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => 500, 'fields' => 'ids', 'lang' => '', 'suppress_filters' => false ) );
	$docs = array();
	foreach ( $ids as $id ) { $d = ss_ssx_doc( $id ); if ( $d ) $docs[ $id ] = $d; }
	$idx = array( 't' => time(), 'docs' => $docs );
	update_option( 'ss_ssx_idx', $idx, false );
	return $idx;
}
function ss_ssx_idx() {
	static $i = null;
	if ( null === $i ) {
		$i = get_option( 'ss_ssx_idx' );
		if ( ! is_array( $i ) || empty( $i['docs'] ) ) $i = ss_ssx_build();
	}
	return $i;
}
/* cron: rebuild the word index + fingerprint only new/changed products (one Cloudflare call per 40 products) */
function ss_ssx_refresh() {
	$idx = ss_ssx_build();
	if ( ! ss_ssx_ai_ready() ) return 'index only (AI not connected)';
	$o = get_option( 'ss_ssx_vec', array() );
	$o = is_array( $o ) ? $o + array( 'v' => array(), 'h' => array() ) : array( 'v' => array(), 'h' => array() );
	$todo = array();
	foreach ( $idx['docs'] as $id => $d ) { $h = md5( $d['emb'] ); if ( ( $o['h'][ $id ] ?? '' ) !== $h || empty( $o['v'][ $id ] ) ) $todo[ $id ] = $d['emb']; }
	foreach ( array_keys( $o['v'] ) as $id ) { if ( ! isset( $idx['docs'][ $id ] ) ) unset( $o['v'][ $id ], $o['h'][ $id ] ); }
	$done = 0;
	foreach ( array_chunk( $todo, 40, true ) as $chunk ) {
		$r = ss_ssx_embed( array_values( $chunk ), 40 );
		if ( ! $r ) break;
		$i = 0;
		foreach ( $chunk as $id => $t ) { $o['v'][ $id ] = ss_ssx_pack( $r[ $i++ ] ); $o['h'][ $id ] = md5( $t ); $done++; }
	}
	$o['t'] = time();
	update_option( 'ss_ssx_vec', $o, false );
	global $wpdb; /* "Similar sounds" lists use the new fingerprints */
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '\_transient\_ss\_sim\_%' OR option_name LIKE '\_transient\_timeout\_ss\_sim\_%'" );
	return $done . ' fingerprinted, ' . count( $o['v'] ) . '/' . count( $idx['docs'] ) . ' ready';
}
add_action( 'ss_ssx_cron', 'ss_ssx_refresh' );
add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'ss_ssx_daily' ) ) wp_schedule_event( time() + 600, 'daily', 'ss_ssx_daily' );
} );
add_action( 'ss_ssx_daily', 'ss_ssx_refresh' );
function ss_ssx_soon() { if ( ! wp_next_scheduled( 'ss_ssx_cron' ) ) wp_schedule_single_event( time() + 60, 'ss_ssx_cron' ); }
add_action( 'save_post_product', 'ss_ssx_soon' );
add_action( 'trashed_post', function ( $id ) { if ( 'product' === get_post_type( $id ) ) ss_ssx_soon(); } );

/* ---------- search ---------- */
function ss_ssx_search( $q, $limit = 12 ) {
	$q = trim( mb_substr( (string) $q, 0, 200 ) );
	$f = ss_ssx_parse( $q );
	$ai = array( 'sem' => false, 'llm' => false );
	$qt = ss_ssx_tokens( $q );
	if ( count( $qt ) >= 4 && ss_ssx_signals( $f ) < 2 ) {
		$j = ss_ssx_llm( $q );
		if ( $j ) { $f = ss_ssx_merge_llm( $f, $j ); $ai['llm'] = true; $qt = array_merge( $qt, ss_ssx_tokens( $f['extra'] ) ); }
	}
	foreach ( array_merge( $f['genres'], $f['moods'] ) as $x ) $qt = array_merge( $qt, ss_ssx_tokens( $x ) );
	$qt = array_values( array_unique( $qt ) );

	$idx = ss_ssx_idx(); $docs = $idx['docs']; $N = max( 1, count( $docs ) );
	/* document frequency for idf */
	$df = array();
	foreach ( $docs as $d ) foreach ( array_unique( array_merge( $d['tt'], $d['tk'], $d['bt'] ) ) as $t ) $df[ $t ] = ( $df[ $t ] ?? 0 ) + 1;
	$qv = null; $vecs = ss_ssx_vecs();
	if ( $vecs && $q !== '' ) { $qv = ss_ssx_qvec( $q ); $ai['sem'] = (bool) $qv; }
	elseif ( ! $vecs && ss_ssx_ai_ready() ) ss_ssx_soon();

	$res = array();
	foreach ( $docs as $id => $d ) {
		$lex = 0.0;
		foreach ( $qt as $t ) {
			$w = log( 1 + $N / ( 1 + ( $df[ $t ] ?? 0 ) ) ) + 0.3;
			if ( in_array( $t, $d['tt'], true ) ) { $lex += 3 * $w; continue; }
			if ( in_array( $t, $d['tk'], true ) ) { $lex += 2 * $w; continue; }
			if ( in_array( $t, $d['bt'], true ) ) { $lex += 1 * $w; continue; }
			foreach ( array( array( 'tt', 2 ), array( 'tk', 1.5 ), array( 'bt', 0.7 ) ) as $fz ) { /* prefix or one typo */
				foreach ( $d[ $fz[0] ] as $dt ) { if ( ( mb_strlen( $t ) >= 4 && 0 === mb_strpos( $dt, $t ) ) || ss_ssx_lev_ok( $t, $dt ) ) { $lex += $fz[1] * $w; continue 3; } }
			}
		}
		$sem = ( $qv && isset( $vecs[ $id ] ) ) ? ss_ssx_dot( $qv, $vecs[ $id ] ) : null;
		$bon = 0; $why = array(); $hard = false;
		$dg = array_map( 'ss_ssx_low', $d['g'] ); $dall = ss_ssx_low( implode( ' ', array_merge( $d['g'], $d['tg'], array( $d['n'] ) ) ) ) . ' ' . ss_ssx_low( implode( ' ', $d['bt'] ) );
		if ( $f['type'] ) { if ( $d['f'] === $f['type'] ) $bon += 6; else $bon -= 4; }
		foreach ( $f['genres'] as $g ) { if ( in_array( $g, $dg, true ) || false !== strpos( $dall, $g ) ) { $bon += 5; $why[] = ucwords( $g ); } elseif ( 'techno' !== $g && false !== strpos( $g, 'techno' ) && false !== strpos( $dall, 'techno' ) ) $bon += 1.5; }
		foreach ( $f['moods'] as $m ) { foreach ( explode( ' ', $m ) as $mw ) { if ( false !== strpos( $dall, ss_ssx_stem( $mw ) ) ) { $bon += 1.5; break; } } }
		if ( $f['bpm'] && $d['b'] ) {
			$dd = ( $f['bmin'] && $d['b'] >= $f['bmin'] && $d['b'] <= $f['bmax'] ) ? 0 : abs( $d['b'] - $f['bpm'] );
			$bon += $dd <= 1 ? 6 : ( $dd <= 3 ? 4 : ( $dd <= 8 ? 1 : -2 ) );
		}
		if ( $f['key'] && $d['k'] ) { if ( $d['k'] === $f['key'] ) { $bon += 3; } }
		if ( $f['abl'] && $d['v'] ) { $bon += $d['v'] <= $f['abl'] ? 1 : -3; }
		if ( $f['pmax'] && $d['pr'] > $f['pmax'] ) { $bon -= 5; $hard = true; }
		if ( $f['free'] && $d['pr'] > 0 ) $bon -= 2;
		if ( $f['native'] ) $bon += $d['nat'] ? 2 : -1;
		foreach ( $f['plugins'] as $pl ) { if ( false !== strpos( $dall, $pl ) ) $bon += 3; }
		$semS = null === $sem ? 0 : max( 0, ( $sem - 0.35 ) / 0.35 ) * 10; /* bge-m3 cosine ~0.35 noise … ~0.7 strong */
		$score = min( $lex, 14 ) + $semS + $bon + min( 1, $d['s'] / 50 );
		$match = $lex > 0.5 || $semS >= 2.5 || $bon >= 4;
		if ( $hard || $score <= 1 ) $match = false;
		if ( ! $why && $d['g'] ) $why[] = $d['g'][0];
		$fl = array( 'templates' => 'Template', 'presets' => 'Presets', 'music' => 'Music', 'sample-packs' => 'Samples' );
		if ( isset( $fl[ $d['f'] ] ) ) array_unshift( $why, $fl[ $d['f'] ] );
		if ( $d['b'] ) $why[] = $d['b'] . ' BPM';
		if ( $d['k'] ) $why[] = ucfirst( $d['k'] );
		if ( $d['nat'] && in_array( $d['f'], array( 'templates', 'presets' ), true ) ) $why[] = 'No external plugins';
		$res[] = array( 'id' => $id, 'sc' => round( $score, 2 ), 'm' => $match, 'why' => implode( ' · ', array_slice( array_unique( $why ), 0, 4 ) ) );
	}
	usort( $res, function ( $a, $b ) { return ( $b['m'] <=> $a['m'] ) ?: ( $b['sc'] <=> $a['sc'] ); } );
	$res = array_slice( $res, 0, $limit );
	/* studio services: dictionary hits + plain word hits */
	$svcs = array();
	if ( function_exists( 'ss_srch_services' ) ) {
		$all = ss_srch_services();
		foreach ( $f['svcs'] as $i ) if ( isset( $all[ $i ] ) ) $svcs[ $i ] = $all[ $i ];
		foreach ( $all as $i => $s ) { foreach ( $qt as $t ) { if ( mb_strlen( $t ) > 3 && false !== mb_strpos( ss_ssx_low( $s[0] . ' ' . $s[1] ), $t ) ) { $svcs[ $i ] = $s; break; } } }
	}
	return array( 'res' => $res, 'svcs' => array_values( $svcs ), 'f' => $f, 'ai' => $ai, 'docs' => $docs );
}
function ss_ssx_ids( $q ) {
	$r = ss_ssx_search( $q, 60 );
	return array_values( array_map( function ( $x ) { return $x['id']; }, array_filter( $r['res'], function ( $x ) { return $x['m']; } ) ) );
}

/* ---------- REST for the search bar ---------- */
add_action( 'rest_api_init', function () {
	register_rest_route( 'ss/v1', '/find', array(
		'methods' => 'GET', 'permission_callback' => '__return_true',
		'args' => array( 'q' => array( 'type' => 'string', 'required' => true ) ),
		'callback' => function ( WP_REST_Request $r ) {
			if ( ! ss_ssx_on() ) return new WP_Error( 'ss_off', 'off', array( 'status' => 503 ) );
			$ip = md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
			$n = (int) get_transient( 'ss_ssx_rl_' . $ip );
			if ( $n > 40 ) return new WP_Error( 'ss_slow', 'Too many searches, wait a minute.', array( 'status' => 429 ) );
			set_transient( 'ss_ssx_rl_' . $ip, $n + 1, MINUTE_IN_SECONDS );
			$q = sanitize_text_field( $r['q'] );
			$ck = 'ss_ssx_r_' . md5( ss_ssx_low( $q ) );
			$out = get_transient( $ck );
			if ( ! is_array( $out ) ) {
				$s = ss_ssx_search( $q, 6 );
				$items = array();
				foreach ( $s['res'] as $x ) {
					$d = $s['docs'][ $x['id'] ];
					$items[] = array( 'name' => $d['n'], 'url' => $d['u'], 'img' => $d['i'], 'price' => $d['ph'], 'mp3' => $d['mp3'], 'why' => $x['why'], 'sc' => $x['m'] ? max( 1, $x['sc'] ) : 0 );
				}
				$svcs = array_map( function ( $s ) { return array( 'name' => $s[0], 'url' => $s[2], 'price' => $s[3] ); }, $s['svcs'] );
				$out = array( 'items' => $items, 'svcs' => $svcs, 'f' => $s['f'], 'ai' => $s['ai'] );
				set_transient( $ck, $out, 15 * MINUTE_IN_SECONDS );
			}
			if ( ! array_filter( $out['items'], function ( $x ) { return $x['sc'] > 0; } ) ) ss_search_miss( $q );
			$res = rest_ensure_response( $out );
			$res->header( 'Cache-Control', 'public, max-age=300' );
			return $res;
		},
	) );
} );

/* ---------- admin: status + rebuild + try a query (WooCommerce → Solid Sound AI) ---------- */
add_action( 'ss_ai_admin_page_after', function () {
	if ( ! current_user_can( 'manage_woocommerce' ) ) return;
	$msg = '';
	if ( isset( $_POST['ss_ssx_rebuild'] ) && check_admin_referer( 'ss_ssx' ) ) $msg = 'Rebuilt: ' . esc_html( ss_ssx_refresh() );
	if ( isset( $_POST['ss_ssx_toggle'] ) && check_admin_referer( 'ss_ssx' ) ) { update_option( 'ss_ssx_off', ss_ssx_on() ? 1 : 0, false ); }
	$idx = get_option( 'ss_ssx_idx' ); $vec = get_option( 'ss_ssx_vec' ); $b = get_option( 'ss_ssx_budget', array() );
	echo '<hr><h2>Smart search</h2>' . ( $msg ? '<div class="notice notice-success"><p>' . $msg . '</p></div>' : '' );
	echo '<p>Status: <b>' . ( ss_ssx_on() ? 'on' : 'off (old search)' ) . '</b> · products indexed: <b>' . ( is_array( $idx ) ? count( $idx['docs'] ) : 0 ) . '</b> · meaning fingerprints: <b>' . ( is_array( $vec ) && ! empty( $vec['v'] ) ? count( $vec['v'] ) : 0 ) . '</b>'
		. ' · today: ' . (int) ( $b['emb'] ?? 0 ) . '/' . SS_SSX_CAP_EMB . ' query fingerprints, ' . (int) ( $b['llm'] ?? 0 ) . '/' . SS_SSX_CAP_LLM . ' AI parses' . ( (int) ( $b['err'] ?? 0 ) ? ', ' . (int) $b['err'] . ' errors' : '' ) . '</p>';
	echo '<form method="post">'; wp_nonce_field( 'ss_ssx' );
	echo '<button class="button" name="ss_ssx_rebuild" value="1">Rebuild index now</button> <button class="button" name="ss_ssx_toggle" value="1">' . ( ss_ssx_on() ? 'Turn off' : 'Turn on' ) . '</button></form>';
	$tq = isset( $_GET['ss_ssx_q'] ) ? sanitize_text_field( wp_unslash( $_GET['ss_ssx_q'] ) ) : '';
	echo '<form method="get" style="margin-top:12px"><input type="hidden" name="page" value="ss-ai"><input name="ss_ssx_q" class="regular-text" value="' . esc_attr( $tq ) . '" placeholder="Try a search, e.g. dark techno 130 like Anyma"> <button class="button">Try</button></form>';
	if ( $tq ) {
		$s = ss_ssx_search( $tq, 6 );
		echo '<p><code>' . esc_html( wp_json_encode( array_filter( $s['f'] ) ) ) . '</code> · meaning: ' . ( $s['ai']['sem'] ? 'yes' : 'no' ) . ' · AI parse: ' . ( $s['ai']['llm'] ? 'yes' : 'no' ) . '</p><ol>';
		foreach ( $s['res'] as $x ) echo '<li>' . ( $x['m'] ? '<b>' : '' ) . esc_html( $s['docs'][ $x['id'] ]['n'] ) . ( $x['m'] ? '</b>' : '' ) . ' — ' . esc_html( $x['sc'] . ' · ' . $x['why'] ) . '</li>';
		echo '</ol>';
		foreach ( $s['svcs'] as $sv ) echo '<p>Service: ' . esc_html( $sv[0] ) . '</p>';
	}
} );

/* ---------- «searched and found nothing» (28.11.2026, Analyst via «Задачи») ----------
 * Searches with 0 results from the search bar (/ss/v1/find) and the classic ?s= page. Stored: only the query text (≤ 60 chars,
 * e-mails, phone numbers and links cut out) and a count per day — no IP, nothing about the person. Last 30 days.
 * People only: robots, crawlers and admins are skipped. Option ss_search_miss {day: {query: count}}, read via /ss/v1/stats → «miss». */
function ss_search_miss( $q ) {
	if ( current_user_can( 'manage_options' ) || ( function_exists( 'ss_stats_is_bot' ) && ss_stats_is_bot() ) ) return;
	$q = preg_replace( array( '~\S+@\S+~', '~(https?://|www\.)\S+~i', '~\+?\d[\d\s().-]{6,}\d~' ), ' ', (string) $q );
	$q = trim( preg_replace( '~\s+~', ' ', mb_strtolower( wp_strip_all_tags( $q ) ) ) );
	if ( mb_strlen( $q ) < 3 ) return; /* «me», «a» while typing */
	$q = trim( mb_substr( $q, 0, 60 ) );
	$all = get_option( 'ss_search_miss', array() ); $all = is_array( $all ) ? $all : array();
	$day = gmdate( 'Y-m-d' );
	if ( isset( $all[ $day ] ) && count( $all[ $day ] ) >= 300 && ! isset( $all[ $day ][ $q ] ) ) return; /* a flood cannot grow the option */
	$all[ $day ][ $q ] = ( isset( $all[ $day ][ $q ] ) ? $all[ $day ][ $q ] : 0 ) + 1;
	ksort( $all ); $all = array_slice( $all, -30, null, true );
	update_option( 'ss_search_miss', $all, false );
}
add_action( 'template_redirect', function () {
	global $wp_query;
	if ( is_search() && ! is_admin() && $wp_query && 0 === (int) $wp_query->found_posts && ! get_query_var( 'paged' ) ) ss_search_miss( get_search_query( false ) );
} );
