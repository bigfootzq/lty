<?php
	//API控制器
	
	
	namespace Api\Controller;
	use Component\AppController;
	
	class SchemeController extends AppController{
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
						// dump($_SERVER);
						$header = getallheaders() ;
						// dump($header);
						// $usertoken = $header['Authorization'];
						$data =	explode(" ",$header['Authorization']);
						// dump($data);
						$usertoken = trim($data[1]); 
						
						$this->userid = parent::checkUserToken($usertoken);
						
						
						if (!$this->userid){
									$data = array (
										"retcode" => "403",
										"retmessage" =>"token校验失败,请重新登录",
										);
										$this -> response($data, 'json');
										exit();
						}
					}
	
			
			// 各种业务方法

			
			public function scheme(){
				
				switch ($this->_method){      
					case 'get': // get请求处理代码
					//根据彩店ID查询所有对应的status=0的方案
					$map['shopid'] = $this->userid;
					$map['status'] = 1;
					$s = M('LotteryScheme')->where($map)->limit(9)->select();
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
							// $s[$k]['ticketsdetail'] = $de;
						}
						
						// dump($s);
						$data = array (
									"retcode" => "200",
									"retmessage" =>"新方案已经下发",
									"shopid" => $this->userid,
									"scheme" => $s);
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
										"retcode"=>"200",
										"retmessage"=> "提交成功"
								);
						$this->response($data,'json');
						
					}else{//返回失败报文
						$data = array(
										"retcode"=>"400",
										"retmessage"=> "提交失败".M('LotteryScheme')->getDbError()
								);
						$this->response($data,'json');
					}
					break; 
					      
					case 'patch': // patch请求处理代码 
					//这里是更新状态信息
					$patch = file_get_contents("php://input");//获取POST数据
					// $post = I('post.');
					// dump($patch);
					$res = json_decode ( $patch, true);//对POST信息解码
					//对信息进行校验，暂时没写
					//信息入库
					// dump($res);
					$ls = M('LotteryScheme');
					$map['schemeid'] = $res['schemeid'];
					if ($res['tstatus'] == 6){
						$de = array();
						$map2['sid'] = $res['schemeid'];
						$detail = M('LotteryDetail')->where($map2)->select();
						foreach ($detail as $k2 =>$v2){
								$de[$k2 ]['amount'] = $v2['amount'];
								$de[$k2 ]['type'] = $v2['type'];
								$de[$k2 ]['ticketid'] = $v2['ticketid'];
								$de[$k2 ]['lotteryNumber'] = (array)json_decode($v2['lotteryNumber']);
							}
						$data = array(
										"retcode"=>"200",
										"schemedetail"=>$de,
										"retmessage"=> "请求成功"
								);
						$this->response($data,'json');
					}
					
					$tstatus = M('LotteryScheme')->where($map)->getField('tstatus');
											
					if($tstatus == 1){
						$de = array();
						$map2['sid'] = $res['schemeid'];
						$detail = M('LotteryDetail')->where($map2)->select();
						foreach ($detail as $k2 =>$v2){
								$de[$k2 ]['amount'] = $v2['amount'];
								$de[$k2 ]['type'] = $v2['type'];
								$de[$k2 ]['ticketid'] = $v2['ticketid'];
								$de[$k2 ]['lotteryNumber'] = (array)json_decode($v2['lotteryNumber']);
							}
						// dump($de);
						$list = M('LotteryScheme')->where('schemeid = %s', $res["schemeid"])->setField('tstatus',$res['tstatus']);//更新数据库，注意这里要锁表,暂时没写。
						// dump($list);
						if ($list == false){//返回失败报文,
							$data = array(
											"retcode"=>"400",
											"retmessage"=> "提交失败".M('LotteryScheme')->getDbError()
									);
							$this->response($data,'json');
						}else{
						//返回成功报文
							$data = array(
										"retcode"=>"200",
										"schemedetail"=>$de,
										"retmessage"=> "提交成功"
								);
						$this->response($data,'json');
						}
					}
					break;   
				 
				 }
			}
		


	}
	
?>