<?php echo $setting_header;?>
<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','ads_manage',array('act'=>'edit_submit'));?>" method="post" class="form-horizontal">
   <input type="hidden" name="key" value="<?php echo $ads_edit['key'];?>"/>
   <fieldset>
   <div class="control-group">
      <label class="control-label" for="ad_name"><?php echo T('th_title');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="ad_name" name="ad_name"  value="<?php echo $ads_edit['ad_name'];?>"/>
      </div>
    </div>
     <div class="control-group">
		<label class="control-label" for="ad_position"><?php echo T('th_type');?></label>
		<div class="controls">
			<select id="ad_position" name="ad_position">
			    <?php if($positions):?>
			    <?php foreach ($positions as $position):?>
			    <option value="<?php echo $position;?>" <?php echo ($position==$ads_edit['ad_position'])?'selected':'';?>><?php echo T($position);?></option>
			    <?php endforeach;?>
			    <?php endif;?>
			</select>
		</div>
	</div>
	<div class="control-group">
            <label class="control-label"><?php echo T('width').','.T('height');?></label>
            <div class="controls">
               <?php echo T('width');?><input type="text" class="input-small" id="width" name="width" value="<?php echo $ads_edit['width'];?>"> Px 
               <?php echo T('height');?><input type="text" class="input-small" id="height" name="height" value="<?php echo $ads_edit['height'];?>"> Px
            </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="ad_source"><?php echo T('th_content');?></label>
      <div class="controls">
        <textarea class="input-xlarge" id="ad_source" name="ad_source" rows="3"><?php echo $ads_edit['ad_source'];?></textarea>
        <p class="help-block">HTML is ok here.</p>
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