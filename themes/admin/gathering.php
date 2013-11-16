<?php echo $setting_header;?>
<div class="tablebox_header">   
<form action="<?php echo spUrl('admin','gathering', array('act'=>'search'));?>" method="post" class="form-search pull-left">
        <span class="label">淘宝搜索</span>
        <input type="text" name="keyword" class="input-medium search-query" value="<?php echo $keyword;?>">
 		<select id="channel_category_id" name="channel_category_id">
			  <option value="0" <?php echo ($channel_category_id==0)?'selected':'';?>>所有分类</option>
			  <?php if($channel_categories):?>
			  <?php foreach ($channel_categories as $category):?>
			  <option value="<?php echo $category->cid;?>" <?php echo ($channel_category_id==$category->cid)?'selected':'';?>><?php echo $category->name;?></option>
			  <?php endforeach;?>
			  <?php endif;?>
		</select>
        <button type="submit" class="btn"><?php echo T('search');?></button>
</form>
<br/>
<br/>
<form id="publish_form" method="post" class=" form-search pull-left">
        <a href="javascript:void(0);" id="select_all" class="label label-warning">全选</a>
 		<span class="label label-info">发布至分类</span>
 		<select id="category_id" name="category_id">
			  <?php if($categories):?>
			  <?php foreach ($categories as $category):?>
			  <option value="<?php echo $category['category_id'];?>"><?php echo $category['category_name_cn'];?></option>
			  <?php endforeach;?>
			  <?php endif;?>
		</select>
		<span class="label label-info">专辑</span>
		<select id="album_id" name="album_id">
			  
		</select>
		<input id="num_iid_str" name="num_iid_str" type="hidden"/>
        <button type="submit" class="btn btn-primary">发布</button> 
		<br/><span id="ajax_message" class="label label-important">赶快发布吧</span>
</form>
</div>
<div class="tablebox">
   <table class="table table-striped table-bordered table-condensed">
        <thead>
          <tr>
            <th width="20">选择</th>
            <th width="250">商品信息</th>
            <th width="30">店铺</th>
            <th width="30">价格</th>
            <th width="30">比率</th>
            <th width="30">佣金</th>
            <th width="100">推广信息</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($items['items'] as $item):?>
          <tr>
            <td><input type="checkbox" name="num_iid" value="<?php echo $item->num_iid;?>" /></td>
            <td><img src="<?php echo $item->pic_url.'_100x100.jpg'; ?>" height="50" style="float: left;"/>
            	<?php echo $item->title;?>
            	<br><a href="<?php echo $item->click_url; ?>" target="_blank">推广链接</a>
			</td>
            <td><?php echo $item->nick;?></td>
            <td><?php echo $item->price;?>元</td>
            <td><?php echo round(($item->commission_rate/100),2); ?>%</td>
            <td><?php echo $item->commission; ?>元</td>
            <td>此商品近期推广量<?php echo $item->commission_num; ?>件<br/>
				此商品近期推广佣金<?php echo $item->commission_volume; ?>元
			</td>
          </tr>
        <?php endforeach;?>
        </tbody>
</table>
<?php echo $pages;?>
</div>
<script type="text/javascript">
$(document).ready(function($) {
	var interval;
	function get_channel_categories() {
		var purl = '<?php echo spUrl('admin','gathering',array('act'=>'fetch_category'));?>';
		$("#ajax_message").html("正在获取淘宝分类，请稍后....");
	    interval = window.setInterval(function(){
			var text = $("#ajax_message").html();
			if (text.length < 32){
				$("#ajax_message").html(text + '.');					
			} else {
				 $("#ajax_message").html("正在获取淘宝分类，请稍后....");		
			}
		}, 200);
		$.ajax({
			type: "get",
			dataType: 'json',
			url: purl
		}).error(function() {
			window.clearInterval(interval);
			alert('获取淘宝分类失败');
		}).success(function(result) {
			window.clearInterval(interval);
			if (result.success === !0) {
				var lis = $(Mustache.render('<option value="0">所有分类</option>{{#data}}<option value="{{cid}}">{{name}}</option>{{/data}}', result));
				$('#channel_category_id').html(lis);
				 $("#ajax_message").html("分类获取成功，赶快发布吧。");		
			} else {
				$('#channel_category_id').html('');
				 $("#ajax_message").html("分类获取失败，请检查您的淘宝KEY以及服务器网络状态。");		
			}
		});
	}
	function getAlbumList(cat_id) {
		var purl = '<?php echo spUrl('album','album_list');?>';
		$.ajax({
			type: "get",
			dataType: 'json',
			data: {'cid':cat_id},
			url: purl
		}).error(function() {
			alert('获取专辑失败');
		}).success(function(result) {
			if (result.success === !0) {
				var lis = $(Mustache.render('{{#data}}<option value="{{album_id}}">{{album_title}}</option>{{/data}}', result));
				$('#album_id').html(lis);
			} else {
				$('#album_id').html('');
			}
		});
	}
	$('#publish_form').validate({
		submitHandler: function(form) {
			var num_iid_str = '';
			$(':checkbox').each(function() {
				var checked = $(this).attr("checked");
				var num_iid = $(this).val();
				if(checked=='checked'){
					num_iid_str += num_iid+',';
				}
	        });
			if(num_iid_str==null||num_iid_str==""){
				alert("您没有内容需要发布，请选择。");
				return false;
			}
	        $("#num_iid_str").val(num_iid_str);
			$('#publish_form').ajaxSubmit({
					url:  '<?php echo spUrl('admin','gathering', array('act'=>'publish'));?>',
					data: $('#publish_form').formSerialize(),
					type: 'POST',
					dataType: 'json',
					beforeSubmit: function(){
					    $("#ajax_message").html("发布中，20件商品一般需要3到5分钟左右，请稍后....");
					    interval = window.setInterval(function(){
							var text = $("#ajax_message").html();
							if (text.length < 55){
								$("#ajax_message").html(text + '.');					
							} else {
								 $("#ajax_message").html("发布中，20件商品一般需要3到5分钟左右，请稍后....");		
							}
						}, 200);
					},
					success: function(result) {
						 window.clearInterval(interval);
						 if (result.success === !0) {
							 $("#ajax_message").html("恭喜你，发布成功");
						 }else {
							 $("#ajax_message").html("发布失败....");
						 }
					}
			});
			return false;
		}
	});
	$("#select_all").click(function(){
		$(":checkbox").attr("checked","checked");
	});
	
	$('#category_id').change(function (){
		getAlbumList(this.value);
	});
	getAlbumList($('#category_id').val());
	get_channel_categories();
});
</script>
</body>
</html>