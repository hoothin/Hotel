<?php 
function test($value=''){
	echo "test Function<br>";
	echo '数据库查询测试：'.Sql::getInstance()->sqlQuery("select nickName from user where id=1;")[0];
}
?>