<?php exit; ?>
.owl-carousel.navstyle_circle .owl-nav div {
	border-radius: 4%;
}
.w-form-checkbox:before {
	color: #e09900;
}
.vc_btn3.vc_btn3-size-md {
	font-weight: 800;
    font-family: 'Roboto';
}
.mejs-container {
	margin-bottom: 10px;
}

.woocommerce .product .summary .price {
	color:#e09900;
}

.wcz-badge-pos-topleft {
	z-index: 999;
}
.screen-reader-text:focus {
    display: none!important;
}
.type-product .price > del {
	font-size: 0.8em!important;
	position: relative;
    text-decoration: none;
	opacity: 1;
}

del:before {
    content: "";
    width: 105%;
    height: 0.07em;
    background: red;
    position: absolute;
    top: 50%;
    left: 0;
    transform: rotate(-5deg);
}

.usg_post_custom_field_1 {
	margin: 14px 2% 2px 26% !important;
}

#wp-admin-bar-us_theme_otions {
	display:none!important;
}

@media (max-width: 480px) {
iframe {
	height: 260px;
	}
}
@media only screen
and (min-device-width: 768px)
and (max-device-width: 1024px)
and (orientation: landscape) {
iframe {
	height: 400px;
	}
}
a.button.wcz-bts-btn {
	margin: 10px 0 25px;
}
.widget.widget_shopping_cart .remove {
	font-size: 2.5rem;
}

div.usof-tabs-sections:nth-child(1) > div:nth-child(1) > div:nth-child(6) > div:nth-child(2) > div:nth-child(2) > div:nth-child(2) > a:nth-child(1) {
	display: none;
}

/* кнопка «наверх» выше чата Tidio */
.w-toplink{
	bottom:110px!important;
}
body.ssp-open.ssp-open .w-toplink{
	bottom:155px!important;
}



/* WIKI studio style */
article.ssa{counter-reset:s}
article.ssa h2{position:relative;margin:2.4em 0 .7em;padding:.15em 0 .15em 20px;border-left:5px solid #e8a010;font-weight:700;letter-spacing:.2px}
article.ssa h2[id]:not(#faq):not(#checklist)::before{counter-increment:s;content:"0" counter(s);display:block;font-size:.5em;letter-spacing:3px;color:#e8a010;font-weight:700;margin-bottom:2px}
article.ssa h3{color:#e8a010;text-transform:uppercase;letter-spacing:1.5px;font-size:.8em}
article.ssa ul:not(.check) li::marker,article.ssa ol li::marker{color:#e8a010}
article.ssa figure{margin:1.6em 0;background:#161412;border:1px solid #2e2a25;border-radius:14px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.45)}
article.ssa figure img{display:block;width:100%;height:auto;border-radius:0!important;border-bottom:3px solid #e8a010}
article.ssa figcaption{font-size:13px;color:#9a9288;text-align:center;padding:10px 14px;letter-spacing:.3px}
article.ssa figcaption a{color:#e8a010}
article.ssa .toc{background:#161412;border:1px solid #2e2a25;border-left:5px solid #e8a010;border-radius:12px;padding:18px 24px;margin:1.8em 0}
article.ssa .toc b{display:block;color:#e8a010;text-transform:uppercase;letter-spacing:3px;font-size:13px;margin-bottom:8px}
article.ssa .toc ol{margin:0;padding-left:22px;columns:2;column-gap:36px}
article.ssa .toc li{margin:.35em 0;break-inside:avoid}
article.ssa .toc a{color:#e8e4dc;text-decoration:none}
article.ssa .toc a:hover{color:#e8a010}
@media(max-width:700px){article.ssa .toc ol{columns:1}}
html:has(article.ssa){scroll-behavior:smooth}

article.ssa .gal{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin:1.6em 0}article.ssa .gal figure{margin:0}@media(max-width:700px){article.ssa .gal{grid-template-columns:1fr}}

/* кнопка наверх: запас над чатом (скрипт ставит inline bottom) */ .w-toplink{margin-bottom:70px!important}

/* readability */ article.ssa p,article.ssa li{font-size:18px!important;line-height:1.8!important;color:#ece8e0}article.ssa p{margin:0 0 1.15em}article.ssa li{margin:.5em 0}article.ssa strong,article.ssa b{color:#fff}article.ssa figcaption{font-size:15px!important;line-height:1.5;color:#c9c2b6!important}article.ssa .toc li{font-size:16px!important;line-height:1.5!important}article.ssa .toc a{color:#f1ede5}@media(max-width:700px){article.ssa p,article.ssa li{font-size:17px!important}}

/* wiki lead form + A/B audio */
article.ssa .lead-box{background:linear-gradient(135deg,#1f1a12,#141210);border:1px solid #3a2f1a;border-left:5px solid #e8a010;border-radius:14px;padding:22px 24px;margin:28px 0}article.ssa .lead-box>b{display:block;font-size:22px;color:#fff;margin-bottom:6px}article.ssa .lead-box p{margin:0 0 14px!important;color:#c9c2b6}
article.ssa .ss-lead{display:flex;flex-wrap:wrap;gap:10px;position:relative}article.ssa .ss-lead input[type=email]{flex:1 1 240px;background:#0f0e0d;border:1px solid #3a3733;border-radius:10px;color:#fff;padding:13px 14px;font-size:16px}article.ssa .ss-lead button{background:#e8a010;color:#121110;border:0;border-radius:10px;padding:13px 22px;font-weight:700;font-size:16px;cursor:pointer}article.ssa .ss-lead button:disabled{opacity:.6}article.ssa .ss-lead .cons{flex-basis:100%;font-size:13px;color:#9a9288;display:flex;gap:8px;align-items:flex-start}article.ssa .ss-lead-msg{flex-basis:100%;color:#3ecf8e;font-weight:700}article.ssa .ss-lead.done input,article.ssa .ss-lead.done button,article.ssa .ss-lead.done .cons{display:none}article.ssa .ss-lead-msg a{color:#e8a010}
article.ssa .ab{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:22px 0}article.ssa .ab>div{background:#161412;border:1px solid #2e2a25;border-radius:12px;padding:14px}article.ssa .ab b{display:block;margin-bottom:8px;color:#fff}article.ssa .ab .bad b{color:#f07070}article.ssa .ab .good b{color:#3ecf8e}article.ssa .ab audio{width:100%}article.ssa .ab-note{font-size:13px;color:#9a9288;margin-top:-10px!important}@media(max-width:700px){article.ssa .ab{grid-template-columns:1fr}}
