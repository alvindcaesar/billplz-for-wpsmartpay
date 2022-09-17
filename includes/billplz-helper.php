<?php

/**
 * Retrieve Billplz URL helper function
 *
 * @return void
 */
function bwpsp_get_billplz_url()
{
  if (smartpay_is_test_mode()) {
    $url = 'https://billplz-sandbox.com';
  } else {
    $url = 'https://billplz.com';
  }
  return $url;
}

function bwpsp_get_billplz_secret_key()
{
  if (smartpay_is_test_mode()) {
    $secret_key = base64_encode(smartpay_get_option('billplz_sandbox_secret_key'));
  } else {
    $secret_key = base64_encode(smartpay_get_option('billplz_secret_key'));
  }
  return $secret_key;
}

function bwpsp_get_billplz_collection_id()
{
  if (smartpay_is_test_mode()) {
    $collection_id = smartpay_get_option('billplz_sandbox_collection_id');
  } else {
    $collection_id = smartpay_get_option('billplz_collection_id');
  }
  return $collection_id;
}

function bwpsp_get_billplz_xsignature_key()
{
  if (smartpay_is_test_mode()) {
    $xsignature_key = smartpay_get_option('billplz_sandbox_xsignature_key');
  } else {
    $xsignature_key = smartpay_get_option('billplz_xsignature_key');
  }
  return $xsignature_key;
}
