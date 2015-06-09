<?php
/**
 * 系统主页
 * @copyright (c) XM All Rights Reserved
 */

require_once 'globals.php';

$action = isset($_GET['action']) ? addslashes($_GET['action']) : '';

if($action == ''){
	$serverapp = $_SERVER['SERVER_SOFTWARE'];
	$DB = MySql::getInstance();
	$mysql_ver = $DB->getMysqlVersion();
	$php_ver = PHP_VERSION;	

	$Order_Model = new Order_Model();
	$date = date('Y').'-'.date('m');
	$start_date = $date.'-01';
	$end_date = $date.'-'.date('t');
	$curMonNum = $Order_Model->getOrderNumByDate($start_date, $end_date);

	$preMon = date("n") - 1;
	$preMon = $preMon < 10 ? '0'.$preMon : $preMon;
	$pre_date = date('Y').'-'.$preMon;
	$pre_start_date = $pre_date.'-01';
	$pre_end_date = $pre_date.'-'.date('t', $pre_start_date);
	$preMonNum = $Order_Model->getOrderNumByDate($pre_start_date, $pre_end_date);

	$notfullNum = $Order_Model->getOrderNumByDate($start_date, $end_date, 'i', " AND fstate!='f' ");

	$curMonSum = $Order_Model->countSum($start_date, $end_date);
	$curMonRetSum = $Order_Model->countSum($start_date, $end_date, 'r');
	$preMonSum = $Order_Model->countSum($pre_start_date, $pre_end_date);
	$preMonRetSum = $Order_Model->countSum($pre_start_date, $pre_end_date, 'r');

	$curMonPay = $Order_Model->countPay($start_date, $end_date);
	$curMonRet = $Order_Model->countPay($start_date, $end_date, 'r');  
	$preMonPay = $Order_Model->countPay($pre_start_date, $pre_end_date);
	$preMonRet = $Order_Model->countPay($pre_start_date, $pre_end_date, 'r');

	require_once TEMPLATE_PATH.'header.php';
	require_once TEMPLATE_PATH.'index.php';
	require_once TEMPLATE_PATH.'footer.php';
}

if($action == 'phpinfo'){
	@phpinfo() OR jcMsg('phpinfo函数被禁用!!');
}


