<?php
/**
 * Plugin Name: Sitewide Notice Calendar WP
 * Description: Adds a simple message bar to the front-end of your website.
 * Plugin URI: https://yoohooplugins.com
 * Version: 2.0.4
 * Author: Yoohoo Plugins
 * Author URI: https://yoohooplugins.com
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sitewide-notice-wp
 *
 * Sitewide Notice WP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Sitewide Notice WP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sitewide Notice WP. If not, see http://www.gnu.org/licenses/gpl.html
 *
**/

defined( 'ABSPATH' ) or exit;

/**
 * INCLUDES
 */
include 'sitewide-notice-settings.php'; //all admin code can be found in here.

class SiteWide_Notice_Event_WP {

    /** Refers to a single instance of this class. */
    private static $instance = null;

    /**
    * Creates or returns an instance of this class.
    *
    * @return  Sitewide_Notice_Event_WP A single instance of this class.
    */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
            self::$instance->hooks();
        }
        return self::$instance;
    } // end get_instance;

    /**
    * Initializes the plugin by setting localization, filters, and administration functions.
    */
    private function __construct() {

    } //end of construct


    private static function hooks() {
        global $pagenow;

        //run this code regardless if the actual banner is activated or not.
        add_action( 'init', array( 'SiteWide_Notice_Event_WP', 'init' ) );

        $swneza_options = get_option( 'swneza_options' );

        if( $swneza_options['active'] && !is_admin() && ( $pagenow !== 'wp-login.php' ) ) {
            add_action( 'wp_footer', array( 'SiteWide_Notice_Event_WP', 'display_sitewide_notice_banner' ) );
            add_action( 'wp_enqueue_scripts', array( 'SiteWide_Notice_Event_WP', 'enqueue_scripts' ) );
        }
    }

    public static function init() {

        if( isset( $_REQUEST['remove_swneza_settings'] ) || !empty( $_REQUEST['remove_swneza_settings'] ) ) {
            delete_option( 'swneza_options' );
        }
    }

    public static function enqueue_scripts() {
        wp_enqueue_style( 'swneza_css', plugins_url( '/css/swneza.css', __FILE__ ) );
        wp_enqueue_script( 'swneza_css', plugins_url( '/js/jquery_cookie.js', __FILE__ ), array( 'jquery' ), '2.1.4', true );
    }

    public static function display_sitewide_notice_banner() {
       $swneza_options = get_option( 'swneza_options' );

        if( ( isset( $swneza_options[ 'hide_for_logged_in' ] ) && ! empty( $swneza_options['hide_for_logged_in'] ) ) && is_user_logged_in() ) {
            return;
        }

        if( ( !isset( $swneza_options[ 'events' ] ) || empty( $swneza_options['events'] ) ) ) {
            return;
        }

        if( $swneza_options[ 'active' ] ) {

            $today = date("Y-m-d") . " 00:00";
            $tmrw = date("Y-m-d", strtotime('+2 days') ) . " 00:00";
        
            $args = [
                "start_date" => $today, 
                "end_date" => $tmrw,
                "eventDisplay" => "map"
            ];
            $args[Tribe__Events__Main::TAXONOMY] = implode(",", $swneza_options[ 'events' ]);

            $cats = get_terms(TribeEvents::TAXONOMY, array('hide_empty' => 0));

            $tribeEvents = tribe_get_events( $args );
            $todayEvents = [];
            $tomorrowEvents = [];
            $dateClass = "tomorrow";
            $compare1 = DateTime::createFromFormat("Y-m-d H:i", $today);
            foreach ( $tribeEvents as $event ){
                $compare2 = DateTime::createFromFormat("Y-m-d H:i:s", $event->event_date_utc);
                $compare2->setTime(0,0);
                $interval = date_diff($compare1, $compare2);

                if ($interval->days == 0) {
                    $todayEvents[] = $event->post_title;
                    $dateClass = "today";
                } else {
                    $tomorrowEvents[] = $event->post_title;
                }
            }

            if ( count($todayEvents) < 1 && count($tomorrowEvents) < 1 ) {
                return;
            }

            ?>

            <!-- SiteWide Notice WP Custom CSS -->
                <style type="text/css">
                    .swneza_banner{
                        position:fixed;
                        width:100%;
                        padding:10px 0px;
                        z-index:999;
                        display:block;
                    }  
                    .swneza_banner.today{
                        background:<?php echo $swneza_options['background_color_today'] ?>;
                    }  
                    .swneza_banner.tomorrow{
                        background:<?php echo $swneza_options['background_color_tomorrow'] ?>;
                    }  

                    <?php if( isset( $swneza_options['show_on_top'] ) && ! empty( $swneza_options['show_on_top'] ) ) { ?>
                        .admin-bar .swneza_banner { margin-top:32px; }
                        .swneza_banner { top:0; }
                        .swneza_close_button { bottom:-10px; }
                    <?php } else { ?> 
                        .swneza_banner{ bottom:0; }
                        .swneza_close_button { top:-10px;}
                    <?php } ?>   

                    .swneza_banner b {
                        text-align:center;
                        z-index:1000;
                        font-size:20px;
                        display:block;
                    }
                    .swneza_banner.today b {
                        color: <?php echo $swneza_options['font_color_today'] ?>;
                    } 
                    .swneza_banner.tomorrow b {
                        color: <?php echo $swneza_options['font_color_tomorrow'] ?>;
                    } 

                    .swneza_close_button{
                        display:block;
                        position:absolute;
                        right:5px;
                        width:20px;
                        height:20px;
                        background:url("<?php echo plugins_url( 'images/close-button.svg', __FILE__ ); ?>") no-repeat center center;
                        background-color:white;
                        border-radius:100px;
                        border: 1px solid #000;
                    }

                    .swneza_close_button:hover{
                        cursor: pointer;
                    }

                <?php if( $swneza_options[ 'show_on_mobile' ] != 1 ) { ?>
                    @media all and (max-width: 500px){
                    .swneza_banner{
                        display: none;
                    }
                }
                <?php } ?>
                </style>
                <?php } ?>

        <div class="swneza_banner <?php echo $dateClass; ?>" id="swneza_banner_id">
            <?php
                if (count($todayEvents) > 0) {
                    echo "<b>Closed Today - ".implode(", ", $todayEvents)."</b>";
                }
                if (count($tomorrowEvents) > 0) {
                    echo "<b>Closed Tomorrow - ".implode(", ", $tomorrowEvents)."</b>";
                }
            ?>
            <!-- <a id="swneza_close_button_link" class="swneza_close_button"></a> -->
        </div>

    <?php
    }
} //end of class

Sitewide_Notice_Event_WP::get_instance();
