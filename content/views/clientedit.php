<?php if(!defined('JCORDER_ROOT')) {exit('error!');}?>
<div class=containertitle><b>修改客户资料</b>
<?php if(isset($_GET['error_phone'])):?><span class="error">客户电话不合法</span><?php endif;?>
<?php if(isset($_GET['error_exist'])):?><span class="error">该客户电话已存在</span><?php endif;?>
<?php if(isset($_GET['error_des'])):?><span class="error">描述过长，请限制在255个字符内</span><?php endif;?>
</div>
<div class=line></div>
<form action="client.php?action=update" method="post">
<div class="item_edit">
    <li>
	<select name="sorc" id="sorc" class="input">
		<option value="s" <?php echo $exs; ?>>个人</option>
		<option value="c" <?php echo $exc; ?>>公司</option>
	</select>
	</li>
	<li><input type="text" value="<?php echo $phone; ?>" name="ophone" style="display:none;" class="input" /></li>
	<li><input type="text" value="<?php echo $cname; ?>" name="cname" style="width:200px;" class="input" /> 姓名</li>
	<li><input type="text" value="<?php echo $phone; ?>" name="phone" style="width:200px;" class="input" /> 电话</li>
	<li><input type="text"  value="<?php echo $email; ?>" name="email" style="width:200px;" class="input" /> 电子邮件</li>

    <li>性别
		<input type="radio" name="sex" value="m" <?php echo $exm; ?>/> 男
		<input type="radio" name="sex" value="f" <?php echo $exf; ?>/> 女
	</li>

	<li>个人描述<br />
	<textarea name="description" rows="5" style="width:260px;" class="textarea"><?php echo $description; ?></textarea></li>
	<li>
	<input type="hidden" value="<?php echo $cid; ?>" name="cid" />
	<input type="submit" value="保 存" class="button" />
	<input type="button" value="取 消" class="button" onclick="window.location='client.php';" /></li>
</div>
</form>
<script>
$(document).ready(function(){
	$("#menu_pm a").attr("class","current");
});
setTimeout(hideActived,2600);
$("#menu_user").addClass('sidebarsubmenu1');
</script>