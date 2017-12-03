<?php
	//API控制器
	namespace Api\Controller;
	use Component\AppController;
	
	class UserController extends AppController{
			private $userid;
			
			// 自动加载验证
			// function _initialize() {
						// parent::_initialize();

					// }
	
			
			// 各种业务方法

			//登陆
			public function logon(){
				// $post = $GLOBALS[""];
				// $post = file_get_contents("php://input");//获取POST数据
				$post = I('post.');
				if (empty($post)){
					$data = array(
							    "retcode"=>"0002",
								"retmessage"=> "post数据为空",
								"token"=> ""
					);
					$this->response($data,'json');
				}
				$res = $post;
				// dump($res);
				$username = trim($res['username']);
				$password = trim($res['password']);
				
				if (empty($username) || empty($password)) {
					$data = array(
							    "retcode"=>"0003",
								"retmessage"=> "用户名或密码不能为空",
								"userid"=>"",
								"token"=> ""
					);
					$this->response($data,'json');
				}

				$user_object = D('User/User');
				$uid = $user_object->login($username, $password);
				// echo $user_object->_sql();
				// dump($uid);
				if($uid){
					$token = parent::setUserToken($uid);
					// dump($token);
					$info['last_logonip'] = get_client_ip();
					$info['last_logontime'] = date('Y-m-d H:i:s', time());
					$user_object->where(array('user_id' => $user_id))->save($info);
					
					$data = array(
										"retcode"=>"200",
										"retmessage"=> "登陆成功",
										"userid"=>$uid,
										"token"=> $token
						);
					$this -> response($data, 'json');
					// $this -> response($data,'xml');
					// echo 'ok';
				}else{
					$data = array(
							    "retcode"=>"400",
									"retmessage"=> "登陆失败,用户名或密码错误",
									"userid"=>$uid,
									"token"=> ""
					);
					$this->response($data,'json');
					// $this -> response($data,'xml');
					// echo 'error';
					
					exit();
				}
			}

		


	}
	
?>