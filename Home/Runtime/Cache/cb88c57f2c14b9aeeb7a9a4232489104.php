<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">    
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="0">
<title>游戏结束提醒</title>



<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.5.1.min.js"></script>

<script type="text/javascript" src="__PUBLIC__/Js/alert2.js"></script>




<script type="text/javascript">
	
$(document).ready(function() { 
	
	alert("<?php echo ($cfg_game_finish); ?>");
	return false;

}); 

</script>
</head>


<body class="activity-lottery-winning">

</body>
</html>