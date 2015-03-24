<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>拼图游戏后台</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0 ,user-scalable=no">

    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Admin/style.css" />
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Admin/bootstrap.min.css" />
	<script type="text/javascript" src="__PUBLIC__/Js/Admin/jquery.js"></script>
	<script type="text/javascript" src="__PUBLIC__/Js/Admin/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
		<script type="text/javascript" src="__PUBLIC__/Js/Admin/html5shiv.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/Js/Admin/respond.min.js"></script>

    <![endif]-->
	<script type="text/javascript" src="__PUBLIC__/Js/Admin/functions.js"></script>
	<script type="text/javascript" src="__PUBLIC__/Js/Admin/jquery.form.js"></script>
	<script type="text/javascript" src="__PUBLIC__/Js/Admin/asyncbox.js"></script>
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Admin/default.css" />

	</head>

	<body>

		<nav class="navbar navbar-inverse navbar-static-top" role="navigation">

			<a href="http://www.wayhu.com"  target="_black"><img class="img-rounded" src="__PUBLIC__/Images/bot_logo.png" /></a>
			<p style="text-align:right;margin-top:-53px;color:white;">拼图游戏后台欢迎你 <?php echo (session('username')); ?>
			
			<a href="__APP__/Index/home"  class="btn btn-default navbar-btn btn-group-lg">后台首页</a>
			
			<a href="__APP__/Register/pswModify" class="btn btn-default navbar-btn btn-group-lg">修改个人信息</a>

			<a href="__APP__/Login/doLogout"  class="btn btn-default navbar-btn btn-group-lg">退出登录</a>


		</nav>

	

	

				


<div class="pull-left diywap_left">
			
			<div class="list-group" id="accordion">
			
				<?php if($_SESSION['role']== 'level_1_admin' ): ?><h4>
					<a class="list-group-item active navbar-link " data-toggle="collapse"  data-parent="#accordion" href="#collapseOne">
					商品管理  <span class="glyphicon  glyphicon-arrow-down"></span></a>
				</h4>
					<div id="collapseOne" class="panel-collapse collapse  
					<?php  if(strstr(__SELF__, 'addGoodsType')){echo ' in';} if(strstr(__SELF__, 'goodsTypeInfo')){echo ' in';} if(strstr(__SELF__, 'modifyGoodsType')){echo ' in';} if(strstr(__SELF__, 'searchGoodsType')){echo ' in';} if(strstr(__SELF__, 'addGoods')){echo ' in';} if(strstr(__SELF__, 'goodsInfo')){echo ' in';} if(strstr(__SELF__, 'modifyGoods')){echo ' in';} if(strstr(__SELF__, 'searchGoods')){echo ' in';} ?> ">
				<a href="__APP__/GoodsType/addGoodsType" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 添加商品类别</a>
				<a href="__APP__/GoodsType/goodsTypeInfo" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 商品类别列表</a>
				<a href="__APP__/Goods/addGoods" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 添加商品</a>
				<a href="__APP__/Goods/goodsInfo" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 商品列表</a>
				
				</div><?php endif; ?>

				<h4>
				<a class="list-group-item active navbar-link" data-toggle="collapse"  data-parent="#accordion" href="#collapsetwo">用户管理 <span class="glyphicon  glyphicon-arrow-down"></span></a>	
				</h4>
				<div id="collapsetwo" class="panel-collapse collapse
					<?php  if(strstr(__SELF__, 'userInfo')){echo ' in';} if(strstr(__SELF__, 'searchUser')){echo ' in';} if(strstr(__SELF__, 'exchangeGift')){echo ' in';} if(strstr(__SELF__, 'scoreInfo')){echo ' in';} if(strstr(__SELF__, 'searchScore')){echo ' in';} if(strstr(__SELF__, 'modifyScore')){echo ' in';} if(strstr(__SELF__, 'exchangeInfo')){echo ' in';} if(strstr(__SELF__, 'searchExchange')){echo ' in';} ?>				
				">
				<a href="__APP__/User/userInfo" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span>用户记录查询</a>
				<a href="__APP__/User/scoreInfo"  class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span>游戏成绩查询</a>				
				<a href="__APP__/User/exchangeInfo"  class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span>兑换记录查询</a>	
				</div>

				<!--角色判断-->

				<?php if($_SESSION['role']== 'level_1_admin' ): ?><h4>
				<a class="list-group-item active navbar-link" data-toggle="collapse"  data-parent="#accordion" href="#collapsethree">配置管理 <span class="glyphicon  glyphicon-arrow-down"></span></a>	
					</h4>
				<div id="collapsethree" class="panel-collapse collapse
					<?php  if(strstr(__SELF__, 'system')){echo ' in';} if(strstr(__SELF__, 'game')){echo ' in';} if(strstr(__SELF__, 'weixin')){echo ' in';} ?>				
				">
				<a href="__APP__/Config/configInfo/grouptype/system" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 系统配置</a>					
				<a href="__APP__/Config/configInfo/grouptype/game"  class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 活动配置</a>
				<a href="__APP__/Config/configInfo/grouptype/weixin" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 微信配置</a>
				
					</div>
				
				
					<h4>
				<a class="list-group-item active navbar-link" data-toggle="collapse"  data-parent="#accordion" href="#collapsefour">角色管理 <span class="glyphicon  glyphicon-arrow-down"></span></a>	
					</h4>
				<div id="collapsefour" class="panel-collapse collapse
					<?php  if(strstr(__SELF__, 'adminInfo')){echo ' in';} if(strstr(__SELF__, 'addAdmin')){echo ' in';} if(strstr(__SELF__, 'searchAdmin')){echo ' in';} ?>				
				">
				<a href="__APP__/Admin/addAdmin" class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span> 添加管理员</a>					
				<a href="__APP__/Admin/adminInfo"  class="list-group-item"><span class="glyphicon glyphicon-circle-arrow-right"></span>管理员列表</a>
					</div><?php endif; ?>	
				

				<a class="list-group-item active navbar-link " href="http://www.wayhu.com">官方网站：www.wayhu.com</a>
				<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1599675606&site=qq&menu=yes" class="list-group-item active navbar-link ">
				<img border="0" src="http://wpa.qq.com/pa?p=2:1599675606:51" alt="点击这里给我发消息" title="点击这里给我发消息"/> 技术QQ</a>
				
			</div>
			
			
		</div>	



		

