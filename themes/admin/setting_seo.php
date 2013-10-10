<?php echo $setting_header;?>
<div class="formmain">
<?php echo $setting_nav;?>
<div class="formbox">
  <form action="<?php echo spUrl('admin','setting_seo',array('act'=>'save'));?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="page_title"><?php echo T('site_title');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="page_title" name="page_title" value="<?php echo $vsettings['page_title'];?>">
                  <p class="help-block"><?php echo T('site_title');?></p>
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="page_keywords"><?php echo T('page_keywords');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="page_keywords" name="page_keywords" value="<?php echo $vsettings['page_keywords'];?>">
              <p class="help-block"><?php echo T('page_keywords_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="page_description"><?php echo T('description');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="page_description" name="page_description" value="<?php echo $vsettings['page_description'];?>">
              <p class="help-block"><?php echo T('description');?></p>
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
