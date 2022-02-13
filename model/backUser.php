<?php 
/**
 * 用户类
 */
class BackUser{

	public $id;
	public $role;							/*用户角色*/
	public $targetId;						/*对应ID*/
	public $nickName;						/*昵称*/
	public $account;						/*账户*/
	public $pass;							/*密码*/
	public $province;
	public $city;
	public $county;
	public $contact;
	public $phone;
	function __construct($id, $init=true){
		$this->id=$id;
		if(!$init)return;
		$userRow=Sql::getInstance()->sqlQuery("select * from backUser where id=".$id);
		if($userRow==null){
			return;
		}
		$this->role=$userRow["role"];
		$this->targetId=$userRow["targetId"];
		$this->nickName=$userRow["nickName"];
		$this->account=$userRow["account"];
		$this->pass=$userRow["pass"];
		$this->province=$userRow["province"];
		$this->city=$userRow["city"];
		$this->county=$userRow["county"];
		$this->contact=$userRow["contact"];
		$this->phone=$userRow["phone"];
	}

	static public function getUserByHotel($hotelid){
		$userRow=Sql::getInstance()->sqlQuery("select * from backUser where targetId=".$hotelid." and role=3");
		if($userRow==null){
			return null;
		}
		$user=new BackUser($userRow["id"]);
		return $user;
	}

	/**
	 * 后台用户登录
	 */
	static public function getUserByLogin($account, $pass){
		$userRow=Sql::getInstance()->sqlQuery("select * from backUser where account='".$account."'");
		if($userRow){
			$user=new BackUser($userRow["id"], false);
			if(!password_verify($pass, $userRow["pass"])) {
				$user->id=-1;
			}else{
				$user->role=$userRow["role"];
				$user->targetId=$userRow["targetId"];
				$user->nickName=$userRow["nickName"];
				$user->account=$userRow["account"];
				$user->pass=$userRow["pass"];
			}
			return $user;
		}else{
			return null;//账号不存在
		}
	}

	static public function getAuthority($backId){
		$userRow=Sql::getInstance()->sqlQuery("select backRole.rolePower from backUser,backRole where backUser.id='".$backId."' and backUser.role=backRole.role");
		return $userRow[0];
	}

	static public function getChannelId($backId){
		$userRow=Sql::getInstance()->sqlQuery("select targetId from backUser where id='".$backId."' and role=1");
		if($userRow==null)return null;
		else return $userRow[0];
	}

	static public function delChannel($targetid){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		Sql::getInstance()->sqlQuery("delete from channel where id=".$targetid, null, 1);
        Sql::getInstance()->sqlQuery("delete from backUser where role=1 and targetId=".$targetid, null, 1);
        return 1;
	}

	static public function addChannel($qdname,$contact,$phone,$account,$pass){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		$backUser=Sql::getInstance()->sqlQuery("select * from backUser where account='".$account."'",null,1);
		if($backUser>0)return 0;
		$channelid=Sql::getInstance()->sqlQuery("INSERT INTO channel (contact, phone) VALUES ('".$contact."', '".$phone."')",null,3);
		Sql::getInstance()->sqlQuery("INSERT INTO backUser (role, targetId, nickName, account, pass, contact, phone) VALUES (1, '".$channelid."', '".$qdname."', '".$account."', '".password_hash($pass, PASSWORD_DEFAULT)."', '".$contact."', '".$phone."')",null,3);
		return 1;
	}

	static public function getChannel($channelid){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		$channelRow=Sql::getInstance()->sqlQuery("select backUser.contact,backUser.phone,backUser.nickName,backUser.account from backUser,channel where backUser.targetId=channel.id and channel.id='".$channelid."'");
		return $channelRow;
	}

	static public function editChannel($channelid,$qdname,$contact,$phone,$account,$pass){
		if($_SESSION["role"]!=0){//管理员
			return 0;
		}
		if($pass){
			$backUser=Sql::getInstance()->sqlQuery("select * from backUser,channel where backUser.account='".$account."' and channel.id=backUser.targetId and channel.id!=".$channelid,null,1);
			if($backUser>0)return 0;//要加的账户名已被其他渠道用掉
		}
		Sql::getInstance()->sqlQuery("update channel set contact='".$contact."', phone='".$phone."' where id=".$channelid,null,3);
		Sql::getInstance()->sqlQuery("update backUser set contact='".$contact."', phone='".$phone."', nickName='".$qdname."'".($pass?(", account='".$account."', pass='".password_hash($pass, PASSWORD_DEFAULT)."'"):"")." where role=1 and targetId=".$channelid,null,3);
		return 1;
	}

