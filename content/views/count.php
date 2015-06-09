<?php if(!defined('JCORDER_ROOT')) {exit('error!');} ?>
<div class="containertitle"><b>财务统计</b>
<?php if(isset($_GET['error_date'])):?><span class="error">日期格式错误</span><?php endif;?>
</div>
<div class="line"></div>
<div class="filters">
<div id="f_title">
	<div style="float:left; margin-top:8px;">
		<form action="count.php?" method="get" id="form_filter" name="form_filter">
			选择指定日期：
			<select name="year" id="year">
				<?php for($i = $start_year; $i <= $cur_year; $i++): ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php endfor; ?>
			</select>年
			<select name="month" id="month">
				<option value="">按年</option>
				<?php for($i = 1; $i <= 12; $i++): ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php endfor; ?>
			</select>月
			<select name="day" id="day"></select>日
			<input type="submit" value="点击查询"></input>
		</form>
	<div style="clear:both"></div>
</div>
</div>
<div style="clear:both"></div>
<form action="order.php?action=operate_order" method="post" name="form_log" id="form_log">

  	<table width="100%" id="adm_log_list" class="item_list" style="height:30px;">
  	<thead>
      <tr >
      	<th width="100" >订货单</th>
		<th width="100">订单数</th>
        <th width="100">已完成</th>
		<th width="100">未完成</th>
		<th width="100">交易额</th>
		<th width="100">实付款</th>
        <th width="100">欠付款</th>
        <th width="100">合  计</th>
      </tr>
  	</thead>
  	<tbody>
      <tr>
	      <td><b><?php echo $date; ?></b></td>
		  <td><?php echo $iOrderNum; ?></td>
	      <td><?php echo $iFOrderNum; ?></td>
	      <td><?php echo $iNUOrderNum; ?></td>
	      <td>￥<?php echo $iOrderSum; ?></td>      
	      <td>￥<?php echo $iOrderPay; ?></td>
	      <td>￥<?php echo $iOrderNotPay; ?></td>
		  <td>￥<?php echo $iOrderPay + $iOrderNotPay; ?></td>
      </tr>
      <tr>
      	<th>退货单</th>
		<th>退单数</th>
        <th>已完成</th>
		<th>未完成</th>
		<th>退款额</th>
		<th>实退款</th>
        <th>欠退款</th>
        <th></th>
      </tr>
      <tr>
	      <td><b><?php echo $date; ?></b></td>
		  <td><?php echo $rOrderNum; ?></td>
	      <td><?php echo $rFOrderNum; ?></td>
	      <td><?php echo $rNUOrderNum; ?></td>
	      <td>￥<?php echo $rOrderSum; ?></td>      
	      <td>￥<?php echo $rOrderPay; ?></td>
	      <td>￥<?php echo $rOrderNotPay; ?></td>
		  <td>￥<?php echo $rOrderPay + $rOrderNotPay; ?></td>
      </tr>
      <tr>
	      <td><b>合  计</b></td>
		  <td><b><?php echo $iOrderNum + $rOrderNum; ?></b></td>
	      <td><b><?php echo $iFOrderNum + $rFOrderNum; ?></b></td>
	      <td><b><?php echo $iNUOrderNum + $rNUOrderNum; ?></b></td>
	      <td><b>￥<?php echo $iOrderSum - $rOrderSum; ?></b></td>      
	      <td><b>￥<?php echo $iOrderPay - $rOrderPay; ?></b></td>
	      <td><b>￥<?php echo $iOrderNotPay - $rOrderNotPay; ?></b></td>
		  <td><b>￥<?php echo $iOrderPay - $rOrderPay - $rOrderNotPay + $iOrderNotPay; ?></b></td>
      </tr>
	</tbody>	
	</table>
    <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
	<div class="list_footer">
	</div>
</form>
<div class="page"></div>
<script>
$(document).ready(function(){
	$("#menu_ct a").attr("class","current");
	$("#adm_log_list tbody tr:odd").addClass("tralt_b");
	$("#adm_log_list tbody tr")
		.mouseover(function(){$(this).addClass("trover");$(this).find("span").show();})
		.mouseout(function(){$(this).removeClass("trover");$(this).find("span").hide();});

	$("#month").find("option[value='<?php echo isset($_GET['month']) ? intval($_GET['month']) : intval($cur_month); ?>']").attr("selected", true);
	countDays();
});

$("#month,#year").change(function(){
	countDays();
});

function countDays(){
	$("#day option").remove();
	var i;
	days = getdays($("#year").val(), $("#month").val());
	$("#day").append('<option value="">按月</option>');
	for(i = 1; i <= days; i++){
		$("#day").append('<option value="'+i+'">'+i+'</option>');
	}	
	$("#day").find("option[value='<?php echo isset($_GET['day']) ? intval($_GET['day']) : intval($cur_day); ?>']").attr("selected", true);
}

</script>
