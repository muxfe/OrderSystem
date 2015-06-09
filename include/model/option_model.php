<?php
/**
 * 配置信息
 */

class Option_Model {
	//JCOS版本编号
	const JCOS_VERSION = '1.0.3';

	private $DB;

	function __construct(){
		$this->DB = MySql::getInstance();
	}

	/**
	 * 获取所有配置信息
	 */
	function getAll(){
		$options = array();
		$res = $this->DB->query("SELECT * FROM ".DB_PREFIX."options");
		while($row = $this->DB->fetch_array($res)){
			$options[$row['option_name']] = $row['option_value'];
		}
		return $options;
	}

	/**
	 * 获取配置信息
	 */
	function get($option){
		$data = $this->DB->once_fetch_array("SELECT * FROM ".DB_PREFIX."options WHERE option_name='$option'");
		return $data;
	}

	/**
	 * 更改配置信息
	 */
	function updateOption($option, $value){	
		$this->DB->query("UPDATE ".DB_PREFIX."options SET option_value='$value' WHERE option_name='$option'");
	}

}


