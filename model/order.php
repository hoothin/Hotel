<?php 
/**
 * 订单类
 * @author Hoothin
 */
class Order{
	public $id;
	public $userid;									/*会员ID*/
	public $usedCard;								/*使用的卡片*/
	public $orderHotel;								/*预约酒店id*/
	public $orderHotelName;							/*预约酒店名*/
	public $orderHotelPos;
	public $orderRoom;								/*预约房型id*/
	public $orderRoomName;							/*预约房型名*/
	public $startTime; 	        					/*预约入住时间*/
	public $endTime;         						/*预约离店时间*/
	public $orderDays;								/*入住天数*/
	public $state;   								/*状态 0 已预约 1 已确认 2 已取消 3 已入住 4 超时未入住*/
	public $stateStr;
	public static $stateStrArr = array(0 => "等待确认",1 => "已确认",2 => "已取消", 3=>"已入住", 4 => "超时未入住", 5 => "后台取消");
	function __construct($id){
		$this->id=$id;
		$db=sql::getInstance()->newDB();
		$orderRow=Sql::getInstance()->sqlQuery("select * from orders where id=".$id,$db);
		if($orderRow==null){
			$db->close();
			return;
		}
		$this->userid=$orderRow["userid"];
		$this->usedCard=$orderRow["usedCard"];
		$this->orderHotel=$orderRow["orderHotel"];
		$this->orderRoom=$orderRow["orderRoom"];
		$this->startTime=$orderRow["startTime"];
		$this->endTime=$orderRow["endTime"];

		$datetime1 = new DateTime($this->startTime);
		$datetime2 = new DateTime($this->endTime);
		$interval = $datetime1->diff($datetime2);

		$this->orderDays=$interval->format('%a');
		$this->state=$orderRow["state"];
		$this->stateStr=self::$stateStrArr[$this->state];

		$hotelRow=Sql::getInstance()->sqlQuery("select * from hotel where id=".$this->orderHotel,$db);
		$this->orderHotelName=$hotelRow["name"];
		$this->orderHotelPos=$hotelRow["pos"];
		$this->orderRoomName=Sql::getInstance()->sqlQuery("select type from rooms where id=".$this->orderRoom,$db)[0];
		$db->close();
	}

	public function checkOrderState(){
		if($this->state!=0 && $this->state!=1){
			return;
		}
		if(time()>strtotime("+1days",strtotime($this->startTime))){
			if($this->state==1){
				$state=3;
				Sql::getInstance()->sqlQuery("update user set orderTimes=orderTimes+1 where id=".$this->userid, null, 1);
			}else if($this->state==0){
				$state=2;
			}else{
				return;
			}
			Sql::getInstance()->sqlQuery("update orders set state=".$state." where id=".$this->id, null, 1);
		}
	}

	static public function newOrder($userid,$roomid,$startDate,$endDate){
		$user=new User($userid);
		$room=new room($roomid);
		$room->initInfo();

		$canUseCard=$user->canUseCard($room->enableCard);
		if($canUseCard==0){
			return "没有预定该房间权限";
		}
		if($user->isBan==1)return "用户已被封禁";

		if($room->getEnableRoomsNum($startDate,$endDate)<=0){
			return "该时段房间已满";
		}
		$today=strtotime("today");
		$sd=strtotime($startDate);
		$ed=strtotime($endDate);
		$days=round(($ed-$sd)/3600/24);
		//最大连续预约天数seriesOrderDay
		if($days>$GLOBALS["sysConfig"]["seriesOrderDay"]){
			return "超过最大连续预定天数";
		}
		if($ed<=$today || $ed<=$sd || $sd<$today){
			return "日期错误";
		}
		$days=round(($ed-$today)/3600/24);
		//最久可预约多少天后maxOrderDay
		if($days>$GLOBALS["sysConfig"]["maxOrderDay"]){
			return "超过最久预定限制";
		}

		$latestOrder=Sql::getInstance()->sqlQuery("select * from orders where userid=".$userid." and state!=2 and state!=5 order by id desc limit 1");
		//orderCDcd时间未到，不能连续预定
		if($latestOrder!=null){
			if($latestOrder["state"]==0 || $latestOrder["state"]==1){
				return "请先完成上次预定";
			}
			$card=new Card($canUseCard);
			$cdTime=strtotime("+".$card->cardType->orderCD."hours",strtotime($latestOrder["endTime"]));
			if($cdTime>time()){
				return "两次预约间隔时间过短";
			}
		}
		Sql::getInstance()->sqlQuery("INSERT INTO orders (userid, usedCard, orderHotel, orderRoom, startTime, endTime, state) VALUES ('".$userid."', '".$canUseCard."', '".$room->hotel."', '".$room->id."', '".$startDate."', '".$endDate."', 0)",null,3);

		$changeD="d".$days;
		Sql::getInstance()->sqlQuery("update hotel set ".$changeD."=".$changeD."-1 where id=".$room->hotel,null,1);
		return 1;
	}

	/**
	 * 通过用户获取订单
	 */
	static public function getOrderByUserid($userid){
		$orders=array();
		$orderRows=Sql::getInstance()->sqlQuery("select * from orders where userid=".$userid." limit 50", null, 2);
		foreach ($orderRows as $order) {
			array_push($orders, new Order($order["id"]));
		}
		return $orders;
	}

