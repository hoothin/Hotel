<?php 
/**
 * 任务类
 */
class Task{

	public $id;
	public $type;
	public $userid;
	public $description;
	public $state;
	function __construct($id){
		$this->id=$id;
		$db=sql::getInstance()->newDB();
		$taskRow=Sql::getInstance()->sqlQuery("select * from tasks where id=".$id,$db);
		if($taskRow==null){
			$db->close();
			return;
		}
		$this->type=$taskRow["type"];
		$this->userid=$taskRow["userid"];
		$this->description=$taskRow["description"];
		$this->state=$taskRow["state"];

		$db->close();
	}

	static public function adminSearchTaskList($taskName, $addDate, $page, $limit){
		if($_SESSION["role"]==0){//管理员
		}else if($_SESSION["role"]==1){//渠道
		}else if($_SESSION["role"]==2){
		}else if($_SESSION["role"]==3){
		}else if($_SESSION["role"]==4){//客服
		}else{
			return null;
		}
		$sqlAdd="";
		$sqlStr="select * from taskList";
    	if($taskName!=""){
			$sqlAdd.=" taskName like '%".$taskName."%'";
		}
		if($addDate!=""){
			if($sqlAdd!="")$sqlAdd.=" and";
    		$sqlAdd.=" addTime>'".$addDate." 00:00:00' and addTime<'".$addDate." 23:59:59'";
    	}
    	if($sqlAdd!=""){
    		$sqlStr.=(" where".$sqlAdd);
    	}
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		for ($i=0; $i < count($rows); $i++) { 
			$task=$rows[$i];
			$allTask=Sql::getInstance()->sqlQuery("select * from tasks where type=".$task["id"],$db,1);
			$doneTask=Sql::getInstance()->sqlQuery("select * from tasks where state=1 and type=".$task["id"],$db,1);
			$rows[$i]["allTaskNum"]=$allTask;
			$rows[$i]["doneTaskNum"]=$doneTask;
			$rows[$i]["undoneTaskNum"]=$allTask-$doneTask;
		}
		$db->close();
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$rows);
		return $result;
	}

	static public function addTask($taskName,$regTimeStart,$regTimeEnd,$age_min,$age_max,$sex,$order_min,$order_max,$cardType){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		$db=Sql::getInstance()->newDB();

		$taskListId=Sql::getInstance()->sqlQuery("INSERT INTO taskList (taskName, addTime) VALUES ('".$taskName."', now())",$db,3);

		$sqlStr="select * from user where phone is not null";
    	if($regTimeStart!=""){
			$sqlStr.=" regTime>'".$regTimeStart."'";
		}
		if($regTimeEnd!=""){
    		$sqlStr.=" and regTime<'".$regTimeEnd."'";
    	}
    	if($age_min!=""){
			$birthday=date("Y-m-d", strtotime("-".$age_min."years", time()));
    		$sqlStr.=" and birthday<'".$birthday."'";
    	}
    	if($age_max!=""){
			$birthday=date("Y-m-d", strtotime("-".$age_min."years", time()));
    		$sqlStr.=" and birthday>'".$birthday."'";
    	}
    	if($sex!=""){
    		$sqlStr.=" and sex=".$sex;
    	}
    	if($order_min!=""){
    		$sqlStr.=" and orderTimes>".$order_min;
    	}
    	if($order_max!=""){
    		$sqlStr.=" and orderTimes<".$order_max;
    	}
    	if($cardType!=""){
    		$sqlStr.=" and user.id IN(select userid FROM cards where cards.type=".$cardType.")";
    	}
		$users=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		foreach ($users as $userRow) {
			Sql::getInstance()->sqlQuery("INSERT INTO tasks (type, userid, state) VALUES ('".$taskListId."', ".$userRow["id"].", 0)",$db,3);
		}
		$db->close();
		return 1;
	}

	static public function delTask($taskListId){
		if($_SESSION["role"]!=0){//管理员
			return;
		}
		Sql::getInstance()->sqlQuery("delete from taskList where id=".$taskListId, null, 1);
		Sql::getInstance()->sqlQuery("delete from tasks where type=".$taskListId, null, 1);
		return 1;
	}

	static public function getTasks($taskId, $page, $limit){
		$db=Sql::getInstance()->newDB();
		$sqlStr="select tasks.id,user.id as userid,user.nickName,user.phone,user.sex,tasks.description from user,tasks where user.id=tasks.userid and tasks.type=".$taskId;
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$tasks=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$tasks);
		return $result;
	}
}
?>