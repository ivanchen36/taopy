<script id="user_profile_tpl" type="text/template">
	<b class="arrow_t"><i class="arrow_inner_t"></i></b>
    {{#success}}
	{{#data}}
    <div class="info">
        <a href="{{user.home}}" target="_blank" class="avatar"><img src="{{user.avatar}}" width="50" height="50" /></a>
        <p><b><a href="{{user.home}}" target="_blank">{{user.nickname}}</a></b></p>
        <p class="meta"><a href="{{user.home}}" target="_blank">{{user.total_albums}}<?php echo T('album');?></a><em class="dot">●</em><a href="{{user.home}}" target="_blank">{{user.total_shares}}<?php echo T('share');?></a></p>
    </div>
    <div class="mark_list">
    {{#shares}}
    <a href="{{link}}" target="_blank"><img src="<?php echo base_url();?>{{image_path}}_square.jpg" title="{{title}}" /></a>
    {{/shares}}
    </div>
	{{/data}}
    {{/success}}
    {{^success}}
    <p class="message">{{{message}}}</p>
    {{/success}}

    <b class="arrow_b"><i class="arrow_inner_b"></i></b>
</script>