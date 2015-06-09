<?php
/**
 * 安装程序
 * @copyright (c) XM All Rights Reserved
 */

define('JCORDER_ROOT', dirname(__FILE__));
define('DEL_INSTALLER', 1);

require_once JCORDER_ROOT.'/include/lib/function.base.php';

header('Content-Type: text/html; charset=UTF-8');
doStripslashes();

$act = isset($_GET['action'])? $_GET['action'] : '';

if (PHP_VERSION < '5.0'){
    jcMsg('您的php版本过低，请选用支持PHP5的环境安装jceos。');
}

if(!$act){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>jceos</title>
<style type="text/css">
<!--
body {background-color:#F7F7F7;font-family: Arial;font-size: 12px;line-height:150%;}
.main {background-color:#FFFFFF;font-size: 12px;color: #666666;width:750px;margin:30px auto;padding:10px;list-style:none;border:#DFDFDF 1px solid; border-radius: 4px;}
.logo{background:url(content/views/images/logo.gif) no-repeat center;padding:30px 0px 30px 0px;margin:30px 0px;}
.title{text-align:center; font-size: 14px;}
.input {border: 1px solid #CCCCCC;font-family: Arial;font-size: 18px;height:28px;background-color:#F7F7F7;color: #666666;margin:0px 0px 0px 25px;}
.submit{cursor: pointer;font-size: 12px;padding: 4px 10px;}
.care{color:#0066CC;}
.title2{font-size:18px;color:#666666;border-bottom: #CCCCCC 1px solid; margin:40px 0px 20px 0px;padding:10px 0px;}
.foot{text-align:center;}
.main li{ margin:20px 0px;}
-->
</style>
</head>
<body>
<form name="form1" method="post" action="install.php?action=install">
<div class="main">
<p class="logo"></p>
<p class="title">jceos <?php echo Option_Model::JCOS_VERSION ?> 安装程序</p>
<div class="b">
<p class="title2">MySQL数据库设置</p>
<li>
	数据库地址： <br />
    <input name="hostname" type="text" class="input" value="localhost">
	<span class="care">(通常为 localhost， 不必修改)</span>
</li>
<li>
    数据库用户名：<br /><input name="dbuser" type="text" class="input" value="">
</li>
<li>
    数据库密码：<br /><input name="password" type="password" class="input">
</li>
<li>
    数据库名：<br />
      <input name="dbname" type="text" class="input" value="">
	  <span class="care">(程序不会自动创建数据库，请提前创建一个空数据库或使用已有数据库)</span>
</li>
<li>
    数据库表前缀：<br />
  <input name="dbprefix" type="text" class="input" value="jceos_">
  <span class="care"> (通常默认即可，不必修改。由英文字母、数字、下划线组成，且必须以下划线结束)</span>
</li>
</div>
<div class="c">
<p class="title2">管理员设置</p>
<li>
登录名：<br />
<input name="admin" type="text" class="input">
</li>
<li>
登录密码：<br />
<input name="adminpw" type="password" class="input">
<span class="care">(不小于6位)</span>
</li>
<li>
再次输入登录密码：<br />
<input name="adminpw2" type="password" class="input">
</li>
</div>
<div>
<p class="foot">
<input type="submit" class="submit" value="开始安装jceos">
</p>
</div>
</div>
</form>
</body>
</html>
<?php
}
if($act == 'install' || $act == 'reinstall'){
	$db_host = isset($_POST['hostname']) ? addslashes(trim($_POST['hostname'])) : '';
	$db_user = isset($_POST['dbuser']) ? addslashes(trim($_POST['dbuser'])) : '';
	$db_pw = isset($_POST['password']) ? addslashes(trim($_POST['password'])) : '';
	$db_name = isset($_POST['dbname']) ? addslashes(trim($_POST['dbname'])) : '';
	$db_prefix = isset($_POST['dbprefix']) ? addslashes(trim($_POST['dbprefix'])) : '';
	$admin = isset($_POST['admin']) ? addslashes(trim($_POST['admin'])) : '';
	$adminpw = isset($_POST['adminpw']) ? addslashes(trim($_POST['adminpw'])) : '';
	$adminpw2 = isset($_POST['adminpw2']) ? addslashes(trim($_POST['adminpw2'])) : '';
	$result = '';

	if($db_prefix == ''){
		jcMsg('数据库表前缀不能为空!');
	}elseif(!preg_match("/^[\w_]+_$/",$db_prefix)){
		jcMsg('数据库表前缀格式错误!');
	}elseif($admin == '' || $adminpw == ''){
		jcMsg('登录名和密码不能为空!');
	}elseif(strlen($adminpw) < 6){
		jcMsg('登录密码不得小于6位');
	}elseif($adminpw!=$adminpw2)	 {
		jcMsg('两次输入的密码不一致');
	}

	//初始化数据库类
	define('DB_HOST',   $db_host);
	define('DB_USER',   $db_user);
	define('DB_PASSWD', $db_pw);
	define('DB_NAME',   $db_name);
	define('DB_PREFIX', $db_prefix);

	$DB = MySql::getInstance();

	if($act != 'reinstall' && $DB->num_rows($DB->query("SHOW TABLES LIKE '{$db_prefix}orders'")) == 1){
		echo <<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>emlog system message</title>
<style type="text/css">
<!--
body {background-color:#F7F7F7;font-family: Arial;font-size: 12px;line-height:150%;}
.main {background-color:#FFFFFF;font-size: 12px;color: #666666;width:750px;margin:10px auto;padding:10px;list-style:none;border:#DFDFDF 1px solid;}
.main p {line-height: 18px;margin: 5px 20px;}
-->
</style>
</head><body>
<form name="form1" method="post" action="install.php?action=reinstall">
<div class="main">
	<input name="hostname" type="hidden" class="input" value="$db_host">
	<input name="dbuser" type="hidden" class="input" value="$db_user">
	<input name="password" type="hidden" class="input" value="$db_pw">
	<input name="dbname" type="hidden" class="input" value="$db_name">
	<input name="dbprefix" type="hidden" class="input" value="$db_prefix">
	<input name="admin" type="hidden" class="input" value="$admin">
	<input name="adminpw" type="hidden" class="input" value="$adminpw">
	<input name="adminpw2" type="hidden" class="input" value="$adminpw2">
<p>
你的jceos看起来已经安装过了。继续安装将会覆盖原有数据，确定要继续吗？
<input type="submit" value="继续&raquo;">
</p>
<p><a href="javascript:history.back(-1);">&laquo;点击返回</a></p>
</div>
</form>
</body>
</html>
EOT;
		exit;
	}

	if(!is_writable('config.php')){
		jcMsg('配置文件(config.php)不可写。如果您使用的是Unix/Linux主机，请修改该文件的权限为777。如果您使用的是Windows主机，请联系管理员，将此文件设为可写');
	}

	$config = "<?php\n"
	."//mysql database address\n"
	."define('DB_HOST','$db_host');"
	."\n//mysql database user\n"
	."define('DB_USER','$db_user');"
	."\n//database password\n"
	."define('DB_PASSWD','$db_pw');"
	."\n//database name\n"
	."define('DB_NAME','$db_name');"
	."\n//database prefix\n"
	."define('DB_PREFIX','$db_prefix');"
	."\n//auth key\n"
	."define('AUTH_KEY','".getRandStr(32).md5($_SERVER['HTTP_USER_AGENT'])."');"
	."\n//cookie name\n"
	."define('AUTH_COOKIE_NAME','EM_AUTHCOOKIE_".getRandStr(32,false)."');"
	."\n";

	$fp = @fopen('config.php', 'w');
	$fw = @fwrite($fp, $config);
	if (!$fw){
		jcMsg('配置文件(config.php)不可写。如果您使用的是Unix/Linux主机，请修改该文件的权限为777。如果您使用的是Windows主机，请联系管理员，将此文件设为可写');
	}
	fclose($fp);

	//密码加密存储
	$PHPASS = new PasswordHash(8, true);
	$adminpw = $PHPASS->HashPassword($adminpw);
	$curdate = gmdate('Y-m-d H:i:s', time() + 8 * 3600);

	$dbcharset = 'utf8';
	$type = 'MYISAM';
	$add = $DB->getMysqlVersion() > '4.1' ? 'ENGINE='.$type.' DEFAULT CHARSET='.$dbcharset.';':'TYPE='.$type.';';
	$setchar = $DB->getMysqlVersion() > '4.1' ? "ALTER DATABASE `{$db_name}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;" : '';

	define('SYS_URL', getBlogUrl());

	$sql = $setchar."
DROP TABLE IF EXISTS {$db_prefix}orders;
CREATE TABLE {$db_prefix}orders (
oid int(12) unsigned NOT NULL auto_increment,
pids varchar(255) NOT NULL default '',
pnums varchar(255) NOT NULL default '',
pdescreptions varchar(255) NOT NULL default '',
state enum('y','n') NOT NULL default 'y',
fstate enum('f','n','u') NOT NULL default 'u',
sum numeric(12,2) NOT NULL default '0',
pay numeric(12,2) NOT NULL default '0',
username varchar(32) NOT NULL default '',
phone varchar(32) NOT NULL default '',
type enum('i','r') NOT NULL default 'i',
descreption varchar(255) NOT NULL default '',
chgdate timestamp,
date varchar(32) NOT NULL default '',
PRIMARY KEY (oid),
KEY date (date)
)".$add."
DROP TABLE IF EXISTS {$db_prefix}client;
CREATE TABLE {$db_prefix}client (
cid int(10) unsigned NOT NULL auto_increment,
cname varchar(32) NOT NULL default '',
phone varchar(32) NOT NULL default '',
sex enum('f','m') NOT NULL default 'm',
adress varchar(255) NOT NULL default '',
postcode varchar(20) NOT NULL default '',
sorc enum('s','c') NOT NULL default 's',
email varchar(60) NOT NULL default '',
description varchar(255) NOT NULL default '',
date timestamp,
username varchar(32) NOT NULL default '',
PRIMARY KEY (cid),
KEY phone (phone)
)".$add."
DROP TABLE IF EXISTS {$db_prefix}product;
CREATE TABLE {$db_prefix}product(
pid int(10) unsigned NOT NULL auto_increment,
pname char(30) NOT NULL default '',
description varchar(255) NOT NULL default '',
price numeric(12,2) unsigned NOT NULL default '0',
danwei enum('j','g','k','p','z') NOT NULL default 'g',
date timestamp,
username varchar(32) NOT NULL default '',
PRIMARY KEY (pid),
KEY pname (pname)
)".$add."
DROP TABLE IF EXISTS {$db_prefix}options;
CREATE TABLE {$db_prefix}options (
opid int(10) unsigned NOT NULL auto_increment,
option_name varchar(60) NOT NULL default '',
option_value varchar(255) NOT NULL default '',
PRIMARY KEY (opid),
KEY option_name (option_name)
)".$add."
INSERT INTO {$db_prefix}options (option_name, option_value) VALUES ('footer_info','站点底部信息写这里');
INSERT INTO {$db_prefix}options (option_name, option_value) VALUES ('order_info','订货单底部信息写这里');
INSERT INTO {$db_prefix}options (option_name, option_value) VALUES ('site_title','站点标题写这里');
INSERT INTO {$db_prefix}options (option_name, option_value) VALUES ('site_url','站点URL地址写这里');
INSERT INTO {$db_prefix}options (option_name, option_value) VALUES ('order_title','订货单的抬头写这里');
INSERT INTO {$db_prefix}options (option_name, option_value) VALUES ('index_ordernum','10');
INSERT INTO {$db_prefix}options (option_name, option_value) VALUES ('perpage_cp','10');
DROP TABLE IF EXISTS {$db_prefix}user;
CREATE TABLE {$db_prefix}user (
uid int(10) unsigned NOT NULL auto_increment,
username varchar(32) NOT NULL default '',
nickname char(20) NOT NULL default '',
password varchar(64) NOT NULL default '',
role varchar(60) NOT NULL default '',
email varchar(60) NOT NULL default '',
description varchar(255) NOT NULL default '',
date varchar(32) NOT NULL default '',
PRIMARY KEY (uid),
KEY username (username)
)".$add."
INSERT INTO {$db_prefix}user (uid, username, password, role, date) VALUES (1,'$admin','".$adminpw."','admin', '$curdate');";

	$array_sql = preg_split("/;[\r\n]/", $sql);
	foreach($array_sql as $sql){
		$sql = trim($sql);
		if ($sql){
			$DB->query($sql);
		}
	}

	$result .= "
		<p style=\"font-size:24px; border-bottom:1px solid #E6E6E6; padding:10px 0px;\">恭喜，安装成功！</p>
		<p>您的jceos已经安装好了，现在可以开始您的工作了，就这么简单!</p>
		<p><b>用户名</b>：{$admin}</p>
		<p><b>密 码</b>：您刚才设定的密码</p>";
	if (DEL_INSTALLER === 1 && !@unlink('./install.php') || DEL_INSTALLER === 0) {
	    $result .= '<p style="color:red;margin:10px 20px;">警告：请手动删除根目录下安装文件：install.php</p> ';
	}
	$result .= "<p style=\"text-align:right;\"><a href=\"./\">访问首页</a></p>";
	jcMsg($result, 'none');
}
?>