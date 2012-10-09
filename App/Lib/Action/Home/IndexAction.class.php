<?php
/**
 * 首页
 */
class IndexAction extends BaseAction {

	public function index(){
		$postsModel = M('Posts');
		
		$postsMaps=array(
			'post_status'=>'publish',
			'post_type'=>'post',
		);
		$posts= $postsModel->where($postsMaps)->select();
		//dump($posts);
		$assign= array(
			'posts'=>$posts,
		);
		
		$this->assign($assign);
		$this->display();
	}
}