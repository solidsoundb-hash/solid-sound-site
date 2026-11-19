<?php
/**
 * (15.11.2026 owner) AI tools in the creator's Add Product form (free Cloudflare Workers AI, key in WooCommerce -> Solid Sound AI):
 *  - "✨ Write with AI" next to Description: an English description from the facts in the form (name, format, genre, BPM, key,
 *    Live version, plugins, mood). The model is told to use only these facts. The creator edits the text before sending.
 *  - "✨ Make a cover with AI": a background from genre + mood (FLUX schnell), the product name and "Produced by …" are drawn on top
 *    in the browser (1200×1200 JPG) and put into the form as the cover. "Try again" / "Use this cover".
 * Daily limits per creator: 20 descriptions, 10 covers (the free plan is shared by everyone).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_aic_left( $kind, $use = false ) {
	$max = 'desc' === $kind ? 60 : 30; /* 16.11: each click asks for 3 variants (owner: let them choose) */
	$uid = get_current_user_id();
	$c = get_user_meta( $uid, 'ss_ai_count', true );
	$d = current_time( 'Y-m-d' );
	if ( ! is_array( $c ) || ( $c['d'] ?? '' ) !== $d ) $c = array( 'd' => $d, 'desc' => 0, 'img' => 0 );
	if ( $use ) { $c[ $kind ]++; update_user_meta( $uid, 'ss_ai_count', $c ); }
	return $max - (int) $c[ $kind ];
}
function ss_aic_guard( $kind ) {
	check_ajax_referer( 'ss_aic', 'n' );
	if ( ! function_exists( 'ss_cp_can' ) || ! ss_cp_can() ) wp_send_json_error( array( 'm' => 'Please log in as a creator.' ), 403 );
	if ( ! function_exists( 'ss_cf_ai' ) ) wp_send_json_error( array( 'm' => 'AI is not available.' ), 500 );
	if ( ss_aic_left( $kind ) <= 0 ) wp_send_json_error( array( 'm' => 'Daily AI limit reached — try again tomorrow.' ), 429 );
}
function ss_aic_facts() {
	$f = isset( $_POST['f'] ) ? json_decode( wp_unslash( (string) $_POST['f'] ), true ) : array();
	$out = array();
	foreach ( (array) $f as $k => $v ) {
		$k = sanitize_key( $k ); if ( ! $k ) continue;
		$v = is_array( $v ) ? implode( ', ', array_map( 'sanitize_text_field', array_slice( $v, 0, 12 ) ) ) : sanitize_text_field( (string) $v );
		if ( '' !== $v ) $out[ $k ] = mb_substr( $v, 0, 300 );
	}
	return $out;
}

