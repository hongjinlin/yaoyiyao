<?php

class IndexAction extends CommonAction {
	public function index(){
		$Config = M('Config');
        $Goods = M('Prizeset');
        $prize = $Goods->getField('prizename,prizecontent');

        $this->assign('prize',$prize);
		//判断活动是否结束
		if($this->isGameEnd()){
			
			$cfg_game_finish=$Config->where("varname='cfg_game_finish'")->getField('value');
			
			
			$this->assign('cfg_game_finish',$cfg_game_finish);
		
			$this->display('gameFinish'); 
			
		}else{
			
			//判断是否为微信授权模式，如果是，跳转微信授权页面，如果不是，判断用普通登录模式
				
		$need_login = 1; //默认需要弹登录框
		
		if($cfg_isoauth_open=='1'){
			
		}else{
			//session(null);
			if((isset($_SESSION['username']) && $_SESSION['username']!='')
			&& (isset($_SESSION['userphone']) && $_SESSION['userphone']!='')){
				$User = M('User');
				
				$condition['username'] = $_SESSION['username'];
				$condition['userphone'] = $_SESSION['userphone'];
				$userList = $User->where($condition)->find();
				
				if(count($userList)>0){
					$need_login = 0; //表示不需要弹出登陆框
				}
				
				//获取用户ID
				//$uid = $User->where($condition)->getField('id');
				session("uid",$userList['id']);
				
			}
		}
		
		$this->assign('need_login',$need_login); 
		
		//获取首页的标题
		$cfg_homepage_title = $Config->where("varname='cfg_homepage_title'")->getField('value');
		$this->assign('cfg_homepage_title',$cfg_homepage_title);
        //获取每天摇奖次数
		$cfg_game_times = $Config->where("varname='cfg_game_times'")->getField('value');
		$this->assign('cfg_game_times',$cfg_game_times);
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
    	
    	
    	//如果是微信授权的，没注册的，直接添加一笔记录
    	if(!empty($_POST['username']) && !empty($_POST['userphone'])){
            $User = M("User");

            //检查用户手机号是否已注册过
            $user_array['userphone'] =$_POST['userphone'];
            $user_result=$User->where($user_array)->find();
            
            if($user_result != null){
                $user_array['username']=$_POST['username'];
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
        		$user_array['regtime']= date("Y-m-d H:i:s");
        		 // 实例化User对象
        		$lastId = $User->add($user_array);
        		if(!$lastId){
        			echo json_encode(array('status'=>'ng','message'=>'新增用户失败！'));
        			exit;
        		}
        		 
        		session("uid",$lastId);//获取最新用户ID
                session("username",$user_result['username']);
                session("userphone",$user_result['userphone']);
            }
    	}else{
    	   echo json_encode(array('status'=>'ng','message'=>'姓名和手机号必填！'));
           exit;
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
    
    public function doLottery(){

        //echo json_encode(array('success'=>'true'));exit;
        /* 
         * 奖项数组 
         * 是一个二维数组，记录了所有本次抽奖的奖项信息， 
         * 其中id表示中奖等级，prize表示奖品，v表示中奖概率。 
         * 注意其中的v必须为整数，你可以将对应的 奖项的v设置成0，即意味着该奖项抽中的几率是0， 
         * 数组中v的总和（基数），基数越大越能体现概率的准确性。 
         * 本例中v的总和为100，那么平板电脑对应的 中奖概率就是1%， 
         * 如果v的总和是10000，那中奖概率就是万分之一了。 
         *  
         */  
        $Prize = M('Prizeset');
        $prize_arr = $Prize->select();
        
        

         /* 
         * 每次前端页面的请求，PHP循环奖项设置数组， 
         * 通过概率计算函数get_rand获取抽中的奖项id。 
         * 将中奖奖品保存在数组$res['yes']中， 
         * 而剩下的未中奖的信息保存在$res['no']中， 
         * 最后输出json个数数据给前端页面。 
         */  
        foreach ($prize_arr as $key => $val) {   
            $arr[$val['id']] = $val['chance'];   
        }   
        $rid = $this->get_rand($arr); //根据概率获取奖项id  
        $res['prizeid'] = $rid;
        $res['sn'] = rand(); 
        $res['prizetype'] = $Prize->where("id=$rid")->getField('prizename');
        $res['prizecontent'] = $Prize->where("id=$rid")->getField('prizecontent');
        $res['success'] = true;
        echo json_encode($res);exit;
        //var_dump($res);      

    }

     /* 
     * 经典的概率算法， 
     * $proArr是一个预先设置的数组， 
     * 假设数组为：array(100,200,300，400)， 
     * 开始是从1,1000 这个概率范围内筛选第一个数是否在他的出现概率范围之内，  
     * 如果不在，则将概率空间，也就是k的值减去刚刚的那个数字的概率空间， 
     * 在本例当中就是减去100，也就是说第二个数是在1，900这个范围内筛选的。 
     * 这样 筛选到最终，总会有一个数满足要求。 
     * 就相当于去一个箱子里摸东西， 
     * 第一个不是，第二个不是，第三个还不是，那最后一个一定是。 
     * 这个算法简单，而且效率非常 高， 
     * 关键是这个算法已在我们以前的项目中有应用，尤其是大数据量的项目中效率非常棒。 
     */  
    function get_rand($proArr) {   
        $result = '';    
        //概率数组的总概率精度   
        $proSum = array_sum($proArr);    
        //概率数组循环   
        foreach ($proArr as $key => $proCur) {   
            $randNum = mt_rand(1, $proSum);   
            if ($randNum <= $proCur) {   
                $result = $key;   
                break;   
            } else {   
                $proSum -= $proCur;   
            }         
        }   
        unset ($proArr);    
        return $result;   
    }

    public function saveScore(){
        //echo json_encode(array('success'=>true));exit;
        if(!empty($_SESSION['uid'])){
            $uid = $_SESSION['uid'];
            $user = M('User');
            $num = $user->where("id=$uid")->count();

            //echo $num;
            if($num >0){
                $Score = M('Score');
                $Score->uid = $_SESSION['uid'];
                $Score->pid = $_GET['prizeid'];
                $Score->gametime = date("Y-m-d H:i:s");
                $Score->create();
                $rzt = $Score->add();
                if($rzt > 0){
                    echo json_encode(array('success'=>true));exit;
                }else{
                    echo json_encode(array('success'=>false));exit;
                }
                
            }else{
                return false;
            }

        }else{
            echo json_encode(array('success'=>false));exit;
        }
        
        //$user->username = $_GET['tel'];
        //$user->userphone = $_GET['tel'];
        //$user->create();
        //$lastId = $user->add();
        //if($lastId > 0){
            
        //}
    }
}