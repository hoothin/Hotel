<?php 
/**
 * 用户类
 */
class User{

	public $id;
	public $wxid;							/*微信ID*/
	public $nickName;						/*昵称*/
	public $birthday;									/*年龄*/
	public $sex;									/*性别*/
	public $phone;							/*手机号*/
	public $regTime;							/*注册日期*/
	public $realName;						/*真实姓名*/
	public $idCard;							/*身份证*/
	public $lastLoginTime;						/*上次访问时间*/
	public $cardID;							/*卡ID*/
	public $cards;
	public $state;									/*状态 0 未验证 1 已验证 2 禁止登录*/
	public $isBan;									/*封禁状态 0 未封禁 1 已封禁*/
	public $banEndTime;						/*封禁结束时间*/
	public $wxInfo;							/*微信信息*/
	public $avatar;
	function __construct($id, $init=true){
		$this->id=$id;
		if(!$init)return;
		$db=sql::getInstance()->newDB();
		$userRow=Sql::getInstance()->sqlQuery("select * from user where id=".$id,$db);
		if($userRow==null){
			$db->close();
			return;
		}
		$this->wxid=$userRow["wxid"];
		$this->nickName=$userRow["nickName"];
		$this->birthday=$userRow["birthday"];
		$this->sex=$userRow["sex"];
		$this->phone=$userRow["phone"];
		$this->regTime=$userRow["regTime"];
		$this->realName=$userRow["realName"];
		$this->idCard=$userRow["idCard"];
		$this->lastLoginTime=$userRow["lastLoginTime"];
		$this->cardID=$userRow["cardID"];
		$this->state=$userRow["state"];
		$this->isBan=$userRow["isBan"];
		$this->banEndTime=$userRow["banEndTime"];
		$this->avatar=$userRow["avatar"];

		$db->close();

		$this->checkUser();
	}

	static public function getUserByPhone($phone){
		return Sql::getInstance()->sqlQuery("select * from user where phone=".$phone);
	}

	public function updatePhone($phone){
		Sql::getInstance()->sqlQuery("update user set phone='".$phone."' where id=".$this->id);
	}

	static public function authIdcard($idCard, $realName){
	    $host = "http://idcard.market.alicloudapi.com";
	    $path = "/lianzhuo/idcard";
	    $method = "GET";
	    $appcode = "31f7c164629a4eeda0d37d3a883a40ae";
	    $headers = array();
	    array_push($headers, "Authorization:APPCODE " . $appcode);
	    $querys = "cardno=".$idCard."&name=".$realName;
	    $bodys = "";
	    $url = $host . $path . "?" . $querys;

	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_FAILONERROR, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    if (1 == strpos("$".$host, "https://")){
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    }
	    $response = curl_exec($curl); // 已经获取到内容，没有输出到页面上。
		curl_close($curl);
		return $response;
	}


	public function hasSameTypeCard($card){
		$type=Sql::getInstance()->sqlQuery("select type from cards where cardID='".$card."'")["type"];
		foreach ($this->cards as $card) {
			if($type==$card->type){
				return true;
			}
		}
		return false;
	}

	public function canUseCard($cardids){
		// if($this->isBan==1)return 0;
		if($cardids=="" && $this->cardID)return $this->cards[0]->cardID;
		foreach (explode(",",$cardids) as $cardid) {
    		foreach ($this->cards as $card) {
    			if($cardid==$card->type){
    				return $card->cardID;
    			}
    		}
		}
		return 0;
	}

	public function checkUser(){
		if($this->isBan==1 && time()-strtotime($this->banEndTime)>0){
			Sql::getInstance()->sqlQuery("update user set isBan=0 where id=".$this->id);
		}
		$this->cards=array();
		if($this->cardID){
			$fineCards=explode(',', $this->cardID);
			foreach ($fineCards as $card) {
				$cardRow=Sql::getInstance()->sqlQuery("SELECT cardType.validDays,cards.activeTime FROM cardType,cards WHERE cardType.type=cards.type and cards.cardID=".$card);
				$newCard=new Card($card);
				if(time()-strtotime("+".$cardRow["validDays"]."days",strtotime($cardRow["activeTime"]))>0 || $newCard->canUseTimes<=0){
					$fineCards = array_diff($fineCards, [$card]);
					Sql::getInstance()->sqlQuery("update cards set state=2 where cardID=".$card);
				}else{
					array_push($this->cards, $newCard);
				}
			}
			$this->cardID=join(",",$fineCards);
			Sql::getInstance()->sqlQuery("update user set cardID=".$this->cardID." where id=".$this->id);
		}
	}

	static public function getUserInfo($userid){
		$user=new User($userid);
		// $sex = substr($card, (strlen($card)==15 ? -2 : -1), 1) % 2 ? '1' : '0';
		// echo $sex//1为男 0为女
		$birth = strlen($user->idCard)==15 ? ('19' . substr($user->idCard, 6, 6)) : substr($user->idCard, 6, 8);
		$birth = date('Y-m-d',strtotime($birth));
		$cardInfoArr=array();
		foreach ($user->cards as $card) {
			array_push($cardInfoArr, array("type"=>$card->cardType->cardName,"activeTime"=>$card->activeTime,"cardID"=>$card->cardID,"state"=>($user->isBan==1?"已封禁至（".$user->banEndTime."）":"未封禁"),"cardValid"=>$card->validTime,"leftTimes"=>$card->canUseTimes,"usedLog"=>$card->getUseLog()));
		}
		$info=array("avatar"=>$user->avatar,"realName"=>$user->realName,"nickName"=>$user->nickName,"sex"=>($user->sex==2?"女":"男"),"birthday"=>$user->birthday,"birth"=>$birth,"regTime"=>$user->regTime,"phone"=>$user->phone,"idCard"=>$user->idCard,"cardInfo"=>$cardInfoArr);
		return $info;
	}

