<?php
$user=User::getUserByOpenid();
if(!$user)exit();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>会员中心</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<link type="text/css" rel="stylesheet" href="css/icon.css">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/swiper.min.css"/>
<link type="text/css" rel="stylesheet" href="css/home.css">
<style>
body{ background-color: #fff; }
.vip-top{ height: 3.44rem; color:#fff; box-sizing: border-box; padding: .56rem .5rem 0 .5rem; background: url(images/bg_vip_top.png) no-repeat left top; background-size: 100% auto; }
.vip-top .faceimg{ width: 50px; height: 50px; border: 2px solid #fff; margin-right: 12px; }
.vip-top .uname{ font-size: 18px; }
.vip-card{ width: 6.7rem; height: 3.34rem; margin:0 auto; background: url(images/test/bg_vip_card.png) no-repeat left top; background-size: 100% auto; position: relative; }
.vip-card .time{ background-color: #F9D640; position: absolute; left: .48rem; right: .44rem; bottom: .38rem; border-radius: 17px; line-height: 34px; padding-left: 12px; }
.vip-cards{ margin-top: -76px; }
.vip-cards .swiper-slide{ margin: 0; }

.page-panel{ padding:0 18px; border-top: 10px solid #f6f6f6; margin-top: 18px; }
.ly-row .ico{ width: 22px; margin-right: 14px; }
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/swiper.min.js"></script>
</head>
<body>
    <div class="vip-top">
        <div class="doc-row col-center">
            <img src="<?php echo $user->wxInfo["headimgurl"]?>" class="faceimg" alt="">
            <div class="uname"><?php echo $user->nickName?></div>
        </div>
    </div>

    <div class="vip-cards">
        <section class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                if($user->cardID){
                    foreach ($user->cards as $card) {
                        echo '<div class="swiper-slide">
                            <div class="vip-card">
                                <div class="time">卡号:'.$var=sprintf("%09d", $card->cardID).' 有效期至:'.date("Y-m-d",$card->validTime).'</div>
                            </div>
                        </div>';
                    }
                }else{
                    echo '<div class="swiper-slide">
                            <div class="vip-card">
                                <div class="time">有效期至</div>
                            </div>
                        </div>';
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </section>
    </div>

    <div class="page-panel">
        <a href="/?p=userInfo" class="doc-row col-center ly-row big">
            <img src="images/ico_vip_userinfo.png" class="ico" alt="">
            <div>个人信息</div>
        </a>
        <a href="/?p=activiteCard" class="doc-row col-center ly-row big">
            <img src="images/ico_vip_activite.png" class="ico" alt="">
            <div>卡片激活</div>
        </a>
        <a href="/?p=service" class="doc-row col-center ly-row big">
            <img src="images/ico_vip_service.png" class="ico" alt="">
            <div>客服中心</div>
        </a>
        <a href="/?p=rule" class="doc-row col-center ly-row big">
            <img src="images/ico_vip_rule.png" class="ico" alt="">
            <div>使用规则</div>
        </a>
    </div>

    <div id="node_edit_phone" class="overlay">
        <div class="dialog">
            <span id="node_close_dialog" class="dialog-close"></span>
            <div class="dialog-title">注册</div>
            <form class="com-form">
                <div class="doc-row col-center">
                    <img src="images/ico_phone.png" class="ico" alt="">
                    <input type="tel" class="ipt flex-1 phone" placeholder="请输入手机号">
                </div>
                <div class="doc-row col-center">
                    <img src="images/ico_pswd.png" class="ico" alt="">
                    <input type="" class="ipt flex-1 code" placeholder="请输入验证码">
                    <button class="btn-get-code ly-btn theme" type="button">获取验证码</button>
                </div>
                <button class="btn-submit ly-btn theme big" type="button">提交</button>
            </form>
        </div>
    </div>

    <footer class="aui-footer">
        <a href="/?p=index" class="aui-tabBar-item">
                <span class="aui-tabBar-item-icon">
                    <img src="picture/index_17.png" >
                </span>
            <span class="aui-tabBar-item-text">首页</span>
        </a>
        <a href="/?p=orderList" class="aui-tabBar-item ">
                <span class="aui-tabBar-item-icon">
                    <img src="picture/index_19.png" >
                </span>
            <span class="aui-tabBar-item-text">我的订单</span>
        </a>
        <a href="/?p=aboutUs" class="aui-tabBar-item ">
                <span class="aui-tabBar-item-icon">
                    <img src="picture/index_21.png" >
                </span>
            <span class="aui-tabBar-item-text">关于我们</span>
        </a>
        <a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">
                <span class="aui-tabBar-item-icon">
                    <img src="picture/index_23_f.png" >
                </span>
            <span class="aui-tabBar-item-text">个人中心</span>
        </a>
    </footer>

<script>
$(function(){
    var banner = new Swiper('.swiper-container', {
        loop: true,
        autoplay: <?if($user->cardID && count($user->cards)>1) echo "true";else echo "false"?>,
        disableOnInteraction: false,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
        }
    });
    
    var $node_edit_phone = $('#node_edit_phone'),
        $node_close_dialog = $('#node_close_dialog');
    <?php
    if(!$user->phone){
        echo '$node_edit_phone.fadeIn("fast");';
    }else {
        
    }
    ?>
    $node_close_dialog.on('click', function(){
        $node_edit_phone.fadeOut('fast');
    });

    var phone;
    $(".btn-get-code").click(function(){
        let me=$(this);
        if(me.hasClass("disabled"))return;

        phone=$(".phone").val();
        if(!(/^1[3456789]\d{9}$/.test(phone))){
            alert("请输入正确的手机号");
            return;
        }
        $.get("/?f=userFun&func=sendSmsWithCaptcha&param=2,"+phone,function(data,status){
            
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
        var code=$(".code").val();
        phone=$(".phone").val();
        if(!(/^1[3456789]\d{9}$/.test(phone))){
            alert("请输入正确的手机号");
            return;
        }
        if(code==""){ 
            alert("请填入验证码");  
            return; 
        }
        me.addClass("disabled");
        $.get("/?f=userFun&func=regPhone&param="+phone+","+code,function(data,status){
            if(data==1){
                alert("注册成功");
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