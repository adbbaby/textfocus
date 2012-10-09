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
<div id="table">
	<form action="__URL__/doAdd" method="POST" id="mainform" class="form-horizontal">
	<table class="edit"	cellspacing="0">
	<thead>
		<tr>
			<th colspan="2" class="full"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th width="25%">节 点 名</th>
			<td ><input name="name" type="text" value="" size="50"/>*</td>
		</tr>
		<tr>
			<th width="25%">显 示 名</th>
			<td><input name="viewName" type="text" value=""size="50"/>*</td>
		</tr>
		<tr>
			<th width="25%">排 序</th>
			<td><input name="orders" type="text" value="" class="input-mini"/></td>
		</tr>
		<tr>
			<th>描　述</th>
			<td><input name="description" type="text" value=""size="50"/>*</td>
		</tr>
		<tr>
			<th>父节点</th>
			<td>
				<select name="pid" id="select">
					<option value="0">根节点</option>
					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $_GET['id']): ?>selected<?php endif; ?>><?php echo ($vo["viewName"]); ?></option>
					
					<?php if(!empty($vo["child"])): if(is_array($vo["child"])): foreach($vo["child"] as $key=>$sub): ?><option value="<?php echo ($sub["id"]); ?>" <?php if(($sub["id"]) == $_GET['id']): ?>selected<?php endif; ?> >&nbsp └<?php echo ($sub["viewName"]); ?></option><?php endforeach; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>是否菜单显示</th>
			<td><input type="radio" name="type" value="1" checked=true/>显示 <input type="radio" name="type" value="0"/>不显示</td>
		</tr>
			<tfoot>
            <tr class="last">
			<td colspan="2" align="center">
				<input type="submit" class="btn btn-primary"  name="Submit" value="确定新增" class="inputButton" />
				<input type="button" class="btn btnClose" value="返回" />
			</td>
			</tfoot>
		</tr>
	</tbody>
	</table>
	</form>
</div>
		</div>
</div>

<!-- 主页面结束 -->

<!-- 版权信息区域 -->
</BODY>
</HTML>