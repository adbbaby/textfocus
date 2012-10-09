<?php

/**
 * 全局类，具体的功能模块都继承于此类
 * 方便做初始化和权限验证
 * 包含公共操作
 *
 * @author Aoki
 */
class GlobalAction extends Action {

	private $gid;
	private $uid;
	private $userName;
	private $limit;
	private $likeFields;

	function _initialize() {
		$this->gid = getCurrentGroupId();
		$this->uid = getCurrentUserId();
		
		$this->userName = session('username');
		C('LOGINUSERNAME', $this->userName);
		
		if ($this->uid == false || $this->gid == false) {
			if(MODULE_NAME == 'Index' && ACTION_NAME == 'index'){
				redirect(U('Public:login'));
			}else if(MODULE_NAME == 'Index' && ACTION_NAME == 'login'){
				$this->display('Public:login');
			}else{
				$this->assign('jumpUrl',U('Public/login'));
				$this->display('Public:logout');
			}
			exit;
		}
		
		//$this->_checkSecurity();
		
		$this->assign('uid',$this->getUid());
	}

	public function isSupervisor() {
		$supervisor_gid = C('SUPERVISOR_GID');
		if(empty($supervisor_gid)) {
			$supervisor_gid = array(1);	//如果没有配置超级管理员组,就默认1组为超级管理员
		}
		$result = in_array($this->getGid(), $supervisor_gid)===false?false:true;
		return $result;
	}//end isSupervisor()

	/**
	 * 获取用户ID
	 * @access protected
	 * @return array
	 */
	Protected function getLikeFields() {
		return $this->likeFields;
	}

	/**
	 * 设置使用like查询的字段
	 * @access protected
	 * @return void
	 */
	Protected function setLikeFields($likeFields) {
		if(is_string($likeFields)) {
			$likeFields = explode(',' , $likeFields);
		}
		$this->likeFields = $likeFields;
	}

	/**
	 * 获取使用like查询的字段
	 * @access protected
	 * @return int
	 */
	Protected function getUid() {
		return $this->uid;
	}

	/**
	 * 获取限制列表(数据库查询条件限制)
	 * @access protected
	 * @return array
	 */
	Protected function getLimit() {
		return $this->limit;
	}

	/**
	 * 获取限制列表(数据库查询条件限制)
	 * @access protected
	 * @return void
	 */
	Protected function setLimit($limit) {
		$this->limit = $limit;
	}

	/**
	 * 获取用户组ID
	 * @access protected
	 * @return int
	 */
	Protected function getGid() {
		return $this->gid;
	}

	/**
	 * 获取用户名
	 * @access protected
	 * @return string
	 */
	Protected function getName() {
		return $this->userName;
	}

	/**
	 * 检查用户组权限
	 * @param string $action 要操作的模块
	 * @access protected
	 * @return void
	 */
	protected function _checkSecurity() {
		if (!$this->uid) {
			$this->error('非法用户');
		}
		import('@.ORG.Security');

		//检查用户权限
		$module = Security::getModuleAccess(MODULE_NAME, ACTION_NAME, $this->gid);
		if (!$module) {
			//非法权限跳转页面
			$this->assign('waitSecond', 10);
			$this->error('当前位置: ' . MODULE_NAME . ' 下的 ' . ACTION_NAME .' 无访问权限!');
		}
	}

	public function index($model, $tpl = null) {
		$model = $this->_checkModel($model);
		//列表过滤器，生成查询Map对象
		$map = $this->_search($model);
		if (method_exists($this, '_filter')) {
			$this->_filter($map);
		}

		if (!empty($model)) {
			$this->_list($model, $map);
		}

		$this->display(trim($tpl));
	}

	/**
	 * 根据表单生成查询条件
	 * 进行列表过滤
	 * @access protected
	 * @param string $name 数据对象名称
	 * @return HashMap
	 * @throws ThinkExecption
	 */
	//TODO getSearchCondition
	protected function _search($model) {
		//生成查询条件
		$model = $this->_checkModel($model);
		$map = array();
		$likeFields = $this->getLikeFields();
		foreach ($model->getDbFields() as $val) {
			$currentRequest = trim($_REQUEST[$val]);
			if (isset($_REQUEST[$val]) && $currentRequest != '') {
				if(!empty($likeFields) && is_array($likeFields) && in_array($val, $likeFields)) {
					$map[$val] = array('like', '%' . $currentRequest . '%');
				} else {
					$map[$val] = $currentRequest;
				}
			}
		}

		$limit = $this->getLimit();
		if(!empty($limit)) {	//现在的限制条件只限于相等,以后有必要时再进行扩展其它条件
			foreach($limit as $k => $v) {
				$map[$k] = $v;
			}
		}
		return $map;
	}

