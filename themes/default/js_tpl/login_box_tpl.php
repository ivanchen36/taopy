<script type="text/template" id="login_box_tpl" data-title="<?php echo T('login_tip');?>">
    <ul class="other">
		<?php foreach ($vendors as $vendor):$lowerkey = strtolower($vendor);?>
		<?php if($settings['api_setting'][$vendor]['OPEN']):?>
		<li><a href="<?php echo spUrl('social','go',array('vendor'=>$vendor));?>"><i class="login_icon_<?php echo $lowerkey;?>"></i><?php echo T($vendor.'_name');?></a></li>
		<?php endif;?>
		<?php endforeach;?> 
 	</ul>
</script>