	static public function adminSearchChannel($channelName, $page, $limit){
		$sqlStr="select backUser.account,backUser.nickName,channel.id,backUser.contact,backUser.phone from backUser,channel where backUser.targetId=channel.id and backUser.role=1";
    	if($channelName!=""){
			$sqlStr.=" and backUser.nickName like '%".$channelName."%'";
		}
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$rows);
		return $result;
	}

	static public function adminSearchBillByChannel($channelName, $page, $limit){
		$sqlStr="select backUser.nickName,channel.id from backUser,channel where backUser.targetId=channel.id and backUser.role=1";
		if($channelName!=""){
			$sqlStr.=" and backUser.nickName like '%".$channelName."%'";
		}
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
		$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$channels=array();
		foreach ($rows as $row) {
			$row["paid"]=BillLog::getBillTotalCount($row["id"]);
			$row["needPay"]=Order::getOrderCostByChannel($row["id"]);
			$row["paymore"]=$row["paid"]-$row["needPay"];
			array_push($channels, $row);
		}
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$channels);
		return $result;
	}

	static public function adminSearchAgent($agentName, $card, $province, $city, $county, $page, $limit){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		$sqlStr="select backUser.city,backUser.province,backUser.county,backUser.contact,backUser.nickName,agent.id,backUser.phone,agent.cardPrice,agent.cardStart,agent.cardEnd from backUser,agent where backUser.id=agent.agentId and backUser.role=2";
    	if($agentName!=""){
			$sqlStr.=" and backUser.nickName like '%".$agentName."%'";
		}
		if($card!=""){
			$sqlStr.=" and agent.cardStart < ".$card." and agent.cardEnd > ".$card;
		}
		if($province!=""){
    		$sqlStr.=" and province='".$province."'";
    	}
    	if($city!=""){
    		$sqlStr.=" and city='".$city."'";
    	}
    	if($county!=""){
    		$sqlStr.=" and county='".$county."'";
    	}
    	$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		for ($i=0; $i < count($rows); $i++) { 
			$pos=new PosInfo($rows[$i]["province"],$rows[$i]["city"],$rows[$i]["county"]);
			$rows[$i]["city"]=$pos->city;
			$activeCard=Sql::getInstance()->sqlQuery("select * from cards where state!=0 and cardID>=".$rows[$i]["cardStart"]." and cardID<=".$rows[$i]["cardEnd"],$db,1);
			$rows[$i]["activeCard"]=$activeCard;
		}
		$db->close();
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$rows);
		return $result;
	}

	static public function addAgent($agentName,$contact,$phone,$account,$pass,$province,$city,$county){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		$backUser=Sql::getInstance()->sqlQuery("select * from backUser where account='".$account."'",null,1);
		if($backUser>0)return 0;
		$agentId=Sql::getInstance()->sqlQuery("INSERT INTO backUser (role, targetId, nickName, account, pass, contact, phone, province, city, county) VALUES (2, 0, '".$agentName."', '".$account."', '".password_hash($pass, PASSWORD_DEFAULT)."', '".$contact."', '".$phone."', ".$province.", ".$city.", ".$county.")",null,3);
		// Sql::getInstance()->sqlQuery("INSERT INTO agent (contact, phone) VALUES ('".$contact."', '".$phone."')",null,3);
		return 1;
	}

	static public function delAgent($agentId){
		Sql::getInstance()->sqlQuery("delete from agent where id=".$agentId, null, 1);
        return 1;
	}

	static public function getAllAgent(){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		$agents=Sql::getInstance()->sqlQuery("select id,nickName from backUser where role=2",null,2);
		return $agents;
	}

	static public function addAgentCard($agentId,$cardStart,$cardEnd,$price){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		$hasStartIn=Sql::getInstance()->sqlQuery("select * from agent where cardStart<=".$cardStart." and cardEnd>=".$cardStart,null,1);
		if($hasStartIn>0)return 0;
		$hasEndIn=Sql::getInstance()->sqlQuery("select * from agent where cardStart<=".$cardEnd." and cardEnd>=".$cardEnd,null,1);
		if($hasEndIn>0)return 0;
		$allOut=Sql::getInstance()->sqlQuery("select * from agent where cardStart>=".$cardStart." and cardEnd<=".$cardEnd,null,1);
		if($allOut>0)return 0;
		Sql::getInstance()->sqlQuery("INSERT INTO agent (agentId, cardStart, cardEnd, cardPrice) VALUES ('".$agentId."', '".$cardStart."', '".$cardEnd."', '".$price."')",null,3);
		return 1;

	}
}


/**
 * 代理
 */
class Agent {
	
	public $id;
	public $cardPrice;						/*代理价格 230*/
	public $agentId;						/*对应backUser的ID*/
	public $cardStart;
	public $cardEnd;
	function __construct($id){
		$this->id=$id;
		$userRow=Sql::getInstance()->sqlQuery("select * from agent where id=".$id);
		if($userRow==null){
			return;
		}
		$this->agentId=$userRow["agentId"];
		$this->cardPrice=$userRow["cardPrice"];
		$this->cardStart=$userRow["cardStart"];
		$this->cardEnd=$userRow["cardEnd"];
	}
}

/**
 * 渠道
 */
class Channel{
	public $id;
	public $payType;								/*支付方式 0 月结 1 预付*/
	public $contact;
	public $phone;
	function __construct($id){
		$this->id=$id;
		$userRow=Sql::getInstance()->sqlQuery("select * from channel where id=".$id);
		if($userRow==null){
			return;
		}
		$this->payType=$userRow["payType"];
		$this->contact=$userRow["contact"];
		$this->phone=$userRow["phone"];
	}
}
?>