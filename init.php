<?php
/**
 * 全局项加载
 * @copyright (c) XM All Rights Reserved
 */

error_reporting(7);
header('Content-Type: text/html; charset=UTF-8');

define('JCORDER_ROOT', dirname(__FILE__));

require_once JCORDER_ROOT.'/config.php';
require_once JCORDER_ROOT.'/include/lib/function.base.php';

doStripslashes();

$userData = array();

define('ISLOGIN', LoginAuth::isLogin());

//用户
define('ROLE_ADMIN', 'admin');
define('ROLE_VISITOR', 'visitor');

//用户角色
define('ROLE', ISLOGIN === true ? $userData['role'] : ROLE_VISITOR);
//用户ID
define('UID', ISLOGIN === true ? $userData['uid'] : '');
//站点固定地址
define('SYS_URL', getBlogUrl());
