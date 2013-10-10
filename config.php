<?php

$user_config = array (
  'product_info' => 
  array (
    'version' => '3.0',
    'build' => '0929',
  ),
  'vendors' => 
  array (
    0 => 'Taobao',
    1 => 'Sina',
    2 => 'Renren',
    3 => 'QQ',
    4 => 'Facebook',
    5 => 'Twitter',
  ),
  'optimizer' => 
  array (
    'gzip' => '1',
    'gzip_compression_level' => '',
  ),
  'db' => 
  array (
    'host' => 'localhost',
    'port' => '3306',
    'login' => 'ivan_tao',
    'password' => 'ivantao36',
    'database' => 'tao_ivan',
    'prefix' => 'tao_',
  ),
  'lang' => 
  array (
    'default' => 'zh_cn',
    'en' => '/home/ivan/web/tao/lang/en/lang.php',
    'zh_cn' => '/home/ivan/web/tao/lang/zh_cn/lang.php',
  ),
  'default_controller' => 'welcome',
  'launch' => 
  array (
    'router_prefilter' => 
    array (
      0 => 
      array (
        0 => 'spUrlRewrite',
        1 => 'setReWrite',
      ),
    ),
    'function_url' => 
    array (
      0 => 
      array (
        0 => 'spUrlRewrite',
        1 => 'getReWrite',
      ),
    ),
  ),
);
