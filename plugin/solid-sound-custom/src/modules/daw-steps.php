<?php
/**
 * Add Product — choices step by step (owner 19.11.2026: «построй градацию от верхних: Templates → Ableton и другие секвенсоры,
 * так же и с Reconstructed Templates, потом версии секвенсора»):
 *   1) Format (Templates / Presets / Reconstructed Templates / Music / Sample Packs)
 *   2) for Templates and Reconstructed Templates — the DAW (Ableton Live, FL Studio, Logic Pro, Bitwig Studio, Cubase, Studio One, Reason)
 *   3) only the versions of that DAW.
 * The DAW and version lists are the site's own taxonomies ("daw", "product-version"); the missing DAWs and versions are added
 * once (option ss_daw_terms_v1). Nothing else changes: same fields, same saving, the upload robot (creators.php) checks the
 * project file of the chosen DAW.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_daw_list() {
	return array(
		'Ableton Live'  => array( '9', '10', '11', '12' ),
		'FL Studio'     => array( '20', '21', '2024', '2025' ),
		'Logic Pro'     => array( '10', '11' ),
		'Bitwig Studio' => array( '4', '5' ),
		'Cubase'        => array( '12', '13', '14' ),
		'Studio One'    => array( '6', '7' ),
		'Reason'        => array( '12', '13' ),
	);
}

add_action( 'init', function () {
	if ( get_option( 'ss_daw_terms_v1' ) || ! taxonomy_exists( 'daw' ) || ! taxonomy_exists( 'product-version' ) ) return;
	update_option( 'ss_daw_terms_v1', gmdate( 'c' ), false );
	foreach ( ss_daw_list() as $daw => $vers ) {
		if ( ! term_exists( $daw, 'daw' ) ) wp_insert_term( $daw, 'daw' );
		foreach ( $vers as $v ) { $n = $daw . ' ' . $v; if ( ! term_exists( $n, 'product-version' ) ) wp_insert_term( $n, 'product-version' ); }
	}
}, 30 );

/* the step logic, only where the upload form is */
function ss_ds_on() { return function_exists( 'ss_cs_ok' ) && ss_cs_ok() && ! get_option( 'ss_ds_off' ); }
/* in <head>: the form is drawn already folded (nothing jumps when the script runs) */
add_action( 'wp_head', function () {
	if ( ! ss_ds_on() ) return;
	/* (owner 19.11 «эд продакт коряво грузится») five scripts finish the form after it shows (nice selects, «+ Add your own», AI and
	   Preview buttons, the steps): it stays invisible until they are done and then appears at once; a quiet line meanwhile; 4 s safety */
	echo '<style id="ss-ds-ready">#solid-sound-upload-form{opacity:0;transition:opacity .18s ease}#solid-sound-upload-form.ss-ready{opacity:1}#solid-sound-upload-form:not(.ss-ready)~.ss-ds-wait,.ss-ds-wait{display:block}.ss-ds-wait{position:absolute;left:0;right:0;top:120px;text-align:center;color:#94a3b8;font:600 15px "Exo 2",sans-serif}#solid-sound-upload-form.ss-ready~.ss-ds-wait{display:none}</style><noscript><style>#solid-sound-upload-form{opacity:1}</style></noscript>';
	echo '<style id="ss-ds-head">#solid-sound-upload-form:not(:has(.ss-ap-format input:checked)) .ss-wm-body>*:not(.ss-ap-format):not(.ss-ap-daw):not(.ss-ap-version):not(.ss-wm-drop):not(.ss-wm-files):not(#ss-ff-st):not(#ss-zf):not(.ss-ff-c1):not(.ss-ff-c2){display:none!important}</style>'; /* 19.11: files first — the drop window shows before the type */
}, 99 );
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'ss_cs_ok' ) || ! ss_cs_ok() ) return;
	/* on for everyone (owner 19.11: «выкладывай всё, что готово»); off with option ss_ds_off=1 */
	if ( get_option( 'ss_ds_off' ) ) return; ?>