add_action( 'wp_ajax_ss_aic_desc', function () {
	ss_aic_guard( 'desc' );
	$f = ss_aic_facts();
	if ( empty( $f['name'] ) ) wp_send_json_error( array( 'm' => 'Add the product name first.' ), 400 );
	if ( count( $f ) < 4 ) wp_send_json_error( array( 'm' => 'AI writes only from your data — first fill in the type, genre, BPM and key (and the DAW).' ), 400 );
	$facts = ''; foreach ( $f as $k => $v ) $facts .= ucfirst( str_replace( '_', ' ', $k ) ) . ': ' . $v . "\n";
	$style = isset( $_POST['style'] ) ? sanitize_key( $_POST['style'] ) : 'detailed';
	$shape = array(
		'short'    => "Write in English, 35-55 words: one short paragraph (what it is, the sound, who it is for), then a line 'What's inside:' and 3 bullet points starting with '- '.\n",
		'detailed' => "Write in English, 70-110 words: two short paragraphs (what it is and the sound / who it is for, then what the buyer learns or gets), then a line 'What's inside:' and 3-5 bullet points starting with '- '.\n",
		'story'    => "Write in English, 60-90 words, in a warm producer-to-producer voice: start with the vibe of the track and where it works (club, festival, late night), then what the buyer gets and learns. End with a line 'What's inside:' and 3-4 bullet points starting with '- '.\n",
	);
	$sys = "You write product descriptions for Solid Sound, a marketplace of Ableton Live templates, synth presets, sample packs and royalty-free music for producers and DJs.\n"
		. ( $shape[ $style ] ?? $shape['detailed'] )
		. "Build the description from ALL the facts given: mention the format, genre, BPM, key, DAW and version, plugins (or that no external plugins are needed), mood, length, channels and anything else listed, naturally, the way a producer would describe it. Use ONLY these facts. Never invent plugin names, numbers, artists, awards or contents that are not in the facts. If a fact is missing, leave it out.\n"
		. "Plain, confident producer language. No emojis, no hashtags, no words like ultimate, game-changer, unleash, elevate, next level. Do not repeat the product name more than twice. Output only the description.";
	$r = ss_cf_ai( '@cf/meta/llama-3.1-8b-instruct', array( 'messages' => array( array( 'role' => 'system', 'content' => $sys ), array( 'role' => 'user', 'content' => "Facts:\n" . $facts ) ), 'max_tokens' => 460, 'temperature' => 0.6 ) );
	if ( is_wp_error( $r ) ) wp_send_json_error( array( 'm' => 'AI is busy — please try again in a minute.' ), 502 );
	$t = trim( (string) ( $r['response'] ?? '' ) );
	$t = preg_replace( '/^(here is|here\'s)[^\n]*\n+/i', '', $t );
	if ( '' === $t ) wp_send_json_error( array( 'm' => 'Empty answer — please try again.' ), 502 );
	ss_aic_left( 'desc', true );
	wp_send_json_success( array( 'text' => $t, 'left' => ss_aic_left( 'desc' ) ) );
} );

/* 16.11: 5 name ideas to choose from (the creator taps one, then can edit it) */
add_action( 'wp_ajax_ss_aic_names', function () {
	ss_aic_guard( 'desc' );
	$f = ss_aic_facts();
	$facts = ''; foreach ( $f as $k => $v ) if ( 'price' !== $k ) $facts .= ucfirst( $k ) . ': ' . $v . "\n";
	if ( '' === trim( $facts ) ) wp_send_json_error( array( 'm' => 'Pick the format and genre first (or type a working name).' ), 400 );
	$sys = "You suggest product names for a marketplace of Ableton Live templates, presets, sample packs and music. Give exactly 5 short names (2-5 words each), one per line, no numbering, no quotes, no emojis. "
		. "Mix styles: clear genre + type (e.g. 'Melodic Techno Template'), evocative track-like titles, mood + genre, a short brandable name. English only. Never invent artist names. Output only the 5 lines.";
	$r = ss_cf_ai( '@cf/meta/llama-3.1-8b-instruct', array( 'messages' => array( array( 'role' => 'system', 'content' => $sys ), array( 'role' => 'user', 'content' => "Facts:\n" . $facts ) ), 'max_tokens' => 120, 'temperature' => 0.9 ) );
	if ( is_wp_error( $r ) ) wp_send_json_error( array( 'm' => 'AI is busy — please try again in a minute.' ), 502 );
	$out = array();
	foreach ( preg_split( '/\r?\n/', (string) ( $r['response'] ?? '' ) ) as $l ) {
		$l = trim( preg_replace( '/^\s*(\d+[.)]|[-*•])\s*/u', '', $l ), " \t\"'" );
		if ( '' !== $l && mb_strlen( $l ) <= 60 && ! preg_match( '/^(here|sure|names?:)/i', $l ) ) $out[] = sanitize_text_field( $l );
	}
	$out = array_slice( array_values( array_unique( $out ) ), 0, 5 );
	if ( ! $out ) wp_send_json_error( array( 'm' => 'Empty answer — please try again.' ), 502 );
	ss_aic_left( 'desc', true );
	wp_send_json_success( array( 'names' => $out, 'left' => ss_aic_left( 'desc' ) ) );
} );