<div class="pull-left diywap_right">	

<div class="panel panel-info">
	  <div class="panel-heading">程序版本信息</div>
	  <div class="panel-body">
	    
		 <div class="row">
		  
		   <div class="col-md-6">
				<div class="panel panel-default">
				  <!-- Default panel contents -->
				  <div class="panel-heading">服务器信息</div>
				 

				  <!-- List group -->
				<table class="table table-bordered table-hover">
				<tr class="active">
					<td>PHP版本:</td>
					<td><?php echo phpversion(); ?></td>
				</tr>
				<tr class="warning">
					<td>操作系统:</td>
					<td><?php echo PHP_OS; ?></td>
				</tr>
				<tr class="success">
					<td>程序目录:</td>
					<td><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
				</tr>
				<tr class="warning">
					<td>端口号:</td>
					<td><?php echo $_SERVER['SERVER_PORT'] ; ?></td>
				</tr>
				<tr class="active">
					<td>服务器IP:</td>
					<td><?php echo $_SERVER['SERVER_ADDR'] ; ?></td>
				</tr>
					
			</table>
				</div>
		  </div>
		  
		   <div class="col-md-6">
				<div class="panel panel-default">
				  <!-- Default panel contents -->
				  <div class="panel-heading">程序信息</div>
				 

				  <!-- List group -->
				<table class="table table-bordered table-hover">
				<tr class="active">
					<td>程序名称:</td>
					<td>拼图游戏</td>
				</tr>
				<tr class="warning">
					<td>程序版本:</td>
					<td>VER1.0</td>
				</tr>
				<tr class="success">
					<td>程序开发者:</td>
					<td>威虎小王</td>
				</tr>
				<tr class="warning">
					<td>开发者QQ:</td>
					<td>1599675606</td>
				</tr>
				<tr class="active">
					<td>官方网站:</td>
					<td><a href="http://www.wayhu.com">www.wayhu.com</a></td>
				</tr>
			</table>
				</div>
		  </div>
		  
		
		</div>
	  </div>
	</div>
</div>








  

  

</div>

	

	</body>

</html>