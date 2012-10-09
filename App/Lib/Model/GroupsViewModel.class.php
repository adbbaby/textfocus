<?php

/**
 * 用户组视图模型
 *
 * @author Aoki
 * @date 2010年5月28日
 */
import('ViewModel');
class GroupsViewModel extends ViewModel {

	protected $viewFields = array(
		'Groups'=>array('COUNT(Admin.id)'=>'userCount','id','name'=>'groupName','status','_type'=>'LEFT'),
		'Admin'=>array('id'=>'uid','gid','_on'=>'Groups.id = Admin.gid'),
	);

}
?>