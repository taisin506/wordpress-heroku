<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Post grid and filter ultimate
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Pgafu_Script {
	
	function __construct() {

		// Action to add script at admin side
		add_action( 'admin_enqueue_scripts', array($this, 'pgafu_admin_script') );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'pgafu_plugin_style') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'pgafu_plugin_script') );
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @package Post grid and filter ultimate
	 * @since 1.3
	 */
	function pgafu_admin_script( $hook ) {

		if( $hook == 'toplevel_page_pgafu-about' ) {

			// Registring admin script
			wp_register_script( 'pgafu-admin-script', PGAFU_URL.'assets/js/pgafu-admin.js', array('jquery'), PGAFU_VERSION, true );
			wp_enqueue_script( 'pgafu-admin-script' );
		}
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package Post grid and filter ultimate
	 * @since 1.0.0
	 */
	function pgafu_plugin_style(){		
		
		// Registring and enqueing public css
		wp_register_style( 'pgafu-public-style', PGAFU_URL.'assets/css/pgafu-public.css', array(), PGAFU_VERSION );
		wp_enqueue_style( 'pgafu-public-style');
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package Post grid and filter ultimate
	 * @since 1.0.0
	 */
	function pgafu_plugin_script() {		
		
		// Registring isotope js
		if( ! wp_script_is( 'wpos-isotope-js', 'registered' ) ) {
			wp_register_script( 'wpos-isotope-js', PGAFU_URL.'assets/js/isotope.pkgd.min.js', array('jquery', 'imagesloaded'), PGAFU_VERSION, true );
		}
				
		// Registring public js
		wp_register_script( 'pgafu-public-js', PGAFU_URL.'assets/js/pgafu-public.js', array('jquery'), PGAFU_VERSION, true );	
		
	}	
}

$pgafu_script = new Pgafu_Script();