<?php

define("CODECHA_SERVER", "codecha.org");
define("CODECHA_PATH", "/api/verify");

define("RECAPTCHA_SERVER", "google.com");
define("RECAPTCHA_PATH", "/recaptcha/api/verify");


function _qsencode($data) {
	$req = "";
	foreach ( $data as $key => $value )
		$req .= $key . '=' . urlencode( stripslashes($value) ) . '&';

	$req=substr($req,0,strlen($req)-1);
	return $req;
}


function _codecha_request($host, $path, $data) {
	$port = 80;

	$req = _qsencode ($data);

	$http_request  = "POST $path HTTP/1.0\r\n";
	$http_request .= "Host: $host\r\n";
	$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
	$http_request .= "Content-Length: " . strlen($req) . "\r\n";
	$http_request .= "User-Agent: CODECHA/PHP\r\n";
	$http_request .= "\r\n";
	$http_request .= $req;

	$response = '';
	if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
		die ('Could not open socket');
	}

	fwrite($fs, $http_request);

	while ( !feof($fs) )
		$response .= fgets($fs, 1160);
	fclose($fs);
	$response = explode("\r\n\r\n", $response, 2);

	$answers = explode ("\n", $response [1]);

	if (trim($answers[0]) == 'true') {
		return true;
	}

	return false;
}


function codecha_check($challenge, $response, $remoteip, $privatekey) {
	return _codecha_request(CODECHA_SERVER, CODECHA_PATH,
		array (
			'challenge' => $challenge,
			'response' => $response,
			'remoteip' => $remoteip,
			'privatekey' => $privatekey
		)
	);
}

function recaptcha_check($challenge, $response, $remoteip, $privatekey) {
	return _codecha_request(RECAPTCHA_SERVER, RECAPTCHA_PATH,
		array (
			'challenge' => $challenge,
			'response' => $response,
			'remoteip' => $remoteip,
			'privatekey' => $privatekey
		)
	);
}


?>
