<?php 
/**
 * 酒店类
 */
class Hotel{
	public $id;
	public $name;
	public $description;
	public $pics;
	public $posStr;
	public $gps;
	public $province;
	public $city;
	public $county;
	public $phone;
	public $addTime;
	public $source;
	public $state;
	public $starLv;
	public $recom;

	public $enableCards;
	public $rooms;
	public $pos;

	public $d1;
	public $d2;
	public $d3;
	public $d4;
	public $d5;
	public $d6;
	public $d7;
	function __construct($id){
		$this->id=$id;
		$db=sql::getInstance()->newDB();
		$hotelRow=Sql::getInstance()->sqlQuery("select * from hotel where id=".$id,$db);
		if($hotelRow==null){
			$db->close();
			return;
		}
		$this->name=$hotelRow["name"];
		$this->description=$hotelRow["description"];
		$this->posStr=$hotelRow["pos"];
		$this->gps=$hotelRow["gps"];
		$this->province=$hotelRow["province"];
		$this->city=$hotelRow["city"];
		$this->county=$hotelRow["county"];
		$this->phone=$hotelRow["phone"];
		$this->addTime=$hotelRow["addTime"];
		$this->source=$hotelRow["source"];
		$this->state=$hotelRow["state"];
		$this->pics=$hotelRow["pics"];
		$this->starLv=$hotelRow["starLv"];
		$this->recom=$hotelRow["recom"];
		$this->d1=$hotelRow["d1"];
		$this->d2=$hotelRow["d2"];
		$this->d3=$hotelRow["d3"];
		$this->d4=$hotelRow["d4"];
		$this->d5=$hotelRow["d5"];
		$this->d6=$hotelRow["d6"];
		$this->d7=$hotelRow["d7"];
		$this->pos=new PosInfo($this->province,$this->city,$this->county);

		$this->enableCards=array();
		$this->rooms=array();

		$rows=Sql::getInstance()->sqlQuery("select * from rooms where state=1 and hotel=".$id,$db,2);
		foreach ($rows as $room) {
			$this->addEnableCard(explode(",",$room["enableCard"]));
			$newRoom=new Room($room["id"]);
			$newRoom->configInfo($room["type"], $room["description"], $room["pics"], $room["price"], $room["channelPrice"], $room["num"], $room["hotel"], $room["addtime"], $room["enableCard"], $room["space"], $room["bed"], $room["people"], $room["prop"], $room["cancelTime"]);
			array_push($this->rooms, $newRoom);
		}
		$db->close();
	}

	/**
	 * 获取城市列表
	 * @return 城市列表[1=>100,2=>200]
	 */
	static public function getCityList(){
		$rows=Sql::getInstance()->sqlQuery("select * from hotel where state=1",null,2);
		$cityList=array();
		foreach ($rows as $hotel) {
			$pos=new PosInfo($hotel["province"],$hotel["city"],$hotel["county"]);
			if(empty($cityList[$pos->cityPY])){
				$cityList[$pos->cityPY]=array();
			}
			if(empty($cityList[$pos->cityPY][$pos->city])){
				$cityList[$pos->cityPY][$pos->city]=0;
			}
			$cityList[$pos->cityPY][$pos->city]++;
		}
		ksort($cityList);
		return $cityList;
	}

	static public function getNewHotels($num=0){
		if($num==0){
			$num=$GLOBALS["sysConfig"]["newCityNum"];
		}
		// $rows=Sql::getInstance()->sqlQuery("select * from hotel where state!=0 order by addTime desc limit ".$num,null,2);
		$rows=Sql::getInstance()->sqlQuery("select * from hotel where state!=0 and id in(".$GLOBALS["sysConfig"]["newHotel"].") order by addTime desc limit ".$num,null,2);
		$hotelList=array();
		foreach ($rows as $hotel) {
			array_push($hotelList, new Hotel($hotel["id"]));
		}
		return $hotelList;
	}

	static public function getRecomHotels($num=0){
		if($num==0){
			$num=$GLOBALS["sysConfig"]["recomCityNum"];
		}
		$rows=Sql::getInstance()->sqlQuery("select * from hotel where recom!=0 and state!=0 order by recom desc limit ".$num,null,2);
		$hotelList=array();
		foreach ($rows as $hotel) {
			array_push($hotelList, new Hotel($hotel["id"]));
		}
		return $hotelList;
	}

