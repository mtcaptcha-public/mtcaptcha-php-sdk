<?php
require_once dirname(dirname(__FILE__)) . '/lib/class.mtcaptchalib.php';
require_once dirname(dirname(__FILE__)) . '/config/config.php';
session_start();

$MTCaptchaSDK = new MTCaptchaLib(MTCAPTCHA_PRIVATE_KEY);
$result = $MTCaptchaSDK->validate_token($_POST['mtcaptcha-verifiedtoken']);

/*
Example Result

{
  "success": true,
  "tokeninfo": {
    "v": "1.0",
    "code": 201,
    "codeDesc": "valid:captcha-solved",
    "tokID": "4fbacb02e8b460062adfae67d0de8192",
    "timestampSec": 1561298161,
    "timestampISO": "2019-06-23T13:56:01Z",
    "hostname": "app.mtdemo.local",
    "isDevHost": false,
    "action": "",
    "ip": "117.196.99.246"
  }
}
*/

print_r($result);