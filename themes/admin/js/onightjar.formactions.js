(function($) {
	$.oValidate = function(id) {
		var list = {
				'save_share_form':save_share_validate,
				'register_form':register_validate,
				'social_register_form':social_register_validate,
				'update_userinfo':update_userinfo_validate,
				'update_password_form':update_password_form,
				'edit_share_form':edit_share_validate,
				'update_goodshop_form':update_goodshop_validate,
				'setting_forum_form':setting_forum_validate,
				'login_form':login_validate
		};
		if(id&&list[id]&&$('#'+id)) {
			$('#'+id).validate(list[id]);
		}
	};

	var save_share_validate = {
			rules : {	intro : {required : true,byteRangeLength:[4,500]}, 
				title : {required : true,byteRangeLength:[4,80]}
			},
			messages : {	intro : {required : "请为分享写个描述吧",byteRangeLength: "描述长度请设置在4-500个字符之间(1个中文汉字为2个字符)"},
					title : {required : "请为分享写个标题吧",byteRangeLength: "标题长度请设置在4-80个字符之间(1个中文汉字为2个字符)"}
			},
		submitHandler : function(form) {

			var images_arr = new Array();
			$('#publish_image_list li.selected').each(function() {
				if(!$(this).hasClass('cover')){
					images_arr.push($(this).attr('data-name'));
				}
	        });
			$('#save_share_form #all_files').val(images_arr.join("|"));
			$('#save_share_form').ajaxSubmit({
				url : $('#save_share_form').attr("data-url"),
				data : $('#save_share_form').formSerialize(),
				type : 'POST',
				dataType : 'json',
				beforeSubmit : function() {
					var filename = $('#save_share_form #cover_filename').val();
					if (filename == null || filename == '') {
						$('#ajax_share_message').html('您还没有分享图片');
						return false;
					}
					var album_select_id = $('#save_share_form #album_select_id').val();
					if (album_select_id == null || album_select_id == '' || album_select_id == 0) {
						$('#ajax_share_message').html('您必须选择一个专辑');
						return false;
					}
					$('#ajax_share_message').html('提交中，请稍候');
				},
				success : function(data) {
					if ($.trim(data.success) == "true") {
						//alert(data.message);
						$('#ajax_share_message').html(data.message);
						window.location.href = $('#save_share_form').attr("next-url");
					} else {
						$('#ajax_share_message').html(data.message);
					}
				},
				error : function() {
						alert("貌似服务器泡妞去了，要不你稍后试试～");
				}
			});
			return false;
		}
	};
	
	var edit_share_validate = {
			rules : {	intro : {required : true,byteRangeLength:[4,500]}, 
						title : {required : true,byteRangeLength:[4,80]}
					},
			messages : {	intro : {required : "请为分享写个描述吧",byteRangeLength: "描述长度请设置在4-500个字符之间(1个中文汉字为2个字符)"},
							title : {required : "请为分享写个标题吧",byteRangeLength: "标题长度请设置在4-80个字符之间(1个中文汉字为2个字符)"}
					},
			submitHandler : function(form) {
				$('#edit_share_form').ajaxSubmit({
					url : $('#data-actions').attr("data-editshare-url"),
					data : $('#edit_share_form').formSerialize(),
					type : 'POST',
					dataType : 'json',
					beforeSubmit : function() {
						var album_select_id = $('#edit_share_form .album_select_id').val();
						if (album_select_id == null || album_select_id == '' || album_select_id == 0) {
							$('#ajax_share_message').html('您必须选择一个专辑');
							return false;
						}
						$('#ajax_share_message').html('提交中，请稍候');
					},
					success : function(data) {
						if ($.trim(data.success) == "true") {
							$('#ajax_share_message').html(data.message);
							window.location.href = $('#edit_share_form').attr("next-url");
						} else {
							$('#ajax_share_message').html(data.message);
						}
					},
					error : function() {
							alert("貌似服务器泡妞去了，要不你稍后试试～");
					}
				});
				return false;
			}
		};
	
	var update_goodshop_validate = {
			rules : {	
				shop_desc : {required : true,byteRangeLength:[10,300]}
				},
			messages : {	
				shop_desc : {required : "请为您的好店写个描述吧",byteRangeLength: "描述长度请设置在10-300个字符之间(1个中文汉字为2个字符)"}
				},
			errorElement: "span",
			submitHandler : function(form) {
				$('#update_goodshop_form').ajaxSubmit({
					url : $('#data-actions').attr("data-editgoodshop-url"),
					data : $('#update_goodshop_form').formSerialize(),
					type : 'POST',
					dataType : 'json',
					beforeSubmit : function() {
						$('#ajax_share_message').html('提交中，请稍候');
					},
					success : function(data) {
						if ($.trim(data.success) == "true") {
							$('#ajax_share_message').html(data.message);
							window.location.href = $('#update_goodshop_form').attr("next-url");
						} else {
							$('#ajax_share_message').html(data.message);
						}
					},
					error : function() {
							alert("貌似服务器泡妞去了，要不你稍后试试～");
					}
				});
				return false;
			}
		};
	
	var update_staruser_validate = {
			rules : {	
				star_reason : {required : true,byteRangeLength:[10,300]}
				},
			messages : {	
				star_reason : {required : "请为写个描述吧",byteRangeLength: "描述长度请设置在10-300个字符之间(1个中文汉字为2个字符)"}
				},
			errorElement: "span",
			submitHandler : function(form) {
				$('#update_staruser_from').ajaxSubmit({
					url : $('#data-actions').attr("data-editstaruser-url"),
					data : $('#update_staruser_from').formSerialize(),
					type : 'POST',
					dataType : 'json',
					beforeSubmit : function() {
						$('#ajax_share_message').html('提交中，请稍候');
					},
					success : function(data) {
						if ($.trim(data.success) == "true") {
							$('#ajax_share_message').html(data.message);
							window.location.href = $('#update_goodshop_form').attr("next-url");
						} else {
							$('#ajax_share_message').html(data.message);
						}
					},
					error : function() {
							alert("貌似服务器泡妞去了，要不你稍后试试～");
					}
				});
				return false;
			}
	};
	
	
	var update_userinfo_validate = {
		rules: {
			nickname: { required: true,byteRangeLength:[4,20],remote: function(){return $('#data-actions').attr('data-ajax-updatenickname');} }
		},
		messages: {
			nickname: { required: "请输入昵称", byteRangeLength: "昵称长度请设置在4-20个字符之间(1个中文汉字为2个字符)",remote: "昵称已存在，请使用其它昵称"}
		},
		errorElement: "span",
		submitHandler: function(form) {
			$('#update_userinfo').ajaxSubmit({
					url: $('#data-actions').attr('data-updateuser-url'),
					data: $('#update_userinfo').formSerialize(),
					type: 'POST',
					dataType: 'json',
					beforeSubmit: function(){
					 	$('#update_userinfo #ajax_message').html('提交中，请稍候');
					},
					success: function(data) {
					 if ($.trim(data.result) == "true") {
					     $('#update_userinfo #ajax_message').html(data.message);
					     window.location.href = $('#data-actions').attr('data-mybasicsettings-url');
					 }else {
					  	$('#update_userinfo #ajax_message').html(data.message);
					 }
					 
				}
			});
			return false;
		}
	};
	
	var setting_forum_validate = {
			rules: {
				bbs_username: { required: true },
				bbs_password: { required: true }
			},
			messages: {
				bbs_username: { required: "请输入论坛用户昵称"},
				bbs_password: { required: "请输入论坛密码"}
			},
			errorElement: "span",
			submitHandler: function(form) {
				$('#setting_forum_form').ajaxSubmit({
						url: $('#data-actions').attr('data-forum-setting-url'),
						data: $('#setting_forum_form').formSerialize(),
						type: 'POST',
						dataType: 'json',
						beforeSubmit: function(){
						 	$('#setting_forum_form #ajax_message').html('提交中，请稍候');
						},
						success: function(data) {
						 if ($.trim(data.success) == "true") {
						     $('#setting_forum_form #ajax_message').html(data.message);
						     window.location.href = $('#data-actions').attr('data-mybasicsettings-url');
						 }else {
						  	$('#setting_forum_form #ajax_message').html(data.message);
						 }
						 
					}
				});
				return false;
			}
		};
	
	var update_password_form = {
			rules: {
				email: { required: true, email: true, remote: function(){ return $('#data-actions').attr('data-ajax-email');}},
				org_passwd: { required: true, rangelength: [6, 15] },
				new_passwd: { required: true, rangelength: [6, 15] },
				new_verify_passwd: { required: true, rangelength: [6, 15], equalTo: "#new_passwd" }
			},
			messages: {
				email: { required: "请输入有效的邮箱地址", email: "请输入有效的邮箱地址", remote: "邮箱已存在，请选择其它邮箱"},
				org_passwd: { required: "请输入密码", rangelength: "密码长度为6-15位"},
				new_passwd: { required: "请输入密码", rangelength: "密码长度为6-15位" },
				new_verify_passwd: { required: "请输入密码", rangelength: "密码长度为6-15位",equalTo: "两次输入的密码不一致" }
			},
			errorElement: "span",
			submitHandler: function(form) {
				$('#update_password_form').ajaxSubmit({
						url: $('#data-actions').attr('data-ajax-resetpasswd'),
						data: $('#update_password_form').formSerialize(),
						type: 'POST',
						dataType: 'json',
						beforeSubmit: function(){
						 	$('#update_password_form #ajax_message').html('提交中，请稍候');
						},
						success: function(data) {
						 if ($.trim(data.result) == "true") {
						     $('#update_password_form #ajax_message').html(data.message);
						     window.location.href = $('#data-actions').attr('data-mybasicsettings-url');
						 }else {
						  	$('#update_password_form #ajax_message').html(data.message);
						 }
						 
					}
				});
				return false;
			}
		};

	var login_validate = {
		rules : {
			email : {
				required : true,
				email : true
			},
			password : {
				required : true,
				minlength : 6
			}
		},
		errorElement: "span",
		messages : {
			email : {
				required : "请输入有效的邮箱地址",
				email : "请输入有效的邮箱地址"
			},
			password : {
				required : "请输入有效的密码",
				minlength : "请输入有效的密码"
			}
		},
		submitHandler : function(form) {
			$('#login_form').ajaxSubmit({
				url : $('#data-actions').attr('data-login-url'),
				data : $('#login_form').formSerialize(),
				type : 'POST',
				dataType : 'json',
				beforeSubmit : function() {
					$('#ajax_message').html('登录中，请稍候');
				},
				success : function(data) {
					if ($.trim(data.result) == "true") {
						$('#ajax_message').html(data.msg);
						//window.history.back();
						setTimeout(function(){
							window.location.href = $('#data-actions').attr('data-loginredirect-url');
						},1000);
						return true;
					} else {
						$('#ajax_message').html(data.msg);
						return false;
					}
				}
			});
			return false;
		}
	};
	
	var register_validate = {
		rules: {
			nickname: { required: true,byteRangeLength:[4,20],remote: function(){return $('#data-actions').attr('data-ajax-nickname');} },
			email: { required: true, email: true, remote: function(){ return $('#data-actions').attr('data-ajax-email');}},
			password: { required: true, rangelength: [6, 15] },
			passconf: { required: true, rangelength: [6, 15],equalTo: "#password" },
			terms: {"required":true}
		},
		messages: {
			nickname: { required: "请输入昵称", byteRangeLength: "昵称长度请设置在4-20个字符之间(1个中文汉字为2个字符)",remote: "昵称已存在，请使用其它昵称"},
			email: { required: "请输入有效的邮箱地址", email: "请输入有效的邮箱地址", remote: "邮箱已存在，请选择其它邮箱"},
			password: { required: "请输入密码", rangelength: "密码长度为6-15位"},
			passconf: { required: "请输入密码", rangelength: "密码长度为6-15位",equalTo: "两次输入的密码不一致" },
			terms:  {required: "请阅读用户协议"}
		},
		errorElement: "span",
		submitHandler: function(form) {
			$('#register_form').ajaxSubmit({
					url:  $('#data-actions').attr('data-register-url'),
					data: $('#register_form').formSerialize(),
					type: 'POST',
					dataType: 'json',
					beforeSubmit: function(){
					 	$('#ajax_message').html('注册中，请稍候');
					},
					success: function(data) {
					 if ($.trim(data.result) == "true") {
					     $('#ajax_message').html(data.msg);
					     window.location.href = $('#data-actions').attr('data-regredirect-url');
					 }else {
					  	$('#ajax_message').html(data.msg);
					 }
					},
					error:function(data){
						$('#ajax_message').html(data);
					}
			});
			return false;
		}
	};  //注册表单验证结束
	
	var social_register_validate = {
			rules: {
				nickname: { required: true,byteRangeLength:[4,20],remote: function(){return $('#data-actions').attr('data-ajax-nickname');} }
			},
			messages: {
				nickname: { required: "请输入昵称", byteRangeLength: "昵称长度请设置在4-20个字符之间(1个中文汉字为2个字符)",remote: "昵称已存在，请使用其它昵称"}
			},
			errorElement: "span",
			submitHandler: function(form) {
				$('#social_register_form').ajaxSubmit({
						url:  $('#social_register_form').attr('data-url'),
						data: $('#social_register_form').formSerialize(),
						type: 'POST',
						dataType: 'json',
						beforeSubmit: function(){
						 	$('#ajax_message').html('注册中，请稍候');
						},
						success: function(data) {
						 if ($.trim(data.success) == "true") {
						     $('#ajax_message').html(data.message);
						     window.location.href = $('#social_register_form').attr('data-redirect-url');
						 }else {
						  	$('#ajax_message').html(data.message);
						 }
						},
						error:function(data){
							$('#ajax_message').html("服务器出错了！");
						}
				});
				return false;
			}
		};  //注册表单验证结束
	
})(jQuery);

$(document).ready(function($) {
		jQuery.validator.addMethod("byteRangeLength", function(value, element, param) {      
		  var length = value.length;      
		  for(var i = 0; i < value.length; i++){      
		      if(value.charCodeAt(i) > 127){      
		      length++;      
		      }      
		  } 
		  return this.optional(element) || ( length >= param[0] && length <= param[1] );      
		}, "请确保输入的值在{0}-{1}个字符之间(一个中文字算2个字符)"); 
		
		jQuery.validator.addMethod("notDigit", function(value, element, param) {
				var patrn=/^[0-9]{1,20}$/; 
				if(patrn.test(value))   return false; 
				return true; 
			}, "请确保输入的值不全为数字字符"); 
		
	$.oValidate('save_share_form');
	$.oValidate('login_form');
	$.oValidate('social_register_form');
	$.oValidate('update_password_form');
	$.oValidate('update_userinfo');
	$.oValidate('setting_forum_form');
	$.oValidate('update_goodshop_form');

});