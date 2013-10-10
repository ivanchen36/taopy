<?php echo $setting_header;?>
<div class="admin_header">
	<div class="logo">
		<img
			src="<?php echo base_url('themes/admin/images/admin_logo.png');?>" />
	</div>
	<div class="login_info">
		<strong><?php echo $current_user['nickname']?> </strong> <span>[administrator]</span>
		<a href="<?php echo spUrl('welcome','index');?>" target="_blank"><?php echo T('admin_frontpage');?></a> <a
			href="<?php echo spUrl('admin','logout');?>"><?php echo T('admin_logout');?></a>
	</div>
</div>
<div id="content" class="admin_main" style="width: auto">
	<div class="admin_menu">
		<div id="Scroll">
			<div id="menu" class="menu_list">
				<dl>
					<dt>
						<i class="menu_icon menu_home"></i><?php echo T('admin_dashboard');?>
					</dt>
					<dd>
						<ul>
							<li
							<?php echo ($current_action == 'dashboard') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','dashboard');?>"><i
									class="arrow_icon"></i><?php echo T('admin_dashboard');?></a></li>
						</ul>
					</dd>
				</dl>
				<dl class="active">
					<dt>
						<i class="menu_icon menu_site"></i><?php echo T('admin_site_setting');?>
					</dt>
					<dd>
						<ul>
							<li
							<?php echo ($current_action == 'setting_basic') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','setting_basic');?>"><i
									class="arrow_icon"></i><?php echo T('admin_setting_basic');?></a></li>
									<li
							<?php echo ($current_action == 'ui_layout') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','ui_layout');?>"><i
									class="arrow_icon"></i><?php echo T('admin_ui_layout');?></a></li>
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>
						<i class="menu_icon menu_content"></i><?php echo T('admin_content_setting');?>
					</dt>
					<dd>
						<ul>
							<li><a href="#"
								url="<?php echo spUrl('admin','homepage_slide');?>"><i
									class="arrow_icon"></i><?php echo T('admin_homepage_slide');?></a></li>
							<li
							<?php echo ($current_action == 'category_list') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','category_list');?>"><i
									class="arrow_icon"></i><?php echo T('admin_category_list');?></a></li>
							<li
							<?php echo ($current_action == 'tag_list') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','tag_list');?>"><i
									class="arrow_icon"></i><?php echo T('admin_tag_list');?></a></li>
							<li
							<?php echo ($current_action == 'item_list' || $current_action == 'item_edit') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','item_list',array('act'=>'search','is_show'=>'0'));?>"><i
									class="arrow_icon"></i><?php echo T('admin_item_list');?></a></li>
							<li
							<?php echo ($current_action == 'smile_list' || $current_action == 'smile_edit') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','smile_list');?>">
								<i class="arrow_icon"></i><?php echo T('admin_smile_list');?></a></li>
							<li
							<?php echo ($current_action == 'ads_manage' || $current_action == 'ads_manage') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','ads_manage');?>">
								<i class="arrow_icon"></i><?php echo T('admin_ad_manage');?></a></li>
						</ul>
					</dd>
				</dl>
				<?php if($lang=='zh_cn'):?>
				<dl>
					<dt>
						<i class="menu_icon menu_content"></i>采集工具
					</dt>
					<dd>
						<ul>
							<li><a href="#"
								url="<?php echo spUrl('admin','gathering');?>"><i
									class="arrow_icon"></i>淘宝采集</a></li>
						</ul>
					</dd>
				</dl>
				<?php endif;?>
				<dl>
					<dt>
						<i class="menu_icon menu_user"></i><?php echo T('admin_user_setting');?>
					</dt>
					<dd>
						<ul>
							<li
							<?php echo ($current_action == 'user_list') ?  ' class="active"' : ''; ?>><a
								href="#" url="<?php echo spUrl('admin','user_list');?>"><i
									class="arrow_icon"></i><?php echo T('admin_user_list');?></a></li>
						</ul>
					</dd>
				</dl>
				
				<dl>
					<dt>
						<i class="menu_icon menu_tools"></i><?php echo T('admin_misc');?>
					</dt>
					<dd>
						<ul>
						<li><a href="#"
								url="<?php echo spUrl('admin','update_cache');?>"><i
									class="arrow_icon"></i><?php echo T('admin_update_cache');?></a></li>
							<li><a href="#"
								url="<?php echo spUrl('admin','system_update');?>"><i
									class="arrow_icon"></i><?php echo T('admin_system_update');?></a></li>
							<li><a href="#"
								url="<?php echo spUrl('admin','database_management');?>"><i
									class="arrow_icon"></i><?php echo T('admin_database_management');?></a></li>
							<li><a href="#"
								url="<?php echo spUrl('admin','frindlink_list');?>"><i
									class="arrow_icon"></i><?php echo T('admin_frindlink_list');?></a></li>
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>
						<a href="<?php echo spUrl('admin','logout');?>"><i
							class="menu_icon menu_out"></i><?php echo T('admin_logout');?></a>
					</dt>
				</dl>
			</div>
		</div>
		<a href="javascript:;" id="openClose"
			style="outline-style: none; outline-color: invert; outline-width: medium;"
			hideFocus="hidefocus" class="opens" title="展开与关闭"><span
			class="hidden">展开</span> </a>

	</div>
	<div class="admin_container">
		<div class="admin_crumbs">
			<div class="pull-left"><?php echo T('admin_site_setting');?></div>
		</div>
		<div class="admin_content">
			<iframe id="rightMain" src="<?php echo spUrl('admin','dashboard');?>"
				width="100%" frameborder="false" scrolling="auto"
				style="border-top-style: none; border-right-style: none; border-bottom-style: none; border-left-style: none; border-width: initial; border-color: initial; margin-bottom: 30px; height: 155px;"
				allowtransparency="true"></iframe>
		</div>
	</div>
