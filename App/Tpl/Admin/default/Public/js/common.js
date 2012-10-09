/**
 *	@Author: Aoki
 *  @Date: 2010年5月
 *
 */

var contentDialog = '';
function success(content){
	msgShow(1,content);
}

function error(content){
	msgShow(0,content);
}

function msgShow(status,content){
	var icon ='success.gif';
	if(status != 1){
		var icon = 'error.gif';
	}
	$.dialog({
		icon: icon,
		content: content
	});
}

function clearCache(){
	$.dialog.confirm('确定要删除缓存吗？', function(){
		$.ajax({
			type: 'GET',
			url: APP+'/Public/clearCache',
			dataType: "json",
			success: function(msg){
				if(msg.status){
					success('清除成功');
				}
			}
		});
	}, function(){
		
	});
}

$(document).ready(function(){
	//$("#ps").show().click(function(){$("#ps").fadeOut("slow");});
	$("a").attr("hidefocus",true);
	//表格相关
	$("tbody tr:odd").addClass("odd");
	$("tbody tr").hover(
		function(){
			$(this).addClass("hover");
		}, function(){
			$(this).removeClass("hover");
		}
	);
	
	/*
	$(".grid tr").click(function(){
		var node = $(this);
		if (node.find("input[name=id[]]").attr("checked")) {
			node.find("input[name=id[]]").attr("checked", false);
			node.removeClass("checked");
		} else {
			node.find("input[name=id[]]").attr("checked", true);
			node.addClass("checked");
		}
	});
	*/
	
	$("input[name='id[]']").click(function(){
		if($(this).attr("checked")){
			$(this).attr("checked", true);
			$(this).parent().parent().addClass("checked");
		}else{
			$(this).attr("checked", false);
			$(this).parent().parent().removeClass("checked");
		}
	});
	
	$("#submitButton").click(function(){
		return confirm('确定提交吗');
	});
	
	$(".confirm").click(function(e){
		var $handle = $(this);
		$.dialog.confirm('确定要删除吗？删除后将不能恢复！', function(){
			$.ajax({
				type: 'GET',
				url: $handle.attr('href'),
				dataType: "json",
				success: function(msg){
					if(msg.status){
						if(msg.url){
							location = msg.url;
						}else{
							location.reload();
						}
					}else{
						error(msg.info);
					}
				}
			});
		}, function(){
			
		});
		return false;
	});

	//表格相关 ----  全选操作
	$("#selectAll").click(function() {
		if ($(this).attr("checked")) {
			$("input[name='id[]']").each(function() {
				$(this).attr("checked", true);
				$(this).parent().parent().addClass("checked");
			});
		}else {
			$("input[name='id[]']").each(function() {
				$(this).attr("checked", false);
				$(this).parent().parent().removeClass("checked");
			});
		}
	});

	$("td:contains('*') :input").parent().css('color','red');
	$("th:contains('*') :input").parent().css('color','red');
	$(".Wdate").attr('autocomplete','off');
	
	/*对话框*/
	$(".dialog").live('click',function(e){
		var title= $(this).val();
		var content= 'url:'+$(this).attr('href');
		var width=$(this).attr('w');
		var height=$(this).attr('h');
		var top = $(this).attr('t')||'30%';
		var left = $(this).attr('l')||'30%';
		contentDialog = $.dialog({id: 'appcontent',title:title, content:content, lock:true, left:left, top:top,width:width,height:height});
		e.preventDefault();
	});
	
	$("a.ajaxTodo").click(function(){
		$.dialog({
			time: 2,
			icon: 'success.gif',
			content: '我可以定义消息图标哦'
		});
		return false;
	});
	$('#mainform').checkForm();
	$('#mainform').submit(function(event){
		var r = $(this).checkForm(true);
		if(r){
			$.ajax({
				type: $(this).attr('method'),
				url: $(this).attr('action'),
				data: $(this).serialize(),
				dataType: "json",
				success: function(msg){
					if(msg.status){
						if(msg.url){
							parent.location = msg.url;
						}else{
							parent.location.reload();
						}
					}else{
						error(msg.info);
					}
				}
			});
		}
		event.preventDefault();
	});
	
	$('.btnClose').click(function(){
		parent.contentDialog.close();
		return false;
	});
	
	/* 2012-08-09 table th*/
	var _sort = $('tr.sortable').attr('sort');
	var _sortImg = $('tr.sortable').attr('sortImg');
	var _currentOrder = $('tr.sortable').attr('currentOrder');
	var _params = $('tr.sortable').attr('params');
	var _sortThHtml = '<a href="'+URL+'/index/"><span class="fl"></span><span class="sort_by asc"><i class="icon-chevron-up"></i></span><span class="sort_by desc"><i class="icon-chevron-down"></i></span></a>';
	
	$('tr.sortable th').each(function(i,n){
		var _order = $(n).attr('order');
		if(_order){	
			var _name = $(n).html();
			$(n).html(_sortThHtml);
			$(n).find('.fl').html(_name);
			var _href = $(n).find('a').attr('href');
			_href+= '_order/'+_order+'/_sort/'+_sort+'/_params/'+_params;
			$(n).find('a').attr('href',_href);
			if(_order == _currentOrder){
				$(n).find('.sort_by.'+_sortImg).show();
			}
		}
	});
	
});