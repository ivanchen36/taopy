<?php echo $setting_header;?>
<div class="formmain">
<?php echo $ui_nav;?>
<div class="formbox">
  <form action="<?php echo spUrl('admin','ui_detail',array('act'=>'save'));?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="detail_album"><?php echo T('detail_album');?></label>
            <div class="controls">
              <input type="radio" name="detail_album" class="input-xlarge" value="1" <?php echo $vsettings['detail_album']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="detail_album" class="input-xlarge" value="0" <?php echo !$vsettings['detail_album']?'checked':'';?>><?php echo T('no');?></input>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="detail_same_from"><?php echo T('detail_same_from');?></label>
            <div class="controls">
              <input type="radio" name="detail_same_from" class="input-xlarge" value="1" <?php echo $vsettings['detail_same_from']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="detail_same_from" class="input-xlarge" value="0" <?php echo !$vsettings['detail_same_from']?'checked':'';?>><?php echo T('no');?></input>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="detail_history"><?php echo T('detail_history');?></label>
            <div class="controls">
              <input type="radio" name="detail_history" class="input-xlarge" value="1" <?php echo $vsettings['detail_history']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="detail_history" class="input-xlarge" value="0" <?php echo !$vsettings['detail_history']?'checked':'';?>><?php echo T('no');?></input>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="detail_may_like"><?php echo T('detail_may_like');?></label>
            <div class="controls">
              <input type="radio" name="detail_may_like" class="input-xlarge" value="1" <?php echo $vsettings['detail_may_like']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="detail_may_like" class="input-xlarge" value="0" <?php echo !$vsettings['detail_may_like']?'checked':'';?>><?php echo T('no');?></input>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="detail_ad"><?php echo T('detail_ad');?></label>
            <div class="controls">
              <input type="radio" name="detail_ad" class="input-xlarge" value="1" <?php echo $vsettings['detail_ad']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="detail_ad" class="input-xlarge" value="0" <?php echo !$vsettings['detail_ad']?'checked':'';?>><?php echo T('no');?></input>
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
