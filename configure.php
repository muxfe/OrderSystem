<?php
/**
 * 基本设置
 * @copyright (c) XM All Rights Reserved
 */

require_once 'globals.php';

$Option_Model = new Option_Model();

if ($action == '') {

	$index_ordernum = intval($index_ordernum);

	include View::getView('header');
	require_once(View::getView('configure'));
	include View::getView('footer');
}

if ($action == 'mod_config') {

	$site_title = isset($_POST['site_title']) ? addslashes($_POST['site_title']) : '';
	$site_url = isset($_POST['site_url']) ? addslashes($_POST['site_url']) : '';
	$index_ordernum = isset($_POST['index_ordernum']) ? addslashes($_POST['index_ordernum']) : '';
	$order_info = isset($_POST['order_info']) ? addslashes($_POST['order_info']) : '';
	$footer_info = isset($_POST['footer_info']) ? addslashes($_POST['footer_info']) : '';
	$order_title = isset($_POST['order_title']) ? addslashes($_POST['order_title']) : '';
	$perpage_cp = isset($_POST['perpage_cp']) ? addslashes($_POST['perpage_cp']) : '';

    LoginAuth::checkToken();

	$optionsData = array(
		'site_title' => $site_title,
		'site_url' => $site_url,
		'order_title' => $order_title,
		'index_ordernum' => $index_ordernum,
		'order_info' => $order_info,
		'footer_info' => $footer_info,
		'perpage_cp' =>$perpage_cp,
	);

	foreach ($optionsData as $key => $val) {
		$Option_Model->updateOption($key, $val);
	}
	jcDirect("./configure.php?activated=1");
}
