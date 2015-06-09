<?php
/**
 * 订单信息
 * @copyright (c) XM All Rights Reserved
 */

class Order_Model {

	private $DB;

	function __construct(){
		$this->DB = MySql::getInstance();
	}

	/**
	 * 获取所有订单
	 */
	function getOrders($type = 'i', $condition = '', $state = 'y', $page = 1){
		global $index_ordernum;
		$start_limit = !empty($page) ? ($page - 1) * intval($index_ordernum) : 0;
		$limit = " LIMIT $start_limit, ".$index_ordernum;

		$res = $this->DB->query("SELECT * FROM ".DB_PREFIX."orders WHERE state='$state' and type='$type' $condition $limit");
		$orders = array();
		while($row = $this->DB->fetch_array($res)){
			$row['oid'] = htmlspecialchars($row['oid']);
			$row['state'] = htmlspecialchars($row['state']);
			$row['fstate'] = htmlspecialchars($row['fstate']);
			$row['sum'] = htmlspecialchars($row['sum']);
			$row['pay'] = htmlspecialchars($row['pay']);
			$row['descreption'] = htmlspecialchars($row['descreption']);
			$row['phone'] = htmlspecialchars($row['phone']);
			$row['date'] = htmlspecialchars($row['date']);
			$row['type'] = htmlspecialchars($row['type']);
			$orders[] = $row;
		}
		return $orders;
	}

	/**
	 * 获取单笔订单详情
	 */
	function getOneOrder($oid){
		$row = $this->DB->once_fetch_array("SELECT * FROM ".DB_PREFIX."orders WHERE oid=$oid");

		$orderData = array();
		if($row){
			$orderData = array(
				'oid' => htmlspecialchars($oid),
				'pids' => htmlspecialchars($row['pids']),
				'pnums' => htmlspecialchars($row['pnums']),
				'sum' => htmlspecialchars($row['sum']),
				'date' => htmlspecialchars($row['date']),
				'chgdate' => htmlspecialchars($row['chgdate']),
				'state' => htmlspecialchars($row['state']),
				'fstate' => htmlspecialchars($row['fstate']),
				'username' => htmlspecialchars($row['username']),
				'phone' => htmlspecialchars($row['phone']),
				'type' => htmlspecialchars($row['type']),
				'pdescreptions' => htmlspecialchars($row['pdescreptions']),
				'pay' => htmlspecialchars($row['pay']),
				'descreption' => htmlspecialchars($row['descreption']),
			);
		}
		return $orderData;
	}

	/**
	 * 修改订单信息
	 */
	function updateOrder($orderData, $oid){
		$Item = array();
		foreach($orderData as $key => $data){
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$this->DB->query("UPDATE ".DB_PREFIX."orders set $upStr WHERE oid=$oid");
	}

	/**
	 * 修改订单付款金额
	 */
	function updateOrderPay($newpay, $oid){
		$row = $this->DB->once_fetch_array("SELECT sum,pay,fstate FROM ".DB_PREFIX."orders WHERE oid=$oid");
		$orderData = array();
		if($row){
			$orderData = array(
				'sum' => htmlspecialchars($row['sum']),
				'fstate' => htmlspecialchars($row['fstate']),
				'pay' => htmlspecialchars($row['pay']),
			);
		}

		$orderData['pay'] += $newpay;
		if($orderData['pay'] >= $orderData['sum']){
			$orderData['fstate'] = 'f';
		}else if($orderData['pay'] <= 0){
			$orderData['fstate'] = 'u';
		}else{
			$orderData['fstate'] = 'n';
		}

		self::updateOrder($orderData, $oid);
	}

	/**
	 * 添加订单信息
	 */
	function addOrder($orderData){
		$Itemk = array();
		$Itemd = array();
		foreach($orderData as $key => $data){
			$Itemk[] = $key;
			$Itemd[] = $data;
		}
		$field = implode(',', $Itemk);
		$values = "'".implode("','", $Itemd)."'";
		$this->DB->query("INSERT INTO ".DB_PREFIX."orders ($field) VALUES ($values)");
		$orderid = $this->DB->insert_id();
		return $orderid;
	}

	/**
	 * 获取订单数目
	 */
	function getOrderNum($type = 'i', $condition = '', $state = 'y'){
		$data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."orders WHERE state='y' AND type='$type' $condition");
		return $data['total'];
	}

	/**
	 * 取消(作废)订单
	 */
	function cancelOrder($oid){
		$this->DB->query("UPDATE ".DB_PREFIX."orders SET state='n' WHERE oid=$oid");
	}

	/**
	 * 删除订单
	 */
	function deleteOrder($oid){
		$this->DB->query("DELETE FROM ".DB_PREFIX."orders WHERE oid=$oid");
	}

	/**
	 * 获取客户订单数量
	 */
	function getCOrderNum($phone, $type = 'i'){
		$data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."orders WHERE phone='$phone' AND type='$type' AND state='y'");
		return $data['total'];
	}

 	/**
 	 * 获取指定日期订单数
 	 */
	function getOrderNumByDate($start, $end, $type = 'i', $fstate = ''){
		$start .= ' 00:00:00';
		$end .= ' 23:59:59';
		$data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."orders WHERE date BETWEEN '$start' AND '$end' AND type='$type' AND state='y' $fstate");
		return $data['total'] === NULL ? 0 : $data['total'];
	}

 	/**
 	 * 计算销售额
 	 */
	function countSum($start, $end, $type = 'i'){
		$start .= ' 00:00:00';
		$end .= ' 23:59:59';
		$data = $this->DB->once_fetch_array("SELECT SUM(sum) AS sum FROM ".DB_PREFIX."orders WHERE date BETWEEN '$start' AND '$end' AND type='$type' AND state='y' ");
		return $data['sum'] === NULL ? '0.00' : $data['sum'];
	}

 	/**
 	 * 计算支付额
 	 */
	function countPay($start, $end, $type = 'i'){
		$start .= ' 00:00:00';
		$end .= ' 23:59:59';
		$data = $this->DB->once_fetch_array("SELECT SUM(pay) AS sum FROM ".DB_PREFIX."orders WHERE date BETWEEN '$start' AND '$end' AND type='$type' AND state='y' ");
		return $data['sum'] === NULL ? '0.00' : $data['sum'];
	}

 	/**
 	 * 计算欠付款
 	 */
	function countNotPay($start, $end, $type = 'i'){
		$start .= ' 00:00:00';
		$end .= ' 23:59:59';
		$res = $this->DB->query("SELECT sum,pay,fstate FROM ".DB_PREFIX."orders WHERE date BETWEEN '$start' AND '$end' AND type='$type' AND state='y' ");
		$data = 0;
		while($row = $this->DB->fetch_array($res)){
			if($row['fstate'] != 'f'){
				$data += $row['sum'] - $row['pay'];
			}
		}
		return $data ? $data : '0.00';
	}
}
