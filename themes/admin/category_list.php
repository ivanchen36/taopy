<?php echo $setting_header;?>
<div class="tablebox">
   <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th><?php echo T('category_name_cn');?></th>
            <th><?php echo T('category_name_en');?></th>
            <th><?php echo T('category_hot_words');?></th>
            <th><?php echo T('displayorder');?></th>
            <th><?php echo T('category_is_open');?></th>
            <th><?php echo T('category_is_home');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $item):?>
          <tr>
            <td><?php echo $item['category_id'];?></td>
            <td><?php echo $item['category_name_cn'];?></td>
            <td><?php echo $item['category_name_en'];?></td>
            <td style="word-wrap:break-word;width: 400px;"><?php echo $item['category_hot_words'];?></td>
            <td><?php echo $item['display_order'];?></td>
            <td><?php echo $item['is_open']?T('yes'):T('no');?></td>
            <td><?php echo $item['is_home']?T('yes'):T('no');?></td>
            <td>
            	<?php if (!$item['is_system']):?>
            <a href="<?php echo spUrl('admin','category_list',array('act'=>'delete','catid'=>$item['category_id']));?>"><?php echo T('delete');?></a>
            <?php endif;?>
           		<a href="<?php echo spUrl('admin','category_list',array('act'=>'edit','catid'=>$item['category_id']));?>"><?php echo T('edit');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>

</div>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','category_list',array('act'=>'add'));?>" method="post" class="form-horizontal">
   <fieldset>
   
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category_name_cn');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="category_name_cn" name="category_name_cn" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category_name_en');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="category_name_en" name="category_name_en" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category_hot_words');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="category_hot_words" name="category_hot_words"/>
        <p class="help-block"><?php echo T('category_hot_words_tip');?></p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="display_order" name="display_order" />
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