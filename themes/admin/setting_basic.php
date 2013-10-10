<?php echo $setting_header;?>
<div class="formmain">
<?php echo $setting_nav;?>
<div class="formbox">
  <form action="<?php echo spUrl('admin','setting_basic',array('act'=>'save'));?>" method="post"  class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="lang"><?php echo T('current_lang');?></label>
            <div class="controls">
	             <select class="span2" id="lang" name="lang">
	                <?php foreach ($dirs as $d):?>
			            <option value="<?php echo $d;?>" <?php echo ($d==$site_info['lang'])?'selected':'';?>><?php echo $d;?></option>
			        <?php endforeach;?>
	              </select>
                  <p class="help-block"><?php echo T('current_lang_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="site_name"><?php echo T('site_name');?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="site_name" name="site_name" value="<?php echo $site_info['site_name'];?>">
              <p class="help-block"><?php echo T('site_name');?></p>
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="site_domain"><?php echo T('site_domain');?></label>
            <div class="controls">
              <input type="text"  class="input-xlarge" id="site_domain" name="site_domain" value="<?php echo $site_info['site_domain'];?>">
              <p class="help-block"><?php echo T('site_domain_tip');?></p>
            </div>
          </div>
          <?php if($lang=='zh_cn'):?>
           <div class="control-group">
            <label class="control-label" for="site_beian">站点备案号</label>
            <div class="controls">
              <input type="text"  class="input-xlarge" id="site_beian" name="site_beian" value="<?php echo $site_info['site_beian'];?>">
              <p class="help-block">请填写站点备案号，格式为：京ICP备xxxxxx</p>
            </div>
          </div>
          <?php endif;?>
          <div class="control-group">
            <label class="control-label" for="site_tongji"><?php echo T('site_analyze_code');?>:</label>
            <div class="controls">
              <textarea class="input-xlarge" id="site_tongji" name="site_tongji" rows="3"><?php echo $site_info['site_tongji'];?></textarea>
              <p class="help-block"><?php echo T('site_name');?><?php echo T('site_analyze_code_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="site_need_verify"><?php echo T('content_verify');?></label>
            <div class="controls">
              <input type="radio" name="site_need_verify" class="input-xlarge" value="1" <?php echo $site_info['site_need_verify']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="site_need_verify" class="input-xlarge" value="0" <?php echo !$site_info['site_need_verify']?'checked':'';?>><?php echo T('no');?></input>
              <p class="help-block"><?php echo T('content_verify_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="forbid_user_post"><?php echo T('forbid_member_share');?></label>
            <div class="controls">
              <input type="radio" name="forbid_user_post" class="input-xlarge" value="1" <?php echo $site_info['forbid_user_post']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="forbid_user_post" class="input-xlarge" value="0" <?php echo !$site_info['forbid_user_post']?'checked':'';?>><?php echo T('no');?></input>
              <p class="help-block"><?php echo T('forbid_member_share_tip');?></p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="site_close"><?php echo T('close_site');?></label>
            <div class="controls">
              <input type="radio" name="site_close" class="input-xlarge" value="1" <?php echo $site_info['site_close']?'checked':'';?>><?php echo T('yes');?></input>
              <input type="radio" name="site_close" class="input-xlarge" value="0" <?php echo !$site_info['site_close']?'checked':'';?>><?php echo T('no');?></input>
              <p class="help-block"><?php echo T('close_site_tip');?></p>
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
