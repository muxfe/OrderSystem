<?php
/**
 * 产品信息
 * @copyright (c) XM All Rights Reserved
 */

class Product_Model {

	private $DB;

	function __construct(){
		$this->DB = MySql::getInstance();
	}

	/**
	 * 获取产品简略信息
	 */
	function getProducts($condition = '', $page = 1){
		global $perpage_cp;
		$start_limit = !empty($page) ? ($page - 1) * intval($perpage_cp) : 0;
		$limit = " LIMIT $start_limit, ".$perpage_cp;		

		$res = $this->DB->query("SELECT * FROM ".DB_PREFIX."product $condition $limit");
		$products = array();
		while($row = $this->DB->fetch_array($res)){
			$row['pid'] = htmlspecialchars($row['pid']);
			$row['pname'] = htmlspecialchars($row['pname']);
			$row['price'] = htmlspecialchars($row['price']);
			$row['danwei'] = htmlspecialchars($row['danwei']);
			$row['username'] = htmlspecialchars($row['username']);
			$products[] = $row;
		}
		return $products;
	}

	/**
	 * 获取单个产品详细信息
	 */
	function getOneProduct($pid){
		$row = $this->DB->once_fetch_array("SELECT * FROM ".DB_PREFIX."product WHERE pid=$pid");
		$productData = array();
		if($row){
			$productData = array(
				'pid' => htmlspecialchars($pid),
				'pname' => htmlspecialchars($row['pname']),
				'price' => htmlspecialchars($row['price']),
				'descreption' => htmlspecialchars($row['descreption']),
				'date' => htmlspecialchars($row['date']),
				'danwei' => htmlspecialchars($row['danwei']),
				'username' => htmlspecialchars($row['username'])
			);
		}
		return $productData;
	}

	/**
	 * 修改单个产品详细信息
	 */
	function updateProduct($productData, $pid){
		$Item = array();
		foreach ($productData as $key => $data){
			$Item[] = "$key='$data'";
		}

		$upStr = implode(',', $Item);
		$this->DB->query("UPDATE ".DB_PREFIX."product set $upStr WHERE pid=$pid");
	}

	/**
	 * 添加产品信息
	 */
	function addProduct($productData){
		$Itemk = array();
		$Itemd = array();
		foreach($productData as $key => $data){
			$Itemk[] = $key;
			$Itemd[] = $data;
		}
		$field = implode(',', $Itemk);
		$values = "'".implode("','", $Itemd)."'";
		$this->DB->query("INSERT INTO ".DB_PREFIX."product ($field) VALUES ($values)");
	}

	/**
	 * 删除产品信息
	 */
	function deleteProduct($pid){
		$this->DB->query("DELETE FROM ".DB_PREFIX."product WHERE pid=$pid");
	}

	/**
	 * 获取产品总数
	 */	
	function getProductNum(){
		$data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."product");
		return $data['total'];
	}

	/**
	 * 判断产品名称是否存在
	 */	
	function isPnameExist($pname, $pid = ''){
		$subSql = '';
		if($pid != ''){
			$subSql .= ' and pid!='.$pid;
		}
		$data = $this->DB->once_fetch_array("SELECT COUNT(*) AS total FROM ".DB_PREFIX."product WHERE pname='$pname' $subSql");
		if($data['total'] > 0){
			return true;
		}else{
			return false;
		}
	}
}

