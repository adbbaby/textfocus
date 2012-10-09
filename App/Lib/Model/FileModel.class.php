<?php
/**
 * 文件操作模型
 *
 * @author UID
 * @date 2012年08月10日
 */
class FileModel extends Model {

	protected $autoCheckFields = false;

	private static $imageClassPath;	//图片类库包路径
	private static $h_upload;	//文件上传类句柄
	private static $ipa_util_loaded = false;	//是否已装载解IPA文件所需的类库文件
	public static $URL_PREFIX = array(
						'http://',
						'https://',
						'ftp://',
						'ftps://',
					);
	public static $SSL_URL_PREFIX = array(
						'https://',
						'ftps://',
					);

//	private $upload_path;

	protected function _initialize() {
		parent::_initialize();
		self::$imageClassPath = C('IMG_CLASS_PATH');
		import('@.ORG.UploadFile');
		import('@.ORG.Util.Ftp');
		self::$h_upload = new UploadFile();
	}

	public function getApkInfo($file) {
		import('@.ORG.ManifestParser');
		$ap = new ApkParser();
		$ap->open($file);
		$apk_info = array(
					'package' => $ap->getPackage(),
					'name' => $ap->getAppName(),
					'ver_code' => $ap->getVersionCode(),
					'ver_name' => $ap->getVersionName(),
		);
		return $apk_info;
	}//end getApkInfo()

	// 根据包名,从谷哥Play获取应该数据
	public function getAppinfoFromGooplay($app_url) {
		$return_data = array();

		import('@.ORG.phpQuery');
		phpQuery::newDocumentFile($app_url);

		$icon = pq('.doc-banner-icon > img:eq(0)')->attr('src');
		if(!empty($icon)) {
			$icon = $this->_chgGooplayImgSize($icon);
			$return_data['icon91'] = $this->getRemoteImgs($icon, C('IMG_UP_TYPE.APP_ICON'), null, true);
		} else {
			$return_data['icon91'] = array('error' => 1);
		}

		// $scrshots = pq('.screenshot-carousel-content-container')->find('img');
		$scrshots = pq('.screenshot-carousel-content-container > img');
		$scrshots_url = array();
		foreach($scrshots as $scrshot) {
			$get_scrshot = $this->getRemoteImgs(
				$this->_chgGooplayImgSize(pq($scrshot)->attr('src')),
				C('IMG_UP_TYPE.APP_SCREENSHOT'),
				null,
				true
			);
			if(!$get_scrshot['error']) {
				$scrshots_url[] = $get_scrshot['url'];
			}
		}
		if(!empty($scrshots_url)) {
			$return_data['screenshot91'] = array(
				'error' => 0,
				'url' => $scrshots_url,
			);
		} else {
			$return_data['screenshot91'] = array('error' => 1);
		}

		$return_data['description'] = pq('#doc-original-text')->html();
		$return_data['homepage'] = pq('.doc-description-show-all + a')->attr('href');
		$return_data['email'] = pq('.contact-developer-spacer + a')->attr('href');
		$return_data['developer'] = pq('.doc-header-link')->html();
		return $return_data;
	}//end getAppinfoFromGooplay()

	public function getIpaInfo($file) {
		$this->_importIpaUtil();

		$application = AnalyzeIpa::processIPA($file);
		$ios_type = $application['UIDeviceFamily'];
		if(count($ios_type) > 1) {
			$ios_type = 3;
		} else {
			$ios_type = array_shift($ios_type);
		}
		$apk_info = array();
		if(!empty($application)) {
			$apk_info = array(
						'package' => $application['CFBundleIdentifier'],
						'name' => $application['CFBundleDisplayName'],
						'ver_code' => $application['CFBundleVersion'],
						'ver_name' => $application['CFBundleVersion'],
						'min_os_ver' => D('Firmware')->genOrder($application['MinimumOSVersion']),
						'ios_type' => $ios_type,
			);
		}

		return $apk_info;
	}//end getIpaInfo()

