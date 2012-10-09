<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ($charset); ?>">
<title><?php echo (C("app_name")); ?></title>
</head>
	<frameset rows="95,*" cols="*" frameborder="no" border="0" framespacing="0" >
		<frame src="<?php echo U('Admin/Menu/topmenu');?>" name="mcTopFrame" id="mcTopFrame" scrolling="no" noresize>
		<frameset cols="206,*" name="mcBodyFrame" id="mcBodyFrame" frameborder="no" border="0" framespacing="0"  >
			<frame src="<?php echo U('Admin/Menu/menu/action/');?>" name="mcMenuFrame" id="mcMenuFrame" scrolling="no" noresize>
			<frame src="#" name="mcMainFrame" id="mcMainFrame" scrolling="auto" noresize>
		</frameset>
	</frameset>
	
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; endforeach; endif; else: echo "" ;endif; ?>
	<noframes>
		<body>很遗憾，你的浏览器不支持框架。</body>
	</noframes>
</html>