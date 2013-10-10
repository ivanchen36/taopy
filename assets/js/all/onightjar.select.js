$(document).ready(function($) {
	var crop_image;
	var fetched_images;
	var pushDialog;
	var alert_message;
	var loading;
	var data_actions = $('#data-actions');

	var ptx_actions = {
		focusClick: function(e, target_id) {
			$('#' + target_id).focus();
			return false;
		},
		openLoginDialogClick: function() {
			openLoginDialog();
			return false;
		},
		openRegisterDialogClick: function() {
			var register = $.oDialog('loginDialog').width(700).head(getTip('welcome')).body($('#register_box_tpl').html());
			register.elem.addClass("dialog-login");
			register.show();
			$.oValidate('register_form');
			return false;
		},
		
		openAvatarClick: function() {
			var avatar_tpl = $('#avatar_tpl');
			getPushDialog().width(600).head(avatar_tpl.attr('data-title')).body(avatar_tpl.html());
			getPushDialog().show();
			ajaxUpload(data_actions.attr('data-avatarupload-url'), 'avatar_upload_btn', 'avatar_upload_file', 'avatar_img_div', true, 180, 180);
			return false;
		},
		
		closePushDialogClick: function() {
			getPushDialog().hide();
			return false;
		},
		saveAvatarClick: function() {
			var filename = $('#avatar_upload_file').val();
			if (filename == null || filename == undefined || filename == '') {
				showError(getTip('no-selection'));
				return;
			}
			var img_avatar = $('#avatar_img_div .image_croped');
			var selection = crop_image.getSelection();
			var w = selection.x2 - selection.x1;
			var h = selection.y2 - selection.y1;
			var js_h = img_avatar.height();
			var js_w = img_avatar.width();
			saveCropAvatar(data_actions.attr('data-avatarsave-url'), selection.x1, selection.y1, w, h, js_w, js_h, filename,'avatar');
			return false;
		},
		
		deleteShareClick: function(e, sid) {
			if(confirm(getTip('confirm-delete-share'))){
				deleteShare(sid);
			}
			return false;
		},
		delCommentClick: function(e, sid, hash) {
			if(confirm(getTip('confirm-delete-comment'))){
				deleteComment(sid, hash);
			}
			return false;
		},
		addLikeClick: function(e, sid) {
			var purl = data_actions.attr("data-likeshare-url");
			addLike('share',sid, sid + '_image', purl);
			return false;
		},
		socialShareClick: function(e, sid, type) {
			socialShare(sid,type);
			return false;
		},
		addLikeAlbumClick: function(e, aid) {
			var purl = data_actions.attr("data-likealbum-url");
			addLike('album',aid, aid + '_album', purl);
			return false;
		},
		removeLikeAlbumClick: function(e, aid) {
			var purl = data_actions.attr("data-removelikealbum-url");
			removeLike('album',aid, aid + '_album', purl);
			return false;
		},
		addCommentBoxClick: function(e, sid) {
			addCommentBox(sid);
			return false;
		},
		addCommentClick: function(e, sid, tpl, prepend) {
			var comments = $('#' + sid + '_commentbox').val();
			if(comments==null||comments==undefined||$.trim(comments)==''){
				showError(getTip('not-null'));
				return false;
			}
			var type = 'comment';
			if (prepend == undefined || prepend == null) {
				prepend = false;
			} else {
				prepend = true;
			}
			addComment(tpl, sid, comments, type, prepend);
			return false;
		},
		openCropClick: function(e, position, aspectRatio) {
			$('#crop_dialog_tpl').attr('data-position', position);
			openPushCropDialog(aspectRatio);
			return false;
		},
		openPushDialogClick: function(e, sid, cid, path) {
			$('#crop_dialog_tpl').attr('data-sid', sid);
			$('#crop_dialog_tpl').attr('data-cid', cid);
			$('#crop_dialog_tpl').attr('data-type', 'home');
			$('#crop_dialog_tpl').attr('data-crop-url', $('#push_dialog_tpl').attr('data-crop-url'));
			$('#crop_dialog_tpl').attr('data-imgpath', base_url + path + '_large.jpg');
			findPushShare(sid, cid);
			return false;
		},
		cropBtnClick: function(e) {
			var sid = $('#crop_dialog_tpl').attr('data-sid');
			var cid = $('#crop_dialog_tpl').attr('data-cid');
			var position = $('#crop_dialog_tpl').attr('data-position');
			var selection = crop_image.getSelection();
			var w = selection.x2 - selection.x1;
			var h = selection.y2 - selection.y1;
			var js_h = $('#crop_image_' + sid).height();
			var js_w = $('#crop_image_' + sid).width();
			pushCrop(sid, cid, position, selection.x1, selection.y1, w, h, js_w, js_h);
			return false;
		},
		openPublishSelectDialogClick: function() {
			var publish_select_tpl = $('#publish_select_tpl');
			getPushDialog().width(publish_select_tpl.attr('data-width')).head(publish_select_tpl.attr('data-title')).body(publish_select_tpl.html());
			getPushDialog().elem.removeClass("dialog_upload");
			getPushDialog().elem.addClass("dialog_tools");
			getPushDialog().show();
			return false;
		},
		openPublishDialogClick: function(e, type) {
			//getPushDialog().hide();
			var publish_tpl = $('#publish_tpl');
			var publishDialog = getPushDialog().width(750).head(publish_tpl.attr('data-title')).body(publish_tpl.html());
			publishDialog.elem.addClass("dialog_upload");
			publishDialog.show();
			$.oValidate('save_share_form');
			ajaxUpload(publish_tpl.attr('data-upload-url'), 'item_upload_btn', 'cover_filename', 'upload_imgview_div', false, 150, 180);
			getCategories('category_select_div', null, null);
			getSmiles('smiles_div','publish_intro');
			switchFetchType(type);
			resetInputPromote();
			return false;
		},
		listTagsClick:function(e,cat_id,div_id){
			var div_elem = $('#'+div_id);
			getTags(cat_id,div_elem);
		},
		listCategoriesClick:function(e,div_id){
			getCategories(div_id, null, null);
		},
		listSmilesClick:function(e,div_id,textarea){
			getSmiles(div_id,textarea);
		},
		editAlbumSaveClick: function(e, album_id,div_id) {
			var album_div = $('#'+div_id);
			var album_title = album_div.find('.album_title').val();
			var category_id = album_div.find('.category_select_id').val();
			editAlbumSave(album_id,album_title,category_id);
		},
		deleteAlbumClick: function(e, album_id) {
			if(confirm(getTip('confirm-delete-album'))){
				deleteAlbum(album_id);
			}
			return false;
		},
		confirmRedirectClick: function(e, url, tip) {
			if(confirm(getTip('confirm-'+tip))){
				window.location.href = url;
			}
			return false;
		},
		editAlbumOpenClick: function(e, album_id, album_title,category_id,category_title) {
			fetchAjaxTpl('edit_album_tpl',{album_id:album_id,album_title:album_title},function(){getCategories('edit_album_tpl',category_id,category_title);});
		},
		albumItemClick: function(e, album_id, album_title,div_id) {
			albumSelect(album_id, album_title,div_id);
		},
		categoryItemClick: function(e, cat_id, cat_name,div_id,find_album) {
			categorySelect(cat_id, cat_name,div_id,find_album);
		},
		albumItemPopupClick: function(e,div_id) {
			albumItemPopup(div_id);
		},
		categoryItemPopupClick: function(e,div_id) {
			categoryItemPopup(div_id);
		},
		albumListPopupClick: function(e) {
			albumListPopup();
		},
		albumPopItemClick: function(e, album_id, album_title) {
			albumSelected(album_id, album_title);
		},
		categoryListPopupClick: function(e,id) {
			categoryListPopup(id);
		},
		categoryPopItemClick: function(e, cat_id, cat_name) {
			categorySelected(cat_id, cat_name,'category_select_div','album_select_div');
		},
		openCatItemClick: function(e, cat_id, cat_name,div_id) {
			categorySelected(cat_id, cat_name,div_id,null);
		},
		albumCreateClick: function(e) {
			createAlbum();
		},
		albumPopCreateClick: function(e,div_id,uid) {
			createAlbumItem(div_id,uid);
		},
		switchPublishClick: function(e, type) {
			switchFetchType(type);
		},
		switchPushStyleClick: function(e, type) {
			switchPushStyle(type);
		},
		fetchRemoteClick: function(e, type) {
			var remote_url = $('#remote_url').val();
			if (remote_url == null || remote_url == '') {
				$('#ajax_fetch_message').html(getTip('link-not-null'));
				return false;
			}
			$('#ajax_fetch_message').html('');
			fetchRemote(remote_url,type);
			return false;
		},
		preImageClick: function(e, param1) {
			//pre_fetched_image();
			pre_image();
			return false;
		},
		nextImageClick: function(e, param1) {
			//next_fetched_image();
			next_image();
			return false;
		},
		fetchFileItemClick: function(e, src) {
			$(this).parent().parent().find("li").removeClass("active");
			$(this).parent().addClass("active");
			$(this).parent().parent().find("i").addClass("hide");
			$(this).parent().find(".active").removeClass("hide");
			$('#filename').val(src);
			return false;
		},
		editOShareOpenClick: function(e, sid) {
			getShare(sid,'edit_item','item_edit_tpl');
			return false;
		},
		editShareClick: function(e, sid) {
			return false;
		},
		publishPinItemClick: function(e) {
			if($(this).hasClass("selected")){
				$(this).removeClass("selected");
			}else{
				$(this).addClass("selected");
			}
			return false;
		}
	};
	$('#body').actionController({
		controller: ptx_actions,
		events: 'click'
	});

	function getPushDialog(){
		if(pushDialog==null||pushDialog==undefined){
			pushDialog = $.oDialog('pDialog', {
				id: 'ui_push_dialog',
				beforeShow: function() {
					if (crop_image != null && crop_image != undefined)
					 crop_image.remove();
				},
				beforeHide: function() {
					if (crop_image != null && crop_image != undefined)
					 crop_image.remove();
				}
			});
		}
		return pushDialog;
	}
	
	function getAlertMessage(){
		if(alert_message==null||alert_message==undefined){
			alert_message = $.oDialog('alert_message').width(400).head(getTip('error')).body(getTip('server-error'));
		}
		return alert_message;
	}
	function getLoading(){
		if(loading==null||loading==undefined){
			loading = $.oDialog('loading_message').width(400).head(getTip('loading')).body(getTip('loading-detail'));
		}
		return loading;
	}

	function showSuccess(message,redirect){
		hide_loading();
		show_message('success',getTip('success')+': '+getTip(message),true,5000);
		//getAlertMessage().head(getTip('success')).body(getTip(message)).show();
		if(redirect!=null){
			window.location.href = redirect;
		}
	}
	
	function bindParam(funcName){
	    var args=[];
	    for(var i=1;i<arguments.length;i++){
	        args.push(arguments[i]);
	    }
	    return function(){
	        funcName.apply(this,args);
	    };
	}
	
	function showError(message) {
		hide_loading();
		if ($.trim(message) == "not-login") {
			openLoginDialog();
		} else if (message != null) {
			show_message('error',getTip('error')+': '+getTip($.trim(message)),false,0);
		} else {
			show_message('error',getTip('error')+': '+getTip('server-error'),false,0);
		}
	}
	
	function fetchAjaxTpl(tpl,data,func){
		show_loading('success');
		$.ajax({
			type: "post",
			url: data_actions.attr('data-fetchtpl-url'),
			dataType: 'json',
			data: {
				'tpl': tpl
			}
		}).error(function() {
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var lis = $(Mustache.to_html(result.data.tpl, data));
				 var d = getPushDialog().body(lis);
				 var tpl_elem = d.elem.find('#'+tpl);
				 d.head(tpl_elem.attr('data-title'));
				 d.width(tpl_elem.attr('data-width'));
				 d.elem.addClass(tpl_elem.attr('data-css-class'));
				 d.show();
				 func();
			} else {
				showError(result.message);
			}
		});
	}
	function deleteAlbum(aid){
		show_loading('success');
		$.ajax({
			type: "post",
			url: data_actions.attr('data-delalbum-url'),
			dataType: 'json',
			data: {
				'album_id': aid
			}
		}).error(function() {
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				getPushDialog().hide();
				showSuccess(result.message,data_actions.attr('data-myalbum-url'));
			} else {
				showError(result.message);
			}
		});
		
	}
	function editAlbumSave(aid,atitle,cid){
		show_loading('success');
		$.ajax({
			type: "post",
			url: data_actions.attr('data-editalbum-url'),
			dataType: 'json',
			data: {
				'album_id': aid,
				'album_title': atitle,
				'category_id': cid
			}
		}).error(function() {
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				getPushDialog().hide();
				showSuccess(result.message,data_actions.attr('data-myalbum-url'));
			} else {
				showError(result.message);
			}
		});
		
	}
	
	function getShare(sid,type,tpl){
		show_loading('success');
		$.ajax({
			type: "post",
			url: data_actions.attr('data-ajaxgetshare-url'),
			dataType: 'json',
			data: {
				'sid': sid
			}
		}).error(function() {
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var tpl_ins = $('#'+tpl);
				var lis = $(Mustache.to_html(tpl_ins.html(), result));
				 var d = getPushDialog().width(630).head(tpl_ins.attr('data-title')).body(lis);
				 resetInputPromote();
				 if(type=='forwarding'){
					 d.elem.addClass("dialog_upload");
					 $('#forwarding_div').attr('data-sid',sid);
					 $('#forwarding_div').attr('data-uid',0);
					 getCategories('forwarding_div', null, null);
				 }else if(type=='edit_forwarding'){
					 d.head(tpl_ins.attr('data-edit-title'));
					 d.elem.addClass("dialog_upload");
					 $('#forwarding_div').attr('data-sid',sid);
					 $('#forwarding_div').attr('data-edit','1');
					 getCategories('forwarding_div', result.data.share.category_id, result.data.share.category_name_cn);
				 }else if(type=='edit_item'){
					 d.width(750);
					 d.head(tpl_ins.attr('data-edit-title'));
					 d.elem.addClass("dialog_upload");
					 $('#item_detail_div').attr('data-sid',sid);
					 getCategories('item_detail_div', result.data.share.category_id, result.data.share.category_name_cn);
					 getSmiles('edit_smiles_div','edit_publish_intro');
					 $.oValidate('edit_share_form');
				 }
				 d.show();
			} else {
				showError(result.message);
			}
		});
	}
	
	function switchFetchType(type) {
		if (type == 0) {
			$('#website_publish').addClass("selected");
			$('#upload_publish').removeClass("selected");
			$('#upload_input').css("display", 'none');
			$('#website_input').css("display", 'block');
			$('#save_share_form').attr("data-url", $('#publish_tpl').attr('data-fetch-save-url'));
			$('#fetch_remote_btn').attr("data-params",0);
		} else if (type == 1) {
			$('#upload_publish').addClass("selected");
			$('#website_publish').removeClass("selected");
			$('#upload_input').css("display", 'block');
			$('#website_input').css("display", 'none');
			$('#share_type').val('upload');
			$('#save_share_form').attr("data-url", $('#publish_tpl').attr('data-upload-save-url'));
		} else {
			$('#upload_publish').removeClass("selected");
			$('#website_publish').removeClass("selected");
			$('#upload_input').css("display", 'none');
			$('#website_input').css("display", 'block');
			$('#save_share_form').attr("data-url", $('#publish_tpl').attr('data-video-save-url'));
			$('#fetch_remote_btn').attr("data-params",2);
		}
	}
	function switchPushStyle(type){
		$('#crop_dialog_tpl').attr('data-style',type);
		$('#push_'+type).parent().find('.push_style').addClass("hide");
		$('#push_'+type).parent().find('.selected').removeClass("selected");
		$('#push_'+type).removeClass("hide");
		$('#link_'+type).addClass("selected");
	}
	function fetchRemote(remote_url,type) {
		var purl = (type==0)?$('#publish_tpl').attr('data-fetch-url'):$('#publish_tpl').attr('data-video-fetch-url');
		$('#ajax_fetch_message').html(getTip('loading-detail'));
		$.ajax({
			type: "post",
			url: purl,
			dataType: 'json',
			data: {
				'remote_url': remote_url
			}
		}).error(function() {
			showError();
		}).success(function(result) {
			if (result.success === !0) {
				var data_type = result.data.type;
				if (data_type == 'images') {
					if (result.data.images.length == 0 || result.data.images.length == undefined) {
						$('#ajax_fetch_message').html(getTip('no-image-fetch'));
						return;
					}

					var rand = random(4);
					//var lis = $(Mustache.to_html('{{#images}}<li data-action="publishPinItem" data-name="{{src}}"><b><img class="f_image" src="{{src}}"/></b><i></i></li>{{/images}}', result.data));
					var length = result.data.images.length;
					var loaded = 0;
					$('#publish_image_list').html('');
					for(var i=0;i<length;i++){
						var im = result.data.images[i];
						var real_url = '';
						var url = im.src+'?'+rand;
						imageReady(url,real_url,
						function(r){
							if(this.width>min_fetch_width&&this.height>min_fetch_height){
								var lis = $('<li data-action="publishPinItem" data-name="'+this.src+'"><b><img src="'+this.src+'"/></b><i></i><input type="text" name="desc" placeholder="'+getTip('type_some')+'"/></li>');
								$('#publish_image_list').prepend(lis);
							}
						},
						function(r){
							loaded++;
							if(loaded===length){
								next_image();
							}
						},
						function(r){
							loaded++;
						}
						);
					}
					$('#reference_url').val($("#remote_url").val());
					$('#share_type').val('images');
					if(result.data.title){
						$('#title').val(result.data.title).focus();
						$('#publish_intro').val(result.data.title).focus();
					}
					$('#channel').val('others');
				} else if (data_type == 'channel') {
					var lis = $(Mustache.to_html('{{#item_imgs}}<li data-action="publishPinItem" data-name="{{url}}"><b><img src="{{url}}_200x200.jpg"/></b><i></i><input type="text" name="desc" placeholder="'+getTip('type_some')+'"/></li>{{/item_imgs}}', result.data));
					$('#publish_image_list').html(lis);
					next_image();
					$('#share_type').val('channel');
					$('#item_id').val(result.data.item_id);
					$('#channel').val(result.data.channel);
					$('#title').val(result.data.name).focus();
					$('#price').val(result.data.price).focus();
					$('#promotion_url').val(result.data.promotion_url).focus();
					$('#publish_intro').val(result.data.name).focus();
					$('#reference_url').val($("#remote_url").val());
				}
				$('#ajax_fetch_message').html(getTip('fetch-success'));
			} else {
				$('#ajax_fetch_message').html(getTip('fetch-faild'));
				showError(result.message);
			}
		});
	}
	function next_fetched_image() {
		var index = Number($('#upload_imgview_div').attr('data-index'));
		var image_show;
		if (index == 0 || index == null || index == undefined) {
			index = 0;
			$('#upload_imgview_div').attr('data-index', 0);
			image_show = fetched_images[0];
		} else {
			$('#preImageBtn').removeClass('disabled');
			image_show = fetched_images[index];
		}
		if ((index + 1) == fetched_images.length) {
			$('#nextImageBtn').addClass('disabled');
		} else {
			$('#upload_imgview_div').attr('data-index', index + 1);
		}
		$('#upload_imgview_div').html('<img src="' + image_show.src + '" style="max-width:180px;max-height: 180px;"/>');
		$('#filename').val(image_show.src);
	}
	function pre_fetched_image() {
		var index = Number($('#upload_imgview_div').attr('data-index'));
		var image_show;
		if (index == 0 || index == null || index == undefined) {
			index = 0;
			$('#upload_imgview_div').attr('data-index', 0);
			image_show = fetched_images[0];
			$('#preImageBtn').addClass('disabled');
			$('#nextImageBtn').removeClass('disabled');
		} else {
			image_show = fetched_images[index - 1];
		}
		if (index > 1) {
			$('#upload_imgview_div').attr('data-index', index - 1);
		}
		if (index == 1) {
			$('#preImageBtn').addClass('disabled');
		}
		$('#nextImageBtn').removeClass('disabled');
		$('#upload_imgview_div').html('<img src="' + image_show.src + '" style="max-width:180px;max-height: 180px;"/>');
		$('#filename').val(image_show.src);
	}
	function next_image(){
		var image_show;
		var cover = $('#publish_image_list').find('li.cover');
		var lis = $('#publish_image_list').find('li');
	    if ( cover.length == 0 ) {
	    	if(lis.length==0){
		    	$('#upload_imgview_div').html('');
	    	}else{
		    	$('#upload_imgview_div').html('<img src="' + $(lis[0]).find('img').attr('src') + '"/>');
	    	}
	    }
	    var next =  $(cover).next().length ? $(cover).next() : lis[0];
	    $(cover).removeClass('cover');
	    $(next).addClass('cover');
	    image_show = $(next).find('img').attr('src');
	    $('#upload_imgview_div').html('<img src="' + image_show + '"/>');
	    $('#cover_filename').val($(next).attr('data-name'));
	}
	function pre_image(){
		var image_show;
		var cover = $('#publish_image_list').find('li.cover');
		var lis = $('#publish_image_list').find('li');
	    if ( cover.length == 0 ) $('#upload_imgview_div').html('');
	    var pre =  $(cover).prev().length ? $(cover).prev() : lis[lis.length-1];
	    $(cover).removeClass('cover');
	    $(pre).addClass('cover');
	    image_show = $(pre).find('img').attr('src');
	    $('#upload_imgview_div').html('<img src="' + image_show + '"/>');
	    $('#cover_filename').val($(pre).attr('data-name'));
	}
	function albumListPopup() {
		var elem = $('#album_select_div');
		$("body").click(function(e) {
			var eid = $(e.target).attr('id');
			if(eid=='album_name'||eid=='create_board'){
				return;
			}
			return !elem || elem.removeClass("btn_select_hover");
		});
		if (elem.hasClass("btn_select_disabled"))
		 return;
		elem.addClass("btn_select_hover");
	}
	
	function categoryListPopup(id) {
		var elem = $('#'+id);
		$("body").click(function(e) {
			return !elem || elem.removeClass("btn_select_hover");
		});
		if (elem.hasClass("btn_select_disabled"))
		 return;
		elem.addClass("btn_select_hover");
	}
	
	function albumSelected(album_id, album_title) {
		var elem = $('#album_select_div');
		if (elem.hasClass("btn_select_hover")) {
			elem.removeClass("btn_select_hover");
		}
		if(album_id==null){
			elem.find('.album_select_title').html(getTip('pls_select'));
			elem.find('.album_select_id').val('');
		}else{
			elem.find('.album_select_title').html(album_title);
			elem.find('.album_select_id').val(album_id);
		}
	}
	
	function categorySelected(cat_id, cat_name_cn,div_id,album_id) {
		var elem = $('#'+div_id);
		if (elem.hasClass("btn_select_hover")) {
			elem.removeClass("btn_select_hover");
		}
		elem.find('.category_select_title').html(cat_name_cn);
		elem.find('.category_select_id').val(cat_id);
		if(album_id!=null && album_id != undefined)
			getAlbumList(album_id,cat_id,null,null);
	}
	
	function createAlbum() {
		var btn = $('#album_select_create');
		$.ajax({
			type: "post",
			url: btn.attr("data-url"),
			dataType: 'json',
			data: {
				'album_title': btn.prev().val(),
				'category_id': $('#category_select_id').val()
			}
		}).error(function() {
			showError();
		}).success(function(result) {
			if (result.success === !0) {
				var lis = $(Mustache.to_html('{{#data}}<li><a href="javascript:;" data-action="albumPopItem" data-params="{{album_id}},{{album_title}}">{{album_title}}</a></li>{{/data}}', result));
				lis.insertBefore(btn.parent());
				albumSelected(result.data.album_id, result.data.album_title);
				btn.prev().val('');
			} else {
				showError(result.message);
			}
		});
	}
	
	function getTags(cat_id,div_id){
		var div_elem = $(div_id);
		var purl = data_actions.attr("data-taglist-url");
		//var ul = div_elem.find("ul");
		$.ajax({
			type: "get",
			dataType: 'json',
			data: {
				'cid':cat_id,
				'radom': random(5)
			},
			url: purl
		}).error(function() {
			showError();
		}).success(function(result) {
			if (result.success === !0) {
				var target_id = div_elem.attr('data-target-id');
				var lis = $(Mustache.render('{{#data}}<li><a href="javascript:;" class="box" onclick="javascript:$(\'#'+target_id+'\').insertAtCaret(\'#{{.}}#\',0);">#{{.}}#</a></li>{{/data}}', result));
				div_elem.html(lis);
			} else {
				showError(result.message);
			}
		});
	}
	
	function getCategories(div_id, cat_id, cat_name) {
		var div_elem = $('#' + div_id);
		var cat_div_id = div_elem.find('.category_select_list');
		//var album_div_id = div_elem.find('.album_select_list');
		var inited = cat_div_id.attr("data-init");
		var find_album = cat_div_id.attr("data-find-album");
		if (inited != '0') {
			return;
		}
		var ul = cat_div_id.find("ul");
		var purl = data_actions.attr("data-categorylist-url");
		$.ajax({
			type: "get",
			dataType: 'json',
			data: {
				'radom': random(5)
			},
			url: purl
		}).error(function() {
			getPushDialog().hide();
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var lis = $(Mustache.render('{{#data}}<li><a href="javascript:;" data-action="categoryItem" data-params="{{category_id}},{{category_name_cn}},'+div_id+','+find_album+'">{{category_name_cn}}</a></li>{{/data}}', result));
				ul.prepend(lis);
				if (cat_id == null || cat_id == undefined || cat_name == null || cat_name == undefined) {
					categorySelect(result.data[0].category_id, result.data[0].category_name_cn,div_id,find_album);
				} else {
					categorySelect(cat_id, cat_name,div_id,find_album);
				}
				cat_div_id.attr("data-init", 1);
			} else {
				getPushDialog().hide();
				showError(result.message);
			}
		});
	}
	
	function getSmiles(div_id,text_area){
		var div_elem = $('#' + div_id);
		var smiles_div = div_elem.find('.smiles');
		var inited = smiles_div.attr("data-init");
		if (inited != '0') {
			return;
		}
		var purl = data_actions.attr("data-smiles-url");
		$.ajax({
			type: "get",
			dataType: 'json',
			data: {
				'radom': random(5)
			},
			url: purl
		}).error(function() {
			getPushDialog().hide();
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var lis = $(Mustache.render('{{#data}}<li><a href="javascript:;" onclick="javascript:$(\'#'+text_area+'\').insertAtCaret(\'{{code}}\',0);"><img src="'+base_url+'assets/img/smiles/default/{{url}}"></a></li>{{/data}}', result));
				smiles_div.prepend(lis);
				smiles_div.attr("data-init", 1);
			} else {
				getPushDialog().hide();
				showError(result.message);
			}
		});
	}
	

	function categoryItemPopup(div_id) {
		var div_elem = $('#' + div_id);
		var elem = div_elem.find('.category_select_list');
		$("body").click(function(e) {
			return !elem || elem.removeClass("btn_select_hover");
		});
		if (elem.hasClass("btn_select_disabled"))
		 return;
		elem.addClass("btn_select_hover");
	}
	
	function albumItemPopup(div_id) {
		var div_elem = $('#' + div_id);
		var elem = div_elem.find('.album_select_list');
		
		$("body").click(function(e) {
			var eid = $(e.target).attr('data-id');
			if(eid=='album_name'||eid=='create_board'){
				return;
			}
			return !elem || elem.removeClass("btn_select_hover");
		});
		if (elem.hasClass("btn_select_disabled"))
		 return;
		elem.addClass("btn_select_hover");
		
	}
	
	function createAlbumItem(div_id,uid) {
		var div_elem = $('#' + div_id);
		var a_name_elem = div_elem.find('.album_name');
		var cat_input_id = div_elem.find('.category_select_id');
		$.ajax({
			type: "post",
			url: data_actions.attr("data-ajax-albumcreate"),
			dataType: 'json',
			data: {
				'album_title': a_name_elem.val(),
				'category_id': cat_input_id.val(),
				'uid': uid
			}
		}).error(function() {
			showError();
		}).success(function(result) {
			if (result.success === !0) {
				var lis = $(Mustache.to_html('{{#data}}<li><a href="javascript:;" data-aid="{{album_id}}" data-action="albumItem" data-params="{{album_id}},{{album_title}},'+div_id+'">{{album_title}}</a></li>{{/data}}', result));
				lis.insertBefore(a_name_elem.parent());
				albumSelect(result.data.album_id, result.data.album_title,div_id);
				a_name_elem.val('');
			} else {
				showError(result.message);
			}
		});
	}
	
	function categorySelect(cat_id, cat_name_cn,div_id,find_album) {
		var div_elem = $('#' + div_id);
		var cat_div_id = div_elem.find('.category_select_list');
		//var album_div_id = div_elem.find('.album_select_list');
		
		if (cat_div_id.hasClass("btn_select_hover")) {
			cat_div_id.removeClass("btn_select_hover");
		}
		
		cat_div_id.find('.category_select_title').html(cat_name_cn);
		cat_div_id.find('.category_select_id').val(cat_id);
		if(find_album!=0&&find_album!=null&&find_album!=''&&find_album!=undefined){
			var album_div_id = div_elem.find('.album_select_list');
			//alert('1');
			if(album_div_id){
				var album_id = album_div_id.attr('data-album-id');
				var album_name = album_div_id.attr('data-album-name');
				//alert('sec'+album_name);
				getAlbums(div_id,cat_id,album_id,album_name);
			}else{
				getAlbums(div_id,cat_id,null,null);
			}
		}
		var tag_list = div_elem.find('.tags_list');
		if(tag_list){
			getTags(cat_id,tag_list);
		}
	}
	
	function albumSelect(aid, a_title,div_id) {
		var div_elem = $('#' + div_id);
		var elem = div_elem.find('.album_select_list');
		if (elem.hasClass("btn_select_hover")) {
			elem.removeClass("btn_select_hover");
		}
		var find = false;
		if(aid!=null){
			elem.find('li').each(function() {
	            var data_id = $(this).find('a').attr('data-aid');
	            if(aid==data_id){
	            	find=true;
	    			return;
	            }
	        });
		}
		if(find){
			elem.find('.album_select_title').html(a_title);
			elem.find('.album_select_id').val(aid);
		}else{
			elem.find('.album_select_title').html(getTip('pls_select'));
			elem.find('.album_select_id').val('');
		}
		
	}
	
	function getAlbums(div_id,cid, aid, a_title) {
		var div_elem = $('#' + div_id);
		var album_div = div_elem.find('.album_select_list');
		var ul = album_div.find("ul");
		var purl = data_actions.attr("data-albumlist-url");
		var uid = div_elem.attr('data-uid');
		$.ajax({
			type: "get",
			dataType: 'json',
			data: {'cid':cid,'uid':uid,'radom': random(5)},
			url: purl
		}).error(function() {
			getPushDialog().hide();
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var lis = $(Mustache.render('{{#data}}<li><a href="javascript:;" data-aid="{{album_id}}" data-action="albumItem" data-params="{{album_id}},{{album_title}},'+div_id+'">{{album_title}}</a></li>{{/data}}', result));
				ul.html(ul.children(":last"));
				lis.insertBefore(ul.children(":last"));
				if (aid == null || aid == undefined || a_title == null || a_title == undefined) {
					albumSelect(result.data[0].album_id, result.data[0].album_title,div_id);
				} else {
					albumSelect(aid, a_title,div_id);
				}
				album_div.attr("data-init", 1);
			} else {
				var last = ul.children(":last");
				ul.html(last);
				albumSelect(null, null,div_id);
			}
		});
	}
	
	function getCategoryList(div_id, cat_id, cat_name) {
		var div_elem = $('#' + div_id);
		var inited = div_elem.attr("data-init");
		if (inited != '0') {
			return;
		}
		var ul = div_elem.find("ul");
		var purl = data_actions.attr("data-categorylist-url");
		$.ajax({
			type: "get",
			dataType: 'json',
			data: {'radom': random(5)},
			url: purl
		}).error(function() {
			getPushDialog().hide();
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var lis = $(Mustache.render('{{#data}}<li><a href="javascript:;" data-action="categoryPopItem" data-params="{{category_id}},{{category_name_cn}}">{{category_name_cn}}</a></li>{{/data}}', result));
				ul.prepend(lis);
				if (cat_id == null || cat_id == undefined || cat_name == null || cat_name == undefined) {
					categorySelected(result.data[0].category_id, result.data[0].category_name_cn,'category_select_div','album_select_div');
				} else {
					categorySelected(cat_id, cat_name,'category_select_div','album_select_div');
				}
				div_elem.attr("data-init", 1);
			} else {
				getPushDialog().hide();
				showError(result.message);
			}
		});
	}
	
	function getAlbumList(div_id,cat_id, album_id, album_title) {
		var div_elem = $('#' + div_id);
		var ul = div_elem.find("ul");
		var purl = data_actions.attr("data-albumlist-url");
		
		$.ajax({
			type: "get",
			dataType: 'json',
			data: {'cid':cat_id,'radom': random(5)},
			url: purl
		}).error(function() {
			getPushDialog().hide();
			hide_loading();
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var lis = $(Mustache.render('{{#data}}<li><a href="javascript:;" data-action="albumPopItem" data-params="{{album_id}},{{album_title}}">{{album_title}}</a></li>{{/data}}', result));
				ul.html(ul.children(":last"));
				lis.insertBefore(ul.children(":last"));
				if (album_id == null || album_id == undefined || album_title == null || album_title == undefined) {
					albumSelected(result.data[0].album_id, result.data[0].album_title);
				} else {
					albumSelected(album_id, album_title);
				}
				div_elem.attr("data-init", 1);
			} else {
				var last = ul.children(":last");
				ul.html(last);
				albumSelected(null, null);
			}
		});
	}
	function deleteComment(sid, hash) {
		$.ajax({
			type: "post",
			url: data_actions.attr("data-delcomment-url"),
			dataType: 'json',
			data: "sid=" + sid + "&hash=" + hash
		}).error(function() {
			showError();
		}).success(function(result) {
			if (result.success === !0) {
				$('#comment_' + hash).fadeTo(1000, "hide", 0);
			} else {
				showError(result.message);
			}
		});
	}
	function addCommentBox(id) {
		$('#waterfall').find('.commentdiv').addClass('hide');
		$('#' + id + '_commentdiv').removeClass('hide');
		$('#waterfall').masonry('reload');
		$('#' + id + '_commentbox').focus();
	}
	function openPushCropDialog(aspectRatio) {
		var ass = aspectRatio.split(':');
		var dialog = $('#crop_dialog_tpl');
		dialog.attr('data-width',ass[0]);
		dialog.attr('data-height',ass[1]);
		var sid = dialog.attr('data-sid');
		var path = dialog.attr('data-imgpath');
		var data = {
			path: path,
			sid: sid
		};
		var lis = $(Mustache.to_html(dialog.html(), data));
		getPushDialog().width(600).head(dialog.attr('data-title')).body(lis).show();
		$('#ui_push_dialog').imagesLoaded(function() {
			crop_image = $('#crop_image_' + sid).imgAreaSelect({
				zIndex: 2000,
				instance: true,
				aspectRatio: aspectRatio,
				show: true,
				x1: 5,
				y1: 5,
				x2: 100,
				y2: 100,
				handles: true
			});
		});
	}
	function openLoginDialog() {
		var login_tpl = $('#login_box_tpl');
		var login = $.oDialog('loginDialog', {id: 'ui_login_dialog'});
		login.width(650).head(login_tpl.attr('data-title')).body(login_tpl.html());
		login.elem.addClass("dialog-login");
		login.show();
		$.oValidate('login_form');
		$.oValidate('bbs_login_form');
	}
	function findPushShare(sid, cid) {
		show_loading('success');
		var dialog = $('#push_dialog_tpl');
		$.ajax({
			type: "post",
			url: dialog.attr("data-fetch-url"),
			dataType: 'json',
			data: "cid=" + cid
		}).error(function() {
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				var lis = $(Mustache.to_html(dialog.html(), result));
				getPushDialog().width(1000).head(dialog.attr("data-title")).body(lis).show();
				//alert(result.data.style);
				switchPushStyle(result.data.style);
			} else {
				getAlertMessage().body(result.message).show();
			}
		});
	}
	function pushCrop(sid, cid, position, x, y, w, h, js_w, js_h) {
		show_loading('success');
		var crop_dialog = $('#crop_dialog_tpl');
		var ww = crop_dialog.attr('data-width');
		var hh = crop_dialog.attr('data-height');
		var type = crop_dialog.attr('data-type');
		var style = crop_dialog.attr('data-style');
		var uid = crop_dialog.attr('data-uid');
		$.ajax({
			type: "post",
			url: crop_dialog.attr("data-crop-url"),
			dataType: 'json',
			data: {'sid':sid,'cid':cid,'uid':uid,'position':position,'x':x,'y':y,'w':w,'h':h,'js_w':js_w,'js_h':js_h,'ww':ww,'hh':hh,'sy':style}
		}).error(function() {
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				if(type=='home'){
					findPushShare(sid, cid);
				}else if(type=='star'){
					starOpen(uid,cid,sid);
					//alert(result.data.img);
					//$('#push_'+style).find('.pos'+position).html('<img src="'+result.data.img+'" />');
				}
				
			} else {
				showError(result.message);
			}
		});
	}
	function deleteShare(sid) {
		$.ajax({
			type: "post",
			url: data_actions.attr("data-delshare-url"),
			dataType: 'json',
			data: "sid=" + sid
		}).error(function() {
			showError();
		}).success(function(result) {
			if (result.success === !0) {
				$('#' + sid).fadeTo(1000, "hide", 0);
				setTimeout(function() {
					$('#waterfall').masonry('remove', $('#' + sid));
					$('#waterfall').masonry('reload');
					$('#timeline-box').masonry('remove', $('#' + sid));
					$('#timeline-box').masonry('reload',function(){resetArrow();});
				},
				1000);
			} else {
				showError(result.message);
			}
		});
	}
	
	function addComment(tpl, sid, comments, type, prepend) {
		var tpl_e = $('#' + tpl);
		var url = data_actions.attr('data-addcomment-url');
		var reload = tpl_e.attr('reload');
		$.ajax({
			url: url,
			data: {
				'sid': sid,
				'comment': comments,
				'type': type
			},
			type: 'POST',
			dataType: 'json',
			error: function() {
				showError();
			},
			success: function(result) {
				if (result.success === !0) {
					var new_comment = $(Mustache.to_html(tpl_e.html(), result));
					if (prepend) {
						$('#' + sid + '_comments').prepend(new_comment);
					} else {
						$('#' + sid + '_comments').append(new_comment);
					}
					new_comment.fadeIn(2000);
					$('#' + sid + '_commentbox').val('');
					if (reload) {
						$('#' + sid + '_commentdiv').addClass('hide');
						$('#waterfall').masonry('reload');
					}
					check_message('reward',true,3000,700);
				} else {
					showError(result.message);
				}
			}
		});
	}
	
	function getParamsOfShareWindow(a, b) {
		return ['toolbar=0,status=0,resizable=1,width=' + a + ',height=' + b + ',left=', (screen.width - a) / 2, ',top=', (screen.height - b) / 2].join('');
	}
	var share_sub_str = function(a, b, c) {
		if (a.length <= b) return a;
		var d = /https?:\/\/[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)+([-_A-Z0-9a-z\$\.\+\!\*\/,:;@&=\?\~\#\%]*)*/gi;
		var e;
		while ((e = d.exec(a)) != null) {
			if (e.index < b && (e.index + e[0].length) > b) {
				return a.substr(0, e.index + e[0].length) + c;
			}
		}
		return a.substr(0, b) + c;
	};
	
	function socialShare(sid,type){
		var site_name = encodeURIComponent('拼图秀');
		var window_name = 'social_share';
		var img = $('#'+sid+'_image').find('img');
		var img_url = "";
		if(img.length>0){
			img_url = $(img[0]).attr('orgin_src');
		}
		var link = $('#'+sid+'_image').attr('href');
		var video = $('#'+sid+'_image').find('.video_icon');
		if(video.length>0){
			link = $(video).attr('orgin_url');
			img_url = $(video).attr('orgin_src');
		}
		var desc_text = $('#'+sid+' .share_desc').text();
		var text = encodeURIComponent(share_sub_str(desc_text, 100, '...'));
		link = encodeURIComponent(link);
		img_url = encodeURIComponent(img_url);
		if(type=='sina'){
			var social_point = 'http://service.weibo.com/share/share.php?appkey=' + sina_key + '&pic=' + img_url + '&title=' + text + '&url=' + link + '&relateuid=2793533962';
			var social_window = getParamsOfShareWindow(615, 505);
			window.open(social_point, window_name, social_window);
		}else if(type=='renren'){
			var social_point = 'http://share.renren.com/share/buttonshare?link=' + link + '&title=' + text;
			var social_window = getParamsOfShareWindow(626, 436);
			window.open(social_point, window_name, social_window);
		}else if(type=='qq'){
			var social_point = 'http://v.t.qq.com/share/share.php?appkey=' + qq_key + '&pic=' + img_url + '&title=' + text + '&source=' + site_name + '&url=' + link;
			var social_window = getParamsOfShareWindow(642, 468);
			window.open(social_point, window_name, social_window);
		}else if(type=='qzone'){
			var summary=encodeURIComponent(desc_text);
			var title_social = encodeURIComponent(share_sub_str(desc_text, 30, '...'));
			var social_point = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?pics='+img_url+'&url='+link+'&title='+title_social+'&summary='+summary+'&site='+site_name;
			var social_window = getParamsOfShareWindow(634, 668);
			window.open(social_point, window_name, social_window);
		}else if(type=='twitter'){
			var social_point = 'https://twitter.com/intent/tweet?original_referer=' + link + '&source=tweetbutton&text=' + text +link+'&url=' + link;
			var social_window = getParamsOfShareWindow(634, 505);
			window.open(social_point, window_name, social_window);
		}
	}
	
	function addLike(type, sid, appendId, purl) {
		var postdata;
		if(type=='share'){
			postdata = {'sid': sid};
		}else if(type=='album'){
			postdata = {'aid': sid};
		}
		$.ajax({
			url: purl,
			data: postdata,
			type: 'post',
			dataType: 'json',
			success: function(result) {
				if (result.success === !0) {
					$('#' + appendId).append('<div id="'
					 + sid
					 + '_like_added" class="like_add">'+getTip('add-like')+'</div>');
					$('#' + sid + '_like_added').fadeTo(3000, "hide", 0);
					setTimeout(function() {
						$('#' + sid + '_like_added').remove();
					},
					3000);
					var likenum = $('#' + sid + '_likenum').find('em').text();
					$('#' + sid + '_likenum').find('em').text(Number(likenum) + 1);
					check_message('reward',true,3000,700);
				} else if ($.trim(result.message) == "not_login") {
					openLoginDialog();
				} else if ($.trim(result.message) == "like_already") {
					$('#' + appendId).append('<div id="'
					 + sid
					 + '_like_already" class="like_already">'+getTip('like-already')+'</div>');
					$('#' + sid + '_like_already').fadeTo(3000, "hide", 0);
					setTimeout(function() {
						$('#' + sid + '_like_already').remove();
					},
					3000);
				} else if ($.trim(result.message) == "like_self") {
					$('#' + appendId).append('<div id="'
					 + sid
					 + '_like_self" class="like_self">'+getTip('like-self')+'</div>');
					$('#' + sid + '_like_self').fadeTo(3000, "hide", 0);
					setTimeout(function() {
						$('#' + sid + '_like_self').remove();
					},
					3000);
				} else {
					showError(result.message);
				}
			},
			error: function(result) {
				showError();
			}
		});
	}
	
	function removeLike(type, sid, appendId, purl) {
		var postdata;
		if(type=='share'){
			postdata = {'sid': sid};
		}else if(type=='album'){
			postdata = {'aid': sid};
		}
		$.ajax({
			url: purl,
			data: postdata,
			type: 'POST',
			dataType: 'json',
			success: function(data) {
				if (result.success === !0) {
					$('#' + appendId).append('<div id="'+ sid + '_like_added" class="like_add">'+getTip('remove-like')+'</div>');
					$('#' + sid + '_like_added').fadeTo(3000, "hide", 0);
					setTimeout(function() {
						$('#' + sid + '_like_added').remove();
					},
					3000);
					var likenum = $('#' + sid + '_likenum').find('em').text();
					$('#' + sid + '_likenum').find('em').text(Number(likenum) + 1);
				} else if ($.trim(result.message) == "not_login") {
					openLoginDialog();
				} else if ($.trim(result.message) == "not_liked") {
					$('#' + appendId).append('<div id="'+ sid + '_like_already" class="like_already">'+getTip('not_liked')+'</div>');
					$('#' + sid + '_like_already').fadeTo(3000, "hide", 0);
					setTimeout(function() {
						$('#' + sid + '_like_already').remove();
					},
					3000);
				} else if ($.trim(result.message) == "like_self") {
					$('#' + appendId).append('<div id="' + sid + '_like_self" class="like_self">'+getTip('like-self')+'</div>');
					$('#' + sid + '_like_self').fadeTo(3000, "hide", 0);
					setTimeout(function() {
						$('#' + sid + '_like_self').remove();
					},
					3000);
				} else {
					showError(result.message);
				}
			},
			error: function(result) {
				showError();
			}
		});
	}
	
	function ajaxUpload(url, btn_id, input_id, imageview_id, addCrop, mw, mh) {
		var button = $('#' + btn_id),
		interval;
		new AjaxUpload(button, {
			action: url,
			name: 'qqfile',
			responseType: 'json',
			onSubmit: function(file, ext) {
				button.text(getTip('select_file'));
				this.disable();
				interval = window.setInterval(function() {
					var text = button.text();
					if (text.length < 13) {
						button.text(text
						 + '.');
					} else {
						button.text(getTip('uploading'));
					}
				},
				200);
			},
			onComplete: function(file, response) {
				button.text(getTip('select_file'));
				window.clearInterval(interval);
				this.enable();
				if (response.success === !0) {
					var filename = response.data.filename
					 + "." + response.data.ext;
					var filepath = base_url
					 + 'data/attachments/tmp/'
					 + filename;
					if (addCrop) {
						$('#' + imageview_id).html('<img class="image_croped" src="'
						 + filepath
						 + '" style="max-width:' + mw + 'px;max-height: ' + mh + 'px;"/>');
						$('#' + input_id).val(filename);
						$('#' + imageview_id).imagesLoaded(function() {
							if (crop_image != null && crop_image != undefined) {
								crop_image.remove();
							}
							crop_image = $("#"+imageview_id+" .image_croped").imgAreaSelect({
								zIndex: 2000,
								instance: true,
								show: true,
								aspectRatio: mw+':'+mh,
								x1: 0,
								y1: 0,
								x2: 100,
								y2: 100,
								handles: true
							});
						});
					}else{
						$('#' + input_id).val(filename);
						$('#' + imageview_id).html('<img src="' + filepath + '" style="max-width:' + mw + 'px;max-height: ' + mh + 'px;"/>');
						var lis = $('<li data-action="publishPinItem" data-name="'+filename+'" class="selected cover"><b><img src="' + filepath + '"/></b><i></i><input type="text" name="desc" placeholder="'+getTip('type_some')+'"/></li>');
						$('#publish_image_list').find('li').removeClass("cover");
						$('#publish_image_list').append(lis);
					}
				} else {
					showError(response.message);
				}
			}
		});
	}
	function saveCropAvatar(purl, x, y, w, h, js_w, js_h, filename,type) {
		show_loading('success');
		$.ajax({
			type: "post",
			url: purl,
			dataType: 'json',
			data: "x=" + x + "&y=" + y + "&w="
			 + w + "&h=" + h + "&js_w="
			 + js_w + "&js_h=" + js_h
			 + "&filename=" + filename
			 + "&type=" + type
		}).error(function() {
			showError();
		}).success(function(result) {
			hide_loading();
			if (result.success === !0) {
				if (crop_image != null && crop_image != undefined)
				 crop_image.remove();
				if(type=='avatar'){
					$('#avatar_upload_file').val('');
					$('#avatar_img_div .image_croped').addClass('hide');
					$('#avatar_large_div').removeClass('hide').html('<img src="'
					 + base_url
					 + result.data.avatar_local
					 + '_large.jpg?'
					 + result.data.hash
					 + '" width="150" height="150"/>');
					$('#avatar_middle_div').removeClass('hide').html('<img src="'
					 + base_url
					 + result.data.avatar_local
					 + '_middle.jpg?'
					 + result.data.hash
					 + '" width="50" height="50"/>');
					$('#avatar_small_div').removeClass('hide').html('<img src="'
					 + base_url
					 + result.data.avatar_local
					 + '_small.jpg?'
					 + result.data.hash
					 + '" width="16" height="16"/>');
					$('#upload_avatar_div .actions').html('<button type="submit" data-action="closePushDialog" class="btn btn_red"><span>'+getTip('done')+'</span></button>');
					check_message('reward',true,3000,1500);
				}else if(type=='banner'){
					$('#banner_upload_file').val('');
					$('#banner_img_div').removeClass('hide').html('<img src="'
					 + base_url
					 + result.data.avatar_local
					 + '_banner.jpg?'
					 + result.data.hash
					 + '" width="500" height="158"/>');
					$('#upload_banner_div .actions').html('<button type="submit" data-action="closePushDialog" class="btn btn_red"><span>'+getTip('done')+'</span></button>');
				}
			} else {
				showError(result.message);
			}
		});
	}
	
	function resetInputPromote(){
		$('input[type=text][title],input[type=password][title],textarea[title]').each(function(i){
		    $(this).addClass('input-prompt-' + i);
		    var promptSpan = $('<span class="input-prompt"/>');
		    $(promptSpan).attr('id', 'input-prompt-' + i);
		    $(promptSpan).append($(this).attr('title'));
		    $(promptSpan).click(function(){
		      $(this).hide();
		      $('.' + $(this).attr('id')).focus();
		    });
		    if($(this).val() != ''){
		      $(promptSpan).hide();
		    }
		    $(this).before(promptSpan);
		    $(this).focus(function(){
		      $('#input-prompt-' + i).hide();
		    });
		    $(this).change(function(){
		    	if($(this).val() == ''){
		    		$('#input-prompt-' + i).hide();
		    	}
			});
		    $(this).blur(function(){
		      if($(this).val() == ''){
		        $('#input-prompt-' + i).show();
		      }
		    });
		  });
	}
});
