<?php echo $setting_header;?>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','smile_list',array('act'=>'edit','smile_id'=>$smile['smile_id']));?>" method="post" class="form-horizontal">
   <fieldset>
   
   <div class="control-group">
      <label class="control-label" for="code"><?php echo T('smile_code');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="code" name="code" value="<?php echo $smile['code'];?>"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="url"><?php echo T('file_name');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="url" name="url" value="<?php echo $smile['url'];?>" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="displayorder"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="displayorder" name="displayorder" value="<?php echo $smile['displayorder'];?>" />
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