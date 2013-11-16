<div class="nav-cat f_l">
	<?php $need_cat = in_array($current_controller,array('pin','album'));
		  if($current_controller=='my'&&in_array($current_action, array('shares','favorite_shares','album'))){
		  	$need_cat = true;
		  }
		  if($current_action=='tgroup') $need_cat = false;
	?>
	<a href="javascript:void(0);" class="first"> <?php switch ($current_controller) {
		case 'pin':
			echo T('pin');
			break;
		case 'album':
			echo T('album_long');
			break;
		case 'my':
			echo T('my_pin');
			break;
		case 'pub':
			echo T('his_her_pin');
			break;
		default:
			echo T('pin_long');
			break;
	}?> </a>
	<?php if($categories&&$need_cat):?>
	<em class="dot">●</em> <a
	<?php echo !$category_id?'class="selected"':'';?>
		href="<?php echo spUrl($current_controller,$current_action);?>"><?php echo T('all_category');?></a>
		<?php foreach ($categories as $category):?>
	<em class="dot">●</em> <a <?php echo $category_id==$category['category_id']?'class="selected"':'';?>
		href="<?php echo spUrl($current_controller,$current_action, array("cat"=>$category['category_id'])); ?>"><?php echo $category['category_name_cn'] ?>
	</a>
	<?php endforeach;?>
	<?php elseif($current_action=='tgroup'):?>
		<em class="dot">●</em> <a href="<?php echo spUrl('pin','index',array("cat"=>$tgroup['category_id']));?>"><?php echo $tgroup['category_name_cn'];?></a>
		<em class="dot">●</em> <a class="selected" href="<?php echo spUrl('pin','tgroup',array("tg"=>$tgroup['tag_id']));?>"><?php echo $tgroup['tag_group_name_cn'];?></a>
	<?php else:?>
	<em class="dot">●</em> <a href="<?php echo spUrl('pin','hot');?>"><?php echo T('hot_pin');?></a>
	<em class="dot">●</em> <a href="<?php echo spUrl('pin','lastest');?>"><?php echo T('lastest_pin');?></a>
	<?php endif;?>
</div>
