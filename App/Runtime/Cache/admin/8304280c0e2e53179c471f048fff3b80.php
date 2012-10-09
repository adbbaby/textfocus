<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>导航</title>
<link href="../Public/style/reset.css" rel="stylesheet" type="text/css" />
<link href="../Public/style/top.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
var url = '__URL__';
var app = '__APP__';
</script>
<script type="text/javascript" src="../Public/js/topmenu.js?123"></script>
</head>
<body>
<div class="topnav">
	<div class="sitenav">
		<div class="welcome">
		<span class="userimg"><img src="../Public/images/sticky_unread_mine.gif" alt=""/>
		</span>你好，<span class="username"><a href="<?php echo U('Admin/Index/modify');?>/id/<?php echo ($uid); ?>" alt="我的账户" target="mcMainFrame"><?php echo (session('username')); ?></a></span>
		</div>
		<div class="sitelink">
			<a href="<?php echo U('Admin/Public/logout');?>" target="_top">安全退出</a>
			<a target="mcMainFrame" title="清除缓存" href="javascript:parent.mcMainFrame.clearCache();" class="reload">清除缓存</a>
			<a target="mcMainFrame" title="点击刷新右侧窗口" href="javascript:parent.mcMainFrame.location.reload()" class="reload">刷新右窗口页面</a>
		</div>
	</div>
	<div id="contentNav">
		<div class="nav-home blockLink">
			<a href="<?php echo U('Admin/Index/index');?>" target="_top"></a>
		</div>
		<ul class="navs blockLink">
			<?php if(is_array($list)): $i = 0; $__LIST__ = array_slice($list,0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(in_array($vo['id'],$menu)){ ?>
				<li class="items"><A href="<?php echo U('Admin/Menu/menu');?>/action/<?php echo ($vo["name"]); ?>" target="mcMenuFrame"  class="current"><?php echo ($vo["viewName"]); ?></A></li>
				<?php } endforeach; endif; else: echo "" ;endif; ?>
			<?php if(is_array($list)): $i = 0; $__LIST__ = array_slice($list,1,null,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(in_array($vo['id'],$menu)){ ?>
				<li class="items"><A href="<?php echo U('Admin/Menu/menu');?>/action/<?php echo ($vo["name"]); ?>" target="mcMenuFrame"><?php echo ($vo["viewName"]); ?></A></li>
				<?php } endforeach; endif; else: echo "" ;endif; ?>
			<li class="items lastItem"></li>
		</ul>
	</div>
</div>
</body>
</html>