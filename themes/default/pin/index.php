<?php echo $tpl_header; ?>
<div class="clear"></div>
<div id="waterfall_outer" data-fullscreen="<?php echo $settings['ui_layout']['pin_auto']?1:0;?>" class="main">
	<div class="g960" id="waterfall" data-pin-width="235" data-animated="0">
		<?php if($tag_group):?>
		<div class="pin border-top">
			<div class="tagbox">
			   <?php foreach ($tag_group as $group):?>
			  <div class="tag_title"><a href="<?php echo spUrl("pin","tgroup", array('tg'=>$group['tag_id']));?>"><strong><?php echo $group['tag_group_name_cn'];?></strong></a></div>
				<ul class="taglist">
				<?php $tags = explode(',',$group['tags']) ?>
				<?php foreach($tags as $tag):?>
				<li><a href="<?php echo spUrl("pin","index", array("tag"=> trim($tag)));?>"><?php echo trim($tag);?></a></li>
				<?php endforeach; ?>
				</ul>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif;?>
		<?php echo $tpl_waterfall; ?>
	</div>
</div>
<div class="clear"></div>
<div class="main">
	<div id="loadingPins" class="g960 text_c"><img src="<?php echo base_url()?>assets/img/ajax-loader.gif"></div>
</div>
<div id="page-nav" class="mt20 main of_h">
	<a id="page-next" href="<?php echo $nextpage_url; ?>"></a>
</div>
<div class="hide">
	<?php echo $pages;?>
</div>
<?php echo $tpl_footer; ?>