<?php
/*
Plugin Name:Blog Designer - WP Post and Widget
Plugin URL: https://www.wponlinehelp.com/
Description: Display wordpress Post on your website with Grid and Slider designs and also use with  widget.
Version: 1.2
Author: wp online help
Author URI: http://www.jdtechnolab.com/
Contributors: WP Online Help
Text Domain: blog-designer-wp-post-and-widget
Domain Path: /languages/
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Basic plugin definitions
 * 
 * @package Blog Designer - WordPress Post and Widget
 * @since 1.0.0
 */
if( !defined( 'BDWPW_VERSION' ) ) {
    define( 'BDWPW_VERSION', '1.2' ); // Version of plugin
}
if( !defined( 'BDWPW_DIR' ) ) {
    define( 'BDWPW_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'BDWPW_URL' ) ) {
    define( 'BDWPW_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'BDWPW_PLUGIN_BASENAME' ) ) {
    define( 'BDWPW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}
if( !defined('BDWPW_POST_TYPE') ) {
    define('BDWPW_POST_TYPE', 'post'); // Post type name
}
if( !defined('BDWPW_CAT') ) {
    define('BDWPW_CAT', 'category'); // Plugin category name
}
/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Blog Designer - WordPress Post and Widget
 * @since 1.0
 */
function bdwpw_load_textdomain() {
    load_plugin_textdomain( 'blog-designer-wp-post-and-widget', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
// Action to load plugin text domain
add_action('plugins_loaded', 'bdwpw_load_textdomain');



// Functions file
require_once( BDWPW_DIR . '/includes/bdwpw-functions.php' );

// Script Class File
require_once( BDWPW_DIR . '/includes/class-bdwpw-script.php' );

// Admin Class File
require_once( BDWPW_DIR . '/includes/admin/class-bdwpw-admin.php' );

// Shortcode File
require_once( BDWPW_DIR . '/includes/shortcode/wpoh-post.php' );
require_once( BDWPW_DIR . '/includes/shortcode/wpoh-recent-post-slider.php' );

// Widget File
require_once( BDWPW_DIR . '/includes/widget/latest-post-widget.php' );