	<div class="pin_form" id="edit_album_tpl" data-title="<?php echo T('edit').T('album');?>" data-width="400" data-css-class="dialog_upload">
		<div style="height: 100px;">
				<div class="btn_select mt10 category_select_list first_div" data-init="0" data-find-album="0">
					<a href="javascript:void(0);" class="listbtn" data-action="categoryItemPopup" data-params="edit_album_tpl"><span class="label"><?php echo T('category');?>：</span><span class="category_select_title"><?php echo T('loading');?></span><s><b></b></s></a>
               		<ul>
                	</ul>
                	<input name="category_id" type="hidden" class="category_select_id">
            	</div>
            	<div>
       				<input type="text" name="album_title" class="album_title" title="<?php echo T('album_name');?>" value="{{album_title}}"/>
            	</div>
				<div id="ajax_share_message" class="error"></div>
				<div class="mt10 text_c">
					<button data-action="editAlbumSave" data-params="{{album_id}},edit_album_tpl"><?php echo T('submit');?></button>
				</div>
		</div>
    </div>
