<script type="text/template" id="comment_waterfall_tpl" reload="true">
 {{#data}} 
<div class="comment hide">
	<div class="shareface"><a class="trans07" href="javascript: void(0);" data-user-id="{{user_id}}" data-user-profile="1"><img src="{{user_avatar}}" onerror="javascript:this.src = base_url + '/assets/img/avatar_small.jpg';" width="30" height="30"></a></div>
	<div class="shareinfo"><a class="trans07" href="javascript: void(0);" data-user-id="{{user_id}}" data-user-profile="1">{{nickname}}</a><p>{{{comment_txt}}}</p></div>
</div>
 {{/data}} 
</script>
<script type="text/template" id="comment_detail_tpl" reload="false">
 {{#data}} 
	<li class="list_info hide" id="comment_{{comment_id}}"> 
		<a class="img_face" href="javascript:void(0);" data-user-id="{{user_id}}" data-user-profile="1"><img src="{{user_avatar}}"  onerror="javascript:this.src = base_url + '/assets/img/avatar_small.jpg';" width="30" height="30"/></a>
    	<p>
		<span><a href="javascript:void(0);" data-user-id="{{user_id}}" data-user-profile="1">{{nickname}}</a></span>
		<span class="cgray f_r">{{post_time_friend}}</span>
		</p>
		<p class="tj">{{{comment_txt}}}
			<?php if($current_user['user_type']>1):?>
        	<span><a href="javascript:void(0);" data-action="delComment" data-params="{{share_id}},{{comment_id}}"><?php echo T('delete');?></a></span>
    		<?php endif;?>
		</p>
	</li>
 {{/data}} 
</script>