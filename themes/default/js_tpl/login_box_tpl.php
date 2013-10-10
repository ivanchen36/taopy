<script type="text/template" id="login_box_tpl" data-title="<?php echo T('login_tip');?>">
<div class="login_left">
	<div id="loginTabContent" class="tab-content form">
       <div class="tab-pane fade active in" id="ptx_login">
          <form id="login_form" method="post">
		        <input type="hidden" name="redirectUrl" value="/"/>
		        <ul>
		            <li><label for="email"><?php echo T('user_email');?></label><input id="email" type="text" name="email" value=""/>
		            </li>
		            <li><label for="password"><?php echo T('user_password');?></label><input id="password" type="password" name="password"/></li>
		            <li class="def">
					<label class="checkbox">
		    			<input name="is_remember" type="checkbox" value="1" checked="checked"/> <?php echo T('autologin_next');?>
		  			</label>
		            </li>
		            <li>
		                <button type="submit"><?php echo T('login');?></button>
		                　			<span style="display:none;"><a href="#"><?php echo T('forgot_password');?></a></span>
						<span id="ajax_message" class="ajax_error"></span>
		            </li>
		        </ul>
		    </form>
       </div>
    </div>
 </div>
    <ul class="other">
        <li><strong><?php echo T('other_login');?></strong> <?php echo T('or');?> <strong><a href="javascript:void(0);" data-action="openRegisterDialog"><?php echo T('register');?></a></strong></li>
		<?php foreach ($vendors as $vendor):$lowerkey = strtolower($vendor);?>
		<?php if($settings['api_setting'][$vendor]['OPEN']):?>
		<li><a href="<?php echo spUrl('social','go',array('vendor'=>$vendor));?>"><i class="login_icon_<?php echo $lowerkey;?>"></i><?php echo T($vendor.'_name');?></a></li>
		<?php endif;?>
		<?php endforeach;?> 
 	</ul>
</script>