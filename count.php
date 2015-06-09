<?php
/**
 * 财务统计
 */

require_once 'globals.php';

if($action == ''){

	$start_year = intval(substr($userData['date'], 0, 4));
	$cur_year = date('Y');
	$cur_month = date('m');
	$cur_day = date('d');

	$year = isset($_GET['year']) ? addslashes($_GET['year']) : $cur_year;
	$month = isset($_GET['month']) ? addslashes($_GET['month']) : $cur_month;
	$day = isset($_GET['day']) ? addslashes($_GET['day']) : $cur_day;

	$month = strlen($month) < 2 ? '0'.$month : $month;
	$day = strlen($day) < 2 ? '0'.$day : $day;

	if(empty($year)){
		jcDirect("./count.php?error_date=1");
	}else if(empty($month)){
		$start_date = $year.'-01-01';
		$end_date = $year.'-12-31';
		$date = $year;
	}else if(empty($day)){
		$date = $year.'-'.$month;
		$start_date = $date.'-01';
		$end_date = $date.'-'.date('t', $start_date);
	}else{
		$start_date = $end_date = $date = $year.'-'.$month.'-'.$day;
	}

	if((strlen($start_date) != strlen($end_date)) || (strlen($start_date) != 10)){
		jcDirect("./count.php?error_date=1");
	}

	$Order_Model = new Order_Model();
	$iOrderNum = $Order_Model->getOrderNumByDate($start_date, $end_date);
	$rOrderNum = $Order_Model->getOrderNumByDate($start_date, $end_date, 'r');

	$iFOrderNum = $Order_Model->getOrderNumByDate($start_date, $end_date, 'i', " AND fstate='f'");
	$rFOrderNum = $Order_Model->getOrderNumByDate($start_date, $end_date, 'r', " AND fstate='f'");

	$iNUOrderNum = $Order_Model->getOrderNumByDate($start_date, $end_date, 'i', " AND fstate!='f'");
	$rNUOrderNum = $Order_Model->getOrderNumByDate($start_date, $end_date, 'r', " AND fstate!='f'");

	$iOrderSum = $Order_Model->countSum($start_date, $end_date);
	$rOrderSum = $Order_Model->countSum($start_date, $end_date, 'r');

	$iOrderPay = $Order_Model->countPay($start_date, $end_date);
	$rOrderPay = $Order_Model->countPay($start_date, $end_date, 'r'); 

	$iOrderNotPay = $Order_Model->countNotPay($start_date, $end_date);
	$rOrderNotPay = $Order_Model->countNotPay($start_date, $end_date, 'r'); 	

	include View::getView('header');
	require_once View::getView('count');
	include View::getView('footer');
}
