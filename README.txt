=== FPX Payment for WPSmartPay (Billplz) ===
Contributors: alvindcaesar
Author URI: https://alvindcaesar.com
Plugin URI: https://wpkartel.com/fpx-payment-for-wpsmartpay/
Tags: e-commerce, payment-gateway, product, subscription, payment-forms
Requires at least: 5.5
Tested up to: 6.0.1
Requires PHP: 7.0
Stable tag: 1.0.3
License: GNU Version 2 or Any Later Version
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accept payment in WPSmartPay by using Billplz.

== Description ==
This is a gateway extension for WPSmartPay plugin to use Billplz Payment Gateway.

Payments are processed offsite at [Billplz](https://billplz.com) and the customer will be redirected back to your site after completing the payment.

⚡ In addition to this free plugin, an extension for more Malaysia's payment gateway (Toyyibpay, Bizappay and more) can be purchased here: [FPX Payment for WPSmartPay](https://wpkartel.com/fpx-payment-for-wpsmartpay/)

== Frequently Asked Questions ==

= I can't install the plugin, the plugin is displayed incorrectly =

Please temporarily enable the [WordPress Debug Mode](https://codex.wordpress.org/Debugging_in_WordPress). Edit your `wp-config.php` and set the constants `WP_DEBUG` and `WP_DEBUG_LOG` to `true` and try
it again. When the plugin triggers an error, WordPress will log the error to the log file `/wp-content/debug.log`. Please check this file for errors. When done, don't forget to turn off
the WordPress debug mode by setting the two constants `WP_DEBUG` and `WP_DEBUG_LOG` back to `false`.

= I get a white screen when opening ... =

Most of the time a white screen means a PHP error. Because PHP won't show error messages on default for security reasons, the page is white. Please turn on the WordPress Debug Mode to turn on PHP error messages (see previous answer).

== Changelog ==

= 1.0.3, July 29, 2022 =
* New: Billplz Sandbox setting is now available.
* New: An admin notice will show up as a reminder when Smartpay Test Mode is on.
* New: Compatibility with Pro version of the plugin [(FPX Payment For WPSmartPay)](https://wpkartel.com/fpx-payment-for-wpsmartpay/).
* Improve: Minor codebase optimization.

= 1.0.2, June 11, 2022 =
* New: Bump minimum PHP version required to 7.0
* Fix: Success payment page details not showing on WPSmartPay 2.6.7

= 1.0.1, June 9, 2022 =
* New: Compatibility with WordPress 6.0
* Fix: PHP error when the query parameters not exists on redirect URL

= 1.0.0, April 28, 2022 =
* Initial release