<?php echo $setting_header;?>
<div class="tablebox">
   <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th><?php echo T('category');?></th>
            <th><?php echo T('tag_group_name_cn');?></th>
            <th><?php echo T('tag_group_name_en');?></th>
            <th><?php echo T('tags_content');?></th>
            <th><?php echo T('displayorder');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($tags as $item):?>
          <tr>
            <td><?php echo $item['tag_id'];?></td>
            <td><?php echo $item['category']['category_name_cn'];?></td>
            <td><?php echo $item['tag_group_name_cn'];?></td>
            <td><?php echo $item['tag_group_name_en'];?></td>
            <td style="word-wrap:break-word;width: 400px;"><?php echo $item['tags'];?></td>
            <td><?php echo $item['display_order'];?></td>
            <td><a href="<?php echo spUrl('admin','tag_list',array('act'=>'delete','tagid'=>$item['tag_id']));?>"><?php echo T('delete');?></a>
           		<a href="<?php echo spUrl('admin','tag_list',array('act'=>'edit','tagid'=>$item['tag_id']));?>"><?php echo T('edit');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>

</div>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','tag_list',array('act'=>'add'));?>" method="post" class="form-horizontal">
   <fieldset>
   
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('tag_group_name_cn');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="tag_group_name_cn" name="tag_group_name_cn" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('tag_group_name_en');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="tag_group_name_en" name="tag_group_name_en" />
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('category');?></label>
      <div class="controls">
        <select id="category_id" name="category_id">
            <?php if($categories):?>
            <?php foreach ($categories as $category):?>
              <option value="<?php echo $category['category_id'];?>"><?php echo $category['category_name_cn'];?></option>
            <?php endforeach;?>
            <?php endif;?>
            </select>
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('tags_content');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="tags" name="tags" />(<?php echo T('tags_content_tip');?>)
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="display_order" name="display_order" value="100"/>
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