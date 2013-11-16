<?php echo $setting_header;?>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','category_list',array('act'=>'edit','catid'=>$category['category_id']));?>" method="post" class="form-horizontal">
   <fieldset>
   
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category_name_cn');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="category_name_cn" name="category_name_cn" value="<?php echo $category['category_name_cn'];?>"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category_name_en');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="category_name_en" name="category_name_en" value="<?php echo $category['category_name_en'];?>" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category_hot_words');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="category_hot_words" name="category_hot_words" value="<?php echo $category['category_hot_words'];?>" />
        <p class="help-block"><?php echo T('category_hot_words_tip');?></p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="display_order" name="display_order" value="<?php echo $category['display_order'];?>" />
      </div>
    </div>
    <div class="control-group">
            <label class="control-label" for="is_open"><?php echo T('category_is_open');?></label>
            <div class="controls">
              <input type="radio" name="is_open" class="input-xlarge" value="1" <?php echo ($category['is_open'])?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="is_open" class="input-xlarge" value="0" <?php echo (!$category['is_open'])?'checked':'';?>><?php echo T('no');?></input>
            </div>
    </div>
    <div class="control-group">
            <label class="control-label" for="is_home"><?php echo T('category_is_home');?></label>
            <div class="controls">
              <input type="radio" name="is_home" class="input-xlarge" value="1" <?php echo ($category['is_home'])?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="is_home" class="input-xlarge" value="0" <?php echo (!$category['is_home'])?'checked':'';?>><?php echo T('no');?></input>
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