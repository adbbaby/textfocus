<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>菜单</title>
<link href="../Public/style/reset.css" rel="stylesheet" type="text/css" />
<link href="../Public/style/menu.css?123" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
<base target="mcMainFrame" />
</head>
<script language="javascript">
<!--	

$(document).ready(function(){
	$(".tree li a").click(function(){
		var node = $(this);
		$(".tree li a").each(function() {
			$(this).removeClass("current");
		});
		node.addClass("current");
	});
});

function refreshMainFrame(url)
{
	parent.mcMainFrame.document.location = url;
}
-->
</script>
<base target="mcMainFrame">
<body>
<div id="contentBody">
<div id="sidebar">
	<?php $bread = session('breadcrumb'); ?>
	<div class="title"> <div class="m_bg"><?php echo ($bread['current_group_name']); ?></div> </div> 
	
	<div class="trees blockTextLink" >
	<?php if(is_array($childNode)): $i = 0; $__LIST__ = $childNode;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i; if(!empty($groupNode[$row['id']])){ ?>
		<!--<div class="module" ><?php echo ($row["viewName"]); ?></div>-->
		<div class="tree tree-expand">
			<ul class="nodes">
				<?php if(is_array($row["child"])): $i = 0; $__LIST__ = $row["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row2): $mod = ($i % 2 );++$i; if(in_array($row2['id'],$groupNode[$row['id']])){ ?>
					<li class="node"><a href="<?php echo U('Admin/'.$row['name'].'/'.$row2['name']);?>"><?php echo ($row2["viewName"]); ?></a></li>
					<?php } endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
	<?php } endforeach; endif; else: echo "" ;endif; ?>
		<div class="tree_last" style="display: block; "></div>
	</div>
	
</div>
<script type="text/javascript">refreshMainFrame('<?php echo ($url); ?>');</script>

</div>
</body>
</html>