<?php
include './core.php';
@$p=isset($_REQUEST["p"])?$_REQUEST["p"]:null;
@$f=isset($_REQUEST["f"])?$_REQUEST["f"]:null;
if(!is_null($p)){//1f1p.com/?p=order/index
	checkPower($p);
	$p=strpos($p,".php")?$p:$p.".php";
	if(file_exists("control/".$p))include("control/".$p);
	includeFile("view/".$p);
}else if(!is_null($f)){
	checkPower($f);
	$f=strpos($f,".php")?$f:$f.".php";
	includeFile("control/".$f);
	call_user_func_array($_REQUEST["func"], isset($_REQUEST["param"])?explode(',', $_REQUEST["param"]):array());
}else{
	include("view/index.php");
}

function checkPower($url){
	if(strstr($url,"admin/")!=false){
		header('Location: /admin/?p=index');
		exit();
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