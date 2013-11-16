<?php echo $tpl_header; ?>
<?php echo $tpl_user_banner; ?>
<div class="main">
	<div class="g240 scroll-container">
	<?php echo $tpl_usercontrol;?>
	</div>
	
	<div class="g720 right-content white_bg mb30 mt20">
		<div class="page-header">
			<h5><?php echo T('tip_security_settings');?></h5>
  		</div>
  		<div class="page-content">
  		<form id="update_password_form" class="form" method="post">
		<ul>
		 <?php if($current_user['is_social']):?>
          <li><label for="email"><?php echo T('user_email');?></label>
          	<input type="text" class="span3" id="email" name="email">
           </li>
          <?php else:?>
         	<li><label for="email"><?php echo T('user_email');?></label>
            	<?php echo $current_user['email']?>
            </li>
          	<li><label for="org_passwd"><?php echo T('user_orgin_password');?></label>
            <input type="password" id="org_passwd" name="org_passwd">
            </li>
          <?php endif;?>
            <li><label for="new_passwd"><?php echo T('user_new_password');?></label>
              <input type="password" id="new_passwd" name="new_passwd"/>
            </li>
            <li><label for="new_verify_passwd"><?php echo T('re_user_new_password');?></label>
            <input type="password" id="new_verify_passwd" name="new_verify_passwd">
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
