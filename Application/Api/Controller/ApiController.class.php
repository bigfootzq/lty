<?php
	//API控制器
	
	
	namespace Api\Controller;
	use Component\AppController;
	// use \Vendor\JWT\JWT;
	
	class ApiController extends AppController{
			private $userid;
					// 自动加载验证
			function _initialize() {
						parent::_initialize();
						// $post = file_get_contents("php://input");//获取POST数据
						// $res = json_decode ( $post,  true );
						// dump($post);
					

						// 验证 客户端 token
						// $apptoken = I('post.apptoken');
						// parent::checkAppToken($apptoken);
						
						// 验证 用户 token
						$header = getallheaders() ;
						// dump($header);
						$usertoken = $header['Authorization'];
						$this->userid = parent::checkUserToken($usertoken);
						
						
						if (!$this->userid){
									$data = array (
										"retcode" => "403",
										"retmessage" =>"token校验失败",
										);
										$this -> response($data, 'json');
										exit();
						}
					}
	
			
			// 各种业务方法

			//登陆
			public function logon(){
				// $post = $GLOBALS[""];
				$post = file_get_contents("php://input");//获取POST数据
				// $post = I('get.');
				// print_r($post);
				if (!empty($post)){
						$res = json_decode ( $post,  true );
						// $res = explode(',',$res['id']);
						// $res = $post;
						// dump($res);
				}else{
					$data = array(
							    "retcode"=>"0002",
								"retmessage"=> "post数据为空",
								"jobid"=> "",
								"token"=> ""
					);
					$this->response($data,'json');
				}

				// dump($res);
				$username = $res['username'];
				$password = $res['password'];
				// $username = trim($res[0]);
				// $password = trim($res[1]);
				if (empty($username) || empty($password)) {
					$data = array(
							    "retcode"=>"0003",
								"retmessage"=> "用户名或密码不能为空",
								"userid"=>"",
								"jobid"=> "",
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
					// M('User')->where(array('user_id' => $user_id))->save($info);
					
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
					// $this->response($data,'json');
					// $this -> response($data,'xml');
					echo 'error';
					
					exit();
				}
			}
			public function scheme(){
				
				switch ($this->_method){      
					case 'get': // get请求处理代码
					//根据彩店ID查询所有对应的status=0的方案
					$map['shopid'] = $this->userid;
					$map['status'] = 1;
					$s = M('LotteryScheme')->where($map)->limit(10)->select();
					// dump($s);
					
					if ($s){
						foreach ($s as $k=>$v){
							$map2['sid'] = $v['schemeid'];
							$detail = M('LotteryDetail')->where($map2)->select();
							foreach ($detail as $k2 =>$v2){
								$de[$k2 ]['amount'] = $v2['amount'];
								$de[$k2 ]['type'] = $v2['type'];
								$de[$k2 ]['ticketid'] = $v2['ticketid'];
								$de[$k2 ]['lotteryNumber'] = (array)json_decode($v2['lotteryNumber']);
								
							}
							$s[$k]['ticketsdetail'] = $de;
						}
						
						// dump($s);
						$data = array (
									"retcode" => "200",
									"retmessage" =>"新方案已经下发",
									"scheme" => $s,
									"shopid" => $this->userid);
						$this -> response($data, 'json');			
					}else{
						$data = array (
									"retcode" => "400",
									"retmessage" =>"暂时还没有新的方案",
									"shopid" => $this->userid);
						$this -> response($data, 'json');
					}
					break;   
					case 'post': // post请求处理代码
					$post = file_get_contents("php://input");//获取POST数据
					// $post = I('post.');
					// dump($post);
					$res = json_decode ( $post, true);//对POST信息解码
					//对信息进行校验，暂时没写
					//信息入库
					// $list = M('LotteryScheme')->add($res);//写入数据库
					if ($list !== false){//返回成功报文
						$data = array(
										"retcode"=>"0000",
										"retmessage"=> "提交成功"
								);
						$this->response($data,'json');
						
					}else{//返回失败报文
						$data = array(
										"retcode"=>"0008",
										"retmessage"=> "提交失败".M('LotteryScheme')->getDbError()
								);
						$this->response($data,'json');
					}
					break; 
					      
					case 'patch': // put请求处理代码 
					//这里是更新状态信息
					$post = file_get_contents("php://input");//获取POST数据
					// $post = I('post.');
					dump($post);
					$res = json_decode ( $post, true);//对POST信息解码
					//对信息进行校验，暂时没写
					//信息入库
					// $list = M('LotteryScheme')->add($res);//更新数据库，注意这里要锁表
					if ($list !== false){//返回成功报文
						$data = array(
										"retcode"=>"0000",
										"retmessage"=> "提交成功"
								);
						$this->response($data,'json');
						
					}else{//返回失败报文
						$data = array(
										"retcode"=>"0008",
										"retmessage"=> "提交失败".M('LotteryScheme')->getDbError()
								);
						$this->response($data,'json');
					}
					break;   
				 
				 }
			}
		


	}
	
?>