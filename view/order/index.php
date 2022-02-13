<?php 
// $hotel=new Hotel(1);
// echo $hotel->name;
// echo json_encode($hotel->enableCards);
// echo $hotel->description;
echo PosInfo::getCityID("张家界市");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $GLOBALS["sysConfig"]["sysTitle"];?></title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link type="text/css" rel="stylesheet" href="css/base.css">
    <link type="text/css" rel="stylesheet" href="css/icon.css">
    <link type="text/css" rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/swiper.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/loader.css">
</head>
<body>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(window).load(function(){
            $('body').addClass('loaded');
            $('#loader-wrapper .load_title').remove();
        });
    </script>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
        <div class="load_title">Hoothin酒店集团</div>
    </div>

    <section class="aui-flexView">
        <section class="aui-scrollView">
            <div class="aui-hotel-content">
                <div class="aui-hotel-content-bg">
                    <div class="m-slider" data-ydui-slider>
                        <div class="slider-wrapper">
                            <div class="slider-item">
                                <a href="javascript:;">
                                    <img src="picture/index_02.png">
                                </a>
                            </div>
                            <div class="slider-item">
                                <a href="javascript:;">
                                    <img src="picture/index_02.png">
                                </a>
                            </div>
                            <div class="slider-item">
                                <a href="javascript:;">
                                    <img src="picture/index_02.png">
                                </a>
                            </div>

                        </div>
                        <div class="slider-pagination"></div>
                    </div>
                    

                    

                    <div class="aui-tab" data-ydui-tab>

                        <div class="tab-panel">
                            <!-- 酒店预订 begin -->
                            <div class="tab-panel-item tab-active">
                                <div class="aui-flex aui-flex-mar b-line">
                                    <div id="beijing" class="aui-hotel-city">
                                        <input type="" name="" id="" placeholder="目的地" />>
                                    </div>
                                    <div class="aui-flex-box aui-hotel-text fr">
                                        <a href="javascript:void(0);"><img src="picture/dw_03.png" class="dw">我的位置</a>
                                    </div>
                                </div>

                                <div id="checkinout" class="b-line">
                                    <div id="firstSelect" style="width:100%;">
                                        <div class="Date_lr" style="float:left;">
                                            <P style="text-align:left;padding-left:.15rem;">入住</p>
                                            <input id="startDate" type="text" value="" style="" readonly>
                                        </div>
                                        <span class="aui-calendar-days">共<span class="NumDate">1</span>晚</span>
                                        <div class="Date_lr" style="float:right;padding-right:.07rem;">
                                            <P style="text-align:right;padding-right: .15rem;">离店</p>
                                            <input id="endDate" type="text" value="" style="" readonly>
                                        </div>

                                    </div>
                                </div>

                                <div class="aui-flex aui-flex-mar b-line">
                                    <div class="aui-flex-box">
                                        <input type="text" class="aui-flex-search" placeholder="酒店名称">
                                    </div>
                                </div>

                                <div class="aui-flex-button-box">
                                    <button class="aui-flex-button-btn">开始查找</button>
                                </div>


                            </div>
                            <!-- 酒店预订 end -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- 公共 -->
        <section class="aui-tit center">
            <h5>本周·<span>上新</span></h5>
            <p>每一次睡眠都为您精心挑选</p>
        </section>
        <!-- 公共 -->
        <div class="viewCon pc-banner">
          <section class="swiper-container banner">
            <ul class="swiper-wrapper">
                <li class="swiper-slide">
                    <a href="#">
                        <img src="picture/index_05.png" alt="">
                        <h5>特色度假酒店</h5>
                        <p>182间可定<span class="yellow fr">杭州</span></p>  
                    </a>
                </li>
                <li class="swiper-slide">
                    <a href="#">
                        <img src="picture/index_05.png" alt="">
                        <h5>特色度假酒店</h5>
                        <p>182间可定<span class="yellow fr">杭州</span></p>  
                    </a>
                </li>
                <li class="swiper-slide">
                    <a href="#">
                        <img src="picture/index_05.png" alt="">
                        <h5>特色度假酒店</h5>
                        <p>182间可定<span class="yellow fr">杭州</span></p>  
                    </a>
                </li>
            </ul>
            <div class="swiper-pagination"></div>
          </section>
        </div>
        <script>
        $(function() {
            var banner = new Swiper('.swiper-container.banner', {
                loop: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                observeParents: true,
                observer: true,
                observeSlideChildren: true,
                autoplay: {
                    disableOnInteraction: false,
                },
                watchOverflow: true,
                pagination: {
                    el: '.swiper-pagination',
                }
            });
        })
        </script>
        <!-- 公共 -->

        <section class="aui-tit center">
            <h5>本周·<span>上新</span></h5>
            <p>每一次睡眠都为您精心挑选</p>
        </section>
        <!-- 公共 -->
        <section class="aui-list">
            <div class="aui-list-img">
                <img src="picture/index_09.png" class="" alt="">
                <div class="aui-list-mask">
                    <p>金卡可订/银卡可订<span class="fr">371间可订</span></p>
                </div>
            </div>
            <a href="#">
                <h5>特色度假酒店</h5>
                <p>上饶 <span class="yellow fr">会员免费</span></p>    
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
                <h5>特色度假酒店</h5>
                <p>承德 <span class="yellow fr">会员免费</span></p> 
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
                <h5>特色度假酒店</h5>
                <p>丽水 <span class="yellow fr">会员免费</span></p>    
            </a>    
            
        </section>
        <section class="aui-list">
            <div class="h100"></div>
            
        </section>
        <div class="h100"></div>
        <footer class="aui-footer">
            <a href="javascript:;" class="aui-tabBar-item">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_17.png" >
                    </span>
                <span class="aui-tabBar-item-text">首页</span>
            </a>
            <a href="javascript:;" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_19.png" >
                    </span>
                <span class="aui-tabBar-item-text">我的订单</span>
            </a>
            <a href="javascript:;" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_21.png" >
                    </span>
                <span class="aui-tabBar-item-text">关于我们</span>
            </a>
            <a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_23.png" >
                    </span>
                <span class="aui-tabBar-item-text">个人中心</span>
            </a>
        </footer>


    </section>

    <!-- 入住离店日期弹窗 begin -->
    <div class="mask_calendar">
        <div class="calendar"></div>
        <div class="calendar_tishi aui-calendar-tips">请选择离店时间</div>
    </div>
    <!-- 入住离店日期弹窗 end -->



    <script type="text/javascript" src="js/tab.js"></script>
    <script type="text/javascript" src="js/data.js"></script>
    <script type="text/javascript" src="js/layer.js"></script>
    <script src="js/rem.js" type="text/javascript" charset="utf-8"></script>


    <script type="text/javascript">
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
                daysnumber: "60",
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
            var b = new Date();
            var ye = b.getFullYear();
            var mo = b.getMonth() + 1;
            mo = mo < 10 ? "0" + mo : mo;
            var da = b.getDate();
            da = da < 10 ? "0" + da : da;
            $('#startDate').val(ye + '-' + mo + '-' + da);
            b = new Date(b.getTime() + 24 * 3600 * 1000);
            var ye = b.getFullYear();
            var mo = b.getMonth() + 1;
            mo = mo < 10 ? "0" + mo : mo;
            var da = b.getDate();
            da = da < 10 ? "0" + da : da;
            $('#endDate').val(ye + '-' + mo + '-' + da);
        });
    </script>
    <script type="text/javascript">


        $('.aui-grids-item').click(function(e){

            $(this).addClass('this-card').siblings().removeClass('this-card');

            $('#type-amount').html($(this).find('.cardAmount').html());

        })


    </script>

</body>
</html>