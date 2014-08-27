<?php

class MyStuff {
	public static function log($str) {
		if (file_exists('/home/thomp/www/arnet-iq/www/yii/output.log')) {
			$log_file = '/home/thomp/www/arnet-iq/www/yii/output.log';
		} elseif (file_exists('/Users/nrodrigo/git/arnet-iq/www/yii/output.log')) {
			$log_file = '/Users/nrodrigo/git/arnet-iq/www/yii/output.log';
		} elseif (file_exists('/home/arqbrand/public_html/yii/output.log')) {
			$log_file = '/home/arqbrand/public_html/yii/output.log';
		} elseif (file_exists('/var/www/arq/arnet-iq/www/yii/output.log')) {
			$log_file = '/var/www/arq/arnet-iq/www/yii/output.log';
		}
		
		if (file_exists($log_file) and is_writable($log_file)) {
			if (is_array($str) or is_object($str)) {
				file_put_contents($log_file, print_r($str, 1)."\n", FILE_APPEND);
			} else {
				file_put_contents($log_file, "$str\n", FILE_APPEND);
			}
		}
	}
	
	# expecting $postdata to be in json format or array/object
	function curl_request($url,  $postdata = false) //single custom cURL request.
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
	
		if ($postdata)
		{
			$data_string = '';
			foreach($postdata as $key=>$value) { $data_string .= $key.'='.$value.'&'; }
			rtrim($data_string, '&');
			curl_setopt($ch, CURLOPT_POST, count($postdata));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		}
	
		$response = curl_exec($ch);
		curl_close($ch);
	
		MyStuff::log("the response inside curl_request");
		MyStuff::log($response);
		MyStuff::log($ch);
		return $response;
	}
}
