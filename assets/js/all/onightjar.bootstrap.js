// JavaScript Document
$(document).ready(function() {
	$.fn.wait = function(time, type) {
        time = time || 1000;
        type = type || "fx";
        return this.queue(type, function() {
            var self = this;
            setTimeout(function() {
                $(self).dequeue();
            }, time);
        });
    };
	
	$(function() {
	    if (window.PIE) {
	        $('.userbar li').each(function() {
	            PIE.attach(this);
	        });
	        $('.userbar li img').each(function() {
	            PIE.attach(this);
	        });
	        $('.userbar li a').each(function() {
	            PIE.attach(this);
	        });
	        $('.tabBox .tabs a').each(function() {
	            PIE.attach(this);
	        });
	        $('#photo').each(function() {
	            PIE.attach(this);
	        });
	        $('#photo img').each(function() {
	            PIE.attach(this);
	        });
	    }
	});
	$(function() {
		$('#userbar li.menu').hover(function() {
			$('ul', this).css('display', 'block');
		}, function() {
			$('ul', this).fadeOut(300);
		});
	});
	
	$(function() {
		 var $win = $(window);
	     var $nav = $('.scroll');
	     var navTop = $nav.length && $nav.offset().top;
	     var isFixed = 0;

	    processScroll();
	    $nav.on('click', function () {
	      if (!isFixed) setTimeout(function () {  $win.scrollTop($win.scrollTop() - 47); }, 10);
	    });
	    $win.on('scroll', processScroll);
	    function processScroll() {
	      var scrollTop = $win.scrollTop();
	      if (scrollTop >= navTop && !isFixed) {
	        isFixed = 1;
	        $nav.addClass('scroll-fixed');
	      } else if (scrollTop <= navTop && isFixed) {
	        isFixed = 0;
	        $nav.removeClass('scroll-fixed');
	      }
	    }
	});
	
	$(function() {
		var scrtime;
		$("#con").hover(function() {
			clearInterval(scrtime);
		}, function() {

			scrtime = setInterval(function() {
				var $ul = $("#con ul");
				var liHeight = $ul.find("li:last").height();
				$ul.animate({
					marginTop : liHeight + 20 + "px"
				}, 1000, function() {

					$ul.find("li:last").prependTo($ul);
					$ul.find("li:first").hide();
					$ul.css({
						marginTop : 0
					});
					$ul.find("li:first").fadeIn(1000);
				});
			}, 5000);

		}).trigger("mouseleave");
	});
	
	$('input[type=text][title],input[type=password][title],textarea[title]').each(function(i){
	    $(this).addClass('input-prompt-' + i);
	    var promptSpan = $('<span class="input-prompt"/>');
	    $(promptSpan).attr('id', 'input-prompt-' + i);
	    $(promptSpan).append($(this).attr('title'));
	    $(promptSpan).click(function(){
	      $(this).hide();
	      $('.' + $(this).attr('id')).focus();
	    });
	    if($(this).val() != ''){
	      $(promptSpan).hide();
	    }
	    $(this).before(promptSpan);
	    $(this).focus(function(){
	      $('#input-prompt-' + i).hide();
	    });
	    $(this).blur(function(){
	      if($(this).val() == ''){
	        $('#input-prompt-' + i).show();
	      }
	    });
	  });


	if (!+'\v1' && !('maxHeight' in document.body.style)) {
		window['FXL_IE6'] = 1;
	} else {
		window['FXL_IE6'] = 0;
	}

	// 回到顶部按钮
	$("#BackToTop").hide();
	$(function() {
		$(window).scroll(function() {
			if ($(this).scrollTop() > 100) {
				$('.top').addClass("navbar-fixed-top");
				$('#BackToTop').fadeIn();
			} else {
				$('.top').removeClass("navbar-fixed-top");
				$('#BackToTop').fadeOut();
			}
		});
	});
	$(function() {
		$('#slideshow .slideshow').append('<div id="loadingPins" style="width:100%;height:100%;" class="active"><img style="margin-top:100px;" src="'+base_url+'/assets/img/ajax-loader.gif" alt="正在加载..."></div>');
		$('#slideshow .slideshow').imagesLoaded(function(){
			$('#slideshow .slideshow #loadingPins').remove();
			var playSlideshow = setInterval(function(){slideshow_next();}, 5000 );
			$('#prev').click(function(evt) {
				evt.preventDefault();
				slideshow_pre();
			});
			$('#next').click(function(evt) {
				evt.preventDefault();
				slideshow_next();
			});
			
			$('#slideshow').hover(function() {
					addButton();
				    clearInterval(playSlideshow);
			},function(){removeButton();playSlideshow = setInterval(function(){slideshow_next();}, 5000 ); });
			
			function addButton(){
				$('#prev').removeClass('hide');
				$('#next').removeClass('hide');
			}
			function removeButton(){
				$('#prev').addClass('hide');
				$('#next').addClass('hide');
			}
			function slideshow_pre() {
			    var $active = $('#slideshow .slideshow div.active');

			    if ( $active.length == 0 ) $active = $('#slideshow .slideshow div:first');

			    var $next =  $active.prev().length ? $active.prev()
			        : $('#slideshow .slideshow div:last');

			    $active.addClass('last-active');

			    $next.css({opacity: 0.0})
			        .addClass('active')
			        .animate({opacity: 1.0}, 1000, function() {
			            $active.removeClass('active last-active');
			        });
			}
			function slideshow_next() {
			    var $active = $('#slideshow .slideshow div.active');

			    if ( $active.length == 0 ) $active = $('#slideshow .slideshow div:last');

			    var $next =  $active.next().length ? $active.next()
			        : $('#slideshow .slideshow div:first');

			    $active.addClass('last-active');

			    $next.css({opacity: 0.0})
			        .addClass('active')
			        .animate({opacity: 1.0}, 1000, function() {
			            $active.removeClass('active last-active');
			        });
			}
		});
	});
	$.oPopover('user_profile',{
		autoDirection : true,
    	remote : $('#data-actions').attr('data-userprofile-url'),
		elem_mark : 'a[data-user-profile="1"]',
		data_id_mark : 'data-user-id',
		tpl_mark : 'user_profile_tpl'
	});
    $.oPopover('tags_pop',{
		autoDirection : true,
    	remote : $('#data-actions').attr('data-tags-url'),
		elem_mark : 'a[data-tags="1"]',
		data_id_mark : 'data-category-id',
		tpl_mark : 'tags_pop_tpl'
	});
    
});