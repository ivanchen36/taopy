<?php echo $setting_header;?>
<div class="formmain">
<?php echo $setting_nav;?>
<div class="formbox pl30">
  <form action="<?php echo spUrl('admin','setting_file',array('act'=>'save'));?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label"><?php echo T('allow_upload_size');?></label>
            <div class="controls">
              <input type="text" class="input-small" id="upload_file_size" name="upload_file_size" value="<?php echo $vsettings['upload_file_size'];?>"> KB
            </div>
          </div>
           <div class="control-group">
            <label class="control-label"><?php echo T('allow_upload_type');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="upload_file_type" name="upload_file_type"  value="<?php echo $vsettings['upload_file_type'];?>">
              <p class="help-block"><?php echo T('allow_upload_type');?>(jpg|gif|png)</p>
            </div>
          </div>
            <div class="control-group">
            <label class="control-label"><?php echo T('allow_upload_maxsize');?></label>
            <div class="controls">
               <?php echo T('width');?><input type="text" class="input-small" id="upload_image_size_h" name="upload_image_size_h"  value="<?php echo $vsettings['upload_image_size_h'];?>"> x<?php echo T('height');?>
               <input type="text" class="input-small" id="upload_image_size_w" name="upload_image_size_w" value="<?php echo $vsettings['upload_image_size_w'];?>"> Px
              <p class="help-block"><?php echo T('allow_upload_maxsize_tip');?></p>
             </div>
          </div>
            <div class="control-group">
            <label class="control-label"><?php echo T('remote_fetch_maxsize');?></label>
            <div class="controls">
              <?php echo T('width');?> <input type="text" class="input-small" id="fetch_image_size_h" name="fetch_image_size_h"  value="<?php echo $vsettings['fetch_image_size_h'];?>"> x<?php echo T('height');?>
               <input type="text" class="input-small" id="fetch_image_size_w" name="fetch_image_size_w" value="<?php echo $vsettings['fetch_image_size_w'];?>"> Px
              <p class="help-block"><?php echo T('remote_fetch_maxsize_tip');?></p>
            </div>
          </div>
         
          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo T('save');?></button> 
          </div>
        </fieldset>
      </form>
</div>
</div>
</div>
</body>
</html>
