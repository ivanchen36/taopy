<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="<?php echo base_url('themes/admin/css/bootstrap.min.css'); ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url('themes/admin/css/admin.css'); ?>" type="text/css" rel="stylesheet" />
<script>
var base_url = '<?php echo base_url();?>';
</script>
<script src="<?php echo base_url();?>themes/admin/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<title>System Update</title>
</head>


<body>
<div class="tablebox">
   <table class="table table-striped">
        <thead>
          <tr>
            <th><?php echo T('product_version');?></th>
            <th><?php echo T('release');?></th>
            <th><?php echo T('create_time');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($releases['data'] as $sm):?>
          <tr>
            <td><?php echo $sm['pzversion'];?></td>
            <td><?php echo $sm['pzrelease'];?></td>
            <td><?php echo friendlyDate($sm['date']);?></td>
            <td><a href="<?php echo $sm['link'];?>" target="_blank"><?php echo T('download');?></a> 
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
   </table>

</div>
</body>
</html>