</div>
<script type="text/javascript">
if(!Array.prototype.map)
Array.prototype.map = function(fn,scope) {
  var result = [],ri = 0;
  for (var i = 0,n = this.length; i < n; i++){
	if(i in this){
	  result[ri++]  = fn.call(scope ,this[i],i,this);
	}
  }
return result;
};

var getWindowSize = function(){
return ["Height","Width"].map(function(name){
  return window["inner"+name] ||
	document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
});
}
window.onload = function (){
	if(!+"\v1" && !document.querySelector) { // for IE6 IE7
	  document.body.onresize = resize;
	} else { 
	  window.onresize = resize;
	}
	function resize() {
		wSize();
		return false;
	}
}

function wSize(){
	//这是一字符串
	var str=getWindowSize();
	var strs= new Array(); //定义一数组
	strs=str.toString().split(","); //字符分割
	var heights = strs[0]-150,Body = $('body');$('#rightMain').height(heights);   
	//iframe.height = strs[0]-46;
	if(strs[1]<980){
		$('.admin_header').css('width',980+'px');
		$('#content').css('width',980+'px');
		Body.attr('scroll','');
		Body.removeClass('objbody');
	}else{
		$('.admin_header').css('width','auto');
		$('#content').css('width','auto');
		Body.attr('scroll','no');
		Body.addClass('objbody');
	}
	var openClose = $("#rightMain").height()+39;
	$('#center_frame').height(openClose+9);
	$("#openClose").height(openClose+30);	
	$("#Scroll").height(openClose-20);
	windowW();
}
wSize();

$("#openClose").click(function(){
	if($(this).data('clicknum')==1) {
		$("html").removeClass("on");
		$(".admin_menu").removeClass("admin_menu_on");
		$(this).removeClass("closes");
		$(this).data('clicknum', 0);
		$("#Scroll").show();
	} else {
		$(".admin_menu").addClass("admin_menu_on");
		$(this).addClass("closes");
		$("html").addClass("on");
		$(this).data('clicknum', 1);
		$("#Scroll").hide();
	}
	return false;

});
function windowW(){
	if($('#Scroll').height()<$("#admin_menu").height()){
		$(".scroll").show();
	}else{
		$(".scroll").hide();
	}
}
windowW();

function menuScroll(num){
	var Scroll = document.getElementById('Scroll');
	if(num==1){
		Scroll.scrollTop = Scroll.scrollTop - 60;
	}else{
		Scroll.scrollTop = Scroll.scrollTop + 60;
	}
}
$("#menu").find("dt").click(function(){
$(this).parent().parent().find("dd").hide();
$(this).parent().find("dd").fadeIn(500);
$(this).parent().parent().find("dl").removeClass("active");
$(this).parent().addClass("active");
})
$("#menu").find("li").find("a").click(function(){
var abc=$(this).attr("url");
$("#rightMain").attr("src",abc)
$("#menu").find("li").removeClass("active");
$(this).parent().addClass("active");
})
</script>
</body>
</html>
