<?php

/**
 * 管理员管理
 *
 * @author Aoki
 */
class AdminAction  extends GlobalAction{

	function _initialize() {
		parent::_initialize();
		$this->setLikeFields(array('username'));
	}

	public function _before_index(){
		$groupModel = D('Groups');
		$groups = $groupModel->where('id != 2')->select();
		$this->assign('groups',$groups);
	}

	public function _before_add(){
		$groupModel = D('Groups');
		$groups = $groupModel->where('id != 2')->select();
		$this->assign('list',$groups);
	}

	public function _before_edit(){
		$groupModel = D('Groups');
		$groups = $groupModel->where('id != 2')->select();
		$this->assign('list',$groups);
	}

	public function resetPassword() {
		$this->display();
	}

	public function reset() {
		$_POST['id'] = intval($_POST['id']);
		$mAdmin = M('Admin');

		$_POST['password'] = md5($_POST['password']);
		$_POST['password2'] = md5($_POST['password2']);
		if($_POST['password'] !== $_POST['password2']) {
			$this->error('两次输入的密码不一样！');
			return 1;
		} else {
			unset($_POST['password2']);
		}

		$mAdmin->create();
		$vo = $mAdmin->save();
		if($vo) {
			$this->success('用户资料修改成功！');
		}else {
			$this->error('数据提交失败！');
		}
	}

}
?>
