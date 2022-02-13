<?php 
/**
 * 位置信息 省份 城市 区县
 */
class PosInfo{
	static private $posJson;
	public $province;
	public $provinceId;
	public $city;
	public $cityId;
	public $county;
	public $countyId;
	public $cityPY;
	function __construct($provinceId,$cityId,$countyId=null){
		$this->provinceId=$provinceId;
		$this->cityId=$cityId;
		$this->countyId=$countyId;

		if(!isset(self::$posJson)){
			$json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/static/pos.json');
			self::$posJson = json_decode($json_string, true);
		}
		

		$provinceData = self::$posJson[array_search($this->provinceId, array_column(self::$posJson, 'province_id'))];
		$this->province=$provinceData["province"];

		$cityData=$provinceData["city"];
		$cityData = $cityData[array_search($this->cityId, array_column($cityData, 'city_id'))];
		$this->city=$cityData["name"];
		$this->cityPY=$cityData["pinyin"];

		if($countyId!=null && $countyId!=""){
			$countyData=$cityData["county"];
			$this->county=empty($cityList[$this->countyId])?"":$countyData[$this->countyId];
		}
	}

	static public function getCityID($cityName){
		if(!isset(self::$posJson)){
			$json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/static/pos.json');
			self::$posJson = json_decode($json_string, true);
		}
		foreach (self::$posJson as $provinceData) {
			foreach ($provinceData['city'] as $cityData) {
				if($cityData['name']==$cityName){
					return $cityData['city_id'];
				}
			}
		}
		return null;
	}

	static public function getProvinceList(){
		if(!isset(self::$posJson)){
			$json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/static/pos.json');
			self::$posJson = json_decode($json_string, true);
		}
		$provinces=array();
		foreach (self::$posJson as $provinceData) {
			$provinces[$provinceData['province_id']]=$provinceData['province'];
		}
		return $provinces;
	}

	static public function getCityListByProvince($province){
		if(!isset(self::$posJson)){
			$json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/static/pos.json');
			self::$posJson = json_decode($json_string, true);
		}

		$citys=array();
		foreach (self::$posJson as $provinceData) {
			if($provinceData['province_id']==$province){
				foreach ($provinceData['city'] as $cityData) {
					$citys[$cityData["city_id"]]=$cityData["name"];
				}
				break;
			}
		}
		return $citys;
	}

	static public function getCountyListByCity($city){
		if(!isset(self::$posJson)){
			$json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/static/pos.json');
			self::$posJson = json_decode($json_string, true);
		}
		foreach (self::$posJson as $provinceData) {
			foreach ($provinceData['city'] as $cityData) {
				if($cityData['city_id']==$city){
					return $cityData['county'];
				}
			}
		}
	}

	static public function analyzePosStr($posStr){
		$provinceStr=$posStr;
		$cityStr="市辖区";
		$posStrArr=explode("省",$posStr);
		if(count($posStrArr)==2){
			$provinceStr=$posStrArr[0]."省";
			$cityStr=$posStrArr[1];
		}else if(count($posStrArr)==1){
			$posStrArr=explode("自治区",$posStr);
			if(count($posStrArr)==2){
				$provinceStr=$posStrArr[0]."自治区";
				$cityStr=$posStrArr[1];
			}
		}
		if(!isset(self::$posJson)){
			$json_string = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/static/pos.json');
			self::$posJson = json_decode($json_string, true);
		}
		$curCityId;
		$curProvinceId;
		$provinceData=self::$posJson[array_search($provinceStr, array_column(self::$posJson, 'province'))];
		$curProvinceId=$provinceData["province_id"];
		$cityData=$provinceData["city"];
		$cityData = $cityData[array_search($cityStr, array_column($cityData, 'name'))];
		$curCityId=$cityData["city_id"];
		return new self($curProvinceId, $curCityId);
	}
}
function unescape($str) {
    $ret = ''; 
    $len = strlen($str); 
    for ($i = 0; $i < $len; $i ++) {
        if ($str[$i] == '%' && $str[$i + 1] == 'u') {
            $val = hexdec(substr($str, $i + 2, 4)); 
            if ($val < 0x7f) 
                $ret .= chr($val); 
            else  
                if ($val < 0x800) 
                    $ret .= chr(0xc0 | ($val >> 6)) . 
                     chr(0x80 | ($val & 0x3f)); 
                else 
                    $ret .= chr(0xe0 | ($val >> 12)) . 
                     chr(0x80 | (($val >> 6) & 0x3f)) . 
                     chr(0x80 | ($val & 0x3f)); 
            $i += 5; 
        } else  
            if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3)); 
                $i += 2; 
            } else 
                $ret .= $str[$i]; 
    } 
    return $ret; 
}
?>