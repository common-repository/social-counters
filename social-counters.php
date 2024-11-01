<?php
/*
Plugin Name: Social Counters
Plugin URI: http://blogestudio.com/plugin/social-counters/
Description:  Get Counters from Social Webs
Version: 2.2.9
Author: Alejandro Carravedo (Blogestudio)
Author URI: http://blogestudio.com


1.1.0 	- Solucionado error con la cache de contador
		- Solucionado error de conexion con Meneame.

1.2.0	- Cambiado sistema de cuento de Twitter por Tweetmeme, el primero obvia todo lo que no sea la URL concreta. (15.06.2010)
		- Anyadida funcion "social_counter__twitter__login_string" para cambiar el usuario de ReTweet.

2.0.0   - Reprogramacion del plugin para funcionar por filtros y acciones, por ahora.
		- ToDo: Funcionamiento por clases!!

2.0.1	- Solucionado error con la CONSTANTE "SOCIAL_COUNTER__LOAD_CSS"

2.1.0	- Anadido parametro para enviar el objecto POST a las funciones "the_" (postparam).
		- Cambiado sistema de "share" en Twitter

2.1.1	- Cambiado sistema de contador en Twitter, ahora se usa el propio Twitter, no TweetMeMe
		- Reparado contador de Facebook, ahora funciona con el Graph API, han quitado de sopetón los datos del REST API.

2.2.0	- Anadido el contador de "Bitacoras"
		- Anadido el contador de "LinkedIn"
		- Anadida constante SOCIAL_COUNTER__LOAD_CSS_SMALL para cargar solo los iconos, no los textos de compartir.

2.2.1	- Actualizada version del CSS

2.2.2	- Reparado "desperfecto" en el contador de Twitter.

2.2.3	- Eliminado contador de Google Buzz, que ha dejado de funcionar.

2.2.4	- Solucionado error con las URLs internas del plugin y el Domain Mapping
		- Solucionado error con WPML y orden de carga del idioma.

2.2.5	- Cambio de version en el README!!

2.2.6	- Error en llamada a funcion (plugin_dir_url)!! Sorry!!

2.2.7	- Re-Tagged last version.

2.2.8	- Cambio en el sistema de caché para acelerar la carga de las páginas.

2.2.9	- Reducidos los timeouts de las solicitudes a "1".

*** Configuraciones ***
SOCIAL_COUNTER__LOAD_CSS : Ponerlo a FALSE para que no cargue el estilo por defecto.
SOCIAL_COUNTER__LOAD_CSS_SMALL : Ponerlo a TRUE para que cargue solo los iconos.
SOCIAL_COUNTER__TWITTER_USER : Indicar el Usuario de RT para Twitter.


*** ToDo ***
2.3.0	- Pagina de Opciones
3.0.0	- Convertir en Clase

*/


### Opciones generales!!!
if ( !defined('SOCIAL_COUNTER__LOAD_CSS') )
	define('SOCIAL_COUNTER__LOAD_CSS', true);

if ( !defined('SOCIAL_COUNTER__LOAD_CSS_SMALL') )
	define('SOCIAL_COUNTER__LOAD_CSS_SMALL', false);


### Use WordPress 2.6 Constants
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH.'wp-content');

if ( !defined('WP_CONTENT_URL') )
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');


// Rutas de acceso al plugno
function social_counters__path() {
	return plugin_dir_path( __FILE__ );
}

function social_counters__url() {
	return  plugin_dir_url(__FILE__);
}


// Cargamos todo lo necesario
require_once('social-counters-admin.php');

require_once('counters/tuenti.php');
//require_once('counters/google-buzz.php');
require_once('counters/meneame.php');
require_once('counters/twitter.php');
require_once('counters/facebook.php');
require_once('counters/bitacoras.php');
require_once('counters/linkedin.php');


/* CSS Load */
function social_counters__init() {

	// Cargamos los idiomas
	load_plugin_textdomain('social-counters', '', dirname( plugin_basename( __FILE__ ) ) .'/langs/');

	if ( SOCIAL_COUNTER__LOAD_CSS )
		wp_enqueue_style( 'social-counters', social_counters__url() .'css/social-counters.css', array(), '2.2.3', 'screen');

}
add_action('init', 'social_counters__init');


function social_counter__wp_head() {
	if ( !is_admin() )
		do_action('social_counter__wp_head');
}
add_action('wp_head', 'social_counter__wp_head');


/* Print  */
function the_social_counters( $social_counters = array(), $postparam = false ) {
	echo the_social_counters__get($social_counters, $postparam);
}

function the_social_counters__get( $social_counters = array(), $postparam = false ) {

	$default_social_counters = apply_filters('social_counter__defaults', array());


	$social_counters = ( sizeof($social_counters) > 0 ) ? $social_counters : $default_social_counters;

	$codeExit = '';
	foreach ( $social_counters as $social_counter ) {
		$codeExit .= social_counter__get($social_counter, $postparam);
	}

	$mini_counters = ( defined('SOCIAL_COUNTER__LOAD_CSS_SMALL') ) ? SOCIAL_COUNTER__LOAD_CSS_SMALL : false;

	$codeExit = ( !empty($codeExit) ) ? '<div class="social-counters'. ( $mini_counters ? ' social-counters-mini' : '' ) .'">'. $codeExit .'</div>' : '';

	return $codeExit;
}


/* */
function social_counter( $social_counter = '', $postparam = false, $linked = true ) {
	echo social_counter__get( $social_counter, $postparam, $linked );
}

function social_counter__get( $social_counter = '', $postparam = false, $linked = true ) {

	$codeExit = apply_filters('social_counter__get__'.$social_counter, '', $postparam, $linked);
	$codeExit  = ( empty($codeExit) ) ? false : $codeExit;

	return $codeExit;
}


function social_counters__the_title__filter( $title ) {

	$title = str_replace(
		array(
			'&#8220;', // "
			'&#8221;', // "
			'&amp;#8220;', // "
			'&amp;#8221;', // "
			'&quot;', // "
			'&amp;quot;', // "

			'&#8230;' // ...
		),
		array(
			'"',
			'"',
			'"',
			'"',
			'"',
			'"',

			'...',
		),
		$title
	);

	return $title;
}
add_filter('social_counters__the_title', 'social_counters__the_title__filter');

