<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Blog Designer - WordPress Post and Widget
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Bdwpw_Script {
	
	function __construct() {

		// Action to add style at front side
		add_action('wp_enqueue_scripts', array($this, 'bdwpw_front_style'));

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'bdwpw_front_script') );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0
	 */
	function bdwpw_front_style() {

		// Registring and enqueing slick css
		if( !wp_style_is( 'wpoh-slick-style', 'registered' ) ) {
			wp_register_style( 'wpoh-slick-style', BDWPW_URL.'assets/css/slick.css', array(), BDWPW_VERSION );
			wp_enqueue_style( 'wpoh-slick-style');	
		}
		
		// Registring and enqueing public css
		wp_register_style( 'bdwpw-public-css', BDWPW_URL.'assets/css/bdwpw-public.css', null, BDWPW_VERSION );
		wp_enqueue_style( 'bdwpw-public-css' );
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0
	 */
	function bdwpw_front_script() {

		// Registring slick slider script
		if( !wp_script_is( 'wpoh-slick-jquery', 'registered' ) ) {
	        wp_register_script( 'wpoh-slick-jquery', BDWPW_URL.'assets/js/slick.min.js', array('jquery'), BDWPW_VERSION, true );
	    }
	    
	    // Registring public script
	    wp_register_script( 'bdwpw-public-js', BDWPW_URL.'assets/js/bdwpw-public-js.js', array('jquery'), BDWPW_VERSION, true );
	    wp_localize_script( 'bdwpw-public-js', 'Bdwpw', array(
	                                                        'is_mobile' => (wp_is_mobile()) ? 1 : 0,
	                                                        'is_rtl'    => (is_rtl()) ? 1 : 0
	                                                    ));
	}
}

$bdwpw_script = new Bdwpw_Script();