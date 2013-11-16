<?php echo $setting_header;?>
<div class="tablebox_header">   
<form action="<?php echo spUrl('admin','item_list', array('act'=>'search'));?>" method="get" class=" form-search pull-right">
 		<select id="category_id" name="category_id">
			            <?php if($categories):?>
			            <?php foreach ($categories as $category):?>
			              <option value="<?php echo $category['category_id'];?>"><?php echo $category['category_name_cn'];?></option>
			            <?php endforeach;?>
			            <?php endif;?>
		</select>
        <input type="text" name="keyword" class="input-medium search-query">
        <button type="submit" class="btn"><?php echo T('search');?></button>
</form>
<div class="pull-left">
<a href="<?php echo spUrl('admin','item_list', array('act'=>'search','is_show'=>'0'));?>"><?php echo T('not_verify');?></a>  
<a href="<?php echo spUrl('admin','item_list', array('act'=>'search','is_show'=>'1'));?>"><?php echo T('already_verify');?></a>  
</div>
</div>
<div class="tablebox">
   <table class="table table-striped">
        <thead>
          <tr>
            <th width="30">ID</th>
            <th width="50"><?php echo T('thumb');?></th>
            <th width="50"><?php echo T('poster');?></th>
            <th width="250"><?php echo T('description');?></th>
            <th width="50"><?php echo T('th_category');?></th>
            <th width="50"><?php echo T('price');?></th>
            <th width="90"><?php echo T('create_time');?></th>
            <th width="50"><?php echo T('th_status');?></th>
            <th width="60"><?php echo T('link_url');?></th>
            <th><?php echo T('edit');?></th>
            <th><?php echo T('delete');?></th>
            <th><?php echo T('verify');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item):?>
          <tr>
            <td><?php echo $item['item_id'];?></td>
            <td><img src="<?php echo base_url().$item['image_path'].'_square.jpg';?>" width="50"/></td>
            <td style="word-wrap:break-word;width: 50px;"><?php echo $item['user_nickname'];?></td>
            <td style="word-wrap:break-word;width: 200px;"><?php echo $item['intro'];?></td>
            <td><?php echo $item['category_name_cn'];?></td>
            <td><?php echo $item['price'];?></td>
            <td><?php echo friendlyDate($item['create_time']);?></td>
            <td>
            <?php if($item['is_show']):?>
            	<?php echo T('already_verify');?>
            <?php else:?>
            	<?php echo T('not_verify');?>
            <?php endif;?>
            </td>
            <td><a href="<?php echo spUrl("detail","index", array("share_id"=> $item['share_id']));?>" target="_blank"><?php echo T('view');?></a></td>
            <td>
            <a href="<?php echo spUrl('admin','item_list', array('act'=>'edit','item_id'=>$item['item_id']));?>"><?php echo T('edit');?></a>
            </td>
            <td>
            <a href="<?php echo spUrl('admin','item_list', array('act'=>'delete','item_id'=>$item['item_id']));?>"><?php echo T('delete');?></a>
            </td>
            <td>
            <?php if($item['is_show']):?>
            <a href="<?php echo spUrl('admin','item_list', array('act'=>'deverify','item_id'=>$item['item_id']));?>"><?php echo T('already_verify');?></a>
            <?php else:?>
            <a href="<?php echo spUrl('admin','item_list', array('act'=>'verify','item_id'=>$item['item_id']));?>"><?php echo T('filter');?></a>
            <?php endif;?>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
<?php echo $pages;?>
</div>

</body>
</html>