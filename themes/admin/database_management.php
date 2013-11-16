<?php echo $setting_header;?>
<div class="tablebox_header">   
<form action="<?php echo spUrl('admin','database_backup',array('act'=>'outall'));?>" method="post" class=" form-search pull-right">
        <button type="submit" class="btn"><?php echo T('database_export');?></button>
</form>
</div>
<div class="tablebox">

<form id="form1" name="form1" method="post" action="">
    <table class="table table-striped table-bordered table-condensed">
    <thead>
          <tr>
            <th><?php echo T('database_table');?></th>
            <th><?php echo T('database_engine');?></th>
            <th><?php echo T('database_charset');?></th>
            <th><?php echo T('database_memory');?></th>
            <th><?php echo T('database_records');?></th>
            <th><?php echo T('create_time');?></th>
            <th><?php echo T('update_time');?></th>
            <th><?php echo T('check_time');?></th>
            <th><?php echo T('database_scrap');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
    </thead>
    <tbody>
  <?php foreach ($table['rs'] as $d):?>
    <tr>
      <td><?php echo $d['TABLE_NAME'];?><?php if($d['TABLE_NAME'] != '') echo $d['TABLE_COMMENT'];?></td>
      <td><?php echo $d['ENGINE'];?></td>
        <td><?php echo $d['TABLE_COLLATION'];?></td>
        
         <td><?php echo $d['DATA_LENGTH'];?></td>
         <td><?php echo $d['TABLE_ROWS'];?></td>
        
        <td><?php echo $d['CREATE_TIME'];?></td>
        <td><?php echo $d['UPDATE_TIME'];?></td>
        <td><?php echo $d['CHECK_TIME'];?></td>
        <td><?php echo $d['DATA_FREE'];?></td>
        <td align="right">
      <?php if ($d['DATA_FREE']!=0):?>
        <a href="<?php echo spUrl('admin','database_backup',array('act'=>'optimize','table'=>$d['TABLE_NAME']));?>"><?php echo T('database_optimize');?></a>
       <?php endif;?>
       <?php if ($d['CHECK_TABLE']!='OK' && $d['CHECK_TABLE']!='NCHECK'):?>
          <a href="<?php echo spUrl('admin','database_backup',array('act'=>'repair','table'=>$d['TABLE_NAME']));?>"><?php echo T('database_repair');?></a>
       <?php endif;?>
       <?php if ($d['DATA_FREE']==0 || $d['CHECK_TABLE']!='NCHECK'|| $d['CHECK_TABLE']!='OK'):?>
          <a href="<?php echo spUrl('admin','database_backup',array('act'=>'outone','table'=>$d['TABLE_NAME']));?>"><?php echo T('export');?></a>
       <?php endif;?>
        </td>
      </tr>
  
  <?php endforeach;?>
  
   <tr>
      <td><?php echo T('total_tables');?>：<strong><?php echo $table['all_table'];?></strong></td>
      <td></td>
        <td align="right"><?php echo T('database_memory');?>：</td>
        
         <td><?php echo $table['all_byte'];?></td>
         <td> </td>
        <td> </td>
        
      <td> </td>
        <td align="right"><?php echo T('database_scrap');?>:</td>
        <td><?php echo $table['all_free'];?></td>
        <td></td>
      </tr>
      </tbody>
      
    </table>
    </form>

</div>

</body>
</html>