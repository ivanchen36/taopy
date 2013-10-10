<?php echo $setting_header;?>
<div class="tablebox_header">   
<form action="<?php echo spUrl('admin','user_list', array('act'=>'search'));?>" method="get" class=" form-search pull-right">
        <input type="text" name="keyword" class="input-medium search-query">
        <button type="submit" class="btn"><?php echo T('search');?></button>
</form>
<div class="pull-left"><a href="<?php echo spUrl('admin','user_list');?>">ALL</a>  
<a href="<?php echo spUrl('admin','user_list', array('act'=>'search','user_type'=>'1'));?>"><?php echo T('member');?></a>  
<a href="<?php echo spUrl('admin','user_list', array('act'=>'search','user_type'=>'3'));?>"><?php echo T('admin');?></a>  
<a href="<?php echo spUrl('admin','user_list', array('act'=>'search','user_type'=>'2'));?>"><?php echo T('editer');?></a> 
<a href="<?php echo spUrl('admin','user_list', array('act'=>'search','user_type'=>'0'));?>"><?php echo T('banned_visit');?></a>  
</div>
</div>
<div class="tablebox">
   <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th><?php echo T('email');?></th>
            <th><?php echo T('th_username');?></th>
            <th><?php echo T('th_user_title');?></th>
            <th><?php echo T('th_user_desc');?></th>
            <th><?php echo T('th_type');?></th>
            <th><?php echo T('th_operation');?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u):?>
          <tr>
            <td><?php echo $u['user_id'];?></td>
            <td style="word-wrap:break-word;width:100px;"><?php echo $u['email'];?></td>
            <td width="100"><?php echo $u['nickname'];?></td>
            <td width="150"><?php echo $u['user_title'];?></td>
            <td style="word-wrap:break-word;width:400px;"><?php echo $u['bio'];?></td>
            <td><?php 
            switch ($u['user_type']) {
            	case 0:
            	echo T('banned_visit');
            	break;
            	case 1:
            	echo T('member');
            	break;
            	case 2:
            	echo T('editer');
            	break;
            	case 3:
            	echo T('admin');
            	break;
            }
            ?></td>
            <td><a href="<?php echo spUrl('admin','user_list',array('act'=>'edit','uid'=>$u['user_id']));?>"><?php echo T('edit');?></a></td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      
<?php echo $pages;?>
</div>

</body>
</html>