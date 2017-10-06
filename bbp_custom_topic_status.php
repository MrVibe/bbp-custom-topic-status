<?php
/*
Plugin Name: Custom Topic Status
Plugin URI: http://www.Vibethemes.com
Description: Add custom topic status in BBPRess
Version: 1.0
Author: VibeThemes
Author URI: http://www.vibethemes.com
License: GPLv2
Text Domain: bbpcts
Domain Path: /languages/
*/

if ( !defined( 'ABSPATH' ) ) exit;


include_once 'includes/class.init.php';


add_action('plugins_loaded','wplms_eventon_translations');
function wplms_eventon_translations(){
	$locale = apply_filters("plugin_locale", get_locale(), 'bbpcts');
	$lang_dir = dirname( __FILE__ ) . '/languages/';
	$mofile        = sprintf( '%1$s-%2$s.mo', 'bbpcts', $locale );

	$mofile_local  = $lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;
	if ( file_exists( $mofile_global ) ) {
		load_textdomain( 'bbpcts', $mofile_global );
	} else {
		load_textdomain( 'bbpcts', $mofile_local );
	}	
}
