function getChecked(node) {
	var re = false;
	$('input.'+node).each(function(i){
		if (this.checked) {
			re = true;
		}
	});
	return re;
}

function em_confirm (id, property, token) {
	switch (property){
		case 'client':
			var urlreturn="client.php?action=del&cid="+id;
			var msg = "你确定要删除该名客户吗？";break;
		case 'product':
			var urlreturn="product.php?action=del&pid="+id;
			var msg = "你确定要删除该件产品吗？";break;
		case 'user':
			var urlreturn="user.php?action=del&uid="+id;
			var msg = "你确定要删除该用户吗？";break;
		case 'order':
			var urlreturn="order.php?action=del&oid="+id;
			var msg = "你确定要删除该订单吗？";break;
	}
	if(confirm(msg)){window.location = urlreturn + "&token="+token;}else {return;}
}

function focusEle(id){try{document.getElementById(id).focus();}catch(e){}}
function hideActived(){
	$(".actived").hide();
	$(".error").hide();
}

function displayToggle(id, keep){
	$("#"+id).toggle();
	if (keep == 1){$.cookie('em_'+id,$("#"+id).css('display'),{expires:365});}
	if (keep == 2){$.cookie('em_'+id,$("#"+id).css('display'));}
}

//计算最终总价
function countSum(){
	var sum = 0;
	$(".jc_onesum").each(function(){
		sum = accadd(sum, parseFloat($(this).html()));
	});
	$("#sum").val(sum);	
}

//判断按键
function checkey(key, func){
	if((key > 47 && key < 58) || (key > 95 && key < 106)){
		return true;
	}else if(key == 8 || key == 13){
		return true;
	}
	if(func == 'phone'){
		if(key == 109 || key == 173 || key == 189){
			return true;
		}
	}else if(func == 'pay'){
		if(key == 110 || key == 190){
			return true;
		}
	}
	return false;
}

//解决js浮点数乘法精度问题
function accmulti(a, b){
	var m = 0, s1 = a.toString(), s2 = b.toString();
	try{m += s1.split(".")[1].length;}catch(e){}
	try{m += s2.split(".")[1].length;}catch(e){}
	return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m);
}

//解决加法精度问题
//出现问题：参数若不为浮点数 结果报错，因为 split(".")函数执行失败
//待解决*****************************************************
function accadd(a, b){
	var r1 = a.toString(), r2 = b.toString(), m = 0;
	try{r1 = r1.split(".")[1].length;}catch(e){r1 = 0;}
	try{r2 = r2.split(".")[1].length;}catch(e){r2 = 0;}
	m = Math.pow(10,Math.max(r1,r2));
	return (a*m+b*m)/m;
}

//判断复选框是否选中
function checked(obj){
	if($(obj).parent().parent().prev().find("input.ids").attr("checked") == "checked"){
		return true;
	}else{
		return false;
	}
}
//计算总价
function sum(obj){
	if(checked($(obj))){
		var num = parseInt($(obj).val());
		if(isNaN(num)){
			num = 0;
			$(obj).val(0);
		}
		var price = parseFloat($(obj).parent().parent().next().html());
		$(obj).parent().parent().next().next().next().html(accmulti(num, price));		
	}else{
		$(obj).val(0);
	}
	countSum();	
}

function sumByClick(obj, symbol){
	if(checked($(obj))){
		var num = parseInt($(obj).siblings("input").val());
		if(num < 10000){
			var newnum, newsum, price = parseFloat($(obj).parent().parent().next().html());
			if(symbol == '-'){
				newnum = num - 1;
			}else if(symbol == '+'){
				newnum = num + 1;
			}
			$(obj).siblings("input").val(newnum);
			$(obj).parent().parent().next().next().next().html(accmulti(newnum, price));
		}
	}else{
		$(obj).siblings("input").val(0);
		$(obj).parent().parent().next().next().next().html(0);
	}
	countSum();	
}

function logact(act){
	if (getChecked('ids') == false) {
		alert('请选择要操作的订单');
		return;
	}
	if(act == 'cancel' && !confirm('你确定要删除所选订单吗？')){return;}
	if(act == 'del' && !confirm('你确定要彻底删除所选订单吗？')){return;}
	if(act == 'recycle' && !confirm('你确定要恢复所选订单吗？')){return;}
	if(act == 'updatefs' && !confirm('你确定要修改所选订单的付款状态吗？')){return;}
	if(act == 'updatepay' && !confirm('你确定要修改所选订单的付款金额吗？')){return;}
	$("#operate").val(act);
	$("#form_log").submit();
}

function getdays(year, month){
	var leapyear = (year % 4 == 0 && year % 100 != 0) || (year % 400 == 0) ? 1 : 0;
	var md = [0,31,28,31,30,31,30,31,31,30,31,30,31];
	return month == 2 ? (md[2] + leapyear) : md[month];
}
