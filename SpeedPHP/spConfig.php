<?php
/////////////////////////////////////////////////////////////////
// SpeedPHP中文PHP框架, Copyright (C) 2008 - 2010 SpeedPHP.com //
/////////////////////////////////////////////////////////////////

/**
 * spConfig
 *
 * SpeedPHP应用框架的系统默认配置
 */

return array(
	'mode' => 'realse', // 应用程序模式，默认为调试模式
	'sp_core_path' => SP_PATH.'/Core', // 框架MVC核心目录
	'sp_drivers_path' => SP_PATH.'/Drivers', // 框架各类驱动文件目录
	'sp_include_path' => array( SP_PATH.'/Extensions' ), // 框架扩展功能载入路径

	'auto_load_controller' => array('spArgs'), // 控制器自动加载的扩展类名
	'auto_load_model' => array('spPager','spVerifier','spCache','spLinker'), // 模型自动加载的扩展类名

	'sp_error_show_source' => 5, // spError显示代码的行数
	'sp_error_throw_exception' => FALSE, // 是否抛出异常
	'allow_trace_onrelease' => FALSE, // 是否允许在部署模式下输出调试信息
	'sp_notice_php' => SP_PATH."/Misc/notice.php", // 框架默认的错误提示程序

	'inst_class' => array(), // 已实例化的类名称
	'import_file' => array(), // 已经载入的文件
	'sp_access_store' => array(), // 使用spAccess保存到内存的变量
	'view_registered_functions' => array(), // 视图内注册的函数记录

	'default_controller' => 'index', // 默认的控制器名称
	'default_action' => 'index',  // 默认的动作名称
	'url_controller' => 'c',  // 请求时使用的控制器变量标识
	'url_action' => 'a',  // 请求时使用的动作变量标识

	'auto_session' => TRUE, // 是否自动开启SESSION支持
	//'dispatcher_error' => "spError('路由错误，请检查控制器目录下是否存在该控制器/动作。');", // 定义处理路由错误的函数
	'dispatcher_error' => 'spController::error404();', // 定义处理路由错误的函数
	'auto_sp_run' => FALSE, // 是否自动执行spRun函数

	'sp_cache' => APP_PATH.'/tmp', // 框架临时文件夹目录
	'sp_app_id' => '',  // 框架识别ID
	'controller_path' => APP_PATH.'/controller', // 用户控制器程序的路径定义
	'model_path' => APP_PATH.'/model', // 用户模型程序的路径定义


	'url' => array( // URL设置
		'url_path_info' => false, // 是否使用path_info方式的URL
		'url_path_base' => '', // URL的根目录访问地址，默认为空则是入口文件index.php
),

	'db' => array(  // 数据库连接配置
		'driver' => 'mysql',   // 驱动类型
		'host' => 'localhost', // 数据库地址
		'port' => 3306,        // 端口
		'login' => 'root',     // 用户名
		'password' => '',      // 密码
		'database' => '',      // 库名称
		'prefix' => '',           // 表前缀
		'persistent' => FALSE,    // 是否使用长链接
	),
	'db_driver_path' => '', // 自定义数据库驱动文件地址
	'db_spdb_full_tblname' => TRUE, // spDB是否使用表全名

	'security' => array(
		'xss_clean' => true,
),

	'view' => array( // 视图配置
		'enabled' => TRUE, // 开启视图
		'config' =>array(
			'template_dir' => APP_PATH.'/themes', // 模板目录
),
		'engine_name' => 'speedy', // 模板引擎的类名称
		'engine_path' => SP_PATH.'/Drivers/speedy.php', // 模板引擎主类路径
		'xss_clean' => true,
),

	'html' => array( 
		'enabled' => TRUE, // 是否开启真实静态HTML文件生成器
		'file_root_name' => 'topic', // 静态文件生成的根目录名称，设置为空则是直接在入口文件的同级目录生成
		'safe_check_file_exists' => FALSE, // 获取URL时，检查物理HTML文件是否存在，如文件不存在，则返回安全的动态地址
),

	'lang' => array(), // 多语言设置，键是每种语言的名称，而值可以是default（默认语言），语言文件地址或者是翻译函数
// 同时请注意，在使用语言文件并且文件中存在中文等时，请将文件设置成UTF8编码
	'ext' => array(
		'spUrlRewrite' => array(
			'sep' => '-',
			'hide_default' => false,
			'args_path_info' => false,
			'suffix' => '.html',
			'map' => array(),
           'args' => array(),
		),

		'sessionAndCookie' => array(
			'cookie_prefix' => 'ptx_',
			'cookie_path' => '/',
			'cookie_domain' => '',
			'encryption_key' => '(@*^#%$&^##%!#$%&*!*@^^HGS%S%',
			'sess_match_ip' => false,
			'sess_expire_on_close' => true,
			'sess_expiration' => 7200,
			'sess_match_useragent' => false,
			'sess_cookie_name' => 'pt_session',
			'sess_encrypt_cookie' => true
		),

		'spLog' => array(
			'logsize'   => '10240000',      // 日志文件大小
			'logpath'   => APP_PATH.'/log', // 日志保存目录
			'logprefix' => 'log_',          // 日志文件前缀’
			'mail'      => 'ERROR',           // 是否发送日志邮件，取值"ALL"是全部日志都发送，取值'ERROR', 'WARN','NOTICE','INFO','DEBUG'任意一种是只发送该种日志，取值NULL是不发送日志
			'mailto'    => 'admin@94ivan.com', // 发送到的邮件地址
)

),
	'include_path' => array(
APP_PATH.'/include',
APP_PATH.'/extensions'
), // 用户程序扩展类载入路径
);
