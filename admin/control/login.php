<?php 
if (isset($_REQUEST['loginout'])) {
	$_SESSION["adminId"]=null;
}
if (isset($_SESSION["adminId"])) {
	header("Location: ?p=index");
	exit();
}else if (isset($_REQUEST['authcode'])) {
	if (strtolower($_REQUEST['authcode'])==$_SESSION['authcode']) {
		$backUser=BackUser::getUserByLogin($_REQUEST['username'], $_REQUEST['password']);
		if($backUser==null){
			echo "账号不存在";
		}else if($backUser->id==-1){
			echo "密码错误";
		}else{
			echo "1";
			$_SESSION["adminId"]=$backUser->id;
			$_SESSION["nickName"]=$backUser->nickName;
			$channelId=BackUser::getChannelId($backUser->id);
			$_SESSION["channelId"]=$channelId;
			$_SESSION["role"]=$backUser->role;
		}
	}else{
		echo '验证码错误';
	}
	exit();
}
?>