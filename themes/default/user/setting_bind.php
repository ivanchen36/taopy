<?php echo $tpl_header; ?>
<?php echo $tpl_user_banner; ?>
<div class="main">
	<div class="g240 scroll-container">
	<?php echo $tpl_usercontrol;?>
	</div>

	<div class="g720 right-content white_bg mb30 mt20">
		<div class="page-header">
			<h5><?php echo T('tip_bind_settings');?></h5>
		</div>
		<div class="page-content">
	  		<div class="alert alert-info">
	        	<?php echo T('tip_bind_settings_long');?>
	     	</div>
	     	<?php foreach ($vendors as $v):?>
	     	<?php if($settings['api_setting'][$v]['APPKEY']):?>
		     	<?php if($cs[$v]):?>
				<div class="bind <?php echo $v.'_act';?>"><a href="<?php echo spUrl('social','unbind',array('vendor'=>$v));?>" class="trans06"><?php echo T('cancel_bind');?></a></div>
		     	<?php else:?>
				<div class="bind <?php echo $v;?>"><a href="<?php echo spUrl('social','go',array('vendor'=>$v));?>"><?php echo T('bind');?><?php echo $v;?><?php echo T('account');?></a></div>
				<?php endif;?>
	     	<?php endif;?>
			<?php endforeach;?>
		</div>
	</div>
</div>

<?php echo $tpl_footer; ?>
