<?php if(!defined('JCORDER_ROOT')) {exit('error!');} ?>
<div class="containertitle"><b><?php echo $pwd; ?></b>
<?php if(isset($_GET['active_cancel'])):?><span class="actived">删除成功</span><?php endif;?>
<?php if(isset($_GET['active_del'])):?><span class="actived">彻底删除成功</span><?php endif;?>
<?php if(isset($_GET['active_f'])):?><span class="actived">更改订单支付状态成功</span><?php endif;?>
<?php if(isset($_GET['active_re'])):?><span class="actived">恢复订单成功</span><?php endif;?>
<?php if(isset($_GET['active_pay'])):?><span class="actived">添加付款成功</span><?php endif;?>
<?php if(isset($_GET['error_a'])):?><span class="error">请选择要处理的订单</span><?php endif;?>
<?php if(isset($_GET['error_b'])):?><span class="error">请选择要执行的操作</span><?php endif;?>
<?php if(isset($_GET['active_add'])&&!isset($_GET['active_c'])):?><span class="actived">添加订单成功</span><?php endif;?>
<?php if(isset($_GET['active_edit'])):?><span class="actived">更改订单成功</span><?php endif;?>
<?php if(isset($_GET['active_c'])&&isset($_GET['active_add'])):?><span class="actived">成功添加新订单和新客户</span><?php endif;?>
</div>

<div class="line"></div>
<div class="filters">
<div id="f_title">
	<div style="float:left; margin-top:8px;">
		<form action="order.php?" method="get" id="form_filter" name="form_filter">
			<span><a href="./order.php?">全部 </a>| </span>
			<input name="filterByPhone" type="text" value="<? echo isset($_GET['filterByPhone']) ? $_GET['filterByPhone'] : ''; ?>" id="filterByPhone" style="width:100px;height:10px;" class="input" maxlength="12">
			(按电话过滤) | </input>
			<select name="filterByFstate" id="filterByFstate">
				<option value="" >只看状态为...</option>
				<option value="u">未付</option>
				<option value="f">已付</option>
				<option value="n">未付完</option>
			</select> | 
			<select name="filterByState" id="filterByState">
				<option value="i">订货单</option>
				<option value="r">退货单</option>
			</select>
		</form>
	<div style="clear:both"></div>
