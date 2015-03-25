<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title><?php echo ($cfg_homepage_title); ?></title>


<link rel="stylesheet" type="text/css" href="./public/css/shake.css" />
<link rel="stylesheet" type="text/css" href="./public/css/alertify.css" />
<link rel="stylesheet" type="text/css" href="./public/css/jquery.alerts.css" />
<script type="text/javascript" src="./public/js/jquery.alerts.js"></script>


<style>
.game-yao:before {
	z-index: 999;
}
.game-run-top{
	position:relative;
}
.game-run-bottom{
	position:relative;
}
</style>
<style>
body{
	background-color: #bf232e;
}
.wrapper {
	width: 100%;
	position: relative;
	margin: 0 auto;
	max-width: 500px;
}
.bg {
	position: absolute;
	top: 0px;
	left: 0px;
	width: 100%;
	z-index: -1;
}
.loading-mask {
	width: 100%;
	height: 200%;
	position: fixed;
	background: rgba(0,0,0,0.6);
	z-index: 100;
	left: 0px;
	top: 0px;
	display: none;
}
.loading-mask img.gimg {
	display: block;
	margin: 160px auto 0px;
}
.inner-cont{
	width: 85%;
	margin: 0 auto;
	padding-top: 20px;
	color: #FFFFFF;
}
.qtitle{
	line-height: 30px;
	font-size: 26px;
	font-weight: bold;
}
.field-contain{
	margin-top: 20px;
}
.input-label{
	font-size: 18px;
	line-height: 32px;
}
.input-text {
	display: block;
	width: 90%;
	height: 32px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	border-radius: 8px;
	padding: 2px 5px;
	background: -moz-linear-gradient(top, #d2cfd2 0, #ffffff 50%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #d2cfd2), color-stop(50%, #ffffff));
	background: -webkit-linear-gradient(top, #d2cfd2 0, #ffffff 50%);
	background: -o-linear-gradient(top, #d2cfd2 0, #ffffff 50%);
	background: -ms-linear-gradient(top, #d2cfd2 0, #ffffff 50%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d2cfd2', endColorstr='#ffffff', GradientType=0 );
	background: linear-gradient(top, #d2cfd2 0, #ffffff 50%);
	border: 1px solid #c6c3c6;
	border-top-width: 2px;
}
.loading-mask{
	background: rgba(0,0,0,0.6);
}
.tip{
	color: #FFFFFF;
	font-size: 12px;
}
#save-btn{
	padding: 10px 12px;
	font-size: 1em;
	padding: 10px 32px;
	background: #ffaa22;
	background: -moz-linear-gradient(top, #ffaa22 0, #f04a02 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ffaa22), color-stop(100%, #f04a02));
	background: -webkit-linear-gradient(top, #ffaa22 0, #f04a02 100%);
	background: -o-linear-gradient(top, #ffaa22 0, #f04a02 100%);
	background: -ms-linear-gradient(top, #ffaa22 0, #f04a02 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffaa22', endColorstr='#f04a02', GradientType=0 );
	background: linear-gradient(top, #ffaa22 0, #f04a02 100%);
	color: #000;
	font-size: 20px;
	border: 1px solid #c94e04;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	border-radius: 8px;
	font-weight: bold;
	margin-top: 20px;
}
#save-btn{
	color: #c63535;
	padding: 7px 30px;
	background: #ffd700;
	display: inline-block;
	border-radius: 9px;
}
.page-url {
	border-top:0px;
	margin-top: 10px;
	position: absolute;
	bottom: 0px;
	text-align: center;
	width: 100%;
}
.page-url-link{
	color: white;
	text-shadow:none;
}

</style>

<script type="text/javascript" src="./public/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="./public/js/jquery.mobile.events.min.js"></script>
<script type="text/javascript" src="./public/js/jquery.alerts.js"></script>
<script type="text/javascript" src="./public/js/alertify.min.js"></script>


<script type="text/javascript">
    //隐藏微信中网页底部导航栏
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        WeixinJSBridge.call('hideToolbar');
    });
    //隐藏微信中网页右上角按钮
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        WeixinJSBridge.call('hideOptionMenu');
    });

    var result = undefined; //记录摇奖结果
    var shaking = false;
    var audio1 = null;
    var audio2 = null;
    var audio3 = null;
    var stop = false;
    var stepCount = 0;
    var hasSend = false;



    //处理摇奖结果--by 威虎小王
    function pointResult(res) {
        stepCount++;
        if (stepCount < 4) return;
        if (!shaking) return;
        shaking = false;
        stop = true;
        if (res.success) {
            if (audio2.paused) {
                audio2.play();
            }
        } else {
            if (audio3.paused) {
                audio3.play();
            }
        }
        setTimeout(function () {
            $('.game-run-tips').html('摇奖结束');

            if (res.success) {             
              // alert("中奖了");
               $('.loading_pop').hide();
                $('.wap').show();

				//处理中奖的结果--by 威虎小王

                $("#sncode").text(res.sn);
                             $("#prizetype").text("您中了"+ res.prizetype +"等奖");
                            $('.game-status').hide();
                            $('.game-btn').hide();
                            $('.loading_pop').hide();
                            $('.game-blank').hide();
                            $('.wap').show();
                            $('#result').show();

            } else {
                Alertify.dialog.labels.ok = "再试一次";
                Alertify.dialog.alert("很抱歉，您没有中奖！", function () {
                    location.reload();
                });
            }
        }, 800);
    }

    //开始摇动
    function shake() {
        if (audio1.paused && !stop) {
            audio1.play();
        }
        if (!shaking && !stop) {
           
            shaking = true;
            if (!hasSend) {
                hasSend = true;
                //发送请求
				
				//这个是用ajax来POST一个事件接口，获取摇晃的中奖结果---by 威虎小王
                $.ajax({
                   
                    type: "post",
                    url: "__APP__/doLottery&sd="+Math.random(),                  
                   // contentType: "application/json; charset=utf-8",
                    cache : false,
                   dataType: "json",
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    },
                    success: function (data) {

                        if (data.success == false) {
                            alert(222222);
                            Alertify.dialog.labels.ok = "确定";
                            Alertify.dialog.alert("摇奖失败", function () {
                                location.reload();
                            });
                        } else {	
							//摇晃成功后，返回结果，并且输出 - by 威虎小王
                            alert(1111);
                            result = data;
                          
                            $('.loading_pop').hide();
                            
                            pointResult(result);
                        }
                    }
                   
                });


            }

            setTimeout(function () {
                $(".game-run-top").animate({
                    top: "-25%"
                }, 300, function () {
                    $(".game-run-top").animate({
                        top: "0"
                    }, 300);
                });
                $(".game-run-bottom").animate({
                    top: "25%"
                }, 300, function () {
                    $(".game-run-bottom").animate({
                        top: "0"
                    }, 300, function () {
                        if (result == undefined && stepCount == 2) {
                          
                            $('.loading_pop').show();
                        } else {
                            pointResult(result);
                        }
                        $('.game-run-tips').html('请您继续摇动...');
                        shaking = false;
                    });
                });
            }, 1000);
        }
    }

    $(function () {
        //if(<?php echo ($need_login); ?> == 1){            
        //   loginbox();
        //}else{
        //    alert(2222);
        //}
        var $game_status = $('.game-status');
        $game_status.height($game_status.width() + 20);
        audio1 = document.getElementById('audio1');
        audio2 = document.getElementById('audio2');
        audio3 = document.getElementById('audio3');
        var SHAKE_THRESHOLD = 1500;
        var last_update = 0;
        var x = y = z = last_x = last_y = last_z = 0;

        $(".game-start-btn,.lihua").off("click").on("click", function () {
			//alert("click me");
            var overTotalCount = false; //是否超过总次数
            var overPerDayCount = false; //是否超过每天总次数
            if (overTotalCount) {
                Alertify.dialog.labels.ok = "确定";
                Alertify.dialog.alert("亲，您的参与次数已经用完了！");
                return;
            }
            if (overPerDayCount) {
                Alertify.dialog.labels.ok = "确定";
                Alertify.dialog.alert("亲，您今天的参与次数已经用完了，请明天再来吧！");
                return;
            }

            $(".wap").hide();
            var z = '<div class="game-blank"><div class="game-run-top"></div><div class="game-run-bottom"></div><div class="game-run-tips">大力摇吧，摇出大奖</div></div>';
            $("body").append(z);
        });

        //添加点击事件和摇动事件
        $('body').on('click', '.game-blank', shake);
        if (window.DeviceMotionEvent) {
            window.addEventListener('devicemotion', function (eventData) {
                var acceleration = eventData.accelerationIncludingGravity;
                var curTime = new Date().getTime();
                if ((curTime - last_update) > 100) {
                    var diffTime = curTime - last_update;
                    last_update = curTime;
                    x = acceleration.x;
                    y = acceleration.y;
                    z = acceleration.z;
                    var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
                    if (speed > SHAKE_THRESHOLD / 4 && $('.wap').css('display') == 'none') {
                        shake();
                    }
                    last_x = x;
                    last_y = y;
                    last_z = z;
                }
            }, false);
        }

    });
</script>
</head>

<body>






<div class="wap">
    
    <div class="game-status" onclick="javascript:void(0);">
        <div class="game-yao"></div>
        <div class="lihua"></div>
    </div>
    <div class="game-btn" style="z-index: 9999;position: relative;">
        <div class="game-start" onclick="javascript:void(0);"><span class="game-start-btn">开始摇奖</span></div>        
        <div class="game-last-time">
         <div style="margin-bottom:5px;">您今天已经摇奖<span class="game-last-times font-ffd700">0</span>次，每天最多有<span class="game-last-times font-ffd700"><?php echo ($cfg_game_times); ?></span>次摇奖机会</div>
            
        </div>
    </div>

    <div class="wrapper" id="result" style="display:none; z-index:100;">
    
		<img class="bg" src="./public/images/info_bg.jpg" />
		<div class="inner-cont">
			<div class="qtitle">填写领奖的个人信息：</div>
			<div class="field-contain">
           <label for="username" class="input-label">你中了：<span class="red" id="prizetype">等奖</span></label><br />
           <label for="username" class="input-label">你的兑奖SN码：<span class="red" id="sncode"></span></label>				
			</div>
			<div class="field-contain">
				<label for="phone" class="input-label">请输入您的手机号码:</label>
				<input type="tel" name="phone" id="phone" class="input-text" value="">
				<span class="tip">*请务必填写正确，此手机号将作为您以后领奖的依据</span>
			</div>
			<button id="save-btn">保存</button>
		</div>
	</div>

    <div class="game-box page-descs">
        <h1>摇奖说明</h1>
        <div class="content page-desc">
        	本活动日期为：<br/> <br/>
            <?php echo ($cfg_game_star_time); ?> 至 <?php echo ($cfg_game_end_time); ?><br><br>本活动仅为演示
        </div>
        <h1 class="game-prize-list">奖项设置</h1>
        
		<ul class="prize-list game-list clearfix page-prize-list">
        <?php if(is_array($prize)): foreach($prize as $k=>$vo): ?><li class="clearfix">
        <span class="prize-name"><?php echo ($k); ?></span>
        <span class="prize-num"><?php echo ($vo); ?></span>
            </li><?php endforeach; endif; ?>
		</ul>
    </div>    
	







</div>

	
<div class="loading_pop" style="display:none;">
	<div class="cont" style="text-align:center;width:100%;height:50px;position:absolute;top:50%;margin-top:-25px;z-index:999999;font-size:20px;color:rgb(249, 249, 249);">
		<img style="width:30px;height:30px;vertical-align:-7px;"
			src="data:image/gif;base64,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"
			alt="Loading..."> <span>摇奖中...</span>
	</div>
	<div class="pop_mask" id="popMask" style="position:absolute;width:100%;height:100%;background-color:black;opacity:0.65;top:0;left:0;z-index:99999;"></div>
</div>
    
<audio id="audio1" src="./public/css/shake_sound_male.mp3" preload="auto">
</audio>
<audio id="audio2" src="./public/css/shake_match.mp3" preload="auto">
</audio>
<audio id="audio3" src="./public/css/shake_fail.mp3" preload="auto">
</audio>
	

	<div id="loading" class="loading-mask">
		<img class="gimg" src="./public/images/ajax-loader.gif">
	</div>
	<script type="text/javascript">
	    $(function () {
	        $("#save-btn").on("click", function () {
	            var $username = $("#username");
	            var username = $username.val();
	            var $phone = $("#phone");
	            var phone = $phone.val();
	            if (username == "") {
	                
	                jAlert("请输入用户名!", '友情提示');	
	                $username.focus();
	                return false;
	            }
	            if (phone == "") {
	               
	                jAlert("请输入手机号码!", '友情提示');	
	                $phone.focus();
	                return false;
	            }
	            var regu = /^[0-9]{8,20}$/;
	            var re = new RegExp(regu);
	            if (!re.test(phone)) {
	              
	                jAlert("请输入正确的手机号码!", '友情提示');	
	                $phone.focus();
	                return false;
	            }
	            if (confirm("信息提交后不可修改，真的确定要提交吗？")) {
	                var submitData = {
	                    id: "61",
	                    t: "rvmb8uisn585l989",
	                    code: $("#sncode").text(),
	                    tel: phone,
	                    action: "yyysj"
	                };
	                $.get('HAjax.aspx', submitData,
    function (data) {
        if (data.success == true) {
           
            jAlert("提交成功，谢谢您的参与", '友情提示');	
            return
        } else { }
    },
    "json");
	            }
	        });
	        $(document).on('ajaxBeforeSend', function (e, xhr, options) {
	            $("#loading").show();
	        }).on("ajaxComplete ", function (e, xhr, options) {
	            $("#loading").hide();
	        });
	    });
</script>

</body>
</html>