	/**
	 * 根据表单生成查询条件
	 * 进行列表过滤
	 * @access protected
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
	 * @return void
	 * @throws ThinkExecption
	 */
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if (isset($_REQUEST ['_order'])) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = !empty($sortBy) ? $sortBy : (method_exists($model, 'getOrderField') ? $model->getOrderField() : $model->getPk());
		}

		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST ['_sort'])) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
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

			$voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();

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
			
			
			$this->assign ( 'totalCount', $count );
			$this->assign ( 'numPerPage', C('PAGE_LISTROWS') );
			$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
		}
		import("@.ORG.Util.Cookie");
		Cookie::set('_currentUrl_', __SELF__);
	}

	/**
	 * 检查所操作的Model
	 * @param string $model 要操作的Model
	 * @access protected
	 * @return void
	 */
	private function _checkModel($model) {
		if(empty($model)) {
			try {
				if(strtolower(ACTION_NAME) == 'index') {
					if (class_exists(MODULE_NAME . 'ViewModel')) {
						return D(MODULE_NAME . 'View');
					}
				}
				return D(MODULE_NAME);
			} catch (ThinkException $e) {
				$this->error('操作失败！<br/>未定义的模型或者是代码错误！请联系开发者！');
			}
		}
		if(is_string($model)) {
			return D($model);
		} else {
			return $model;
		}
	}

	/**
	 * 默认的添加页面
	 * TODO index \ add \ edit 放置前
	 * @return void
	 */
	public function add() {
		$this->display();
	}

	/**
	 * 插入记录
	 * @access protected
	 * @param Model $model 数据对象
	 * @return void
	 */
	public function doAdd($model = MODULE_NAME) {
		//检测模型
		$model = $this->_checkModel($model);

		$vo = $model->create();

		if (false === $vo) {
			$this->error($model->getError());
		}
		
		if (!empty($_FILES)) {
			if (!empty($_FILES['attachment']['name'][0])) {
				$files = $this->_upload();
				$vo['attachment'] = $files[0]['savename'];
			}
		}
		//保存当前Vo对象
		$id = $model->add($vo);
		//保存成功
		if ($id) {
			//数据保存触发器
			if (method_exists($this, ACTION_NAME . '_trigger')) {
				$action = ACTION_NAME . '_trigger';
				$this->$action($vo, $id);
			}

			$this->assign('jumpUrl', __URL__);
			$this->success("操作成功");
		} else {
			$this->error('数据提交失败！');
		}
	}

	/**
	 * 默认的编辑页面
	 * 
	 * @return void
	 */
	public function edit($model, $tpl = null) {
		$id = $this->_get('id');
		
		$model = $this->_checkModel($model);
		
		$map = array(
			$model->getPk() => $id
		);
		
		$limit = $this->getLimit();
		if(!empty($limit)) { //现在的限制条件只限于相等,以后有必要时再进行扩展其它条件
			foreach($limit as $k => $v) {
				$map[$k] = $v;
			}
		}
		$vo = $model->where($map)->find();
		$vo = $model->parseFieldsMap($vo);	//解析字段映射 By UID
		$this->assign('vo', $vo);
		$this->display(trim($tpl));
	}

	/**
	 * 默认的查看页面
	 * 
	 * @return void
	 */
	public function detail() {
		$id = $this->_get('id');

		$name = $this->getActionName();
		$model = D($name);

		$map = array(
			$model->getPk() => $id
		);

		$vo = $model->where($map)->find();

		$this->assign('vo', $vo);
		$this->display();
	}

	/**
	 * 更新一个数据对象
	 * @access protected
	 * @param Model $model 数据对象
	 * @return void
	 */
	public function doEdit($model, $return_url) {
		$model = $this->_checkModel($model);
		$vo = $model->create('', Model::MODEL_UPDATE);
		if(!$vo) {
			$this->error($model->getError());
		}
		if(!empty($_FILES)) {
			if (!empty($_FILES['attachment']['name'][0])) {
				$files = $this->_upload();
				$vo['attachment'] = $files[0]['savename'];
			}
		}
		$id = is_array($vo) ? $vo[$model->getPk()] : $vo->{$model->getPk()};
		
		$result = $model->save($vo);
		if(false !== $result) {
			//数据保存触发器
			if(method_exists($this, ACTION_NAME . '_trigger')) {
				$action = ACTION_NAME . '_trigger';
				$this->$action($vo, $id);
			}
			$return_url = empty($return_url) ? __URL__ : trim($return_url);
			$this->assign('jumpUrl', $return_url);
			$this->success('更新成功！');
		} else {
			$this->error($model->getError());
		}
	}

	/**
	 * 执行批量操作
	 * 删除/锁定/推荐/置顶/审核/移动
	 * @param string $model 要操作的模型
	 * @access protected
	 * @return void
	 */
	public function exec($model) {
		//检测模型
		$model = $this->_checkModel($model);
		$model_up_name = strtoupper($model->getModelName());
		$ids = $_REQUEST['id'];
		$act = trim($_REQUEST['act']);
		//处理参数
		if (is_array($ids)) {
			$id = implode(',', $ids);
		} else if (is_numeric($ids)) {
			$id = $ids;
		} else {
			$this->error('数据错误！');
		}
		//检测操作类型
		if (!$act) {
			$this->error('操作类型必须选择');
		}
		switch ($act) {
			case 'lock': {
					//先获取配置里有无当前模块的批量操作配置,有就用配置的值,没有就用默认的状态值
					$currentStatusCfg = C($model_up_name . '_STATUS');
					$statusToSet = 0;
					$statusField = 'status';
					if(!empty($currentStatusCfg)) {
						if(isset($currentStatusCfg[$model_up_name . '_BATCH_CANCEL_STATUS'])) {
							$statusToSet = intval($currentStatusCfg[$model_up_name . '_BATCH_CANCEL_STATUS']);
						}
						if(isset($currentStatusCfg[$model_up_name . '_STATUS_FIELD'])) {
							$statusField = trim($currentStatusCfg[$model_up_name . '_STATUS_FIELD']);
						}
					}
					$result = $model->where(' ' . $model->getPk() . " IN({$id})")->setField($statusField, $statusToSet);
					$actName = '锁定';
					break;
				}
			case 'unlock': {
					//先获取配置里有无当前模块的批量操作配置,有就用配置的值,没有就用默认的状态值
					$currentStatusCfg = C($model_up_name . '_STATUS');
					$statusToSet = 1;
					$statusField = 'status';
					if(!empty($currentStatusCfg)) {
						if(isset($currentStatusCfg[$model_up_name . '_BATCH_RECOVER_STATUS'])) {
							$statusToSet = intval($currentStatusCfg[$model_up_name . '_BATCH_RECOVER_STATUS']);
						}
						if(isset($currentStatusCfg[$model_up_name . '_STATUS_FIELD'])) {
							$statusField = trim($currentStatusCfg[$model_up_name . '_STATUS_FIELD']);
						}
					}
					$result = $model->where(' ' . $model->getPk() . " IN({$id})")->setField($statusField, $statusToSet);
					$actName = '解锁';
					break;
				}
			case 'delete': {
					$result = $model->where(' ' . $model->getPk() . " IN({$id})")->delete();
					$actName = '删除';
					break;
				}
			case 'toggle': {
					$result = $model->where(' ' . $model->getPk() . " IN({$id})")->setField($_GET['field'], $_GET['value']);
					$actName = '开关';
					break;
				}
			default:
				if(method_exists($this, $act)) {
					$this->$act();
					die;
				} else {
					$this->error('未知操作类型');
				}
				break;
		}
		if ($result) {
			$this->success('操作成功！');
		} else {
			$this->error('操作失败！');
		}
	}//end exec()

	/*
	 * 404 错误定向
	 */
	protected function _404($message = '', $jumpUrl = '', $waitSecond = 3) {
		$this->assign('message', L('pagenotfound'));
		if (!empty($jumpUrl)) {
			$this->assign('jumpUrl', $jumpUrl);
			$this->assign('waitSecond', $waitSecond);
		}
		$this->display(C('TMPL_ACTION_ERROR'));
	}
}

?>