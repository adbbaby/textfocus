<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo (L("welcome")); ?></title>
</head>

<body>

<h1 style="font-size:200px;"><?php echo (L("welcome")); ?></h1>
切换到：<a href="?l=zh-cn">简体中文</a> | <a href="?l=zh-tw">繁体中文</a> | <a href="?l=en-us">英文</a>

<ul>
<?php if(is_array($posts)): $i = 0; $__LIST__ = $posts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li post_id="<?php echo ($vo["ID"]); ?>"><?php echo ($vo["post_title"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
</body>
</html>