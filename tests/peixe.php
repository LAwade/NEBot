<?php
// Clients public and private key provided by service provider
$public_key = "aa078e8e5b463b42ec077658faf67f19bbd00c97b68de94a";
$private_key = "9d55533ed3dab0fd3af58423a5dd811b818fa91e339a0cae";

// Define the request parameter's
$method = "GET";
$request = "/user";

$timestamp = gmdate('D, d M Y H:i:s T'); // Date as per RFC2616 - Wed, 25 Nov 2014 12:45:26 GMT

// Creating content to sign with private key
$content_to_sign = $method.$request.$timestamp.$public_key;

// Hash content to sign into HMAC signature
$signature = hash_hmac("sha256", $content_to_sign, $private_key);

// Add required headers
// Authorization: hmac public_key:signature
// Date: Wed, 25 Nov 2014 12:45:26 GMT
$headers = [
    "Accept: application/json",
    "Authorization: hmac {$public_key}:{$signature}",
    "Date: {$timestamp}"
];

// Prepare and make https request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.fieldclimate.com/v2" . $request);
// SSL important
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$output = curl_exec($ch);
curl_close($ch);

// Parse response as json and work on it ..
echo $output. PHP_EOL;