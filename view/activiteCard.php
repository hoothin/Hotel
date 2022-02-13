<?php 
$user=User::getUserByOpenid();
if(!$user)exit();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>卡片激活</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<link type="text/css" rel="stylesheet" href="css/icon.css">
<link type="text/css" rel="stylesheet" href="css/home.css">
<style>
body{ background-color: #fff; }
.page-panel{ padding:0 30px; border-top:20px solid #f6f6f6; }
.error{ text-align: right; }
</style>
</head>
<body>

    <div class="page-panel">
        <form class="com-form">
            <div class="doc-row col-center">
                <img src="images/ico_name.png" class="ico" alt="">
                <input type="text" class="ipt flex-1 realName" placeholder="请输入真实姓名">
            </div>
            <div class="doc-row col-center">
                <img src="images/ico_id_card.png" class="ico" alt="">
                <input type="text" class="ipt flex-1 idCard" placeholder="请输入身份证号码">
            </div>
            <div class="doc-row col-center">
                <img src="images/ico_date.png" class="ico" alt="">
                <input type="text" class="ipt flex-1 cardID" placeholder="请输入卡号">
            </div>
            <div class="doc-row col-center">
                <img src="images/ico_card_num.png" class="ico" alt="">
                <input type="text" class="ipt flex-1 cardPass" placeholder="请输入卡密">
            </div>
            <div class="doc-row col-center">
                <img src="images/ico_phone.png" class="ico" alt="">
                <input type="tel" class="ipt flex-1 phoneNum" placeholder="请输入手机号">
            </div>
            <div class="doc-row col-center">
                <img src="images/ico_pswd.png" class="ico" alt="">
                <input type="text" class="ipt flex-1 validCode" placeholder="请输入验证码">
                <button class="btn-get-code ly-btn theme" type="button">获取验证码</button>
            </div>
            <!-- <div class="doc-row col-center">
                <img src="images/ico_pswd.png" class="ico" alt="">
                <input type="text" class="ipt flex-1" placeholder="请输入验证码">
                <button class="btn-get-code ly-btn disabled" type="button">重新发送(59)</button>
            </div> -->
            <button class="btn-submit ly-btn theme big" type="button">提交</button>
        </form>
        <div class="error mt15"><!-- 实名认证失败 --></div>
    </div>

<script src="js/jquery.js"></script>
<script src="js/idCardNoUtil.js"></script>
<script>
$(function(){
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
        var validCode=$(".validCode").val();
        var realName=$(".realName").val();
        var idCard=$(".idCard").val();
        var cardID=$(".cardID").val();
        var cardPass=$(".cardPass").val();
        if(!(/^1[3456789]\d{9}$/.test(phone))){ 
            alert("手机号码有误，请重填");  
            return; 
        }
        if(validCode==""){ 
            alert("请填入验证码");  
            return; 
        }
        if(realName==""){ 
            alert("请填入真实姓名");  
            return; 
        }
        if(idCard==""){ 
            alert("请填入身份证号码");  
            return; 
        }
        if(idCardNoUtil.checkIdCardNo(idCard)==false){ 
            alert("请填入正确的身份证号码");  
            return; 
        }
        if(cardID==""){ 
            alert("请填入会员卡号");  
            return; 
        }
        if(cardPass==""){ 
            alert("请填入会员卡密");  
            return; 
        }
        $.get("/?f=userFun&func=activite&param="+validCode+","+realName+","+idCard+","+cardID+","+cardPass+","+phone,
            function(result){
            if(result==1){
                alert("激活成功");
                window.location = './?p=index';
            }else{
                alert(result);
                me.removeClass("disabled");
            }
        });
        me.addClass("disabled");
    });
});
</script>
</body>
</html>