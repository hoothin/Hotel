<?php 
function activite($validCode,$realName,$idCard,$cardID,$cardPass,$phone){
	$userRow=User::getUserByPhone($phone);
	if($userRow && $userRow["id"]!=$_SESSION["userid"]){
		echo "手机号已被注册过";
		return;
	}
	if(!isset($_SESSION["userid"])){
		echo "无法访问";
		return;
	}
	if(!isset($_SESSION["captcha"])){
		echo "验证码失效";
		return;
	}
	if($_SESSION["captcha"]!=$validCode){
		echo "验证码错误";
		return;
	}
	$user=new User($_SESSION["userid"]);
	// if($user->idCard=="" || $user->idCard==null){
	$idCardData=json_decode(User::authIdcard($idCard,$realName));
	if($idCardData->resp->code!=0){
		echo "请输入正确的实名信息";
		return;
	}
	// }
	if($user->hasSameTypeCard($cardID)){
		echo "已绑定过该类型卡";
		return;
	}
	$cardRow=Sql::getInstance()->sqlQuery("select * from cards where cardID='".$cardID."' and cardPass='".$cardPass."' and state=0");
	if($cardRow==null){
		echo "此卡片无效";
		return;
	}
	$userRow=Sql::getInstance()->sqlQuery("select * from user where (idCard='".$idCard."' or phone='".$phone."') and id!='".$_SESSION["userid"]."'");
	if($userRow!=null){
		echo "用户信息已存在";
		return;
	}
	Sql::getInstance()->sqlQuery("update cards set state=1,activeTime=now(),userid='".$_SESSION["userid"]."' where cardID='".$cardID."'");
	$cardsStr=Sql::getInstance()->sqlQuery("select cardID from user where id='".$_SESSION["userid"]."'")[0];
	if($cardsStr=="" || $cardsStr==NULL)$cardsStr=$cardID;
	else $cardsStr=$cardsStr.",".$cardID;

	$sex=getSexSign($idCard);
	$birthday=getBirthday($idCard);
	Sql::getInstance()->sqlQuery("update user set birthday='".$birthday."',sex=".$sex.", phone='".$phone."',idCard='".$idCard."',realName='".$realName."',cardID='".$cardsStr."' where id='".$_SESSION["userid"]."'");
	echo 1;
}

function getSexSign($IDCard){
    //1 男 2 女
    $sexInt = (int)substr($IDCard, 16, 1);
    return $sexInt % 2 === 0 ? 2 : 1;
}

function getBirthday($IDCard){
    if(strlen($IDCard)==18){
        $tyear=intval(substr($IDCard,6,4));
        $tmonth=intval(substr($IDCard,10,2));
        $tday=intval(substr($IDCard,12,2));
        if($tyear>date("Y")||$tyear<(date("Y")-100)){
            $flag=0;
        }elseif($tmonth<0||$tmonth>12){
            $flag=0;
        }elseif($tday<0||$tday>31){
            $flag=0;
        }else{
            $tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
            if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
                $flag=0;
            }else{
                $flag=1;
            }
        }
    }
    elseif(strlen($IDCard)==15){
        $tyear=intval("19".substr($IDCard,6,2));
        $tmonth=intval(substr($IDCard,8,2));
        $tday=intval(substr($IDCard,10,2));
        if($tyear>date("Y")||$tyear<(date("Y")-100)){
            $flag=0;
        }elseif($tmonth<0||$tmonth>12){
            $flag=0;
        }elseif($tday<0||$tday>31){
            $flag=0;
        }else{
            $tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
            if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
                $flag=0;
            }else{
                $flag=1;
            }
        }
    }
    return $tdate;
}

function order($roomid,$startDate,$endDate){
	if(!isset($_SESSION["userid"])){
		echo "无法访问";
		return;
	}
	echo Order::newOrder($_SESSION["userid"],$roomid,$startDate,$endDate);
}
function cancelOrder($orderid){
	if(!isset($_SESSION["userid"])){
		echo "无法访问";
		return;
	}
	echo Order::cancelOrder($orderid, $_SESSION["userid"]);
	return;
}
function sendSmsWithCaptcha($type, $phone){
	if(!isset($_SESSION["userid"])){
		echo "无法访问";
		return;
	}
	$code=mt_rand(999, 9999);
	$_SESSION["captcha"]=$code;
	sendSms($phone, $code, $type);
	echo 1;
}
function regPhone($phone, $code){
	if(!isset($_SESSION["userid"])){
		echo "无法访问";
		return;
	}
	if(!isset($_SESSION["captcha"])){
		echo "验证码失效";
		return;
	}
	if($_SESSION["captcha"]!=$code){
		echo "验证码错误";
		return;
	}
	if(User::getUserByPhone($phone)){
		echo "手机号已被注册过";
		return;
	}
	$user=new User($_SESSION["userid"]);
	$user->updatePhone($phone);
	echo 1;
}
?>