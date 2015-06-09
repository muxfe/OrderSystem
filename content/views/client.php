<?php if(!defined('JCORDER_ROOT')) {exit('error!');}?>
<div class=containertitle><b>客户管理</b>
<?php if(isset($_GET['active_del'])):?><span class="actived">删除成功</span><?php endif;?>
<?php if(isset($_GET['active_update'])):?><span class="actived">修改客户资料成功</span><?php endif;?>
<?php if(isset($_GET['active_add'])):?><span class="actived">添加客户成功</span><?php endif;?>
<?php if(isset($_GET['error_exist'])):?><span class="error">该客户电话已存在</span><?php endif;?>
<?php if(isset($_GET['error_phone'])):?><span class="error">电话格式不正确</span><?php endif;?>
</div>

<div id="f_title">
	<div style="float:left; margin-top:8px;">
		<form action="client.php?" method="get" name="form_sea" id="form_sea">
			<span><a href="client.php?">全部&nbsp;&nbsp;</a></span>
			<input name="search" type="text" id="search" style="width:100px;height:10px;" class="input" maxlength="12">(按电话搜索)</input>
		</form>
	<div style="clear:both"></div>
</div>
<div style="clear:both"></div>
<div class=line></div>
<form action="client.php" method="post" name="form" id="form">
  <table width="100%" id="adm_comment_list" class="item_list">
  	<thead>
      <tr>
        <th width="110">客户</th>
        <th width="200"><a href="./client.php?sortPhone=<?php echo $sortPhone; ?>">电话</a></th>
        <th width="250">描述</th>
        <th width="220">电子邮件</th>
        <th width="50">性别</th>
        <th width="50">订单数</th>
        <th width="50">退单数</th>
      </tr>
    </thead>
    <tbody>
	<?php
		if($clients):
		foreach($clients as $key => $val):
	?>
     <tr>
        <td><?php echo $val['cname']; ?></td>
		<td>
		<?php echo $val['phone']; ?><br />
		<?php echo $val['sorc'] == 's' ? '个人' : '公司'; ?>
		<span style="display:none; margin-left:8px;">
		<a href="client.php?action=edit&cid=<?php echo $val['cid']?>">编辑</a> 
		<a href="javascript: em_confirm(<?php echo $val['cid']; ?>, 'client', '');" class="care">删除</a>
		</span>
		</td>
		<td><?php echo $val['description']; ?></td>
		<td><?php echo $val['email']; ?></td>
		<td><?php echo $val['sex'] == 'm' ? '男' : '女'; ?></td>
		<td><a href="./order.php?filterByPhone=<?php echo $val['phone']; ?>"><?php echo $val['conum'] == '' ? '0' : $val['conum']; ?></a></td>
		<td><a href="./order.php?filterByState=r&filterByPhone=<?php echo $val['phone']; ?>"><?php echo $val['retnum'] == '' ? '0' : $val['retnum']; ?></a></td>
     </tr>
	<?php endforeach;else:?>
	  <tr><td class="tdcenter" colspan="6">还没有添加客户</td></tr>
	<?php endif;?>
	</tbody>
  </table>
</form>
<div class="page"><?php echo $pageurl; ?>(有<?php echo $clientNum; ?>位客户)</div> 
<form action="client.php?action=new" method="post">
<div style="margin:30px 0px 10px 0px;"><a href="javascript:displayToggle('client_new', 2);">添加客户+</a></div>
<div id="client_new" class="item_edit" style="display:none;">
    <li>
	<select name="sorc" id="sorc" class="input">
		<option value="s">个人</option>
		<option value="c">公司</option>
	</select>
	</li>
	<li><input name="phone" type="text" id="phone" value="" style="width:180px;" class="input" /> 电话</li>
	<li><input name="cname" type="text" id="cname" value="" style="width:180px;" class="input" /> 姓名</li>
	<li><input type="submit" name="" value="添加客户" class="button" /></li>
</div>
</form>
<script>
$("#user_new").css('display', $.cookie('em_user_new') ? $.cookie('em_user_new') : 'none');
$(document).ready(function(){
	$("#menu_cm a").attr("class","current");
	$("#adm_comment_list tbody tr:odd").addClass("tralt_b");
	$("#adm_comment_list tbody tr")
		.mouseover(function(){$(this).addClass("trover");$(this).find("span").show();})
		.mouseout(function(){$(this).removeClass("trover");$(this).find("span").hide();})
});
setTimeout(hideActived,2600);
$("#menu_user").addClass('sidebarsubmenu1');

//验证电话
$("#phone, #search").keydown(function(event){
	if(checkey(event.which, 'phone')){ 
		return true;
	}
	return false;
});

</script>