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
	$max = 'desc' === $kind ? 20 : 10;
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
	$facts = ''; foreach ( $f as $k => $v ) $facts .= ucfirst( str_replace( '_', ' ', $k ) ) . ': ' . $v . "\n";
	$sys = "You write product descriptions for Solid Sound, a marketplace of Ableton Live templates, synth presets, sample packs and royalty-free music for producers and DJs.\n"
		. "Write in English, 70-110 words: two short paragraphs (what it is and the sound / who it is for, then what the buyer learns or gets), then a line 'What's inside:' and 3-5 bullet points starting with '- '.\n"
		. "Use ONLY the facts given. Never invent plugin names, numbers, artists, awards or contents that are not in the facts. If a fact is missing, leave it out.\n"
		. "Plain, confident producer language. No emojis, no hashtags, no words like ultimate, game-changer, unleash, elevate, next level. Do not repeat the product name more than twice. Output only the description.";
	$r = ss_cf_ai( '@cf/meta/llama-3.1-8b-instruct', array( 'messages' => array( array( 'role' => 'system', 'content' => $sys ), array( 'role' => 'user', 'content' => "Facts:\n" . $facts ) ), 'max_tokens' => 420, 'temperature' => 0.6 ) );
	if ( is_wp_error( $r ) ) wp_send_json_error( array( 'm' => 'AI is busy — please try again in a minute.' ), 502 );
	$t = trim( (string) ( $r['response'] ?? '' ) );
	$t = preg_replace( '/^(here is|here\'s)[^\n]*\n+/i', '', $t );
	if ( '' === $t ) wp_send_json_error( array( 'm' => 'Empty answer — please try again.' ), 502 );
	ss_aic_left( 'desc', true );
	wp_send_json_success( array( 'text' => $t, 'left' => ss_aic_left( 'desc' ) ) );
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
</style>
<script>(function(){
var AJAX=<?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>,N=<?php echo wp_json_encode( wp_create_nonce( 'ss_aic' ) ); ?>,ARTIST=<?php echo wp_json_encode( $artist ); ?>;
function facts(f){var o={},q=function(s){return f.querySelector(s)};
 o.name=(q('[name=product_name]')||{}).value||'';
 function picked(cls){var w=q('.ss-ap-'+cls);if(!w)return '';var a=[];w.querySelectorAll('input:checked').forEach(function(i){var l=i.closest('label');a.push((l?l.textContent:i.value).trim())});w.querySelectorAll('select').forEach(function(s){[].forEach.call(s.selectedOptions||[],function(o){if(o.value)a.push(o.text.trim())})});w.querySelectorAll('input[type=text],input[type=number]').forEach(function(i){if(i.value)a.push(i.value)});return a.join(', ')}
 ['format','genre','daw','version','ext','mood','bpm','key','length','tag','dl'].forEach(function(k){var v=picked(k);if(v)o[k==='ext'?'plugins':(k==='dl'?'download':k)]=v});
 o.price=(q('[name=product_price]')||{}).value||'';return o}
function post(action,f){var fd=new FormData();fd.append('action',action);fd.append('n',N);fd.append('f',JSON.stringify(facts(f)));return fetch(AJAX,{method:'POST',credentials:'same-origin',body:fd}).then(function(r){return r.json()})}
function note(after,txt,bad){var n=after.parentNode.querySelector('.ss-aic-n');if(!n){n=document.createElement('p');n.className='ss-aic-n';after.parentNode.insertBefore(n,after.nextSibling)}n.textContent=txt;n.classList.toggle('bad',!!bad)}
function wrap(ctx,text,x,y,maxW,lh,maxLines){var w=text.split(/\s+/),line='',lines=[];w.forEach(function(word){var t=line?line+' '+word:word;if(ctx.measureText(t).width>maxW&&line){lines.push(line);line=word}else line=t});if(line)lines.push(line);lines=lines.slice(0,maxLines);lines.forEach(function(l,i){ctx.fillText(l,x,y+i*lh)});return lines.length}
function compose(cv,img,title,sub){var c=cv.getContext('2d'),S=1200;cv.width=S;cv.height=S;c.drawImage(img,0,0,S,S);var g=c.createLinearGradient(0,S*.45,0,S);g.addColorStop(0,'rgba(0,0,0,0)');g.addColorStop(1,'rgba(0,0,0,.82)');c.fillStyle=g;c.fillRect(0,0,S,S);
 c.fillStyle='#fff';c.textAlign='center';c.shadowColor='rgba(0,0,0,.55)';c.shadowBlur=18;var fs=title.length>18?104:132;c.font='800 '+fs+'px Jura, Roboto, sans-serif';var n=wrap(c,title.toUpperCase(),S/2,S-330+(title.length>18?0:30),S-140,fs*1.02,2);
 if(sub){c.font='700 44px Roboto, sans-serif';c.fillStyle='#f5ad12';c.fillText(sub,S/2,S-150)}c.font='600 34px Roboto, sans-serif';c.fillStyle='rgba(255,255,255,.85)';c.fillText('PRODUCED BY: '+ARTIST.toUpperCase(),S/2,S-90)}
function init(){var f=document.getElementById('solid-sound-upload-form');if(!f||f.dataset.aic)return !!f;f.dataset.aic=1;
 /* description */
 var desc=f.querySelector('[name=product_description]');if(desc){var b=document.createElement('button');b.type='button';b.className='ss-aic-b';b.innerHTML='✨ Write with AI';desc.parentNode.insertBefore(b,desc.nextSibling);
  b.addEventListener('click',function(){if(!f.querySelector('[name=product_name]').value.trim()){note(b,'Add the product name (and genre, BPM, plugins) first — AI uses only what you fill in.',true);return}
   if(desc.value.trim().length>30&&!desc.dataset.aiok){desc.dataset.aiok=1;note(b,'This replaces your text. Click again to continue.',true);return}
   b.disabled=true;b.innerHTML='✨ Writing…';post('ss_aic_desc',f).then(function(j){b.disabled=false;b.innerHTML='✨ Rewrite with AI';if(j&&j.success){desc.value=j.data.text;desc.dispatchEvent(new Event('input',{bubbles:true}));note(b,'Check the facts and edit before sending. '+j.data.left+' AI descriptions left today.')}else note(b,(j&&j.data&&j.data.m)||'Something went wrong — please try again.',true)}).catch(function(){b.disabled=false;b.innerHTML='✨ Write with AI';note(b,'Network problem — please try again.',true)})})}
 /* cover */
 var drop=f.querySelector('.ss-wm-drop');if(drop){var cb=document.createElement('button');cb.type='button';cb.className='ss-aic-b';cb.innerHTML='✨ Make a cover with AI';drop.parentNode.insertBefore(cb,drop);
  var box=document.createElement('div');box.className='ss-aic-cv';box.innerHTML='<canvas width="1200" height="1200" aria-label="Cover preview"></canvas><div class="c"><label for="ss-aic-t">Title on the cover</label><input id="ss-aic-t" type="text"><label for="ss-aic-s">Second line (genre, “No external plugins”…)</label><input id="ss-aic-s" type="text"><button type="button" class="use">Use this cover</button><button type="button" class="ss-aic-b again">↻ New background</button></div>';cb.parentNode.insertBefore(box,cb.nextSibling);
  var cv=box.querySelector('canvas'),T=box.querySelector('#ss-aic-t'),Sb=box.querySelector('#ss-aic-s'),bg=null;
  function redraw(){if(bg)compose(cv,bg,T.value||'Untitled',Sb.value)}
  T.addEventListener('input',redraw);Sb.addEventListener('input',redraw);
  function gen(btn){btn.disabled=true;var old=btn.innerHTML;btn.innerHTML='✨ Painting… (10–20 s)';post('ss_aic_cover',f).then(function(j){btn.disabled=false;btn.innerHTML=old;if(!(j&&j.success)){note(cb,(j&&j.data&&j.data.m)||'Something went wrong — please try again.',true);return}
    var im=new Image();im.onload=function(){bg=im;var fc=facts(f);if(!T.value)T.value=fc.name||'';if(!Sb.value)Sb.value=(fc.genre||'').split(',')[0];box.classList.add('on');redraw();note(cb,'Edit the text, then “Use this cover”. '+j.data.left+' AI covers left today.')};im.src=j.data.img}).catch(function(){btn.disabled=false;btn.innerHTML=old;note(cb,'Network problem — please try again.',true)})}
  cb.addEventListener('click',function(){if(!f.querySelector('[name=product_name]').value.trim()&&!T.value){note(cb,'Add the product name first.',true);return}gen(cb)});
  box.querySelector('.again').addEventListener('click',function(){gen(this)});
  box.querySelector('.use').addEventListener('click',function(){cv.toBlob(function(bl){if(!bl)return;var file=new File([bl],(T.value||'cover').replace(/[^\w\-]+/g,'-').toLowerCase()+'-cover.jpg',{type:'image/jpeg'});var multi=f.querySelector('input[type=file][multiple]');try{var dt=new DataTransfer();dt.items.add(file);multi.files=dt.files;multi.dispatchEvent(new Event('change',{bubbles:true}));note(cb,'✓ Cover added to the product.')}catch(e){note(cb,'Your browser could not attach it — right-click the picture, save it and drop it into the box.',true)}},'image/jpeg',0.9)})}
 return true}
if(!init()){var mo=new MutationObserver(function(){requestAnimationFrame(function(){if(init())mo.disconnect()})});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},12000)}
})();</script>
	<?php
}, 60 );
