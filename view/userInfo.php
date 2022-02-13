<?php 
$user=User::getUserByOpenid();
if(!$user)exit();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>个人信息</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<link type="text/css" rel="stylesheet" href="css/icon.css">
<link type="text/css" rel="stylesheet" href="css/home.css">
<link type="text/css" rel="stylesheet" href="css/userhome.css">
</head>
<body>

    <div class="upanel">
        <div class="doc-row col-center align-justify hairline-in-pseudo-bottom">
            <div>头像</div>            
            <img src="<?php echo $user->wxInfo["headimgurl"]?>" class="faceimg" alt="">
        </div>
        <div class="doc-row col-center align-justify hairline-in-pseudo-bottom">
            <div>昵称</div>            
            <div><?php echo $user->nickName?></div>
        </div>
        <div class="doc-row col-center align-justify hairline-in-pseudo-bottom">
            <div>真实姓名</div>            
            <div><?php echo $user->realName?></div>
        </div>
        <div id="node_phone" class="doc-row col-center align-justify hairline-in-pseudo-bottom">
            <div>手机号</div>            
            <div class="uphone arrow-by-border"><?php echo $user->phone?></div>
        </div>
        <div class="doc-row col-center align-justify hairline-in-pseudo-bottom">
            <div>身份证</div>            
            <div><?php echo $user->idCard?></div>
        </div>
    </div>

    <div id="node_edit_phone" class="overlay">
        <div class="dialog">
            <span id="node_close_dialog" class="dialog-close"></span>
            <div class="dialog-title">更换手机号</div>
            <form class="com-form">
                <div class="doc-row col-center">
                    <img src="images/ico_phone.png" class="ico" alt="">
                    <input type="tel" class="ipt flex-1 phoneNum" placeholder="请输入手机号">
                </div>
                <div class="doc-row col-center">
                    <img src="images/ico_pswd.png" class="ico" alt="">
                    <input type="tel" class="ipt flex-1 validCode" placeholder="请输入验证码">
                    <button class="btn-get-code ly-btn theme" type="button">获取验证码</button>
                </div>
                <button class="btn-submit ly-btn theme big" type="button">提交</button>
            </form>
        </div>
    </div>

<script src="js/jquery.js"></script>
<script>
$(function(){
    var $node_phone = $('#node_phone'),
        $node_edit_phone = $('#node_edit_phone'),
        $node_close_dialog = $('#node_close_dialog');
    $node_phone.on('click', function(){
        $node_edit_phone.fadeIn('fast');
    });
    $node_close_dialog.on('click', function(){
        $node_edit_phone.fadeOut('fast');
    });

    $(".btn-get-code").click(function(){
        let me=$(this);
        if(me.hasClass("disabled"))return;
        var phone = $('.phoneNum').val();
        if(!(/^1[3456789]\d{9}$/.test(phone))){ 
            alert("手机号码有误，请重填");  
            return; 
        }
        $.get("/?f=userFun&func=sendSmsWithCaptcha&param=0,"+phone,function(data,status){
            
        });
        var leftTime=60;
        me.removeClass("theme").addClass("disabled").html("重新发送(60)");
        var intval=setInterval(function(){
            me.html("重新发送("+--leftTime+")");
            if(leftTime==0){
                clearInterval(intval);
                me.removeClass("disabled").addClass("theme").html("获取验证码");
            }
        }, 1000);
    });
    $(".btn-submit").click(function(){
        let me=$(this);
        if(me.hasClass("disabled"))return;
        var phone = $('.phoneNum').val();
        var code=$(".validCode").val();
        if(!(/^1[3456789]\d{9}$/.test(phone))){ 
            alert("手机号码有误，请重填");  
            return; 
        }
        if(code==""){ 
            alert("请填入验证码");  
            return; 
        }
        me.addClass("disabled");
        $.get("/?f=userFun&func=regPhone&param="+phone+","+code,function(data,status){
            if(data==1){
                alert("更改成功");
                $node_edit_phone.fadeOut('fast');
            }else{
                alert(data);
            }
            me.removeClass("disabled");
        });
    });
});
</script>
</body>
</html>