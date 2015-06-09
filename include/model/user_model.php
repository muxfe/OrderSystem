<?php
/**
 * 管理员信息
 * @copyright (c) XM All Rights Reserved
 */

class User_Model {

	private $DB;

	function __construct() {
		$this->DB = MySql::getInstance();
	}

	function getUsers($condition = '') {

		$res = $this->DB->query("SELECT * FROM ".DB_PREFIX."user $condition");
		$users = array();
		while($row = $this->DB->fetch_array($res)) {
			$row['name'] = htmlspecialchars($row['nickname']);
			$row['username'] = htmlspecialchars($row['username']);
			$row['role'] = htmlspecialchars($row['role']);
			$row['uid'] = htmlspecialchars($row['uid']);
			$users[] = $row;
		}
		return $users;
	}

	function getOneUser($uid) {
		$row = $this->DB->once_fetch_array("SELECT * FROM ".DB_PREFIX."user WHERE uid=$uid");
		$userData = array();
		if($row) {
			$userData = array(
				'username' => htmlspecialchars($row['username']),
				'nickname' => htmlspecialchars($row['nickname']),
				'email' => htmlspecialchars($row['email']),
				'description' => htmlspecialchars($row['description']),
				'role' => htmlspecialchars($row['role']),
				'date' => htmlspecialchars($row['date']),
			);
		}
		return $userData;
	}

	function updateUser($userData, $uid) {
		$Item = array();
		foreach ($userData as $key => $data) {
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$this->DB->query("UPDATE ".DB_PREFIX."user SET $upStr WHERE uid=$uid");
	}

	function addUser($login, $password,  $role = 'admin') {
		$sql="INSERT INTO ".DB_PREFIX."user (username,password,role) VALUES ('$login','$password','$role')";
		$this->DB->query($sql);
	}

	function deleteUser($uid) {
		$this->DB->query("DELETE FROM ".DB_PREFIX."user WHERE uid=$uid");
	}

	/**
	 * 判断用户名是否存在
	 *
	 * @param string $login
	 * @param int $uid 兼容更新作者资料时用户名未变更情况
	 * @return boolean
	 */
	function isUserExist($login, $uid = '') {
		$subSql = $uid ? 'and uid!='.$uid : '';
        $data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE username='$login' $subSql");
		if ($data['total'] > 0) {
			return true;
		}else {
			return false;
		}
	}

    /**
	 * 判断用户昵称是否存在
	 *
	 * @param string $nickname
	 * @param int $uid 兼容更新作者资料时用户名未变更情况
	 * @return boolean
	 */
	function isNicknameExist($nickname, $uid = '') {
        if(empty($nickname)) {
            return FALSE;
        }
		$subSql = $uid ? 'and uid!='.$uid : '';
        $data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE nickname='$nickname' $subSql");
		if ($data['total'] > 0) {
			return true;
		}else {
			return false;
		}
	}

	function getUserNum() {
        $data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user");
		return $data['total'];
	}
}
