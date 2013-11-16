<script type="text/template" id="crop_dialog_tpl" data-type="home" data-crop-url="" data-style="" data-sid="0" data-cid="" data-uid="" data-imgpath="" data-position="1" data-width="0" data-height="0" data-title="<?php echo T('start_crop_image');?>">
    <div id="crop_image_div" style="text-align: center;">
    	<img id="crop_image_{{sid}}" src="{{path}}" style="max-width: 100%;max-height: 500px;"/>
    </div>
    <div style="text-align: center;height: 30px;line-height: 30px;">
       <button data-action="cropBtn"><?php echo T('submit');?></button>
    </div>
</script>