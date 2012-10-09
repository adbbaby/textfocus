<?php
return array(
	'LOAD_EXT_CONFIG' => 'db',	//加载扩展配置文件
	'APP_AUTOLOAD_PATH' =>'@ORG.Uitl',
	'APP_GROUP_LIST' => 'Home,Admin',
	'DEFAULT_GROUP' =>'Home',
	
	'URL_MODEL'=>1,
	'LOG_RECORD' => true,
	'LOG_LEVEL'=> 'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL',
	
	'DB_FIELDTYPE_CHECK'=>true,
	'TMPL_STRIP_SPACE'  => true,
	
	'DEFAULT_THEME'     =>'default',
	'VAR_PAGE'          =>'p',
	'URL_PARAMS_BIND'=>false,
	
	'LANG_SWITCH_ON' => true,
	'DEFAULT_LANG' => 'zh-cn', // 默认语言
	'LANG_AUTO_DETECT' => true, // 自动侦测语言
	'LANG_LIST'=>'en-us,zh-cn,zh-tw'//必须写可允许的语言列表
);
?>