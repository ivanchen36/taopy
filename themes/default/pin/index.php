<?php echo $tpl_header; ?>
<div class="clear"></div>
<div id="waterfall_outer" data-fullscreen="<?php echo $settings['ui_layout']['pin_auto']?1:0;?>" class="main">
        <?php if($tag_group):?>
			<div class="tagbox" style="margin-left:10px;">
			  <div class="tag_title">
				<ul class="taglist">
                  <li <?php if(!$tg):?> class="active" <?php endif;?> ><a href="<?php echo spUrl('pin','index',array("cat"=>$category_id));?>">全部</a></li>
   			      <?php foreach ($tag_group as $group):?>
                  <li <?php if($tg == $group['tag_id']):?> class="active" <?php endif;?> ><a href="<?php echo spUrl("pin","index", array("cat"=>$category_id, 'tg'=>$group['tag_id']));?>"><?php echo $group['tag_group_name_cn'];?></a></li>
				  <?php endforeach; ?>
				</ul>
              </div>
			</div>
		<?php endif;?>
	<div class="g960" id="waterfall" data-pin-width="235" data-animated="0">
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