/* 16.11: price hint from published products of the same format (no AI). The creator taps a price or types their own. */
add_action( 'wp_ajax_ss_aic_price', function () {
	check_ajax_referer( 'ss_aic', 'n' );
	if ( ! function_exists( 'ss_cp_can' ) || ! ss_cp_can() ) wp_send_json_error( array(), 403 );
	$fmt = isset( $_POST['fmt'] ) ? absint( $_POST['fmt'] ) : 0;
	$key = 'ss_aic_price_' . $fmt; $res = get_transient( $key );
	if ( false === $res ) {
		$args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 300, 'fields' => 'ids', 'no_found_rows' => true );
		if ( $fmt ) $args['tax_query'] = array( array( 'taxonomy' => 'product-format', 'field' => 'term_id', 'terms' => $fmt ) );
		$p = array();
		foreach ( get_posts( $args ) as $id ) { $v = (float) get_post_meta( $id, '_regular_price', true ); if ( $v > 0 ) $p[] = $v; }
		sort( $p ); $n = count( $p );
		$q = function ( $x ) use ( $p, $n ) { return $n ? $p[ min( $n - 1, (int) floor( $x * ( $n - 1 ) ) ) ] : 0; };
		$res = $n >= 3 ? array( 'n' => $n, 'low' => $q( .25 ), 'mid' => $q( .5 ), 'high' => $q( .75 ) ) : array( 'n' => $n );
		set_transient( $key, $res, 6 * HOUR_IN_SECONDS );
	}
	wp_send_json_success( $res );
} );

add_action( 'wp_ajax_ss_aic_cover', function () {
	ss_aic_guard( 'img' );
	$f = ss_aic_facts();
	$g = $f['genre'] ?? ''; $m = $f['mood'] ?? ''; $fmt = $f['format'] ?? '';
	$style = array( 'abstract', 'minimal geometric', 'cinematic', 'textured', 'neon' );
	$p = 'Album cover artwork background for ' . ( $g ? $g . ' ' : 'electronic ' ) . 'music' . ( $m ? ', mood: ' . $m : '' ) . ( $fmt ? ', ' . $fmt : '' )
		. ', ' . $style[ wp_rand( 0, count( $style ) - 1 ) ] . ' style, dramatic lighting, deep contrast, rich colors, square composition, empty space in the lower third for a title. No text, no letters, no words, no logos, no watermark, no people faces.';
	$r = ss_cf_ai( '@cf/black-forest-labs/flux-1-schnell', array( 'prompt' => $p, 'steps' => 6, 'seed' => wp_rand( 1, 999999 ) ), 60 );
	if ( is_wp_error( $r ) ) wp_send_json_error( array( 'm' => 'AI is busy — please try again in a minute.' ), 502 );
	$img = isset( $r['image'] ) ? (string) $r['image'] : '';
	if ( isset( $r['type'] ) ) $img = base64_encode( $img ); /* raw bytes answer */
	if ( strlen( $img ) < 1000 ) wp_send_json_error( array( 'm' => 'No image came back — please try again.' ), 502 );
	ss_aic_left( 'img', true );
	wp_send_json_success( array( 'img' => 'data:image/jpeg;base64,' . $img, 'left' => ss_aic_left( 'img' ) ) );
} );

