<?php
/////////////////////////////////////////////////////////////////
// SpeedPHP中文PHP框架, Copyright (C) 2008 - 2010 SpeedPHP.com //
/////////////////////////////////////////////////////////////////

/**
 * spController 基础控制器程序父类 应用程序中的每个控制器程序都应继承于spController
 */
class spController {

	/**
	 * 视图对象
	 */
	public $v;

	/**
	 * 赋值到模板的变量
	 */
	private $__template_vals = array();

	/**
	 * 构造函数
	*/
	public function __construct()
	{
		if(TRUE == $GLOBALS['G_SP']['view']['enabled']){
			$this->v = spClass('spView');
		}
	}

	/**
	 *
	 * 跳转程序
	 *
	 * 应用程序的控制器类可以覆盖该函数以使用自定义的跳转程序
	 *
	 * @param $url  需要前往的地址
	 * @param $delay   延迟时间
	 */
	public function jump($url, $delay = 0,$body=''){
		$content = "<html><head><meta http-equiv='refresh' content='{$delay};url={$url}'></head><body>{$body}</body></html>";
		if(TRUE == $GLOBALS['G_SP']['optimizer']['gzip']){
			$gzip_compression_level = $GLOBALS['G_SP']['optimizer']['gzip_compression_level']?$GLOBALS['G_SP']['optimizer']['gzip_compression_level']:9;
			$content = gzencode($content,$gzip_compression_level);
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
		}
		echo $content;
		exit;
	}

	/**
	 *
	 * 错误提示程序
	 *
	 * 应用程序的控制器类可以覆盖该函数以使用自定义的错误提示
	 *
	 * @param $msg   错误提示需要的相关信息
	 * @param $url   跳转地址
	 */
	public function error($msg, $url = ''){
		$url = empty($url) ? "window.history.back();" : "location.href=\"{$url}\";";
		$content = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"{$msg}\");{$url}}</script></head><body onload=\"sptips()\"></body></html>";
		if(TRUE == $GLOBALS['G_SP']['optimizer']['gzip']){
			$gzip_compression_level = $GLOBALS['G_SP']['optimizer']['gzip_compression_level']?$GLOBALS['G_SP']['optimizer']['gzip_compression_level']:9;
			$content = gzencode($content,$gzip_compression_level);
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
		}
		echo $content;
		exit;
	}

	/**
	 *
	 * 成功提示程序
	 *
	 * 应用程序的控制器类可以覆盖该函数以使用自定义的成功提示
	 *
	 * @param $msg   成功提示需要的相关信息
	 * @param $url   跳转地址
	 */
	public function success($msg, $url = ''){
		$url = empty($url) ? "window.history.back();" : "location.href=\"{$url}\";";
		$content = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"{$msg}\");{$url}}</script></head><body onload=\"sptips()\"></body></html>";
		if(TRUE == $GLOBALS['G_SP']['optimizer']['gzip']){
			$gzip_compression_level = $GLOBALS['G_SP']['optimizer']['gzip_compression_level']?$GLOBALS['G_SP']['optimizer']['gzip_compression_level']:9;
			$content = gzencode($content,$gzip_compression_level);
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
		}
		echo $content;
		exit;
	}

	public static function error404(){
		if(SP_DEBUG){
			global $__controller, $__action;
			spError("路由错误，请检查控制器目录下是否存在该控制器".htmlspecialchars($__controller)."与动作".htmlspecialchars($__action)."。");
		}else{
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			import(APP_PATH.'/themes/puzzing/errorpage/404.html');
		}
		exit;
	}
	/**
	 * 魔术函数，获取赋值作为模板内变量
	 */
	public function __set($name, $value)
	{
		if(TRUE == $GLOBALS['G_SP']['view']['enabled'] && false !== $value){
			$this->v->engine->assign(array($name=>$value));
		}
		$this->__template_vals[$name] = $value;
	}


	/**
	 * 魔术函数，返回已赋值的变量值
	 */
	public function __get($name)
	{
		return $this->__template_vals[$name];
	}

	/**
	 * 输出模板
	 *
	 * @param $tplname   模板路径及名称
	 * @param $output   是否直接显示模板，设置成FALSE将返回HTML而不输出
	 */
	public function display($tplname, $output = TRUE)
	{
		@ob_start();
		if(TRUE == $GLOBALS['G_SP']['view']['enabled']){
			$this->v->display($tplname);
		}else{
			extract($this->__template_vals);
			require($tplname);
		}
		if( TRUE != $output )return ob_get_clean();
	}

	public function ajax_response($response){
		if($response['success']){
			$this->ajax_success_response($response['data'], $response['message']);
		}else{
			$this->ajax_failed_response($response['message']);
		}
	}

	public function ajax_success_response($data,$message){
		$response = array('success' => true, 'data' => $data, 'message'=>$message);
		$content = json_encode($response);
		if(TRUE == $GLOBALS['G_SP']['optimizer']['gzip']){
			$gzip_compression_level = $GLOBALS['G_SP']['optimizer']['gzip_compression_level']?$GLOBALS['G_SP']['optimizer']['gzip_compression_level']:9;
			$content = gzencode($content,$gzip_compression_level);
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
		}
		echo $content;
		exit();
	}

	public function ajax_failed_response($message='failed_msg',$data=null){
		$response = array('success' => false,'message'=>$message,'data'=>$data);
		$content = json_encode($response);
		if(TRUE == $GLOBALS['G_SP']['optimizer']['gzip']){
			$gzip_compression_level = $GLOBALS['G_SP']['optimizer']['gzip_compression_level']?$GLOBALS['G_SP']['optimizer']['gzip_compression_level']:9;
			$content = gzencode($content,$gzip_compression_level);
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
		}
		echo $content;
		exit();
	}

