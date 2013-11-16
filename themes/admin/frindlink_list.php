<?php echo $setting_header;?>
<div class="tablebox">
   <table class="table table-striped table-bordered table-condensed">
        <thead>
          <tr>
            <th><?php echo T('site_name');?></th>
            <th><?php echo T('site_link');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($links as $item):?>
          <tr>
            <td><?php echo $item['link_name'];?></td>
            <td><?php echo $item['link_url'];?></td>
            <td>
            	<a href="<?php echo spUrl('admin','frindlink_list',array('act'=>'delete','key'=>$item['key']));?>"><?php echo T('delete');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>

</div>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','frindlink_list',array('act'=>'add'));?>" method="post" class="form-horizontal">
   <fieldset>
   
   <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('site_name');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="link_name" name="link_name" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="input01"><?php echo T('site_link');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="link_url" name="link_url" />
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