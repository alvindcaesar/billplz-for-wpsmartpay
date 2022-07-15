<?php

use SmartPay\Models\Form;
use SmartPay\Models\Payment;
use SmartPay\Models\Product;

if (!defined('ABSPATH')) {
  die;
}

/**
 * Add a link to payment gateway setting page
 *
 * @param [type] $links
 * @return void
 */
function bwpsp_setting_link($links)
{
  $mylinks = array(
    '<a href="' . admin_url('admin.php?page=smartpay-setting&tab=gateways') . '">Settings</a>',
  );
  return array_merge($links, $mylinks);
}


/**
 * Retrieve Billplz URL helper function
 *
 * @return void
 */
function bwpsp_billplz_url()
{
  if (smartpay_is_test_mode()) {
    $url = 'https://billplz-sandbox.com';
  } else {
    $url = 'https://billplz.com';
  }
  return $url;
}

/**
 * Register Billplz Payment Gateway
 *
 * @param array $gateways
 * @return array
 */
function bwpsp_register_gateway(array $gateways = array()): array
{
  $gateways['billplz'] = array(
    'admin_label'    => 'Billplz',
    'checkout_label' => 'Billplz',
    'gateway_icon'   =>  BILLPLZ_WPSP_PLUGIN_URL . '/assets/img/billplz.png',
  );
  return $gateways;
}

/**
 * Register for gateway activation on SmartPay Settings
 *
 * @param array $availableGateways
 * @return array
 */
function bwpsp_register_to_available_gateway_on_setting(array $availableGateways = array()): array
{
  $availableGateways['billplz'] = array(
    'label' => 'Billplz'
  );
  return $availableGateways;
}

/**
 * Register Billplz section setiing tab
 *
 * @param array $sections
 * @return array
 */
function bwpsp_gateway_section(array $sections = array()): array
{
  $sections['billplz'] = __('Billplz', 'billplz-for-smartpay');
  return $sections;
}

/**
 * Configuration Inputs
 *
 * @param array $settings
 * @return array
 */
function bwpsp_gateway_settings(array $settings): array
{
  $gateway_settings = array(
    array(
      'id' => 'billplz_settings',
      'name' => '<h4 class="text-uppercase text-info my-1">' . __('Billplz Settings', 'billplz-for-smartpay') . '</h4>',
      'desc' => __('Configure your Billplz Settings', 'billplz-for-smartpay'),
      'type' => 'header'
    ),

    array(
      'id'   => 'billplz_secret_key',
      'name'  => __('Secret Key', 'billplz-for-smartpay'),
      'desc'  => __('Enter secret key', 'billplz-for-smartpay'),
      'type'  => 'text',
    ),

    array(
      'id'   => 'billplz_collection_id',
      'name'  => __('Collection ID', 'billplz-for-smartpay'),
      'desc'  => __('Enter your Collection ID', 'billplz-for-smartpay'),
      'type'  => 'text',
    ),

    array(
      'id'   => 'billplz_xsignature_key',
      'name'  => __('X-Signature Key', 'billplz-for-smartpay'),
      'desc'  => __('Enter your X-Signature key', 'billplz-for-smartpay'),
      'type'  => 'text',
    ),

    array(
      'id' => 'billplz_sandbox_settings',
      'name' => '<h4 class="text-uppercase text-info my-1">' . __('Billplz Sandbox Settings', 'fpx-payment-smartpay') . '</h4>',
      'desc' => __('Configure your Billplz Sandbox Settings', 'fpx-payment-smartpay'),
      'type' => 'header'
    ),

    array(
      'id'   => 'billplz_sandbox_secret_key',
      'name'  => __('Sandbox Secret Key', 'fpx-payment-smartpay'),
      'desc'  => __('Enter your sandbox secret key', 'fpx-payment-smartpay'),
      'type'  => 'text',
    ),

    array(
      'id'   => 'billplz_sandbox_collection_id',
      'name'  => __('Sandbox Collection ID', 'fpx-payment-smartpay'),
      'desc'  => __('Enter your sandbox Collection ID', 'fpx-payment-smartpay'),
      'type'  => 'text',
    ),

    array(
      'id'   => 'billplz_sandbox_xsignature_key',
      'name'  => __('Sandbox X-Signature Key', 'fpx-payment-smartpay'),
      'desc'  => __('Enter your Sandbox X-Signature key', 'fpx-payment-smartpay'),
      'type'  => 'text',
    ),
  );

  return array_merge($settings, ['billplz' => $gateway_settings]);
}

function bwpsp_test_mode_warning()
{
  if (smartpay_is_test_mode() && smartpay_is_gateway_active('billplz')) {
    echo __(sprintf(
      '<div class="notice notice-warning">
            <p><strong>WPSmartPay: You are using Billplz for WPSmartPay in Test Mode. </strong> Make sure to switch to <a href="' . get_admin_url() . 'admin.php?page=smartpay-setting&tab=gateways">Live Mode</a> when you\'re ready to accept real payments.</p>
        </div>',
    ), 'billplz-for-smartpay');
  }
}

/**
 * Payment Processing
 *
 * @param [type] $paymentData
 * @return void
 */
