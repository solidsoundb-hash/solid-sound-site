<?php
/**
 * (20.11.2026 owner: «переведи» — language robot: contact pages half in English) The theme's contact form (us_cform) prints its
 * field names, consent text and messages in English on every language. Here they are translated for ua, de, fr, es, it, pt.
 * Only the form's printed HTML changes; the pages and the form settings in WordPress stay as they are.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function ss_i18n_lang() {
	$l = function_exists( 'pll_current_language' ) ? (string) pll_current_language( 'slug' ) : '';
	if ( ! $l && preg_match( '#^/(ua|uk|de|fr|es|it|pt)/#', (string) ( $_SERVER['REQUEST_URI'] ?? '' ), $m ) ) $l = $m[1];
	return 'uk' === $l ? 'ua' : $l;
}
function ss_i18n_form_words() {
	return array(
		'ua' => array( 'Name *' => 'Ім’я *', '"Name"' => '"Ім’я"', 'Email *' => 'Email *', '"Message"' => '"Повідомлення"', 'Message' => 'Повідомлення',
			'Fill out this field' => 'Заповніть це поле', 'I consent to having Solid Sound collect my name and email.' => 'Я погоджуюся, що Solid Sound збереже моє ім’я та email.',
			'By submitting the contact form, you consent to your submitted data being collected and stored.' => 'Надсилаючи форму, ви погоджуєтеся на збір і зберігання ваших даних.',
			'You need to agree with the terms to proceed' => 'Щоб продовжити, потрібно погодитися з умовами' ),
		'de' => array( 'Name *' => 'Name *', '"Message"' => '"Nachricht"', 'Message' => 'Nachricht', 'Fill out this field' => 'Bitte füll dieses Feld aus',
			'I consent to having Solid Sound collect my name and email.' => 'Ich bin einverstanden, dass Solid Sound meinen Namen und meine E-Mail speichert.',
			'By submitting the contact form, you consent to your submitted data being collected and stored.' => 'Mit dem Absenden stimmst du zu, dass deine Angaben gespeichert werden.',
			'You need to agree with the terms to proceed' => 'Bitte stimme den Bedingungen zu, um fortzufahren' ),
		'fr' => array( 'Name *' => 'Nom *', '"Name"' => '"Nom"', 'Email *' => 'E-mail *', '"Message"' => '"Message"', 'Fill out this field' => 'Veuillez remplir ce champ',
			'I consent to having Solid Sound collect my name and email.' => 'J’accepte que Solid Sound enregistre mon nom et mon e-mail.',
			'By submitting the contact form, you consent to your submitted data being collected and stored.' => 'En envoyant ce formulaire, vous acceptez que vos données soient collectées et conservées.',
			'You need to agree with the terms to proceed' => 'Vous devez accepter les conditions pour continuer' ),
		'es' => array( 'Name *' => 'Nombre *', '"Name"' => '"Nombre"', 'Email *' => 'Email *', '"Message"' => '"Mensaje"', 'Message' => 'Mensaje', 'Fill out this field' => 'Rellena este campo',
			'I consent to having Solid Sound collect my name and email.' => 'Acepto que Solid Sound guarde mi nombre y mi email.',
			'By submitting the contact form, you consent to your submitted data being collected and stored.' => 'Al enviar el formulario, aceptas que tus datos se recojan y guarden.',
			'You need to agree with the terms to proceed' => 'Debes aceptar las condiciones para continuar' ),
		'it' => array( 'Name *' => 'Nome *', '"Name"' => '"Nome"', 'Email *' => 'Email *', '"Message"' => '"Messaggio"', 'Message' => 'Messaggio', 'Fill out this field' => 'Compila questo campo',
			'I consent to having Solid Sound collect my name and email.' => 'Acconsento che Solid Sound conservi il mio nome e la mia email.',
			'By submitting the contact form, you consent to your submitted data being collected and stored.' => 'Inviando il modulo acconsenti alla raccolta e alla conservazione dei tuoi dati.',
			'You need to agree with the terms to proceed' => 'Devi accettare le condizioni per continuare' ),
		'pt' => array( 'Name *' => 'Nome *', '"Name"' => '"Nome"', 'Email *' => 'Email *', '"Message"' => '"Mensagem"', 'Message' => 'Mensagem', 'Fill out this field' => 'Preencha este campo',
			'I consent to having Solid Sound collect my name and email.' => 'Aceito que a Solid Sound guarde o meu nome e o meu email.',
			'By submitting the contact form, you consent to your submitted data being collected and stored.' => 'Ao enviar o formulário, aceita que os seus dados sejam recolhidos e guardados.',
			'You need to agree with the terms to proceed' => 'Tem de aceitar as condições para continuar' ),
	);
}
add_filter( 'do_shortcode_tag', function ( $out, $tag ) {
	if ( 'us_cform' !== $tag || is_admin() ) return $out;
	$w = ss_i18n_form_words(); $l = ss_i18n_lang();
	if ( empty( $w[ $l ] ) ) return $out;
	/* longest first, so «Name *» is replaced before «Name» */
	$t = $w[ $l ]; uksort( $t, function ( $a, $b ) { return strlen( $b ) - strlen( $a ); } );
	return strtr( $out, $t );
}, 20, 2 );
