<?php echo $setting_header;?>
<style type="text/css">
div.button {
	height: 25px;
	width: 100px;
	background-color: #000000;
	font-size: 14px;
	color: #ffffff;
	text-align: center;
	line-height: 20px;
}
div.button.hover {
	background-color: darkgray;
	color: #ffffff;
}
</style>
<div class="tablebox">
   <table class="table table-striped">
        <thead>
          <tr>
            <th width="160">pic</th>
            <th width="200"><?php echo T('image_desc');?></th>
            <th width="80"><?php echo T('link_url');?></th>
            <th width="80"><?php echo T('displayorder');?></th>
            <th width="100"><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($slides as $item):?>
          <tr>
            <td><a href="<?php echo $item['link_url'];?>"><img src="<?php echo base_url($item['image_url']);?>" width="150" height="55"/></a></td>
            <td style="word-wrap:break-word;width: 200px;"><?php echo $item['desc'];?></td>
            <td><a href="<?php echo $item['link_url'];?>" target="_blank"><?php echo T('link_url');?></a></td>
            <td><?php echo $item['order'];?></td>
            <td>
            	<a href="<?php echo spUrl('admin','homepage_slide',array('act'=>'delete','key'=>$item['key']));?>"><?php echo T('delete');?></a>
           		<a href="<?php echo spUrl('admin','homepage_slide',array('act'=>'edit','key'=>$item['key']));?>"><?php echo T('edit');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>

</div>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','homepage_slide',array('act'=>'add'));?>" method="post" class="form-horizontal">
   <fieldset>
   <div class="control-group">
      <label class="control-label" for="input01"></label>
      <div class="controls" id="imgview">
      </div>
    	<input type="hidden" id="filename" name="filename"/>
   </div>
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('select_file');?></label>
      <div class="controls" id='fileupload_div'>
			<div id="button1" class="button"><?php echo T('upload_pic');?></div>
			<p class="help-block">640X280(px)</p>
      </div>
    </div>
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('image_desc');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="desc" name="desc" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('link_url');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="link_url" name="link_url" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="order" name="order" />
      </div>
    </div>
   
    <div class="form-actions">
  <button type="submit" class="btn"><?php echo T('save');?></button>
  </div>
  </fieldset>
</form>
</div>
<script  type="text/javascript">
$(document).ready(function(){
	var button = $('#button1'), interval;
	
	new AjaxUpload(button, {
		action: '<?php echo spUrl('admin','homepage_slide', array('act'=>'upload'));?>', 
		name: 'qqfile',
		responseType: 'json',
		onSubmit : function(file, ext){
			// change button text, when user selects file			
			button.text('Uploading');
							
			// If you want to allow uploading only 1 file at time,
			// you can disable upload button
			this.disable();
			
			// Uploding -> Uploading. -> Uploading...
			interval = window.setInterval(function(){
				var text = button.text();
				if (text.length < 13){
					button.text(text + '.');					
				} else {
					button.text('Uploading');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button.text('<?php echo T('upload_pic');?>');
						
			window.clearInterval(interval);
						
			// enable upload button
			this.enable();

			if($.trim(response.success) == "false"){
				alert(response.message);
				return;
			}
			var filename = response.data.filename+"."+response.data.ext;
			var filepath = "<?php echo base_url().'data/attachments/homeslide/';?>"+filename;
			$('#imgview').html('<img src="'+filepath+'" height="200"/>');
			$('#filename').val(filename);
		}
	});
});
</script>
</body>
</html>