	/**
	 * 添加酒店
	 */
	static public function addHotel($name, $starLv, $phone, $hotelUser, $hotelPass, $province, $city, $county, $pics, $description, $pos, $lng, $lat, $source, $recom, $state){
		// $backUser=Sql::getInstance()->sqlQuery("select * from backUser where account='".$hotelUser."'",null,1);
		// if($backUser>0)return 0;
		if($_SESSION["role"]==0){//管理员

		}else if($_SESSION["role"]==1){//渠道
			$source=$_SESSION["channelId"];
		}else{
			return null;
		}
		$name=str_replace("'","''",$name);
		$description=str_replace("'","''",$description);
        $hotelid=Sql::getInstance()->sqlQuery("INSERT INTO hotel (name, starLv, description, pics, province, city, county, phone, pos, gps, source, recom, state, addTime) VALUES ('".$name."', '".$starLv."', '".$description."', '".$pics."', '".$province."', '".$city."', '".$county."', '".$phone."', '".$pos."', '".$lat.",".$lng."','".$source."','".$recom."','".$state."', now())",null,3);
		// Sql::getInstance()->sqlQuery("INSERT INTO backUser (role, targetId, nickName, account, pass) VALUES (3, ".$hotelid.", '".$name."', '".$hotelUser."', '".password_hash($hotelPass, PASSWORD_DEFAULT)."')",null,3);
		return 1;
    }

    static public function editHotel($hotelid, $name, $starLv, $phone, $hotelUser, $hotelPass, $province, $city, $county, $pics, $description,  $pos, $lng, $lat, $recom, $state){
    	if($_SESSION["role"]==0){//管理员

		}else if($_SESSION["role"]==1){//渠道
			$source=Sql::getInstance()->sqlQuery("select source from hotel where id=".$hotelid)[0];
			if($source!=$_SESSION["channelId"])return null;
		}else{
			return null;
		}
		$name=str_replace("'","''",$name);
		$description=str_replace("'","''",$description);
    	Sql::getInstance()->sqlQuery("update hotel set name='".$name."', starLv='".$starLv."', description='".$description."', pics='".$pics."', province='".$province."', city='".$city."', county='".$county."', phone='".$phone."', pos='".$pos."', gps='".$lat.",".$lng."',recom='".$recom."',state='".$state."' where id=".$hotelid, null, 1);
    	if($hotelUser && $hotelPass)
		Sql::getInstance()->sqlQuery("update backUser set account='".$hotelUser."', pass='".password_hash($hotelPass, PASSWORD_DEFAULT)."' where targetId=".$hotelid, null, 1);
		return 1;
    }

    static public function changeRoomNum($roomId,$roomNum){
    	$roomNum=str_replace("-","",$roomNum);

    	$db=Sql::getInstance()->newDB();
    	$roomRow=Sql::getInstance()->sqlQuery("select num,hotel from rooms where id=".$roomId, $db);
    	if(!$roomRow)return 0;
    	$oldRoomNum=$roomRow["num"];
    	$hotelid=$roomRow["hotel"];
		$oldRoomNumArr=explode(",", $oldRoomNum);
		$newRoomNumArr=explode(",", $roomNum);
		$changeNumArr=array(0,0,0,0,0,0,0);
		$hasChange=false;
		for ($i=0; $i < count($newRoomNumArr); $i++) { 
			if(isset($oldRoomNumArr[$i])){
				$changeNumArr[$i]=$newRoomNumArr[$i]-$oldRoomNumArr[$i];
				if($changeNumArr[$i]!=0){
					$hasChange=true;
				}
			}
		}
		if($hasChange){
			Sql::getInstance()->sqlQuery("update hotel set d1=d1+(".$changeNumArr[0]."), d2=d2+(".$changeNumArr[1]."), d3=d3+(".$changeNumArr[2]."), d4=d4+(".$changeNumArr[3]."), d5=d5+(".$changeNumArr[4]."), d6=d6+(".$changeNumArr[5]."), d7=d7+(".$changeNumArr[6].") where id=".$hotelid,$db,1);
		}
        Sql::getInstance()->sqlQuery("update rooms set num='".$roomNum."' where id=".$roomId,$db,1);
        $db->close();
        return 1;
    }

