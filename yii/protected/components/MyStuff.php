<?php

class MyStuff {
	public static function log($str) {
		$log_file = "";
		if (file_exists('/home/thomp/www/arqnet/yii/output.log')) {
			$log_file = '/home/thomp/www/arqnet/yii/output.log';
		} elseif (file_exists('/home/arqbrand/public_html/yii/output.log')) {
			$log_file = '/home/arqbrand/public_html/yii/output.log';
		} elseif (strcmp($_SERVER['HTTP_HOST'],'157.22.244.225:8002')==0) {
			$log_file = '/var/www/arqnet8002/yii/output.log';
		} elseif ($_SERVER['HTTP_HOST']=='157.22.244.225:8001') {
			$log_file = '/home/arq/arqnet8001/yii/output.log';
		} elseif ($_SERVER['HTTP_HOST']=='157.22.244.225') {
			$log_file = '/var/www/arqnet/yii/output.log';
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
	static function curl_request($url,  $postdata = false) //single custom cURL request.
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
	
		#MyStuff::log("the response inside curl_request");
		#MyStuff::log($response);
		#MyStuff::log($ch);
		return $response;
	}

	function get_sql_date($type='curdate()') {
		$valid_types = array(
			'curdate()' => 1,
			'now()' => 1,
			);
		if ($valid_types[strtolower($type)]) {
			$sql = "select $type get_date";
			$date = Yii::app()->db->createCommand($sql)->queryRow();
			return $date['get_date'];
		} else {
			return false;
		}
	}

}
