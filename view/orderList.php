<?php 
$user=User::getUserByOpenid();
if(!$user)exit();
$orders=Order::getOrderByUserid($user->id);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>我的订单</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<link type="text/css" rel="stylesheet" href="css/icon.css">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link type="text/css" rel="stylesheet" href="css/home.css">
<style>
body{ background-color: #fff; padding-top: 1.28rem; color:#595959; }
.order-tab{ position: fixed; left:0; top:0; text-align: center;
width: 100%; line-height: .86rem; background-color: #F4F4F4; z-index: 1;
}
.order-tab .item{ position: relative; float: left; width: 25%;  font-size: 16px; }
.order-tab .item:after{ content: ' '; position: absolute; left:0; bottom: 0; width: 100%; height: 5px; background: #F8B500; display: none; }
.order-tab .item.active{ color:#F8B500; }
.order-tab .item.active:after{ display: inline; }
.order-list li{ margin-bottom: .28rem; }
.order-list .num{ line-height: .7rem; padding: 0 .54rem; background-color: #F4F4F4; }
.order-list .normal{ color:#F8B500; }
.order-list .txts{ padding: .2rem .54rem; position: relative; }
.order-list .txts:after{ width: auto; right:.54rem; }
.order-list .btn-action{ line-height: 28px; height: 28px; padding: 0 8px; border:1px solid #979797; border-radius: 6px; color:#9B9B9B; margin-left: 12px; position: relative; top: 6px; }
.list-empty{ text-align: center; margin-top: 50px; }
.order-dialog{ padding-top: 60px; }
.order-dialog .btns{  margin-top: 58px; }
.order-dialog .ly-btn{ width: 47%; }
</style>
</head>
<body>
<div id="app" v-cloak>
    <div class="order-tab">
        <div @click="curTab=1;" class="item" :class="{'active': curTab == 1}">全部</div>
        <div @click="curTab=2;" class="item" :class="{'active': curTab == 2}">待入住</div>
        <div @click="curTab=3;" class="item" :class="{'active': curTab == 3}">已入住</div>
        <div @click="curTab=4;" class="item" :class="{'active': curTab == 4}">已取消</div>
    </div>

    <ul v-show="curTab == 1" class="order-list fz14">
        <?php
        if(sizeof($orders)==0){
            echo '<div class="list-empty fz16">你还没有订单哦～</div>';
        }else{
            foreach ($orders as $order) {
                echo '<li prop="'.$order->id.'" roomid="'.$order->orderRoom.'">
                        <div class="num doc-row align-justify">
                            <span>订单编号：'.$order->id.'</span>
                            <span class="normal">'.$order->stateStr.'</span>
                        </div>
                        <div class="txts hairline-in-pseudo-bottom">
                            <div class="fz18 mb8 dark"><a href="/?p=hotelInfo&hotelID='.$order->orderHotel.'">'.$order->orderHotelName.'</a></div>
                            <div class="info doc-row align-justify">
                                <div class="flex-1">
                                    <div class="mb4">'.$order->startTime.' 至 '.$order->endTime.'</div>
                                    <div>'.$order->orderDays.'晚  1间  '.$order->orderRoomName.'</div>
                                    '.($order->state==1?'<div style="color: #f8b500;">您的酒店已预订成功，本房间仅限您本人携带身份证办理入住手续。</div>':'')
                                    .($order->state==5?'<div style="color: #f8b500;">很抱歉，此酒店今天无合适房型，请您选择本平台其他酒店。</div>':'').'
                                </div>
                                '.(($order->state==0 || $order->state==1)?'<div class="btn-action node_cancel_order">取消订单</div>':'<div class="btn-action repeat_order">再次预定</div>').'
                            </div>
                        </div>
                    </li>';
            }
        }
        ?>        
    </ul>
    <ul v-show="curTab == 2" class="order-list fz14">
        <?php
        if(sizeof($orders)==0){
            echo '<div class="list-empty fz16">你还没有订单哦～</div>';
        }else{
            foreach ($orders as $order) {
                if($order->state!=0 && $order->state!=1)continue;
                echo '<li prop="'.$order->id.'" roomid="'.$order->orderRoom.'">
                        <div class="num doc-row align-justify">
                            <span>订单编号：'.$order->id.'</span>
                            <span class="normal">'.$order->stateStr.'</span>
                        </div>
                        <div class="txts hairline-in-pseudo-bottom">
                            <div class="fz18 mb8 dark"><a href="/?p=hotelInfo&hotelID='.$order->orderHotel.'">'.$order->orderHotelName.'</a></div>
                            <div class="info doc-row align-justify">
                                <div class="flex-1">
                                    <div class="mb4">'.$order->startTime.' 至 '.$order->endTime.'</div>
                                    <div>'.$order->orderDays.'晚  1间  '.$order->orderRoomName.'</div>
                                    '.($order->state==1?'<div style="color: #f8b500;">您的酒店已预订成功，本房间仅限您本人携带身份证办理入住手续。</div>':'').'
                                </div>
                                <div class="btn-action node_cancel_order">取消订单</div>
                            </div>
                        </div>
                    </li>';
            }
        }
        ?> 
    </ul>
    <ul v-show="curTab == 3" class="order-list fz14">
        <?php
        if(sizeof($orders)==0){
            echo '<div class="list-empty fz16">你还没有订单哦～</div>';
        }else{
            foreach ($orders as $order) {
                if($order->state!=3)continue;
                echo '<li prop="'.$order->id.'" roomid="'.$order->orderRoom.'">
                        <div class="num doc-row align-justify">
                            <span>订单编号：'.$order->id.'</span>
                            <span class="normal">'.$order->stateStr.'</span>
                        </div>
                        <div class="txts hairline-in-pseudo-bottom">
                            <div class="fz18 mb8 dark"><a href="/?p=hotelInfo&hotelID='.$order->orderHotel.'">'.$order->orderHotelName.'</a></div>
                            <div class="info doc-row align-justify">
                                <div class="flex-1">
                                    <div class="mb4">'.$order->startTime.' 至 '.$order->endTime.'</div>
                                    <div>'.$order->orderDays.'晚  1间  '.$order->orderRoomName.'</div>
                                </div>
                                '.($order->state==0?'<div class="btn-action node_cancel_order">取消订单</div>':'<div  class="btn-action repeat_order">再次预定</div>').'
                            </div>
                        </div>
                    </li>';
            }
        }
        ?> 
    </ul>
    <ul v-show="curTab == 4" class="order-list fz14">
        <?php
        if(sizeof($orders)==0){
            echo '<div class="list-empty fz16">你还没有订单哦～</div>';
        }else{
            foreach ($orders as $order) {
                if($order->state!=2 && $order->state!=4 && $order->state!=5)continue;
                echo '<li prop="'.$order->id.'" roomid="'.$order->orderRoom.'">
                        <div class="num doc-row align-justify">
                            <span>订单编号：'.$order->id.'</span>
                            <span class="normal">'.$order->stateStr.'</span>
                        </div>
                        <div class="txts hairline-in-pseudo-bottom">
                            <div class="fz18 mb8 dark"><a href="/?p=hotelInfo&hotelID='.$order->orderHotel.'">'.$order->orderHotelName.'</a></div>
                            <div class="info doc-row align-justify">
                                <div class="flex-1">
                                    <div class="mb4">'.$order->startTime.' 至 '.$order->endTime.'</div>
                                    <div>'.$order->orderDays.'晚  1间  '.$order->orderRoomName.'</div>
                                    '.($order->state==5?'<div style="color: #f8b500;">很抱歉，此酒店今天无合适房型，请您选择本平台其他酒店。</div>':'').'
                                </div>
                                '.($order->state==0?'<div class="btn-action node_cancel_order">取消订单</div>':'<div class="btn-action repeat_order">再次预定</div>').'
                            </div>
                        </div>
                    </li>';
            }
        }
        ?> 
    </ul>
    

    <div id="node_dialog_cancel" class="overlay">
        <div class="dialog dark fz18">
            <span id="node_close_dialog" class="dialog-close"></span>
            <div class="order-dialog">
                <div>您确认要取消该订单吗？</div>
                <div>取消后不能恢复～</div>
                <div class="btns doc-row align-justify">
                    <button class="ly-btn black big cancelOK">是</button>
                    <button class="ly-btn theme big cancelNO">否</button>
                </div>
            </div>        
        </div>
    </div>
</div>
<footer class="aui-footer">
    <a href="/?p=index" class="aui-tabBar-item">
            <span class="aui-tabBar-item-icon">
                <img src="picture/index_17.png" >
            </span>
        <span class="aui-tabBar-item-text">首页</span>
    </a>
    <a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">
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
    <a href="/?p=userHome" class="aui-tabBar-item ">
            <span class="aui-tabBar-item-icon">
                <img src="picture/index_23.png" >
            </span>
        <span class="aui-tabBar-item-text">个人中心</span>
    </a>
</footer>

<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/zepto.min.js"></script>
<script src="js/vue.2.5.13.min.js"></script>
<script>
$(function(){
    new Vue({
      el: '#app',
      data: {
         curTab: 1
      },
      methods: {

      }
    });
    var node_cancel_order = $('.node_cancel_order'),
        node_dialog_cancel = $('#node_dialog_cancel'),
        repeat_order = $('.repeat_order'),
        cancelOK = $('.cancelOK'),
        cancelNO = $('.cancelNO'),
        node_close_dialog = $('#node_close_dialog');
    var orderid="",room="";
    node_cancel_order.on('click', function(){
        orderid=$(this).parents("li").attr("prop");
        node_dialog_cancel.fadeIn('fast');
    });
    cancelOK.on('click', function(){
        $.get("/?f=userFun&func=cancelOrder&param="+orderid,
            function(result){
            if(result==1){
                alert("取消成功");
                window.location = './?p=orderList';
            }else if(result==2){
                alert("即将入住，无法取消");
            }else{
                alert(result);
            }
            node_dialog_cancel.fadeOut('fast');
        });
        node_dialog_cancel.fadeIn('fast');
    });
    repeat_order.on('click', function(){
        room=$(this).parents("li").attr("roomid");
        location.href="/?p=roomInfo&roomID="+room;
    });
    node_close_dialog.on('click', function(){
        node_dialog_cancel.fadeOut('fast');
    });
    cancelNO.on('click', function(){
        node_dialog_cancel.fadeOut('fast');
    });
});
</script>
</body>
</html>