    static public function addRoom($hotelid,$roomType,$price,$channelPrice,$pics,$people,$bedType,$space,$prop,$roomNum,$enableCards,$description){
    	if($_SESSION["role"]==0){//管理员

		}else if($_SESSION["role"]==1){//渠道
			$source=Sql::getInstance()->sqlQuery("select source from hotel where id=".$hotelid)[0];
			if($source!=$_SESSION["channelId"])return null;
		}else{
			return null;
		}
		$roomNum=str_replace("-","",$roomNum);
		$bedType=str_replace("'","''",$bedType);
		$space=str_replace("'","''",$space);
		$roomType=str_replace("'","''",$roomType);
		$description=str_replace("'","''",$description);
		$changeNumArr=explode(",", $roomNum);
		Sql::getInstance()->sqlQuery("update hotel set d1=d1+(".$changeNumArr[0]."), d2=d2+(".$changeNumArr[1]."), d3=d3+(".$changeNumArr[2]."), d4=d4+(".$changeNumArr[3]."), d5=d5+(".$changeNumArr[4]."), d6=d6+(".$changeNumArr[5]."), d7=d7+(".$changeNumArr[6].") where id=".$hotelid,null,1);
        $roomid=Sql::getInstance()->sqlQuery("INSERT INTO rooms (hotel, type, price, channelPrice, pics, people, bed, space, prop, num, enableCard, description, addtime, state) VALUES ('".$hotelid."', '".$roomType."', '".$price."', '".$channelPrice."', '".$pics."', '".$people."', '".$bedType."', '".$space."', '".$prop."', '".$roomNum."', '".$enableCards."', '".$description."', now(), 1)",null,3);
		return 1;
    }

    static public function editRoom($roomid,$hotelid,$roomType,$price,$channelPrice,$pics,$people,$bedType,$space,$prop,$description,$roomNum,$enableCards){
    	if($_SESSION["role"]==0){//管理员

		}else if($_SESSION["role"]==1){//渠道
			$source=Sql::getInstance()->sqlQuery("select source from hotel where id=".$hotelid)[0];
			if($source!=$_SESSION["channelId"])return null;
		}else{
			return null;
		}
		$roomNum=str_replace("-","",$roomNum);
		$bedType=str_replace("'","''",$bedType);
		$space=str_replace("'","''",$space);
		$roomType=str_replace("'","''",$roomType);
		$description=str_replace("'","''",$description);
		$oldRoomNum=Sql::getInstance()->sqlQuery("select num from rooms where id=".$roomid)[0];
		$oldRoomNumArr=explode(",", $oldRoomNum);
		$newRoomNumArr=explode(",", $roomNum);
		$changeNumArr=array(0,0,0,0,0,0,0);
		$hasChange=false;
		for ($i=0; $i < count($newRoomNumArr); $i++) { 
			if(isset($oldRoomNumArr[$i])){
				$changeNumArr[$i]=$newRoomNumArr[$i]-$oldRoomNumArr[$i];
				if($changeNumArr[$i]!=0){
					$hasChange=true;
				}
			}
		}
		if($hasChange){
			Sql::getInstance()->sqlQuery("update hotel set d1=d1+(".$changeNumArr[0]."), d2=d2+(".$changeNumArr[1]."), d3=d3+(".$changeNumArr[2]."), d4=d4+(".$changeNumArr[3]."), d5=d5+(".$changeNumArr[4]."), d6=d6+(".$changeNumArr[5]."), d7=d7+(".$changeNumArr[6].") where id=".$hotelid,null,1);
		}
        Sql::getInstance()->sqlQuery("update rooms set hotel='".$hotelid."', type='".$roomType."', price='".$price."', channelPrice='".$channelPrice."', pics='".$pics."', people='".$people."', bed='".$bedType."', space='".$space."', prop='".$prop."', description='".$description."', enableCard='".$enableCards."', num='".str_replace("-","",$roomNum)."' where id=".$roomid,null,1);
		return 1;
    }

    static public function delRoom($id){
    	if($_SESSION["role"]==0){//管理员

		}else if($_SESSION["role"]==1){//渠道
			$source=Sql::getInstance()->sqlQuery("select hotel.source from hotel,rooms where rooms.id=".$id." and hotel.id=rooms.hotel")[0];
			if($source!=$_SESSION["channelId"])return null;
		}else{
			return null;
		}
        Sql::getInstance()->sqlQuery("delete from rooms where id=".$id, null, 1);
        return 1;
    }

