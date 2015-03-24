<?php

class IndexAction extends CommonAction {
	public function index(){
		$Config = M('Config');
		//判断活动是否结束
		if($this->isGameEnd()){
			
			$cfg_game_finish=$Config->where("varname='cfg_game_finish'")->getField('value');
			
			
			$this->assign('cfg_game_finish',$cfg_game_finish);
		
			$this->display('gameFinish'); 
			
		}else{
			
			//判断是否为微信授权模式，如果是，跳转微信授权页面，如果不是，判断用普通登录模式
		
		$cfg_isoauth_open = $Config->where("varname='cfg_isoauth_open'")->getField('value');
		
		$need_login = 1; //默认需要弹登录框
		
		if($cfg_isoauth_open=='1'){
			$cfg_oauth_cb_url = $Config->where("varname='cfg_oauth_cb_url'")->getField('value');
			
			$cfg_appid = $Config->where("varname='cfg_appid'")->getField('value');
			$cfg_screct = $Config->where("varname='cfg_screct'")->getField('value');
			
			//授权判断
			if (empty($openid)){
				if (empty($_REQUEST["code"])) {
					$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$cfg_appid."&redirect_uri=".$cfg_oauth_cb_url."&response_type=code&scope=snsapi_userinfo&state=blinq#wechat_redirect";

					redirect($url);
			
				}else{
					$code = $_REQUEST['code'];

					$accessTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $cfg_appid . "&secret=" . $cfg_screct . "&code=$code&grant_type=authorization_code";
					$ch = curl_init($accessTokenUrl);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20100101 Firefox/21.0');
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					$info = curl_exec($ch);
					$dataJson = json_decode($info, true);
					$openid = $dataJson['openid'];
					
					session("wxid", $openid);
					
					//判断wxid是否已经配置过，如果存在，则不需要显示登陆框
					$User = M('User');
					$condition['wxid'] = $openid;
					$userCount = $User->where($condition)->count();
					
					if($userCount>0){
						$need_login = 0; //表示不需要弹出登陆框
					}
					
					//获取用户ID
					$uid = $User->where($condition)->getField('id');
					session("uid",$uid);
					
					//获取数据
// 					$userdata = $table->get_subscribe_res($openid);
// 					$subscribed = $userdata['data']['subscribed'];
// 					if($subscribed){
// 						session("openid", $openid);
			
// 					}else{
// 						$url="http://www.oschina.net/code/step1?catalog=";
// 						echo $this->assign('url', $url)->fetch('redirect');
// 						return;
// 					}
				}
			}
		}else{
			//session(null);
			if((isset($_SESSION['username']) && $_SESSION['username']!='')
			&& (isset($_SESSION['userphone']) && $_SESSION['userphone']!='')){
				$User = M('User');
				
				$condition['username'] = $_SESSION['username'];
				$condition['userphone'] = $_SESSION['userphone'];
				$condition['wxid'] = 'none';
				$userList = $User->where($condition)->find();
				
				if(count($userList)>0){
					$need_login = 0; //表示不需要弹出登陆框
				}
				
				//获取用户ID
				//$uid = $User->where($condition)->getField('id');
				session("uid",$userList['id']);
				session("wxid", $userList['wxid']);
				
			}
		}
		
		$this->assign('need_login',$need_login); 
		
		//获取首页的标题
		$cfg_homepage_title = $Config->where("varname='cfg_homepage_title'")->getField('value');
		$this->assign('cfg_homepage_title',$cfg_homepage_title);
		
		
		//获取统计代码
		$cfg_tongji_code = $Config->where("varname='cfg_tongji_code'")->getField('value');
		$this->assign('cfg_tongji_code',$cfg_tongji_code);
		
		//获取banner路径
		$cfg_top_banner = $Config->where("varname='cfg_top_banner'")->getField('value');
		$this->assign('cfg_top_banner',$cfg_top_banner);
		
		//获取活动时间，活动规则，活动奖品，活动说明等信息
		$cfg_game_star_time = $Config->where("varname='cfg_game_star_time'")->getField('value');
		$this->assign('cfg_game_star_time',$cfg_game_star_time);
		$cfg_game_end_time = $Config->where("varname='cfg_game_end_time'")->getField('value');
		$this->assign('cfg_game_end_time',$cfg_game_end_time);
		
		$cfg_game_rule = $Config->where("varname='cfg_game_rule'")->getField('value');
		$cfg_game_rule = str_replace("\n", "</br>", $cfg_game_rule);
		$this->assign('cfg_game_rule',$cfg_game_rule);

		
		$cfg_gift = $Config->where("varname='cfg_gift'")->getField('value');
		$cfg_gift = str_replace("\n", "</br>", $cfg_gift);
		$this->assign('cfg_gift',$cfg_gift);
		
		$cfg_game_note = $Config->where("varname='cfg_game_note'")->getField('value');
		$this->assign('cfg_game_note',$cfg_game_note);
		
		//获取微信分享图片，分享标题，分享描述，分享URL
		$cfg_wx_share_title = $Config->where("varname='cfg_wx_share_title'")->getField('value');
		$this->assign('cfg_wx_share_title',$cfg_wx_share_title);
		
		$cfg_wx_share_desc = $Config->where("varname='cfg_wx_share_desc'")->getField('value');
		$this->assign('cfg_wx_share_desc',$cfg_wx_share_desc);
		
		$cfg_wx_share_pic = $Config->where("varname='cfg_wx_share_pic'")->getField('value');
		$this->assign('cfg_wx_share_pic',$cfg_wx_share_pic);
		
		$cfg_wx_share_url = $Config->where("varname='cfg_wx_share_url'")->getField('value');
		$this->assign('cfg_wx_share_url',$cfg_wx_share_url);
		
		//显示拼图
		$GoodsType=M('Goodstype');
		$condition['isshow']=1;
		$goodstypepic = $GoodsType->where($condition)->getField('goodstypepic');
		$goodstypename = $GoodsType->where($condition)->getField('goodstypename');
		$this->assign('goodstypepic',$goodstypepic);
		$this->assign('goodstypename',$goodstypename);
		
		//获取排行
		$Model = new Model(); // 实例化一个model对象 没有对应任何数据表
		
		$query_str='select min(s.score) as score ,u.username as username,u.userphone as userphone from tp_score s join tp_user u on s.uid=u.id group by u.username order by score asc';
		$queryResult = $Model->query($query_str);
		$winList=array();
		if($queryResult!=null){
 			$i =0;
 			while($row = $queryResult[$i]){
 				$winList[$i]=array('phone'=>substr_replace($row['userphone'],'****',4,4)."&nbsp;&nbsp;&nbsp;".$row['score'].'s','name'=>($i+1).".&nbsp;&nbsp;&nbsp;".$this->cut_str($row['username'], 1, 0).'**'."&nbsp;");
 				$i++;
 			}
		}
		
		$winList=json_encode($winList);

		$this->assign('winList',$winList);
		
		//获取商品类别
		$queryStr ="select g.id as id,g.goodstitle as goodstitle ,g.goodscontent as goodscontent,g.goodspic as goodspic,g.goodslink as goodslink,t.goodstypename as goodstypename from tp_goods g join tp_goodstype t on g.goodstypeid=t.id and t.isshow=1 order by g.id asc";
			
		$Model = new Model(); // 实例化一个model对象 没有对应任何数据表
			
		$queryResult = $Model->query($queryStr);
		$this->assign('goodsList',$queryResult);
		
		$this->display(); 
		}
		
		
    }
    
