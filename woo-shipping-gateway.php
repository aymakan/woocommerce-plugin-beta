<?php
/**
 * Plugin Name: WooCommerce Aymakan Shipping
 * Plugin URI:
 * Description: WooCommerce Aymakan Shipping Carrier
 * Author: Abdul Shakoor
 * Author URI: https://www.aymakan.com.sa
 * Version: 1.2.0
 * License: GPLv2 or later
 * Text Domain: woo-aymakan-shipping
 * Domain Path: languages/
 */

define( 'AYMAKAN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Aymakan_Main' ) ) :

    /**
     * Aymakan main class.
     */
    class Aymakan_Main {
        /**
         * Plugin version.
         *
         * @var string
         */
        const VERSION = '1.0.0';

        /**
         * Instance of this class.
         *
         * @var object
         */
        protected static $instance = null;

        /**
         * Initialize the plugin
         */
        private function __construct() {
            add_action( 'init', array( $this, 'load_plugin_textdomain' ), -1 );

            // Checks with WooCommerce is installed.
            if ( class_exists( 'WC_Integration' ) ) {
                include_once AYMAKAN_PATH . 'includes/class-aymakan-shipping-helper.php';
                include_once AYMAKAN_PATH . 'includes/class-aymakan-shipping-form.php';
                include_once AYMAKAN_PATH . 'includes/class-aymakan-shipping-method.php';
                include_once AYMAKAN_PATH . 'includes/class-aymakan-shipping-create.php';
                add_filter( 'woocommerce_shipping_methods', array( $this, 'aymakan_add_method' ) );

            } else {
                add_action( 'admin_notices', array( $this, 'wc_aymakan_woocommerce_fallback_notice' ) );
            }

        }

        /**
         * Return an instance of this class.
         *
         * @return object A single instance of this class.
         */
        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            if ( null === self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Load the plugin text domain for translation.
         */
        public function load_plugin_textdomain() {
            load_plugin_textdomain( 'woo-aymakan-shipping', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        /**
         * Get main file.
         *
         * @return string
         */
        public static function get_main_file() {
            return __FILE__;
        }

        /**
         * Get plugin path.
         *
         * @return string
         */
        public static function get_plugin_path() {
            return plugin_dir_path( __FILE__ );
        }

        /**
         * Get templates path.
         *
         * @return string
         */
        public static function get_templates_path() {
            return self::get_plugin_path() . 'templates/';
        }

        /**
         * Add the Aymakan to shipping methods.
         *
         * @param array $methods
         *
         * @return array
         */
        function aymakan_add_method( $methods ) {
            $methods['aymakan'] = 'Aymakan_Shipping_Method';
            return $methods;
        }

    }

    add_action( 'plugins_loaded', array( 'Aymakan_Main', 'get_instance' ) );

endif;
