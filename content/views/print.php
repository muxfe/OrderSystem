<?php if(!defined('JCORDER_ROOT')) {exit('error!');} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<meta name="author" content="xiaomu" />
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<title>订单打印</title>

<style type="text/css">
#body {width:600px;}
#main {width:600px;}

#header {text-align: center; font-family: '楷体', sans-serif; font-size: 1.4em;}
#order_header {width:100%; text-align: left;}
#order_header ul {list-style: none;}
#order_table {font-family: '宋体', sans-serif; border:solid 1px #666;  width:100%;text-align:center;}
#order_table td, #order_table th {  font-size:0.8em; border:solid 1px #666; padding:8px;}
#order_table th {font-size:0.8em; border:solid 1px #666; padding:8px; }
#footer {text-align: center; font-family: '楷体', sans-serif; font-size: 1em;}
#noprint {text-align: center;}
</style>

<script type="text/javascript">
function doprint(){
	if(confirm('确定打印该订单吗？')){
		document.getElementById('noprint').style.display = 'none';
		window.print();
	}
}
</script>
</head> 

<body>

<div id="main">
<div id="header">
<p id="order_title"><?php echo $order_title; ?></p>
</div>
<div id="container">
	<div id="order_header">
		<ul>
			<li style="float:left">客户名称：<?php echo $client.' '.$phone; ?></li>
			<li style="float:right">开票时间：<?php echo $date; ?></li>
		</ul>
	</div>

	<div id="order_table" style="clear:both">
		<table id="order_list" cellspacing="0">
			<thead>
				<tr>
					<th width="150">项目</th>
					<th width="100">数量</th>
					<th width="100">金额</th>
					<th width="100">合计</th>
					<th width="100">备注</th>
				</tr>
			</thead>
			<tbody>
				<?php if($products):
					  foreach($products as $key => $val):
				?>
				<tr>
					<td><?php echo $val['pname']; ?></td>
					<td><?php echo $pnum[$key] ? $pnum[$key] : '&nbsp;'; ?></td>
					<td><?php echo $val['price']; ?></td>
					<td><?php echo $pnum[$key] ? ($pnum[$key] * $val['price']) : '&nbsp;'; ?></td>
					<td><?php echo $pdescreption[$key]; ?></td>
				</tr>
				<?php endforeach; endif; ?>
				<tr>
					<td><b>合计</b></td>
					<td></td>
					<td></td>
					<td><?php echo $sum ? $sum : '0.00'; ?></td>
					<td></td>
				</tr>				
			</tbody>
		</table>
	</div>

</div>
<div id="footer">
	<p id="order_info"><?php echo $order_info; ?></p>
</div>
<div id="noprint">
	<input type="button" value="打印订单" onclick="doprint()"></input>
	<input type="button" value="取消" onclick="javascript:window.location='./order.php'"></input>
</div>
</div>
</body>
</html>