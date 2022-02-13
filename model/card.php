<?php 
/**
 * 会员卡类
 */
class Card{

	public $cardID;   						/*卡ID*/
	public $cardPass;						/*卡密*/
	public $type;									/*卡类型*/
	public $state;								/*卡状态 0 未激活 1 已激活*/
	public $activeTime;						/*激活时间*/
	public $validTime;						/*截止日期*/
	public $usedTimes;						/*已经用过的次数*/
	public $canUseTimes;					/*剩余可用次数*/
	public $userid;
	public $cardType;
	function __construct($id){
		$this->cardID=$id;
		$db=sql::getInstance()->newDB();
		$cardRow=Sql::getInstance()->sqlQuery("select * from cards where cardID=".$id,$db);
		if($cardRow==null){
			$db->close();
			return;
		}
		$this->cardPass=$cardRow["cardPass"];
		$this->type=$cardRow["type"];
		$this->state=$cardRow["state"];
		$this->activeTime=$cardRow["activeTime"];
		$this->userid=$cardRow["userid"];
		$this->cardType=new CardType($this->type);

		$this->usedTimes=Sql::getInstance()->sqlQuery("select * from orders where usedCard=".$id." and state!=2 and state!=5",$db,1);
		$this->canUseTimes=$this->cardType->useTimes-$this->usedTimes;
		$this->validTime=strtotime("+".$this->cardType->validDays."days",strtotime($this->activeTime));

		$db->close();
	}

	public function getUseLog(){
		$sqlStr="select hotel.name,rooms.type,orders.startTime,orders.state from hotel,rooms,orders where hotel.id=orders.orderHotel and rooms.id=orders.orderRoom and orders.usedCard='".$this->cardID."'";
		$logRow=Sql::getInstance()->sqlQuery($sqlStr, null, 2);
		return $logRow;
	}

	static public function getAllType(){
		return Sql::getInstance()->sqlQuery("select * from cardType", null, 2);
	}

	static public function adminSearchType($page, $limit){
		if($_SESSION["role"]!=0){
			return null;
		}
		$sqlStr="select * from cardType";
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$rows);
		return $result;
	}

	static public function addCardType($cardName, $useTimes, $validDays, $orderCD, $cardHead){
		if($_SESSION["role"]==0){//管理员

		}else{
			return null;
		}
		$headRow=Sql::getInstance()->sqlQuery("select * from cardType where cardHead like '".$cardHead."%'");
		if($headRow!=null)return 0;
        Sql::getInstance()->sqlQuery("INSERT INTO cardType (cardName, validDays, orderCD, useTimes, cardHead) VALUES ('".$cardName."', '".$validDays."', '".$orderCD."', '".$useTimes."', '".$cardHead."')",null,3);
		return 1;
	}

	static public function deleteCardType($cardType){
		if($_SESSION["role"]==0){//管理员

		}else{
			return null;
		}
		Sql::getInstance()->sqlQuery("delete from cardType where type=".$cardType, null, 1);
		return 1;
	}

	static public function getCardType($type){
		if($_SESSION["role"]==0){//管理员

		}else{
			return null;
		}
		return Sql::getInstance()->sqlQuery("select * from cardType where type='".$type."'");
	}

	static public function editCardType($cardType, $cardName, $useTimes, $validDays, $orderCD, $cardHead){
		if($_SESSION["role"]==0){//管理员

		}else{
			return null;
		}
		$headRow=Sql::getInstance()->sqlQuery("select * from cardType where cardHead like '".$cardHead."%' and type!='".$cardType."'");
		if($headRow!=null)return 0;
        Sql::getInstance()->sqlQuery("update cardType set cardName='".$cardName."', validDays='".$validDays."', orderCD='".$orderCD."', useTimes='".$useTimes."', cardHead='".$cardHead."' where type='".$cardType."'",null,1);
		return 1;
	}

	static public function addCard($type, $cardList){
		$cardType=new CardType($type);
		foreach ($cardList as $cardArr) {
			if(strpos($cardArr["card"],$cardType->cardHead)===false){
				continue;
			}
			Sql::getInstance()->sqlQuery("REPLACE INTO cards (cardID, cardPass, type, state) VALUES ('".$cardArr["card"]."','".$cardArr["pass"]."','".$cardType->type."',0)",null,3);
			
		}
		return 1;
	}
}

/**
 * 会员卡类型
 */
class CardType{
	public $type;									/*卡类型*/
	public $cardName;								/*卡名称*/
	public $validDays;								/*激活后有效期日数*/
	public $orderCD;								/*预定冷却时间 小时*/
	public $cardHead;
	public $useTimes;   							/*可使用次数*/
	function __construct($type){
		$this->type=$type;
		$typeRow=Sql::getInstance()->sqlQuery("select * from cardType where type=".$type);
		if($typeRow==null){
			return;
		}
		$this->cardName=$typeRow["cardName"];
		$this->validDays=$typeRow["validDays"];
		$this->orderCD=$typeRow["orderCD"];
		$this->useTimes=$typeRow["useTimes"];
		$this->cardHead=$typeRow["cardHead"];
	}
}
?>