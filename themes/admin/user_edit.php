<?php echo $setting_header;?>
<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','user_list',array('act'=>'edit','uid'=>$user['user_id']));?>" method="post" class="form-horizontal">
   <fieldset>
   <input type="hidden" id="hash" name="hash" value="sKHIIB*&%^(HG"/>
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('user_nickname');?></label>
      <div class="controls">
        <span class="input-xlarge"><?php echo $user['nickname'];?></span>
      </div>
    </div>
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('user_password');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="password" name="password">
         <p class="help-block"><?php echo T('not_modify_leave_empty');?></p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('usertype');?></label>
      <div class="controls">
        <select id="user_type" name="user_type">
            <option value="0" <?php echo ($user['user_type']==0)?'selected':'';?>><?php echo T('banned_visit');?></option>
            <option value="1" <?php echo ($user['user_type']==1)?'selected':'';?>><?php echo T('member');?></option>
            <option value="2" <?php echo ($user['user_type']==2)?'selected':'';?>><?php echo T('editer');?></option>
            <option value="3" <?php echo ($user['user_type']==3)?'selected':'';?>><?php echo T('admin');?></option>
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('custom_title');?></label>
      <div class="controls">
      	<input type="text" class="input-xlarge" value="<?php echo $user['user_title'];?>" id="user_title" name="user_title">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="bio"><?php echo T('user_description');?></label>
      <div class="controls">
      	<textarea class="input-xlarge" id="bio" name="bio" rows="3"><?php echo $user['bio'];?></textarea>
         <p class="help-block"><?php echo T('user_description');?></p>
      </div>
    </div>
    <div class="form-actions">
  <button type="submit" class="btn"><?php echo T('save');?></button>
  </div>
  </fieldset>
</form>
</div>
</body>
</html>