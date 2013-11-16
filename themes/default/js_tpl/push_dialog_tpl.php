<script type="text/template" id="push_dialog_tpl" data-style="home_1" data-title="<?php echo T('push_to_home_index');?>" data-crop-url="<?php echo spUrl('share','crop_category_hot_share');?>" data-fetch-url="<?php echo spUrl('share','find_category_hot_share');?>">
 {{#data}} 
	<div class="category-bd">
		<a href="javascript:void(0);" id="link_home_1" data-action="switchPushStyle" data-params="home_1" class="selected"><?php echo T('style_1');?></a> | 
		<a href="javascript:void(0);" id="link_home_2" data-action="switchPushStyle" data-params="home_2"><?php echo T('style_2');?></a>
	</div>
    <div class="category-bd push_style" id="push_home_1">
    	<div class="category-hot-200-300 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="1,200:300">{{#s1.image_path}}<img src="<?php echo base_url()?>{{s1.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s1.image_path}}{{^s1.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 120px;">{{/s1.image_path}}</a></div>
		<div class="category-hot-170-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="2,175:145">{{#s2.image_path}}<img src="<?php echo base_url()?>{{s2.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s2.image_path}}{{^s2.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s2.image_path}}</a></div>
    	<div class="category-hot-170-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="3,175:145">{{#s3.image_path}}<img src="<?php echo base_url()?>{{s3.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s3.image_path}}{{^s3.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s3.image_path}}</a></div>
    	<div class="category-hot-350-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="4,350:145">{{#s4.image_path}}<img src="<?php echo base_url()?>{{s4.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s4.image_path}}{{^s4.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s4.image_path}}</a></div>
    	<div class="category-hot-350-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="5,350:145">{{#s5.image_path}}<img src="<?php echo base_url()?>{{s5.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s5.image_path}}{{^s5.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s5.image_path}}</a></div>
    	<div class="category-hot-170-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="6,175:145">{{#s6.image_path}}<img src="<?php echo base_url()?>{{s6.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s6.image_path}}{{^s6.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s6.image_path}}</a></div>
    	<div class="category-hot-170-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="7,175:145">{{#s7.image_path}}<img src="<?php echo base_url()?>{{s7.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s7.image_path}}{{^s7.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s7.image_path}}</a></div>
	</div>
    <div class="category-bd push_style hide" id="push_home_2">
    	<div class="category-hot-180-300 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="1,180:300">{{#s1.image_path}}<img src="<?php echo base_url()?>{{s1.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s1.image_path}}{{^s1.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 120px;">{{/s1.image_path}}</a></div>
		<div class="category-hot-180-300 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="2,180:300">{{#s2.image_path}}<img src="<?php echo base_url()?>{{s2.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s2.image_path}}{{^s2.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 120px;">{{/s2.image_path}}</a></div>
    	<div class="category-hot-180-300 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="3,180:300">{{#s3.image_path}}<img src="<?php echo base_url()?>{{s3.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s3.image_path}}{{^s3.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 120px;">{{/s3.image_path}}</a></div>
    	<div class="category-hot-350-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="4,350:145">{{#s4.image_path}}<img src="<?php echo base_url()?>{{s4.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s4.image_path}}{{^s4.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s4.image_path}}</a></div>
    	<div class="category-hot-350-145 text_c"><a href="javascript:void(0);" data-action="openCrop" data-params="5,350:145">{{#s5.image_path}}<img src="<?php echo base_url()?>{{s5.image_path}}_home.jpg?{{rand}}" onerror="this.src='<?php echo base_url()?>assets/img/pin_button.png';"/>{{/s5.image_path}}{{^s5.image_path}}<img src="<?php echo base_url()?>assets/img/pin_button.png" style="margin-top: 50px;">{{/s5.image_path}}</a></div>
	</div>
    <br/>
 {{/data}} 
</script>