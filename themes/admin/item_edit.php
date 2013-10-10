<?php echo $setting_header;?>
	<div class="tablebox_footer">
	<?php if($message):?>
	<div class="alert alert-danger">
	    <strong><?php echo T('error');?></strong> $message
	</div>
	<?php endif;?>
		<form id="save_share_form" 
		action="<?php echo spUrl('admin','item_list', array('act'=>'edit_save','item_id'=>$item['item_id']));?>" method="post"
			class="form-horizontal">
			<fieldset>

				<div class="control-group">
					<label class="control-label" for="input01"></label>
					<div class="controls" id="imgview"><img src="<?php echo base_url().$item['image_path'].'_middle.jpg';?>"/></div>
					<input type="hidden" id="filename" name="filename" value="<?php echo base_url().$item['image_path'].'_middle.jpg';?>"/>
				</div>
				<div class="control-group">
					<label class="control-label" for="input01"><?php echo T('orgin_url');?></label>
					<div class="controls">
						<input type="text" class="input-xlarge" name="orgin_url" id="orgin_url" value="<?php echo $item['orgin_url'];?>"/>
						 <p class="help-block"><?php echo T('orgin_url');?></p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input01"><?php echo T('share_price');?></label>
					<div class="controls">
						<input type="text" class="input-xlarge" name="price" id="price" value="<?php echo $item['price'];?>"/>
						 <p class="help-block"><?php echo T('error');?><?php echo T('share_price');?></p>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="input01"><?php echo T('promotion_url');?></label>
					<div class="controls">
       					<input type="text" class="input-xlarge" name="promotion_url" id="promotion_url" value="<?php echo $item['promotion_url'];?>"/>
						 <p class="help-block"><?php echo T('promotion_url');?></p>
					</div>
				</div>
				
				<div class="control-group">
			      <label class="control-label" for="input01"><?php echo T('category');?></label>
			      <div class="controls">
			        <select id="category_id" name="category_id">
			            <?php if($categories):?>
			            <?php foreach ($categories as $category):?>
			              <option value="<?php echo $category['category_id'];?>" <?php echo ($category['category_id']==$share['category_id'])?'selected':'';?>><?php echo $category['category_name_cn'];?></option>
			            <?php endforeach;?>
			            <?php endif;?>
			            </select>
			      </div>
			    </div>
				
				<div class="control-group">
					<label class="control-label" for="input01"><?php echo T('share_title');?></label>
					<div class="controls">
       					<input type="text" class="input-xlarge" name="title" id="title" value="<?php echo $item['title'];?>"/>
						 <p class="help-block"><?php echo T('share_title');?></p>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="input01"><?php echo T('share_desc');?></label>
					<div class="controls">
						<textarea rows="5" cols="50" class="input-xlarge" id="intro"
							name="intro"><?php echo $item['intro'];?></textarea>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn"><?php echo T('save');?></button>
					<div id="ajax_share_message"></div>
				</div>
			</fieldset>
		</form>
	</div>
</body>
</html>
