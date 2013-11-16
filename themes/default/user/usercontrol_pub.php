<?php if($user['user_id']):?>
<div class="usercontrol pin scroll white_bg">
 	<div class="userbox">
	    <div class="avatar"><a href="javascript:void(0);"><img src="<?php echo $user['avatar_remote'];?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_middle.jpg';" /></a></div>
	    <div class="info ml10"><a href="javascript:void(0);"><strong class="colororange"><?php echo $user['nickname'];?></strong></a>
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
  		<a href="<?php echo spUrl("pub","shares",array('uid'=>$user['user_id']));?>">
	  		<strong class="colororange"><?php echo $user['total_shares'];?></strong>
	  		<span class="colormiddle"><?php echo T('share');?></span>
  		</a>
  	</li>
  	<li>
  		<a href="<?php echo spUrl("pub","album",array('uid'=>$user['user_id']));?>">
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
      <li class="my <?php echo ($current_action=='shares'||$current_action=='favorite_share')?'active':'';?>"><a href="<?php echo spUrl("pub","shares",array('uid'=>$user['user_id']));?>"><i></i><?php echo T('his_her_pin');?></a>
      </li>
      <li class="alb <?php echo (stripos($current_action,'album')!== false)?'active':'';?>"><a href="<?php echo spUrl("pub","album",array('uid'=>$user['user_id']));?>"><i></i><?php echo T('his_her_album');?></a>
      </li>
    </ul>
  </div>
</div>
<?php endif;?>
