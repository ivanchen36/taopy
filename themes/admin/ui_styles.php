<?php echo $setting_header;?>
<div class="formmain">
<?php echo $ui_nav;?>
<div class="formbox">
  <form action="<?php echo spUrl('admin','ui_styles',array('act'=>'save'));?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="color"><?php echo T('current_style');?></label>
            <div class="controls">
             	<input type="text" class="input-xlarge" id="color" name="color" value="<?php echo $vsettings['color'];?>">
                  <p class="help-block"><?php echo T('current_style_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="input01"><?php echo T('current_template');?></label>
            <div class="controls">
	             <select class="span2" id="style" name="style">
	                <?php foreach ($dirs as $d):?>
			            <option value="<?php echo $d;?>" <?php echo ($d==$vsettings['style'])?'selected':'';?>><?php echo $d;?></option>
			        <?php endforeach;?>
	              </select>
                  <p class="help-block"><?php echo T('current_template_tip');?></p>
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
