<?php
// App接口公共控制器 AppController
	namespace Component;
	use Think\Controller;
	use Think\Controller\RestController;
	class AppController extends RestController {
			// 自动加载的东西
			function _initialize() {
				}
				
			protected $allowMethod    = array('get','post','put','patch'); // REST允许的请求类型列表    
			protected $allowType      = array('html','xml','json'); // REST允许请求的资源类型列表
			
			// 验证 客户端 token
			protected function checkAppToken($apptoken){
					// 引入 function.php 中定义的检测 apptoken 的函数
					if(checkingAppToken($apptoken)){
							return true;
					}else{
							$data['code'] = '404';
							$data['msg'] = 'apptoken无效';
							$data['data'] = null;
							$this -> response($data, 'json');
							exit();
					}
			}
			
			// 验证 用户 token
			protected function checkUserToken($usertoken){
				if(!empty($usertoken)){
					// $t = M('user_token')->where("token = '%s'",$usertoken)->getField('user_id');//查询是否存在同用户token
					// if(empty($t)){ //如果不存在
							// return false;
					// }else{//这里要检查过期时间，如果过期返回false，暂时不做了。
							// return $t;
					// }
					$key = "lottery";
					$payload  = \Component\JWT\JWT::decode($usertoken, $key,array('HS256'));
					// dump($payload);
					$uid = $payload->uid;
					
					if ($payload){
						$token = \Component\JWT\JWT::encode($payload, $key);
						if ($token == $usertoken){
							return $uid;
						}else{
							return false;
						}
					}
				}		
			}
			
			// 各种验证 ……
			protected function setUserToken($user_id){
					if(!empty($user_id)){
							$t = M('user_token')->where("user_id = %d",$user_id)->getField('token');//查询是否存在同用户token
							// dump($t);
							if(empty($t)){//如果不存在，生成一个token，存入数据表
							$key = "lottery";
							$payload = array(
							"iss" => "Server", 
							'uid' => $user_id,
							);
							
							$token = \Component\JWT\JWT::encode($payload, $key);
							$user_token['token'] = $token;
							$user_token['user_id'] = $user_id;
							$user_token['create_time'] = date('Y-m-d H:i:s',time());
							$user_token['expire_time'] = date('Y-m-d H:i:s',(time()+2592000));
							// dump($user_token);
							M('user_token')->data($user_token)->add();
						}else{//如果存在，直接返回
						
									$token = $t;
						}
					}
					return $token;
			}			

	}
?>