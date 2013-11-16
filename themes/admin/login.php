<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo base_url('themes/admin/css/bootstrap.min.css'); ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url('themes/admin/css/admin.css'); ?>" type="text/css" rel="stylesheet" />
<title><?php echo T('login')?></title>
<script language="JavaScript"> 
if (window != top) 
top.location.href = location.href; 
</script>
</head>
<body class="body_login">
<div class="admin_login">
<div class="admin_login_header">
<img src="<?php echo base_url('themes/admin/images/admin_logo.png'); ?>" alt="logo" />
</div>
<div class="admin_login_form">
  <form action="<?php echo spUrl('admin','login');?>" method="post" class="form-horizontal settingform">
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="email"><?php echo T('user_email')?></label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="email" name="email">
            </div>
          </div>
           <div class="control-group">
            <label class="control-label" for="password"><?php echo T('user_password')?></label>
            <div class="controls">
              <input type="password" class="input-xlarge" id="password" name="password">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn"><?php echo T('login')?></button>
          </div>
        </fieldset>
      </form>
</div>
</div>
</body>
</html>
