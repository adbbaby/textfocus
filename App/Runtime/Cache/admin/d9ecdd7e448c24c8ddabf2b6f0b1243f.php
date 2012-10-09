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

<div id="toolbar">
	<form action="<?php echo U();?>" method="post" class="form-inline" id="search-form">
	<div class="items first">
		<!-- 搜索条件 begin -->
		<fieldset class="searchBar">
			<a name="add" class="btn addButton dialog" w="785px" h="380px" t="30px" l="30px" href="__URL__/add/" ><i class="icon-plus"></i>新增用户组</a>
		</fieldset>
		<!-- 搜索条件 end -->
	</div>
	<div class="items fr">
		<div class="controls">
			<div class="input-append">
				<input class="span2" id="appendedPrependedInput" size="16" name="username" type="text" placeholder="组名"><input type="submit" name="submit" class="btn btn-primary"  value="查询">
			</div>
		</div>
	</div>
	</form>
</div>
<form action="__URL__/exec" method="post" class="form-inline">
<div id="table" class="fl">
	<table cellspacing="0"  class="table table-striped table-bordered table-condensed">
	<thead>
		<tr class="sortable" sort="<?php echo ($sort); ?>" sortImg="<?php echo ($sortImg); ?>" currentOrder="<?php echo ($order); ?>">
			<th  width="3%"  class="first"><input name="checkbox" type="checkbox" class="checkbox" id="selectAll" value="选择"/></th>
			<th order="id" width="5%">ID</th>
			<th order="groupName" align="left" width="15%">用户组名称</th>
			<th order="userCount" align="left" width="10%">用户数量</th>
			<th width="25%">描述</th>
			<th order="status" width="10%">状态</th>
			<th class="last">操作</th>
		</tr>
	</thead> 
	<tbody class="grid">
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr ondblclick="window.location = '__APP__/Admin/index/gid/<?php echo ($vo["id"]); ?>'">
			<td><input name="id[]" type="checkbox" value="<?php echo ($vo["id"]); ?>" class="checkbox"></td>
			<td><?php echo ($vo["id"]); ?></td>
			<td align="left"><a href="__APP__/Admin/index/gid/<?php echo ($vo["id"]); ?>"><?php echo ($vo["groupName"]); ?>&nbsp;</a></td>
			<td align="left"><a href="__APP__/Admin/index/gid/<?php echo ($vo["id"]); ?>"><?php echo ($vo["userCount"]); ?>&nbsp;</a></td>
			<td align="left"><?php echo ($vo["description"]); ?>&nbsp;</td>
			<td><?php echo getStatus($vo['status']);?></td>
			<td>
				<a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>" class="btn dialog btn-mini" w="785px" h="360px" ><i class="icon-edit"></i>编辑</a>
				<a href="__URL__/exec/act/delete/id/<?php echo ($vo["id"]); ?>" class="confirm btn btn-danger btn-mini " ><i class="icon-trash icon-white"></i>删除</a>
				<?php if(($vo['status']) == "1"): ?><a href="__URL__/exec/act/lock/id/<?php echo ($vo["id"]); ?>" class="btn btn-mini "><i class="icon-lock"></i>锁定</a><?php endif; ?>
				<?php if(($vo['status']) == "0"): ?><a href="__URL__/exec/act/unlock/id/<?php echo ($vo["id"]); ?>"  class="btn"><i class="icon-unlock"></i>解锁</a><?php endif; ?>
				<a href="__APP__/Permissions/index/id/<?php echo ($vo["id"]); ?>" class="btn btn-mini"><i class="icon-tags"></i>权限编辑</a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
	<tfoot>
		<tr class="last">                
			<td colspan="8">
				<div class="fl">
					<select name="act" class="input-small" >
						<option value="none" selected>批量操作</option>
						<option value="unlock">解锁</option>
						<option value="lock">锁定</option>
						<option value="delete">删除</option>
					</select>
					<input type="submit" value="提交操作" class="btn" id="submitButton" />
				</div>
				<div class="select green-black">
					<?php echo ($page); ?>
				</div>
			</td>
		</tr>
	</tfoot>
	</table>
</div>
</form>
<div class="clear"></div>
<!-- 主页面结束 -->

<!-- 版权信息区域 -->
</BODY>
</HTML>