</div>
</div>
<div style="clear:both"></div>
<form action="order.php?action=operate_order" method="post" name="form_log" id="form_log">

  	<table width="100%" id="adm_log_list" class="item_list">
  	<thead>
      <tr>
		<th width="100"><b>编号</b></th>
        <th width="170"><a href="./order.php?sortPhone=<?php echo $sortPhone; ?>">客户电话</a></th>
		<th width="100"><a href="./order.php?sortSum=<?php echo $sortSum; ?>">金额</a></th>
		<th width="100"><b>已付款</b></th>
		<th width="100"><b>欠付款</b></th>
        <th width="80"><b>状态</b></th>
        <th width="150"><b><a href="./order.php?sortDate=<?php echo $sortDate; ?>">日期</a></b></th>
		<th width="150" ><b>描述</b></th>
      </tr>
  	</thead>
  	<tbody>
	<?php
		if($orders):
		foreach($orders as $key=>$value):
	?>
      <tr>
	      <td><input type="checkbox" name="order[]" value="<?php echo $value['oid']; ?>" class="ids" />
	      <?php echo $value['oid']; ?></td>
		  <td><a href="client.php?search=<?php echo $value['phone']; ?>"><?php echo $value['phone']; ?></a><br />
		  <span style="display:none; margin-left:8px;">
		  <a href="new_order.php?action=edit&oid=<?php echo $value['oid']?>">编辑</a> |
		  <a href="print.php?action=print&oid=<?php echo $value['oid']?>">打印</a> 
		  </span>
		  </td>
	      <td><?php echo $value['sum']; ?></td>
	      <td><?php echo $value['pay']; ?></td>
	      <td><?php echo $value['fstate'] == 'f' ? 0 : ($value['sum'] - $value['pay']); ?></td>
	      	<?php
	      		$fstateColor = '';
	      		$fstateText = '';
	      		switch($value['fstate']){
	      			case 'n': $fstateText='未付完';$fstateColor='#FF3333';break;case 'f': $fstateText='已付';$fstateColor='green';break;case 'u': $fstateText='未付';$fstateColor='red';break;
	      		}
	      	?>	      
	      <td class="fstate" style="color:<?php echo $fstateColor; ?>">
	      	<?php echo $fstateText; ?>
	      	<input name="fstate[]" class="fstate input" type="hidden" value="<?php echo $value['fstate']; ?>"></input>
	      </td>
	      <td><?php echo $value['date']; ?></td>
		  <td><?php echo $value['descreption']; ?></td>
      </tr>
		<?php endforeach;else:?>
	  <tr><td class="tdcenter" colspan="8">还没有订单</td></tr>
		<?php endif;?>
	</tbody>
	</table>
    <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
	<input name="operate" id="operate" value="" type="hidden" />
	<div class="list_footer">
	<a href="javascript:void(0);" id="select_all">全选 </a>| 选中项：
	<?php if(isset($_GET['state']) && $_GET['state'] == 'n'): ?>
	<a href="javascript:logact('del');" class="care">彻底删除</a> |
	<a href="javascript:logact('recycle');">恢复订单</a>
	<?php else: ?> 
	<a href="javascript:logact('cancel');" class="care">回收站</a> |
	<select id="updateFs" name="updateFs">
		<option value="nosel">更新付款状态</option>
		<option value="u">未付款</option>
		<option value="n">未全付</option>
		<option value="f">已全付</option>
	</select> | 
	<input name="updatePay" id="updatePay" class="input" type="text" value="0" style="width:80px" maxlength="5">
	<a href="javascript:logact('updatepay');">新增付款</a></input>
	<?php endif; ?>
	</div>
</form>
<div class="page"><?php echo $pageurl; ?>(有<?php echo $orderNum; ?>笔订单)</div>
<script>
$(document).ready(function(){
	<?php if(isset($_GET['state']) && $_GET['state'] == 'n'): ?>
	$("#menu_ret a").attr("class","current");
	<?php else: ?>
	$("#menu_om a").attr("class","current");
	<?php endif; ?>
	$("#adm_log_list tbody tr:odd").addClass("tralt_b");
	$("#select_all").toggle(function () {$(".ids").attr("checked", "checked");},function () {$(".ids").removeAttr("checked");});
	$("#adm_log_list tbody tr")
		.mouseover(function(){$(this).addClass("trover");$(this).find("span").show();})
		.mouseout(function(){$(this).removeClass("trover");$(this).find("span").hide();});
	var filterByFstate = "<?php echo $filterByFstate; ?>";
	switch(filterByFstate){
		case '': $("#filterByFstate").val("");break;
		case 'u': $("#filterByFstate").val("u");break;
		case 'n': $("#filterByFstate").val("n");break;
		case 'f': $("#filterByFstate").val("f");break;
	}
	var filterByState = "<?php echo $filterByState; ?>";
	switch(filterByState){
		case 'i': $("#filterByState").val("i");break;
		case 'r': $("#filterByState").val("r");break;
	} 
});

$("#filterByFstate,#filterByState").change(function(){
	$("#form_filter").submit();
});

$("#updateFs").change(function(){
	if($(this).val() == 'nosel'){
		return false;
	}else{
		logact('updatefs');
		$(this).val('nosel');
	}
});

$("#updatePay").keydown(function(event){
	if(event.which == 13){
		event.preventDefault();
	}else if(checkey(event.which, 'pay')){
		return true;
	}else{
		return false;
	}
});
</script>
