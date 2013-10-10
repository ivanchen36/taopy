<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<script>
var base_url = '<?php echo base_url();?>';
</script>
<script src="<?php echo base_url();?>themes/admin/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>themes/admin/js/jquery.zrssfeed.min.js" type="text/javascript"></script>
<link href="<?php echo base_url('themes/admin/css/bootstrap.min.css'); ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url('themes/admin/css/admin.css'); ?>" type="text/css" rel="stylesheet" />
<title><?php echo T('admin_dashboard')?></title>
<script type="text/javascript">
$(document).ready(function () {
	$('#rssOutput').rssfeed('http://www.onightjar.com/topics/category/news/feed', {
		header:false,
		limit: 5,
		linktarget:'_blank',
		errormsg:'no news'
	});
});
</script>

<script type="text/javascript">
$.ajax({
	type: "post",
	url: 'http://www.onightjar.com/index.php?c=pzupgrade&a=tkneedupdate&pzrelease=<?php echo $release;?>&pzversion=<?php echo $version;?>',
	dataType: 'json'
}).error(function() {
}).success(function(result) {
	if (result.success ===!0) {
		$('#checknewversion').html(result.message);
		$('#checknewversion').addClass('label label-important');
	} else {
		$('#checknewversion').html(result.message);	
		$('#checknewversion').addClass('label');
	}
});

</script>
</head>

<body>
<div class="sitebox">
<div class="siteinfo"><h3><?php echo T('site_info')?></h3>
<div class="sitecontent">
<?php echo T('product_version')?>： <?php echo $version.', Release '.$release;?>  <a href="<?php echo spUrl('admin','system_update');?>" id="checknewversion">[<?php echo T('check_new')?>]</a><br />
<?php echo T('server_info')?>：<?php echo $_SERVER['SERVER_SOFTWARE']?> <br />
</div>
</div>
<div class="siteinfo"><h3><?php echo T('team_info')?></h3>
<div class="sitecontent"><?php echo T('copy_right')?>：oNightJar.com<br />
<?php echo T('support_team')?>：oNightJar Studio (夜鹰工作室)<br />
<?php echo T('offical_site')?>：<a href="http://www.onightjar.com">http://www.onightjar.com</a> <br />
<?php echo T('offical_forum')?>：<a href="http://bbs.onightjar.com">http://bbs.onightjar.com</a>
</div>
</div>
</div>
<div class="sitenew">
<h3><?php echo T('offical_news')?></h3>
<div id="rssOutput" style="padding:5px 10px;"></div>
</div>
</div>
</body>
</html>
