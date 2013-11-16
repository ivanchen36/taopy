<ul class="nav nav-tabs">
  <li<?php echo ($current_action == 'setting_basic') ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','setting_basic');?>"><?php echo T('setting_basic');?></a></li>
  <li<?php echo ($current_action == 'setting_optimizer') ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','setting_optimizer');?>"><?php echo T('setting_optimizer');?></a></li>
  <li<?php echo ($current_action == 'setting_seo')   ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','setting_seo');?>"><?php echo T('setting_seo');?></a></li>
  <li<?php echo ($current_action == 'setting_file')  ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','setting_file');?>"><?php echo T('setting_file');?></a></li>
  <?php if($lang=='zh_cn'):?>
  <li<?php echo ($current_action == 'setting_api')  ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','setting_api');?>"><?php echo T('setting_api');?></a></li>
  <?php endif;?>
</ul>