<?php
/**
 * Plugin Name: Sitewide Notice WP
 * Description: Simply add a notification bar to the bottom of your WordPress website.
 * Plugin URI: https://yoohooplugins.com
 * Version: 2.0.0
 * Author: YooHoo Plugins
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

class SiteWide_Notice_WP {

	/** Refers to a single instance of this class. */
    private static $instance = null;

    /**
    * Creates or returns an instance of this class.
    *
    * @return  Sitewide_Notice_WP A single instance of this class.
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

    }


} //end of class

Sitewide_Notice_WP::get_instance();

