<?php
if (!defined('CAL_GREGORIAN'))
	define('CAL_GREGORIAN',1);

//输出节点等级的名
function echoNodeLevel($level) {
	$name = C('NODELEVEL');
	return $name[$level];
}
function getCurrentUserId() {
	return intval(session('id'));
	//return intval(Session::get(APP_SESSION_FLAG . 'id'));
}
function getCurrentGroupId() {
	return intval(session('gid'));
	//return intval(Session::get(APP_SESSION_FLAG . 'gid'));
}
function getUid(){
	return getCurrentUserId();
}
function getGid(){
	return getCurrentGroupId();
}

function getFileSuffix($filename) {
	return substr($filename,strripos(strtolower($filename),".")+1);
}

function orderAuth($oid) {
	$mOrder = D('Order');
	return $mOrder->orderAuth($oid);
}//end orderAuth()

function diftime($time) {
	$ctime = time();
	$y = intval(date('Y',$time));
	$cy = intval(date('Y',$ctime));
	$w = intval(date('W',$time));
	$cw = intval(date('W',$ctime));
	$m = intval(date('m',$time));
	$cm = intval(date('m',$ctime));
	$d = intval(date('d',$time));
	$cd = intval(date('d',$ctime));
	$dif = $ctime - $time;
	switch($dif){
		case $dif <0:
			if($dif > -172800){
				if($dif > -86400){
					$title ='明天';
				}else{
					$title ='后天';
				}
				$day = intval(abs($dif)/(86400))+1;
				$type =1;
				$msg = '剩余'.$day.'天结束';
			}
			break;
		case $dif <3600://小于一个小时
			if($dif <60){
				$type =1;
				$title ='今天';
				$msg = intval($dif).'秒之前';
			}else{
			$type =1;
			$title ='今天';
			$msg = intval($dif/60).'分钟之前';
			}
			break;
		case $dif < 86400://小于一天
			$type =1;
			$title ='今天';
			$msg = intval($dif/3600).'小时之前';
			break;
		case $dif < 604800://小于一周
			if($w == $cw){
				$type =2;
				$title ='本周';
				if($y == $cy){
					$msg = date('m-d',$time);
				}else{
					$msg = date('Y-m-d',$time);
				}
			}else{
				$type =3;
				$title ='上周';
				if($y == $cy){
					$msg = date('m-d',$time);
				}else{
					$msg = date('Y-m-d',$time);
				}
			}
			break;
		case $dif < 2419200://小于一个月
			if($m == $cm){
				$type =4;
				$title ='本月';
				if($y == $cy){
					$msg = date('m-d',$time);
				}else{
					$msg = date('Y-m-d',$time);
				}
			}else{
				$type =5;
				$title = '更早';
				if($y == $cy){
					$msg = date('m-d',$time);
				}else{
					$msg = date('Y-m-d',$time);
				}
			}
			break;
		default:
			$type =5;
			$title = '更早';
			if($y == $cy){
				$msg = date('m-d',$time);
			}else{
				$msg = date('Y-m-d',$time);
			}
		break;
	}
	return array(
		'type'=>$type,
		'title'=>$title,
		'msg'=>$msg
	);
}
function checkExpired($time) {
	$ctime = time();
	$y = intval(date('Y',$time));
	$cy = intval(date('Y',$ctime));
	$w = intval(date('W',$time));
	$cw = intval(date('W',$ctime));
	$m = intval(date('m',$time));
	$cm = intval(date('m',$ctime));
	$d = intval(date('d',$time));
	$cd = intval(date('d',$ctime));
	$dif = $ctime - $time;
	switch($dif){
		case $dif <0:
			if($dif > -172800){
				if($dif > -86400){
					$title ='明天';
				}else{
					$title ='后天';
				}
				$day = intval(abs($dif)/(86400))+1;
				$type =1;
				$msg = '剩余'.$day.'天结束';
			}
			break;
		default:
			$type =1;
			$title = '过期';
			$day = intval(abs($dif)/(86400));
			$msg = $title.$day.'天';
		break;
	}
	return array(
		'type'=>$type,
		'title'=>$title,
		'msg'=>$msg
	);
}

