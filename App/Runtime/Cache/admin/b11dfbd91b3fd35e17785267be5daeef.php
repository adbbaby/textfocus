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
<!-- 菜单区域  -->
<style>
ul.tree {list-style:none; margin:5px;}

</style>
<div class="row-fluid">
	<div class="span3">
	<div class="panel">
		<div class="panelHeader">
			<div class="panelHeaderContent">
				<h1 style="font-size:14px;">节点列表</h1>
			</div>
		</div>
		<div class="panelContent" >
<ul class="tree">

	<li>
		<a href="__URL__/index/id/<?php echo ($level1["id"]); ?>"><i class="icon-folder-open"></i>&nbsp;所有节点</a>
		<ul>
	<?php if(is_array($allNode)): foreach($allNode as $key=>$level1): ?><li>
		<a href="__URL__/index/id/<?php echo ($level1["id"]); ?>"><i class="icon-folder-open"></i>&nbsp;<?php echo ($level1["name"]); ?>.<?php echo ($level1["viewName"]); ?></a>
		<?php if(!empty($level1["child"])): ?><ul>
		<?php if(is_array($level1["child"])): foreach($level1["child"] as $key=>$level2): ?><li>
				<a href="__URL__/index/id/<?php echo ($level2["id"]); ?>"><i class="icon-list-alt"></i>&nbsp;<?php echo ($level2["name"]); ?>.<?php echo ($level2["viewName"]); ?></a>
				<?php if(!empty($level2["child"])): ?><ul style="display: none;">
				<?php if(is_array($level2["child"])): foreach($level2["child"] as $key=>$level3): ?><li>
						<a href="__URL__/edit/id/<?php echo ($level3["id"]); ?>"><?php echo ($level3["name"]); ?>.<?php echo ($level3["viewName"]); ?></a>
					</li><?php endforeach; endif; ?>
				</ul><?php endif; ?>
			</li><?php endforeach; endif; ?>
		</ul><?php endif; ?>
	</li><?php endforeach; endif; ?>
	</ul>
	</li>
</ul>
		</div>
	<div class="panelFooter">
		<div class="panelFooterContent"></div>
	</div>
</div>
	</div>
	<div class="span9">
		<div id="toolbar">
			<form method="get" action="" id="list-filter" class="form-inline">
			<div class="items first relative">
				<div class="controls">
					<button class="btn dialog" href="__URL__/add/id/<?php echo $_GET['id'];?>" w="868px" h="480px"  t="20px" l="20px"><i class="icon-plus"></i>新增节点</button>
					<input type="button" name="buildNode" class="btn"  value="自动生成节点" onclick="javascript:window.location='<?php echo U('Node/buildNode');?>'" />
				</div>
			</div>
			
			<div class="items fr">
				<div class="controls">
					<div class="input-append">
						<input class="span2" id="appendedPrependedInput" size="16" type="text"><a class="btn btn-primary" ><i class="icon-search"></i>搜索</a>
					</div>
				</div>
			</div>
			</form>
		</div>
		<form action="__URL__/exec" method="post" class="form-horizontal">
		<div id="table" class="fl">
			<table cellspacing="0" class="table table-striped table-bordered table-condensed">
			<thead>
				<tr class="sortable"  sort="<?php echo ($sort); ?>" sortImg="<?php echo ($sortImg); ?>" currentOrder="<?php echo ($order); ?>" params="<?php echo ($params); ?>">
					<th  width="25" class="first"><input name="checkbox" type="checkbox" class="checkbox" id="selectAll" value="选择"></th>
					<th order="name" width="10%">节点名</th>
					<th order="viewName" width="15%">显示名</th>
					<th width="10%">描述</th>
					<th width="10%">节点等级</th>
					<th order="orders"  width="10%">排序</th>
					<th order="type"  width="13%">菜单显示</th>
					<th class="last">操作</th>
				</tr>
			</thead>
			<tbody class="grid">
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr >
					<td width="25" class="first" align="center"><input name="id[]" type="checkbox" id="id[]" value="<?php echo ($vo["id"]); ?>" class="checkbox"></td>
					<td><a href="__URL__/index/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></a>&nbsp;</td>
					<td><a href="__URL__/index/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["viewName"]); ?></a>&nbsp;</td>
					<td><?php echo ($vo["description"]); ?>&nbsp;</td>
					<td><?php echo (echonodelevel($vo["level"])); ?>&nbsp;</td>
					<td><a href="__URL__/index/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["orders"]); ?></a>&nbsp;</td>
					<td><?php if(($vo['type']) == "1"): ?><img src="../Public/images/correct.gif" alt="Edit" title="状态" /><?php endif; ?>&nbsp;</td>
					<td>
						<a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>" class="btn btn-mini dialog"w="778px" h="480px"  t="10px" l="10px"><i class="icon-edit"></i>编辑</a>
						<a href="__URL__/exec/act/delete/id/<?php echo ($vo["id"]); ?>" class="confirm btn btn-danger btn-mini " ><i class="icon-trash icon-white"></i>删除</a>
						<?php if(($vo['status']) == "1"): ?><a href="__URL__/exec/act/lock/id/<?php echo ($vo["id"]); ?>" class="btn btn-mini "><i class="icon-lock"></i>锁定</a><?php endif; ?>
						<?php if(($vo['status']) == "0"): ?><a href="__URL__/exec/act/unlock/id/<?php echo ($vo["id"]); ?>"  class="btn"><i class="icon-unlock"></i>解锁</a><?php endif; ?>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
			<tfoot>
				<tr class="last">
					<td colspan="8">
						<div class="fl">
							<select name="act"  >
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
	</div>
</div>
		
	</div>
</div>

<script type="text/javascript" language="javascript" src="__PUBLIC__/Js/dwz/js/dwz.core.js"></script>
<script type="text/javascript" language="javascript" src="__PUBLIC__/Js/dwz/js/dwz.ui.js"></script>
<script type="text/javascript" language="javascript" src="__PUBLIC__/Js/dwz/js/dwz.tree.js"></script>
<script type="text/javascript" language="javascript" >

$(document).ready(function(){
	//$("ul.tree").jTree();
});
</script>


<!-- 版权信息区域 -->
</BODY>
</HTML>