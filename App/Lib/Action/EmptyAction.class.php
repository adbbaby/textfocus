<?php

/**
 * 空模块，404跳转
 *
 * @author Aoki
 */
class EmptyAction extends Action {
	
	public function _empty($method) {
		$this->assign('message',L('pagenotfound'));
		$this->display(C('TMPL_ACTION_ERROR'));
	}
	
}