	public function ajax_echo($message){
		$content = $message;
		if(TRUE == $GLOBALS['G_SP']['optimizer']['gzip']){
			$gzip_compression_level = $GLOBALS['G_SP']['optimizer']['gzip_compression_level']?$GLOBALS['G_SP']['optimizer']['gzip_compression_level']:9;
			$content = gzencode($content,$gzip_compression_level);
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
		}
		echo $content;
		exit();
	}

	/**
	 * 自动输出页面
	 * @param tplname 模板文件路径
	 */
	public function auto_display($tplname)
	{
		if( TRUE != $this->v->displayed && FALSE != $GLOBALS['G_SP']['view']['auto_display']){
			if( method_exists($this->v->engine, 'templateExists') && TRUE == $this->v->engine->templateExists($tplname))$this->display($tplname);
		}
	}

	/**
	 * 魔术函数，实现对控制器扩展类的自动加载
	 */
	public function __call($name, $args)
	{
		if(in_array($name, $GLOBALS['G_SP']["auto_load_controller"])){
			return spClass($name)->__input($args);
		}elseif(!method_exists( $this, $name )){
			spError("方法 {$name}未定义！<br />请检查是否控制器类(".get_class($this).")与数据模型类重名？");
		}
	}

	/**
	 * 获取模板引擎实例
	 */
	public function getView()
	{
		$this->v->addfuncs();
		return $this->v->engine;
	}
	/**
	 * 设置当前用户的语言
	 * @param $lang   语言标识
	 */
	public function setLang($lang)
	{
		if( array_key_exists($lang, $GLOBALS['G_SP']["lang"]) ){
			@ob_start();
			$domain = ('www.' == substr($_SERVER["HTTP_HOST"],0,4)) ? substr($_SERVER["HTTP_HOST"],4) : $_SERVER["HTTP_HOST"];
			//setcookie($GLOBALS['G_SP']['sp_app_id']."_SpLangCookies", $lang, time()+31536000, '/', $domain ); // 一年过期
			spClass('Cookie')->set_data($GLOBALS['G_SP']['sp_app_id']."_SpLangCookies",$lang,31536000);
			$_SESSION[$GLOBALS['G_SP']['sp_app_id']."_SpLangSession"] = $lang;
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * 获取当前用户的语言
	 */
	public function getLang()
	{
		$lang = spClass('Cookie')->get_data($GLOBALS['G_SP']['sp_app_id']."_SpLangCookies");
		if(!$lang) $lang = $_SESSION[$GLOBALS['G_SP']['sp_app_id']."_SpLangSession"];
		if(!$lang) $lang = $GLOBALS['G_SP']['lang']['default'];
		return $lang;
	}
}

/**
 * spArgs
 * 应用程序变量类
 * spArgs是封装了$_GET/$_POST、$_COOKIE等，提供一些简便的访问和使用这些
 * 全局变量的方法。
 */

class spArgs {
	/**
	 * 在内存中保存的变量
	 */
	private $args = null;
	private $security;

	/**
	 * 构造函数
	 *
	 */
	public function __construct(){
		if(TRUE == $GLOBALS['G_SP']['security']['xss_clean']){
			$this->security = spClass('Security');
		}
		$this->args = $_REQUEST;
	}

	/**
	 * 获取应用程序请求变量值，同时也可以指定获取的变量所属。
	 *
	 * @param name    获取的变量名称，如果为空，则返回全部的请求变量
	 * @param default    当前获取的变量不存在的时候，将返回的默认值
	 * @param method    获取位置，取值GET，POST，COOKIE
	 */
	public function get($name = null, $default = FALSE, $method = null, $xss='TRUE')
	{
		if(null != $name){
			if( $this->has($name) ){
				if( null != $method ){
					switch (strtolower($method)) {
						case 'get':
							return $this->security_get($_GET[$name],$xss);
						case 'post':
							return $this->security_get($_POST[$name],$xss);
						case 'cookie':
							return $this->security_get($_COOKIE[$name],$xss);
					}
				}
				return $this->security_get($this->args[$name],$xss);
			}else{
				return (FALSE === $default) ? FALSE : $default;
			}
		}else{
			return $this->args;
		}
	}

	private function security_get($input,$xss='TRUE'){
		if($xss=='false'){
			return $input;
		}
		if(TRUE == $GLOBALS['G_SP']['security']['xss_clean'] && $this->security){
			return $this->security->xss_clean($input);
		}
	}

	/**
	 * 设置（增加）环境变量值，该名称将覆盖原来的环境变量名称
	 *
	 * @param name    环境变量名称
	 * @param value    环境变量值
	 */
	public function set($name, $value)
	{
		$this->args[$name] = $value;
	}

	/**
	 * 检测是否存在某值
	 *
	 * @param name    待检测的环境变量名称
	 */
	public function has($name)
	{
		return isset($this->args[$name]);
	}

	/**
	 * 构造输入函数，标准用法
	 * @param args    环境变量名称的参数
	 */
	public function __input($args = -1)
	{
		if( -1 == $args )return $this;
		@list( $name, $default, $method, $xss ) = $args;
		return $this->get($name, $default, $method, $xss);
	}

	/**
	 * 获取请求字符
	 */
	public function request(){
		return $_SERVER["QUERY_STRING"];
	}
}