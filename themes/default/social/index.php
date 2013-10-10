<?php echo $tpl_header; ?>
<div class="clear"></div>
<div class="main mt20">
	<div class="g960 round-shadow white_bg">
		<div class="category-header boder-b mt10 ml10 mr10">
			<h6 title="<?php echo T('social_login_page');?>"><a href="" target="_blank"><?php echo T('social_login_page');?></a></h5>
		</div>
		<div class="category-bd mt10 mb20 ml10 mr10">
		<form id="social_register_form" class="form"
			data-url="<?php echo spUrl('social','bind');?>"
			data-redirect-url="<?php echo spUrl('pin','index');?>" method="post">
			<input type="hidden" name="vendor" value="<?php echo $social_user_info['vendor'];?>"/>
		<ul>
			<li><label for="nickname"><?php echo T('avatar');?></label>
				<img src="<?php echo $social_user_info['avatar'];?>" width="50" height="50" />
              <p class="help-block"><?php echo T('this_is_your');?><?php echo $social_user_info['vendor'];?><?php echo T('avatar');?></p>
            </li>
            <li><label for="nickname"><?php echo T('user_nickname');?></label><input id="nickname" type="text" name="nickname" value="<?php echo $social_user_info['screen_name'];?>"/>
              <p class="help-block"><?php echo T('nick_not_valid');?></p>
            </li>
            <li>
                <button type="submit" class="btn btn_red"><?php echo T('active');?></button>
				<span id="ajax_message" class="ajax_error"></span>
            </li>
        </ul>
    	</form>
		
		
		</div>
	</div>
</div>

<?php echo $tpl_footer; ?>