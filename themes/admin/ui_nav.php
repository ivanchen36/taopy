<ul class="nav nav-tabs">
  <li<?php echo ($current_action == 'ui_layout') ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','ui_layout');?>"><?php echo T('ui_layout');?></a></li>
  <li<?php echo ($current_action == 'ui_pin') ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','ui_pin');?>"><?php echo T('ui_pin');?></a></li>
  <li<?php echo ($current_action == 'ui_album') ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','ui_album');?>"><?php echo T('ui_album');?></a></li>
  <li<?php echo ($current_action == 'ui_detail') ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','ui_detail');?>"><?php echo T('ui_detail');?></a></li>
  <li<?php echo ($current_action == 'ui_styles') ?  ' class="active"' : ''; ?>><a href="<?php echo spUrl('admin','ui_styles');?>"><?php echo T('ui_styles');?></a></li>
</ul>