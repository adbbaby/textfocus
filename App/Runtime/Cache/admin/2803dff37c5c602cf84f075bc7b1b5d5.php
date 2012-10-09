<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
#breadcrumb{
    background-image:url('../Public/images/breadcrumb/bc_bg.gif'); 
    background-repeat:repeat-x;
    height:30px;
    line-height:30px;
    color:#9b9b9b;
    border:solid 1px #cacaca;
    width:100%;
    overflow:hidden;
    margin:0px;
    padding:0px;
}
#breadcrumb li {
    list-style-type:none;
    float:left;
    padding-left:10px;
}
#breadcrumb a{
    height:30px;
    display:inline;
    float:left;
    background-image:url('../Public/images/breadcrumb/bc_separator.gif'); 
    background-repeat:no-repeat; 
    background-position:right;
    padding-right: 15px;
    text-decoration: none;
    color:#454545;
    line-height:30px;
}
#breadcrumb a:hover{color:#35acc5;}
.home{border:none;margin: 8px 0px;}
.srch{display:inline; padding:5px 10px 0 0;}


</style>

<!--[if lt IE 7]>
   <style type="text/css">
   	.srch{display:inline; padding:7px 10px 0 0;}
   </style>
<![endif]-->
<!--[if IE]>
   <style type="text/css">
   	.chinese{ margin-top:2px;}
   </style>
<![endif]-->
<ul id="breadcrumb">
	<li><a href="<?php echo U('Index/index');?>" target="_top"><img src="../Public/images/breadcrumb/home_blk.gif" alt="Home" class="home" /></a></li>
	<?php $bread = session('breadcrumb'); ?>
	<?php if($bread['current_group_name']): ?>
	<li><a href="#" class="chinese"><?php echo ($bread['current_group_name']); ?></a></li>
	<?php endif;?>
	<?php if($bread['current_action_name']): ?>
	<li><a href="#" class="chinese"><?php echo ($bread['current_action_name']); ?></a></li>
	<?php endif;?>
	<?php if($bread['current_method_name']): ?>
	<li><a href="#" class="chinese"><?php echo ($bread['current_method_name']); ?></a></li>
	<?php endif;?>
	<?php if($bread['current_product_name']):?>
	<li><a href="#" class="chinese"><?php echo ($bread['current_product_name']); ?></a></li>
	<?php endif;?>
	<?php session('breadcrumb','');?>
</ul>