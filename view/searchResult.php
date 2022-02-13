<?php 
@$startDate=isset($_REQUEST["startDate"])?$_REQUEST["startDate"]:null;
@$endDate=isset($_REQUEST["endDate"])?$_REQUEST["endDate"]:null;
@$hotelName=isset($_REQUEST["hotelName"])?$_REQUEST["hotelName"]:null;
@$targetLoc=isset($_REQUEST["targetLoc"])?unescape($_REQUEST["targetLoc"]):null;
if(!$targetLoc)$targetLoc="全国";
if(!$startDate)$startDate=date("Y-m-d");
if(!$endDate){
  $d=strtotime("tomorrow");
  $endDate=date("Y-m-d", $d);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>搜索结果</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<link rel="stylesheet" type="text/css" href="css/home.css"/>
<link rel="stylesheet" type="text/css" href="css/loader.css">
<style>
body{ background-color: #fff; padding-top: 58px; }
.top-kits{ position: fixed; left:0; top:0; right: 0; padding: 8px 16px;  background-color: #fff; z-index: 2000; }
.top-ipt{ background-color: #F8F8F8; border-radius: 6px; line-height: 42px; }
.top-ipt .name{ padding: 0 12px 0 18px; position: relative; font-size: 16px; margin-right: 20px; }
.top-ipt .name:after{ right:0; bottom:16px; }
.aui-list a p span.yellow{ border-radius: 6px; }
#checkinout{ border-top:1px solid #efefef; margin-left: 0; margin-right: 0; }

</style>
</head>
<body>   

   <div class="top-kits">
      <div class="top-ipt doc-row">
         <a href="/?p=cityList&f=searchResult" class="name arrow-solid"><?php echo $targetLoc;?></a>
         <input type="text" id="hotelName" class="flex-1 fz14" placeholder="请输入酒店名称" value="<?php echo $hotelName?>"/>
      </div>
   </div>

   <div class="doc-wrap">
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
   $hotels=Hotel::searchHotel($targetLoc, $hotelName, $endDate);
   foreach ($hotels as $hotel) {
     echo '<section class="aui-list hotel" hotelid="'.$hotel->id.'">
          <div class="aui-list-img">
             <img src="'.explode(',', $hotel->pics)[0].'" class="" alt="">
             <div class="aui-list-mask">
                <p>'.join("/",$hotel->getCardsName("","可订")).'<span class="fr">0间可订</span></p>
             </div>
          </div>
          <a href="#">
             <h5>'.$hotel->name.'</h5>
             <p>'.$hotel->pos->city." ".$hotel->pos->county.' <span class="yellow fr">会员免费</span></p> 
          </a>
       </section>';
   }
   ?>
   <!--section class="aui-list">
      <div class="aui-list-img">
         <img src="picture/index_09.png" class="" alt="">
         <div class="aui-list-mask">
            <p>金卡可订/银卡可订<span class="fr">371间可订</span></p>
         </div>
      </div>
      <a href="#">
         <h5>九思堂特色度假酒店</h5>
         <p>上饶 婺源 <span class="yellow fr">会员免费</span></p>	
      </a>	
      
   </section>
   <section class="aui-list">
      <div class="aui-list-img">
         <img src="picture/index_12.png" class="" alt="">
         <div class="aui-list-mask">
            <p>金卡可订<span class="fr">589间可订</span></p>
         </div>
      </div>
      <a href="#">
         <h5>衍居·院上特色度假酒店</h5>
         <p>承德 围场满族蒙古族自治 <span class="yellow fr">会员免费</span></p>	
      </a>	
      
   </section>
   <section class="aui-list">
      <div class="aui-list-img">
         <img src="picture/index_14.png" class="" alt="">
         <div class="aui-list-mask">
            <p>银卡可订<span class="fr">298间可订</span></p>
         </div>
      </div>
      <a href="#">
         <h5>松阳近水楼台特色度假酒店</h5>
         <p>丽水 松阳 <span class="yellow disabled fr">满房</span></p>	
      </a>	
      
   </section-->

   <!-- 入住离店日期弹窗 begin -->
   <div class="mask_calendar">
      <div class="calendar"></div>
      <div class="calendar_tishi aui-calendar-tips">请选择离店时间</div>
   </div>
   <!-- 入住离店日期弹窗 end -->

<script type="text/javascript" src="js/jquery.js"></script>    
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
            //下面做ajax请求
        },
        comfireBtn: '.comfire'//确定按钮的class或者id
    });
});
function isElementInViewport (el, offset = 0) {
    const box = el.getBoundingClientRect(),
          top = (box.top >= 0),
          left = (box.left >= 0),
          bottom = (box.bottom <= (window.innerHeight || document.documentElement.clientHeight) + offset),
          right = (box.right <= (window.innerWidth || document.documentElement.clientWidth) + offset);
    return (top && left && bottom && right);
}
function checkHotelInited(){
  $(".aui-list-mask .fr:not(.inited)").each(function(){
    let me=$(this);
    if(isElementInViewport(me[0])){
      me.addClass("inited");
      $.get("/?f=searchFun&func=getEnableRoomsNumByHotelid&param="+me.parents("section").attr("hotelid")+","+$('#startDate').val()+","+$('#endDate').val(),function(data,status){
        me.html(data+"间可订");
        var yellowSpan=me.parents("section").find("span.yellow");
        if(data<=0){
          yellowSpan.addClass("disabled");
          yellowSpan.html("满房");
        }else{
          yellowSpan.removeClass("disabled");
          yellowSpan.html("会员免费");
        }
      });
    }
  });
}
$(window).scroll(function() {
  setTimeout("checkHotelInited()",500);
});
function getUrlParam(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
  var r = window.location.search.substr(1).match(reg);
  if (r != null) return unescape(r[2]); return null;
}
checkHotelInited();
$(function(){
  var myweek = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
  var st = new Date($('#startDate').val());
  var en = new Date($('#endDate').val());
  $('.week').text(myweek[st.getDay()]);
  $('.week1').text(myweek[en.getDay()]);
  $('.pos').val(getUrlParam("loc"));
});
$(document).keyup(function(event){
  if(event.keyCode ==13){
    location.href="/"+window.location.search+"&hotelName="+$("#hotelName").val();
  }
});

$(".hotel").click(function(){
    location.href="/?p=hotelInfo&hotelID="+$(this).attr("hotelid");
});
</script>
</body>
</html>