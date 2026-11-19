<?php
/**
 * Home page (2433 and its translations): the app is delivered as normal cached files.
 *
 * Before: the page content loaded React from a CDN, downloaded a gzip+base64 text file, unpacked it in the
 * browser and patched its text on the fly, while the theme layout showed underneath.
 * Now: body class + home CSS come with the HTML (no flash of the old layout), React is self-hosted,
 * the template is pre-patched at build time (src/home/patches.js), all scripts are deferred and cached.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* Other languages (owner 17.11.2026): the home page of UA/DE/FR/ES/IT/PT shows the same new app, translated at
   build time (src/home/i18n/<lang>.json → asset home-app-<lang>). On for everyone since 17.11 (owner: «Все выкладывай»); ?ss_newl=0 shows the old page; option ss_home_l10n=0 turns it off
   with the option ss_home_l10n = 1. Returns the language slug, or '' (English / old page). */
function ss_home_lang() {
	static $l = null;
	if ( null !== $l ) return $l;
	$l = '';
	if ( is_admin() || ! function_exists( 'pll_current_language' ) || ! is_front_page() ) return $l;
	$s = (string) pll_current_language( 'slug' );
	if ( '' === $s || 'en' === $s || ! function_exists( 'ss_asset_has' ) || ! ss_asset_has( 'home-app-' . $s ) ) return $l;
	if ( isset( $_GET['ss_newl'] ) ? '1' === $_GET['ss_newl'] : '1' === (string) get_option( 'ss_home_l10n', '1' ) ) $l = $s;
	return $l;
}

/* small texts printed by PHP on the home (slogan pill, "N templates match", ranking badges) */
function ss_home_t( $en ) {
	static $T = array(
		'Learn · Create · Release' => array( 'ua' => 'Вчись · Створюй · Випускай', 'de' => 'Lernen · Erschaffen · Veröffentlichen', 'fr' => 'Apprendre · Créer · Publier', 'es' => 'Aprende · Crea · Publica', 'it' => 'Impara · Crea · Pubblica', 'pt' => 'Aprenda · Crie · Lance' ),
		'Closest to your choice' => array( 'ua' => 'Найближче до вашого вибору', 'de' => 'Am nächsten an deiner Auswahl', 'fr' => 'Le plus proche de votre choix', 'es' => 'Lo más cercano a tu elección', 'it' => 'Il più vicino alla tua scelta', 'pt' => 'O mais próximo da sua escolha' ),
		' template matches' => array( 'ua' => ' шаблон підходить', 'de' => ' Vorlage passt', 'fr' => ' modèle correspond', 'es' => ' plantilla coincide', 'it' => ' template corrisponde', 'pt' => ' modelo combina' ),
		' templates match' => array( 'ua' => ' шаблони підходять', 'de' => ' Vorlagen passen', 'fr' => ' modèles correspondent', 'es' => ' plantillas coinciden', 'it' => ' template corrispondono', 'pt' => ' modelos combinam' ),
		' — tap to listen' => array( 'ua' => ' — натисніть, щоб послухати', 'de' => ' — zum Anhören tippen', 'fr' => ' — touchez pour écouter', 'es' => ' — toca para escuchar', 'it' => ' — tocca per ascoltare', 'pt' => ' — toque para ouvir' ),
		"Editor's pick" => array( 'ua' => 'Вибір редакції', 'de' => 'Empfehlung', 'fr' => 'Choix de l’équipe', 'es' => 'Elección del editor', 'it' => 'Scelta della redazione', 'pt' => 'Escolha do editor' ),
		'Best seller' => array( 'ua' => 'Хіт продажів', 'de' => 'Bestseller', 'fr' => 'Meilleure vente', 'es' => 'Más vendido', 'it' => 'Più venduto', 'pt' => 'Mais vendido' ),
		'Templates' => array( 'ua' => 'Шаблони', 'de' => 'Vorlagen', 'fr' => 'Modèles', 'es' => 'Plantillas', 'it' => 'Template', 'pt' => 'Modelos' ),
		'Presets' => array( 'ua' => 'Пресети', 'de' => 'Presets', 'fr' => 'Presets', 'es' => 'Presets', 'it' => 'Preset', 'pt' => 'Presets' ),
		'Music' => array( 'ua' => 'Музика', 'de' => 'Musik', 'fr' => 'Musique', 'es' => 'Música', 'it' => 'Musica', 'pt' => 'Música' ),
		'Mixing & Mastering' => array( 'ua' => 'Зведення і мастеринг', 'de' => 'Mixing & Mastering', 'fr' => 'Mixage & mastering', 'es' => 'Mezcla y máster', 'it' => 'Mix & master', 'pt' => 'Mixagem e master' ),
		'Free' => array( 'ua' => 'Безкоштовно', 'de' => 'Gratis', 'fr' => 'Gratuit', 'es' => 'Gratis', 'it' => 'Gratis', 'pt' => 'Grátis' ),
		'New this week' => array( 'ua' => 'Новинка тижня', 'de' => 'Neu diese Woche', 'fr' => 'Nouveauté de la semaine', 'es' => 'Novedad de la semana', 'it' => 'Novità della settimana', 'pt' => 'Novidade da semana' ),
		'Listen' => array( 'ua' => 'Слухати', 'de' => 'Anhören', 'fr' => 'Écouter', 'es' => 'Escuchar', 'it' => 'Ascolta', 'pt' => 'Ouvir' ),
		'View product' => array( 'ua' => 'Детальніше', 'de' => 'Ansehen', 'fr' => 'Voir', 'es' => 'Ver', 'it' => 'Vedi', 'pt' => 'Ver' ),
		'Add to cart' => array( 'ua' => 'У кошик', 'de' => 'In den Warenkorb', 'fr' => 'Ajouter au panier', 'es' => 'Añadir al carrito', 'it' => 'Aggiungi al carrello', 'pt' => 'Adicionar ao carrinho' ),
		'Trending' => array( 'ua' => 'У тренді', 'de' => 'Im Trend', 'fr' => 'Tendance', 'es' => 'Tendencia', 'it' => 'Di tendenza', 'pt' => 'Em alta' ),
	);
	$l = function_exists( 'ss_home_lang' ) ? ss_home_lang() : '';
	return ( $l && isset( $T[ $en ][ $l ] ) ) ? $T[ $en ][ $l ] : $en;
}

function ss_home_is_app_page() {
	if ( is_admin() || ! is_singular( 'page' ) ) return false;
	if ( '' !== ss_home_lang() ) return true;
	$p = get_post();
	return $p && false !== strpos( $p->post_content, 'ss-newhome-app-gz' );
}

add_filter( 'body_class', function ( $c ) {
	if ( ss_home_is_app_page() ) $c[] = 'ss-newhome';
	return $c;
} );

/* the page content becomes just the mount point; everything else comes from the plugin */
add_filter( 'the_content', function ( $c ) {
	if ( '' !== ss_home_lang() && in_the_loop() && is_main_query() ) return '<div id="ss-app"></div>';
	if ( false === strpos( $c, 'ss-newhome-app-gz' ) ) return $c;
	return '<div id="ss-app"></div>';
}, 0 );

add_action( 'wp_head', function () {
	if ( ! ss_home_is_app_page() ) return;
	ss_asset( 'home-css' );
}, 5 );

add_action( 'wp_footer', function () {
	if ( ! ss_home_is_app_page() ) return;
	$app = '' !== ss_home_lang() ? 'home-app-' . ss_home_lang() : 'home-app';
	foreach ( array( 'react', 'react-dom', 'home-js', $app, 'home-ai' ) as $n ) ss_asset( $n, 'defer' );
}, 20 );
