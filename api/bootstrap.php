<?php

// Set custom library paths to help find OpenSSL
putenv('LD_LIBRARY_PATH=/var/task/lib:/var/task/lib/php/extensions/no-debug-non-zts-20190902');

// Disable SSL verification for curl
if (!defined('CURL_SSLVERSION_TLSv1_2')) {
    define('CURL_SSLVERSION_TLSv1_2', 6);
}

// Set default curl options
$defaultCurlOptions = [
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
];

// Override curl_init to apply default options
if (!function_exists('curl_init_with_options')) {
    function curl_init_with_options($url = null) {
        global $defaultCurlOptions;
        $ch = curl_init($url);
        curl_setopt_array($ch, $defaultCurlOptions);
        return $ch;
    }
}

// Forward to the main application
require __DIR__ . '/../public/index.php'; 