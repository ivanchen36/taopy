$(document).ready(function($) {
	(function($) {
	$.oValidate = function(id) {
		var list = {
				'save_share_form':save_share_validate,
				'register_form':register_validate,
				'social_register_form':social_register_validate,
				'update_userinfo':update_userinfo_validate,
				'update_password_form':update_password_form,
				'edit_share_form':edit_share_validate,
				'login_form':login_validate
		};
		if(id&&list[id]&&$('#'+id)) {
			$('#'+id).validate(list[id]);
		}
	};

	var save_share_validate = {
			rules : {	intro : {required : true,byteRangeLength:[4,4000]}, 
				title : {required : true,byteRangeLength:[4,80]}
			},
			messages : {	intro : {required : getTip('required-field'),byteRangeLength: getTip("intro_length_not_valid")},
					title : {required : getTip('required-field'),byteRangeLength: getTip("title_length_not_valid")}
			},
		submitHandler : function(form) {
			var has_cover = false;
			var images_arr = new Array();
			$('#publish_image_list li.selected').each(function() {
				var desc_input = $(this).find('input');
				var desc_str = (desc_input.length==0)?'':$(desc_input).val();
				if(str_length(desc_str)>140){
					$('#ajax_share_message').html(getTip('img_desc_not_valid'));
					return false;
				}
				if(!$(this).hasClass('cover')){
					var img = {url:$(this).attr('data-name'),desc:desc_str,cover:false};
					images_arr.push(img);
				}else{
					var img = {url:$(this).attr('data-name'),desc:desc_str,cover:true};
					images_arr.push(img);
					has_cover = true;
				}
	        });

			if(!has_cover){
				$('#ajax_share_message').html(getTip('no_img_cover_selected'));
				return false;
			}
			$('#save_share_form #all_files').val(serialize(images_arr));
			$('#save_share_form').ajaxSubmit({
				url : $('#save_share_form').attr("data-url"),
				data : $('#save_share_form').formSerialize(),
				type : 'POST',
				dataType : 'json',
				beforeSubmit : function() {
					var filename = $('#save_share_form #cover_filename').val();
					if (filename == null || filename == '') {
						$('#ajax_share_message').html(getTip('no_img_share'));
						return false;
					}
					var album_select_id = $('#save_share_form .album_select_id').val();
					if (album_select_id == null || album_select_id == '' || album_select_id == 0) {
						$('#ajax_share_message').html(getTip('album_cannot_be_null'));
						return false;
					}
					$('#ajax_share_message').html(getTip('loading-detail'));
				},
				success : function(data) {
					if ($.trim(data.success) == "true") {
						show_message('success',getTip('success')+': '+data.message,false,0);
						setTimeout(function(){
							window.location.href = $('#save_share_form').attr("next-url");
						},2000);
					} else {
						show_message('error',getTip('error')+': '+data.message,false,0);
					}
				},
				error : function() {
					show_message('error',getTip('error')+': '+getTip('server-error'),false,0);
				}
			});
			return false;
		}
	};
	
	var edit_share_validate = {
			rules : {	intro : {required : true,byteRangeLength:[4,4000]}, 
						title : {required : true,byteRangeLength:[4,80]}
					},
			messages : {	intro : {required : getTip('required-field'),byteRangeLength: getTip("intro_length_not_valid")},
							title : {required : getTip('required-field'),byteRangeLength: getTip("title_length_not_valid")}
					},
			submitHandler : function(form) {
				var images_arr = new Array();
				var has_cover = false;
				$('#publish_image_list li.selected').each(function() {
					var desc_str = $(this).find('input').val();
					if(str_length(desc_str)>140){
						$('#ajax_share_message').html(getTip('img_desc_not_valid'));
						return false;
					}
					if(!$(this).hasClass('cover')){
						var img = {id:$(this).attr('data-id'),url:$(this).attr('data-url'),desc:desc_str,cover:false};
						images_arr.push(img);
					}else{
						var img = {id:$(this).attr('data-id'),url:$(this).attr('data-url'),desc:desc_str,cover:true};
						images_arr.push(img);
						has_cover = true;
					}
		        });
				if(!has_cover){
					$('#ajax_share_message').html(getTip('no_img_cover_selected'));
					return false;
				}
				$('#edit_share_form #all_files').val(serialize(images_arr));
				
				$('#edit_share_form').ajaxSubmit({
					url : $('#data-actions').attr("data-editshare-url"),
					data : $('#edit_share_form').formSerialize(),
					type : 'POST',
					dataType : 'json',
					beforeSubmit : function() {
						var album_select_id = $('#edit_share_form .album_select_id').val();
						if (album_select_id == null || album_select_id == '' || album_select_id == 0) {
							$('#ajax_share_message').html(getTip('album_cannot_be_null'));
							return false;
						}
						$('#ajax_share_message').html(getTip('loading-detail'));
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
						show_message('error',getTip('error')+': '+getTip('server-error'),false,0);
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
			nickname: { required: getTip('required-field'), byteRangeLength: getTip("nick_not_valid"),remote: getTip("nick_already_existed")}
		},
		errorElement: "span",
		submitHandler: function(form) {
			$('#update_userinfo').ajaxSubmit({
					url: $('#data-actions').attr('data-updateuser-url'),
					data: $('#update_userinfo').formSerialize(),
					type: 'POST',
					dataType: 'json',
					beforeSubmit: function(){
					 	$('#update_userinfo #ajax_message').html(getTip('loading-detail'));
					},
					success: function(data) {
					 if (data.success === !0) {
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
	
	var update_password_form = {
			rules: {
				email: { required: true, email: true, remote: function(){ return $('#data-actions').attr('data-ajax-email');}},
				org_passwd: { required: true, rangelength: [6, 15] },
				new_passwd: { required: true, rangelength: [6, 15] },
				new_verify_passwd: { required: true, rangelength: [6, 15], equalTo: "#new_passwd" }
			},
			messages: {
				email: { required: getTip('required-field'), email: getTip('not_valid'), remote: getTip("email_already_existed")},
				org_passwd: { required: getTip('required-field'), rangelength: getTip("password_not_valid")},
				new_passwd: { required: getTip('required-field'), rangelength: getTip("password_not_valid") },
				new_verify_passwd: { required: getTip('required-field'), rangelength: getTip("password_not_valid"),equalTo: getTip('password_not_match') }
			},
			errorElement: "span",
			submitHandler: function(form) {
				$('#update_password_form').ajaxSubmit({
						url: $('#data-actions').attr('data-ajax-resetpasswd'),
						data: $('#update_password_form').formSerialize(),
						type: 'POST',
						dataType: 'json',
						beforeSubmit: function(){
						 	$('#update_password_form #ajax_message').html(getTip('loading-detail'));
						},
						success: function(data) {
						 if (data.success === !0) {
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
				required : getTip('required-field'),
				email : getTip('not_valid')
			},
			password : {
				required : getTip('required-field'),
				minlength : getTip('not_valid')
			}
		},
		submitHandler : function(form) {
			$('#login_form').ajaxSubmit({
				url : $('#data-actions').attr('data-login-url'),
				data : $('#login_form').formSerialize(),
				type : 'POST',
				dataType : 'json',
				beforeSubmit : function() {
					$('#ajax_message').html(getTip('loading-detail'));
				},
				success : function(data) {
					if (data.success === !0) {
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
			nickname: { required: getTip('required-field'), byteRangeLength: getTip("nick_not_valid"),remote: getTip("nick_already_existed")},
			email: { required: getTip('required-field'), email: getTip('not_valid'), remote: getTip("email_already_existed")},
			password: { required: getTip('required-field'), rangelength: getTip("password_not_valid")},
			passconf: { required: getTip('required-field'), rangelength: getTip("password_not_valid"),equalTo: getTip("password_not_match") },
			terms:  {required: getTip('required-field')}
		},
		errorElement: "span",
		submitHandler: function(form) {
			$('#register_form').ajaxSubmit({
					url:  $('#data-actions').attr('data-register-url'),
					data: $('#register_form').formSerialize(),
					type: 'POST',
					dataType: 'json',
					beforeSubmit: function(){
					 	$('#ajax_message').html(getTip('loading-detail'));
					},
					success: function(data) {
					 if (data.success === !0) {
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
				nickname: { required: getTip('required-field'), byteRangeLength: getTip("nick_not_valid"),remote: getTip("nick_already_existed")}
			},
			errorElement: "span",
			submitHandler: function(form) {
				$('#social_register_form').ajaxSubmit({
						url:  $('#social_register_form').attr('data-url'),
						data: $('#social_register_form').formSerialize(),
						type: 'POST',
						dataType: 'json',
						beforeSubmit: function(){
						 	$('#ajax_message').html(getTip('loading-detail'));
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
							show_message('error',getTip('error')+': '+getTip('server-error'),false,0);
						}
				});
				return false;
			}
		};  //注册表单验证结束
	
})(jQuery);
		jQuery.validator.addMethod("byteRangeLength", function(value, element, param) {      
		  var length = value.length;      
		  for(var i = 0; i < value.length; i++){      
		      if(value.charCodeAt(i) > 127){      
		      length++;      
		      }      
		  } 
		  return this.optional(element) || ( length >= param[0] && length <= param[1] );      
		}, getTip("length_rang")); 
		
		jQuery.validator.addMethod("notDigit", function(value, element, param) {
				var patrn=/^[0-9]{1,20}$/; 
				if(patrn.test(value))   return false; 
				return true; 
			}, getTip('not_all_digit')); 
		
	$.oValidate('save_share_form');
	$.oValidate('login_form');
	$.oValidate('social_register_form');
	$.oValidate('update_password_form');
	$.oValidate('update_userinfo');

});