<style>#solid-sound-upload-form .ss-ds-off{display:none!important}#solid-sound-upload-form .ss-ds-hint{margin:6px 0 0;color:#94a3b8;font-size:14px}
#solid-sound-upload-form .ss-wm-body>.ss-ap-daw,#solid-sound-upload-form .ss-wm-body>.ss-ap-version,#solid-sound-upload-form .ss-wm-body>.ss-ap-genre{margin-top:22px!important}
#solid-sound-upload-form .ss-ap-format .acf-radio-list,#solid-sound-upload-form .ss-ap-daw .acf-radio-list,#solid-sound-upload-form .ss-ap-version .acf-radio-list{max-height:none!important;overflow:visible!important}
/* owner 19.11: «сначала — что это: шаблон, пресет и т.д., а уже от этого плясать дальше» — nothing else until the type is chosen */
/* 19.11 owner: files FIRST — the «Drop your files» window (files-first.php) stays visible before the type is chosen; it picks the type itself */
#solid-sound-upload-form.ss-ds-nofmt .ss-wm-body>*:not(.ss-ap-format):not(.ss-wm-drop):not(.ss-wm-files):not(#ss-ff-st):not(#ss-zf):not(.ss-ff-c1):not(.ss-ff-c2),#solid-sound-upload-form.ss-ds-nofmt .ss-wm-body~*:not(.ss-wm-hidden){display:none!important}
#solid-sound-upload-form .ss-ds-first{margin:10px 0 0;color:#F59E0B;font-weight:700}
/* 19.11 owner «надо всё выровнять, криво»: BPM / Key / Plugins… in the grid — title, one-height hint, «+ Add», then the field full width; key hint under it */
#solid-sound-upload-form .ss-ff-grid .acf-field{display:flex!important;flex-direction:column!important;align-self:start!important;min-width:0}
#solid-sound-upload-form .ss-ff-grid .acf-label{margin:0 0 8px!important}
#solid-sound-upload-form .ss-ff-grid .acf-label label{margin:0 0 4px!important;line-height:1.3!important}
#solid-sound-upload-form .ss-ff-grid .acf-label .description,#solid-sound-upload-form .ss-ff-grid .acf-label p{margin:0!important;line-height:1.45!important;font-size:14px!important;min-height:2.9em}
#solid-sound-upload-form .ss-ff-grid .acf-input{display:flex!important;flex-direction:column!important;align-items:stretch!important;gap:8px!important}
#solid-sound-upload-form .ss-ff-grid .acf-input>*{width:100%!important;max-width:100%!important;flex:none!important;margin-left:0!important;margin-right:0!important;box-sizing:border-box}
#solid-sound-upload-form .ss-ff-grid .acf-input>.acf-actions,#solid-sound-upload-form .ss-ff-grid .acf-input>a{width:auto!important;align-self:flex-start;order:-1}
#solid-sound-upload-form .ss-ff-grid .select2-container{width:100%!important}
#solid-sound-upload-form .ss-ff-grid .select2-selection{min-height:44px}
#solid-sound-upload-form .ss-ff-grid #ss-kd{margin:0!important}
#solid-sound-upload-form .ss-ff-grid .acf-label{position:relative}#solid-sound-upload-form .ss-ff-grid .acf-label .ss-wm-cnt{position:absolute;right:0;top:0;margin:0!important;padding:2px 8px!important;font-size:12px!important}</style>
<script>(function(){
function run(){var form=document.getElementById('solid-sound-upload-form');if(!form||form.dataset.ssds)return!!form;var fmt=form.querySelector('.ss-ap-format'),daw=form.querySelector('.ss-ap-daw'),ver=form.querySelector('.ss-ap-version');if(!fmt||!daw||!ver)return false;form.dataset.ssds='1';
 /* 1 → 2 → 3 right under each other */
 fmt.parentNode.insertBefore(daw,fmt.nextSibling);daw.parentNode.insertBefore(ver,daw.nextSibling);
 var hint=document.createElement('p');hint.className='ss-ds-hint';hint.textContent='Choose the DAW first — then its versions appear.';ver.querySelector('.acf-input')&&ver.querySelector('.acf-input').appendChild(hint);
 function txt(i){var l=i.closest('label');return (l?l.textContent:'').trim()}
 var first=document.createElement('p');first.className='ss-ds-first';first.textContent='Start here: what are you selling? The rest of the form adapts to it.';(fmt.querySelector('.acf-input')||fmt).appendChild(first);
 var lb=fmt.querySelector('.acf-label label');if(lb&&lb.firstChild&&lb.firstChild.nodeType===3)lb.firstChild.nodeValue='What are you selling?';
 function names(){return [].map.call(daw.querySelectorAll('input[type=radio]'),txt).filter(Boolean)} /* a version the creator typed («Add your own») that names no known DAW is always shown */
 function upd(){var fc=!!(fmt.querySelector('input:checked')||(fmt.querySelector('select')&&fmt.querySelector('select').value));form.classList.toggle('ss-ds-nofmt',!fc);first.style.display=fc?'none':'';
  var d=daw.querySelector('input:checked'),dn=d?txt(d):'';
  ver.querySelectorAll('input[type=radio]').forEach(function(i){var li=i.closest('li')||i.closest('label'),t=txt(i),mine=names().some(function(n){return t.indexOf(n+' ')===0}),ok=!mine||(!!dn&&t.indexOf(dn+' ')===0);if(li){if(ok)li.style.removeProperty('display');else li.style.setProperty('display','none','important')}if(!ok&&i.checked)i.checked=false});
  hint.style.display=dn?'none':'';
  var on=!daw.classList.contains('ss-ap-off');if(on&&!dn)ver.style.setProperty('display','none','important');else ver.style.removeProperty('display')}
 form.addEventListener('change',function(e){if(e.target.closest&&(e.target.closest('.ss-ap-daw')||e.target.closest('.ss-ap-format')))setTimeout(upd,0)});
 (function(){var ul=ver.querySelector('ul');if(!ul)return;var li=[].slice.call(ul.children);li.sort(function(a,b){var x=(a.textContent.match(/(\d+)\s*$/)||[0,0])[1],y=(b.textContent.match(/(\d+)\s*$/)||[0,0])[1];var na=a.textContent.replace(/\d+\s*$/,''),nb=b.textContent.replace(/\d+\s*$/,'');return na<nb?-1:na>nb?1:(+y)-(+x)});li.forEach(function(x){ul.appendChild(x)})})(); /* newest version first */
 upd();return true}
if(!run()){var mo=new MutationObserver(function(){if(run())mo.disconnect()});mo.observe(document.documentElement,{childList:true,subtree:true});setTimeout(function(){mo.disconnect()},15000)}
/* show the form once everything has finished building it */
function show(){var f=document.getElementById('solid-sound-upload-form');if(f&&!f.classList.contains('ss-ready')){f.classList.add('ss-ready');var w=document.querySelector('.ss-ds-wait');if(w)w.remove()}}
(function(){var f=document.getElementById('solid-sound-upload-form');if(!f)return;var p=f.parentNode;if(p&&getComputedStyle(p).position==='static')p.style.position='relative';var w=document.createElement('div');w.className='ss-ds-wait';w.textContent='Loading the form…';f.parentNode.insertBefore(w,f.nextSibling)})();
function later(){requestAnimationFrame(function(){requestAnimationFrame(function(){setTimeout(show,250)})})}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',later);else later();
setTimeout(show,4000);
})();</script>
<?php }, 100 );
