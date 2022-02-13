<?php 
$hour=date("H");
if($hour==12){
	include './core.php';
	$orders=Sql::getInstance()->sqlQuery("select * from orders where state<2", null, 2);
	foreach ($orders as $o) {
		$order=new Order($o["id"]);
		$order->checkOrderState();
	}
	echo "done";
	exit();
}else if($hour==23){
	include './core.php';
	$history=$GLOBALS["sysConfig"]["history"];
	$todayStr=date("Y-m-d", time());
	if($history){
		$historyObj=json_decode($history);
		if(end($historyObj)->date==$todayStr){//今天已经入档过了
			echo "already done";
			exit();
		}
	}else{
		$historyObj=array();
	}
	//昨天房间数存档进历史
	if(count($historyObj)>=7){
		array_shift($historyObj);
	}
	$db=sql::getInstance()->newDB();
	$roomRows=Sql::getInstance()->sqlQuery("select * from rooms", $db, 2);
	$roomTodayTotal=0;
	foreach ($roomRows as $room) {
		$roomNums=explode(",", $room["num"]);
		if(count($roomNums)==1){
			$roomNums=array($roomNums[0],$roomNums[0],$roomNums[0],$roomNums[0],$roomNums[0],$roomNums[0],$roomNums[0]);
		}
		//剩余房间数顺延
		$todayNum=array_shift($roomNums);
		array_push($roomNums, 0);
		$roomTodayTotal+=$todayNum;
		Sql::getInstance()->sqlQuery("update rooms set num='".join(",",$roomNums)."' where id=".$room["id"], $db, 1);
	}
    $todayOrder=Sql::getInstance()->sqlQuery("select * from orders where state!=2 and state!=4 and startTime='".$todayStr."'", $db, 1);
    array_push($historyObj, array("date"=>$todayStr, "todayOrder"=>$todayOrder, "roomTodayTotal"=>$roomTodayTotal));
    Sql::getInstance()->sqlQuery("update sysConfig set history='".json_encode($historyObj)."'", $db, 1);
    //酒店房量与满房状态顺延更新

    Sql::getInstance()->sqlQuery("update hotel set d1=d2,d2=d3,d3=d4,d4=d5,d5=d6,d6=d7,d7=0", $db, 1);

    $db->close();
	echo "done";
	exit();
}
echo "not time";
?>