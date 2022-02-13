<?php
include '../core.php';
@$p=isset($_REQUEST["p"])?$_REQUEST["p"]:null;
@$f=isset($_REQUEST["f"])?$_REQUEST["f"]:null;
if(!is_null($p)){//1f1p.com/?p=order/index
	checkPower($p);
	$pp=strpos($p,".php")?$p:$p.".php";
	if(file_exists("control/".$pp))include("control/".$pp);
	includeFile($p.".html");
}else if(!is_null($f)){
	checkPower($f);
	$f=strpos($f,".php")?$f:$f.".php";
	includeFile("control/".$f);
	call_user_func_array($_REQUEST["func"], isset($_REQUEST["param"])?explode(',', $_REQUEST["param"]):array());
}else{
	header("Location: ?p=index");
	exit();
}

function checkPower($url){

	// $_SESSION["adminId"]="1";
	return;//这边没法取#后参数，分不了权限
	if($url=="login"){
		return;
	}
	if(!isset($_SESSION["adminId"])){
		header("Location: ?p=login");
		exit();
	}
	if($_SESSION["adminId"]=="1")return;//创始人账户
	$role=Sql::getInstance()->sqlQuery("select role from backUser where id=".$_SESSION["adminId"])[0];
	if($role==0)return;//超级管理员账户

	$start = 0;
	$end = strpos($url,".php");
	if(!$end) $end = strlen($url);
	$page = substr($url , $start, $end - $start);

	$enabledPages = Sql::getInstance()->sqlQuery("select rolePower from backRole where role=".$role)[0];
	if($enabledPages==null || $enabledPages=="")return;//默认全权限
	$enabledPages = explode(",",$enabledPages);
	if(in_array($page,$enabledPages)){
		
	}else{
		exit("no power");
	}
}

function includeFile($file){
	if(file_exists($file)) {
		include($file);
	}else{
		exit("no file");
	}
}
?>