function bwpsp_ajax_process_payment($paymentData)
{
  global $smartpay_options;
  $payment = smartpay_insert_payment($paymentData);


  if (!$payment->id) {
    die('Can\'t insert payment.');
  }

  if (Payment::FORM_PAYMENT === $paymentData['payment_type']) {
    $form         = Form::where('id', $payment->data['form_id'])->first();
    $productTitle = strtoupper($form->title);
  }

  if (Payment::PRODUCT_PURCHASE === $paymentData['payment_type']) {
    $product      = Product::where('id', $payment->data['product_id'])->first();
    $productTitle = strtoupper($product->title);
  }
  
  $payment_price = number_format($paymentData['amount'], 2);

  $return_url   = add_query_arg(array(
    'payment-id' => $payment->id,
    'smartpay-payment' => $payment->uuid
  ), smartpay_get_payment_success_page_uri());

  $callback_url = site_url('wp-json/billplz-smartpay/v1/bwpsp-callback');

  $args = array(
    'headers' => array(
      'Authorization' => 'Basic ' . base64_encode($smartpay_options['billplz_secret_key']) . ':',
    ),
    'body' => array(
      'collection_id' => $smartpay_options['billplz_collection_id'],
      'email'         => $payment->customer->email,
      'name'          => $payment->customer->full_name,
      'amount'        => strval($payment_price) * 100,
      'redirect_url'  => $return_url,
      'callback_url'  => $callback_url,
      'description'   => 'Payment for' . $productTitle,
      'reference_1_label' => 'Payment ID',
      'reference_1' => $payment->id
    )
  );
  $response    = wp_remote_post(bwpsp_billplz_url() . '/api/v3/bills', $args);

  if (is_wp_error($response)) {
    $error_message = $response->get_error_message();
    echo "Something went wrong: $error_message";
    die();
  }

  $apiBody     = json_decode(wp_remote_retrieve_body($response));
  $bill_url    = $apiBody->url;

  $content  = '<p class="text-center">Redirecting to Billplz...</p>';
  $content .= '<script>window.location.replace("' . $bill_url . '");</script>';

  $allowed_tags = array('p' => array(), 'script' => array());

  echo wp_kses($content, $allowed_tags);
}

/**
 * Register endpoint for callback url
 *
 * @return void
 */
function bwpsp_callback_url_endpoint()
{
  register_rest_route(
    'billplz-smartpay/v1',
    'bwpsp-callback',
    array(
      'methods'             => 'POST',
      'callback'            => 'bwpsp_process_callback',
      'permission_callback' => '__return_true'
    )
  );
}

/**
 * Process the callback from Billplz server
 *
 * @param [type] $request_data
 * @return void
 */
function bwpsp_process_callback($request_data)
{
  global $smartpay_options;

  $params         = $request_data->get_params();
  $transaction_id = $params['id'];

  $x_signature    = $smartpay_options['billplz_xsignature_key'];
  $x_sign         = $params['x_signature'];

  unset($params['x_signature']);

  $arr = array();
  foreach ($params as $k => $v) {
    array_push($arr, ($k . $v));
  }

  sort($arr);

  $new     = implode('|', $arr);

  $hash    = hash_hmac('sha256', $new, $x_signature);

  $args = array(
    'headers' => array(
      'Authorization' => 'Basic ' . base64_encode($smartpay_options['billplz_secret_key']) . ':',
    ),
  );

  $get_bill = wp_remote_get(bwpsp_billplz_url() . '/api/v3/bills/' . $transaction_id, $args);
  $apiBody  = json_decode(wp_remote_retrieve_body($get_bill));
  $payment_id = $apiBody->reference_1;

  $payment = Payment::find($payment_id);

  if (($params['state'] == 'paid') && ($hash == $x_sign) && ('completed' != $payment->status)) {
    $payment->updateStatus('completed');
    $payment->setTransactionId($transaction_id);
  }
}

/**
 * Process the redirect URL params
 *
 * @return void
 */
function bwpsp_process_payment_url()
{
  global $smartpay_options;

  if (empty($_GET)) {
    return;
  }

  $x_signature = $smartpay_options['billplz_xsignature_key'];
  $url         = htmlentities($_SERVER['QUERY_STRING']);
  parse_str(html_entity_decode($url), $query);

  if (empty($query['smartpay-payment']) && empty($query['payment-id']) && empty($query['billplz']['x_signature']) && empty($query['billplz']['id']) && empty($query['billplz']['paid']) && empty($query['billplz']['paid_at']) && empty($query['billplz']['id'])) {
    return;
  } else {

    ksort($query);

    $payment_id     = $query['payment-id'];
    $x_sign         = $query['billplz']['x_signature'];
    $transaction_id = $query['billplz']['id'];

    unset($query['billplz']['x_signature']);
    unset($query['payment-id']);
    unset($query['smartpay-payment']);

    $a = array();
    foreach ($query as $key => $value) {
      foreach ($value as $sub_key => $sub_val) {
        array_push($a, ($key . $sub_key . $sub_val));
      }
    }

    sort($a);

    $new     = implode("|", $a);

    $hash    = hash_hmac('sha256', $new, $x_signature);

    $payment = Payment::find($payment_id);

    if (isset($_GET['payment-id']) && ($hash == $x_sign) && ('true' == $query['billplz']['paid']) && ('completed' != $payment->status)) {
      $payment->updateStatus('completed');
      $payment->setTransactionId($transaction_id);
    } else {
      wp_redirect(smartpay_get_payment_failure_page_uri());
      die;
    }
  }
}

/** Register all actions and filters */
add_filter('plugin_action_links_' . BILLPLZ_WPSP_PLUGIN_FILE, 'bwpsp_setting_link');
add_filter('smartpay_gateways', 'bwpsp_register_gateway', 110);
add_filter('smartpay_get_available_payment_gateways', 'bwpsp_register_to_available_gateway_on_setting', 111);
add_filter('smartpay_settings_sections_gateways', 'bwpsp_gateway_section', 110);
add_filter('smartpay_settings_gateways', 'bwpsp_gateway_settings', 110);
add_action('admin_notices', 'bwpsp_test_mode_warning');
add_action('smartpay_billplz_ajax_process_payment', 'bwpsp_ajax_process_payment');
add_action('rest_api_init', 'bwpsp_callback_url_endpoint');
add_action('init', 'bwpsp_process_payment_url');