    /**
     * 删除酒店
     * @return [type] [description]
     */
    static public function delHotel($id){
    	if($_SESSION["role"]==0){//管理员

		}else if($_SESSION["role"]==1){//渠道
			$source=Sql::getInstance()->sqlQuery("select source from hotel where id=".$id)[0];
			if($source!=$_SESSION["channelId"])return null;
		}else{
			return null;
		}
        Sql::getInstance()->sqlQuery("delete from hotel where id=".$id, null, 1);
        Sql::getInstance()->sqlQuery("delete from rooms where hotel=".$id, null, 1);
        return 1;
    }

    static public function countHotel(){
    	return Sql::getInstance()->sqlQuery("select COUNT(*) from hotel")[0];
    }

    static public function adminSearchHotel($hotelName, $starLv, $province, $city, $county, $page, $limit){
    	if($_SESSION["role"]==0){//管理员
		}else if($_SESSION["role"]==1){//渠道
		}else{
			return null;
		}
    	$sqlStr="select * from hotel";
    	$sqlAdd="";
    	if($hotelName!=""){
			$sqlAdd.=" name like '%".$hotelName."%'";
		}
		if($_SESSION["channelId"]!=null){
			if($sqlAdd!="")$sqlAdd.=" and";
    		$sqlAdd.=" source='".$_SESSION["channelId"]."'";
		}
		if($starLv!=""){
			if($sqlAdd!="")$sqlAdd.=" and";
    		$sqlAdd.=" starLv='".$starLv."'";
    	}
    	if($province!=""){
    		if($sqlAdd!="")$sqlAdd.=" and";
    		$sqlAdd.=" province='".$province."'";
    	}
    	if($city!=""){
    		if($sqlAdd!="")$sqlAdd.=" and";
    		$sqlAdd.=" city='".$city."'";
    	}
    	if($county!=""){
    		if($sqlAdd!="")$sqlAdd.=" and";
    		$sqlAdd.=" county='".$county."'";
    	}
    	if($sqlAdd!=""){
    		$sqlStr.=(" where".$sqlAdd);
    	}
    	$db=sql::getInstance()->newDB();
    	$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$hotelList=array();
		foreach ($rows as $row) {
			$hotel=new Hotel($row["id"]);
			array_push($hotelList, array("id"=>$hotel->id, "name"=>$hotel->name, "city"=>$hotel->pos->city, "starLv"=>$hotel->starLv, "phone"=>$hotel->phone));
		}
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$hotelList);
		return $result;
    }

    static public function adminSearchRoom($hotelName, $channelName, $starLv, $province, $city, $county, $state, $page, $limit){
    	if($_SESSION["role"]==0){//管理员
		}else if($_SESSION["role"]==1){//渠道
		}else{
			return null;
		}
    	$sqlStr="select rooms.num,rooms.id,rooms.price,rooms.addTime,rooms.state,rooms.channelPrice,rooms.type,hotel.name,hotel.starLv,backUser.nickName from rooms,hotel,backUser where rooms.hotel=hotel.id and hotel.source=backUser.targetId and backUser.role=1";
		if($channelName!=""){
    		$sqlStr.=" and backUser.nickName like '%".$channelName."%'";
    	}
    	if($hotelName!=""){
			$sqlStr.=" and hotel.name like '%".$hotelName."%'";
		}
		if($starLv!=""){
    		$sqlStr.=" and hotel.starLv='".$starLv."'";
    	}
    	if($province!=""){
    		$sqlStr.=" and hotel.province='".$province."'";
    	}
    	if($city!=""){
    		$sqlStr.=" and hotel.city='".$city."'";
    	}
    	if($county!=""){
    		$sqlStr.=" and hotel.county='".$county."'";
    	}
    	if($_SESSION["channelId"]!=null){
    		$sqlStr.=" and hotel.source='".$_SESSION["channelId"]."'";
		}
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$roomList=array();
		foreach ($rows as $room) {
			$curRoom=new Room($room["id"]);
			$curRoom->initInfo();
			$room["storage"]=join(",",$curRoom->get7daysRoomsNum());
			array_push($roomList, $room);
		}
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$roomList);
		return $result;
    }

    static public function searchHotel($cityName, $hotelName, $endDate=null){
    	$sqlStr="select id from hotel where state!=0";
    	if($cityName=="全国"){
    		$cityID=null;
    	}else $cityID=PosInfo::getCityID($cityName);
    	if($cityID!=null){
    		$sqlStr.=" and city='".$cityID."'";
    		if($hotelName!=""){
    			$sqlStr.=" and name like '%".$hotelName."%'";
    		}
    	}else if($hotelName!=""){
			$sqlStr.=" and name like '%".$hotelName."%'";
		}

		if($endDate!=null){
			$today=strtotime("today");
			$ed=strtotime($endDate);
			$days=round(($ed-$today)/3600/24);
			$changeD="d".$days;
			if($days>0 && $days<8)
			$sqlStr.=" order by ".$changeD." desc";
		}
		$rows=Sql::getInstance()->sqlQuery($sqlStr,null,2);
		$hotelList=array();
		foreach ($rows as $hotel) {
			array_push($hotelList, new Hotel($hotel["id"]));
		}
		return $hotelList;
    }

    public function getEnableRoomsNum($startDate=null, $endDate=null){
    	$num=0;
    	if($endDate!=null){
			$today=strtotime("today");
			$ed=strtotime($endDate);
			$days=round(($ed-$today)/3600/24);
			if($days>0 && $days<8){
				switch ($days) {
					case 1:
						return $this->d1;
						break;
					case 2:
						return $this->d2;
						break;
					case 3:
						return $this->d3;
						break;
					case 4:
						return $this->d4;
						break;
					case 5:
						return $this->d5;
						break;
					case 6:
						return $this->d6;
						break;
					case 7:
						return $this->d7;
						break;
					default:
						return $this->d1;
						break;
				}
			}
		}
    	foreach ($this->rooms as $room) {
    		$num+=$room->getEnableRoomsNum($startDate, $endDate);
    	}
    	return $num;
    }

    function getCardsName($pre="", $suf=""){
    	$db=sql::getInstance()->newDB();
    	$cardsNum=Sql::getInstance()->sqlQuery("select count(*) from cardType",$db);
    	$cardStrArr=array();
    	if($cardsNum[0]<=1)return $cardStrArr;
    	if(count($this->enableCards)>0)
    	foreach ($this->enableCards as $card) {
    		$row=Sql::getInstance()->sqlQuery("select cardName from cardType where type=".$card,$db);
    		if($row)
			array_push($cardStrArr, $pre.$row["cardName"].$suf);
		}
		$db->close();
		return $cardStrArr;
    }

	function addEnableCard($cards){
		foreach ($cards as $card) {
			if($card && !in_array($card, $this->enableCards)){
				array_push($this->enableCards, $card);
			}
		}
	}

	function addOrder($roomid){

	}

	function changeState($state){

	}

	function cancelOrder($roomid){

	}

	function completeOrder($roomid){

	}
}

