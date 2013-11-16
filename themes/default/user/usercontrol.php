<?php if($user['user_id']):?>
<div class="usercontrol pin scroll white_bg">
 	<div class="userbox">
	    <div class="avatar"><a href="javascript:void(0);"><img src="<?php echo $current_user['avatar_remote'];?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_middle.jpg';" /></a></div>
	    <div class="info ml20"><a href="javascript:void(0);"><strong class="colororange"><?php echo $user['nickname'];?></strong></a>
	      <p class="smalltxt"><?php echo $user['user_title'];?></p>
	      <p class="smalltxt"><?php echo T($user['gender'])?> <?php echo ($user['province']) ? $user['province'].'-'.$user['city'] : T('earth');?></p>
	    </div>
	</div>
	<div class="desc">
		<p class="smalltxt"><?php echo $user['bio']?sysSubStr($user['bio'],120,true):T('user_no_description');?></p>
	</div>
  <div class="nums">
  <ul>
  	<li>
  		<a href="<?php echo spUrl("my","shares",array('uid'=>$user['user_id']));?>">
	  		<strong class="colororange"><?php echo $user['total_shares'];?></strong>
	  		<span class="colormiddle"><?php echo T('share');?></span>
  		</a>
  	</li>
  	<li>
  		<a href="<?php echo spUrl("my","album",array('uid'=>$user['user_id']));?>">
	  		<strong class="colororange"><?php echo $user['total_albums'];?></strong>
	  		<span class="colormiddle"><?php echo T('album');?></span>
  		</a>
  	</li>
  	<li class="noborder">
  		<a href="javascript:;">
	  		<strong class="colororange"><?php echo $user['total_likes'];?></strong>
	  		<span class="colormiddle"><?php echo T('like');?></span>
  		</a>
  	</li>
  </ul>
  </div>
  <div class="clear"></div>
  <div class="menu">
    <ul>
      <?php if($can_post):?>
      <li><a href="javascript:void(0);" data-action="openPublishSelectDialog"><i></i><?php echo T('add_pin');?></a></li>
      <?php endif;?>
            <li class="my <?php echo ($current_action=='shares')?'active':'';?>"><a href="<?php echo spUrl("my","shares");?>"><i></i><?php echo T('my_share_pin');?></a></li>
            <li class="favs <?php echo ($current_action=='favorite_share')?'active':'';?>"><a href="<?php echo spUrl("my","favorite_share");?>"><i></i><?php echo T('my_love_pin');?></a></li>
      </li>
            <li class="alb <?php echo ($current_action=='album')?'active':'';?>"><a href="<?php echo spUrl("my","album");?>"><i></i><?php echo T('my_album');?></a></li>
            <li class="fava <?php echo ($current_action=='favorite_album')?'active':'';?>"><a href="<?php echo spUrl("my","favorite_album");?>"><i></i><?php echo T('my_love_album');?></a></li>
     </li>
    </ul>
  </div>
</div>
<?php endif;?>
