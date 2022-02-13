<?php
@$startDate=isset($_REQUEST["startDate"])?$_REQUEST["startDate"]:null;
@$endDate=isset($_REQUEST["endDate"])?$_REQUEST["endDate"]:null;
@$hotelID=isset($_REQUEST["hotelID"])?$_REQUEST["hotelID"]:null;
$hotel=new Hotel($hotelID);

require_once "jssdk.php";
global $appid;
global $appKey;
$jssdk = new JSSDK($appid, $appKey);
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>酒店信息</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<link rel="stylesheet" type="text/css" href="css/home.css"/>
<link rel="stylesheet" type="text/css" href="css/swiper.min.css"/>
<style>
body{ background-color: #fff; padding-bottom: 50px; height: auto; }
img{ display: inline-block; }
.top-banner{ padding-top: 10px; }
.swiper-container { margin:0; width: 100%; height: 206px;
}
.swiper-container .swiper-slide { width: 90%; border-radius: 6px; box-shadow: 0 0 8px 0 rgba(0, 0, 0, .2); margin: 0 5px; height: 100%;
    background-size: cover; }
.hotel-info{ padding:10px; position: relative; border-bottom: 1px solid #EFEFEF; }
.hotel-info .icos{ width: 62px; font-size: 0; overflow: hidden; }
.hotel-info .ico-item{ margin-left: 8px; float: left; }
.hotel-info .ico-item img{ width: 22px; }
#checkinout{ border-top:1px solid #efefef; margin-left: 0; margin-right: 0; }
.aui-list .txts{ position: relative; }
.aui-list .txts p{ padding-bottom: 0; }
.aui-list .btn-order{ width: 2.42rem; height: .64rem; line-height: .64rem; border-radius: 6px; padding: 0; position: absolute; right: 0; bottom: 0; background: #f8b500; color: #fff; text-align: center; font-size: 16px; }
.disabled{
    background-color: #CCCCCC!important;
    color: #fff!important;
}
</style>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
</head>
<body>   

   <div class="top-banner">
      <div class="swiper-container">
            <div class="swiper-wrapper">
              <?php
              foreach (explode(',', $hotel->pics) as $pic) {
                echo '<div class="swiper-slide" style="background: url('.$pic.') no-repeat center;background-size:100% 100%;  "></div>';
              }
              ?>
            </div>
      </div>
   </div>  

   <div class="doc-wrap">
      <div class="hotel-info doc-row col-center align-justify">
         <div class="flex-1">
            <div class="fz18 "><?php echo $hotel->name;?></div>
            <div class="gray1 fz14"><?php echo $hotel->posStr;?></div>
         </div>
         <div class="icos">
            <a href="javascript:void(0);" onclick="wx.openLocation({latitude: <?echo explode(',', $hotel->gps)[0]?>,longitude: <?echo explode(',', $hotel->gps)[1]?>,name: '<?echo $hotel->name?>',address: '<?echo $hotel->posStr?>',scale: 16,infoUrl: ''});" class="ico-item">
               <img src="images/ico_hotel_location.png" alt="">
            </span>
            <a href="tel:<?php echo $hotel->phone;?>" class="ico-item">
               <img src="images/ico_hotel_tel.png" alt="">
            </a>
         </div>
      </div>
      <div id="checkinout" class="b-line">
         <div id="firstSelect" style="width:100%;">
               <div class="Date_lr" style="float:left;">
                  <P style="text-align:left;padding-left:.15rem;">入住</p>
                  <input id="startDate" type="text" value="<?php echo $startDate;?>" style="" readonly>
                  <span class="week"></span>
               </div>
               <span class="aui-calendar-days">共<span class="NumDate">1</span>晚</span>
               <div class="Date_lr" style="float:right;padding-right:.07rem;">
                  <P style="text-align:right;padding-right: .15rem;">离店</p>
                  <input id="endDate" type="text" value="<?php echo $endDate;?>" style="" readonly>
                  <span class="week1"></span>
               </div>
         </div>
      </div>
   </div>
   <?php
   foreach ($hotel->rooms as $room) {
     $enableRoomsNum=$room->getEnableRoomsNum($startDate, $endDate);
     echo '<section class="aui-list" roomid="'.$room->id.'">
            <div class="aui-list-img">
               <img src="'.(explode(',', $room->pics))[0].'" class="" alt="">
               <div class="aui-list-mask">
                  <p>'.join("/",$room->getCardsName("","可订")).'<span class="fr" style="display:none;">'.$enableRoomsNum.'间可订</span></p>
               </div>
            </div>
            <a href="#">
               <h5 class="dark">'.$room->type.'</h5>
               <div class="txts">
                  <p>'.$room->space.'㎡ '.$room->bed.' '.$room->people.'人入住</p>  
                  <div class="price">¥'.$room->price.'</div>
                  '.($enableRoomsNum==0?'<span class="btn-order disabled">满房</span>':'<span class="btn-order">预定</span>').'
               </div>
            </a>  
          </section>';
   }
   ?>

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
<script>
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
        index: 2,
        //展示的月份个数
        animateFunction: "slideToggle",
        //动画效果
        controlDay: true,
        //控制在daysnumber天之内，这个数值的设置前提是总显示天数大于90天
        daysnumber: <?php echo $GLOBALS["sysConfig"]["maxOrderDay"];?>,
        //连续可订天数
        maxDays: <?php echo $GLOBALS["sysConfig"]["seriesOrderDay"];?>,
        //控制天数
        comeColor: "#0195ff",
        //入住颜色
        outColor: "#0195ff",
        //离店颜色
        comeoutColor: "#8ccfff",
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
            $(".aui-list-mask .fr").each(function(){
              let me=$(this);
              $.get("/?f=searchFun&func=getEnableRoomsNumByRoomid&param="+me.parents("section").attr("roomid")+","+$('#startDate').val()+","+$('#endDate').val(),function(data,status){
                me.html(data+"间可订");
                var yellowSpan=me.parents("section").find("span.btn-order");
                if(data<=0){
                  yellowSpan.addClass("disabled");
                  yellowSpan.html("满房");
                }else{
                  yellowSpan.removeClass("disabled");
                  yellowSpan.html("预定");
                }
              });
            });
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

    $(".aui-list").click(function(){
        location.href="/?p=roomInfo&roomID="+$(this).attr("roomid")+"&startDate="+$("#startDate").val()+"&endDate="+$("#endDate").val();
    });
    wx.config({
      debug: false,
      appId: '<?php echo $signPackage["appId"];?>',
      timestamp: <?php echo $signPackage["timestamp"];?>,
      nonceStr: '<?php echo $signPackage["nonceStr"];?>',
      signature: '<?php echo $signPackage["signature"];?>',
      jsApiList: [
      'openLocation'
      ]
    });
});
</script>
</body>
</html>