<?php

/**
 * 节点表模型
 *
 * @author Aoki
 * @date 2010年5月21日
 */
class NodeModel extends Model {

	protected $_validate	 =	 array(
		array('name','require','名称必填'),
		array('viewName','require','显示名必填'),
	);

	protected $_auto	 =	 array(
		array('status','1')
	);

	public function getAllNode() {
		return $this->makeNodeTree(0);
	}

	public function getChildNode($name) {
		if(!$name) return $this->makeNodeTree();
		$map = array('name'=>$name);
		$node = $this->where($map)->find();
		if($node){
			return $this->makeNodeTree($node['id']);
		}
		return null;
	}
	
	public function getMenuChildNode($name) {
		if(!$name) return $this->makeNodeTree();
		$map = array('name'=>$name);
		$node = $this->where($map)->find();
		$breadcrumb = session('breadcrumb');
		$breadcrumb['current_group_name'] = $node['viewName'];
		session('breadcrumb',$breadcrumb);
		if($node){
			return $this->makeMenuTree($node['id']);
		}
		return null;
	}

	/**
	 * 获取系统登录后默认页模块
	 * @return node 默认模块信息
	 */
	public function getIndexNode() {
		$map = array(
			'pid' => 0,
			'level' => 1,
			'status' => 1,
			'type' => 1,
		);
		return $this->where($map)->order('orders asc')->find();
	}

	/**
	 * 根据pid生成节点递归树
	 * @return node list
	 */
	private function makeNodeTree($pid = 0) {
		$list = array();
		$map['pid'] = $pid;
		$result = $this->where($map)->order('orders ASC,level ASC ,id asc')->select();
		if($result){
			foreach ($result as $key => $value){
				$name = $value['name'];
				$list[$name]['id']	= $value['id'];
				$list[$name]['pid']	= $value['pid'];
				$list[$name]['name']  = $value['name'];
				$list[$name]['viewName'] = $value['viewName'];
				$list[$name]['description'] = $value['description'];
				$list[$name]['level'] = $value['level'];
				$list[$name]['type'] = $value['type'];
				$list[$name]['status'] = $value['status'];
				if($value['level']<3){
					$list[$name]['child'] = $this->makeNodeTree($value['id']);
				}
			}
		}
		return $list;
	}
	
	/**
	 * 根据pid生成菜单递归树
	 * @return node list
	 */
	private function makeMenuTree($pid = 0) {
		$list = array();
		$map['pid'] = $pid;
		$map['type'] = 1;
		$result = $this->where($map)->order('orders ASC,level ASC ,id asc ')->select();
		if($result){
			foreach ($result as $key => $value){
				$name = $value['name'];
				$list[$name]['id']	= $value['id'];
				$list[$name]['pid']	= $value['pid'];
				$list[$name]['name']  = $value['name'];
				$list[$name]['viewName'] = $value['viewName'];
				$list[$name]['description'] = $value['description'];
				$list[$name]['level'] = $value['level'];
				$list[$name]['type'] = $value['type'];
				$list[$name]['status'] = $value['status'];
				if($value['level']<3){
					$list[$name]['child'] = $this->makeMenuTree($value['id']);
				}
			}
		}
		return $list;
	}

	/**
	 * 插入节点 如果节点是 MODULE 级别,就自动添加一些默认 ACTION
	 * @return id
	 */
	public function add($vo='',$options=array()) {
		$pnode = $this->find($vo['pid']);

		$vo['level'] = $pnode['level']+1;
		
		//保存当前Vo对象
		$id = parent::add($vo);
		//如果是 MODULE 节点,则添加默认的 index\add\edit\detail\exec 节点
		if($vo['level']== 2){
			$actionName = substr($vo['viewName'],0,-6);
			$defaultFunctionsNodes=array(
				'index'=>array(
					'pid' =>  $id,
					'name' => 'index',
					'viewName' => "{$actionName}管理",
					'description' => '',
					'level' => '3',
					'type' => '1',
					'status' => '1'
				),
				'add'=>array(
					'pid' =>  $id,
					'name' => 'add',
					'viewName' =>"添加{$actionName}",
					'description' => '',
					'level' => '3',
					'type' => '0',
					'status' => '1'
				),
				'edit'=>array(
					'pid' =>  $id,
					'name' => 'edit',
					'viewName' => "编辑{$actionName}",
					'description' => '',
					'level' => '3',
					'type' => '0',
					'status' => '1'
				),
				'detail'=>array(
					'pid' =>  $id,
					'name' => 'detail',
					'viewName' => "{$actionName}详情",
					'description' => '',
					'level' => '3',
					'type' => '0',
					'status' => '1'
				),
				'exec'=>array(
					'pid' =>  $id,
					'name' => 'exec',
					'viewName' => "{$actionName}批操作",
					'description' => '',
					'level' => '3',
					'type' => '0',
					'status' => '1'
				),
			);
			foreach($defaultFunctionsNodes as $function){
				//判断
				$r = parent::add($function);
			}
		}
		return $id;
	}

}
?>
