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
        add_menu_page( 'Sitewide Notice', 'Sitewide Notice', 'manage_options', 'sitewide-notice-settings', array( $this, 'settings_page_content' ), 'dashicons-megaphone' );

    }

    /**
     * This is where all the settings are stored.
     * @since 1.0.0
     * @return void
     */
    public function settings_page_content() {

      //check to see if swnza_options exist
      $values = get_option( 'swnza_options', true );
      
      //default values
      if( empty( $values ) ){
        $values = array(
        'active'  =>  '1',
        'background_color'  =>  'rgba(255,255,255,1)',
        'font_color'  =>  'rgba(0,0,0,1)',
        'message' =>  '',
        'show_on_mobile'  =>  true,
        );
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
        
        if( isset( $_POST['background-color'] ) ){
          $values['background_color'] = $_POST['background-color'];
        }  

        if( isset( $_POST['font-color'] ) ){
          $values['font_color'] = $_POST['font-color'];
        }

        if( isset( $_POST['message'] ) ){
          $values['message'] = htmlspecialchars( $_POST['message'] );
        }

        if( isset( $_POST['custom_css'] ) ){
          $values['custom_css'] = htmlspecialchars( $_POST['custom_css'] );
        }
        
        //update the options stored in WordPress
        update_option( 'swnza_options', $values );
      }

      ?> 
    <html>
      <body>
        <div class="wrap">
          <h1 align="left"><?php _e('Sitewide Notice WP' , 'sitewide-notice-wp'); ?></h1> <hr/>

            <form action="" method="POST">
            <table class="form-table">
              <tr valign="top">
                <th scope="row">
                    <label for="active"><?php _e( 'Show Banner', 'sitewide-notice-wp' ); ?></label>
                </th>
                <td>
                <input type="checkbox" name="active" <?php if( $values['active'] ){ ?> checked <?php } ?> /> 
                </td>
              </tr>

              <tr>
                <th scope="row">
                <label for="show_on_mobile"><?php _e( 'Display Banner On Mobile Devices', 'sitewide-notice-wp' ); ?></label>
                </th>
                <td>
                   <input type="checkbox" name="show_on_mobile" <?php if( $values['show_on_mobile'] ){ ?> checked <?php } ?> /> 
                </td>
              </tr>
              
              <tr>
              <th scope="row">
                 <label for="background-color"><?php _e( 'Background Color', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                 <input type="text" name="background-color" class="color-picker" data-alpha="true" value="<?php echo $values['background_color']; ?>"/> 
              </td>
              </tr>
             
             <tr>
              <th scope="row">
                <label for="font-color"><?php _e( 'Font Color', 'sitewide-notice-wp' ); ?></label>
              </th>
              <td>
                <input type="text" name="font-color" class="color-picker" data-alpha="true" value="<?php echo $values['font_color']; ?>"/> 
              </td>
              </tr>

              <tr>
              <th scope="row">
                <label for="message" class="col-sm-2 control-label"><?php _e('Message:', 'sitewide-notice-wp'); ?> </label>
              </th>
              <td>
                <textarea name="message" cols="40" rows="5" ><?php echo stripcslashes( $values['message'] ); ?></textarea>
              </td>
              </tr>

              <tr>

              <th scope="row">
                <label for="custom_css" class="col-sm-2 control-label"><?php _e('Custom CSS:', 'sitewide-notice-wp'); ?> </label>
              </th>
              <td>
                 <textarea name="custom_css" cols="40" rows="5" ><?php echo  $values['custom_css']; ?></textarea>
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
}

$sitewide_notice_settings = new SiteWide_Notice_WP_Settings();