add_action( 'wp_footer', function () {
	if ( ! is_user_logged_in() || ! function_exists( 'ss_cp_can' ) || ! ss_cp_can() ) return;
	if ( ! get_option( 'ss_cf_token' ) ) return;
	global $wp; if ( function_exists( 'is_wcfm_page' ) && is_wcfm_page() && ! array_key_exists( 'wcfm-ss-add-product', (array) $wp->query_vars ) ) return; /* only on Add Product */
	$u = wp_get_current_user();
	$artist = function_exists( 'ss_cp_prof' ) ? ( ss_cp_prof( $u->ID )['store_name'] ?? '' ) : '';
	if ( ! $artist ) $artist = $u->display_name;
	?>
<style id="ss-aic-css">
.ss-aic-b{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 14px;margin:8px 0 0;border:1px solid rgba(167,139,250,.5);border-radius:9px;background:linear-gradient(135deg,rgba(124,58,237,.25),rgba(245,158,11,.18));color:#fff;font:700 13.5px Roboto,sans-serif;cursor:pointer}
.ss-aic-b:hover{border-color:#a78bfa}.ss-aic-b[disabled]{opacity:.6;cursor:wait}
.ss-aic-n{margin:6px 0 0;color:#94a3b8;font-size:12.5px}.ss-aic-n.bad{color:#f87171}
.ss-aic-cv{display:none;gap:16px;align-items:flex-start;flex-wrap:wrap;margin:10px 0 0;padding:14px;border:1px solid rgba(255,255,255,.1);border-radius:12px;background:#0b1220}
.ss-aic-cv.on{display:flex}.ss-aic-cv canvas{width:240px;height:240px;border-radius:10px;flex:none;max-width:100%}
.ss-aic-cv .c{display:flex;flex-direction:column;gap:8px;min-width:200px;flex:1}
.ss-aic-cv label{font-size:12px;color:#94a3b8}.ss-aic-cv input{height:38px!important;min-height:0!important}
.ss-aic-cv .use{background:#f59e0b;color:#111;border:0;border-radius:9px;height:40px;font-weight:800;cursor:pointer}
.ss-aic-chips{display:flex;flex-wrap:wrap;gap:6px;margin:8px 0 0;align-items:center}.ss-aic-chips .l{color:#94a3b8;font-size:12.5px}
.ss-aic-c{border:1px solid rgba(148,163,184,.4);background:transparent;color:#e2e8f0;border-radius:999px;padding:5px 12px;font-size:13px;cursor:pointer}.ss-aic-c:hover{border-color:#f59e0b}.ss-aic-c.on{background:#f59e0b;border-color:#f59e0b;color:#111;font-weight:700}
.ss-aic-pick{display:none;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin:10px 0 0}.ss-aic-pick.on{display:grid}@media(max-width:800px){.ss-aic-pick.on{grid-template-columns:1fr}}
.ss-aic-card{display:flex;flex-direction:column;gap:8px;padding:12px;border:1px solid rgba(255,255,255,.12);border-radius:12px;background:#0b1220;color:#e2e8f0}.ss-aic-card.on{border-color:#f59e0b;box-shadow:0 0 0 1px #f59e0b}.ss-aic-card.bad{opacity:.6}
.ss-aic-card b{font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#a78bfa}.ss-aic-card .t{white-space:pre-wrap;font-size:13px;line-height:1.45;flex:1;max-height:260px;overflow:auto}
.ss-aic-card .use{background:#f59e0b;color:#111;border:0;border-radius:8px;height:34px;font-weight:800;cursor:pointer}.ss-aic-card .use[disabled]{opacity:.4;cursor:wait}
.ss-aic-cv .th{display:flex;gap:8px}.ss-aic-cv .th .tb{padding:0;border:2px solid transparent;border-radius:8px;background:none;cursor:pointer;width:64px;height:64px;overflow:hidden}.ss-aic-cv .th .tb.on{border-color:#f59e0b}.ss-aic-cv .th img{width:100%;height:100%;object-fit:cover;display:block}
</style>
<script>(function(){
var AJAX=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,N=<?php echo wp_json_encode( wp_create_nonce( 'ss_aic' ) ); ?>,ARTIST=<?php echo wp_json_encode( $artist ); ?>,CUR=<?php echo wp_json_encode( html_entity_decode( function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$' ) ); ?>;
function facts(f){var o={},q=function(s){return f.querySelector(s)};
 o.name=(q('[name=product_name]')||{}).value||'';
 function picked(cls){var w=q('.ss-ap-'+cls);if(!w)return '';var a=[];w.querySelectorAll('input:checked').forEach(function(i){var l=i.closest('label');a.push((l?l.textContent:i.value).trim())});w.querySelectorAll('select').forEach(function(s){[].forEach.call(s.selectedOptions||[],function(o){if(o.value)a.push(o.text.trim())})});w.querySelectorAll('input[type=text],input[type=number]').forEach(function(i){if(i.value)a.push(i.value)});return a.join(', ')}
 ['format','genre','daw','version','ext','mood','bpm','key','length','tag','dl'].forEach(function(k){var v=picked(k);if(v)o[k==='ext'?'plugins':(k==='dl'?'download':k)]=v});
 /* 19.11 owner («Аи описание должно учитывать все данные трека»): every other filled field of the form goes in too */
 var seen={};for(var k0 in o)seen[String(o[k0]).toLowerCase()]=1;
 f.querySelectorAll('.acf-field').forEach(function(fl){if(fl.offsetParent===null&&!fl.querySelector('input:checked'))return;var lb=fl.querySelector('.acf-label label');if(!lb)return;var L=lb.textContent.replace(/\*|\(.*?\)/g,'').replace(/\s+/g,' ').trim();if(!L||/descr|price|cover|image|file|zip|mp3|preview|demo|terms|agree/i.test(L))return;
  var a=[];fl.querySelectorAll('input:checked').forEach(function(i){var l=i.closest('label');a.push((l?l.textContent:i.value).trim())});fl.querySelectorAll('select').forEach(function(sl){[].forEach.call(sl.selectedOptions||[],function(op){if(op.value)a.push(op.text.trim())})});fl.querySelectorAll('input[type=text],input[type=number],input[type=url]').forEach(function(i){if(i.value&&i.name!=='product_name')a.push(i.value)});fl.querySelectorAll('textarea').forEach(function(t){if(t.name!=='product_description'&&t.value)a.push(t.value.slice(0,200))});
  var v=a.filter(Boolean).join(', ');if(!v||seen[v.toLowerCase()])return;seen[v.toLowerCase()]=1;var key=L.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'').slice(0,40);if(key&&!o[key])o[key]=v});
 var zf=document.getElementById('ss-zf');if(zf){var zt=zf.textContent.replace(/\s+/g,' ').trim();if(zt)o.project_check=zt.slice(0,300)}
 o.price=(q('[name=product_price]')||{}).value||'';return o}
function post(action,f,extra){var fd=new FormData();fd.append('action',action);fd.append('n',N);for(var k in (extra||{}))fd.append(k,extra[k]);fd.append('f',JSON.stringify(facts(f)));return fetch(AJAX,{method:'POST',credentials:'same-origin',body:fd}).then(function(r){return r.json()})}
function note(after,txt,bad){var n=after.nextElementSibling;if(!(n&&n.classList.contains('ss-aic-n'))){n=document.createElement('p');n.className='ss-aic-n';after.parentNode.insertBefore(n,after.nextSibling)}n.textContent=txt;n.classList.toggle('bad',!!bad)}
function wrap(ctx,text,x,y,maxW,lh,maxLines){var w=text.split(/\s+/),line='',lines=[];w.forEach(function(word){var t=line?line+' '+word:word;if(ctx.measureText(t).width>maxW&&line){lines.push(line);line=word}else line=t});if(line)lines.push(line);lines=lines.slice(0,maxLines);lines.forEach(function(l,i){ctx.fillText(l,x,y+i*lh)});return lines.length}
function compose(cv,img,title,sub){var c=cv.getContext('2d'),S=1200;cv.width=S;cv.height=S;c.drawImage(img,0,0,S,S);var g=c.createLinearGradient(0,S*.45,0,S);g.addColorStop(0,'rgba(0,0,0,0)');g.addColorStop(1,'rgba(0,0,0,.82)');c.fillStyle=g;c.fillRect(0,0,S,S);
 c.fillStyle='#fff';c.textAlign='center';c.shadowColor='rgba(0,0,0,.55)';c.shadowBlur=18;var fs=title.length>18?104:132;c.font='800 '+fs+'px Jura, Roboto, sans-serif';var n=wrap(c,title.toUpperCase(),S/2,S-330+(title.length>18?0:30),S-140,fs*1.02,2);
 if(sub){c.font='700 44px Roboto, sans-serif';c.fillStyle='#f5ad12';c.fillText(sub,S/2,S-150)}c.font='600 34px Roboto, sans-serif';c.fillStyle='rgba(255,255,255,.85)';c.fillText('PRODUCED BY: '+ARTIST.toUpperCase(),S/2,S-90)}
function chips(after,cls){var c=after.parentNode.querySelector('.'+cls);if(!c){c=document.createElement('div');c.className='ss-aic-chips '+cls;after.parentNode.insertBefore(c,after.nextSibling)}c.textContent='';return c}
function chipBtn(box,txt,fn){var b=document.createElement('button');b.type='button';b.className='ss-aic-c';b.textContent=txt;b.addEventListener('click',function(){[].forEach.call(box.querySelectorAll('.ss-aic-c.on'),function(o){o.classList.remove('on')});b.classList.add('on');fn()});box.appendChild(b);return b}
function fire(el){el.dispatchEvent(new Event('input',{bubbles:true}));el.dispatchEvent(new Event('change',{bubbles:true}))}
function init(){var f=document.getElementById('solid-sound-upload-form');if(!f||f.dataset.aic)return !!f;f.dataset.aic=1;
 /* name ideas: 5 to choose from (16.11 owner: let them choose, everything editable) */
 var nm=null; /* 19.11 owner: «для имени не нужно аи подсказки» — no name ideas */if(nm){var nb=document.createElement('button');nb.type='button';nb.className='ss-aic-b';nb.textContent='✨ Name ideas';nm.parentNode.insertBefore(nb,nm.nextSibling);
  nb.addEventListener('click',function(){nb.disabled=true;nb.textContent='✨ Thinking…';post('ss_aic_names',f).then(function(j){nb.disabled=false;nb.textContent='✨ More name ideas';if(!(j&&j.success)){note(nb,(j&&j.data&&j.data.m)||'Something went wrong — please try again.',true);return}var c=chips(nb,'ss-aic-nm');j.data.names.forEach(function(n){chipBtn(c,n,function(){nm.value=n;fire(nm);nm.focus()})});note(c,'Tap a name to use it — then edit it as you like.')}).catch(function(){nb.disabled=false;nb.textContent='✨ Name ideas';note(nb,'Network problem — please try again.',true)})})}
 /* description: 3 variants side by side, nothing replaced until "Use this" */
 var desc=f.querySelector('[name=product_description]');if(desc){var b=document.createElement('button');b.type='button';b.className='ss-aic-b';b.textContent='✨ Write with AI (3 versions)';desc.parentNode.insertBefore(b,desc.nextSibling);
  var pk=document.createElement('div');pk.className='ss-aic-pick';b.parentNode.insertBefore(pk,b.nextSibling);
  var ST=[['short','Short'],['detailed','Detailed'],['story','Story']];
  b.addEventListener('click',function(){if(!f.querySelector('[name=product_name]').value.trim()){note(pk,'Add the product name (and genre, BPM, plugins) first — AI uses only what you fill in.',true);return}
   b.disabled=true;b.textContent='✨ Writing 3 versions…';pk.textContent='';pk.classList.add('on');var left=null,done=0;
   ST.forEach(function(s){var card=document.createElement('div');card.className='ss-aic-card';card.innerHTML='<b></b><div class="t">…</div><button type="button" class="use" disabled>Use this</button>';card.querySelector('b').textContent=s[1];pk.appendChild(card);
    post('ss_aic_desc',f,{style:s[0]}).then(function(j){var t=card.querySelector('.t'),u=card.querySelector('.use');if(j&&j.success){t.textContent=j.data.text;left=j.data.left;u.disabled=false;u.onclick=function(){desc.value=j.data.text;fire(desc);[].forEach.call(pk.querySelectorAll('.ss-aic-card'),function(c){c.classList.toggle('on',c===card)});note(pk,'✓ Added to the description — edit it freely before sending.');desc.focus()}}else{t.textContent=(j&&j.data&&j.data.m)||'Could not write this one.';card.classList.add('bad')}})
    .catch(function(){card.querySelector('.t').textContent='Network problem.';card.classList.add('bad')}).then(function(){if(++done===ST.length){b.disabled=false;b.textContent='✨ 3 new versions';note(pk,'Pick the one you like (nothing changes until you tap “Use this”). Check the facts.'+(left!=null?' AI left today: '+left+'.':''))}})})})}
 /* price hint from similar products (no AI): tap a price or type your own */
 var pr=f.querySelector('[name=product_price]');if(pr){var ph=document.createElement('div');ph.className='ss-aic-chips ss-aic-pr';pr.parentNode.insertBefore(ph,pr.nextSibling);var lastF=null;
  function hint(){var r=f.querySelector('.ss-ap-format input[type=radio]:checked'),fm=r?r.value:'0';if(fm===lastF)return;lastF=fm;var fd=new FormData();fd.append('action','ss_aic_price');fd.append('n',N);fd.append('fmt',fm);
   fetch(AJAX,{method:'POST',credentials:'same-origin',body:fd}).then(function(r){return r.json()}).then(function(j){ph.textContent='';if(!(j&&j.success&&j.data.mid))return;var d=j.data,l=document.createElement('span');l.className='l';l.textContent='Similar products: ';ph.appendChild(l);
    [d.low,d.mid,d.high].filter(function(v,i,a){return a.indexOf(v)===i}).forEach(function(v){chipBtn(ph,CUR+(+v).toFixed(0),function(){pr.value=(+v).toFixed(2);fire(pr)})})}).catch(function(){})}
  pr.addEventListener('focus',hint);f.addEventListener('change',function(e){if(e.target.closest&&e.target.closest('.ss-ap-format'))hint()});hint()}
 /* cover: 3 backgrounds to choose from, editable text */
 var drop=f.querySelector('.ss-wm-drop');if(drop){var cb=document.createElement('button');cb.type='button';cb.className='ss-aic-b';cb.textContent='✨ Make a cover with AI (3 options)';drop.parentNode.insertBefore(cb,drop);
  var box=document.createElement('div');box.className='ss-aic-cv';box.innerHTML='<canvas width="1200" height="1200" aria-label="Cover preview"></canvas><div class="c"><div class="th"></div><label for="ss-aic-t">Title on the cover</label><input id="ss-aic-t" type="text"><label for="ss-aic-s">Second line (genre, “No external plugins”…)</label><input id="ss-aic-s" type="text"><button type="button" class="use">Use this cover</button><button type="button" class="ss-aic-b again">↻ 3 new backgrounds</button></div>';cb.parentNode.insertBefore(box,cb.nextSibling);
  var cv=box.querySelector('canvas'),T=box.querySelector('#ss-aic-t'),Sb=box.querySelector('#ss-aic-s'),th=box.querySelector('.th'),bg=null;
  function redraw(){if(bg)compose(cv,bg,T.value||'Untitled',Sb.value)}
  T.addEventListener('input',redraw);Sb.addEventListener('input',redraw);
  function gen(btn){btn.disabled=true;var old=btn.textContent;btn.textContent='✨ Painting 3 options… (10–30 s)';th.textContent='';var got=0,done=0,left=null;
   for(var i=0;i<3;i++)post('ss_aic_cover',f).then(function(j){if(!(j&&j.success))return;left=j.data.left;var im=new Image();im.onload=function(){var t=document.createElement('button');t.type='button';t.className='tb';t.appendChild(im);t.addEventListener('click',function(){bg=im;[].forEach.call(th.children,function(x){x.classList.toggle('on',x===t)});redraw()});th.appendChild(t);if(!got++){var fc=facts(f);if(!T.value)T.value=fc.name||'';if(!Sb.value)Sb.value=(fc.genre||'').split(',')[0];box.classList.add('on');t.click()}};im.src=j.data.img}).catch(function(){}).then(function(){if(++done===3){btn.disabled=false;btn.textContent=old;note(cb,got?'Tap a background, edit the text, then “Use this cover”.'+(left!=null?' AI covers left today: '+left+'.':''):'AI is busy — please try again in a minute.',!got)}})}
  cb.addEventListener('click',function(){if(!f.querySelector('[name=product_name]').value.trim()&&!T.value){note(cb,'Add the product name first.',true);return}gen(cb)});
  box.querySelector('.again').addEventListener('click',function(){gen(this)});
  box.querySelector('.use').addEventListener('click',function(){cv.toBlob(function(bl){if(!bl)return;var file=new File([bl],(T.value||'cover').replace(/[^\w\-]+/g,'-').toLowerCase()+'-cover.jpg',{type:'image/jpeg'});var multi=f.querySelector('input[type=file][multiple]');try{var dt=new DataTransfer();dt.items.add(file);multi.files=dt.files;multi.dispatchEvent(new Event('change',{bubbles:true}));note(cb,'✓ Cover added to the product. You can still drop your own picture instead.')}catch(e){note(cb,'Your browser could not attach it — right-click the picture, save it and drop it into the box.',true)}},'image/jpeg',0.9)})}
 return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},12000)}
})();</script>
	<?php
}, 60 );
