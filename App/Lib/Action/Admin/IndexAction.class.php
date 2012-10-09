<?php

class IndexAction extends GlobalAction {

	public function index() {
		$this->assign('date',time());
		$this->display();
	}

	public function main() {
		$User = D('UserView');
		$map=array('User.id'=>$this->getUid());

		$vo = $User->where($map)->find();
		$this->assign('vo', $vo);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 当前用户个人资料
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public function modify() {

		$User = D('UserView');
		$map=array('User.id' =>$this->getUid() );

		$vo = $User->where($map)->find();

		$this->assign('vo', $vo);
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 修改当前用户个人资料
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	public function edit(){
		$id=$this->getUid();
		if (!$id) $this->error(L('_SELECT_NOT_EXIST_'));

		$User=M("User");
		$Group	= M("Groups");

		$list=$User->find($id);
		if (!$list) $this->error('未找到该用户！');

		$glist=$Group->field('id,groupName')->where('`status` = 1')->order('id asc')->select();

		$this->assign('list',$glist);
		$this->assign('vo',$list);
		$this->display();
	}
	public function resetPassword() {
		$this->assign('id',$this->getUid());
		$this->display();
	}
	public function update() {
		$vo = $this->_save('User');
		if($vo) {
			$this->assign('jumpUrl',__APP__.'/' . MODULE_NAME . '/main');
			$this->success('用户资料修改成功！');
		}else {
			$this->error('数据提交失败！');
		}
	}
	public function reset() {
		$_POST['id'] = intval($this->getUid());
		$mAdmin = M('Admin');
		$oldPW = $mAdmin->where('id=' . $_POST['id'])->field('password')->find();
		if($oldPW['password'] !== md5($_POST['oldpassword'])) {
			$this->error('旧密码错误！');
			return 1;
		} else {
			unset($_POST['oldpassword']);
		}

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
			$this->assign('jumpUrl', __APP__.'/' . MODULE_NAME . '/main');
			$this->success('用户资料修改成功！');
		}else {
			$this->error('数据提交失败！');
		}
	}

}

?>