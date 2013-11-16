<?php echo $tpl_header; ?>
<?php echo $tpl_user_banner; ?>
<div class="main">
	<div class="g240 scroll-container">
	<?php echo $tpl_usercontrol;?>
	</div>
	
	<div class="g720 right-content white_bg mb30 mt20">
		<div class="page-header">
			<h5><?php echo T('tip_user_settings')?></h5>
  		</div>
  		<div class="page-content">
  		<form id="update_userinfo" class="form" method="post">
		<ul>
			<li><label for="nickname"><?php echo T('label_image_settings');?></label>
				<button type="button" class="graybtn" data-action="openAvatar"><?php echo T('label_create_avatar');?></button>
              <p class="help-block"><?php echo T('tip_open_new_win');?></p>
            </li>
            <li><label for="nickname"><?php echo T('user_nickname');?></label><input id="nickname" type="text" name="nickname" value="<?php echo $user['nickname'];?>"/>
              <p class="help-block"><?php echo T('nick_not_valid');?></p>
            </li>
            <li><label for="usertitle"><?php echo T('custom_title');?></label><input id="usertitle" type="text" name="usertitle" value="<?php echo $user['user_title'];?>"/>
              <p class="help-block"><?php echo T('tip_custom_title');?></p>
            </li>
            <li><label for="gender"><?php echo T('label_gender');?></label>
 				<input type="radio" name="gender" id="optionsRadios1" value="male" <?php echo $user['gender']=='male'?checked:'';?>> <?php echo T('male');?>
 				<input type="radio" name="gender" id="optionsRadios2" value="female" <?php echo $user['gender']=='female'?checked:'';?>><?php echo T('female');?>
 				<input type="radio" name="gender" id="optionsRadios3" value="none" <?php echo (!$user['gender']||$user['gender']=='none')?checked:'';?>><?php echo T('none');?>
            </li>
            <?php if ($lang=='zh_cn'):?>
            <li><label for="province"><?php echo T('label_location');?></label>
             
              <select id="province" name="province"></select>&nbsp;&nbsp;
			  <select id="city" name="city"></select>
              <script type="text/javascript" src="<?php echo base_url('assets/js/city_utf8.js'); ?>"></script>
              <script type="text/javascript">
					initPlace('<?php echo $user['city'];?>','<?php echo $user['province'];?>');
			  </script>
            </li>
			<?php else:?>
			<li><label for="province">State:</label>
			 <input id="province" type="text" name="province" value="<?php echo $user['province'];?>"/>
			</li>
			<li><label for="province">City:</label>
				<input id="city" type="text" name="city" value="<?php echo $user['city'];?>"/>
			</li>
			<?php endif;?>
            <li><label for="bio"><?php echo T('user_description');?></label>
            	<textarea id="bio" name="bio" rows="3"><?php echo $user['bio'];?></textarea>
            </li>
            <li>
                <button type="submit" class="btn btn_red"><?php echo T('submit');?></button>
				<span id="ajax_message" class="ajax_error"></span>
            </li>
        </ul>
    	</form>
    	</div>
	</div>
</div>

<?php echo $tpl_footer; ?>
