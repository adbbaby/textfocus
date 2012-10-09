<?php

/**
 * 设置
 * @author Aoki
 */
class SettingModel extends Model {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','标题必须！',0),
		array('name','require','字段名必须！',0,),
	);
	// 自动填充设置
	protected $_auto	 =	 array(
	);

	public function getModules(){
		return $this->field('module')->group('module')->select();
	}

	public function add($data='',$options=array()) {
		if($data['type'] == 3 |$data['type'] == 4 |$data['type'] == 5 ){
			$data['value'] = split(chr(13),$data['value']);
			foreach($data['value'] as &$row){
				if(!empty($row)){
					$temp = split(':',trim($row));
					$arr[$temp[0]]=$temp[1];
				}
			}
			$data['value']=serialize($arr);
		}
		return  parent::add($data,$options);
	}

	public function save($data='',$options=array()) {
		if($data['type'] == 3 |$data['type'] == 4 |$data['type'] == 5 ){
			$data['value'] = split(chr(13),$data['value']);
			foreach($data['value'] as &$row){
				if(!empty($row)){
					$temp = split(':',trim($row));
					$arr[$temp[0]]=$temp[1];
				}
			}
			$data['value']=serialize($arr);
		}
		return  parent::save($data,$options);
	}
}
?>
