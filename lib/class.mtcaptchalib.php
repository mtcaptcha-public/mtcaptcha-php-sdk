<?php
class MTCaptchaLib {
    public static $connectTimeout = 15;
    public static $socketTimeout  = 15;
    public function __construct($private_key) {
        $this->private_key = $private_key;
    }


    /**
     * Validate MTCaptcha Verification Token
     *
     * @param      $verification_token
     * @return     mixed|string
     */
    public function validate_token($verification_token) {
        if (!$verification_token) {
            $result = new stdclass();
            $result->success = false;
            $result->message = "verification token require";
            return json_encode($result);
        }
        $url          = "https://service.mtcaptcha.com/mtcv1/api/checktoken";
        $validate_token_result = $this->post_request($url, $verification_token);
        return $validate_token_result;
    }

    /**
     * post request to MTCaptcha Server
     * @param       $url
     * @param array $postdata
     * @return mixed|string
     */
    private function post_request($url, $verification_token) {
        $mtcaptcha_service_payload = $url."?privatekey=".$this->private_key."&token=".$verification_token;

        if (function_exists('curl_exec')) {
            $curl = curl_init();            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $mtcaptcha_service_payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ));
        
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $err = sprintf("curl[%s] error[%s]", $url, curl_errno($curl) . ':' . curl_error($curl));
                $result = new stdclass();
                $result->success = false;
                $result->message = $err;
                return json_encode($result);
            }

            curl_close($curl);
        } else {
            $opts    = array(
                'http' => array(
                    'method'  => 'GET',
                    'timeout' => self::$connectTimeout + self::$socketTimeout
                )
            );
            $context = stream_context_create($opts);
            $response    = file_get_contents($mtcaptcha_service_payload, false, $context);            
        }

        return $response;
    }
}
