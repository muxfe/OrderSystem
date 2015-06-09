<?php
/**
 * 订单打印
 */

require_once 'globals.php';

if($action == 'print'){

	$oid = isset($_GET['oid']) ? intval($_GET['oid']) : '';
	
	$Order_Model = new Order_Model();
	$orderData = $Order_Model->getOneOrder($oid);
	extract($orderData);

	$Client_Model = new Client_Model();
	$client = $Client_Model->getClientByPhone($phone);

	$pnum = explode('|', $pnums);
	$pid = explode('|', $pids);
	$pdescreption = explode('|', $pdescreptions);

	$Product_Model = new Product_Model();
	$products = array();
	foreach($pid as $val){
		$products[] = $Product_Model->getOneProduct($val);
	}

	$order_info = str_replace(".", "<br />", $order_info);
	$order_info = str_replace("|", "  ", $order_info);

	require_once View::getView('print');
}
