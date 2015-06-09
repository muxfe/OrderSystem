<?php
/**
 * 货物管理
 * @copyright (c) XM All Rights Reserved
 */

require_once 'globals.php';

$Product_Model = new Product_Model();

//加载货物管理页面
if ($action == '') {
	$sortPrice = (isset($_GET['sortPrice']) && $_GET['sortPrice'] == 'DESC') ?  'ASC' : 'DESC';

	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$sqlSegment= '';
	if(isset($_GET['sortPrice'])){
		$sqlSegment.= " ORDER BY price $sortPrice ";
	}

	$products = $Product_Model->getProducts($sqlSegment, $page);
    $productNum = $Product_Model->getProductNum();

    $subPage = '';
	foreach($_GET as $key => $val){
		$subPage .= $key != 'page' ? "&$key=$val" : '';
	}
	$pageurl = pagination($productNum, $perpage_cp, $page, "product.php?{$subPage}&page=");

    include View::getView('header');
    require_once View::getView('product');
    include View::getView('footer');
}

if ($action== 'new') {
	$pname = isset($_POST['pname']) ? addslashes(trim($_POST['pname'])) : '';
	$price = isset($_POST['price']) ? floatval(trim($_POST['price'])) : '';
	$danwei = isset($_POST['danwei']) ? addslashes($_POST['danwei']) : 'g';
	$username = $userData['username'];

	if ($pname == '' || $price == '') {
		jcDirect('./product.php?error_pname_price=1');
	}
	if ($Product_Model->isPnameExist($pname)) {
		jcDirect('./product.php?error_exist=1');
	}

	$productData = array(
		'pname' => $pname,
		'price' => $price,
		'danwei' => $danwei,
		'username' => $username,
	);

	$Product_Model->addProduct($productData);
	jcDirect('./product.php?active_add=1');
}

if ($action== 'edit') {
	$pid = isset($_GET['pid']) ? intval($_GET['pid']) : '';

	$data = $Product_Model->getOneProduct($pid);
	extract($data);

	$exj = $exg = $exp =$exk = $exz = '';
	switch($danwei){
		case 'j': $exj = 'selected="selected"';break;
		case 'g': $exg = 'selected="selected"';break;
		case 'z': $exz = 'selected="selected"';break;
		case 'p': $exp = 'selected="selected"';break;
		case 'k': $exk = 'selected="selected"';break;
	} 

	include View::getView('header');
	require_once View::getView('productedit');
	include View::getView('footer');
}

if ($action=='update') {
	$pid = isset($_POST['pid']) ? addslashes(trim($_POST['pid'])) : '';
	$pname = isset($_POST['pname']) ? addslashes(trim($_POST['pname'])) : '';
	$price = isset($_POST['price']) ? floatval(trim($_POST['price'])) : '';
	$description = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
	$danwei = isset($_POST['danwei']) ? addslashes($_POST['danwei']) : 'g';
	$username = $userData['username'];

	if ($pname == '' || $price == '') {
		jcDirect("./product.php?action=edit&pid={$pid}&error_pname_price=1");
	}
	if ($Product_Model->isPnameExist($pname, $pid)) {
		jcDirect("./product.php?action=edit&pid={$pid}&error_exist=1");
	}
	if(strlen($description) > 255){
		jcDirect("./product.php?action=edit&pid={$pid}&error_des=1");
	}

	$productData = array(
		'pname' => $pname,
		'price' => $price,
		'description' => $description,
		'username' => $username,
		'danwei' => $danwei,
	);

	$Product_Model->updateProduct($productData, $pid);
	jcDirect('./product.php?active_update=1');
}

if ($action== 'del') {
	$products = $Product_Model->getProducts();
	$pid = isset($_GET['pid']) ? intval($_GET['pid']) : '';


	$Product_Model->deleteProduct($pid);
	jcDirect('./product.php?active_del=1');
}
