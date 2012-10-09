<?php

/**
 * 节点管理
 *
 * @author Aoki
 */
class NodeAction  extends GlobalAction{
	
	public function _before_index(){
		$Node	= D("Node");

		if(S('allNode')) {
			$allNode	=	S('allNode');
		}else{
			$allNode = $Node->getAllNode();
			S('allNode',$allNode,1);
		}
		$this->assign('allNode',$allNode);
	}
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if (isset($_REQUEST ['_order'])) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = !empty($sortBy) ? $sortBy : 'orders';
		}

		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST ['_sort'])) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'desc' : 'asc';
		}
		if(unserialize(base64_decode($_REQUEST ['_params']))){
			$map = unserialize(base64_decode($_REQUEST ['_params']));
		}else{
			$id=intval($_GET["id"])?intval($_GET["id"]):0;
			unset($map['id']);
			$map['pid'] = $id;
		}
		$params =base64_encode(serialize($map));
		//取得满足条件的记录数
		$count = $model->where($map)->count($model->getPk());
		
		if ($count > 0) {
			import("@.ORG.Util.Page");
			//创建分页对象
			if (!empty($_REQUEST ['listRows'])) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '10';
			}
			$p = new Page($count, $listRows);
			//分页查询数据
			
			$voList = $model->where($map)->group($model->getPk())->order(" " . $order . " " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();

			//分页跳转的时候保证查询条件
			foreach ($map as $key => $val) {
				if (!is_array($val)) {
					$p->parameter .= "$key=" . urlencode($val) . "&";
				}
			}
			//分页显示
			$page = $p->show();

			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			
			//模板赋值显示
			$this->assign('list', $voList);
			$this->assign('sort', $sort);
			$this->assign('order', $order);
			$this->assign('sortImg', $sortImg);
			$this->assign('sortType', $sortAlt);
			$this->assign("page", $page);
			$this->assign("params", $params);
			
			$this->assign ( 'totalCount', $count );
			$this->assign ( 'numPerPage', C('PAGE_LISTROWS') );
			$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
		}
		import("@.ORG.Util.Cookie");
		Cookie::set('_currentUrl_', __SELF__);
	}
	
	/**
	 +----------------------------------------------------------
	 * 添加节点页面
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public function add(){
		$Node	= D("Node");

		if(S('allNode')) {
			$allNode	=	S('allNode');
		}else{
			$allNode = $Node->getAllNode();
			S('allNode',$allNode,1);
		}
		$this->assign('list',$allNode);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 节点编辑界面
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public function edit(){
		$id=intval($_GET["id"]);
		if (!$id) $this->error(L('_SELECT_NOT_EXIST_'));

		$Node	= D("Node");

		if(S('allNode')) {
			$allNode	=	S('allNode');
		}else{
			$allNode = $Node->getAllNode();
			S('allNode',$allNode,1);
		}
		$vo=$Node->find($id);
		if (!$vo) $this->error('未找到该节点！');

		$this->assign('list',$allNode);
		$this->assign('vo',$vo);
		$this->display();
	}

	public function insert() {
		$Node	= D("Node");
		$id = $this->_add($Node);
		//保存成功
		if($id) {
			$vo=$Node->find($id);
			$this->assign('jumpUrl',__URL__.'/index/id/'.$vo['pid']);
			$this->success('数据提交成功！');
		}else {
			$this->error('数据提交失败！');
		}
	}

	public function update() {
		$vo = $this->_save();
		//保存成功
		if($vo) {
			$this->assign('jumpUrl',__URL__.'/index/id/'.$vo['pid']);
			$this->success('数据提交成功！');
		}else {
			$this->error('数据提交失败！');
		}
	}

}

?>