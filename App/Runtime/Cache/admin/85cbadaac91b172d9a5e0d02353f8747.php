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
	<form action="__URL__/doAdd" method="POST" id="mainform" >
	<table class="edit" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" class="full"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th align="right" width="25%">用 户 名：</td>
			<td>
				<div class="control-group" style="margin-bottom:0px;">
					<div class="controls">
						<input name="username" type="text" value="<?php echo ($vo["username"]); ?>" min="3" max="10" maxLength="10"  require="true" datatype="require" msg="重要！请填写用户名" />
						<span class="help-inline">
							<span id="success_username" style="display:none;"> <span class="ico_cue_ok"></span> </span>
							<span class="error_username" style="position: relative;display:none;"> <span class="ico_cue_no"></span><span id="error_username"></span></span>
						</span>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<th align="right" >密　码：</th>
			<td>
				<div class="control-group" style="margin-bottom:0px;">
					<div class="controls">
						<input name="password" type="password" value="" require="true"  datatype="limit"  min="6" max="16" msg="密码由6-16个字符组成"/> 
						<span class="help-inline">
							<span id="success_password" style="display:none;"> <span class="ico_cue_ok"></span> </span>
							<span class="error_password" style="position: relative;display:none;"> <span class="ico_cue_no"></span><span id="error_password"></span></span>
						</span>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<th align="right" >确认密码：</th>
			<td><input name="password2" type="password" value=""  require="true" value="" datatype="repeat|limit"  min="6" max="16" to="dev_password" msg="两次输入的密码须一样|密码由6-16个字符组成"/> 
				<span class="help-inline">
							<span id="success_password2" style="display:none;"> <span class="ico_cue_ok"></span> </span>
							<span class="error_password2" style="position: relative;display:none;"> <span class="ico_cue_no"></span><span id="error_password2"></span></span>
						</span>
			</td>
		</tr>
		<tr>
			<th align="right"  width="25%">真实姓名：</td>
			<td><input name="contact_person" type="text" value="<?php echo ($vo["contact_person"]); ?>"  /></td>
		</tr>
		<tr>
			<th align="right" >部　门：</th>
			<td>
				<select name="gid" id="select">
					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><option value="<?php echo ($row["id"]); ?>" <?php if(($row["id"]) == $vo['gid']): ?>selected<?php endif; ?>><?php echo ($row["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
		 </td>
		</tr>
		<tr>
			<th align="right" >描　述：</th>
			<td><input name="remark" type="text" value="<?php echo ($vo["remark"]); ?>"  /></td>
		</tr>
		<tr>
			<th align="right" >状　态：</th>
			<td>
				<input type="radio" name="status" value="1" checked=checked />启用
				<input type="radio" name="status" value="0" />锁定
			</td>
		</tr>
	</tbody>
		<tfoot>
		<tr>
			<td colspan="3" align="center">
				<input type="submit" class="btn btn-primary" name="Submit" value="修改" />
				<input type="button" class="btn btnClose" name="add" value="返回" />
			</td>
		</tr>
		</tfoot>
	</table>
	</form>
</div>

<!-- 主页面结束 -->

<!-- 版权信息区域 -->
</BODY>
</HTML>