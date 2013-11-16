<?php echo $tpl_header; ?>
<a href="javascript:void(0);" id="login_click" data-action="openLoginDialog" style="display: none;">点击登录</a>
<script type="text/javascript"> 
$(document).ready(function($) {
	 $("#login_click").click(); 
});
 </script> 

<?php echo $tpl_footer; ?>
