<?php echo $setting_header;?>
<div class="formmain">
<?php echo $ui_nav;?>
<div class="formbox">
  <form action="<?php echo spUrl('admin','ui_layout',array('act'=>'save'));?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="homepage_ad"><?php echo T('home_ad');?></label>
            <div class="controls">
              <input type="radio" name="homepage_ad" class="input-xlarge" value="1" <?php echo $vsettings['homepage_ad']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="homepage_ad" class="input-xlarge" value="0" <?php echo !$vsettings['homepage_ad']?'checked':'';?>><?php echo T('no');?></input>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="pin_auto"><?php echo T('pin_auto');?></label>
            <div class="controls">
              <input type="radio" name="pin_auto" class="input-xlarge" value="1" <?php echo $vsettings['pin_auto']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="pin_auto" class="input-xlarge" value="0" <?php echo !$vsettings['pin_auto']?'checked':'';?>><?php echo T('no');?></input>
              <p class="help-block"><?php echo T('auto_fit_tip');?>...</p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="album_auto"><?php echo T('album_auto');?></label>
            <div class="controls">
              <input type="radio" name="album_auto" class="input-xlarge" value="1" <?php echo $vsettings['album_auto']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="album_auto" class="input-xlarge" value="0" <?php echo !$vsettings['album_auto']?'checked':'';?>><?php echo T('no');?></input>
              <p class="help-block"><?php echo T('auto_fit_tip');?>...</p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="user_pin_auto"><?php echo T('user_pin_auto');?></label>
            <div class="controls">
              <input type="radio" name="user_pin_auto" class="input-xlarge" value="1" <?php echo $vsettings['user_pin_auto']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="user_pin_auto" class="input-xlarge" value="0" <?php echo !$vsettings['user_pin_auto']?'checked':'';?>><?php echo T('no');?></input>
              <p class="help-block"><?php echo T('auto_fit_tip');?>...</p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="login_reminder"><?php echo T('login_reminder');?></label>
            <div class="controls">
              <input type="radio" name="login_reminder" class="input-xlarge" value="1" <?php echo ($vsettings['login_reminder'])?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="login_reminder" class="input-xlarge" value="0" <?php echo (!$vsettings['login_reminder'])?'checked':'';?>><?php echo T('no');?></input>
              <p class="help-block"><?php echo T('login_reminder_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="pin_pagenum"><?php echo T('pin_pagenum');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="pin_pagenum" name="pin_pagenum" value="<?php echo $vsettings['pin_pagenum']?$vsettings['pin_pagenum']:15;?>">
              <p class="help-block"><?php echo T('default_pagenum');?>15。</p>
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
