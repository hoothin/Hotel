<?php 
	error_reporting(E_ALL); 
	ini_set('display_errors', '1'); 
	ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
	header('Content-type:text/html; Charset=utf-8');
	if (!session_id()) session_start();
	
	include $_SERVER['DOCUMENT_ROOT'].'/config.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/sql.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/wxService.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/pos.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/hotel.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/user.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/card.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/order.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/backUser.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/sms.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/task.php';
	include $_SERVER['DOCUMENT_ROOT'].'/model/billLog.php';

	$sysConfig=Sql::getInstance()->sqlQuery('select * from sysConfig');
?>