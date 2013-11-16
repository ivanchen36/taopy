<?php echo $setting_header;?>
<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','tag_list',array('act'=>'edit','tagid'=>$tag['tag_id']));?>" method="post" class="form-horizontal">
   <fieldset>
   
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('tag_group_name_cn');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="tag_group_name_cn" name="tag_group_name_cn" value="<?php echo $tag['tag_group_name_cn'];?>"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('tag_group_name_en');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="tag_group_name_en" name="tag_group_name_en" value="<?php echo $tag['tag_group_name_en'];?>" />
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category');?></label>
      <div class="controls">
        <select id="category_id" name="category_id">
            <?php if($categories):?>
            <?php foreach ($categories as $category):?>
              <option value="<?php echo $category['category_id'];?>" <?php echo ($category['category_id']==$tag['category_id'])?'selected':'';?>><?php echo $category['category_name_cn'];?></option>
            <?php endforeach;?>
            <?php endif;?>
            </select>
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('tags_content');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="tags" name="tags"  value="<?php echo $tag['tags'];?>"/>
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="display_order" name="display_order"  value="<?php echo $tag['display_order'];?>"/>
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