	/*
	 * 生成圆角ICON(暂时只处理苹果的ICON)
	 *
	 */
	public function roundedCorner($p_src_img, $p_dst_img){
		if(!file_exists($p_src_img)) {
			return false;
		}

		$p_dst_img = empty($p_dst_img) ? $p_src_img : $p_dst_img;

		$img_size = getimagesize($p_src_img);
		$rounder = ceil(min($img_size[0], $img_size[1]) * (70/512));
		import('@.ORG.ImageRoundedCorner');
		$image_roundedcorner_obj = new ImageRoundedCorner($p_src_img, $rounder);
		$image_roundedcorner_obj->round_it($p_dst_img);
		return $p_dst_img;
	}//end roundedCorner()

	/*
	 * 批量获取网站图片,并按照图片作用进行处理
	 *
	 * @a_img_url 图片链接
	 * @img_type  图片各类(icon、截图之类的)
	 * @trigger   获取后执行的操作
	 * @up_to_ftp 是否上传到FTP
	 *
	 * @return array 相对于网站根目录的本地存放路径;如果上传到FTP,返回图片服务器的链接
	 */
	public function getRemoteImgs($a_img_url, $img_type, $trigger = null, $up_to_ftp = false) {
		$down_result = array(
			'error' => 0,
			'url' => array(),
			'message' => '',
		);

		$img_type = intval($img_type);
		if(false === in_array($img_type, C('IMG_UP_TYPE'))) {	//图片操作动作不存在
			$down_result['error'] = 1;
			$down_result['message'] = '图片操作动作不存在';
			unset($down_result['url']);
			return $down_result;
		}

		if(is_string($a_img_url)) {
			$a_img_url = array($a_img_url);
		}

		set_time_limit(60 * count($a_img_url));
		$file_base_dir = $this->_genUpImgPath($img_type, true);
		$hander = curl_init();
		curl_setopt($hander, CURLOPT_HEADER, 0);
		curl_setopt($hander, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($hander, CURLOPT_TIMEOUT, 60);
		//curl_setopt($hander, CURLOPT_RETURNTRANSFER, true);
		foreach($a_img_url as $img_url) {
			$ext_name = substr(strrchr($img_url, '.'), 1);
			// todo 根据 image_type_to_extension 定扩展名
			if(strlen($ext_name) < 2 || strlen($ext_name) > 4 || empty($ext_name)) $ext_name = 'jpg';
			$save_file = $file_base_dir . md5($img_url) . '.' . $ext_name;
			$fp = fopen($save_file, 'wb');
			curl_setopt($hander, CURLOPT_URL, $img_url);
			curl_setopt($hander, CURLOPT_FILE, $fp);
/*
			$is_ssl = false;	//文件名是否是URL路径
			$img_url = trim($img_url);
			foreach(self::$SSL_URL_PREFIX as $v) {
				if(0 == strcasecmp($v, substr($img_url, 0, strlen($v)))) {
					$is_ssl = true;
					break;
				}
			}
			if($is_ssl) {
				curl_setopt($hander, CURLOPT_SSL_VERIFYPEER, false); // 阻止对证书的合法性的检查
				curl_setopt($hander, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
			}
 */
			curl_setopt($hander, CURLOPT_SSL_VERIFYPEER, false); // 阻止对证书的合法性的检查
			curl_setopt($hander, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
			$curl_success = curl_exec($hander);
			fclose($fp);
			if($curl_success) {
				//$save_file = $this->_renameByImgSize($save_file);
				//$base_save_file = substr($save_file, strlen(BASE_PATH));
				$save_file = $this->_imgExtRename($save_file);
				$resize_file = $this->_resizeImg($save_file);
				/* if($resize_file) {
					$down_result['url'][] = substr($resize_file, strlen(BASE_PATH));
				} */
				$save_file = empty($resize_file) ? $save_file : $resize_file;

				$this->_imgTrigger($trigger, $save_file);

				$save_file = substr($save_file, strlen(BASE_PATH));

				if($up_to_ftp) {
					$up_ftp_result = $this->uploadFtpFile($save_file, 'IMG_FTP');
/* 					if($up_ftp_result) {
						$save_file = C('IMAGE_HOST') . C('FTP_PATH') . '/' . $save_file;
					} */
					//根据需求改成FTP失败就认为抓图失败
					//继续下一个抓图操作
					//成功时也只返回相对路径,而不是URL
					if(!$up_ftp_result) {
						$this->_unlink($save_file);
						continue;
					}
				}

				$down_result['url'][] = $save_file;
			}
		}
		curl_close($hander);

		if(!empty($down_result['url'])) {
			$down_result['error'] = 0;
			unset($down_result['message']);
		} else {
			$down_result['error'] = 1;
			$down_result['message'] = '图片获取失败';
			unset($down_result['url']);
		}
		return $down_result;
	}

	public function upload_img($img_type, $checksize = false, $up_to_ftp = false, $trigger = null) {
		$up_result = array(
			'error' => 0,
			'url' => '',
			'message' => '',
		);

		$img_type = intval($img_type);
		if(false === in_array($img_type, C('IMG_UP_TYPE'))) {	//图片操作动作不存在
			$up_result['error'] = 1;
			$up_result['message'] = '图片操作动作不存在';
			return $up_result;
		}

		$this->_setUpImgConfig($img_type);
		if (!self::$h_upload->upload()) {	//上传失败
			$up_result['error'] = 1;
			$up_result['message'] = self::$h_upload->getErrorMsg();
		} else {
			$upload_file_info = self::$h_upload->getUploadFileInfo();
			$up_result['error'] = 0;
			$up_result['url'] = substr($upload_file_info[0]['savepath'], strlen(BASE_PATH)) . $upload_file_info[0]['savename'];

			if($checksize) {
				$check_result = $this->imgSizeIsAllow($upload_file_info[0]['savepath'] . $upload_file_info[0]['savename'], true);
				if(!$check_result) {
					$up_result['error'] = 1;
					$up_result['message'] = '不允许上传此尺寸的图片，请修改后再试！';
				} else {
					$save_file = $this->_imgExtRename($upload_file_info[0]['savepath'] . $upload_file_info[0]['savename']);
					$resize_file = $this->_resizeImg($save_file);
					if($resize_file) {
						$up_result['url'] = substr($resize_file, strlen(BASE_PATH));
					}
				}
			}

			if(empty($up_result['error'])) {
				$this->_imgTrigger($trigger, BASE_PATH . $up_result['url']);
			}

			if($up_to_ftp && empty($up_result['error'])) {
				$up_ftp_result = $this->uploadFtpFile($up_result['url'], 'IMG_FTP');
/* 				if($up_ftp_result) {
					$up_result['url'] = C('IMAGE_HOST') . C('FTP_PATH') . '/' . $up_result['url'];
				} */
				//根据需求改成FTP失败就认为上传失败
				//继续下一个上传操作
				//成功时也只返回相对路径,而不是URL
				if(!$up_ftp_result) {
					$up_result['error'] = 1;
					$up_result['message'] = '上传到FTP失败！';
					$this->_unlink($up_result['url']);
				}
			}
		}

		return $up_result;
	}//end upload_img()

	// 判断图片的尺寸是否在允许列表里
	public function imgSizeIsAllow($p_img, $unlink = true) {
		$img_size = getimagesize($p_img);
		$img_size = $img_size[0] . '_' . $img_size[1];

		$allow_size = S('IMG_UP_ALLOW_SIZE');
		if(empty($allow_size)) {
			$allow_size = array();
			foreach(C('IMG_RESIZE_MAP') as $v) {
				$allow_size = array_merge($allow_size, $v);
			}
			S('IMG_UP_ALLOW_SIZE', $allow_size);
		}

		$is_allow = (false === in_array($img_size, $allow_size)) ? false : true;
		if(!$is_allow && $unlink) {
			unlink($p_img);
		}

		return $is_allow;
	}//end imgSizeIsAllow()

	/*
	 * 上传安装文件
	 *
	 *
	 */
	public function upload_file($file_type, $up_to_ftp = false) {
		$up_result = array(
			'error' => 0,
			'url' => '',
			'message' => '',
		);

		$file_type = intval($file_type);
		if(false === in_array($file_type, C('FILE_UP_TYPE'))) {	//文件操作动作不存在
			$up_result['error'] = 1;
			$up_result['message'] = '文件操作动作不存在';
			return $up_result;
		}
		$this->_setUpFileConfig($file_type);

		$upload_file_info = null;
		if (self::$h_upload->upload()) {	//上传成功
			$upload_file_info = self::$h_upload->getUploadFileInfo();
			$up_result['error'] = 0;
			$up_result['url'] = substr($upload_file_info[0]['savepath'], strlen(BASE_PATH)) . $upload_file_info[0]['savename'];

			if($up_to_ftp) {
				$up_ftp_result = $this->uploadFtpFile($up_result['url'], 'SOFT_FTP');
/* 				if($up_ftp_result) {
					$up_result['url'] = C('SOFT_HOST') . C('FTP_PATH') . '/' . $up_result['url'];
				} */
				//根据需求改成FTP失败就认为上传失败
				//继续下一个上传操作
				//成功时也只返回相对路径,而不是URL
				if(!$up_ftp_result) {
					$up_result['error'] = 1;
					$up_result['message'] = '上传到FTP失败！';
					$this->_unlink($up_result['url']);
				}
			}
		} else {
			$up_result['error'] = 1;
			$up_result['message'] = self::$h_upload->getErrorMsg();
		}

		return $up_result;
	}//end upload_file()

	/*
	 * 上传本地文件到FTP上
	 * @p_file    相对于网站根目录的本地文件
	 * @$p_config FTP配置文件,直接用 C($p_config) 装载
	 *
	 * @return bool 只要有一个FTP上传成功,就返回 true
	 */
	public function uploadFtpFile($p_file, $p_config) {
		$up_success = false;

		$file = BASE_PATH . $p_file;
		foreach(C($p_config) as $v) {
			$ftp = new FTP(
				$v['FTP_HOST'],
				$v['FTP_PORT'],
				$v['FTP_USER'],
				$v['FTP_PASS']
			);
			if($ftp->up_file($file, $p_file)) {
				$up_success = true;
			} else {
				echo $ftp->errMsg;
			}
			$ftp->close();
		}

		return $up_success;
	}

	/*
	 * 返回文件URL
	 * 根据请求提取的文件名判断如果有 HTTP://、FTP:// 等前缀,就直接返回文件名;
	 * 如果没有URL前缀,说明是本地文件,加上本站URL后返回
	 *
	 */
	public function getFileUrl($p_file) {
		if(empty($p_file)) {
			return '';
		}
		$is_url = false;	//文件名是否是URL路径
		$p_file = trim($p_file);

		foreach(self::$URL_PREFIX as $v) {
			if(0 == strcasecmp($v, substr($p_file, 0, strlen($v)))) {
				$is_url = true;
				break;
			}
		}

		if($is_url) {
			return $p_file;
		} else {
			return 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__ . '/' . $p_file;
		}
	}//end getFileUrl()

	/*
	 * 返回存放文件的路径
	 *
	 */
	public function getFilePath($file_type) {
		return C('FILE_UP_PATH') . C('FILE_UP_SUB_DIR') . '/' . C('FILE_UPLOAD_CONFIG_' . $file_type . '.savePath') . '/';
	}//end getFilePath()

	/*
	 * 返回存放图片的路径
	 *
	 */
	public function getImgPath($img_type) {
		return C('FILE_UP_PATH') . C('IMG_UP_SUB_DIR') . '/' . C('IMG_UPLOAD_CONFIG_' . $img_type . '.savePath') . '/';
	}//end getImgPath()

	/*
	 * 移动不需要的文件到垃圾目录
	 *
	 */
	public function moveTrash($p_file) {
		return;
	}//end moveTrash()

	/*
	 *	根据上传的类型设置上传类的配置
	 *
	 */
	private function _setUpImgConfig($img_type) {
		$img_config = C('IMG_UPLOAD_CONFIG_' . $img_type);

		self::$h_upload->maxSize = $img_config['maxSize'];
		self::$h_upload->allowExts = $img_config['allowExts'];

		//保存相对于网站主目录的目录路径,子目录名根据当前时间来确定
		self::$h_upload->savePath = $this->_genUpImgPath($img_type);

		self::$h_upload->thumb = $img_config['thumb'];
		self::$h_upload->thumbPrefix = $img_config['thumbPrefix'];
		self::$h_upload->thumbMaxWidth = $img_config['thumbMaxWidth'];
		self::$h_upload->thumbMaxHeight = $img_config['thumbMaxHeight'];
		self::$h_upload->saveRule = $img_config['saveRule'];
		self::$h_upload->thumbRemoveOrigin = $img_config['thumbRemoveOrigin'];
		self::$h_upload->imageClassPath = self::$imageClassPath;

		return;
	}//end _setUpImgConfig()

	private function _genUpImgPath($img_type, $mkdir = false) {
		$date_format = date('YmdH', $_SERVER['REQUEST_TIME']);
		$year = substr($date_format, 0, 4) . '/';
		$month = substr($date_format, 4, 2) . '/';
		$day = substr($date_format, 6, 2) . '/';
		$hour = substr($date_format, 8) . '/';
		$dir = BASE_PATH . $this->getImgPath($img_type) . $year . $month . $day . $hour;
		if($mkdir) mk_dir($dir);
		return $dir;
	}//end _genUpImgPath()

	/*
	 *	根据上传的类型设置上传类的配置
	 *
	 */
	private function _setUpFileConfig($file_type) {
		$file_config = C('FILE_UPLOAD_CONFIG_' . $file_type);

		self::$h_upload->maxSize = $file_config['maxSize'];
		self::$h_upload->allowExts = $file_config['allowExts'];

		//保存相对于网站主目录的目录路径,子目录名根据当前时间来确定
		self::$h_upload->savePath = $this->_genUpFilePath($file_type);
		self::$h_upload->hashType = $file_config['hashType'];
		self::$h_upload->saveRule = $file_config['saveRule'];
		self::$h_upload->uploadReplace = $file_config['uploadReplace'];

		return;
	}//end _setUpFileConfig()

	private function _genUpFilePath($file_type, $mkdir = false) {
		$date_format = date('YmdH', $_SERVER['REQUEST_TIME']);
		$year = substr($date_format, 0, 4) . '/';
		$month = substr($date_format, 4, 2) . '/';
		$day = substr($date_format, 6, 2) . '/';
		$hour = substr($date_format, 8) . '/';
		$dir = BASE_PATH . $this->getFilePath($file_type) . $year . $month . $day . $hour;
		if($mkdir) mk_dir($dir);
		return $dir;
	}//end _genUpFilePath()

	/*
	 * 根据图片尺寸生成相应的小图
	 *
	 * @return string 新文件名: 原文件名_宽_高.扩展名
	 */
	private function _resizeImg($p_img) {
		$img_info = getimagesize($p_img);
		$size_str = $img_info[0] . '_' . $img_info[1];
		$resize_to = '';
		foreach(C('IMG_RESIZE_MAP') as $k => $v) {
			if(false !== in_array($size_str, $v)) {
				if($size_str == $k) {	//相同尺寸就不需要转换,只需要改名
					break;
				} else {
					$resize_to = $k;
					break;
				}
			}
		}

		//标志: 是否生成新尺寸的图片;不用生成新图片时,只需要把原文件改名就行了
		$need_resize = true;
		if(empty($resize_to)) {
			$need_resize = false;
			$resize_to = $size_str;
		}

		$new_size = explode('_', $resize_to);
		$file_part = getFilenamePart($p_img);
		$new_name = $file_part['dirname'] . '/' . $file_part['filename'] . '_' . $resize_to . '.' . $file_part['extname'];

		if($need_resize) {
			// resizeImg($p_img, $new_name, $new_size[0], $new_size[1]);
			import('@.ORG.Image');
			Image::thumb($p_img, $new_name, '', $new_size[0], $new_size[1]);
		} else {
			rename($p_img, $new_name);
		}
		return $new_name;
	}//end _resizeImg()

	/*
	 * 根据图片尺寸改变文件名
	 *
	 * @return string 新文件名: 原文件名_宽_高.扩展名
	 */
	private function _renameByImgSize($p_img) {
		$img_size = getimagesize($p_img);
		$ext_name = substr(strrchr($p_img, '.'), 1);
		$name = substr($p_img, 0, -(strlen($ext_name) +1));
		$new_name = $name . '_' . $img_size[0] . '_' . $img_size[1] . '.' . $ext_name;
		rename($p_img, $new_name);
// $rename_success = rename($p_img, $new_name);
// echo $rename_success ? 'Success!' : "Failed!<br />$p_img<br />$new_name";
		return $new_name;
	}//end _renameByImgSize()

	// 谷哥Play图片链接换成最大的那张图
	private function _chgGooplayImgSize($p_img) {
		$size = substr(strrchr($p_img, '='), 1);
		$url_prefix = substr($p_img, 0, -(strlen($size)));

		$new_size = 0;
		switch(strtolower(substr($size, 0, 1))) {
			case 'w' :
				$new_size = 'w512';
				break;
			case 'h' :
				$new_size = 'h1280';
				break;
			default :
				break;
		}

		$new_name = '';
		if($new_size) {
			$new_name = $url_prefix . $new_size;
		} else {
			$new_name = $p_img;
		}

		return $new_name;
	}//end _chgGooplayImgSize()

	// 根据图片的信息重命名扩展名
	private function _imgExtRename($p_img) {
		$img_info = getimagesize($p_img);
		if(false == $img_info) {
			return false;
		}
		$ext_name = image_type_to_extension($img_info[2]);
		$file_part = getFilenamePart($p_img);
		$new_name = $file_part['dirname'] . '/' . $file_part['filename'] . $ext_name;

		$rename_success = rename($p_img, $new_name);
		return $rename_success ? $new_name : $p_img;
	}//end _imgExtRename()

	/*
	 * 调用图片的处理方法
	 *
	 * @func 方法名
	 * @img  图片全路径
	 *
	 * @return
	 */
	private function _imgTrigger($func, $img) {
		if($func && method_exists($this, $func)) {
			$this->$func($img);
		}

		return;
	}//end _imgTrigger()

	/*
	 * 删除指定文件
	 *
	 * @p_file      要删除的文件
	 * @is_ab_path  文件路径是否绝对路径;相对路径是从网站根目录开始
	 *
	 * @return
	 */
	private function _unlink($p_file, $is_ab_path = false) {
		if(!$is_ab_path) {
			$p_file . BASE_PATH . $p_file;
		}

		//@unlink($p_file);

		return;
	}//end _unlink()

	/*
	 * 装载解IPA文件所需的类库文件
	 *
	 *
	 * @return
	 */
	private function _importIpaUtil() {
		if(!$this->ipa_util_loaded) {
			$path = '@.ORG.AnalyzeIpa.';

			$file_list = array(
				'AnalyzeIpa',
				'CFBinaryPropertyList',
				'CFType',
				'CFTypeDetector',
				'IOException',
				'PListException',
				'CFPropertyList',
			);

			foreach($file_list as $v) {
				import($path . $v);
			}

			$this->ipa_util_loaded = true;
		}
	}//end _importIpaUtil()

}
?>
