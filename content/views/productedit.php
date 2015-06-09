<?php if(!defined('JCORDER_ROOT')) {exit('error!');}?>
<div class=containertitle><b>修改货品资料</b>
<?php if(isset($_GET['error_exist'])):?><span class="error">该货物已存在</span><?php endif;?>
<?php if(isset($_GET['error_pname_price'])):?><span class="error">货物名称或单价不能为空</span><?php endif;?>
<?php if(isset($_GET['error_des'])):?><span class="error">描述过长，请限制在255个字符内</span><?php endif;?>
</div>
<div class=line></div>
<form action="product.php?action=update" method="post">
<div class="item_edit">
	<li><input type="text" value="<?php echo $pname; ?>" name="opname" style="display:none;" class="input" /></li>
	<li><input type="text" value="<?php echo $pname; ?>" name="pname" style="width:200px;" class="input" /> 名称</li>
	<li><input type="text" value="<?php echo $price; ?>" name="price" style="width:200px;" class="input" /> 单价</li>
    <li>计价单位：
		<select name="danwei" id="danwei" class="input">
			<option value="j" <?php echo $exj; ?>>件</option>
			<option value="g" <?php echo $exg; ?>>个</option>
			<option value="k" <?php echo $exk; ?>>块</option>
			<option value="p" <?php echo $exp; ?>>片</option>
			<option value="z" <?php echo $exz; ?>>张</option>
		</select>
	</li>
	<li>货物描述<br />
	<textarea name="description" rows="5" style="width:260px;" class="textarea"><?php echo $description; ?></textarea></li>
	<li>
	<input type="hidden" value="<?php echo $pid; ?>" name="pid" />
	<input type="submit" value="保 存" class="button" />
	<input type="button" value="取 消" class="button" onclick="window.location='product.php';" /></li>
</div>
</form>
<script>
$(document).ready(function(){
	$("#menu_pm a").attr("class","current");
});
setTimeout(hideActived,2600);
$("#menu_user").addClass('sidebarsubmenu1');
</script>