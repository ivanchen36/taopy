<script type="text/template" id="avatar_tpl" data-title="<?php echo T('p_create_avatar');?>">
	 <div id="upload_avatar_div" style="height:250px;">
		<div id="avatar_div">
			<div id="avatar_img_div" class="bglight"> </div>
			<div id="avatar_large_div" class="bglight"><img src="<?php echo base_url().$current_user['avatar_local'].'_large.jpg';?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_large.jpg';"/></div>
			<div id="avatar_middle_div" class="bglight"><img src="<?php echo base_url().$current_user['avatar_local'].'_middle.jpg';?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_middle.jpg';"/></div>
			<div id="avatar_small_div" class="bglight"><img src="<?php echo base_url().$current_user['avatar_local'].'_small.jpg';?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_small.jpg';"/></div>
		</div>
   		<div id="avatar_upload_btn" class="button"><?php echo T('select_file');?></div>
		<input type="hidden" id="avatar_upload_file"/>
   		<div class="actions border-top mt10"><button type="submit" data-action="saveAvatar" enable="false" class="btn btn_red"><span><?php echo T('submit');?></span></button></div>
	</div>
    <br/>
</script>
