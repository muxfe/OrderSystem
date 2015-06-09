<?php
/**
 * 显示订单管理
 * @copyright (c) XM All Rights Reserved
 */

require_once 'globals.php';

$Order_Model = new Order_Model();

//显示订单管理页面
if($action == ''){
	//过滤
	$filterByPhone = isset($_GET['filterByPhone']) ? addslashes($_GET['filterByPhone']) : '';
	$filterByFstate = isset($_GET['filterByFstate']) ? addslashes($_GET['filterByFstate']) : '';
	$filterByState = isset($_GET['filterByState']) ? addslashes($_GET['filterByState']) : 'i';
	//分页
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	//排序
	$sortDate = (isset($_GET['sortDate']) && $_GET['sortDate'] == 'DESC') ?  'ASC' : 'DESC';
	$sortSum = (isset($_GET['sortSum']) && $_GET['sortSum'] == 'DESC') ?  'ASC' : 'DESC';
	$sortPhone = (isset($_GET['sortPhone']) && $_GET['sortPhone'] == 'DESC') ?  'ASC' : 'DESC';
	//订单状态
	$state = isset($_GET['state']) ? addslashes($_GET['state']) : 'y';
	$pwd = '订单管理';
	if($state != 'y'){
		$state = 'n';
		$pwd = '订单回收站';
	}

	$sqlSegment = '';
	if(!empty($filterByFstate)){
		$sqlSegment .= " AND fstate='$filterByFstate' ";
	}
	if(!empty($filterByPhone)){
		$sqlSegment .= " AND phone LIKE '%$filterByPhone%' ";
	}
	

	$sqlSegment .= ' ORDER BY ';
	if(isset($_GET['sortDate'])){
		$sqlSegment .= "date $sortDate";
	}else if(isset($_GET['sortSum'])){
		$sqlSegment .= "sum $sortSum";
	}else if(isset($_GET['sortPhone'])){
		$sqlSegment .= "phone $sortPhone";
	}else{
		$sqlSegment .= 'date DESC';
	}

	$orderNum = $Order_Model->getOrderNum();
	$orders = $Order_Model->getOrders($filterByState, $sqlSegment, $state, $page);

	//分页代码
	$subPage = '';
	foreach($_GET as $key => $val){
		$subPage .= $key != 'page' ? "&$key=$val" : '';
	}
	$pageurl = pagination($orderNum, $index_ordernum, $page, "order.php?{$subPage}&page=");

	include View::getView('header');
	require_once View::getView('order');
	include View::getView('footer');
}

//操作订单
if($action == 'operate_order'){
	$operate = isset($_REQUEST['operate']) ? $_REQUEST['operate'] : '';
	//oid[]
	$orders = isset($_POST['order']) ? array_map('intval', $_POST['order']) : array();
	//更新付款状态
	$updateFs = isset($_POST['updateFs']) ? addslashes($_POST['updateFs']) : '';
	//更新付款金额
	$updatePay = isset($_POST['updatePay']) ? floatval($_POST['updatePay']) : '';

	$token = isset($_POST['token']) ? addslashes($_POST['token']) : '';

	LoginAuth::checkToken();

	if($operate == ''){
		jcDirect("./order.php?error_b=1");
	}
	if(empty($orders)){
		jcDirect("./order.php?error_a=1");
	}

	switch($operate){
		case 'cancel':
			foreach($orders as $val){
				$Order_Model->cancelOrder($val);
			}
			jcDirect("./order.php?active_cancel=1");
			break;
		case 'updatefs':
			if(!empty($updateFs)){
				foreach($orders as $val){
					$orderData = array('fstate' => $updateFs);
					$Order_Model->updateOrder($orderData, $val);
				}
				jcDirect("./order.php?active_f=1");
			}else{
				jcDirect("./order.php?error_f=1");
			}
			break;
		case 'updatepay':
			if(!empty($updatePay)){
				foreach($orders as $val){
					$Order_Model->updateOrderPay($updatePay, $val);
				}
				jcDirect("./order.php?active_pay=1");
			}else{
				jcDirect("./order.php?error_pay=1");
			}
			break;
		case 'recycle':
			foreach($orders as $val){
				$orderData = array('state' => 'y');
				$Order_Model->updateOrder($orderData, $val);	
			}
			jcDirect("./order.php?state=n&active_re=1");
			break;
		case 'del':
			foreach($orders as $val){
				$Order_Model->deleteOrder($val);
			}
			jcDirect("./order.php?state=n&active_del=1");
	}
}
