<?php
/**
 * 用户管理
 * @copyright (c) XM All Rights Reserved
 */

require_once 'globals.php';

$User_Model = new User_Model();

//加载用户管理页面
if ($action == '') {
	$users = $User_Model->getUsers();
    $userNum = $User_Model->getUserNum();
    
    include View::getView('header');
    require_once View::getView('user');
    include View::getView('footer');
}

if ($action== 'new') {
	$login = isset($_POST['login']) ? addslashes(trim($_POST['login'])) : '';
	$password = isset($_POST['password']) ? addslashes(trim($_POST['password'])) : '';
	$password2 = isset($_POST['password2']) ? addslashes(trim($_POST['password2'])) : '';
	$role = isset($_POST['role']) ? addslashes(trim($_POST['role'])) : ROLE_ADMIN;

    LoginAuth::checkToken();

	if ($login == '') {
		jcDirect('./user.php?error_login=1');
	}
	if ($User_Model->isUserExist($login)) {
		jcDirect('./user.php?error_exist=1');
	}
	if (strlen($password) < 6) {
		jcDirect('./user.php?error_pwd_len=1');
	}
	if ($password != $password2) {
		jcDirect('./user.php?error_pwd2=1');
	}

	$PHPASS = new PasswordHash(8, true);
	$password = $PHPASS->HashPassword($password);

	$User_Model->addUser($login, $password, $role);
	jcDirect('./user.php?active_add=1');
}

if ($action== 'edit') {
	$uid = isset($_GET['uid']) ? intval($_GET['uid']) : '';

	$data = $User_Model->getOneUser($uid);
	extract($data);

	include View::getView('header');
	require_once View::getView('useredit');
	include View::getView('footer');
}

if ($action=='update') {
	$login = isset($_POST['username']) ? addslashes(trim($_POST['username'])) : '';
	$nickname = isset($_POST['nickname']) ? addslashes(trim($_POST['nickname'])) : '';
	$password = isset($_POST['password']) ? addslashes(trim($_POST['password'])) : '';
	$password2 = isset($_POST['password2']) ? addslashes(trim($_POST['password2'])) : '';
	$email = isset($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
	$description = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
	$role = isset($_POST['role']) ? addslashes(trim($_POST['role'])) : ROLE_ADMIN;
	$uid = isset($_POST['uid']) ? intval($_POST['uid']) : '';

    LoginAuth::checkToken();

    //创始人账户不能被他人编辑
    if ($uid == 1 && UID != 1) {
		jcDirect('./user.php?error_del_b=1');
	}
	if ($login == '') {
		jcDirect("./user.php?action=edit&uid={$uid}&error_login=1");
	}
	if ($User_Model->isUserExist($login, $uid)) {
		jcDirect("./user.php?action=edit&uid={$uid}&error_exist=1");
	}
	if (strlen($password) > 0 && strlen($password) < 6) {
		jcDirect("./user.php?action=edit&uid={$uid}&error_pwd_len=1");
	}
	if ($password != $password2) {
		jcDirect("./user.php?action=edit&uid={$uid}&error_pwd2=1");
	}

	$userData = array('username' => $login,
						'nickname' => $nickname,
						'email' => $email,
						'description' => $description,
						'role' => $role,
						);

	if (!empty($password)) {
		$PHPASS = new PasswordHash(8, true);
		$password = $PHPASS->HashPassword($password);
		$userData['password'] = $password;
	}

	$User_Model->updateUser($userData, $uid);
	jcDirect('./user.php?active_update=1');
}

if ($action== 'del') {
	$uid = isset($_GET['uid']) ? intval($_GET['uid']) : '';

	LoginAuth::checkToken();

	if (UID == $uid) {
		jcDirect('./user.php');
	}

    //创始人账户不能被删除
    if ($uid == 1) {
		jcDirect('./user.php?error_del_a=1');
	}

	$User_Model->deleteUser($uid);
	jcDirect('./user.php?active_del=1');
}
