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

		<div class="filter">
			<form method="get" action="" id="list-filter">
			<ul class="subsubsub">
				<li><a class="current" href="__URL__/index">全部 <span class="count"></span></a> </li>
				<?php if(is_array($modules)): $i = 0; $__LIST__ = $modules;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a class="current" href="<?php echo U('Setting/index',array('module'=>$vo['module']));?>"><?php echo ($vo["module"]); ?></a> </li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			</form>
		</div>
		<div id="toolbar">
			<div class="items first">
				<form method="get" action="__URL__/index" id="list-filter">
				<p class="search-box">
					<input type="text" value="" name="key" class="post-search-input">
					<input type="submit" class="btn" value="搜索">
				</p>
				</form>
			</div>
			<div class="items">
				<input type="button" name="add" class="btn"  value="新增配置" onclick="javascript:window.location='__URL__/add/gid/{.gid}'" />
			</div>
		</div>
		<form action="__URL__/exec" method="post">
		<div id="table" class="fl">
			<table cellspacing="0">
			<thead>
				<tr>
					<th width="25" class="first"><input name="checkbox" type="checkbox" class="checkbox" id="selectAll" value="选择"></th>
					<th width="20%">所属模块</th>
					<th width="20%">配置标题</th>
					<th>字段名</th>
					<th width="10%">类型</th>
					<th class="last">操作</th>
				</tr>
			</thead> 
			<tbody class="grid">
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr ondblclick="window.location = '__URL__/detail/id/<?php echo ($vo["id"]); ?>'">
					<td><input name="id[]" type="checkbox" id="id[]" value="<?php echo ($vo["id"]); ?>" class="checkbox"></td>
					<td><span class="green"><?php echo ($vo["module"]); ?></span></td>
					<td><?php echo ($vo["title"]); ?></td>
					<td><?php echo ($vo["name"]); ?></td>
					<td><?php echo (getconfigvalue("SETTING_INPUT_TYPE",$vo["type"])); ?></td>
					<td>
						<a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>"><img src="../Public/images/edit-icon.gif" alt="Edit" title="编辑" /></a>
						<a href="__URL__/exec/act/delete/id/<?php echo ($vo["id"]); ?>"  class="confirm"><img src="../Public/images/hr.gif" alt="Delete" title="删除" /></a>						
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
			<tfoot>
				<tr class="last">                
					<td colspan="8">
						<div class="fl">
							<select name="act" class="" >
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
        <div id="ps">
        	<p>删除不可恢复,谨慎操作　必须有一个管理员，内置管理员不允许删除及修改管理组</p>
        </div>
		<div class="clear"></div>
    </div>
</div>

<!-- 版权信息区域 -->
</BODY>
</HTML>