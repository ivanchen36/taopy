<?php echo $setting_header;?>
<div class="formmain">
<?php echo $setting_nav;?>
	<div class="formbox">
		<form
			action="<?php echo spUrl('admin','setting_api',array('act'=>'save'));?>"
			method="post" class="form-horizontal"
			style="padding: 0 20px 0px 20px;">
			<fieldset>
			<?php foreach ($vendors as $vendor):$lowerkey = strtolower($vendor);?>
				<legend>
				<?php echo T($vendor.'_tip');?>
				</legend>
				<div class="control-group">
		            <label class="control-label" for="<?php echo $lowerkey.'_open';?>"><?php echo T('open');?></label>
		            <div class="controls">
		              <input type="radio" name="<?php echo $lowerkey.'_open';?>" class="input-xlarge" value="1" <?php echo $api[$vendor]['OPEN']?'checked':'';?>><?php echo T('yes');?></input>
		              <input type="radio" name="<?php echo $lowerkey.'_open';?>" class="input-xlarge" value="0" <?php echo !$api[$vendor]['OPEN']?'checked':'';?>><?php echo T('no');?></input>
		            </div>
		          </div>
				<div class="control-group">
					<label class="control-label" for="<?php echo $lowerkey;?>_appkey">APP
						Key</label>
					<div class="controls">
						<input type="text" class="input-xlarge"
							id="<?php echo $lowerkey;?>_appkey"
							name="<?php echo $lowerkey;?>_appkey"
							value="<?php echo $api[$vendor]['APPKEY'];?>">
						<p class="help-block"><?php echo T($vendor.'_api_apply_address');?></p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"
						for="<?php echo $lowerkey;?>_appsecret">App Secret</label>
					<div class="controls">
						<input type="text" class="input-xlarge"
							id="<?php echo $lowerkey;?>_appsecret"
							name="<?php echo $lowerkey;?>_appsecret"
							value="<?php echo $api[$vendor]['APPSECRET'];?>">
					</div>
				</div>
				<?php if($vendor=='Taobao'):?>
				<div class="control-group">
					<label class="control-label" for="<?php echo $lowerkey;?>_callback">App
						Callback</label>
					<div class="controls">
						<input type="text" class="input-xlarge"
							id="<?php echo $lowerkey;?>_callback"
							name="<?php echo $lowerkey;?>_callback"
							value="<?php echo $api['Taobao']['CALLBACK']?$api['Taobao']['CALLBACK']:base_url().'index.php?c=callback_taobao';?>">
						<p class="help-block">
							<?php echo T('api_callback_tip').base_url().'index.php?c=callback_taobao';?>
						</p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="<?php echo $lowerkey;?>_pid">淘宝客PID</label>
					<div class="controls">
						<input type="text" class="input-xlarge"
							id="<?php echo $lowerkey;?>_pid"
							name="<?php echo $lowerkey;?>_pid"
							value="<?php echo $api[$vendor]['PID'];?>">
						<p class="help-block">请只填写PID中的数字部份：如 mm_29948364_0_0
							只需要填写：29948364</p>
						<p class="help-block">PID申请地址：http://www.alimama.com</p>
					</div>
				</div>
				<?php else:?>
				<div class="control-group">
					<label class="control-label" for="<?php echo $lowerkey;?>_callback">App Callback</label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="<?php echo $lowerkey;?>_callback"
							name="<?php echo $lowerkey;?>_callback"
							value="<?php echo $api[$vendor]['CALLBACK']?$api[$vendor]['CALLBACK']:$api_callback.$vendor;?>">
						<p class="help-block">
							<?php echo T('api_callback_tip').$api_callback.$vendor;?>
						</p>
					</div>
				</div>
				<?php endif;?>
				<?php endforeach;?>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary">确定</button>
				</div>
			</fieldset>
		</form>
	</div>
</div>
</div>
</body>
</html>
