<?php
@$startDate=isset($_REQUEST["startDate"])?$_REQUEST["startDate"]:null;
@$endDate=isset($_REQUEST["endDate"])?$_REQUEST["endDate"]:null;
@$roomID=isset($_REQUEST["roomID"])?$_REQUEST["roomID"]:null;
$room=new Room($roomID);
$room->initInfo();
$user=User::getUserByOpenid();
if(!$user)exit();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>房间信息</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<link type="text/css" rel="stylesheet" href="css/icon.css">
<link rel="stylesheet" type="text/css" href="css/swiper.min.css"/>
<link rel="stylesheet" type="text/css" href="css/home.css"/>
<link rel="stylesheet" type="text/css" href="css/sweetalert2.min.css"/>
<style>
body{ background-color: #fff; padding-bottom: 1.16rem; font-size: 12px; }
img{ display: inline-block; }
.vip-dialog{ padding-top: 40px; }
.vip-dialog .btns{  margin-top: 38px; }
.vip-dialog .ly-btn{ width: 46%; }

.main{ padding: 15px; }
.main-info{ border-radius: 4px; background-color: #F7FAFF; padding:16px 24px; text-align: center; }
.main-info.for-details{ background-color: #fff; padding: 0 0 12px 0; border-bottom: 1px solid #efefef; }
.main-info.for-details .item-detail{ float: left; width: 25%; margin: 18px 0 0 0; }
.main-info .ico{ height: 20px; }
.main-info .des{ line-height: 22px; }
.sub-info{ padding: 20px 10px; }
.btm-btn{ position: fixed; left:0; bottom:0; right:0; padding: 6px 15px; background-color: #fff; z-index: 1000; }
.dialog-invalid{ background: #fff url(images/bg_face.png) no-repeat right bottom; background-size: 132px auto; }
.dialog-invalid .bd{ padding: 50px 20px; }
.dialog-invalid .title{ font-size: 22px; color:#000; margin-bottom: 18px; }
.top-banner{ padding-top: 10px; }
.swiper-container { margin:0; width: 100%; height: 206px;
}
.swiper-container .swiper-slide { width: 90%; border-radius: 6px; box-shadow: 0 0 8px 0 rgba(0, 0, 0, .2); margin: 0 5px;
    height: 100%;
    background-size: cover; 
}
.sub-info .gray{ font-size: 14px; }
#checkinout{ margin-top: 14px; border-top:1px solid #efefef; margin-left: 0; margin-right: 0; }
.swal2-popup{font-size: initial;}
</style>
</head>
<body>
    <div class="top-banner">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                foreach (explode(',', $room->pics) as $pic) {
                    echo '<div class="swiper-slide" style="background: url('.$pic.') no-repeat center;background-size:100% 100%;  "></div>';
                }
                ?>
            </div>
        </div>
    </div>  
    <div class="main">
        <div class="main-title fz18"><?php echo $room->type;?></div>
        <div class="main-info gray doc-row align-justify mt10">
            <div>
                <img class="ico" src="images/people.png" alt="">
                <div class="des">限住<?php echo $room->people;?>人</div>
            </div>
            <div>
                <img class="ico" src="images/bed.png" alt="">
                <div class="des"><?php echo $room->bed;?></div>
            </div>
            <div>
                <img class="ico" src="images/ico_date.png" alt="">
                <div class="des"><?php echo $room->space;?>㎡</div>
            </div>
        </div>
        <div id="checkinout" class="b-line">
            <div id="firstSelect" style="width:100%;">
                <div class="Date_lr" style="float:left;">
                    <P style="text-align:left;padding-left:.15rem;">入住</p>
                    <input id="startDate" type="text" value="<?php echo $startDate;?>" readonly>
                    <span class="week"></span>
                </div>
                <span class="aui-calendar-days">共<span class="NumDate">1</span>晚</span>
                <div class="Date_lr" style="float:right;padding-right:.07rem;">
                    <P style="text-align:right;padding-right: .15rem;">离店</p>
                    <input id="endDate" type="text" value="<?php echo $endDate;?>" readonly>
                    <span class="week1"></span>
                </div>

            </div>
        </div>
        <div class="main-info for-details cf gray">
            <?php
                foreach ($room->getRoomProp() as $propK=>$propN) {
                    echo '<div class="item-detail">
                            <img class="ico" src="images/'.$propK.'.png" alt="">
                            <div class="des">'.$propN.'</div>
                        </div>';
                }
            ?>
        </div>
        <div class="sub-info">
            <div class="fz16 bold mb8">房间介绍</div>
            <div class="gray"><?php echo $room->description;?></div>
        </div>
        <div class="sub-info">
            <div class="fz16 bold mb8"></div>
            <div class="gray"></div>
        </div>
    </div>

    <div class="btm-btn hairline-in-pseudo-top">
        <?if($user->cardID && $user->canUseCard($room->enableCard)!=0){
            echo '<button id="orderBtn" class="ly-btn theme big">预定房间</button>';
          }else{
            echo '<button id="node_join_vip" class="ly-btn theme big">加入会员</button>';
          }
        ?>
        
    </div>
    
    <div id="node_dialog_vip" class="overlay">
        <div class="dialog dark fz18">
            <span id="node_close_dialog" class="dialog-close"></span>
            <div class="vip-dialog">
                <div class="gray1 fz16">您还不是Hoothin酒店集团会员哦～</div>
                <div class="mt16">如果你喜欢</div>
                <div>就购买/激活会员卡吧！</div>
                <div class="btns doc-row align-justify">
                    <button class="ly-btn black big buyCard">购卡</button>
                    <button class="ly-btn theme big activite">卡片激活</button>
                </div>
            </div>        
        </div>
    </div>

    <div id="node_dialog_order" class="overlay">
        <div class="dialog dark fz18">
            <span id="node_close_dialog" class="dialog-close"></span>
            <div class="vip-dialog">
                <div class="gray1 fz16">您确定要预定该房间吗？</div>
                <div class="mt16 orderTime">时间：</div>
                <div></div>
                <div class="btns doc-row align-justify">
                    <button class="ly-btn black big orderOK">预定</button>
                    <button class="ly-btn theme big orderCancel">取消</button>
                </div>
            </div>        
        </div>
    </div>

    <div id="node_dialog_invalid" class="overlay">
        <div class="dialog dialog-invalid dark">
            <span id="node_close_dialog" class="dialog-close"></span>
            <div class="bd">
                <div class="title">您暂无订房权限</div>
                <div class="des fz16 gray1">您的处罚时间</div>
                <div class="des fz16 gray1">将于<?echo $user->banEndTime?>结束</div>
            </div>
        </div>
    </div>

    <!-- 入住离店日期弹窗 begin -->
    <div class="mask_calendar">
        <div class="calendar"></div>
        <div class="calendar_tishi aui-calendar-tips">请选择离店时间</div>
    </div>
    <!-- 入住离店日期弹窗 end -->

<script type="text/javascript" src="js/jquery.js"></script>    
<script type="text/javascript" src="js/swiper.min.js"></script>
<script type="text/javascript" src="js/tab.js"></script>
<script type="text/javascript" src="js/date.js"></script>
<script type="text/javascript" src="js/layer.js"></script>
<script type="text/javascript" src="js/sweetalert2.min.js"></script>
<script>
<?
echo "var roomNum=JSON.parse('".json_encode($room->get7daysRoomsNum())."');";
?>
$(function() {
    $('#firstSelect').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        $('.mask_calendar').show();
    });
    $('.mask_calendar').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        if (e.target.className == "mask_calendar") {
            $('.calendar').slideUp(200);
            $('.mask_calendar').fadeOut(200);
        }
    })
    $('#firstSelect').calendarSwitch({
        selectors: {
            sections: ".calendar"
        },
        index: 4,
        //展示的月份个数
        animateFunction: "slideToggle",
        //动画效果
        controlDay: true,
        //知否控制在daysnumber天之内，这个数值的设置前提是总显示天数大于90天
        daysnumber: <?php echo $GLOBALS["sysConfig"]["maxOrderDay"];?>,
        //连续可订天数
        maxDays: <?php echo $GLOBALS["sysConfig"]["seriesOrderDay"];?>,
        //控制天数
        comeColor: "#0195ff",
        //入住颜色
        outColor: "#0195ff",
        //离店颜色
        comeoutColor: "#8ccfff",
        fullState:roomNum,
        //入住和离店之间的颜色
        callback: function() {
            //回调函数
            $('.mask_calendar').fadeOut(200);
            var startDate = $('#startDate').val();
            //入住的天数
            var endDate = $('#endDate').val();
            //离店的天数
            var NumDate = $('.NumDate').text();
            //共多少晚
            console.log(startDate);
            console.log(endDate);
            console.log(NumDate);
            //下面做ajax请求
        },
        comfireBtn: '.comfire'//确定按钮的class或者id
    });
    var b = new Date();
    var ye = b.getFullYear();
    var mo = b.getMonth() + 1;
    mo = mo < 10 ? "0" + mo : mo;
    var da = b.getDate();
    da = da < 10 ? "0" + da : da;
    if(!$('#startDate').val())
    $('#startDate').val(ye + '-' + mo + '-' + da);
    b = new Date(b.getTime() + 24 * 3600 * 1000);
    var ye = b.getFullYear();
    var mo = b.getMonth() + 1;
    mo = mo < 10 ? "0" + mo : mo;
    var da = b.getDate();
    da = da < 10 ? "0" + da : da;
    if(!$('#endDate').val())
    $('#endDate').val(ye + '-' + mo + '-' + da);

    var myweek = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
    var st = new Date($('#startDate').val());
    var en = new Date($('#endDate').val());
    $('.week').text(myweek[st.getDay()])
    $('.week1').text(myweek[en.getDay()])
});
$(function(){
    var mySwiper = new Swiper('.swiper-container', {
        loop: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        observeParents: true,
        observer: true,
        observeSlideChildren: true,
        autoplay: {
            disableOnInteraction: false,
        },
        watchOverflow: true
    });

    var $node_dialog_invalid = $('#node_dialog_invalid');
    //$node_dialog_invalid.fadeIn();

    var $node_join_vip = $('#node_join_vip'),
        $node_dialog_vip = $('#node_dialog_vip'),
        $node_dialog_order = $('#node_dialog_order'),
        $orderOK = $('.orderOK'),
        $orderCancel = $('.orderCancel'),
        $node_close_dialog = $('.dialog-close'),
        $orderBtn = $('#orderBtn'),
        $activite = $('.activite'),
        $buyCard = $('.buyCard');

    

    $activite.on('click', function(){
        location.href="/?p=activiteCard";
    });
    $buyCard.on('click', function(){
        location.href="/?p=aboutUs";
    });
    $node_join_vip.on('click', function(){
        $node_dialog_vip.fadeIn('fast');
    });
    $orderBtn.on('click', function(){
        var startDate=$("#startDate").val();
        var endDate=$("#endDate").val();
        $(".orderTime").html("时间："+startDate+" 至 "+endDate);
        $node_dialog_order.fadeIn('fast');
    });
    $orderOK.on('click', function(){
        $node_dialog_order.fadeOut('fast');
        var startDate=$("#startDate").val();
        var endDate=$("#endDate").val();
        <?if($user->isBan==1){
            echo '$node_dialog_invalid.fadeIn("fast");';
          }else{
            echo '$.get("/?f=userFun&func=order&param='.$roomID.',"+startDate+","+endDate,
                    function(result){
                    if(result==1){
                        //alert("预定成功");
                        Swal.fire({
                          title: "等待酒店确认",
                          text: "您的预定信息已提交！正在等待酒店确认，请在个人中心关注订单进度",
                          type: "success",
                          confirmButtonText: "确认"
                        }).then((isConfirm) => {
                            try {
                                if (isConfirm.value) {
                                    window.location = "./?p=orderList";
                                }
                                else {
                                }
                            } catch (e) {
                                alert(e);
                            }
                        });
                    }else{
                        alert(result);
                    }
                });';
          }
        ?>
    });
    $orderCancel.on('click', function(){
        $node_dialog_order.fadeOut('fast');
    });
    $node_close_dialog.on('click', function(){
        $node_dialog_vip.fadeOut('fast');
        $node_dialog_invalid.fadeOut('fast');
        $node_dialog_order.fadeOut('fast');
    });
});
</script>
</body>
</html>