/**
 * 房间类
 */
class Room{
	public $id;
	public $type;							/*房型名称*/
	public $description;					/*房间介绍*/
	public $pics;							/*展示图*/
	public $price;							/*房价*/
	public $channelPrice;					/*渠道价*/
	public $num;							/*剩余房数*/
	public $leftNums;
	public $hotel;							/*关联酒店*/
	public $addtime;						/*添加时间*/
	public $enableCard;						/*可以使用的卡 1,2*/
	public $space;							/*面积*/
	public $bed;							/*床型 0,1,2,2*/
	public $people;							/*限住人数*/
	public $prop;							/*房间属性 kt,yg,bycj,zxb,sz,cz*/
	public static $bedName=array(0=>"1米小床",1=>"1.2米小床",2=>'1.5米大床',3=>'1.8米大床',4=>'2米大床',5=>'圆床',6=>'水床');
	public static $propName=array('yc'=>'有窗','cfj'=>'吹风机','drsh'=>'电热水壶','kt'=>'空调','ly'=>'淋浴','sz'=>'书桌','tx'=>'拖鞋','xsyp'=>'洗漱用品','yg'=>'衣柜','yug'=>'浴缸','bz'=>'可加被子');
	public $cancelTime;		
	function __construct($id){
		$this->id=$id;
		
	}

