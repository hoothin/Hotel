<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $GLOBALS["sysConfig"]["sysTitle"];?></title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link type="text/css" rel="stylesheet" href="css/base.css">
    <link type="text/css" rel="stylesheet" href="css/icon.css">
    <link type="text/css" rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/swiper.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/swiper.min.js"></script>
    <script type="text/javascript" src="js/position.js"></script>
    <script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=DMABZ-36ZKX-LRX4F-74VNC-PJBBV-FBFUG"></script>
    <script type="text/javascript" src="https://apis.map.qq.com/tools/geolocation/min?key=DMABZ-36ZKX-LRX4F-74VNC-PJBBV-FBFUG&referer=myapp"></script>
</head>
<body>
    <script type="text/javascript">
        $(window).load(function(){
            $('body').addClass('loaded');
            $('#loader-wrapper .load_title').remove();
            $('#loader-wrapper img').remove();
        });
    </script>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <img src="picture/logog.png" style="
            width: 100px;
            height: 100px;
            z-index: 99999999999999;
            position: relative;
            margin: 50px auto;
        ">
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
        <div class="load_title"></div>
    </div>

    <section class="aui-flexView">
        <section class="aui-scrollView">
            <div class="aui-hotel-content">
                <div class="aui-hotel-content-bg">
                    <div class="m-slider" data-ydui-slider>
                        <div class="slider-wrapper">
                            <?php
                            foreach(explode(',', $GLOBALS["sysConfig"]["bannerPics"]) as $value){
                                $valueArr=explode("#", $value);
                                $picUrl=$valueArr[0];
                                $href="javascript:;";
                                if(count($valueArr)>1)$href=$valueArr[1];
                                echo '<div class="slider-item">
                                        <a href="'.$href.'">
                                            <img src='.$picUrl.'>
                                        </a>
                                    </div>';
                            }
                            ?>
                        </div>
                        <div class="slider-pagination"></div>
                    </div>
                    

                    

                    <div class="aui-tab" data-ydui-tab>

                        <div class="tab-panel">
                            <!-- ???????????? begin -->
                            <div class="tab-panel-item tab-active">
                                <div class="aui-flex aui-flex-mar b-line">
                                    <div id="beijing" class="aui-hotel-city">
                                        <input type="" name="" id="targetLoc" placeholder="?????????" />>
                                    </div>
                                    <div class="aui-flex-box aui-hotel-text fr">
                                        <a href="javascript:getCurLocation()"><img src="picture/dw_03.png" class="dw">????????????</a>
                                    </div>
                                </div>

                                <div id="checkinout" class="b-line">
                                    <div id="firstSelect" style="width:100%;">
                                        <div class="Date_lr" style="float:left;">
                                            <P style="text-align:left;padding-left:.15rem;">??????</p>
                                            <input id="startDate" type="text" value="" style="" readonly>
                                            <span class="week"></span>
                                        </div>
                                        <span class="aui-calendar-days">???<span class="NumDate">1</span>???</span>
                                        <div class="Date_lr" style="float:right;padding-right:.07rem;">
                                            <P style="text-align:right;padding-right: .15rem;">??????</p>
                                            <input id="endDate" type="text" value="" style="" readonly>
                                            <span class="week1"></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="aui-flex aui-flex-mar b-line">
                                    <div class="aui-flex-box">
                                        <input id="hotelName" type="text" class="aui-flex-search" placeholder="????????????">
                                    </div>
                                </div>

                                <div class="aui-flex-button-box">
                                    <button class="aui-flex-button-btn" id="searchBtn">????????????</button>
                                </div>


                            </div>
                            <!-- ???????????? end -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ?????? -->
        <section class="aui-tit center">
            <h5>????????<span>??????</span></h5>
            <p>????????????????????????????????????</p>
        </section>
        <!-- ?????? -->
        <div class="viewCon pc-banner">
          <section class="swiper-container banner">
            <ul class="swiper-wrapper">
                <?php
                foreach (Hotel::getNewHotels() as $hotel) {
                    echo '<li class="swiper-slide hotel" prop="'.$hotel->id.'" style="background: #fff;">
                            <a href="javascript:void(0);">
                                <img src="'.explode(',', $hotel->pics)[0].'" alt="">
                                <h5 style="width:64%;float:left;">'.$hotel->name.'</h5>
                                <p style="width:22%;float:right;padding:.2rem .2rem;"><span class="yellow fr cityName">'.$hotel->pos->city.'</span></p>  
                            </a>
                        </li>';
                }
                ?>
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
        <!-- ?????? -->

        <section class="aui-tit center">
            <h5>????????<span>??????</span></h5>
            <p>?????????????????????????????????????????????????????????????</p>
        </section>
        <!-- ?????? -->

        <?php
        foreach (Hotel::getRecomHotels() as $hotel) {
            echo '<section class="aui-list hotel" prop="'.$hotel->id.'">
                    <div class="aui-list-img">
                        <img src="'.explode(',', $hotel->pics)[0].'" class="" alt="">
                        <div class="aui-list-mask">
                            <p>'.join("/",$hotel->getCardsName("","??????")).'<span class="fr">'.$hotel->getEnableRoomsNum().'?????????</span></p>
                        </div>
                    </div>
                    <a href="javascript:void(0);">
                        <h5>'.$hotel->name.'</h5>
                        <p>'.$hotel->pos->city." ".$hotel->pos->county.' <span class="yellow fr">????????????</span></p>    
                    </a>
                </section>';
        }
        ?>
        <section class="aui-list center">
            <a href="/?p=searchResult" style="color: #f8b500;"><h5>???????????? ???</h5></a>
            <p>??????????????????</p>
        </section>
        <section class="aui-list">
            <div class="h100">
            </div>
        <div class="h100">
        </section>
        </div>
        <footer class="aui-footer">
            <a href="javascript:;" class="aui-tabBar-item">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_17.png" >
                    </span>
                <span class="aui-tabBar-item-text">??????</span>
            </a>
            <a href="/?p=orderList" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_19.png" >
                    </span>
                <span class="aui-tabBar-item-text">????????????</span>
            </a>
            <a href="/?p=aboutUs" class="aui-tabBar-item ">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_21.png" >
                    </span>
                <span class="aui-tabBar-item-text">????????????</span>
            </a>
            <a href="/?p=userHome" class="aui-tabBar-item aui-tabBar-item-active">
                    <span class="aui-tabBar-item-icon">
                        <img src="picture/index_23.png" >
                    </span>
                <span class="aui-tabBar-item-text">????????????</span>
            </a>
        </footer>


    </section>

    <!-- ???????????????????????? begin -->
    <div class="mask_calendar">
        <div class="calendar"></div>
        <div class="calendar_tishi aui-calendar-tips">?????????????????????</div>
    </div>
    <!-- ???????????????????????? end -->



    <script type="text/javascript" src="js/tab.js"></script>
    <script type="text/javascript" src="js/date.js"></script>
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
                index: 2,
                //?????????????????????
                animateFunction: "slideToggle",
                //????????????
                controlDay: true,
                //?????????daysnumber???????????????????????????????????????????????????????????????90???
                daysnumber: <?php echo $GLOBALS["sysConfig"]["maxOrderDay"];?>,
                //??????????????????
                maxDays: <?php echo $GLOBALS["sysConfig"]["seriesOrderDay"];?>,
                //????????????
                comeColor: "#0195ff",
                //????????????
                outColor: "#0195ff",
                //????????????
                comeoutColor: "#8ccfff",
                //??????????????????????????????
                callback: function() {
                    //????????????
                    $('.mask_calendar').fadeOut(200);
                    var startDate = $('#startDate').val();
                    //???????????????
                    var endDate = $('#endDate').val();
                    //???????????????
                    var NumDate = $('.NumDate').text();
                    //????????????
                    console.log(startDate);
                    console.log(endDate);
                    console.log(NumDate);
                    //?????????ajax??????
                },
                comfireBtn: '.comfire'//???????????????class??????id
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

            var myweek = ["??????", "??????", "??????", "??????", "??????", "??????", "??????"];
            var st = new Date($('#startDate').val());
            var en = new Date($('#endDate').val());
            $('.week').text(myweek[st.getDay()])
            $('.week1').text(myweek[en.getDay()])
        });
    </script>
    <script type="text/javascript">
        $(".hotel").click(function(){
            location.href="/?p=hotelInfo&hotelID="+$(this).attr("prop")+"&startDate="+$("#startDate").val()+"&endDate="+$("#endDate").val();
        });

        $('.aui-grids-item').click(function(e){

            $(this).addClass('this-card').siblings().removeClass('this-card');

            $('#type-amount').html($(this).find('.cardAmount').html());

        })


        var geolocation = new qq.maps.Geolocation("DMABZ-36ZKX-LRX4F-74VNC-PJBBV-FBFUG","myApp");
        var options = {timeout: 9000};

        function getCurLocation() {
            geolocation.getLocation(showPosition, showErr, options);
        }
        function showPosition(position) {
            $("#targetLoc").val(position.city);
        }
        function showErr() {
            //alert("???????????????");
            setTimeout(function(){getCurLocation();},1000);
        }

        $("#searchBtn").click(function(e){
            var hotelName = $("#hotelName").val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var targetLoc = $("#targetLoc").val();
            location.href="/?p=searchResult&hotelName="+hotelName+
            "&startDate="+startDate+
            "&endDate="+endDate+
            "&targetLoc="+targetLoc;
        });

        function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
        if(getUrlParam("targetLoc"))
        $('#targetLoc').val(unescape(getUrlParam("targetLoc")));

        $('#targetLoc').click(function(){location.href="/?p=cityList&f=index"});
        // $(document).ready(function(){
        //     $(".cityName").each(function(e){
        //         let cur=$(this);
        //         getCityStr($(this).html(),function(v){
        //             cur.html(v.replace("???",""));
        //         });
        //     });
        // });
    </script>

</body>
</html>