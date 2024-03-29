<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: Page.class.php 2712 2012-02-06 10:12:49Z liu21st $

class PageWithInput {
	// 分页栏每页显示的页数
	public $rollPage = 5;
	// 页数跳转时要带的参数
	public $parameter;
	// 默认列表每页显示行数
	public $listRows = 20;
	// 起始行数
	public $firstRow;
	// 输入页码的输入框ID
	public $inputId = 'PageWithInput';
	// 分页总页面数
	protected $totalPages;
	// 总行数
	protected $totalRows;
	// 当前页数
	protected $nowPage;
	// 分页的栏的总页数
	protected $coolPages;
	// 分页显示定制
	protected $config =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','input'=>'页码:',
					'theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end% %inputPage%');
	// 默认分页变量名
	protected $varPage;

	/**
	 +----------------------------------------------------------
	 * 架构函数
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param array $totalRows  总的记录数
	 * @param array $listRows  每页显示记录数
	 * @param array $parameter  分页跳转的参数
	 +----------------------------------------------------------
	 */
	public function __construct($totalRows,$listRows='',$parameter='') {
		$this->totalRows = $totalRows;
		$this->parameter = $parameter;
		$this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
		if(!empty($listRows)) {
			$this->listRows = intval($listRows);
		}
		$this->totalPages = ceil($this->totalRows/$this->listRows);	 //总页数
		$this->coolPages  = ceil($this->totalPages/$this->rollPage);
		$this->nowPage  = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
		if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
			$this->nowPage = $this->totalPages;
		}
		$this->firstRow = $this->listRows*($this->nowPage-1);
	}

	public function setConfig($name,$value) {
		if(isset($this->config[$name])) {
			$this->config[$name]	=   $value;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 分页显示输出
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 */
	public function show() {
		if(0 == $this->totalRows) return '';
		$p = $this->varPage;
		$nowCoolPage	  = ceil($this->nowPage/$this->rollPage);
		$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
		$parse = parse_url($url);
		if(isset($parse['query'])) {
			parse_str($parse['query'],$params);
			unset($params[$p]);
			$url   =  $parse['path'].'?'.http_build_query($params);
		}
		//上下翻页字符串
		$upRow   = $this->nowPage-1;
		$downRow = $this->nowPage+1;
		if ($upRow>0){
			$upPage="<a href='".$url."&".$p."=$upRow'>".$this->config['prev']."</a>";
		}else{
			$upPage="";
		}

		if ($downRow <= $this->totalPages){
			$downPage="<a href='".$url."&".$p."=$downRow'>".$this->config['next']."</a>";
		}else{
			$downPage="";
		}
		// << < > >>
		if($nowCoolPage == 1){
			$theFirst = "";
			$prePage = "";
		}else{
			$preRow =  $this->nowPage-$this->rollPage;
			$prePage = "<a href='".$url."&".$p."=$preRow' >上".$this->rollPage."页</a>";
			$theFirst = "<a href='".$url."&".$p."=1' >".$this->config['first']."</a>";
		}
		if($nowCoolPage == $this->coolPages){
			$nextPage = "";
			$theEnd="";
		}else{
			$nextRow = $this->nowPage+$this->rollPage;
			$theEndRow = $this->totalPages;
			$nextPage = "<a href='".$url."&".$p."=$nextRow' >下".$this->rollPage."页</a>";
			$theEnd = "<a href='".$url."&".$p."=$theEndRow' >".$this->config['last']."</a>";
		}
		// 1 2 3 4 5
		$linkPage = "";
		for($i=1;$i<=$this->rollPage;$i++){
			$page=($nowCoolPage-1)*$this->rollPage+$i;
			if($page!=$this->nowPage){
				if($page<=$this->totalPages){
					$linkPage .= "&nbsp;<a href='".$url."&".$p."=$page'>&nbsp;".$page."&nbsp;</a>";
				}else{
					break;
				}
			}else{
				if($this->totalPages != 1){
					$linkPage .= "&nbsp;<span class='current'>".$page."</span>";
				}
			}
		}
		//直接输入页码
		$inputPage = '';
		$inputPage .= $this->config['input'] . '<input size=3 type=text id="' . $this->inputId .'" />';
//		$inputPage .= "<button id={$this->inputId}Button>Go</button>";
		$inputPage .= "<a href=# id='{$this->inputId}Button'>Go</a>";
		$inputPage .= <<< jQuery

<SCRIPT LANGUAGE="JavaScript">
	var {$this->inputId}_url = "{$url}&" + "{$p}=";
	$(function() {
		$("#{$this->inputId}Button").bind('click', function() {
			window.location.href = {$this->inputId}_url + $("#{$this->inputId}").val();
		});
	});
</SCRIPT>

jQuery;
		$pageStr	 =	 str_replace(
			array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%','%inputPage%'),
			array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd,$inputPage),$this->config['theme']);
		return $pageStr;
	}

}