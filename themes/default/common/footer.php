<div class="clear">&nbsp;</div>
<div class="container footer">
	<div class="main">
		<div class="g960 text_c mt30">
			<p style="font-size: 90%;">
			©2012-2013 <a href="<?php echo $settings['basic_setting']['site_domain'];?>"><?php echo $settings['basic_setting']['site_name'];?></a>  <a href="http://www.miitbeian.gov.cn/" target="_blank"><?php echo $settings['basic_setting']['site_beian'];?></a>
			</p>
		</div>
	</div>
</div>
<div style="float:right;font-size: 11px;color:#bebebe;width: 100%;clear: both;position: relative; text-align: center;margin-top: -20px;"><?php echo $settings['basic_setting']['site_tongji'];?>
</div>
<a id="BackToTop" href="#"><?php echo T('back_to_top');?></a>
<?php echo $tpl_js; ?>

<script type="text/template" 
	id="data-actions"  
	data-login-url="<?php echo spUrl('webuser','login'); ?>" 
	data-loginredirect-url="<?php echo spUrl('my','shares');?>"
	data-mybasicsettings-url="<?php echo spUrl('my','setting_basic');?>"
	data-editshare-url="<?php echo spUrl('share','edit_share');?>"
	data-regredirect-url="<?php echo spUrl('pin','index');?>"
	data-register-url="<?php echo spUrl('webuser','ajax_register'); ?>" 
	data-ajaxgetshare-url="<?php echo spUrl('share','ajax_get_share'); ?>" 
	data-likeshare-url="<?php echo spUrl('share','add_like'); ?>" 
	data-likealbum-url="<?php echo spUrl('album','add_like'); ?>" 
	data-removelikealbum-url="<?php echo spUrl('album','remove_like'); ?>" 
	data-addcomment-url="<?php echo spUrl('share','add_comment'); ?>" 
	data-delcomment-url="<?php echo spUrl('share','del_comment'); ?>"
	data-delshare-url="<?php echo spUrl('share','delete_share'); ?>"
	data-avatarupload-url="<?php echo spUrl('webuser','upload_avatar');?>" 
	data-avatarsave-url="<?php echo spUrl('webuser','save_avatar');?>"
	data-ajax-nickname="<?php echo spUrl('webuser','ajax_nick_check');?>"
	data-ajax-updatenickname="<?php echo spUrl('webuser','nickname_update_check');?>"
	data-ajax-albumcreate="<?php echo spUrl('album','album_create');?>"
	data-ajax-resetpasswd="<?php echo spUrl('webuser','reset_passwd');?>"
	data-ajax-message="<?php echo spUrl('ajaxmessage','fetch');?>"
	data-updateuser-url="<?php echo spUrl('webuser','update_userinfo');?>"
	data-ajax-email="<?php echo spUrl('webuser','ajax_email_check');?>"
	data-categorylist-url="<?php echo spUrl('album','category_list');?>"
	data-smiles-url="<?php echo spUrl('misc','smiles');?>"
	data-albumlist-url="<?php echo spUrl('album','album_list');?>"
	data-taglist-url="<?php echo spUrl('album','tag_list');?>"
	data-tags-url="<?php echo spUrl('album','tags');?>"
	data-fetchtpl-url="<?php echo spUrl('ajaxtpl','render_tpl');?>"
	data-editalbum-url="<?php echo spUrl('album','album_edit');?>"
	data-delalbum-url="<?php echo spUrl('album','album_delete');?>"
	data-userprofile-url="<?php echo spUrl('webuser','user_profile');?>"
	data-myalbum-url="<?php echo spUrl('my','album');?>"
	>
</script>
<script type="text/template" 
	id="data-tips"  
	data-welcome="<?php echo T('welcome_to_pintuxiu');?>" 
	data-error="<?php echo T('error');?>" 
	data-no-selection="<?php echo T('no_selection');?>"
	data-confirm-delete-share="<?php echo T('confirm_delete_share');?>"
	data-confirm-delete-comment="<?php echo T('confirm_delete_comment');?>"
	data-confirm-delete-album="<?php echo T('confirm_delete_album');?>"
	data-confirm-banuser="<?php echo T('confirm_delete_banuser');?>"
	data-not-null="<?php echo T('not_null');?>"
	data-required-field="<?php echo T('required_field');?>"
	data-not_valid="<?php echo T('not_valid');?>"
	data-intro_length_not_valid="<?php echo T('intro_length_not_valid');?>"
	data-title_length_not_valid="<?php echo T('title_length_not_valid');?>"
	data-img_desc_not_valid="<?php echo T('img_desc_not_valid');?>"
	data-no_img_cover_selected="<?php echo T('no_img_cover_selected');?>"
	data-no_img_share="<?php echo T('no_img_share');?>"
	data-album_cannot_be_null="<?php echo T('album_cannot_be_null');?>"
	data-nick_not_valid="<?php echo T('nick_not_valid');?>"
	data-nick_already_existed="<?php echo T('nick_already_existed');?>"
	data-email_already_existed="<?php echo T('email_already_existed');?>"
	data-password_not_valid="<?php echo T('password_not_valid');?>"
	data-password_not_match="<?php echo T('password_not_match');?>"
	data-not_all_digit="<?php echo T('not_all_digit');?>"
	data-length_rang="<?php echo T('length_rang');?>"
	data-link-not-null="<?php echo T('link_not_null');?>"
	data-success="<?php echo T('operate_succeed');?>" 
	data-loading="<?php echo T('loading');?>" 
	data-load_complete="<?php echo T('load_complete');?>" 
	data-fetch-success="<?php echo T('fetch_succeed');?>"
	data-fetch-faild="<?php echo T('fetch_failed');?>"
	data-no-image-fetch="<?php echo T('no_img_fetch');?>"
	data-loading-detail="<?php echo T('loading-detail');?>" 
	data-server-error="<?php echo T('server-error');?>" 
	data-add-like="<?php echo T('add_like_plus');?>" 
	data-remove-like="<?php echo T('remove_add_like');?>" 
	data-not_liked="<?php echo T('not_liked');?>" 
	data-like-already="<?php echo T('add_like_already');?>" 
	data-no-permission="<?php echo T('no_permission');?>" 
	data-forward-self="<?php echo T('forward_self');?>" 
	data-like-self="<?php echo T('like_self');?>"
	data-type_some="<?php echo T('type_some');?>"
	data-done="<?php echo T('done');?>"
	data-select_file="<?php echo T('select_file');?>"
	data-uploading="<?php echo T('uploading');?>"
	data-pls_select="<?php echo T('pls_select');?>"
	>
</script>
</body>
</html>
