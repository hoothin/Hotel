<?php
if(!isset($_SERVER['HTTP_REFERER'])) {
    exit();
}
$who = $_SERVER['HTTP_REFERER'];

$fromHost = array(
    '1f1p.rankingfriend.com',
    // 'localhost',
    // '127.0.0.1'
);
$urlArr = parse_url($who);
$host = isset($urlArr['host'])?$urlArr['host']:'';
$host = strtolower(str_ireplace('www.','',$host));
if(!in_array($host,$fromHost))exit('非法来源');

function random($length){
    $captchaSource = "0123456789abcdefghijklmnopqrstuvwxyz";
    $captchaResult = ""; // 随机数返回值
    for($i=0;$i<$length;$i++){
        $n = rand(0, 35);
        if($n >= 36){
            $n = 36 + ceil(($n-36)/3) * 3;
            $captchaResult .= substr($captchaSource, $n, 3);
        }else{
            $captchaResult .= substr($captchaSource, $n, 1);
        }
    }
    return $captchaResult;
}

function upload_file(){
    if(empty($_FILES) || empty($_FILES["file"]))return;
    if ($_FILES["file"]["error"] > 0) {
        exit(json_encode(array('code'=>1, 'msg'=>$_FILES["file"]["error"])));
    }
    // 获取文件后缀
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    // 判断文件是否合法
    if(!in_array($extension, array("gif","jpeg","jpg","png"))){
        exit(json_encode(array('code'=>1, 'msg'=>'上传图片不合法')));
    }
    $uploadfilename = random(8).".".$extension;
    move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $uploadfilename);
    $img = '/uploads/'.$uploadfilename;
    exit(json_encode(array('code'=>0, 'msg'=>$img, 'data'=>array("src"=>$img))));
}

function getClientIP() {
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']); 
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */ 
            foreach ($arr AS $ip) { 
                $ip = trim($ip); 
                if ($ip != 'unknown') { 
                    $realip = $ip; 
                    break; 
                } 
            } 
            if(!isset($realip)){ 
                $realip = "0.0.0.0"; 
            } 
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) { 
            $realip = $_SERVER['HTTP_CLIENT_IP']; 
        } else { 
            if (isset($_SERVER['REMOTE_ADDR'])) { 
                $realip = $_SERVER['REMOTE_ADDR']; 
            } else { 
                $realip = '0.0.0.0'; 
            } 
        } 
    } else { 
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR'); 
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP'); 
        } else {
            $realip = getenv('REMOTE_ADDR'); 
        } 
    } 
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip); 
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0'; 
    return $realip; 
}

//获取所在城市 
function getCity() {
    // 获取当前位置所在城市 
    $getIp = getClientIP(); 
    include $_SERVER['DOCUMENT_ROOT'].'/model/ipCity.class.php';
    $city = new ipCity();
    $addr = $city->getCity( $getIp );
    // echo $addr; // 江苏省常州市 天津市市辖区 内蒙古自治区呼和浩特市
    $pos=Pos::analyzePosStr($addr);
    echo json_encode($pos);
}
?>