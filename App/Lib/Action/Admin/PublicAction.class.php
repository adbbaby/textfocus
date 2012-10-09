<?php

class PublicAction extends Action {

	/**
	 * 生成图像验证码
	 * @access public
	 * @return string
	 */
	public function verify(){
		import("ORG.Util.Image");
		Image::buildImageVerify();
	}
	
	public function admin_login(){
		$this->display('Public:admin_login');
	}
	
	public function login(){
		$this->display('Public:login');
	}
	
	/**
	 * 开发者登陆验证
	 * @access public
	 * @return void
	 */
	public function checkDeveloperLogin() {
		$devModel= D('Developer');
		$username = trim($_POST['dev_username']);
		$password = trim($_POST['dev_password']);
		$verify=trim($_POST['verify']);
		
		if($username==''){
			$this->error('用户名不能为空!!!');
		}elseif($password==''){
			$this->error('密码不能为空!!!');
		}elseif(md5($verify)!=session('verify')){
			$this->error('验证码错误!!!');
		}
		$map=array();
		$map["dev_username"]=$username;
		$vo = $devModel->where($map)->find();
		
		if(false===$vo){
			$this->error('用户名不存在!!!');
		}else{
			if($vo['dev_password'] != md5($password.$vo['dev_salt'])) $this->error('密码错误!!!');
			$vo['dev_login_ip'] = get_client_ip();
			$vo['dev_login_time'] = time();
			$devModel->save($vo);
			import ('@.ORG.Security' );
			Security::authenticate($vo);
			$defaultUrl = C('INDEX_URL');
			if(empty($defaultUrl)) {
				$defaultUrl = 'Index';
			}
			$this->redirect($defaultUrl . '/index');
		}
	}
	
	/**
	 * 登陆验证
	 * @access public
	 * @return void
	 */
	public function checkLogin() {
		$User=M('Admin');
		$username=trim($_POST['username']);
		$password=trim($_POST['password']);
		$verify=trim($_POST['verify']);
		
		if($username==''){
			$this->error('用户名不能为空!!!');
		}elseif($password==''){
			$this->error('密码不能为空!!!');
		}elseif(md5($verify)!=session('verify')){
			if(!C('NO_VERIFY_CODE')){
				$this->error('验证码错误!!!');
			}
		}
		$map=array();
		$map["username"]=$username;
		$vo = $User->where($map)->find();
		
		if(false===$vo){
			$this->error('用户名不存在!!!');
		}else{
			if($vo['password'] != md5($password)) $this->error('密码错误!!!');
			
			import ('@.ORG.Security' );
			Security::authenticate($vo);
			$defaultUrl = C('INDEX_URL');
			if(empty($defaultUrl)) {
				$defaultUrl = 'Index';
			}
			$this->redirect($defaultUrl . '/index');
		}
	}
	
	public function isEmailAvailable(){
		$devModel = D('Developer');
		
		$vo = $devModel->create();
		if($vo){
			echo 'success';
		}else{
			echo $devModel->getError();
		}
	}
	public function isValidNickName(){
		$devModel = D('Developer');
		
		$vo = $devModel->create();
		if($vo){
			echo 'success';
		}else{
			echo $devModel->getError();
		}
	}
	
	public function doDeveloperRegister(){
		$devModel = D('Developer');
		
		$vo = $devModel->create();
		
		if($vo){
			$vo['dev_password'] = md5($vo['dev_password'].$vo['dev_salt']);
			if($id = $devModel->add($vo)){
				if($_POST['HTTP_REFERER']){
					$url = $_POST['HTTP_REFERER'];
				}
				$this->success(L('REGISTER_SUCCESS'),$url);
			}else{
				$this->error($devModel->getError());
			}
		}else{
			$this->error($devModel->getError());
		}
		exit;
	}
	/**
	 * 登出
	 * @access public
	 * @return void
	 */
	public function logout() {
		if(session('gid') == 2){
			$url = U('Public/login');
		}else{
			$url = U('Public/admin_login');
		}
		import ('@.ORG.Security' );
		Security::destoryAccess();
		// 登出成功
		$this->assign("jumpUrl",$url);
		$this->success("登出成功，马上跳转");
		
	}

	public function clearCache() {
		//清空Runtime目录
		//清空S缓存
		import('@.ORG.Util.Dir');
		$dir = new Dir(RUNTIME_PATH);
		$list = $dir->toArray();

		foreach($list as $d){
			$dir->del($d['pathname']);
		}

		$Cache = Cache::getInstance(C('DATA_CACHE_TYPE'));
		$Cache->clear();
		$this->success('缓存清除成功!');
	}//end clearCache()

}

?>