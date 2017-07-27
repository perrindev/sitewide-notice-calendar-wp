<?php

defined( 'ABSPATH' ) or exit;

class SiteWide_Notice_WP_Settings{

	//all hooks go here
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		
	}

	 public function admin_init() {

    }

    public function admin_enqueue_scripts() {
		//enable color wheel
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-alpha', plugins_url( '/js/wp-color-picker-alpha.min.js', __FILE__ ), array( 'wp-color-picker' ), '1.2.2', true );
	}
    /**
     * Adds menu link to WordPress Dashboard
     * @since 1.0.0
     * @return void
     */
    public function admin_menu() {
        add_menu_page( 'Sitewide Notice', 'Sitewide Notice', 'manage_options', 'sitewide-notice-settings', array( 'SiteWide_Notice_WP_Settings', 'settings_page_content' ), 'dashicons-megaphone' );

    }

    /**
     * This is where all the settings are stored.
     * @since 1.0.0
     * @return void
     */
    public function settings_page_content() {
       ?> 
       <h2><?php _e( 'SiteWide Notice Settings', 'sitewide-notice-wp' ); ?></h2>
       <label for="background-color"><?php _e( 'Background Color', 'sitewide-notice-wp' ); ?></label>
       <input type="text" name="background-color" class="color-picker" data-alpha="true"/> 
       <?php
    }	
}

$sitewide_notice_settigns = new SiteWide_Notice_WP_Settings();
