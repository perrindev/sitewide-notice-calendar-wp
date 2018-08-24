<?php

defined( 'ABSPATH' ) or exit;

class SiteWide_Notice_WP_Settings{

  //all hooks go here
  public function __construct() {

    add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

  }

  	/**
  	 * Sanitize function for hex + rgba colors.
  	 * Reference: https://wordpress.stackexchange.com/questions/257581/escape-hexadecimals-rgba-values
  	 */
	public static function sanitize_hex_rgba( $color ) {
		if ( empty( $color ) || is_array( $color ) )
		return 'rgba(0,0,0,0)';

		// If string does not start with 'rgba', then treat as hex
		// sanitize the hex color and finally convert hex to rgba
		if ( false === strpos( $color, 'rgba' ) ) {
		return sanitize_hex_color( $color );
		}

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
	}

   public function admin_init() {

    }

    public function admin_enqueue_scripts() {
    //enable color wheel
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-alpha', plugins_url( '/js/wp-color-picker-alpha.min.js', __FILE__ ), array( 'wp-color-picker' ), '2.1.3', true );
  }
    /**
     * Adds menu link to WordPress Dashboard
     * @since 1.0.0
     * @return void
     */
    public function admin_menu() {
        add_menu_page( 'Sitewide Notice', 'Sitewide Notice', 'manage_options', 'sitewide-notice-settings', array( $this, 'settings_page_content' ), 'dashicons-megaphone' );

    }

