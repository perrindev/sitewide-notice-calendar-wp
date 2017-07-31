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
        );
      }
     
      //If they have submitted the form.
      if( isset( $_POST['submit'] ) ) {
        
        if( isset( $_POST['active'] ) &&  $_POST['active'] === 'on' ){
          $values['active'] = 1;
        }else{
          $values['active'] = 0;
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
        
        //update the options stored in WordPress
        update_option( 'swnza_options', $values );
      }

      ?> 
    <html>
      <body>
        <div class="wrap">
          <h1 align="left"><?php _e('Sitewide Notice WP' , 'sitewide-notice-wp'); ?></h1> <hr/>
          <form class="form-horizontal" action="" method="POST">

           <div class="form-group">
            <label for="active"><?php _e( 'Show Banner', 'sitewide-notice-wp' ); ?></label>
            <input type="checkbox" name="active" <?php if( $values['active'] ){ ?> checked <?php } ?> /> 
          </div>

          <div class="form-group">
            <label for="background-color"><?php _e( 'Background Color', 'sitewide-notice-wp' ); ?></label>
            <input type="text" name="background-color" class="color-picker" data-alpha="true" value="<?php echo $values['background_color']; ?>"/> 
          </div>

          <div class="form-group">
            <label for="font-color"><?php _e( 'Font Color', 'sitewide-notice-wp' ); ?></label>
            <input type="text" name="font-color" class="color-picker" data-alpha="true" value="<?php echo $values['font_color']; ?>"/> 
          </div>

          <div class="form-group">
            <label for="message" class="col-sm-2 control-label"><?php _e('Text message:', 'swnza'); ?> </label>
            <textarea name="message" cols="40" rows="5" ><?php echo stripcslashes( $values['message'] ); ?></textarea>
          </div>

          <br/>

          <div class="form-group">
            <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Settings', 'sitewide-notice-wp' ); ?>"/>
          </div>

          </div>
        </form>
        </div>
      </body>
    </html>
       <?php
    }	
}

$sitewide_notice_settings = new SiteWide_Notice_WP_Settings();
