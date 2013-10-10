$(document).ready(function($) {
	var $container = $('#waterfall');
	if($container!=null&&$container!=undefined){
		var pin_width = Number($container.attr('data-pin-width'));
		var animated = Number($container.attr('data-animated'));
		if(animated==1){
			animated=true;
		}else{
			animated=false;
		}
		$(window).resize(function(){
			var need_fullscreen = $('#waterfall_outer').attr('data-fullscreen');
			if(need_fullscreen==1){
				var scrollWidth = 0;
				var scrollCon = $('#waterfall_outer .scroll-container');
				if(scrollCon!=null&&scrollCon!=undefined){
					scrollWidth = scrollCon.width();
				}
				var full_width = $(this).width();
				var outer_width = Math.floor((full_width/pin_width))*pin_width;
				var margin = (full_width-outer_width+10)/2;
				$('#waterfall_outer').css('width',full_width);
				$('#waterfall').css('width',outer_width-scrollWidth);
				if(!scrollWidth){
					$('#waterfall').addClass('inside');
					$('#waterfall').css('margin-left',margin);
				}else{
					scrollCon.css('margin-left',margin);
					$('#waterfall').addClass('inside');
					$('#waterfall').css('margin-left',0);
				}
				$('#header').css('width',outer_width);
				$('#userbar').css('margin-right',20);
				$('#nav-cat').css('width',outer_width);
			}else{
				var full_width = $container.width();
				var outer_width = Math.floor((full_width/pin_width))*pin_width;
				var scrollCon = $('#waterfall_outer .scroll-container');
				var scrollWidth = scrollCon.width();
				if(scrollWidth==null||scrollWidth==undefined||scrollWidth==0){
					$('#waterfall_outer').css('width',full_width);
					$('#waterfall').css('width',outer_width);
					$('#waterfall').css('margin-left',(full_width-outer_width+10)/2);
				}
			}
		  });
		$(window).resize();
		
		function start_waterfall(elem){
			$(elem).find('.pin').fadeIn();
			$(elem).find('.pin').removeClass('hide');
			$(elem).masonry({
					columnWidth: pin_width,
					isAnimated: animated,
					itemSelector : '.pin'
				});
			$('#loadingPins').addClass('hide');
		}
		function append_waterfall(elem){
			//$(elem).css({ opacity: 0 });
			$(elem).removeClass('hide');
			//$(elem).animate({ opacity: 1 });
			$(elem).fadeIn();
	        $container.masonry( 'appended', $(elem), true ); 
		}
		
		function waterfall(elem,callback){
			var images = $(elem).find('.s_image');
			var length = images.length;
			if(length==null||length==undefined||length==0){
				callback.call(this,elem);
			}else{
				var loaded = 0;
				for(var i=0;i<length;i++){
					var im = images[i];
					imageReady(im,'',
						function(r){
							loaded++;
							if(loaded===length){
								callback.call(this,elem);
							}
						},
						null,
						function(r){
							loaded++;
						}
					);
				}
			}
		}
		start_waterfall($container);
		//waterfall($container,start_waterfall);
		$container.infinitescroll({
		      navSelector  : '#page-nav',    // selector for the paged navigation 
		      nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
		      itemSelector : '.pin',     // selector for all items you'll retrieve
		      bufferPx : 500,
		      loading: {
		          finishedMsg: getTip('load_complete'),
		          img: base_url + 'assets/img/ajax-loader.gif',
		          msg: 'test mesg',
		          msgText: getTip('loading')
		        }
		      },
		      function( newElements ) {
		    	 var $newElems = $( newElements );
		    	 //waterfall($newElems,append_waterfall);
		    	 append_waterfall($newElems);
		      }
		    );
		
		
		
		// 使用 imagesLoaded() 修复该插件在 chrome 下的问题
		/*$container.imagesLoaded(function(){
			$container.find('.pin').fadeIn();
			$container.find('.pin').removeClass('hide');
			$container.masonry({
					columnWidth: pin_width,
					isAnimated: animated,
					itemSelector : '.pin'
				});
			$('#loadingPins').addClass('hide');
		});*/
			/*preloadImages($container.find('.s_image'),function(){
				//$container.find('.pin').fadeIn();
				//$container.find('.pin').removeClass('hide');
				$container.masonry({
						columnWidth: pin_width,
						isAnimated: animated,
						itemSelector : '.pin'
					});
				$('#loadingPins').addClass('hide');
			});*/
			
	
	    /*$container.infinitescroll({
	      navSelector  : '#page-nav',    // selector for the paged navigation 
	      nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
	      itemSelector : '.pin',     // selector for all items you'll retrieve
	      bufferPx : 500,
	      loading: {
	          finishedMsg: '就这些了哦',
	          img: base_url + 'assets/img/ajax-loader.gif',
	          msg: 'test mesg',
	          msgText: '正在加载……'
	        }
	      },
	      function( newElements ) {
	        //var $newElems = $( newElements ).css({ opacity: 0 });
	    	  var $newElems = $( newElements );
	    	 $newElems.imagesLoaded(
	    			 function(){
	    		        	$newElems.removeClass('hide');
	    		        	$newElems.fadeIn();
	    		        	//$newElems.animate({ opacity: 1 });
	    			        $container.masonry( 'appended', $newElems, true ); 
	    				}
	    	 );
	        //preloadImages($newElems.find('.s_image'),function(){
	        	//$newElems.removeClass('hide');
	        	//$newElems.animate({ opacity: 1 });
		    //    $container.masonry( 'appended', $newElems, true ); 
			//});
	      }
	    );*/
	}
});