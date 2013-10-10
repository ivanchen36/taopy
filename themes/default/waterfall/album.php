<?php if($albums): ?>
	<?php foreach ($albums as $album):?>
		<div class="pin hide album" id="<?php echo $album['album_id'];?>_album">
			<div class="actions">
				<?php if ($current_action=='favorite_album'):?>
				<a href="javascript:void(0);" data-action="removeLikeAlbum" data-params="<?php echo $album['album_id'];?>" class="btn_like"><i></i><?php echo T('already_like');?></a>
				<?php else :?>
				<a href="javascript:void(0);" data-action="addLikeAlbum" data-params="<?php echo $album['album_id'];?>" class="btn_like"><i></i><?php echo T('like');?></a>
		 		<?php endif;?>
		 	</div>
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
				<span class="f_l"><?php echo $album['nickname'];?> <?php echo T('share_at');?> <a href="<?php echo spUrl('album','index',array('cat'=>$album['category_id']));?>" target="_blank"><?php echo $album['category_name_cn'];?></a></span>
				<span class="f_r"><?php echo $album['total_share'];?></span>
				<span class="operations">
				<?php if($album['user_id']==$current_user['user_id']||$current_user['user_type']=='3'||$current_user['user_type']=='2'):?>
					<a href="javascript: void(0);" data-action="editAlbumOpen" data-params="<?php echo $album['album_id'].','.$album['album_title'].','.$album['category_id'].','.$album['category_name_cn'];?>"><?php echo T('edit');?></a> 
					<a href="javascript: void(0);" data-action="deleteAlbum" data-params="<?php echo $album['album_id'];?>"><?php echo T('delete');?></a> 
				<?php endif;?>
				</span>
			</div>
		</div>
	<?php endforeach;?>
<?php endif;?>