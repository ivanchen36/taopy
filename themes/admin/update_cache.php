<?php echo $setting_header;?>
<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','update_cache',array('act'=>'update'));?>" method="post" class="form-horizontal">
   <fieldset>
    <div class="control-group">
            <label class="control-label" for="optionsCheckboxList"><?php echo T('update_cache_content');?></label>
            <div class="controls">
              <label class="checkbox">
                <input type="checkbox" name="cat_cache" value="1"><?php echo T('cat_cache');?>
              </label>
              <label class="checkbox">
                <input type="checkbox" name="smile_cache" value="1"><?php echo T('smile_cache');?>
              </label>
              <label class="checkbox">
                <input type="checkbox" name="sys_cache" value="1"><?php echo T('sys_cache');?>
              </label>
            </div>
   </div>

    <div class="form-actions">
  <button type="submit" class="btn btn-primary"><?php echo T('submit');?></button>
  </div>
  </fieldset>
</form>
</div>
</body>
</html>