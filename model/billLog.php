<?php 
/**
 * 账单类
 */
class BillLog{

	public $id;
	public $billNum;
	public $billType;
	public $billDesc;
	public $channelId;
	public $addTime;
	function __construct($id){
		$this->id=$id;
		$db=sql::getInstance()->newDB();
		$billRow=Sql::getInstance()->sqlQuery("select * from billLog where id=".$id,$db);
		if($billRow==null){
			$db->close();
			return;
		}
		$this->billNum=$billRow["billNum"];
		$this->billType=$billRow["billType"];
		$this->billDesc=$billRow["billDesc"];
		$this->channelId=$billRow["channelId"];
		$this->addTime=$billRow["addTime"];

		$db->close();
	}

	static public function getBillTotalCount($channelId=null, $allTime=false){
		if($_SESSION["role"]==0){//管理员
		}else if($_SESSION["role"]==1){//渠道
			if($channelId && $channelId!=$_SESSION["channelId"])
				return null;
		}else{
			return null;
		}
		if($channelId){//查询单渠道本月已支付
			$sqlStr="select sum(billNum) from billLog where channelId=".$channelId;
			if(!$allTime){
				$thismonth_start = date("Y-m-01")." 00:00:00";
				$thismonth_end = date("Y-m-t")." 23:59:59";
				$sqlStr=$sqlStr." and addTime>'".$thismonth_start."' and addTime<'".$thismonth_end."'";
			}
			
		}else{//查询上月总支付
			$last_mont_first_date = date('Y-m-1',strtotime('last month'))." 00:00:00";
			$last_mont_end_date = date('Y-m-d',strtotime(date('Y-m-1').'-1 day'))." 23:59:59";
			$sqlStr="select sum(billNum) from billLog where addTime>'".$last_mont_first_date."' and addTime<'".$last_mont_end_date."'";
		}
		$result=Sql::getInstance()->sqlQuery($sqlStr);
		if($result && $result[0])
			return $result[0];
		else return 0;
	}

	static public function adminSearchBillList($channelId, $startTime, $endTime, $page, $limit){
		if($_SESSION["role"]==0){//管理员
		}else if($_SESSION["role"]==1){//渠道
			if($channelId && $channelId!=$_SESSION["channelId"])
				return null;
		}else{
			return null;
		}
		$sqlStr="select * from billLog where channelId=".$channelId;
    	if($startTime!=""){
			$sqlStr.=" and addTime>'".$startTime."'";
		}
		if($endTime!=""){
    		$sqlStr.=" and addTime<'".$endTime."'";
    	}
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$rows);
		return $result;
	}

	static public function addBill($channelId,$billType,$billNum,$billDesc){
		//channelId: 5; billType: 0; billNum: 11231; billDesc: 231231
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		Sql::getInstance()->sqlQuery("INSERT INTO billLog (billNum, billType, billDesc, channelId, addTime) VALUES ('".$billNum."', '".$billType."', '".$billDesc."', '".$channelId."', now())",null,3);
		return 1;
	}
}
?>