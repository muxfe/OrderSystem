<?php if(!defined('JCORDER_ROOT')) {exit('error!');}?>
<script>setTimeout(hideActived,2600);</script>
<div class="containertitle"><b>基本设置</b>
<?php if(isset($_GET['activated'])):?><span class="actived">设置保存成功</span><?php endif;?>
</div>
<form action="configure.php?action=mod_config" method="post" name="input" id="input">
  <table cellspacing="8" cellpadding="4" width="95%" align="center" border="0">
      <tr>
        <td width="18%" align="right">站点标题：</td>
        <td width="82%"><input maxlength="200" style="width:390px;" class="input" value="<?php echo $site_title; ?>" name="site_title" /></td>
      </tr>
      <tr>
        <td align="right">站点地址：</td>
        <td><input maxlength="200" style="width:390px;" class="input" value="<?php echo $site_url; ?>" name="site_url" /></td>
      </tr>
      <tr>
        <td align="right">订单标题：</td>
        <td><input maxlength="255" style="width:390px;" class="input" value="<?php echo $order_title; ?>" name="order_title" /></td>
      </tr>      
      <tr>
        <td align="right">每页显示：</td>
        <td><input maxlength="5" size="4" class="input" value="<?php echo $index_ordernum; ?>" name="index_ordernum" />笔订单</td>
      </tr>
      <tr>
        <td align="right">每页显示：</td>
        <td><input maxlength="5" size="4" class="input" value="<?php echo $perpage_cp; ?>" name="perpage_cp" />客户/货物数</td>
      </tr>
      <tr>
        <td align="right" width="18%" valign="top">订单底部信息：<br /></td>
        <td width="82%">
          <textarea name="order_info" cols="" rows="6" class="textarea" style="width:386px;"><?php echo $order_info; ?>
          </textarea><br />
        </td>
      </tr>
  <div class="setting_line"></div>
  <table cellspacing="8" cellpadding="4" width="95%" align="center" border="0">
      <tr>
        <td align="right" width="18%" valign="top">首页底部信息：<br /></td>
        <td width="82%">
      		<textarea name="footer_info" cols="" rows="6" class="textarea" style="width:386px;"><?php echo $footer_info; ?>
          </textarea><br />
        </td>
      </tr>
  </table>
  <div class="setting_line"></div>
  <table cellspacing="8" cellpadding="4" width="95%" align="center" border="0">
      <tr>
        <td align="center" colspan="2">
            <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
            <input type="submit" value="保存设置" class="button" />
        </td>
      </tr>
  </table>
</form>
<script>
$(document).ready(function(){
  $("#menu_conf a").attr("class","current");
});
</script>