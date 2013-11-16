<?php echo $setting_header;?>
<div class="formmain">
<?php echo $setting_nav;?>
<div class="formbox">
<div class="alert alert-danger">
	    <strong>暂不可用!</strong> 稍候推出升级包。
	</div>
  <form action="<?php echo spUrl('admin','setting_update',array('act'=>'save'));?>" method="post"  class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="ftp_address">FTP地址：</label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="ftp_address" name="ftp_address" value="<?php echo $update['ftp_address'];?>">
              <p class="help-block">请输入FTP地址</p>
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="ftp_port">FTP端口：</label>
            <div class="controls">
              <input type="text"  class="input-xlarge" id="ftp_port" name="ftp_port" value="<?php echo $update['ftp_port'];?>">
              <p class="help-block">请填写FTP端口，一般为21</p>
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="ftp_user">FTP用户名：</label>
            <div class="controls">
              <input type="text"  class="input-xlarge" id="ftp_user" name="ftp_user" value="<?php echo $update['ftp_user'];?>">
              <p class="help-block">请填写FTP用户名</p>
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="ftp_passwd">FTP密码：</label>
            <div class="controls">
              <input type="text"  class="input-xlarge" id="ftp_passwd" name="ftp_passwd" value="<?php echo $update['ftp_passwd'];?>">
              <p class="help-block">请填写FTP密码</p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="remote_dir">FTP中网站目录：</label>
            <div class="controls">
              <input type="text"  class="input-xlarge" id="remote_dir" name="remote_dir" value="<?php echo $update['remote_dir'];?>">
              <p class="help-block">请填写网站目录，请在末尾跟上‘/’。例如/public/</p>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">确定</button> 
          </div>
        </fieldset>
      </form>
</div>
</div>
</div>
</body>
</html>