	static public function adminSearchOrder($type, $hotelName, $cardID, $phone, $page, $limit){
		$sqlStr="select orders.id,orders.state,hotel.name,rooms.type,orders.startTime,orders.usedCard,user.realName,user.idCard from rooms,hotel,user,orders where rooms.id=orders.orderRoom and hotel.id=orders.orderHotel and user.id=orders.userid";
		if($type!=""){
			switch ($type) {
				case 'all':
					break;
				case 'nc'://待确认
					$sqlStr.=" and orders.state=0";
					break;
				case 'w'://待入住
					$sqlStr.=" and orders.state=1";
					break;
				case 'c'://已取消
					$sqlStr.=" and (orders.state=2 or orders.state=5)";
					break;
				case 'e'://已完成
					$sqlStr.=" and orders.state=3";
					break;
				case 'nu'://未入住
					$sqlStr.=" and orders.state=4";
					break;
				default:
					return;
			}
    	}
    	if($hotelName!=""){
			$sqlStr.=" and hotel.name like '%".$hotelName."%'";
		}
		if($cardID!=""){
    		$sqlStr.=" and orders.usedCard='".$cardID."'";
    	}
    	if($phone!=""){
    		$sqlStr.=" and user.phone='".$phone."'";
    	}
    	if($_SESSION["channelId"]!=null){
    		$sqlStr.=" and hotel.source='".$_SESSION["channelId"]."'";
		}
		$sqlStr.=" order by orders.id desc";
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$orderList=array();
		foreach ($rows as $order) {
			array_push($orderList, $order);
		}
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$orderList);
		return $result;
	}

	static public function cancelOrder($orderid, $userid){
		$order=new Order($orderid);
		if(time()>strtotime("-22hours", strtotime($order->startTime))){
			return 2;
		}
		Sql::getInstance()->sqlQuery("update orders set state=2 where id=".$orderid." and userid=".$userid, null, 1);

		$today=strtotime("today");
		$ed=strtotime($order->endTime);
		$days=round(($ed-$today)/3600/24);
		$changeD="d".$days;
		Sql::getInstance()->sqlQuery("update hotel set ".$changeD."=".$changeD."+1 where id=".$order->orderHotel,null,1);
    	return 1;
	}

	static public function getOrderCostByChannel($channelId=null, $allTime=false){
		if($channelId){//查询单渠道本月已消费
			$sqlStr="select sum(rooms.channelPrice) from rooms,orders,hotel where orders.orderRoom=rooms.id and orders.orderHotel=hotel.id and hotel.source=".$channelId." and orders.state!=2 and orders.state!=5";
			if(!$allTime){
				$thismonth_start = date("Y-m-01")." 00:00:00";
				$thismonth_end = date("Y-m-t")." 23:59:59";
				$sqlStr=$sqlStr." and orders.startTime>'".$thismonth_start."' and orders.startTime<'".$thismonth_end."'";
			}
		}else{//查询上月总消费
			$last_mont_first_date = date('Y-m-1',strtotime('last month'))." 00:00:00";
			$last_mont_end_date = date('Y-m-d',strtotime(date('Y-m-1').'-1 day'))." 23:59:59";
			$sqlStr="select sum(rooms.channelPrice) from rooms,orders,hotel where orders.orderRoom=rooms.id and orders.orderHotel=hotel.id and orders.startTime>'".$last_mont_first_date."' and orders.startTime<'".$last_mont_end_date."' and orders.state!=2 and orders.state!=5";
		}
		$row=Sql::getInstance()->sqlQuery($sqlStr);
		if($row && $row[0])
			return $row[0];
		else return 0;
	}

	static public function getOrderListByChannel($channelId, $startTime, $endTime, $page, $limit){
		if($_SESSION["role"]==0){//管理员
		}else if($_SESSION["role"]==1){//渠道
			if($channelId && $channelId!=$_SESSION["channelId"])
				return null;
		}else{
			return null;
		}
		$sqlStr="select orders.state,hotel.name,rooms.type,orders.startTime,orders.usedCard,rooms.channelPrice from rooms,hotel,orders where orders.state!=2 and orders.state!=5 and rooms.id=orders.orderRoom and hotel.id=orders.orderHotel and hotel.source=".$channelId;
    	if($startTime!=""){
			$sqlStr.=" and orders.startTime>'".$startTime."'";
		}
		if($endTime!=""){
    		$sqlStr.=" and orders.startTime<'".$endTime."'";
    	}
		$sqlStr.=" order by orders.id desc";
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$rows);
		return $result;
	}

	static public function updateOrder($type, $orderID){
		if($_SESSION["role"]==0){//管理员

		}else if($_SESSION["role"]==1){//渠道
			$source=Sql::getInstance()->sqlQuery("select hotel.source from hotel,orders where hotel.id=orders.orderHotel and orders.id=".$orderID)[0];
			if($source!=$_SESSION["channelId"])return 0;
		}else{
			return 0;
		}
		$order=new Order($orderID);
		if($type==1){
			$user=new User($order->userid);
			sendOrderSuccess($user->phone, $user->nickName, $order->startTime, $order->endTime, $order->orderHotelName, $order->orderRoomName, $order->orderHotelPos);
			if(time()>strtotime($order->startTime." 12:00:00")){
				$type=3;
			}
		}
		if($type==2 || $type==4 || $type==5){
			$today=strtotime("today");
			$ed=strtotime($order->endTime);
			$days=round(($ed-$today)/3600/24);
			$changeD="d".$days;
			if($type==4){
				Sql::getInstance()->sqlQuery("update user set isBan=1,banEndTime=DATE_ADD(CURRENT_TIMESTAMP(),INTERVAL 30 DAY) where id=".$order->userid);
			}
			Sql::getInstance()->sqlQuery("update hotel set ".$changeD."=".$changeD."+1 where id=".$order->orderHotel,null,1);
		}
    	Sql::getInstance()->sqlQuery("update orders set state=".$type." where id=".$orderID, null, 1);
    	return 1;
	}
}
?>