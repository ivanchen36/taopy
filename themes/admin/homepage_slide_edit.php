<?php echo $setting_header;?>
<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','homepage_slide',array('act'=>'edit_submit'));?>" method="post" class="form-horizontal">
   <fieldset>
   <div class="control-group">
      <label class="control-label" for="input01"></label>
      <div class="controls" id="imgview">
      	<img src="<?php echo base_url().$slide['image_url'];?>" height="200" />
      </div>
    	<input type="hidden" id="key" name="key" value="<?php echo $slide['key'];?>"/>
   </div>
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('image_desc');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="desc" name="desc" value="<?php echo $slide['desc'];?>"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('link_url');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="link_url" name="link_url"  value="<?php echo $slide['link_url'];?>"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="order" name="order"  value="<?php echo $slide['order'];?>"/>
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