<?php if(!defined('JCORDER_ROOT')) {exit('error!');}?>
<div class=containertitle><b>货物管理</b>
<?php if(isset($_GET['active_del'])):?><span class="actived">删除成功</span><?php endif;?>
<?php if(isset($_GET['active_update'])):?><span class="actived">修改货品资料成功</span><?php endif;?>
<?php if(isset($_GET['active_add'])):?><span class="actived">添加货物成功</span><?php endif;?>
<?php if(isset($_GET['error_exist'])):?><span class="error">该货物已存在</span><?php endif;?>
<?php if(isset($_GET['error_pname_price'])):?><span class="error">货物名称或单价不能为空</span><?php endif;?>
</div>

<div style="clear:both"></div>
<div class=line></div>
<form action="product.php" method="post" name="form" id="form">
  <table width="100%" id="adm_comment_list" class="item_list">
  	<thead>
      <tr>
        <th width="210">名称</th>
        <th width="180"><b><a href="./product.php?sortPrice=<?php echo $sortPrice; ?>">单价</a></b></th>
        <th width="120">单位</th>
        <th width="300"><b>描述</b></th>
        <th width="100"><b>添加者</b></th>
      </tr>
    </thead>
    <tbody>
	<?php
		if($products):
		foreach($products as $key => $val):
	?>
     <tr>
        <td><?php echo $val['pname']; ?>	
        	<span style="display:none; margin-left:8px;">
			<a href="product.php?action=edit&pid=<?php echo $val['pid']?>">编辑</a> 
			<a href="javascript: em_confirm(<?php echo $val['pid']; ?>, 'product', '');" class="care">删除</a>
			</span>
		</td>
		<td><?php echo $val['price'] == '' ? '0.00' : $val['price']; ?></td>
		<td>
			<?php 
				switch($val['danwei']){
					case 'j': echo '件';break;
					case 'g': echo '个';break;
					case 'z': echo '张';break;
					case 'k': echo '块';break;
					case 'p': echo '片';break;
				} 
			?>
		</td>
		<td><?php echo $val['description']; ?></td>
		<td><?php echo $val['username']; ?></td>
     </tr>
	<?php endforeach;else:?>
	  <tr><td class="tdcenter" colspan="6">还没有添加货物</td></tr>
	<?php endif;?>
	</tbody>
  </table>
</form>
<div class="page"><?php echo $pageurl; ?>(有<?php echo $productNum; ?>类产品)</div> 
<form action="product.php?action=new" method="post">
<div style="margin:30px 0px 10px 0px;"><a href="javascript:displayToggle('product_new', 2);">添加货物+</a></div>
<div id="product_new" class="item_edit" style="display:none;">
	<li><input name="pname" type="text" id="pname" value="" style="width:180px;" class="input" /> 名称</li>
	<li><input name="price" type="text" id="price" value="" style="width:180px;" class="input" /> 单价</li>
    <li>计价单位：
		<select name="danwei" id="danwei" class="input">
			<option value="j">件</option>
			<option value="g" selected="selected">个</option>
			<option value="k">块</option>
			<option value="p">片</option>
			<option value="z">张</option>
		</select>
	</li>
	<li><input type="submit" name="" value="添加货物" class="button" /></li>
</div>
</form>
<script>
$("#user_new").css('display', $.cookie('em_user_new') ? $.cookie('em_user_new') : 'none');
$(document).ready(function(){
	$("#menu_pm a").attr("class","current");
	$("#adm_comment_list tbody tr:odd").addClass("tralt_b");
	$("#adm_comment_list tbody tr")
		.mouseover(function(){$(this).addClass("trover");$(this).find("span").show();})
		.mouseout(function(){$(this).removeClass("trover");$(this).find("span").hide();})
});
setTimeout(hideActived,2600);

$("#price").keydown(function(event){
	if(checkey(event.which, 'pay')){
		return true;
	}
	return false;
});

</script>