function getCurrentDate(){
	return date('Y-m-d H:i:s',time());
}

function cutExpressName($field,$value) {
	$arr= split(',',$value);
	if($arr) return $arr[$field];
	return $value;
}
function valueUnPack($value) {
	$value = unserialize($value);
	foreach($value as $key=>$value){
		$arr[] = $key.':'.$value;
	}
	return implode(chr(13),$arr);
}

function checkTimeout($time) {
	$time = strtotime($time);
	$now = time();

	if(($now-$time)>172800){
		return false;
	}else{
		return true;
	}
}

function checkDeliveryTime($time) {
	$time = strtotime($time);
	$now = time();
	$tz = date('z',$time);
	$nz = date('z',$now);

	if($tz == $nz){
		return '今天';
	}else{
		if($nz>$tz && ($nz-$tz) == 1){
			return '昨天';
		}
		return '';
	}

}

function toggle($id,$field,$value) {
	if($value==1){
		$html = '<a href="'.__URL__.'/exec/act/toggle/id/'.$id.'/field/'.$field.'/value/0"><img src="../Public/images/icon/icon_green_on.gif" alt="Status - Enabled" title=" Status - Enabled " border="0" /></a>';
	}else{
		$html = '<a href="'.__URL__.'/exec/act/toggle/id/'.$id.'/field/'.$field.'/value/1"><img src="../Public/images/icon/icon_red_on.gif" alt="Status - Disabled" title=" Status - Disabled " border="0" /></a>';
	}
	echo $html;
}

function getDatetime(){
	return date('Y-m-d H:i:s',time());
}

function array_require( $array ){
	if($array){
		return true;
	}else{
		return false;
	}
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="__PUBLIC__/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="__PUBLIC__/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
			break;
		case - 1 :
			$showText = '删除';
			$showImg = '<IMG SRC="__PUBLIC__/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
			break;
		case 1 :
		default :
			$showText = '正常';
			$showImg = '<IMG SRC="__PUBLIC__/Images/icon_approve.png" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

	}
	return ($imageShow === true) ?  $showImg  : $showText;

}

/**
 * Curl版本
 * 使用方法：
 * $post_string = "app=request&version=beta";
 * request_by_curl('http://facebook.cn/restServer.php',$post_string);
 */
function request_by_curl($remote_server, $post_string)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $remote_server);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "91 Ads");
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function getConfigValue($configName,$key){
	$config = C($configName);
	return $config[$key];
}

function regularEscape($string){
	$find = array("\\","-","/",".");
	foreach($find as $key => $value){
		$replace[] = "\\".$value;
	}
	return str_replace($find, $replace, $string);
}

function getPushModeStartDate($mode,$args){
	if ($mode == 1) {
		return date('Y-m-d',  strtotime($args['push_time']));
	} else if ($mode == 2) {
		return $args['push_date'];
	} else if ($mode == 3) {
		return $args['start_date'];
	}
}

function getPushModeFinishDate($mode,$args){
	if ($mode == 3) {
		return $args['finish_date'];
	}
}

function buildSalt(){
	return substr(md5(time()),1,4);
}

// 循环创建目录
function mk_dir($dir, $mode = 0777) {
    if (is_dir($dir) || @mkdir($dir, $mode))
        return true;
    if (!mk_dir(dirname($dir), $mode))
        return false;
    return @mkdir($dir, $mode);
}

function getImgRealUrl($url){
	if($url){
		return 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/'.APP_NAME.'/'.$url;
	}
}

function revertCurrencyProportion($proportion){
	if($proportion){
		return round(1/$proportion,2);
	}
}
function getAppDescription($id){
	$appInfoModel = D('AppInfo');
	return $appInfoModel->where('`ai_app_id` = '.$id)->getField('ai_app_description');
}
?>