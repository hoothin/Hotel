<?php
	$dbConfig=array(
		"db_user"=>"hoothin",/*数据库用户名*/
		"db_passwd"=>"123456",/*数据库密码*/
		"db_hostname"=>"localhost",/*数据库地址*/
		"db_name"=>"hoothin"/*数据库名字*/
	);
	$md5_salt="a4d12312khA0sdnZg";/*加盐*/

	$mchid = '';  //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
	$appid = 'wx8123123123123188';  //微信支付申请对应的公众号的APPID
	$appKey = '212312312312312373889964fed2fa68d';   //微信支付申请对应的公众号的APP Key
	$apiKey = '';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
?>