<?php if(!defined('JCORDER_ROOT')) {exit('error!');} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<meta name="author" content="xiaomu" />
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<title><?php echo $site_title; ?></title>
<link href="content/views/style/style.css" type=text/css rel=stylesheet>
<link href="content/views/css/css-main.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="include/lib/js/jquery/jquery-1.7.1.js"></script>
<script type="text/javascript" src="include/lib/js/jquery/plugin-cookie.js"></script>
<script type="text/javascript" src="content/views/js/common.js"></script>

</head> 
<body>
<div id="mainpage">
<div id="top_header"></div>
<div id="header">
    <div id="header_left"></div>
    <div id="header_logo"><a href="./" title="系统首页"><?php echo $site_title; ?></a></div>
    <div id="header_title"><?php echo $site_title; ?></div>
    <div id="header_right"></div>
    <div id="header_menu">
    <a href="./user.php?action=edit&uid=<?php echo $userData['uid']; ?>" title="<?php echo subString($userData['username'], 0, 12) ?>">
        <img src="content/views/images/avatar.jpg" align="top" width="20" height="20" />
    </a><span>|</span>
    <?php if (ROLE == ROLE_ADMIN):?>
    <a href="configure.php"> 设置</a><span>|</span>
	<?php endif;?>
	<a href="./?action=logout">退出</a>
    </div>
</div>
<div id="side">
	<div id="sidebartop"></div>
    <div id="log_mg">
		<li id="menu_no"><a href="new_order.php"><span class="ico16"></span>新订单</a></li>

		<?php if (ROLE == ROLE_ADMIN):?>
        <li id="menu_om"><a href="order.php">订单管理</a></li>
        <li id="menu_cm"><a href="client.php">客户管理</a></li>
    	<?php endif;?>

        <li id="menu_pm"><a href="product.php">货物管理</a> </li>

		<?php if (ROLE == ROLE_ADMIN):?>
        <li id="menu_ct"><a href="count.php">财务统计</a></li>
        <li id="menu_user"><a href="user.php">管理员</a></li>
    	<li id="menu_ret"><a href="order.php?state=n" >回收站</a></li>
        <li id="menu_conf"><a href="configure.php" >系统设置</a></li>
		<?php endif;?>

    </div>

	<div id="sidebarBottom"></div>
</div>
<div id="container"> 

<script></script>
