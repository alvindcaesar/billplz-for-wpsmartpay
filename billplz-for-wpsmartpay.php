<?php

/**
 * @link              https://alvindcaesar.com/
 * @since             1.0.0
 * @package           Billplz_for_WPSmartPay
 *
 * @wordpress-plugin
 * Plugin Name:       FPX Payment for WPSmartPay (Billplz)
 * Plugin URI:        https://wpkartel.com/fpx-payment-for-wpsmartpay
 * Description:       Accept payment in WPSmartPay by using Billplz.
 * Version:           1.0.5
 * Author:            Alvind Caesar (WPKartel)
 * Author URI:        https://wpkartel.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       billplz-for-smartpay
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
  die;
}

if (!function_exists('is_plugin_active')) {
  include_once(ABSPATH . 'wp-admin/includes/plugin.php');
  if (! is_plugin_active('smartpay/smartpay.php') || ( is_plugin_active('fpx-payment-smartpay/fpx-payment-smartpay.php'))) {
    return;
  }
}

if (!class_exists('Billplz_WPSP')) {
  class Billplz_WPSP
  {
    private static $instance;

    public static function instance()
    {
      if (!isset(self::$instance) && !(self::$instance instanceof Billplz_WPSP)) {
        self::$instance = new Billplz_WPSP();
        self::$instance->define_constants();
        self::$instance->includes();
      }
      return self::$instance;
    }

    private function define_constants()
    {
      define('BILLPLZ_WPSP_PLUGIN_PATH', plugin_dir_path(__FILE__));
      define('BILLPLZ_WPSP_PLUGIN_URL',  plugin_dir_url(__FILE__));
      define('BILLPLZ_WPSP_PLUGIN_FILE', plugin_basename(__FILE__));
      define('BILLPLZ_WPSP_PLUGIN_NAME', 'Billplz for WPSmartPay');
      define('BILLPLZ_WPSP_PLUGIN_VERSION', '1.0.3');
    }

    private function includes()
    {
      require_once BILLPLZ_WPSP_PLUGIN_PATH . 'includes/billplz-helper.php';
      require_once BILLPLZ_WPSP_PLUGIN_PATH . 'includes/billplz.php';
    }
  }
}

add_action( 'plugins_loaded', array( 'Billplz_WPSP', 'instance'));