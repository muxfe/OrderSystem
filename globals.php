<?php
/**
 *  登陆
 *  @copyright (c) XM All Rights Reserved 
 */

require_once 'init.php';

define('TEMPLATE_PATH', JCORDER_ROOT.'/content/views/');
define('AUTHOR_HOST', 'http://www.xiaomu.org/');

$action = isset($_GET['action']) ? addslashes($_GET['action']) : '';

//登陆验证
if($action == 'login'){
	$username = isset($_POST['user']) ? addslashes(trim($_POST['user'])) : '';
	$password = isset($_POST['pwd']) ? addslashes(trim($_POST['pwd'])) : '';
	$ispersis = isset($_POST['ispersis']) ? intval($_POST['ispersis']) : false;
	/*验证码*/
	$imgcode = isset($_POST['imgcode']) ? addslashes(trim(strtoupper($_POST['imgcode']))) : '';


	$loginAuthRet = LoginAuth::checkUser($username, $password, $imgcode);

	if($loginAuthRet === true){
		LoginAuth::setAuthCookie($username, $ispersis);
		jcDirect("./");
	}else{
		LoginAuth::loginPage($loginAuthRet);
	}
}

//退出
if($action == 'logout'){
	setcookie(AUTH_COOKIE_NAME, ' ', time() - 31536000, '/');
	jcDirect("./");
}

if(ISLOGIN === false){ 
	LoginAuth::loginPage();
}

$Option = new Option_Model();
$optionAll = $Option->getAll();
extract($optionAll);

$request_uri = strtolower(substr(basename($_SERVER['SCRIPT_NAME']),0,-4));
if(ROLE != ROLE_ADMIN && in_array($request_uri, array('order','new_order','user','product','client'))){
	jcMsg("权限不足", './');
}

