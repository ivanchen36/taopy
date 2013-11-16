<?php if($shares): ?>
	<?php foreach ($shares as $share):?>
		<?php if($share['share_type']=='ad'):?>
		<div class="pin hide">
			<div class="share_img" style="width: 200px;height:<?php echo $share['height'];?>px;">
				<iframe src="<?php echo spUrl('misc','adproxy',array('key'=>$share['key'],'ad_position'=>$share['ad_position']));?>" width="<?php echo $share['width'];?>" height="<?php echo $share['height'];?>" frameborder="0" scrolling="no">
				</iframe>
			</div>
			<div class="share_social c_b">
				<span class="prompt f_l"><?php echo $share['ad_name'];?></span>
			</div>
		</div>
		<?php else :?>
		<div class="pin hide" id="<?php echo $share['share_id'];?>">
			<div class="actions">
				<a href="javascript:void(0);" data-action="addLike" data-params="<?php echo $share['share_id'];?>" class="btn_like"><i></i><?php echo T('like');?></a>
				<a href="javascript:void(0);" data-action="<?php echo $current_user?'addCommentBox':'openLoginDialog';?>" data-params="<?php echo $share['share_id'];?>" class="btn_comment"><i></i><?php echo T('comment');?></a>
		 	</div>
			<div class="share_img">
				<?php $img_pro = str_to_arr($share['img_pro'], ',');$height=(200/$img_pro['width'])*$img_pro['height'];?>
                <a target="_blank" href="<?php echo host_url(spUrl("detail","index", array("share_id"=> $share['share_id'])));?>" class="image <?php echo $height>800?'long':'';?>" id="<?php echo $share['share_id']?>_image"><img class="s_image" src="<?php echo base_url($share['image_path'].'_middle.jpg?'.$hash); ?>" orgin_src="<?php echo base_url($share['image_path'].'_large.jpg');?>" width="200" height="<?php echo $height;?>" border="0"/><?php echo $height>800?'<span class="stop"></span>':'';?></a><?php if($share['price']):?><?php if($share['price'] != $share['old_price']):?><div class="old_price"><s><?php echo T('money_unit').number_format($share['old_price'],2);?></s></div><?php endif;?><div class="goods_price"><?php echo T('money_unit').number_format($share['price'],2);?></div><?php endif;?>
			</div>
			<div class="share_desc"><?php echo parse_message(sysSubStr($share['intro'],200,true));?></div>
			<div class="share_info">
				<span id="<?php echo $share['share_id']?>_likenum" class="likenum"><em> <?php echo $share['total_likes'];?></em><?php echo T('like');?></span>
				<span class="likenum"><em><?php echo $share['total_comments'];?></em> <?php echo T('comment');?></span>
				<?php if($current_user['user_type']=='3'||$current_user['user_type']=='2'||$share['user_id']==$current_user['user_id']):?>
				<br/>
				<span><a href="javascript: void(0);" data-action="editOShareOpen" data-params="<?php echo $share['share_id'];?>"><?php echo T('s_edit');?></a> 
				    <a href="javascript: void(0);" data-action="deleteShare" data-params="<?php echo $share['share_id'];?>"><?php echo T('s_delete');?></a> 
				<?php endif;?>
				<?php if($current_user['user_type']=='3'||$current_user['user_type']=='2'):?>
				    <a href="javascript: void(0);" data-action="openPushDialog" data-params="<?php echo $share['share_id'].','.$share['category_id'].','.$share['image_path'];?>"><?php echo T('s_push');?></a>
				    <a href="javascript: void(0);" data-action="confirmRedirect" data-params="<?php echo spUrl('webuser','banuser',array('uid'=>$share['user_id']));?>,banuser"><?php echo T('s_ban');?></a>
				</span>
				<?php endif;?>
			</div>
			<div class="share_people">
            <div class="shareface"><a target="_blank" data-user-id="<?php echo $share['user_id'];?>" data-user-profile="1" href="<?php echo spUrl('pub','index',array('uid'=>$share['user_id']));?>" class="trans07" data-user-id="<?php echo $share['user_id'];?>" data-user-profile="1"><img src="<?php echo $share['avatar_remote'];?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_small.jpg';" width="30" height="30"/></a> </div>
				<div class="shareinfo"> 
					<p><a target="_blank" href="<?php echo spUrl('pub','index',array('uid'=>$share['user_id']));?>" data-user-id="<?php echo $share['user_id'];?>" data-user-profile="1"><?php echo $share['user_nickname'];?></a> <?php echo ($share['user_id']==$share['poster_id'])?T('share'):T('forward');?><?php echo T('to');?> <a target="_blank" href="<?php echo spUrl("baseuser","album_shares", array("aid"=> $share['album_id']));?>"><?php echo $share['album_title'];?></a> </p>
				</div>
			</div>
			<div class="share_comments" id="<?php echo $share['share_id'].'_comments'?>">
		 		<?php $comments = unserialize($share['comments']);
		 		$comment_num=$settings['ui_pin']['pin_commentnum'];
		 		$comments=($comments)?array_slice($comments,0,$comment_num):null;?>
		  		<?php foreach ( $comments as $comment): ?>
				<div class="comment">
					<div class="shareface"><a class="trans07" href="<?php echo spUrl('pub','index',array('uid'=>$comment['user_id']));?>" data-user-id="<?php echo $comment['user_id'];?>" data-user-profile="1"><img src="<?php echo $comment['avatar_remote'];?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_small.jpg';" width="30" height="30"></a></div>
					<div class="shareinfo"><a href="<?php echo spUrl('pub','index',array('uid'=>$comment['user_id']));?>" data-user-id="<?php echo $comment['user_id'];?>" data-user-profile="1"><?php echo $comment['nickname'];?></a><p><?php echo parse_message(sysSubStr($comment['comment_txt'],50,true));?></p></div>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="share_comments commentdiv hide" id="<?php echo $share['share_id'].'_commentdiv'?>">
		  		<div class="comment">
					<div class="shareface"><img src="<?php echo $current_user['avatar_remote'] ?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_small.jpg';" width="30" height="30" /></div>
					<div class="shareinfo">
					<textarea id="<?php echo $share['share_id'].'_commentbox'?>" rows="2" class="borderclass" onkeyup="javascript:strLenCalc(this, '<?php echo $share['share_id'];?>checklen', 140);"></textarea>
					<span class="smalltxt"><i id="<?php echo $share['share_id'];?>checklen">140</i>/140</span><button data-action="addComment" data-params="<?php echo $share['share_id'];?>,comment_waterfall_tpl" class="graybtn f_r"><?php echo T('comment');?></button>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
	<?php endforeach;?>
<?php endif;?>
