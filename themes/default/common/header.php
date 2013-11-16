<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="http://l.tbcdn.cn/apps/top/x/sdk.js?appkey=21627539"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title?$page_title.' ':'';?><?php echo $settings['seo_setting']['page_title'];?> </title>
<meta name="keywords" content="<?php echo $page_keyword?$page_keyword:'';?> <?php echo $settings['seo_setting']['page_keywords'];?>" />
<meta name="description" content="<?php echo $page_description?$page_description:$settings['seo_setting']['page_description'];?>" />
<meta name="generator" content="94ivan V1.0" />
<meta name="copyright" content="2012-2013 94ivan.com" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="icon" href="/favicon.ico" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" href="/favicon.ico" type="image/x-icon" />

<link href="<?php echo base_url();?>themes/<?php echo $themes;?>/css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>themes/<?php echo $themes;?>/css/color-<?php echo $settings['ui_styles']['color'];?>.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>themes/<?php echo $themes;?>/css/text.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>themes/<?php echo $themes;?>/css/970_12_10.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>themes/<?php echo $themes;?>/css/layout.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>themes/<?php echo $themes;?>/css/pintuxiu_<?php echo $lang;?>.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/css/imgareaselect-default.css" type="text/css" rel="stylesheet"/>
<!--[if lte IE 6]>
    <link href="<?php echo base_url();?>themes/<?php echo $themes;?>/css/ie6.css" rel="stylesheet"/>
<![endif]-->
<script>
var base_url = '<?php echo base_url();?>';
var min_fetch_width = <?php echo $settings['file_setting']['fetch_image_size_w']?$settings['file_setting']['fetch_image_size_w']:200;?>;
var min_fetch_height = <?php echo $settings['file_setting']['fetch_image_size_h']?$settings['file_setting']['fetch_image_size_h']:200;?>;
var sina_key = '<?php echo $settings['api_setting']['Sina']['APPKEY']?$settings['api_setting']['Sina']['APPKEY']:'3190596186';?>';
var qq_key = '<?php echo $settings['api_setting']['QQ']['APPKEY']?$settings['api_setting']['QQ']['APPKEY']:'100278689';?>';
</script>
<!-- Load: Always -->
<script src="<?php echo base_url();?>assets/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/onightjar.mini.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js" type="text/javascript"></script>

<!--[if lt IE 10]>
<script src="<?php echo base_url();?>assets/js/PIE.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 6]>
	<script src="<?php echo base_url();?>assets/js/letskillie6.zh_CN.pack.js"></script>
<![endif]-->
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F041acadb91b28866a1e08b7caad57506' type='text/javascript'%3E%3C/script%3E"));
</script>
<script>
<?php if(!$current_user['user_id']&&in_array($current_controller,array('welcome','pin'))&&$settings['ui_layout']['login_reminder']):?>
$(document).ready(function() {
	($.sclogin()).start();
});
<?php endif;?>
</script>
<?php if($current_user['user_type']=='3'||$current_user['user_type']=='2'):?>
<script type="text/javascript">
$.ajax({
	type: "post",
	url: 'http://www.onightjar.com/index.php?c=pzupgrade&a=tkneedupdate&pzrelease=<?php echo $release;?>&pzversion=<?php echo $version;?>',
	dataType: 'json'
}).error(function() {
}).success(function(result) {
	if (result.success === !0) {
		show_message('error','<a href="<?php echo spUrl("admin","index"); ?>" target="_blank" style="color:white;">'+result.message+'</a>',false,0);
	} else {
	}
});

