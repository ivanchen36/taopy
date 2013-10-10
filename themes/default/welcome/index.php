<?php echo $tpl_header; ?>
<div class="clear"></div>
<div class="main mt10">
	<div class="g960 banner round-shadow white_bg">
		<div class="g640 mt10" id="slideshow">
			<div class="slideshow">
				<?php $i=0; foreach ($settings['homeslide'] as $slide):?>
				<?php $i++;?>
				<div <?php echo $i==1?'class="active"':''?>>
					<a target="_blank" href="<?php echo $slide['link_url'];?>">
						<img src="<?php echo base_url($slide['image_url']);?>" width="640" height="280"  onerror="this.src='<?php echo base_url().'assets/img/blank.png'?>';" /> 
					</a>
				</div>
				<?php endforeach;?>
			</div>
			<a href="#" id="prev" class="prev hide">Prev</a><a href="#" id="next" class="next hide">Next</a>
		</div>
		<div class="counter f_r of_h contentbox" id="con">
				<ul class="lastest_share" style="margin-top: 0px;">
					<?php foreach ($shares as $share):?>
					<li style="display: list-item;">
					<a href="<?php echo spUrl("detail","index", array("share_id"=> $share['share_id']));?>">
					<img
						src="<?php echo base_url().$share['image_path'].'_square.jpg'?>"
						width="50" height="50" onerror="this.src='<?php echo base_url().'assets/img/blank.png'?>';" />
					</a>
						<p><strong><a href="<?php echo spUrl('pub','index',array('uid'=>$share['poster_id']));?>"><?php echo $share['poster_nickname']?></a>:</strong><?php echo $share['intro']?></p><span class="sendtime"><?php echo friendlyDate($share['create_time']);?></span>
					</li>
					<?php endforeach;?>

				</ul>
		</div>
	</div>
</div>
<div class="clear"></div>
<div class="main mt20">
	<div class="g960 round-shadow white_bg">
		<div class="category-header mt10 boder-b ml10 mr10">
			<h5 title="<?php echo T('hot').T('album');?>"><a href="javascript:void(0)"><?php echo T('hot').T('album');?></a></h5>
			<ul>
				<?php foreach ($categories as $category):?>
				<li><a href="<?php echo spUrl('album','index',array('cat'=>$category['category_id']));?>" target="_blank"><?php echo $category['category_name_cn'];?></a></li>
				<?php endforeach;?>
			</ul>
			<a href="<?php echo spUrl('album','index');?>" target="_blank" class="more f_r"><?php echo T('friendly_tip_1');?>&gt;&gt;</a>
		</div>
		<div class="category-bd">
			<?php foreach ($albums as $album):?>
			<div class="album g225">
				<div class="album-header">
					<a href="<?php echo spUrl("baseuser","album_shares", array("aid"=> $album['album_id']));?>"><?php echo $album['album_title'];?></a>
				</div>
				<ul class="image_list">
					<?php $covers = str_to_arr_list($album['album_cover']);$num=0;?>
		        	<?php foreach ($covers as $share):?>
		        	<li><a href="<?php echo spUrl("detail","index", array("share_id"=> $share['share_id']));?>" class="trans07"><img src="<?php echo base_url($share['image_path'].'_square.jpg');?>" /></a></li>
		        	<?php $num++;?>
		        	<?php endforeach;?>
		        	<?php for ($i=$num;$i<9;$i++):?>
		        	<li></li>
		        	<?php endfor;?>
	        	</ul>
				<div class="album-footer">
					<span class="f_l"><a href="<?php echo spUrl('pub','index',array('uid'=>$album['user_id']));?>" data-user-id="<?php echo $album['user_id'];?>" data-user-profile="1"><?php echo $album['nickname'];?></a> <?php echo T('share_at');?> <a href="<?php echo spUrl('album','index',array('cat'=>$album['category_id']));?>" target="_blank"><?php echo $album['category_name_cn'];?></a></span>
					<span class="f_r"><?php echo $album['total_share'];?></span>
				</div>
			</div>
			<?php endforeach;?>
		</div>
	</div>
</div>
<?php foreach ($categories as $category):?>
<div class="clear"></div>
<div class="main mt20">
	<div class="g960 round-shadow white_bg">
		<div class="category-bd mt10">
			<div class="g960 inside">
				<div class="category-header boder-b">
					<h5 title="<?php echo $category['category_name_cn'];?>"><a href="<?php echo spUrl("pin","index", array("cat"=>$category['category_id']));?>" target="_blank"><?php echo $category['category_name_cn'];?></a></h5>
					<ul>
						<?php $hotwords = explode(',', $category['category_hot_words']);
							  $hotwords = array_slice($hotwords,0,8);
						?>
				      	<?php foreach ($hotwords as $hotword):?>
				        <li><a href="<?php echo spUrl('pin','index',array('keyword'=>$hotword));?>" target="_blank"><?php echo $hotword;?></a></li>
				        <?php endforeach;?>
					</ul>
					<a href="<?php echo spUrl("pin","index", array("cat"=>$category['category_id']));?>" target="_blank" class="more f_r"><?php echo T('more_tip_1')?>&gt;&gt;</a>
				</div>
				<div class="category-bd mb20" style="padding-left: 5px;">
					<?php echo $category['home_view'];?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach;?>
<?php if($settings['ui_layout']['homepage_ad']&&$ads=$settings['homepage_ad']):?>
<?php foreach ($ads as $ad):?>
<div class="clear"></div>
<div class="main mt20">
	<div class="g960 round-shadow white_bg">
		 <iframe src="<?php echo spUrl('misc','adproxy',array('key'=>$ad['key'],'ad_position'=>'homepage_ad'));?>" width="<?php echo $ad['width'];?>" height="<?php echo $ad['height'];?>" frameborder="0" scrolling="no">
		 </iframe>
	</div>
</div>
<?php endforeach;?>
<?php endif;?>
<!-- friend link -->
<div class="clear"></div>
<div class="main mt20">
	<div class="g960 round-shadow white_bg">
		<div class="category-header boder-b mt10 ml10 mr10">
			<h5 title="<?php echo T('frindlink');?>"><a href="#" target="_blank"><?php echo T('frindlink');?></a></h5>
		</div>
		<div class="category-bd mt10 mb20 ml10 mr10">
		<?php foreach ($settings['frindlink'] as $link):?>
        	<div class="g80 friendlink"><a href="<?php echo $link['link_url'];?>" target="_blank"><?php echo $link['link_name'];?></a></div>
      	<?php endforeach;?>
		</div>
	</div>
</div>

<div class="clear"></div>
<?php echo $tpl_footer; ?>
