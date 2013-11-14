<?php echo $tpl_header; ?>
<div class="clear"></div>
<div class="main">
    <div class="g960 mt20 white_bg">
    	<div class="g960 inside text_c share_header">
             <h5><?php echo $share['title'];?></h5>
    	</div>
    	
    	<div class="g960 inside mt20 mb20">
            <div class="share_detail ml20" id="share_detail">
            	<?php if($share['price']): ?>
            	<div class="share_price"><em><?php echo number_format($share['price'],2);?></em></div>
    			<?php endif;?>
    			
    			<div class="share_pic" id="share_detail_image" href="<?php echo host_url(spUrl("detail","index", array("share_id"=> $share['share_id'])));?>">
                	<?php $images = unserialize($share['images_array']);?>
                	<?php foreach ($images as $image):?>
                		<img src="<?php echo base_url($image['url'].'_large.jpg?'.$hash);?>" orgin_src="<?php echo base_url($image['url'].'_large.jpg');?>">
                		<?php if($image['desc']):?>
                		<div class="share-pic-desc">
							<p><i class="sup-ico"></i><?php echo $image['desc'];?><i class="sub-ico"></i></p>
						</div>
						<?php endif;?>
                	<?php endforeach;?>
                </div>
                
    			<div class="share_txt <?php echo $share['price']?'share_txt_with_price':''; ?>">
                    <p class="share_desc"><?php echo $share_intro;?></p>
                    <a class="buy_link" href="<?php echo $share['promotion_url'];?>" target="_blank"></a>
                </div> 
    			
    			<div class="share_tools mt20">
                    <div class="share_social f_l info">
                    <a href="javascript:void(0);" data-action="addLike" data-params="<?php echo $share['share_id'];?>"><?php echo T('like');?></a>(<?php echo $share['total_likes'];?>) 
                    <a href="javascript:void(0);"><?php echo T('comment');?></a>(<?php echo $share['total_comments'];?>) 
                    </div>
                </div>
                
    			
                <?php if ($pre_share['share_id']):?>
                <a href="<?php echo $pre_share['share_id']?spUrl('detail','index', array('share_id'=>$pre_share['share_id'])):'#';?>" class="steam_prev">prev</a> 
                <?php else:?>
                <a href="#" class="steam_prev none"><?php echo T('no_more_earlier');?></a>
                <?php endif;?>
                        
                        
                <?php if ($next_share['share_id']):?>
                <a href="<?php echo spUrl('detail','index', array('share_id'=>$next_share['share_id']));?>" class="steam_next">next</a>
                <?php else:?>
                <a href="#" class="steam_next none"><?php echo T('already_last');?></a>
                <?php endif;?>
    			
    			<div class="comments mt10 mb20">
                    <b class="arrow"></b>
            		<div class="reply_box">
            			<div class="form_textarea_hd textarea_hd">
							<div class="btn-group" id="detail_smiles_div" data-action="listSmiles" data-params="detail_smiles_div,<?php echo $share['share_id'].'_commentbox';?>">
							<a href="javascript:;" class="smile dropdown-toggle" id="smiles" data-toggle="dropdown">Smilies</a>
							<ul class="dropdown-menu smiles" data-init="0">
					        </ul>
					        </div>
						</div>
                		<textarea id="<?php echo $share['share_id'].'_commentbox';?>" onkeyup="javascript:strLenCalc(this, 'checklen', 140);" class="pl_area answer_text" data-action="<?php echo $current_user?'':'openLoginDialog';?>"></textarea>
                	</div>
                	<div class="clear"></div>
                	<div class="reply_btn_div mt10">
                		<span class="smalltxt"><?php echo T('can_input');?> <strong id="checklen">140</strong> <?php echo T('character');?></span>
						<a href="javascript:void(0);" data-action="addComment" data-params="<?php echo $share['share_id'];?>,comment_detail_tpl,1" class="link_btn f_r"><?php echo T('comment');?></a>
					</div>
               		<div class="comment_list">
                  		<ul class="pl_list" id="<?php echo $share['share_id'].'_comments'?>" delete-url="<?php echo spUrl('share','del_comment'); ?>">
                  		</ul>
                		<div id="pager"></div>
                	</div>
                </div>
				<script type="text/javascript" language="javascript">
			        $(document).ready(function() {
				        $('#detail_smiles_div').click();
		  				<?php  if($share['total_comments']>0):?>
			            var totalPages = Math.ceil(Number(<?php echo $share['total_comments'];?>)/5);
			            var sid = <?php echo $share["share_id"];?>;
			            $("#pager").pager({ pagenumber: 1, pagecount: totalPages, buttonClickCallback: PageClick });
			
			            function PageClick(pageclickednumber) {
			                $("#pager").pager({ pagenumber: pageclickednumber, pagecount: totalPages, buttonClickCallback: PageClick });
							var tpl_e = $('#comment_detail_tpl');
			                $.ajax({
								url : '<?php echo spUrl('detail','get_comments');?>',
								data : {'share_id' : sid,'page' : pageclickednumber},
								type : 'POST',
								dataType : 'json',
								error : function() {
									show_message('error',getTip('error')+': '+getTip('server-error'),false,0);
								},
								success : function(result) {
									if ($.trim(result.success) == "true") {
										var new_comment = $(Mustache.to_html(tpl_e.html(), result));
										$('#' + sid + '_comments').html(new_comment);
										new_comment.fadeIn(50);
										$('#' + sid + '_commentbox').val('');
									} else {
										alert(result.message);
									}
								}
							});
			            }
			            PageClick(1);
						<?php endif;?>
			         });
			    </script>
    		</div>
            <div class="share_side ml20">
             	<div class="side_info" id="<?php echo $share['share_id']?>_image">
		            <a href="javascript:void(0);" class="info_num red" data-action="addLike" data-params="<?php echo $share['share_id'];?>"><i class="icon icon_like"></i><?php echo T('like');?>(<span id="<?php echo $share['share_id']?>_likenum"><em><?php echo $share['total_likes'];?></em></span>)</a>
		            <a href="javascript:void(0);" class="info_num green"><i class="icon icon_click"></i><?php echo T('view');?><em>(<?php echo $share['total_clicks'];?>)</em></a>
		            <a href="javascript:void(0);" data-action="focus" data-params="<?php echo $share['share_id'].'_commentbox';?>" class="info_num"><i class="icon icon_comment"></i><?php echo T('comment');?><em>(<?php echo $share['total_comments'];?>)</em></a>
	            </div>
	            
	            <?php if($share['price']):?>
	            <div class="side_container side_bd mt10">
                <div><h6><?php echo T('buy_here');?> 原价<?php echo T('money_unit');?><?php echo number_format($share['old_price'],2);?></h6></div>
		            <div class="side_content">
		            <p><strong><?php echo sysSubStr($share['title'],25,true);?></strong> <?php echo T('money_unit');?><em><?php echo number_format($share['price'],2);?></em>  <a href="<?php echo $share['promotion_url'];?>" target="_blank"><?php echo T('go_purchase');?></a></p>
		            </div>
	            </div>
	            <?php endif;?>
	            
	            <div class="side_container side_bd mt20">
		            <div class="share_user"> 
                    <div class="share_avatar"><a href="<?php echo spUrl('pub','index',array('uid'=>$share['user_id']));?>"><img src="<?php echo $share['avatar_remote'];?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_large.jpg';"></a></div>
		               <div>
		                  <h4><a href="<?php echo spUrl('pub','index',array('uid'=>$share['user_id']));?>"><?php echo $share['user_nickname'];?></a></h4><span class="f_r"><?php echo $relation;?></span>
		                  <span><?php echo $share['user_title'];?></span>
		                  <p><?php echo sysSubStr($share['bio'],60,true);?></p>
		               </div>
		            </div>
				</div>
				<?php if($album):?>
				<div class="side_container side_bd mt10">
		            <div><h6><?php echo T('album');?></h6></div>
		            <div class="side_content white_bg">
		            	<div class="album g225">
							<div class="album-header">
								<a href="<?php echo spUrl("pin","index", array("aid"=> $album['album_id']));?>"><?php echo $album['album_title'];?></a>
							</div>
							<ul class="image_list">
								<?php $covers = str_to_arr_list($album['album_cover']);$num=0;?>
					        	<?php foreach ($covers as $c):?>
					        	<li><a href="<?php echo spUrl("detail","index", array("share_id"=> $c['share_id']));?>" class="trans07"><img src="<?php echo base_url($c['image_path'].'_square.jpg');?>" /></a></li>
					        	<?php $num++;?>
					        	<?php endforeach;?>
					        	<?php for ($i=$num;$i<9;$i++):?>
					        	<li></li>
					        	<?php endfor;?>
				        	</ul>
							<div class="album-footer">
								<span class="f_l"><?php echo $album['nickname'];?> <?php echo T('share_at');?> <a href="<?php echo spUrl('album','album_index',array('cat'=>$album['category_id']));?>" target="_blank"><?php echo $album['category_name_cn'];?></a></span>
								<span class="f_r"><?php echo $album['total_share'];?></span>
							</div>
						</div>
		            </div>
	            </div>
	            <?php endif;?>
	            <?php if($shares_same_user):?>
				<div class="side_container side_bd mt10">
		            <div><h6><?php echo T('same_from').$share['user_nickname'];?></h6></div>
		            <div class="side_content white_bg">
		            	<div class="album g225" style="height: 200px;">
							<ul class="image_list">
					        	<?php $num=0; foreach ($shares_same_user as $c):?>
					        	<li><a href="<?php echo spUrl("detail","index", array("share_id"=> $c['share_id']));?>" class="trans07"><img src="<?php echo base_url($c['image_path'].'_square.jpg');?>" title="<?php echo $c['title'];?>"/></a></li>
					        	<?php $num++;?>
					        	<?php endforeach;?>
					        	<?php for ($i=$num;$i<9;$i++):?>
					        	<li></li>
					        	<?php endfor;?>
				        	</ul>
						</div>
		            </div>
	            </div>
	            <?php endif;?>
				<?php if($favorite_list):?>
	            <div class="side_container side_bd mt20">
		            <div><h6><?php echo T('who_like_it');?></h6></div>
		            <div class="side_content">
			            <ul class="other">
			       			<li>
			       			<?php foreach ($favorite_list as $favorite):?>
			                	<a href="<?php echo spUrl('pub','index',array('uid'=>$favorite['user_id']));?>" title="<?php echo $favorite['nickname']?>">
			                    <img height="50" width="50" src="<?php echo $favorite['avatar_remote']?>" onerror="javascript:this.src = base_url + '/assets/img/avatar_middle.jpg';"/></a>
			       			<?php endforeach;?>
			        		</li>
			    		</ul>
		            </div>
	            </div>
	            <?php endif;?>
				
				<?php if($view_history):?>
				<div class="side_container side_bd mt20 mb20">
	            	<div><h6><?php echo T('view_history');?></h6></div>
	                <div class="side_content of_h">
	            		<ul class="pic_list">
	                        <?php foreach($view_history as $item): ?>
	                        <li>
	                            <div class="pic_wrap">
	                                <a href="<?php echo spUrl('detail','index', array('share_id'=>$item['s']));?>" class="trans07" title="<?php echo sysSubStr($item['t'],40,true);?>"><img src="<?php echo base_url($item['p'].'_square_like.jpg');?>"></a>
	                            </div>
	
	                        </li>
	                        <?php endforeach;?>
	                    </ul>
	            	</div>
	            </div>
	            <?php endif;?>
	            <?php if($ads):?>
	            <?php foreach ($ads as $ad):?>
	            <div class="side_container side_bd mt20">
		            <div class="side_content text_c">
			            <iframe src="<?php echo spUrl('misc','adproxy',array('key'=>$ad['key'],'ad_position'=>'detailpage_ad'));?>" width="<?php echo $ad['width'];?>" height="<?php echo $ad['height'];?>" frameborder="0" scrolling="no">
						</iframe>
		            </div>
	            </div>
	            <?php endforeach;?>
	            <?php endif;?>
    		</div>
    	</div>
    </div>
</div>

<?php if($settings['ui_detail']['detail_may_like']):?>
<div class="main">
    <div class="g960">
    	<div class="title mt10"  style="margin-left: 8px;"><h5><?php echo T('guess_you_like');?></h5></div>
    	<div class="g960 inside" id="waterfall"data-pin-width="235" data-delete-url="<?php echo spUrl('share','delete_share'); ?>">
			<?php echo $tpl_waterfall; ?>
		</div>
    </div>
</div>
<?php endif;?>
<?php echo $tpl_footer; ?>
