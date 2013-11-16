<?php echo $setting_header;?>
<div class="tablebox">
   	<h5><?php echo T('homepage_ad');?></h5>
   <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th><?php echo T('th_title');?></th>
            <th><?php echo T('th_type');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($homepage_ads as $u):?>
          <tr>
            <td style="word-wrap:break-word;width:50px;"><?php echo $u['key'];?></td>
            <td style="word-wrap:break-word;width:100px;"><?php echo $u['ad_name'];?></td>
            <td style="word-wrap:break-word;width:100px;">
            <?php echo T('homepage_ad').' ';?>
            <?php echo $u['width'];?>X
            <?php echo $u['height'];?>PX
            </td>
            <td style="word-wrap:break-word;width:80px;">
            	<a href="<?php echo spUrl('admin','ads_manage',array('act'=>'delete','key'=>$u['key'],'ad_position'=>'homepage_ad'));?>"><?php echo T('delete');?></a>
           		<a href="<?php echo spUrl('admin','ads_manage',array('act'=>'edit','key'=>$u['key'],'ad_position'=>'homepage_ad'));?>"><?php echo T('edit');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    
   <h5><?php echo T('pinpage_ad');?></h5>
   <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th><?php echo T('th_title');?></th>
            <th><?php echo T('th_type');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($pinpage_ads as $u):?>
          <tr>
            <td style="word-wrap:break-word;width:50px;"><?php echo $u['key'];?></td>
            <td style="word-wrap:break-word;width:100px;"><?php echo $u['ad_name'];?></td>
            <td style="word-wrap:break-word;width:100px;">
            <?php echo T('pinpage_ad').' ';?>
            <?php echo $u['width'];?>X
            <?php echo $u['height'];?>PX
            </td>
            <td style="word-wrap:break-word;width:80px;">
            	<a href="<?php echo spUrl('admin','ads_manage',array('act'=>'delete','key'=>$u['key'],'ad_position'=>'pinpage_ad'));?>"><?php echo T('delete');?></a>
           		<a href="<?php echo spUrl('admin','ads_manage',array('act'=>'edit','key'=>$u['key'],'ad_position'=>'pinpage_ad'));?>"><?php echo T('edit');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    
   <h5><?php echo T('detailpage_ad');?></h5>
   <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th><?php echo T('th_title');?></th>
            <th><?php echo T('th_type');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($detailpage_ads as $u):?>
          <tr>
            <td style="word-wrap:break-word;width:50px;"><?php echo $u['key'];?></td>
            <td style="word-wrap:break-word;width:100px;"><?php echo $u['ad_name'];?></td>
            <td style="word-wrap:break-word;width:100px;">
            <?php echo T('detailpage_ad').' ';?>
            <?php echo $u['width'];?>X
            <?php echo $u['height'];?>PX
            </td>
            <td style="word-wrap:break-word;width:80px;">
            	<a href="<?php echo spUrl('admin','ads_manage',array('act'=>'delete','key'=>$u['key'],'ad_position'=>'detailpage_ad'));?>"><?php echo T('delete');?></a>
           		<a href="<?php echo spUrl('admin','ads_manage',array('act'=>'edit','key'=>$u['key'],'ad_position'=>'detailpage_ad'));?>"><?php echo T('edit');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','ads_manage',array('act'=>'add'));?>" method="post" class="form-horizontal">
   <fieldset>
   <div class="control-group">
      <label class="control-label" for="ad_name"><?php echo T('th_title');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="ad_name" name="ad_name" />
      </div>
    </div>
     <div class="control-group">
		<label class="control-label" for="ad_position"><?php echo T('th_type');?></label>
		<div class="controls">
			<select id="ad_position" name="ad_position">
			    <?php if($positions):?>
			    <?php foreach ($positions as $position):?>
			    <option value="<?php echo $position;?>"><?php echo T($position);?></option>
			    <?php endforeach;?>
			    <?php endif;?>
			</select>
		</div>
	</div>
	<div class="control-group">
            <label class="control-label"><?php echo T('width').','.T('height');?></label>
            <div class="controls">
               <?php echo T('width');?><input type="text" class="input-small" id="width" name="width"> Px 
               <?php echo T('height');?><input type="text" class="input-small" id="height" name="height"> Px
            </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="ad_source"><?php echo T('th_content');?></label>
      <div class="controls">
        <textarea class="input-xlarge" id="ad_source" name="ad_source" rows="3"></textarea>
        <p class="help-block">HTML is ok here.</p>
      </div>
    </div>
    <div class="form-actions">
  <button type="submit" class="btn"><?php echo T('save');?></button>
  </div>
  </fieldset>
</form>
</div>
</body>
</html>