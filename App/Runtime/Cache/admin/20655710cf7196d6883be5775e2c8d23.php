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
<!-- 主页面区域 -->

<div id="table">
	<span class="divleft"></span>
	<span class="divright"></span>
	<form action="__URL__/doAdd" method="POST"  id="mainform">
		<table class="edit"  cellspacing="0">
			<thead>
				<tr>
					<th colspan="2" class="full">增加用户组</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<th width="20%">用户组名称</td>
					<td width="80%"><input name="name" type="text" value="" size="60"/>*</td>
				</tr>

				<tr>
					<th>组描述 </td>
					<td>
						<input name="remark" type="text" value=""/>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" class="btn"  name="Submit" value="确定新增" class="inputButton" />
						<input type="reset" class="btn" name="Reset" value="还原重填" class="inputButton" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>

<!-- 主页面结束 -->

<!-- 版权信息区域 -->
</BODY>
</HTML>