</script>
<?php endif;?>
</head> 
<body id="body">
<div class="main bg_common" id="header">
	<div class="g160 mt20"><a href="<?php echo spUrl("welcome","index"); ?>"><img src="<?php echo base_url("themes/{$themes}/images/logo.png");?>"/></a></div>
	<div class="nav">
		<ul class="nav-items f_l">
			<?php if($current_user['user_id']):?>
	   		<li <?php echo ($current_controller == 'my'&&$current_action == 'favorite_share')?'class="selected"':'';?>><a href="<?php echo spUrl("my","favorite_share"); ?>"><?php echo T('my_favorite');?></a><b></b></li>
	   		<li <?php echo ($current_controller == 'my'&&$current_action == 'shares')?'class="selected"':'';?>><a href="<?php echo spUrl("my","shares"); ?>"><?php echo T('my_pin');?></a><b></b></li>
    		<?php endif;?>
      		<li <?php echo ($current_controller == 'welcome') ? 'class="selected"':'';?>><a href="<?php echo spUrl("welcome","index"); ?>"><?php echo T('home');?></a><b></b></li>
     		<li <?php echo ($current_controller == 'pin' || $current_controller == 'detail')?'class="selected"':'';?>><a href="<?php echo spUrl("pin","index"); ?>"><?php echo T('pin');?></a><b></b></li>
	   		<li <?php echo ($current_controller == 'album')?'class="selected"':'';?>><a href="<?php echo spUrl("album","index");?>"><?php echo T('album');?></a><b></b></li>
	   		<!-- <li <?php echo ($current_controller == 'faq')?'class="selected"':'';?>><a href="<?php echo spUrl("faq","about_us"); ?>"><?php echo T('others');?></a><b></b></li> -->
    	</ul>
	</div>
	<div class="userbar <?php echo $current_user['user_id']?'logged':''?>" id="userbar" style="display: block; ">
	<?php $userbar_need_scroll = in_array($current_controller,array('welcome','pin'));?>
		<ul <?php echo ($userbar_need_scroll)?'class="scroll"':'';?>>
			<?php if(!$current_user['user_id']):?>
			<li class="login menu" id="login_point">
				<a href="/social-go-vendor-Sina.html" class="parent"><?php echo T('login');?></a>
				<ul>
            		<li><a href="/social-go-vendor-Sina.html"><?php echo T('click_login');?></a><b></b></li>
                </ul>
			</li>
         	<li class="register menu">
         		<a href="http://weibo.com/signup/signup.php?from=zw&appsrc=1RwDvl&backurl=https://api.weibo.com/2/oauth2/authorize?client_id=1157778355&response_type=code&display=default&redirect_uri=http://www.94ivan.com/index.php?c=social&amp;a=callback&amp;vendor=Sina&from=&with_cookie=" class="parent"><?php echo T('register');?></a>
         		<ul>
            		<li><a href="http://weibo.com/signup/signup.php?from=zw&appsrc=1RwDvl&backurl=https://api.weibo.com/2/oauth2/authorize?client_id=1157778355&response_type=code&display=default&redirect_uri=http://www.94ivan.com/index.php?c=social&amp;a=callback&amp;vendor=Sina&from=&with_cookie="><?php echo T('join');?></a><b></b></li>
                </ul>
         	</li>
         	<?php else:?>
         	<li class="profile menu">
				<a href="<?php echo spUrl("my","shares");?>" class="parent"><img src="<?php echo $current_user['avatar_remote'];?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_middle.jpg';"/></a>
				<ul>
            		<li><a href="<?php echo spUrl("my","shares");?>"><?php echo T('my_home');?></a></li>
	   				<?php if($current_user['user_type']==3):?>
            		<li><a href="<?php echo spUrl("admin","index"); ?>"><?php echo T('admin_console');?></a></li>
            		<?php endif;?>
                </ul>
			</li>
			<?php if($can_post):?>
			<li class="pinner menu" data-action="openPublishSelectDialog">
				<a href="javascript:void(0);" class="parent"><?php echo T('add_pin')?></a>
				<ul>
            		<li><a href="javascript:void(0);" data-action="openPublishSelectDialog"><?php echo T('add_pin');?></a><b></b></li>
                </ul>
			</li>
			<?php endif;?>
         	<li class="logout menu">
				<a href="<?php echo spUrl('webuser','logout');?>" class="parent"><?php echo T('logout');?></a>
				<ul>
            		<li><a href="<?php echo spUrl('webuser','logout');?>"><?php echo T('logout');?></a><b></b></li>
                </ul>
			</li>
         	
         	<?php endif;?>
		</ul>
    </div>
</div>
<div class="clear">&nbsp;</div>
<div class="container header">
	<div class="main" id="nav-cat">
		<?php echo $tpl_nav;?>
    	<div class="nav-srh f_r">
			<form action="<?php echo spUrl('pin','index');?>" method="post" enctype="application/x-www-form-urlencoded">
            	<input type="text" name="keyword" class="f_l" title="<?php echo T('tip_search');?>" />
            	<button type="submit"><?php echo T('search');?></button>
        	</form>
		</div>
		<div class="f_r" style="height: 40px;line-height: 40px;">
			<iframe width="63" height="24" class="mt10 mr10" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" border="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&width=63&height=24&uid=2793533962&style=1&btn=red&dpc=1"></iframe>
		</div>
	</div>
</div>

	
