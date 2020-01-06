<?php

defined( 'ABSPATH' ) or exit;

class SiteWide_Notice_Event_WP_Settings{

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
    wp_enqueue_script( 'wp-color-alpha', plugins_url( '/js/wp-color-picker-alpha.min.js', __FILE__ ), array( 'wp-color-picker' ), '2.1.3', true );
  }
    /**
     * Adds menu link to WordPress Dashboard
     * @since 1.0.0
     * @return void
     */
    public function admin_menu() {
        add_menu_page( 'Sitewide Notice Event', 'Sitewide Notice Event', 'manage_options', 'sitewide-notice-event-settings', array( $this, 'settings_page_content' ), 'dashicons-megaphone' );

    }

    /**
     * This is where all the settings are stored.
     * @since 1.0.0
     * @return void
     */
    public function settings_page_content() {

      //check to see if swneza_options exist
      $values = get_option( 'swneza_options' );

      //default values
      if( empty( $values ) ){

        $values = array();

        $values['active'] = '1';
        $values['background_color_today'] = 'rgba(255,255,255,1)';
        $values['font_color_today'] = 'rgba(0,0,0,1)';
        $values['background_color_tomorrow'] = 'rgba(255,255,255,1)';
        $values['font_color_tomorrow'] = 'rgba(0,0,0,1)';
        $values['events'] = [];
        // $values['message'] = '';
        $values['show_on_mobile'] = true;
        $values['hide_for_logged_in'] = false;
        $values['show_on_top'] = false;
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

        if( isset( $_POST['background-color-today'] ) ){
          $values['background_color_today'] = $_POST['background-color-today'];
        }

        if( isset( $_POST['font-color-today'] ) ){
          $values['font_color_today'] = $_POST['font-color-today'];
        }

        if( isset( $_POST['background-color-tomorrow'] ) ){
          $values['background_color_tomorrow'] = $_POST['background-color-tomorrow'];
        }

        if( isset( $_POST['font-color-tomorrow'] ) ){
          $values['font_color_tomorrow'] = $_POST['font-color-tomorrow'];
        }

        // if( isset( $_POST['message'] ) ){
        //   $values['message'] = htmlspecialchars( $_POST['message'] );
        // }

        if( isset( $_POST['events'] ) ){
          $values['events'] = $_POST['events'];
        }

        if( isset( $_POST['custom_css'] ) ){
          $values['custom_css'] = htmlspecialchars( $_POST['custom_css'] );
        }

        //update the options stored in WordPress
        if( update_option( 'swneza_options', $values ) ) {
            SiteWide_Notice_Event_WP_Settings::admin_notices_success();
        }
      }

      ?>
    <html>
      <body>
        <div class="wrap">
          <h1 align="left"><?php _e('Sitewide Notice Event WP' , 'sitewide-notice-wp'); ?></h1> <hr/>

            <form action="" method="POST">
            <table class="form-table">
              <tr valign="top">
                <th scope="row" width="50%">
                    <label for="active"><?php _e( 'Banner Enabled', 'sitewide-notice-wp' ); ?></label>
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

              <tr>
              <th scope="row">
                 <label for="background-color-today"><?php _e( 'Background Color - Current Day', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                 <input type="text" name="background-color-today" class="color-picker" data-alpha="true" value="<?php echo $values['background_color_today']; ?>"/>
              </td>
              </tr>

             <tr>
              <th scope="row">
                <label for="font-color-today"><?php _e( 'Font Color - Current Day', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                <input type="text" name="font-color-today" class="color-picker" data-alpha="true" value="<?php echo $values['font_color_today']; ?>"/>
              </td>
              </tr>

              <tr>
              <th scope="row">
                 <label for="background-color-tomorrow"><?php _e( 'Background Color - Tomorrow Only', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                 <input type="text" name="background-color-tomorrow" class="color-picker" data-alpha="true" value="<?php echo $values['background_color_tomorrow']; ?>"/>
              </td>
              </tr>

             <tr>
              <th scope="row">
                <label for="font-color-tomorrow"><?php _e( 'Font Color - Tomorrow Only', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                <input type="text" name="font-color-tomorrow" class="color-picker" data-alpha="true" value="<?php echo $values['font_color_tomorrow']; ?>"/>
              </td>
              </tr>

              <tr>
              <th scope="row">
                <label for="events" class="col-sm-2 control-label"><?php _e('Events:', 'sitewide-notice-wp'); ?> </label>
              </th>
              <td>
                <select name="events[]" multiple><?php 
                  $cats = get_terms(TribeEvents::TAXONOMY, array('hide_empty' => 0));
                  foreach ($cats as $cat) {
                    $selected = "";
                    if (in_array($cat->slug, $values['events'])) {
                      $selected = "selected=\"selected\"";
                    }
                    echo "<option value=".$cat->slug." $selected>".$cat->name."</option>";
                  }
                ?></select>
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

$sitewide_notice_settings = new SiteWide_Notice_Event_WP_Settings();
