<?php
/**
 * 新增、编辑、保存页面
 * @copyright (c) XM All Rights Reserved
 */

require_once 'globals.php';

//新订单页面
if($action == ''){

	$Product_Model = new Product_Model();
	$products = $Product_Model->getProducts(' ORDER BY pid ASC ');

	include View::getView('header');
	require_once View::getView('new_order');
	include View::getView('footer');
}

//订单编辑页面
if($action == 'edit'){
	$Order_Model = new Order_Model();
	$Product_Model = new Product_Model();
	$products = $Product_Model->getProducts(' ORDER BY pid ASC ');

	$oid = isset($_GET['oid']) ? intval($_GET['oid']) : '';
	$orderData = $Order_Model->getOneOrder($oid);
	extract($orderData);

	$pid = explode("|", $pids);
	$pnum = explode("|", $pnums);
	$pdescreption = explode("|", $pdescreptions);

	$exi = $exr = '';
	if($type == 'i'){
		$exi = 'selected="selected"';
	}else{
		$exr = 'selected="selected"';
	}

	$stateColor = $state == 'y' ? 'green' : 'red';
	if($fstate == 'n'){
		$fstateColor = '#FF3333';
	}else if($fstate == 'f'){
		$fstateColor = 'green';
	}else{
		$fstateColor = 'red';
	}

	include View::getView('header');
	require_once View::getView('orderedit');
	include View::getView('footer');
}

//订单保存
if($action == 'save'){
	$phone = isset($_POST['phone']) ? addslashes($_POST['phone']) : '';
	$type = isset($_POST['type']) ? addslashes($_POST['type']) : 'i';
	$sum = isset($_POST['sum']) ? floatval(addslashes($_POST['sum'])) : '';
	$pay = isset($_POST['pay']) ? floatval(addslashes($_POST['pay'])) : '';
	$pdescreptions_arr = isset($_POST['pdescreption']) ? array_map('stringval', $_POST['pdescreption']) : array();
	$pids_f = isset($_POST['pid']) ? array_map('intval', $_POST['pid']) : array();
	$pnums_arr = isset($_POST['pnum']) ? array_map('intval', $_POST['pnum']) : array();
	$pidck = isset($_POST['pidck']) ? array_map('intval', $_POST['pidck']) : array();
	$descreption = isset($_POST['descreption']) ? addslashes($_POST['descreption']) : '';
	$oid = isset($_POST['oid']) ? intval($_POST['oid']) : '';
	$username = $userData['username'];

	$pnums_arr_f = array();
	$pdescreptions_arr_f = array();
	$i = 0; // 选中产品的内循环标记
	foreach($pidck as $val){
		$pids_arr[] = $val;
	}
	foreach($pids_f as $key => $val){
		if($val == $pids_arr[$i]){
			$pnums_arr_f[] = $pnums_arr[$key];
			$pdescreptions_arr_f[] = $pdescreptions_arr[$key];
			$i++;
		}
	}

	if(count($pids_arr) != count($pnums_arr_f) || count($pids_arr) != count($pdescreptions_arr_f)){
		if(isset($_GET['srcedit'])){
			jcDirect("./new_order.php?action=edit&error_data=1&oid=".$oid);
		}else{
			jcDirect("./new_order.php?error_data=1");
		}
	}

	$pdescreptions = implode("|", $pdescreptions_arr_f);
	$pids = implode("|", $pids_arr);
	$pnums = implode("|", $pnums_arr_f);

	$ex = '';
	if(empty($phone) || !in_array(strlen($phone), array('7','8','11','12'))){
		if(isset($_GET['srcedit'])){
			jcDirect("./new_order.php?action=edit&error_phone=1&oid=".$oid);
		}else{
			jcDirect("./new_order.php?error_phone=1");
		}
	}else{
		$Client_Model = new Client_Model();
		if(!$Client_Model->isPhoneExist($phone)){
			$clientData = array(
				'phone' => $phone,
				'username' => $username,
			);
			$Client_Model->addClient($clientData);
			$ex = '&active_c=1';
		}
	}

	if(strlen($pdescreptions) > 255 || strlen($descreption) > 255){
		if(isset($_GET['srcedit'])){
			jcDirect("./new_order.php?action=edit&error_des=1&oid=".$oid);
		}else{
			jcDirect("./order.php?error_des=1");			
		}
	}

	if($pay == 0){
		$fstate = 'u';
	}else if($pay > 0 && $pay < $sum){
		$fstate = 'n';
	}else{
		$fstate = 'f';
	}

	$orderData = array(
		'phone' => $phone,
		'type' => $type,
		'sum' => $sum,
		'pay' => $pay,
		'pdescreptions' => $pdescreptions,
		'pids' => $pids,
		'pnums' => $pnums,
		'username' => $username,
		'fstate' => $fstate,
		'descreption' => $descreption,
	);

	$Order_Model = new Order_Model();
	//为新订单添加日期
	if(!isset($_GET['srcedit'])){
		$orderData['date'] = gmdate('Y-m-d H:i:s', time() + 8 * 3600);
		$url = "./order.php?active_add=1".$ex;
		$Order_Model->addOrder($orderData);
	}else{
		$url = "./order.php?active_edit=1";
		$Order_Model->updateOrder($orderData, $oid);
	}
	jcDirect($url);
}

function stringval($s){
	return addslashes($s);
}
