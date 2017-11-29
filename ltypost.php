<?php  

		$start = microtime(true);
		//数据生成json数组

		//下面是模拟数据
		$arr = array (
					"userid" => "2",
					"token" => "ea28a49a555a8b15dfae9f103b9e785f",
					"test"  => 'test'
					);
		$data_string = json_encode($arr);			
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1/lt/api.php/Api/scheme');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJTZXJ2ZXIiLCJ1aWQiOiIyIn0.b4MzNIaWPk5-QXolVEUfiJI9Symhbm-A3LJvd94qFeU',
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
		);
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
