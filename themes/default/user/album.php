<?php echo $tpl_header; ?>
<?php echo $tpl_user_banner; ?>
<div class="main"  id="waterfall_outer" data-fullscreen="0">
	<div class="g240 scroll-container">
	<?php echo $tpl_usercontrol;?>
	</div>
	<div class="g720 album-list mb30" id="waterfall" data-pin-width="235">
		<?php echo $tpl_waterfall; ?>
	</div>
</div>
<div class="clear"></div>
<div class="main">
	<div id="loadingPins" class="g960 text_c"><img src="<?php echo base_url()?>assets/img/ajax-loader.gif" alt="<?php echo T('loading')?>"></div>
</div>
<div id="page-nav" class="mt20 main of_h">
	<a id="page-next" href="<?php echo $nextpage_url; ?>"></a>
</div>
<?php echo $tpl_footer; ?>
