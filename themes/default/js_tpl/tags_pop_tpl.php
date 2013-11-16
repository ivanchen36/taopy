<script id="tags_pop_tpl" type="text/template">
	<b class="arrow_t"><i class="arrow_inner_t"></i></b>
	{{#data}}
    <div class="tag_title">
	<a href="<?php echo spUrl("pin","tgroup", array('tg'=>'{{tag_id}}'));?>"><strong>{{tag_group_name_cn}}</strong></a></div>
		<ul class="taglist">
		{{#tags}}
		<li><a href="<?php echo spUrl("pin","index", array("tag"=>'{{.}}'));?>">{{.}}</a></li>
		{{/tags}}
		</ul>
	{{/data}}
    <b class="arrow_b"><i class="arrow_inner_b"></i></b>
</script>