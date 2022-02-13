<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>选择目的地</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<script src="js/rem.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="css/base.css">
<style>
body{ background-color: #fff; height: auto; }
.fixed-top{ padding: 15px 20px; background-color: #595959; }
.fixed-top .ipt{ width: 100%; height: 45px; background: #fff url(images/ico_search.png) no-repeat 20px 15px; background-size: 18px 17px; border-radius: 23px; box-sizing: border-box; padding: 12px 0 12px 48px; font-size: 14px; color:#333; }
.select-tab{ background-color: #F4F4F4; line-height: 44px; text-align: center; color:#595959; }
.select-tab .item{ position: relative; }
.select-tab .item.active{ color:#F8B500; }
.select-tab .item:after{ content: ' '; position: absolute; left:0; bottom: 0; width: 100%; height: 5px; background: #F8B500; display: none; }
.select-tab .item.active:after{ display: inline; }

#btn{padding:14px;font-size: 14px;color: #fff;border-radius: 6px;background-color: #337ab7;border: none;}
#index_list{font-size: 16px;position: relative;height: 100%}
#index_list>section{overflow-y: auto;height: 100%}
#index_list>section>dl>dt{background: #f7f7f9;color: #999;height:24px;line-height:24px;padding-left: 12px;}
#index_list>section>dl>dd{font-size: 16px;color: #333; line-height: 22px; padding: 9px 15px 9px 15px;position: relative; display: flex; flex-flow: row;align-items: center; justify-content:space-between; }
#index_list>section>dl>dd .aside{ text-align: right; }
#index_list>section>dl>dd:after{content:'';position: absolute;left: 0;bottom: 1px;width: 100%;height: 1px;background-color:#c8c7cc;transform:scaleY(.5);-webkit-transform:scaleY(.5);}
#index_list>section>dl>dd:last-of-type:after{display: none;}
#navBar{position: fixed;width:26px; right: 0; top: 55%; z-index: 10; transform: translateY(-50%); }
#navBar.active{background:rgba(211,211,211,.6);}
#navBar>a{text-align: center;display: block;text-decoration:none; line-height: 16px; color: #F8B500;font-size: 12px;}
#prompt{position: fixed;left: 50%;top:50%;margin:-30px 0 0 -30px;width: 60px;height: 60px;text-align: center;line-height: 60px;font-size: 30px;color: #fff;background: rgba(0,0,0,0.5);z-index: 10;display: none;border-radius:4px;}
#prompt.active{display:block;}
.no-data{ text-align: center; padding-top: 45px; }
.cur-city{ padding:18px; }
.cur-city .txts{ margin-left: 12px; }
.index-list-bd{ padding-right: 32px; }
.row-hd{ padding-left: 12px; line-height: 24px; background-color: #F4F4F4; }
.hot-cities{ padding: 10px 0 0 4px; }
.hot-cities .item{ width: 33.33%; float: left; }
.hot-cities .item-txt{ text-align: center; line-height: 32px; border:1px solid #F4F4F4; border-radius: 6px; margin:0 0 10px 10px; }
.history-list>.searchCity{
    font-size: 16px;
    color: #333;
    line-height: 22px;
    padding: 9px 15px 9px 15px;
    position: relative;
    display: flex;
    flex-flow: row;
    align-items: center;
    justify-content: space-between;
}
.history-list>div>.name{    
    font-size: 16px;
    color: #333;
    line-height: 22px;
    padding: 9px 15px 9px 15px;
}
.history-list>div>.aside{
    text-align: right;
    font-size: 16px;
    color: #333;
    line-height: 22px;
    padding: 9px 15px 9px 15px;
}
</style>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=DMABZ-36ZKX-LRX4F-74VNC-PJBBV-FBFUG"></script>
<script type="text/javascript" src="https://apis.map.qq.com/tools/geolocation/min?key=DMABZ-36ZKX-LRX4F-74VNC-PJBBV-FBFUG&referer=myapp"></script>
</head>
<body>
   <div class="fixed-top">
      <input type="text" placeholder="请输入目的地、城市" class="ipt" id="searchCity"/>
   </div>
   <div id="app" v-cloak class="select-bd fz16 gray">
      <div class="select-tab doc-row">
         <div @click="curTab=1;" class="item flex-1" :class="{'active': curTab == 1}">已开放城市</div>
         <div @click="curTab=2;" class="item flex-1" :class="{'active': curTab == 2}">搜索历史</div>
      </div>
      <div v-show="curTab == 1" class="index-list">
         <div class="cur-city doc-row col-center">
            <img width="20" src="images/ico_location.png" alt="">
            <div class="txts">
               <div class="fz16" id="curLoc"></div>
               <div class="gray1 fz12">当前定位城市</div>
            </div>
         </div>
         <div class="index-list-bd">
            <div class="row-hd">热门城市</div>
            <div class="hot-cities cf">
               <?php 
               foreach(explode(',', $GLOBALS["sysConfig"]["hotCity"]) as $value){
                 echo '<div class="item">
                        <div class="item-txt">'.$value.'</div>
                     </div>';
               }
               ?>
            </div>
            <div id="index_list"></div>
            <!-- <button id='btn'>点击显示列表</button> -->
         </div>         
      </div>
      <div v-show="curTab == 2" class="history-list">
         
         <div class="no-data">无搜索历史</div>
      </div>
   </div>

<script src="js/zepto.min.js"></script>
<script src="js/vue.2.5.13.min.js"></script>
<script>
var cities = <?php echo json_encode(Hotel::getCityList());?>
</script>
<script src="js/cities.js"></script>
<script>
function getUrlParam(name) {
   var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
   var r = window.location.search.substr(1).match(reg);
   if (r != null) return unescape(r[2]); return null;
}
function setCookie(cname, cvalue, exdays) {
   var d = new Date();
   d.setTime(d.getTime() + (exdays*24*60*60*1000));
   var expires = "expires="+ d.toUTCString();
   document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
   var name = cname + "=";
   var decodedCookie = decodeURIComponent(document.cookie);
   var ca = decodedCookie.split(';');
   for(var i = 0; i <ca.length; i++) {
     var c = ca[i];
     while (c.charAt(0) == ' ') {
         c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
         return c.substring(name.length, c.length);
      }
   }
   return "";
}
function getCityHotelNum(cityName){
   for (let l in cities) {
       var f = cities[l];
       for (let i in f) {
         if(i==cityName)
            return f[i];
       }
   }
   return 0;
}
(function(){
   new Vue({
      el: '#app',
      data: {
         curTab: 1
      },
      mounted: function() {
         $('#index_list').myList()
      },
      methods: {

      }
   });


   var geolocation = new qq.maps.Geolocation("DMABZ-36ZKX-LRX4F-74VNC-PJBBV-FBFUG","myApp");
   var options = {timeout: 9000};

   function getCurLocation() {
      geolocation.getLocation(showPosition, showErr, options);
   }
   function showPosition(position) {
      $("#curLoc").html(position.city);
   }
   function showErr() {
      //alert("定位失败！");
      setTimeout(function(){getCurLocation();},1000);
   }
   getCurLocation();


   var searchHistroy=getCookie("searchHistroy");
   var searchHistroyList=[];
   if(searchHistroy){
      $(".history-list").empty();
      searchHistroyList=searchHistroy.split(",");
   }

   for(let city in searchHistroyList){
      var cityInfo=searchHistroyList[city];
      $(".history-list").append("<div class='searchCity'><div class='name'>"+cityInfo+"</div><div class='aside'><div class='orange fz16'>"+getCityHotelNum(cityInfo)+"</div><div class='gray'>酒店住宿</div></div></div>");
   }

   $("#curLoc,.item-txt,div.name,.searchCity.name").click(function(){
      var searchHistroy=getCookie("searchHistroy");
      if(searchHistroy.indexOf($(this).html())==-1){
         searchHistroyList.unshift($(this).html());
         setCookie("searchHistroy", searchHistroyList.join(","), 3)
      }
      location.href="/?p="+getUrlParam("f")+"&targetLoc="+escape($(this).html());
   });
})();
$('#searchCity').bind('input propertychange',function(){
   let inputStr=$(this).val();
   $("#index_list div.name").each(function(){
      if(inputStr=="" || $(this).text().indexOf(inputStr)!=-1){
         $(this).parent().show();
      }else {
         $(this).parent().hide();
      }
    });
   $("#index_list").scrollTop(0);
   $("#index_list").scroll();
});
</script>
</body>
</html>