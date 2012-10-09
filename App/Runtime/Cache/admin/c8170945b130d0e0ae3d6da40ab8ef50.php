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
legend{margin-bottom:2px;}
</style>
<div class="panel">
	<div class="panelHeader">
		<div class="panelHeaderContent">
			<h1 style="font-size:14px;">权限查看/分配</h1>
		</div>
	</div>
	<div class="panelContent" >
		<form action="__URL__/doAdd" method="POST">
		<table  class="table table-striped table-bordered table-condensed">
				<tbody>
					<tr>
						<th>说明</th>
						<th>设置</th>
					</tr>
					<tr class="cell">
						<th class="altbg1" style="width:150px;">
							<b>用户组:</b>
						</td>
						<td class="altbg2" style="padding:10px">
							<select style="width:150px;" name="gid" id="groupList" onchange="selectMenu(this.value)">
								<option value="0">请选择</option>
								<?php if(is_array($groups)): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $_GET['id']): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</td>
					</tr>
					<tr class="cell">
						<th class="altbg1" style="width:150px;">
							<b>菜单:</b>
						</td>
						<td class="altbg2" id="menulist" style="padding:10px;">
						<ul class="list">
							<?php if(is_array($allNode)): $i = 0; $__LIST__ = $allNode;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
								<input type="checkbox" name="popedom[<?php echo ($vo["id"]); ?>]" value="<?php echo ($vo["id"]); ?>" onclick="selectModel(this,<?php echo ($vo["id"]); ?>,'<?php echo ($vo["viewName"]); ?>')"						
								<?php if(in_array($vo['id'],$menu)){ ?>
								 checked 
								<?php } ?>
								/> <?php echo ($vo["viewName"]); ?>
							</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
						</td>
					</tr>
					
					<tr class="cell">
						<th class="altbg1" style="width:150px;">
							<b>模块:</b>
						</td>
						<td class="altbg2" id="modellist" >
							<?php if(is_array($allNode)): $i = 0; $__LIST__ = $allNode;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(in_array($vo['id'],$menu)){ ?>
								<fieldset id="fieldset_<?php echo ($vo["id"]); ?>">
								<legend><?php echo ($vo["viewName"]); ?></legend>
									<ul class="list" id="list_<?php echo ($vo["id"]); ?>">
										<?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li>
												<input type="checkbox" name="popedom[<?php echo ($vo["id"]); ?>][<?php echo ($sub["id"]); ?>]" value="<?php echo ($sub["id"]); ?>" onclick="selectAction(this,this.value,'<?php echo ($sub["viewName"]); ?>')"
												<?php if(!empty($groupNode[$sub['id']])){ ?>
												 checked 
												<?php } ?>
												/>
												<?php echo ($sub["viewName"]); ?>
											</li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul>
							 	</fieldset>
							<?php } endforeach; endif; else: echo "" ;endif; ?>
						</td>
					</tr>

					<tr class="cell">
						<th class="altbg1" style="width:150px;">
							<b>操作:</b>
						</td>
						<td class="altbg2" id="actionlist" >
							<?php if(is_array($allNode)): $i = 0; $__LIST__ = $allNode;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(in_array($vo['id'],$menu)){ ?>
								<?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$model): $mod = ($i % 2 );++$i; if(!empty($groupNode[$model['id']])){ ?>
								<fieldset id="fieldset_<?php echo ($model["id"]); ?>" level="<?php echo ($vo["id"]); ?>">
									<legend><?php echo ($model["viewName"]); ?></legend>
									<ul class="list" id="list_<?php echo ($model["id"]); ?>">
										<?php if(is_array($model['child'])): $i = 0; $__LIST__ = $model['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li>
											<input type="checkbox" name="popedom[<?php echo ($vo["id"]); ?>][<?php echo ($model["id"]); ?>][]" value="<?php echo ($sub["id"]); ?>" 
											<?php if(in_array($sub['id'],$groupNode[$model['id']])){ ?>
											 checked 
											<?php } ?>
											/>
											<?php echo ($sub["viewName"]); ?>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul>
							 	</fieldset>
								<?php } endforeach; endif; else: echo "" ;endif; ?>
							<?php } endforeach; endif; else: echo "" ;endif; ?>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>&nbsp;</td>
						<td class="footer" >
							<input type="submit" class="btn btn-primary" value="提交" />
							<input type="button" class="btn" name="add" value="返回" onclick="javascript:history.go(-1)" />
						</td>
					</tr> 
				</tfoot>
			</table>
        </form>
	</div>
	<div class="panelFooter">
		<div class="panelFooterContent"></div>
	</div>
</div>
<div class="clear"></div>
	</div>
</div>
</body>
</html>
<style>
.list{
	 list-style-type:none; list-style-position:inside;

	 display:block;
}
.list li{
	width:100px;
	float:left;
}
fieldset{
	padding:5px;
	margin:5px;
	border:1px solid #C4E5F6;
	width:auto;
}
#modellist fieldset{
	float:left;
}
legend{ color:#000; }
</style>

<script type="text/javascript">

function selectMenu(id){
	location.href='__URL__/index/id/'+id;
}
function selectModel(obj,id,nodeName){
	if(obj.checked){
		if($("#fieldset_"+id).size()==0){
			$('#modellist').append(addNode(nodeName,id,0));
		}else{
			$("#list_"+id).html('<li><img src="../Public/images/loading.gif" style="height:15px;"> Loading...</li>');
		}
		$.post('__URL__/getInfoList',{type:'modellist',id:id,userid:$('#groupList').val()},function(txt){
			$("#list_"+id).html(txt);
		});
	}else{
		$("#fieldset_"+id).fadeOut("fast",function(){
		 	$("#fieldset_"+id).remove();
		 	
			$.each( $("fieldset[level='"+id+"']"), function(i, n){
				$(this).remove();
			});
		}); 
	}
}
function selectAction(obj,id,nodeName,level){
	if(obj.checked){
		if($("#fieldset_"+id).size()==0){
			$('#actionlist').append(addNode(nodeName,id,level));
		}else{
			$("#list_"+id).html('<li><img src="../Public/images/loading.gif" style="height:15px;"> Loading...</li>');
		}
		$.post('__URL__/getInfoList',{type:'actionlist',id:id,userid:$('#groupList').val()},function(txt){
			$("#list_"+id).html(txt);
		});
	}else{
		$("#fieldset_"+id).fadeOut("fast",function(){
			$("#fieldset_"+id).remove();
		});
	}
}
function addNode(nodename,id,level){
	var html='<fieldset id="fieldset_'+id+'" level="'+level+'">'
			+'<legend>'+nodename+'</legend>'
			+'	<ul class="list" id="list_'+id+'">'
			+'  	<li><img src="../Public/images/loading.gif" style="height:15px;"> Loading...</li>'
			+'	</ul>'
			+'</fieldset>';
	return html;
}
</script>