    /**
     * This is where all the settings are stored.
     * @since 1.0.0
     * @return void
     */
    public function settings_page_content() {

      //check to see if swnza_options exist
      $values = get_option( 'swnza_options' );

      //default values
      if( empty( $values ) ){

        $values = array();

        $values['active'] = '1';
        $values['background_color'] = 'rgba(255,255,255,1)';
        $values['font_color'] = 'rgba(0,0,0,1)';
        $values['message'] = '';
        $values['show_on_mobile'] = true;
        $values['hide_for_logged_in'] = false;
        $values['show_on_top'] = false;
        if( defined( 'PMPRO_VERSION' ) ){
          $values['show_for_members'] = false;
        }

      }

      //If they have submitted the form.
      if( isset( $_POST['submit'] ) ) {

        if( isset( $_POST['active'] ) &&  $_POST['active'] === 'on' ){
          $values['active'] = 1;
        }else{
          $values['active'] = 0;
        }

        if( isset( $_POST['show_on_mobile'] ) && $_POST['show_on_mobile'] === 'on' ){
          $values['show_on_mobile'] = 1;
        }else{
          $values['show_on_mobile'] = 0;
        }

        if( isset( $_POST['hide_for_logged_in'] ) && $_POST['hide_for_logged_in'] === 'on' ){
          $values['hide_for_logged_in'] = 1;
        }else{
          $values['hide_for_logged_in'] = 0;
        }

        if( isset( $_POST['show_on_top'] ) && $_POST['show_on_top'] === 'on' ){
          $values['show_on_top'] = 1;
        }else{
          $values['show_on_top'] = 0;
        }

        if( isset( $_POST['background-color'] ) ){
          $values['background_color'] = SiteWide_Notice_WP_Settings::sanitize_hex_rgba( $_POST['background-color'] );
        }

        if( isset( $_POST['font-color'] ) ){
          $values['font_color'] =  SiteWide_Notice_WP_Settings::sanitize_hex_rgba( $_POST['font-color'] );
        }

        if( isset( $_POST['message'] ) ){
          $values['message'] = htmlspecialchars( $_POST['message'] );
        }

        // Check if PMPro exists, and update settings.
        if( defined( 'PMPRO_VERSION' ) ){
          if( isset( $_POST['show_for_members'] ) && $_POST['show_for_members'] === 'on' ){
            $values['show_for_members'] = 1;
          }else{
            $values['show_for_members'] = 0;
          }
        }

        //update the options stored in WordPress
        if( update_option( 'swnza_options', $values ) ) {
            SiteWide_Notice_WP_Settings::admin_notices_success();
        }

      }

      ?>
    <html>
      <body>
        <div class="wrap">
          <h1 align="left"><?php _e('Sitewide Notice WP' , 'sitewide-notice-wp'); ?></h1> <hr/>

            <form action="" method="POST">
            <table class="form-table">
              <tr valign="top">
                <th scope="row" width="50%">
                    <label for="active"><?php _e( 'Show Banner', 'sitewide-notice-wp' ); ?></label>
                </th>
                <td width="50%">
                <input type="checkbox" name="active" <?php if( isset( $values['active'] ) && ! empty( $values['active'] ) ){ echo 'checked'; } ?> />
                </td>
              </tr>

              <tr>
                <th scope="row">
                <label for="show_on_mobile"><?php _e( 'Display Banner On Mobile Devices', 'sitewide-notice-wp' ); ?></label>
                </th>
                <td>
                   <input type="checkbox" name="show_on_mobile" <?php if( isset( $values['show_on_mobile'] ) && ! empty( $values['show_on_mobile'] ) ){ echo 'checked'; } ?> />
                </td>
              </tr>

              <tr>
                <th scope="row">
                <label for="hide_for_logged_in"><?php _e( 'Hide Banner For Logged-in Users', 'sitewide-notice-wp' ); ?></label>
                </th>
                <td>
                   <input type="checkbox" name="hide_for_logged_in" <?php if( isset( $values['hide_for_logged_in'] ) && ! empty( $values['hide_for_logged_in'] ) ){ echo 'checked'; } ?> />
                </td>
              </tr>


              <tr>
                <th scope="row">
                  <label for="show_on_top"><?php _e( 'Show Banner On Top Of Screen', 'sitewide-notice-wp' ); ?></label>
                </th>
                <td><input type="checkbox" name="show_on_top" <?php if( isset( $values['show_on_top'] ) && ! empty( $values['show_on_top'] ) ) { echo 'checked'; } ?>/></td>
              </tr>

              <?php if( defined( 'PMPRO_VERSION' ) ) { ?>
                <tr>
                  <th scope="row">
                  <label for="show_for_members"><?php _e( 'Display Banner For PMPro Members', 'sitewide-notice-wp' ); ?></label>
                  </th>
                  <td>
                     <input type="checkbox" name="show_for_members" <?php if( isset( $values['show_for_members'] ) && ! empty( $values['show_for_members'] ) ){ echo 'checked'; } ?> />
                  </td>
                </tr>
              <?php } ?>

              <tr>
              <th scope="row">
                 <label for="background-color"><?php _e( 'Background Color', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                 <input type="text" name="background-color" class="color-picker" data-alpha="true" value="<?php echo esc_attr( $values['background_color'] ); ?>"/>
              </td>
              </tr>

             <tr>
              <th scope="row">
                <label for="font-color"><?php _e( 'Font Color', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                <input type="text" name="font-color" class="color-picker" data-alpha="true" value="<?php echo esc_attr( $values['font_color'] ); ?>"/>
              </td>
              </tr>

              <tr>
              <th scope="row">
                <label for="message" class="col-sm-2 control-label"><?php _e('Message', 'sitewide-notice-wp'); ?> </label>
              </th>
              <td>
                <textarea name="message" cols="40" rows="5" ><?php echo stripcslashes( $values['message'] ); ?></textarea>
              </td>
              </tr>

             <tr>
              <th scope="row">
              <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Settings', 'sitewide-notice-wp' ); ?>"/></th>
              </tr>
          </table>
        </form>
        </div>
      </body>
    </html>
       <?php
    }

    private static function admin_notices_success() {
      ?>
    <div class="notice notice-success is-dismissible">
      <p><strong><?php _e( 'Settings saved.' ,'sitewide-notice-wp' ); ?></strong></p>
      <button type="button" class="notice-dismiss">
        <span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'sitewide-notice-wp' ); ?></span>
      </button>
    </div>
    <?php
    }
} //end class

$sitewide_notice_settings = new SiteWide_Notice_WP_Settings();
