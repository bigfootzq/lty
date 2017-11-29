<?php  
		$start = microtime(true);
		$header = array();
		$header[] = 'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJTZXJ2ZXIiLCJ1aWQiOiIyIn0.b4MzNIaWPk5-QXolVEUfiJI9Symhbm-A3LJvd94qFeU';
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, 'http://192.168.33.10/api.php/Api/scheme');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		
		
		$res = json_decode ( $result,  true );
		echo $result;
		// var_dump($res); 
		
			// echo '<pre>';
		// var_dump($res['questions'][1]);
			// echo '</pre>'; 
		
		
		$elapsed = microtime(true) - $start;
		echo "That took $elapsed seconds.\n";
		function getMillisecond()
		 {
		   list($s1, $s2) = explode(' ', microtime());
		   return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
		 }
