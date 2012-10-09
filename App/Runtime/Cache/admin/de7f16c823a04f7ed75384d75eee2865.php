<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo (C("APPNAME")); ?></title>
	<link href="../Public/style/reset.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
	<!--表单验证-->
	<script type="text/javascript" src="../Public/js/jquery.validator.reg.js?<?php echo time();?>"></script>
	<script type="text/javascript" src="../Public/js/common.js?<?php echo time();?>"></script>
	<script type="text/javascript" src="../Public/js/lhgDialog/lhgdialog.min.js"></script>
	<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/My97DatePicker/WdatePicker.js"></script>


	<!-- Le styles -->
	<link href="../Public/style/bootstrap.css" rel="stylesheet">
	<link href="../Public/style/bootstrap-responsive.css" rel="stylesheet">

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
		
	<link href="../Public/style/main.css?<?php echo time();?>" rel="stylesheet" type="text/css"/>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	var URL = '__URL__'; 
	var APP	 =	 '__APP__'; 
	var PUBLIC = '__PUBLIC__'; 
	var Public = '../Public/'; 
	var SELF = '__SELF__'; 
	//-->
	</SCRIPT>
</head>

<body>
<div id="container" class="container-fluid">
	<div id="header">
		<?php echo W('Breadcrumb');?>
	</div>
	<div id="content">
		<div id="table" class="small">
			<span class="divleft"></span>
			<span class="divright"></span>
			<form action="__URL__/doAdd" method="POST">
				<table class="edit"  cellspacing="0">
					<thead>
						<tr>
							<th colspan="2" class="full" style='text-align:center;'>添加配置选项</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<th width="30%" style='text-align:center;'>配置标题</th>
							<td width="70%"><input name="title" type="text" value="" />*</td>
						</tr>
						<tr>
							<th width="30%" style='text-align:center;'>所属模块</th>
							<td width="70%"><input name="module" type="text" value="" /></td>
						</tr>
						<tr>
							<th width="30%" style='text-align:center;'>控件类型</th>
							<td width="70%">
								<select name="type">
									<?php if(is_array(C("SETTING_INPUT_TYPE"))): foreach(C("SETTING_INPUT_TYPE") as $key=>$wvo): ?><option value="<?php echo ($key); ?>" class="typeoption"><?php echo ($wvo); ?></option><?php endforeach; endif; ?>
								</select>
							</td>
						</tr>

						<tr>
							<th width="30%" style='text-align:center;'>字段名</th>
							<td width="70%"><input name="name" type="text" value=""/>*</td>
						</tr>
						<tr>
							<th width="30%" style='text-align:center;'>值</th>
							<td width="70%" class='controltype'><input name="value" type="text" value=""/></td>
						</tr>
						<tr>
							<th width="30%" style='text-align:center;'>排序</th>
							<td width="70%"><input name="order" type="text" value="0" /></td>
						</tr>
						<tr>
							<th width="30%" style='text-align:center;'>描述</th>
							<td width="70%"><textarea name="description" cols="55" rows="10"></textarea></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" style='text-align:center;'>
								<input type="submit" class="btn" value="确定新增"/>
								<input type="button" class="btn" name="add" value="返回" onclick="javascript:history.go(-1);" />
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("select[name=type]").change(function(){
		var type = $(this).val();
		var textfield = '<input name="value" type="text" value=""/>';
		var toggle = '<div class="toggle ">';
			toggle += '<span class="thumb"></span>';
			toggle += '<span class="toggleOn">打开</span>';
			toggle += '<span class="toggleOff">关闭</span>';
			toggle += '<input type="hidden" name="value" value="0" id="value" />';
			toggle += '</div>';
		var multi = '<textarea name="value" rows="7" cols="40"></textarea>';
			multi += '<br/>请按 <span style="color:red;" >键:值</span> 格式输入<br/>';
			multi += '例如<br/>1:有意向<br/>2:已签单<br/>3:已付款<br/>';


		var datepicker = '<input name="value" type="text" value="" class="wdate" onclick="WdatePicker()"/>';
		switch(type){
			case '1':
				$('.controltype').html(textfield);
				break;
			case '2':
				$('.controltype').html(toggle);
				break;
			case '3':
			case '4':
			case '5':
				$('.controltype').html(multi);
				break;
			case '6':
				$('.controltype').html(datepicker);
				break;
			default:
				break;
		}
	});
});
</script>


<!-- 版权信息区域 -->
</BODY>
</HTML>