<?php echo $tpl_header; ?>
<?php echo $tpl_user_banner; ?>
<div id="waterfall_outer" data-fullscreen="<?php echo $settings['ui_layout']['user_pin_auto']?1:0;?>" class="main">
	<div class="g240 scroll-container">
	<?php echo $tpl_usercontrol;?>
	</div>
	<div class="g720 inside" id="waterfall" data-pin-width="235">
		<?php echo $tpl_waterfall; ?>
	</div>
</div>

<div id="page-nav" class="mt20 main of_h">
	<a id="page-next" href="<?php echo $nextpage_url; ?>"></a>
</div>
<?php echo $tpl_footer; ?>
