<?php echo $setting_header;?>
<div class="formmain">
<?php echo $ui_nav;?>
<div class="formbox">
  <form action="<?php echo spUrl('admin','ui_pin',array('act'=>'save'));?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="pin_commentnum"><?php echo T('pin_commentnum');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="pin_commentnum" name="pin_commentnum" value="<?php echo $vsettings['pin_commentnum'];?>">
              <p class="help-block"><?php echo T('pin_commentnum_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="pin_ad"><?php echo T('pin_ad');?></label>
            <div class="controls">
              <input type="radio" name="pin_ad" class="input-xlarge" value="1" <?php echo $vsettings['pin_ad']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="pin_ad" class="input-xlarge" value="0" <?php echo !$vsettings['pin_ad']?'checked':'';?>><?php echo T('no');?></input>
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
