<?php 
/**
 * 数据库类
 */
class Sql{
	static private $instance;
	private $dbConfig;
	function __construct($config){
		$this->dbConfig=$config;
	}

	static public function getInstance(){
        if (!self::$instance instanceof self) {
        	global $dbConfig;
            self::$instance = new self($dbConfig);
        }
        return self::$instance;
        
    }

	function db_connect(){
		$db_hostname=$this->dbConfig["db_hostname"];
		$db_user=$this->dbConfig["db_user"];
		$db_passwd=$this->dbConfig["db_passwd"];
		$db_name=$this->dbConfig["db_name"];
		return new mysqli($db_hostname, $db_user, $db_passwd, $db_name);
	}

	function newDB(){
		$mysql=self::db_connect();
		if ($mysql->connect_error){
			$mysql->close();
			exit('数据库连接失败');
		}
		return $mysql;
	}

	/**
	 * 查库
	 * @param  [type]  $sqlq
	 * @param  [type]  $mysql
	 * @param  integer $type 0 获得查询结果 1 获得影响行数 2 获得多个查询结果 3 获得插入自增id
	 * @return [type]  
	 */
	function sqlQuery($sqlq, $mysql=null, $type=0){
		$queryOnce=false;
		if($mysql==null){
			$mysql=self::newDB();
			$queryOnce=true;
		}
		$result = $mysql->query($sqlq);
		if(!$result)return null;
		if($type == 1){
			return isset($result->num_rows)?$result->num_rows:0;
		}else if($type == 2){
			$resultRow = array();
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				array_push($resultRow, $row);
			}
		}else if($type == 3){
			return mysqli_insert_id($mysql);
		}else{
			if(!isset($result->num_rows))return null;
			if ($result->num_rows <= 0){
				return null;
			}
			$resultRow = $result->fetch_array(MYSQLI_BOTH);
		}
		if($queryOnce)$mysql->close();
		return $resultRow;
	}
}
?>