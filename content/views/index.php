<?php if(!defined('JCORDER_ROOT')) {exit('error!');}?>
<div id="admindex">
<?php if (ROLE == ROLE_ADMIN):?>
<div id="admindex_main">
   
</div>
<div class="clear"></div>
<div style="margin-top: 20px;">
<div id="admindex_servinfo">
<h3>系统简讯</h3>
<ul>
	<li>本月完成订单：<?php echo $curMonNum; ?>笔</li>
	<li>上月完成订单：<?php echo $preMonNum; ?>笔</li>
    <li>未 完 付 订单：<?php echo $notfullNum; ?>笔</li>
    <li>数据库表前缀：<?php echo DB_PREFIX; ?></li>
	<li>PHP版本：<?php echo $php_ver; ?>，MySQL版本：<?php echo $mysql_ver; ?></li>
	<li>服务器环境：<?php echo $serverapp; ?></li>
	<li><a href="index.php?action=phpinfo">更多信息&raquo;</a></li>
</ul>
</div>
<div id="admindex_msg">
<h3>财务简讯</h3>
<ul>
    <li>本月销售额：￥ <?php echo $curMonSum - $curMonRetSum; ?> 元</li>
    <li>上月销售额：￥ <?php echo $preMonSum - $preMonRetSum; ?> 元</li>
    <li>本 月 入 账：￥ <?php echo $curMonPay - $curMonRet; ?> 元</li>
    <li>上 月 入 账：￥ <?php echo $preMonPay - $preMonRet; ?> 元</li>
</ul>
</div>
<div class="clear"></div>
<div id="about">
    您正在使用 电子订单系统 jceos <?php echo Option_Model::JCOS_VERSION; ?>
    <span id="upmsg"></span>
</div>
</div>
</div>
<script>
$(document).ready(function(){

});

</script>
<div class="clear"></div>
<?php endif; ?>
