<?php 
if(!isset($_SESSION["adminId"])){
	header('Location: ?p=login');
	exit();
}
if(isset($_REQUEST['init'])) {
	$initData=json_decode('{
	  "clearInfo": {
	    "clearUrl": "api/clear.json"
	  },
	  "homeInfo": {
	    "title": "首页",
	    "icon": "fa fa-home",
	    "href": "page/welcome-1.html"
	  },
	  "logoInfo": {
	    "title": "Hoothin酒店集团",
	    "image": "images/logo.png",
	    "href": ""
	  },
	  "menuInfo": {
	    "currency": {
	      "title": "常规管理",
	      "icon": "fa fa-address-book",
	      "child": [
	        {
	          "title": "系统主页",
	          "href": "page/welcome-1.html",
	          "icon": "fa fa-desktop",
	          "target": "_self"
	        },
	        {
	          "title": "渠道管理",
	          "href": "",
	          "icon": "fa fa-globe",
	          "target": "_self",
	          "child": [
	            {
	              "title": "卡片代理商",
	              "href": "page/dl-table.html",
	              "icon": "fa fa-trophy",
	              "target": "_self"
	            },
	              {
	              "title": "发卡登记",
	              "href": "page/dl-card.html",
	              "icon": "fa fa-pencil-square-o",
	              "target": "_self"
	            },
	            {
	              "title": "酒店渠道商",
	              "href": "page/qd-table.html",
	              "icon": "fa fa-sitemap",
	              "target": "_self"
	            },
	             {
	              "title": "酒店管理",
	              "href": "page/jd-table.html",
	              "icon": "fa fa-institution",
	              "target": "_self"
	            }
	          ]
	        },
	        {
	          "title": "客房管理",
	          "href": "",
	          "icon": "fa fa-bed",
	          "target": "_self",
	          "child": [
	            {
	              "title": "订单列表",
	              "href": "page/ft-table.html",
	              "icon": "fa fa-calendar",
	              "target": "_self"
	            },
	            {
	              "title": "客房列表",
	              "href": "page/kf-table.html",
	              "icon": "fa fa-server",
	              "target": "_self"
	            }
	          ]
	        }, 
	      
	        {
	          "title": "会员管理",
	          "href": "",
	          "icon": "fa fa-user",
	          "target": "_self",
	          "child": [
	            {
	              "title": "会员中心",
	              "href": "page/hy-table.html",
	              "icon": "fa fa-group",
	              "target": "_self"
	            },
	            {
	              "title": "回访中心",
	              "href": "page/hf-table.html",
	              "icon": "fa fa-phone-square",
	              "target": "_self"
	            }
	          ]
	        },
	        {
	          "title": "财务统计",
	          "href": "",
	          "icon": "fa fa-calculator",
	          "target": "_self",
	          "child": [
	            {
	              "title": "账单管理",
	              "href": "page/zd-table.html",
	              "icon": "fa fa-file-text-o",
	              "target": "_self"
	            },
	            {
	              "title": "结算详情",
	              "href": "page/zdxq-table.html",
	              "icon": "fa fa-file-text-o",
	              "target": "_self"
	            }
	          ]
	        },
	        {
	          "title": "系统设置",
	          "href": "",
	          "icon": "fa fa-gears",
	          "target": "",
	          "child": [
	            {
	              "title": "会员卡管理",
	              "href": "page/card.html",
	              "icon": "fa fa-credit-card",
	              "target": "_self"
	            },
	            {
	              "title": "系统管理",
	              "href": "page/gg-table.html",
	              "icon": "fa fa-bookmark",
	              "target": "_self"
	            }
	          ]
	        }
	        
	      ]
	    }
	  }
	}');

	if($_SESSION["adminId"]>1){
		$result=BackUser::getAuthority($_SESSION["adminId"]);
		if($result){
			foreach ($initData->menuInfo->currency->child as $key => $value) {
				if(isset($value->child))
				foreach ($value->child as $key1 => $value1) {
					if(isset($value1->child))
					foreach ($value1->child as $key2 => $value2) {
						$page=str_replace("page/","",$value2->href);
						$page=str_replace(".html","",$page);
						if($value2->href!="" && strpos($result,$page)===false){
							unset($initData->menuInfo->currency->child[$key]->child[$key1]->child[$key2]);
						}else if($value2->href=="" && count($value2->child)==0){
							unset($initData->menuInfo->currency->child[$key]->child[$key1]->child[$key2]);
						}
					}
					$page=str_replace("page/","",$value1->href);
					$page=str_replace(".html","",$page);
					if($value1->href!="" && strpos($result,$page)===false){
						unset($initData->menuInfo->currency->child[$key]->child[$key1]);
					}else if($value1->href=="" && count($value1->child)==0){
						unset($initData->menuInfo->currency->child[$key]->child[$key1]);
					}
				}
				$page=str_replace("page/","",$value->href);
				$page=str_replace(".html","",$page);
				if($value->href!="" && strpos($result,$page)===false){
					unset($initData->menuInfo->currency->child[$key]);
				}else if($value->href=="" && count($value->child)==0){
					unset($initData->menuInfo->currency->child[$key]);
				}
			}
		}
	}
	echo json_encode($initData);
    exit();
}else if (isset($_REQUEST['searchHotel'])) {
	$searchParams=null;
	$hotelName="";
	$starLv="";
	$province="";
	$city="";
	$county="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$hotelName=$param->name;
		$starLv=$param->starLv;
		$province=$param->province;
		$city=$param->city;
		$county=$param->county;
	}
	$hotels=Hotel::adminSearchHotel($hotelName, $starLv, $province, $city, $county, $page, $limit);
	echo json_encode($hotels);
	exit();
}else if(isset($_REQUEST['getProvinceList'])) {
	foreach(PosInfo::getProvinceList() as $key=>$province){
        echo '<option value="'.$key.'">'.$province.'</option>';
    }
    exit();
}else if(isset($_REQUEST['getCityListByProvince'])) {
	foreach(PosInfo::getCityListByProvince($_REQUEST['getCityListByProvince']) as $key=>$city){
        echo '<option value="'.$key.'">'.$city.'</option>';
    }
    exit();
}else if(isset($_REQUEST['getCountyListByCity'])) {
	foreach(PosInfo::getCountyListByCity($_REQUEST['getCountyListByCity']) as $key=>$county){
        echo '<option value="'.$key.'">'.$county.'</option>';
    }
    exit();
}else if (isset($_REQUEST['searchRoom'])) {
	//$hotelName, $channelName, $starLv, $province, $city, $county, $state, $page, $limit
	$searchParams=null;
	$hotelName="";
	$channelName="";
	$starLv="";
	$province="";
	$city="";
	$county="";
	$state="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$hotelName=$param->hotelName;
		$channelName=$param->channelName;
		$starLv=$param->starLv;
		$state=$param->state;
		$province=$param->province;
		$city=$param->city;
		$county=$param->county;
	}
	$rooms=Hotel::adminSearchRoom($hotelName, $channelName, $starLv, $province, $city, $county, $state, $page, $limit);
	echo json_encode($rooms);
	exit();
}else if(isset($_REQUEST['addRoom'])) {
	//{"hotelid":"1232","roomType":"12312","price":"1231","channelPrice":"1312","file":"","people":"","bedType":"","space":"","cfj":"on","drsh":"on","tx":"on","xsyp":"on"}
	$result=Hotel::addRoom($_REQUEST['hotelid'],$_REQUEST['roomType'],$_REQUEST['price'],$_REQUEST['channelPrice'],$_REQUEST['pics'],$_REQUEST['people'],$_REQUEST['bedType'],$_REQUEST['space'],$_REQUEST['prop'],$_REQUEST['roomNum'],$_REQUEST['enableCards'],$_REQUEST['description']);
	echo $result;
    exit();
}else if(isset($_REQUEST["delHotel"])) {
	$result=Hotel::delHotel($_REQUEST['hotelid']);
	echo $result;
    exit();
}else if(isset($_REQUEST['addHotel'])) {
	//$name, $starLv, $phone, $hotelUser, $hotelPass, $province, $city, $county, $pics, $description
	$result=Hotel::addHotel($_REQUEST['name'],$_REQUEST['starLv'],$_REQUEST['phone'],$_REQUEST['hotelUser'],$_REQUEST['hotelPass'],$_REQUEST['province'],$_REQUEST['city'],$_REQUEST['county'],$_REQUEST['pics'],$_REQUEST['description'],$_REQUEST['pos'],$_REQUEST['lng'],$_REQUEST['lat'],$_REQUEST['channel'],$_REQUEST['recom'],$_REQUEST['state']);
	echo $result;
    exit();
}else if(isset($_REQUEST["getHotel"])) {
	$hotel=new Hotel($_REQUEST['id']);
	$backUser=BackUser::getUserByHotel($hotel->id);
	if($backUser==null){
		$account="";
	}else{
		$account=$backUser->account;
	}
	$lnglat=explode(",",$hotel->gps);
	$lng="";
	$lat="";
	if(count($lnglat)>1){
		$lng=$lnglat[1];
		$lat=$lnglat[0];
	}
	echo json_encode(array("name"=>$hotel->name,"description"=>$hotel->description,"starLv"=>$hotel->starLv,"pics"=>$hotel->pics,"province"=>$hotel->province,"city"=>$hotel->city,"county"=>$hotel->county,"phone"=>$hotel->phone,"account"=>$account,"pos"=>$hotel->posStr,"lng"=>$lng,"lat"=>$lat,"channel"=>$hotel->source,"recom"=>$hotel->recom, "state"=>$hotel->state));
    exit();
}else if(isset($_REQUEST['editHotel'])) {
	//$name, $starLv, $phone, $hotelUser, $hotelPass, $province, $city, $county, $pics, $description
	$result=Hotel::editHotel($_REQUEST['hotelid'],$_REQUEST['name'],$_REQUEST['starLv'],$_REQUEST['phone'],$_REQUEST['hotelUser'],$_REQUEST['hotelPass'],$_REQUEST['province'],$_REQUEST['city'],$_REQUEST['county'],$_REQUEST['pics'],$_REQUEST['description'],$_REQUEST['pos'],$_REQUEST['lng'],$_REQUEST['lat'],$_REQUEST['recom'],$_REQUEST['state']);
	echo $result;
    exit();
}else if(isset($_REQUEST["delRoom"])) {
	$result=Hotel::delRoom($_REQUEST['id']);
	echo $result;
    exit();
}else if(isset($_REQUEST['editRoom'])) {
	//$name, $starLv, $phone, $hotelUser, $hotelPass, $province, $city, $county, $pics, $description
	$result=Hotel::editRoom($_REQUEST['roomid'],$_REQUEST['hotelid'],$_REQUEST['roomType'],$_REQUEST['price'],$_REQUEST['channelPrice'],$_REQUEST['pics'],$_REQUEST['people'],$_REQUEST['bedType'],$_REQUEST['space'],$_REQUEST['prop'],$_REQUEST['description'],$_REQUEST['roomNum'],$_REQUEST['enableCards']);
	echo $result;
    exit();
}else if(isset($_REQUEST["getRoom"])) {
	$room=new Room($_REQUEST['id']);
	$room->initInfo();
	//$type, $description, $pics, $price, $channelPrice, $num, $hotel, $addtime, $enableCard, $space, $bed, $people, $prop, $cancelTime
	echo json_encode(array("type"=>$room->type,"description"=>$room->description,"pics"=>$room->pics,"price"=>$room->price,"channelPrice"=>$room->channelPrice,"hotel"=>$room->hotel,"enableCard"=>$room->enableCard,"space"=>$room->space,"bed"=>$room->bed,"people"=>$room->people-1,"prop"=>$room->prop,"roomNum"=>$room->num));
    exit();
}else if(isset($_REQUEST["getCards"])) {
	$cards=Card::getAllType();
	$result=[];
	foreach ($cards as $card) {
		array_push($result,array("type"=>$card["type"], "cardName"=>$card["cardName"]));
	}
	echo json_encode($result);
    exit();
}else if (isset($_REQUEST['searchChannel'])) {
	$searchParams=null;
	$channelName="";
	// $province="";
	// $city="";
	// $county="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$channelName=$param->qdname;
	}
	$channels=BackUser::adminSearchChannel($channelName, $page, $limit);
	// $result=array("code"=>0,"msg"=>"","count"=>Hotel::countHotel(),"data"=>$channels);
	echo json_encode($channels);
	exit();
}else if(isset($_REQUEST["delChannel"])) {
	$result=BackUser::delChannel($_REQUEST['id']);
	echo $result;
    exit();
}else if(isset($_REQUEST['addChannel'])) {
	$result=BackUser::addChannel($_REQUEST['qdname'],$_REQUEST['contact'],$_REQUEST['phone'],$_REQUEST['account'],$_REQUEST['pass']);
	echo $result;
    exit();
}else if(isset($_REQUEST['getChannel'])) {
	$result=BackUser::getChannel($_REQUEST['id']);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['editChannel'])) {
	$result=BackUser::editChannel($_REQUEST['channelid'],$_REQUEST['qdname'],$_REQUEST['contact'],$_REQUEST['phone'],$_REQUEST['account'],$_REQUEST['pass']);
	echo $result;
    exit();
}else if(isset($_REQUEST["getCurChannel"])) {
	if($_SESSION["role"]==0){
		echo "admin";
	}else if($_SESSION["channelId"]==null){
		echo "null";
	}else{
		echo $_SESSION["channelId"];
	}
    exit();
}else if(isset($_REQUEST["searchOrder"])){
	$searchParams=null;
	$hotelName="";
	$cardID="";
	$phone="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$hotelName=$param->hotelName;
		$cardID=$param->cardID;
		$phone=$param->phone;
	}
	$orders=Order::adminSearchOrder($_REQUEST["type"],$hotelName,$cardID,$phone,$page,$limit);
	echo json_encode($orders);
	exit();
}else if(isset($_REQUEST["updateOrder"])){
	$result=Order::updateOrder($_REQUEST["type"],$_REQUEST["id"]);
	echo $result;
	exit();
}else if (isset($_REQUEST['searchMember'])) {
	$searchParams=null;
	$realName="";
	$phone="";
	$cardID="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$realName=$param->realName;
		$phone=$param->phone;
		$cardID=$param->cardID;
	}
	$users=User::adminSearchMember($realName, $phone, $cardID, $page, $limit);
	echo json_encode($users);
	exit();
}else if (isset($_REQUEST['banUser'])){
	User::banUser($_REQUEST['id'], $_REQUEST['type']);
	echo 1;
	exit();
}else if (isset($_REQUEST['forbidden'])){
	User::forbidden($_REQUEST['id'], $_REQUEST['type']);
	echo 1;
	exit();
}else if(isset($_REQUEST['getUserInfo'])){
	$user=User::getUserInfo($_REQUEST['id']);
	echo json_encode($user);
	exit();
}else if(isset($_REQUEST['searchCard'])){
	$searchParams=null;
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	$cards=Card::adminSearchType($page, $limit);
	echo json_encode($cards);
	exit();
}else if(isset($_REQUEST['addCardType'])) {
	$result=Card::addCardType($_REQUEST['cardName'],$_REQUEST['useTimes'],$_REQUEST['validDays'],$_REQUEST['orderCD'],$_REQUEST['cardHead']);
	echo $result;
    exit();
}else if(isset($_REQUEST['deleteCardType'])) {
	$result=Card::deleteCardType($_REQUEST['id']);
	echo $result;
    exit();
}else if(isset($_REQUEST['getCardType'])) {
	$result=Card::getCardType($_REQUEST['type']);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['editCardType'])) {
	$result=Card::editCardType($_REQUEST['cardType'],$_REQUEST['cardName'],$_REQUEST['useTimes'],$_REQUEST['validDays'],$_REQUEST['orderCD'],$_REQUEST['cardHead']);
	echo $result;
    exit();
}else if(isset($_REQUEST['addCard'])) {
	$cardContent=$_REQUEST['cardContent'];
	$cardList=array();
	foreach (explode("\n", $cardContent) as $cardAndPass) {
		$cardArr=explode("#", $cardAndPass);
		if(count($cardArr)<2)exit("2");
		array_push($cardList, array("card"=>trim($cardArr[0]),"pass"=>trim($cardArr[1])));
	}
	$result=Card::addCard($_REQUEST['cardType'],$cardList);
	echo $result;
    exit();
}else if(isset($_REQUEST['getSysInfo'])) {
	$result=array("sysTitle"=>$GLOBALS["sysConfig"]["sysTitle"],"hotCity"=>$GLOBALS["sysConfig"]["hotCity"], "newHotel"=>$GLOBALS["sysConfig"]["newHotel"]);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['updateSysInfo'])) {
	Sql::getInstance()->sqlQuery("update sysConfig set newHotel='".$_REQUEST['newHotel']."', sysTitle='".$_REQUEST['sysTitle']."',hotCity='".$_REQUEST['hotCity']."'");
	echo 1;
    exit();
}else if(isset($_REQUEST['getBanner'])) {
	$result=array("banner"=>$GLOBALS["sysConfig"]["bannerPics"]);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['updateBanner'])) {
	Sql::getInstance()->sqlQuery("update sysConfig set bannerPics='".$_REQUEST['bannerPics']."'");
	echo 1;
    exit();
}else if(isset($_REQUEST['getAboutUs'])) {
	$result=array("aboutUs"=>$GLOBALS["sysConfig"]["aboutUs"]);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['getRule'])) {
	$result=array("rule"=>$GLOBALS["sysConfig"]["rule"]);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['getService'])) {
	$result=array("service"=>$GLOBALS["sysConfig"]["service"]);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['updateAboutUs'])) {
	Sql::getInstance()->sqlQuery("update sysConfig set aboutUs='".$_REQUEST['aboutUs']."'");
	echo 1;
    exit();
}else if(isset($_REQUEST['updateService'])) {
	Sql::getInstance()->sqlQuery("update sysConfig set service='".$_REQUEST['service']."'");
	echo 1;
    exit();
}else if(isset($_REQUEST['updateRule'])) {
	Sql::getInstance()->sqlQuery("update sysConfig set rule='".$_REQUEST['rule']."'");
	echo 1;
    exit();
}else if(isset($_REQUEST['getTotalStatistics'])) {
	$db=sql::getInstance()->newDB();
	$roomRows=Sql::getInstance()->sqlQuery("select * from rooms", $db, 2);
    $todayStr=date('Y-m-d',strtotime("today"));
    $sevenDayStr=date('Y-m-d',strtotime("+7days",strtotime("today")));

	$todayRoomsTotal=0;
	$todayRoomsNew=0;

	$allRoomsTotal=0;
	$allRoomsNew=0;
	foreach ($roomRows as $room) {
		$roomNum=explode(",",$room["num"]);
		if(count($roomNum)<7){
			$roomNum=array($roomNum[0],$roomNum[0],$roomNum[0],$roomNum[0],$roomNum[0],$roomNum[0],$roomNum[0]);
		}
		$todayRoomsTotal+=$roomNum[0];
		$allRoomsTotal+=(intval($roomNum[0])+intval($roomNum[1])+intval($roomNum[2])+intval($roomNum[3])+intval($roomNum[4])+intval($roomNum[5])+intval($roomNum[6]));
		if(date('Y-m-d',strtotime($room["addtime"]))==$todayStr){
			$todayRoomsNew+=$roomNum[0];
			$allRoomsNew+=($roomNum[0]+$roomNum[1]+$roomNum[2]+$roomNum[3]+$roomNum[4]+$roomNum[5]+$roomNum[6]);
		}
	}
	$todayOrder=Sql::getInstance()->sqlQuery("select * from orders where state!=2 and state!=4 and startTime='".$todayStr."'", $db, 1);
	$sevenDayOrder=Sql::getInstance()->sqlQuery("select * from orders where state!=2 and state!=4 and startTime>='".$todayStr."' and startTime<'".$sevenDayStr."'", $db, 1);

	$memberAll=Sql::getInstance()->sqlQuery("select count(*) from user", $db)[0];
	$memberHasCard=Sql::getInstance()->sqlQuery("select count(*) from user where cardID != 0", $db)[0];
	$cardNum=Sql::getInstance()->sqlQuery("select count(*) from cards", $db)[0];
	$usedCardNum=Sql::getInstance()->sqlQuery("select count(*) from cards where userid != 0", $db)[0];
	$totalOrder=Sql::getInstance()->sqlQuery("select count(*) from orders where state!=2 and state!=4", $db)[0];

	$db->close();
	$result=array("todayRoomsTotal"=>$todayRoomsTotal, "todayRoomsNew"=>$todayRoomsNew, "allRoomsTotal"=>$allRoomsTotal, "allRoomsNew"=>$allRoomsNew, "todayOrder"=>$todayOrder, "sevenDayOrder"=>$sevenDayOrder, "memberAll"=>$memberAll, "memberHasCard"=>$memberHasCard, "cardNum"=>$cardNum, "usePercent"=>$totalOrder/($usedCardNum*10), "history"=>$GLOBALS["sysConfig"]["history"]);
	echo json_encode($result);
    exit();
}else if (isset($_REQUEST['searchAgent'])) {
	$searchParams=null;
	$agentName="";
	$card="";
	$province="";
	$city="";
	$county="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$agentName=$param->agentName;
		$card=$param->card;
		$province=$param->province;
		$city=$param->city;
		$county=$param->county;
	}
	$agents=BackUser::adminSearchAgent($agentName, $card, $province, $city, $county, $page, $limit);
	echo json_encode($agents);
	exit();
}else if(isset($_REQUEST['addAgent'])) {
	$result=BackUser::addAgent($_REQUEST['agentName'],$_REQUEST['contact'],$_REQUEST['phone'],$_REQUEST['account'],$_REQUEST['pass'],$_REQUEST['province'],$_REQUEST['city'],$_REQUEST['county']);
	echo $result;
    exit();
}else if(isset($_REQUEST['getAgent'])) {
	$result=BackUser::getAllAgent();
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['addAgentCard'])) {
	$result=BackUser::addAgentCard($_REQUEST['agentId'],$_REQUEST['startCard'],$_REQUEST['endCard'],$_REQUEST['price']);
	echo $result;
    exit();
}else if(isset($_REQUEST['delAgent'])) {
	$result=BackUser::delAgent($_REQUEST['id']);
	echo $result;
    exit();
}else if(isset($_REQUEST['getBackUserInfo'])){
	$backUser=new BackUser($_SESSION["adminId"]);
	$result=array("nickName"=>$backUser->nickName,"phone"=>$backUser->phone);
	echo json_encode($result);
	exit();
}else if(isset($_REQUEST['updateBackUserInfo'])){
	Sql::getInstance()->sqlQuery("update backUser set nickName='".$_REQUEST['nickName']."',phone='".$_REQUEST['phone']."' where id=".$_SESSION["adminId"]);
	echo 1;
	exit();
}else if(isset($_REQUEST['updateBackUserPass'])){
	$backUser=new BackUser($_SESSION["adminId"]);
	if(!password_verify($_REQUEST['oldPass'], $backUser->pass)) {
		exit("0");
	}
	Sql::getInstance()->sqlQuery("update backUser set pass='".password_hash($_REQUEST['newPass'], PASSWORD_DEFAULT)."' where id=".$_SESSION["adminId"]);
	echo 1;
	exit();
}else if(isset($_REQUEST['searchTaskList'])){
	$searchParams=null;
	$taskName="";
	$addDate="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$taskName=$param->taskName;
		$addDate=$param->addDate;
	}
	$taskList=Task::adminSearchTaskList($taskName, $addDate, $page, $limit);
	echo json_encode($taskList);
	exit();
}else if(isset($_REQUEST['addTask'])) {
	$result=Task::addTask($_REQUEST['taskName'],$_REQUEST['regTimeStart'],$_REQUEST['regTimeEnd'],$_REQUEST['age_min'],$_REQUEST['age_max'],$_REQUEST['sex'],$_REQUEST['order_min'],$_REQUEST['order_max'],$_REQUEST['cardType']);
	echo $result;
    exit();
}else if(isset($_REQUEST['delTask'])) {
	$result=Task::delTask($_REQUEST['taskListId']);
	echo $result;
    exit();
}else if(isset($_REQUEST['getTasks'])) {
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	$result=Task::getTasks($_REQUEST['taskId'], $page, $limit);
	echo json_encode($result);
    exit();
}else if(isset($_REQUEST['addTaskDesc'])) {
	Sql::getInstance()->sqlQuery("update tasks set description='".$_REQUEST['text']."',state=1 where id=".$_REQUEST['taskId']);
	echo 1;
    exit();
}else if(isset($_REQUEST['searchBill'])){
	$searchParams=null;
	$channelName="";
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$channelName=$param->channelName;
	}
	$billList=BackUser::adminSearchBillByChannel($channelName, $page, $limit);
	echo json_encode($billList);
	exit();
}else if(isset($_REQUEST['getTotalBill'])){
	$paid=BillLog::getBillTotalCount();
	$needPay=Order::getOrderCostByChannel();
	echo json_encode(array("paid"=>$paid, "needPay"=>$needPay, "paymore"=>$paid-$needPay));
	exit();
}else if(isset($_REQUEST['addBill'])){
	//channelId: 5; billType: 0; billNum: 11231; billDesc: 231231
	BillLog::addBill($_REQUEST['channelId'],$_REQUEST['billType'],$_REQUEST['billNum'],$_REQUEST['billDesc']);
	echo 1;
	exit();
}else if(isset($_REQUEST['searchChannelBill'])){
	$searchParams=null;
	$startTime="";
	$endTime="";
	$channelId=$_REQUEST['channelId'];
	if($channelId==0)$channelId=$_SESSION["channelId"];
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$startTime=$param->startTime;
		$endTime=$param->endTime;
	}
	$billList=BillLog::adminSearchBillList($channelId, $startTime, $endTime, $page, $limit);
	echo json_encode($billList);
	exit();
}else if(isset($_REQUEST['searchChannelOrder'])){
	$searchParams=null;
	$startTime="";
	$endTime="";
	$channelId=$_REQUEST['channelId'];
	if($channelId==0)$channelId=$_SESSION["channelId"];
	@$page=$_REQUEST['page'];
	@$limit=$_REQUEST['limit'];
	if(isset($_REQUEST['searchParams'])){
		$searchParams=$_REQUEST['searchParams'];
		$param=json_decode(urldecode($searchParams));
		$startTime=$param->startTime;
		$endTime=$param->endTime;
	}
	$billList=Order::getOrderListByChannel($channelId, $startTime, $endTime, $page, $limit);
	echo json_encode($billList);
	exit();
}else if(isset($_REQUEST['getChannelBillInfo'])){
	$channelId=$_REQUEST['channelId'];
	if($channelId==0)$channelId=$_SESSION["channelId"];
	$paid=BillLog::getBillTotalCount($channelId, true);//支付总额
	$needPay=Order::getOrderCostByChannel($channelId, true);
	echo json_encode(array("paid"=>$paid, "needPay"=>$needPay, "paymore"=>$paid-$needPay));
	exit();
}else if(isset($_REQUEST['changeRoomNum'])){
	$roomNum=$_REQUEST['roomNum'];
	$roomId=$_REQUEST['roomId'];
	echo Hotel::changeRoomNum($roomId,$roomNum);
	exit();
}
?>