    public function doReg(){
    	
    	$Config = M('Config');
    	$cfg_isoauth_open = $Config->where("varname='cfg_isoauth_open'")->getField('value');
    	
    	//如果是微信授权的，没注册的，直接添加一笔记录
    	if($cfg_isoauth_open=='1'){
    		$user_array['username']= $_POST['username'];
    		$user_array['userphone'] = $_POST['userphone'];
    		$user_array['wxid']= $_SESSION['wxid'];
    		$user_array['regtime']= date("Y-m-d H:i:s");
    		$User = M("User"); // 实例化User对象
    		$lastId = $User->add($user_array);
    		if(!$lastId){
    			echo json_encode(array('status'=>'ng','message'=>'新增用户失败！'));
    			exit;
    		}
    		 
    		session("uid",$lastId);//获取最新用户ID
    	}else{
    		//判断用户的手机是否存在，如果手机存在，判断是否用户名输错，如果都不存在，注册新的一笔记录
    		/* 判断用户是否存在 */
    		$User = M("User"); // 实例化User对象
    		$user_array['userphone'] =$_POST['userphone'];
    		$user_result=$User->where($user_array)->find();
    		
    		if($user_result != null){
    			$user_array['username']=$_POST['username'];
    			$user_array['wxid']='none';
    			$user_result=$User->where($user_array)->find();
    				
    			if($user_result==null){//用户名错误了
    				echo json_encode(array('status'=>'ng','message'=>'用户名错误！'));
    				exit;
    			}else{
    				
    				session("uid",$user_result['id']);//获取最新用户ID
    				session("username",$user_result['username']);
    				session("userphone",$user_result['userphone']);
    			}
    		}else{
    			$user_array['username']= $_POST['username'];
    			$user_array['userphone'] = $_POST['userphone'];
    			$user_array['wxid']= 'none';//没有微信ID
    			$user_array['regtime']= date("Y-m-d H:i:s");
    			$User = M("User"); // 实例化User对象
    			$lastId = $User->add($user_array);
    			if(!$lastId){
    				echo json_encode(array('status'=>'ng','message'=>'新增用户失败！'));
    				exit;
    			}
    			 
    			session("uid",$lastId);//获取最新用户ID
    			session("username",$_POST['username']);
    			session("userphone",$_POST['userphone']);
    		}
    	}
    	echo json_encode(array('status'=>'ok'));
    	exit;
    }
    
