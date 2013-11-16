<?php echo $setting_header;?>
<div class="tablebox">
   <table class="table table-striped">
        <thead>
          <tr>
            <th>smile ID</th>
            <th><?php echo T('displayorder');?></th>
            <th><?php echo T('picture');?></th>
            <th><?php echo T('smile_code');?></th>
            <th>DIR</th>
            <th><?php echo T('file_name');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($smiles as $smile):?>
          <tr>
            <td><?php echo $smile['smile_id'];?></td>
            <td><?php echo $smile['displayorder'];?></td>
            <td><?php echo '<img src="'.base_url().'assets/img/smiles/default/'.$smile['url'].'" border="0" alt=""  onerror="this.src=\''.base_url().'assets/img/blank.png\'"/>';?></td>
            <td><?php echo $smile['code'];?></td>
            <td>./assets/img/smiles/default</td>
            <td><?php echo $smile['url'];?></td>
            <td>
            	<a href="<?php echo spUrl('admin','smile_list',array('act'=>'delete','smile_id'=>$smile['smile_id']));?>"><?php echo T('delete');?></a>
           		<a href="<?php echo spUrl('admin','smile_list',array('act'=>'edit','smile_id'=>$smile['smile_id']));?>"><?php echo T('edit');?></a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>

</div>

<div class="tablebox_footer">
   <form action="<?php echo spUrl('admin','smile_list',array('act'=>'add'));?>" method="post" class="form-horizontal">
   <fieldset>
   <div class="control-group">
      <label class="control-label" for="code"><?php echo T('smile_code');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="code" name="code" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="url"><?php echo T('file_name');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="url" name="url" />
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="displayorder"><?php echo T('displayorder');?></label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="displayorder" name="displayorder" />
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