<?php

// Title 		: File to read a FB encoded Signed Request
// Created By	: Colm Doyle <colmdoyle@fb.com>
// Created on 	: February 15, 2011 

include('config.php');
function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

echo("<pre>\n");
print_r($_REQUEST['signed_request']);
echo("</pre>\n");

echo("<pre>\n");
print_r(parse_signed_request($_REQUEST['signed_request'],$appsecret));
echo("</pre>\n");

?>