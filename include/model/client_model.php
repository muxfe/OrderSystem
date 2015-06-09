<?php
/**
 * 客户信息
 * @copyright (c) XM All Rights Reserved
 */

class Client_Model {

	private $DB;

	function __construct(){
		$this->DB = MySql::getInstance();
	}

	/**
	 * 获取所有客户简略信息
	 */
	function getClients($condition = '', $page = 1){	
		global $perpage_cp;
		$start_limit = !empty($page) ? ($page - 1) * intval($perpage_cp) : 0;
		$limit = " LIMIT $start_limit, ".$perpage_cp;		

		$res = $this->DB->query("SELECT * FROM ".DB_PREFIX."client $condition $limit");
		$clients = array();
		while($row = $this->DB->fetch_array($res)){
			$row['cid'] = htmlspecialchars($row['cid']);
			$row['cname'] = htmlspecialchars($row['cname']);
			$row['phone'] = htmlspecialchars($row['phone']);
			$row['sorc'] = htmlspecialchars($row['sorc']);
			$row['username'] = htmlspecialchars($row['username']);

			//获取客户订单数量
			$Order_Model = new Order_Model();
			$row['conum'] = $Order_Model->getCOrderNum($row['phone']);
			$row['retnum'] = $Order_Model->getCOrderNum($row['phone'], 'r');

			$clients[] = $row;
		}
		return $clients;
	}

	/**
	 * 获取单个客户详细信息
	 */
	function getOneClient($cid){
		$row = $this->DB->once_fetch_array("SELECT * FROM ".DB_PREFIX."client WHERE cid=$cid");
		$clientData = array();
		if($row){
			$clientData = array(
				'cid' => htmlspecialchars($cid),
				'cname' => htmlspecialchars($row['cname']),
				'phone' => htmlspecialchars($row['phone']),
				'sex' => htmlspecialchars($row['sex']),
				'adress' => htmlspecialchars($row['adress']),
				'postcode' => htmlspecialchars($row['postcode']),
				'sorc' => htmlspecialchars($row['sorc']),
				'descreption' => htmlspecialchars($row['descreption']),
				'date' => htmlspecialchars($row['date']),
				'username' => htmlspecialchars($row['username'])
			);
		}
		return $clientData;
	}

	/**
	 * 获取客户姓名
	 */
	function getClientByPhone($phone){
		$row = $this->DB->once_fetch_array("SELECT cname FROM ".DB_PREFIX."client WHERE phone='$phone'");
		$cname = '';
		if($row){
			$cname = htmlspecialchars($row['cname']);
		}
		return $cname;
	}

	/**
	 * 修改客户详细信息
	 */
	function updateClient($clientData, $cid){
		$Item = array();
		foreach ($clientData as $key => $data){
			$Item[] = "$key='$data'";
		}

		$upStr = implode(',', $Item);
		$this->DB->query("UPDATE ".DB_PREFIX."client SET $upStr WHERE cid=$cid");
	}

	/**
	 * 添加客户信息
	 */
	function addClient($clientData){
		$Itemk = array();
		$Itemd = array();
		foreach($clientData as $key => $data){
			$Itemk[] = $key;
			$Itemd[] = $data;
		}
		$field = implode(',', $Itemk);
		$values = "'".implode("','", $Itemd)."'";
		$this->DB->query("INSERT INTO ".DB_PREFIX."client ($field) VALUES ($values)");
	}

	/**
	 * 删除客户信息
	 */
	function deleteClient($cid){
		$this->DB->query("DELETE FROM ".DB_PREFIX."client WHERE cid=$cid");
	}

	/**
	 * 获取客户总数
	 */	
	function getClientNum(){
		$data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."client");
		return $data['total'];
	}

	/**
	 * 判断电话是否存在
	 */	
	function isPhoneExist($phone, $cid = ''){
		$subSql = '';
		if($cid != ''){
			$subSql .= ' and cid!='.$cid;
		}
		$data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."client WHERE phone='$phone' $subSql");
		if($data['total'] > 0){
			return true;
		}else{
			return false;
		}
	}
}

