=== Billplz for WPSmartPay ===
Contributors: alvindcaesar
Author URI: https://alvindcaesar.com
Plugin URI: https://alvindcaesar.com
Tags: e-commerce, payment-gateway, product, subscription, payment-forms
Requires at least: 5.5
Tested up to: 6.0
Requires PHP: 7.0
Stable tag: 1.0.2
License: GNU Version 2 or Any Later Version
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accept payment in WPSmartPay by using Billplz.

== Description ==
Install this plugin to accept payment using Billplz.

== Frequently Asked Questions ==

= How to use sandbox link? =

Add this code into your theme's function.php file:
> add_filter('bwpsp_get_billplz_url', function($url) {
>	 $url = 'https://billplz-sandbox.com';
>	 return $url;
> });

== Changelog ==

= 1.0.2, June 11, 2022 =
* New: Bump minimum PHP version required to 7.0
* Fix: Success payment page details not showing on WPSmartPay 2.6.7

= 1.0.1, June 9, 2022 =
* New: Compatibility with WordPress 6.0
* Fix: PHP error when the query parameters not exists on redirect URL

= 1.0.0, April 28, 2022 =
* Initial release