    public function doScoreUpdate(){
    	$score_array['uid']=$_SESSION['uid'];
    	$score_array['score']=$_POST['score'];
    	$score_array['joindate']=date("Y-m-d H:i:s");
    	
    	$Score = M("Score"); // 实例化User对象
    	$lastId = $Score->add($score_array);
    	if(!$lastId){
    		echo json_encode(array('status'=>'ng'));
    		exit;
    	}

    	$sysConfig = M('Config'); //获取恭喜的语句
    	$congar_msg = $sysConfig->where("varname='cfg_game_end_say'")->getField('value');
    	$congar_msg=str_replace('*',$_POST['score'],$congar_msg);
    	
    	$wx_share_msg = $sysConfig->where("varname='cfg_share_friend_say'")->getField('value');
    	$wx_share_msg=str_replace('*',$_POST['score'],$wx_share_msg);
    	
    	echo json_encode(array('status'=>'ok','congar_msg'=>$congar_msg,'wx_share_msg'=>$wx_share_msg));
    	exit;
    }
    
    public function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
    {
    	if($code == 'UTF-8')
    	{
    		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    		preg_match_all($pa, $string, $t_string);
    
    		if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
    		return join('', array_slice($t_string[0], $start, $sublen));
    	}
    	else
    	{
    		$start = $start*2;
    		$sublen = $sublen*2;
    		$strlen = strlen($string);
    		$tmpstr = '';
    
    		for($i=0; $i< $strlen; $i++)
    		{
    		if($i>=$start && $i< ($start+$sublen))
    		{
    		if(ord(substr($string, $i, 1))>129)
    			{
    			$tmpstr.= substr($string, $i, 2);
    		}
    		else
    		{
    		$tmpstr.= substr($string, $i, 1);
    		}
    		}
    		if(ord(substr($string, $i, 1))>129) $i++;
    		}
    			//if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
    			return $tmpstr;
    	}
    	}
}