	function getCardsName($pre="", $suf=""){
    	$db=sql::getInstance()->newDB();
    	$cardsNum=Sql::getInstance()->sqlQuery("select count(*) from cardType",$db);
    	$cardStrArr=array();
    	if($cardsNum[0]<=1)return $cardStrArr;
    	if($this->enableCard)
    	foreach (explode(",",$this->enableCard) as $card) {
    		$row=Sql::getInstance()->sqlQuery("select cardName from cardType where type=".$card,$db);
			array_push($cardStrArr, $pre.$row["cardName"].$suf);
		}
		$db->close();
		return $cardStrArr;
    }

    function getRoomProp(){
    	$resultArr=array();
    	if(!$this->prop)return array();
    	$propArr=explode(",", $this->prop);
    	foreach ($propArr as $prop) {
    		if(!$prop)continue;
    		$resultArr[$prop]=self::$propName[$prop];
    	}
    	return $resultArr;
    }

    function getBedName(){
    	$resultArr=array();
    	$bedArr=explode(",", $this->bed);
    	foreach ($bedArr as $bed) {
    		array_push($resultArr, self::$bedName[$bed]);
    	}
    	return join(" ",$resultArr);
    }

	public function initInfo(){
		$row=Sql::getInstance()->sqlQuery("select * from rooms where id=".$this->id);
		$this->configInfo($row["type"], $row["description"], $row["pics"], $row["price"], $row["channelPrice"], $row["num"], $row["hotel"], $row["addtime"], $row["enableCard"], $row["space"], $row["bed"], $row["people"], $row["prop"], $row["cancelTime"]);
	}

	public function configInfo($type, $description, $pics, $price, $channelPrice, $num, $hotel, $addtime, $enableCard, $space, $bed, $people, $prop, $cancelTime){
		$this->type=$type;
		$this->description=$description;
		$this->pics=$pics;
		$this->price=$price;
		$this->channelPrice=$channelPrice;
		$this->num=$num;
		$this->leftNums=explode(",",$num);
		if(count($this->leftNums)==1){
			$this->leftNums=array($this->leftNums[0],$this->leftNums[0],$this->leftNums[0],$this->leftNums[0],$this->leftNums[0],$this->leftNums[0],$this->leftNums[0]);
		}
		$this->hotel=$hotel;
		$this->addtime=$addtime;
		$this->enableCard=$enableCard;
		$this->space=$space;
		$this->bed=$bed;
		$this->people=$people+1;
		$this->prop=$prop;
		$this->cancelTime=$cancelTime;
	}

    public function getEnableRoomsNum($startDate=null, $endDate=null){
    	if($startDate==null){
    		$start=strtotime("today");
    		$startDate=date('Y-m-d',$start);
    	}else{
    		$start=strtotime($startDate);
    		if($start<strtotime("today")){
    			return 0;
    		}
    	}
    	if($endDate==null){
    		$end=strtotime("tomorrow");
    		$endDate=date('Y-m-d',$end);
    	}else{
    		$end=strtotime($endDate);
    	}
    	if($start>=$end){
    		return 0;
    	}
    	$curDay=$start;
    	$enableRoomsNum=0;
    	$i=round(($start-strtotime("today"))/3600/24);
    	$orderedNum=0;
    	while($curDay<$end){
    		$curDayStr=date('Y-m-d',$curDay);
    		$sqlStr="select count(*) from orders where state!=2 and state!=4 and startTime<='".$curDayStr."' and endTime>'".$curDayStr."' and orderRoom=".$this->id;
    		$orderedNum+=Sql::getInstance()->sqlQuery($sqlStr)[0];
    		$enableRoomsNum+=($this->leftNums[$i++]-$orderedNum);
    		$curDay=strtotime("+1day",$curDay);
    	}
    	return $enableRoomsNum;
    }

    public function get7daysRoomsNum(){
    	$start=strtotime("today");
    	$result=array();
    	for ($i=0; $i < 7; $i++) { 
    		$curDayStr=date('Y-m-d',strtotime("+".$i."day",$start));
    		$sqlStr="select count(*) from orders where state!=2 and state!=4 and startTime='".$curDayStr."' and orderRoom=".$this->id;
    		$orderedNum=Sql::getInstance()->sqlQuery($sqlStr)[0];
    		$enableRoomsNum=($this->leftNums[$i]-$orderedNum);
    		array_push($result, $enableRoomsNum);
    	}
    	return $result;
    }
}
?>