	static public function banUser($userid, $isBan){
		if($isBan==1){//封禁
			Sql::getInstance()->sqlQuery("update user set isBan=1,banEndTime=DATE_ADD(CURRENT_TIMESTAMP(),INTERVAL 30 DAY) where id=".$userid);
		}else{
			Sql::getInstance()->sqlQuery("update user set isBan=0 where id=".$userid);
		}
	}

	static public function forbidden($userid, $forbidden){
		if($forbidden==1){//黑名单
			Sql::getInstance()->sqlQuery("update user set state=2 where id=".$userid);
		}else{
			Sql::getInstance()->sqlQuery("update user set state=0 where id=".$userid);
		}
	}

	static public function adminSearchMember($realName, $phone, $cardID, $page, $limit){
		if($_SESSION["role"]==0){//管理员
		}else if($_SESSION["role"]==1){//渠道
		}else if($_SESSION["role"]==2){
		}else if($_SESSION["role"]==3){
		}else if($_SESSION["role"]==4){
		}else{
			return null;
		}
		$sqlStr="select user.state, user.id,cards.cardID,user.realName,user.sex,user.phone,cards.activeTime,user.isBan from cards,user where cards.userid=user.id and cards.state=1";
    	if($realName!=""){
			$sqlStr.=" and user.realName like '%".$realName."%'";
		}
		if($phone!=""){
    		$sqlStr.=" and user.phone='".$phone."'";
    	}
    	if($cardID!=""){
    		$sqlStr.=" and cards.cardID='".$cardID."'";
    	}
		$db=sql::getInstance()->newDB();
		$count=Sql::getInstance()->sqlQuery($sqlStr,$db,1);
    	$sqlStr.=(" limit ".($page-1)*$limit.",".$limit);
		$rows=Sql::getInstance()->sqlQuery($sqlStr,$db,2);
		$db->close();
		$memberList=array();
		foreach ($rows as $member) {
			$card=new Card($member["cardID"]);
			array_push($memberList, array("userid"=>$member["id"],"cardID"=>$member["cardID"], "realName"=>$member["realName"], "sex"=>($member["sex"]==2?"女":"男"), "phone"=>$member["phone"], "activeTime"=>$member["activeTime"], "usedTimes"=>$card->usedTimes, "isBan"=>$member["isBan"], "state"=>$member["state"]));
		}
		$result=array("code"=>0,"msg"=>"","count"=>$count,"data"=>$memberList);
		return $result;
	}

	/**
	 * 通过openid获取用户
	 */
	static public function getUserByOpenid(){
		$wxPay = WxpayService::initService();
		$wxInfo=$wxPay->getUserinfo();
		if(!$wxInfo || !$wxInfo["openid"])return null;
		$userRow=Sql::getInstance()->sqlQuery("select * from user where wxid='".$wxInfo["openid"]."'");
		if($userRow){//有用户，填充信息
			$user=new User($userRow["id"], false);
			$user->wxid=$userRow["wxid"];
			$user->nickName=$userRow["nickName"];
			$user->birthday=$userRow["birthday"];
			$user->sex=$userRow["sex"];
			$user->phone=$userRow["phone"];
			$user->regTime=$userRow["regTime"];
			$user->realName=$userRow["realName"];
			$user->idCard=$userRow["idCard"];
			$user->lastLoginTime=$userRow["lastLoginTime"];
			$user->cardID=$userRow["cardID"];
			$user->state=$userRow["state"];
			if($user->state==2)return null;
			$user->isBan=$userRow["isBan"];
			$user->banEndTime=$userRow["banEndTime"];
			$user->wxInfo=$wxInfo;
			Sql::getInstance()->sqlQuery("update user set lastLoginTime=now(), avatar='".$wxInfo["headimgurl"]."' where id=".$userRow["id"]);
			$user->checkUser();
			$_SESSION['userid'] = $userRow["id"];
		}else{//没用户，注册
			//array(9) { ["openid"]=> string(28) "o1huHwAHhjfy_AbWcHl7-JXVeykM" ["nickname"]=> string(9) "王某某" ["sex"]=> int(1) ["language"]=> string(5) "zh_CN" ["city"]=> string(0) "" ["province"]=> string(0) "" ["country"]=> string(9) "百慕大" ["headimgurl"]=> string(128) "http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLAgrDFR0aHyUxianqxkwFJ6InEndBMc6Umv2ADHxetckBOKzyIJdnZfQCCzmdaCHESJH5juIySq0A/132" ["privilege"]=> array(0) { } }
			$userid=Sql::getInstance()->sqlQuery("INSERT INTO user (wxid, nickName, regTime, lastLoginTime, state, isBan, avatar) VALUES ('".$wxInfo["openid"]."', '".$wxInfo["nickname"]."', now(), now(), 0, 0, '".$wxInfo["headimgurl"]."');", null, 3);
			$user=new User($userid);
			$user->wxInfo=$wxInfo;
			$_SESSION['userid'] = $userid;
		}
		return $user;
	}
}
?>