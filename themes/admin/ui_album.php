<?php echo $setting_header;?>
<div class="formmain">
<?php echo $ui_nav;?>
<div class="formbox">
  <form id="save_form" action="<?php echo spUrl('admin','ui_album',array('act'=>'save'));?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="album_covernum"><?php echo T('album_covernum');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="album_covernum" name="album_covernum" value="<?php echo $vsettings['album_covernum'];?>">
              <p class="help-block"><?php echo T('album_covernum_tip');?></p>
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
