<?php if(!defined('JCORDER_ROOT')) {exit('error!');}?>
<div class=containertitle><b>编辑订单</b>
<?php if(isset($_GET['error_phone'])):?><span class="error">客户电话不能为空或电话不合法</span><?php endif;?>
<?php if(isset($_GET['error_num'])):?><span class="error">数字不合法</span><?php endif;?>
<?php if(isset($_GET['error_data'])):?><span class="error">数据不合法</span><?php endif;?>
<?php if(isset($_GET['error_des'])):?><span class="error">备注过长,请限制在120个字符之内</span><?php endif;?>
</div>
<div class=line></div>
<form action="new_order.php?action=save&srcedit=1" method="post" id="form_order">
<div class="item_edit">
    <li>客户电话：<input type="text" value="<?php echo $phone; ?>" name="phone" id="phone" class="input" maxlength="12"/></li>
    <li>订单类型：
	<select name="type" id="type" class="input">
		<option value="i" <?php echo $exi; ?>>订货单</option>
		<option value="r" <?php echo $exr; ?>>退货单</option>
	</select>

	</li>
	<li>	
		<span>订单状态：<b style="color:<?php echo $stateColor; ?>"><?php echo $state == 'y' ? '正常' : '作废'; ?></b>
			&nbsp;&nbsp;付款状态：<b style="color:<?php echo $fstateColor; ?>">
				<?php switch($fstate){
						case 'u': echo '未支付';break;case 'f': echo '已付';break;case 'n': echo '未全付';break;
				}; ?></b>
			&nbsp;&nbsp;订单时间：<b><?php echo $date; ?></b> 
			&nbsp;&nbsp;订单最新修改时间：<b><?php echo $chgdate; ?></b>
		</span>
	</li>
	<li>货物列表：
	<table width="100%" id="adm_comment_list" class="item_list">
	  	<thead>
	      <tr>
	        <th width="300"><b>项目</b></th>
	        <th width="150"><b>数量</b></th>
	        <th width="100"><b>单价</b></th>
	        <th width="100"><b>单位</b></th>
	        <th width="100"><b>合计</b></th>
	        <th width="100"><b>备注</b></th>
	      </tr>
	    </thead>
	    <tbody>
		<?php
			if($products):
			$j = 0;
			foreach($products as $key => $val):	
				$checked = '';
				if($val['pid'] == $pid[$j]){ $checked .= 'checked="checked"'; }
		?> 
	    <tr>
	        <td>
	        	<input type="checkbox" name="pidck[]" value="<?php echo $val['pid']; ?>" class="ids" <?php echo $checked; ?>/>
	        	<?php echo $val['pname']; ?>
	        </td>
			<td><!-- 数量 -->
				<div class="jc_amount">
					<a class="jc_minus" href="#" style="font-size:14px;">减</a>
					<input name="pnum[]" type="text" class="input num" style="width:40px;text-align:center;" value="<?php echo empty($checked) ? '0' : $pnum[$j]; ?>" maxlength="4">
					</input>
					<a class="jc_plus" href="#" style="font-size:14px;">加</a>
				</div>
			</td>
			<td><?php echo $val['price'] == '' ? '0' : $val['price']; ?></td>
			<td>
				<?php 
					switch($val['danwei']){
						case 'j': echo '件';break;case 'g': echo '个';break;
						case 'z': echo '张';break;case 'k': echo '块';break;
						case 'p': echo '片';break;
					}
				?>
			</td>
			<!-- 单项合计 -->
			<td class="jc_onesum"></td>
			<td><input name="pdescreption[]" type="text" class="input" value="<?php echo empty($checked) ? '' : $pdescreption[$j++]; ?>" style="width:150px;"></input></td>
	    	<input name="pid[]" type="hidden" value="<?php echo $val['pid']; ?>"></input>
	    </tr>
		<?php endforeach;else:?>
		<tr><td class="tdcenter" colspan="6">空订单</td></tr>
		<?php endif;?>
 	    <tr>
	        <td width="200"><b>合计</b></td>
	        <td width="150"></td>
	        <td width="100"></td>
	        <td width="100"></td>
	        <td width="200">
	        	<input name="sum" id="sum" value="<?php echo $sum; ?>" class="input" readonly="readonly" style="width:100px;"></input>
	        	<input name="oid" id="oid" value="<?php echo $oid; ?>" type="hidden"></input>
	        </td>
	        <td width="100"></td>
      	</tr>		
		</tbody>
	</table>
	</li>
	<li>订单描述：<input name="descreption" id="descreption" value="<?php echo $descreption; ?>" class="input" style="width:600px;"></input></li>
	<li id="payshow"><span>客户支付：</span>
		<input name="pay" id="pay" value="<?php echo $pay; ?>" class="input" style="width:100px;">
		<?php echo $fstate == 'f' ? '' : '(还欠 ￥'.($sum-$pay).' 未付)' ?></input>
	</li>
	<li>
		<input type="submit" value="保 存" class="button" />
		<input type="button" value="取 消" class="button" onclick="window.location='order.php';" />
	</li>
</div>
</form>
<script>
$(document).ready(function(){
	$("#menu_om a").attr("class","current");
	$(".jc_onesum").each(function(){
		$(this).html(0);
	});

	$(".input.num").each(function(){
		sum($(this));
	});	
	$("#adm_comment_list tbody tr:odd").addClass("tralt_b");
	$("#select_all").toggle(function () {$(".ids").attr("checked", "checked");},function () {
		$(".ids").removeAttr("checked").each(function(){
			$(this).parent().next().next().next().next().html(0);
		});
		countSum();
	});

	if($("#type").val() == 'r'){
		$("#payshow span").html('已 退 款： ');
	}else{
		$("#payshow span").html('客户支付：');
	}
	
});
setTimeout(hideActived,2600);
$("#menu_user").addClass('sidebarsubmenu1');

//更换订/退货单文字
$("#type").change(function(){
	if($(this).val() == 'r'){
		$("#payshow span").html('已 退 款： ');
	}else{
		$("#payshow span").html('客户支付：');
	}
});

//货物数量减按钮
$(".jc_minus").click(function(){
	sumByClick($(this), '-');	
});
//加
$(".jc_plus").click(function(){
	sumByClick($(this), '+');	
});

$("input.ids").change(function(){
	if(($(this).attr("checked") == "checked") == false){
		$(this).parent().next().next().next().next().html(0);
		countSum();
	}
});

//实时计算总价
$(".input.num").keyup(function(){
	sum($(this));
}).blur(function(){
	sum($(this));
});

//表单验证
$("#form_order").submit(function(){
	if(isNaN($("#pay").val())){
		$("#pay").val(0);
	}
});

//验证电话
$("#phone").keydown(function(event){
	if(checkey(event.which, 'phone')){ 
		return true;
	}
	return false;
});

$(".input.num,#pay").keydown(function(event){
	if(checkey(event.which, 'pay')){
		return true;
	}
	return false;
});
</script>
