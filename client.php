<?php
/**
 * 客户管理
 * @copyright (c) XM All Rights Reserved
 */

require_once 'globals.php';

$Client_Model = new Client_Model();

//加载用户管理页面
if ($action == '') {
	$sortPhone = (isset($_GET['sortPhone']) && $_GET['sortPhone'] == 'DESC') ?  'ASC' : 'DESC';
	$search = isset($_GET['search']) ? addslashes($_GET['search']) : '';

	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$sqlSegment= '';
	if(isset($_GET['search'])){
		$sqlSegment .= " WHERE phone like '%$search%' ";
	}
	if(isset($_GET['sortPhone'])){
		$sqlSegment.= " ORDER BY phone $sortPhone";
	}

	$clients = $Client_Model->getClients($sqlSegment, $page);
    $clientNum = $Client_Model->getClientNum();

       $subPage = '';
	foreach($_GET as $key => $val){
		$subPage .= $key != 'page' ? "&$key=$val" : '';
	}
	$pageurl = pagination($clientNum, $perpage_cp, $page, "client.php?{$subPage}&page=");

    include View::getView('header');
    require_once View::getView('client');
    include View::getView('footer');
}

if ($action== 'new') {
	$phone = isset($_POST['phone']) ? addslashes(trim($_POST['phone'])) : '';
	$cname = isset($_POST['cname']) ? addslashes(trim($_POST['cname'])) : '';
	$sorc = isset($_POST['sorc']) ? addslashes(trim($_POST['sorc'])) : 's';
	$username = $userData['username'];

	if (empty($phone) || strlen($phone) > 12 || strlen($phone) < 6) {
		jcDirect('./client.php?error_phone=1');
	}
	if ($Client_Model->isPhoneExist($phone)) {
		jcDirect('./client.php?error_exist=1');
	}

	$clientData = array(
		'phone' => $phone,
		'sorc' => $sorc,
		'cname' => $cname,
		'username' => $username,
	);

	$Client_Model->addClient($clientData);
	jcDirect('./client.php?active_add=1');
}

if ($action== 'edit') {
	$cid = isset($_GET['cid']) ? intval($_GET['cid']) : '';

	$data = $Client_Model->getOneClient($cid);
	extract($data);

	$exs = $exc = '';
	if($sorc == 's'){
		$exs = 'selected="selected"';
	}else{
		$exc = 'selected="selected"';
	}

	$exm = $exf = '';
	if($sex == 'm'){
		$exm = 'checked="checked"';
	}else{
		$sex = 'checked="checked"';
	}	

	include View::getView('header');
	require_once View::getView('clientedit');
	include View::getView('footer');
}

if ($action=='update') {
	$cid = isset($_POST['cid']) ? addslashes(trim($_POST['cid'])) : '';
	$phone = isset($_POST['phone']) ? addslashes(trim($_POST['phone'])) : '';
	$cname = isset($_POST['cname']) ? addslashes(trim($_POST['cname'])) : '';
	$email = isset($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
	$description = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
	$sorc = isset($_POST['sorc']) ? addslashes(trim($_POST['sorc'])) : 's';
	$sex = isset($_POST['sex']) ? addslashes(trim($_POST['sex'])) : 'm';

	if (empty($phone) || strlen($phone) > 12 || strlen($phone) < 6) {
		jcDirect("./client.php?action=edit&cid={$cid}&error_phone=1");
	}
	if ($Client_Model->isPhoneExist($phone, $cid)) {
		jcDirect("./client.php?action=edit&cid={$cid}&error_exist=1");
	}

	if(strlen($description) > 255){
		jcDirect("./client.php?action=edit&cid={$cid}&error_des=1");
	}

	$clientData = array(
		'cname' => $cname,
		'phone' => $phone,
		'email' => $email,
		'description' => $description,
		'sorc' => $sorc,
		'sex' => $sex,
	);

	$Client_Model->updateClient($clientData, $cid);
	jcDirect('./client.php?active_update=1');
}

if ($action== 'del') {
	$Clients = $Client_Model->getClients();
	$cid = isset($_GET['cid']) ? intval($_GET['cid']) : '';


	$Client_Model->deleteClient($cid);
	jcDirect('./client.php?active_del=1');
}
