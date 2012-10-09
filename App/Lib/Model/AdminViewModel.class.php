<?php

/**
 * 用户表视图模型
 *
 * @author Aoki
 * @date 2010年5月21日
 */
import('ViewModel');
class AdminViewModel extends ViewModel {

	protected $viewFields = array(
		'Admin'=>array('id','gid','username','contact_person','status','remark','_type'=>'LEFT'),
		'Groups'=>array('name'=>'groupName','status'=>'gstatus', '_on'=>'Admin.gid=Groups.id'),
	);

	public function getPk(){
		return 'Admin.id';
	}

	public function getOrderField(){
		return 'id';
	}
	public function getDbFields(){
		$fields = array();
		foreach($this->viewFields as $table){
			foreach($table as $field){
				$fields[] = $field;
			}
		}
		return $fields;
	}

}
?>
