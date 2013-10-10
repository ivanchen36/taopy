(function($) {
	var list = {};
	$.oPopover = function(id, config) {
	            if(!id || !list[id]) {
	                var D = new oPopover(config);
	                id = !id ? D.elem[0].id : id;
	                list[id] = D;
	            }
	            return list[id];
	        };
	
	
	var _id_counter = 0;

	function oPopover(config) {
		var self = this, cfg = self.config;
		cfg = mix(
				{
					id : null,
					className : 'popover',
					autoDirection : true,
					remote : null,
					elem_mark : 'a[data-popover="1"]',
					data_id_mark : 'data-id',
					tpl_mark : 'popover_tpl',
					outer_tpl : '<div id="%ID" class="ui-dialog %CLASSNAME"></div>',
					loading_tpl : '<p class="message"><img src="'+base_url+'/assets/img/s_loading.gif" />Loading...</p><b class="arrow_t"><i class="arrow_inner"></i></b>',
					error_tpl : '<p class="message">%MESSAGE</p><b class="arrow_t"><i class="arrow_inner"></i></b>'
				}, config || {});
		cfg.id = cfg.id || 'ui-popover-' + counter();
		self.config = cfg;
		self._createHTML();
	}

	mix(
			oPopover.prototype,{
				width : function(px) {
					var self = this;
					self.elem.width(px);
					self.center();
					return self;
				},
				align : function(elem) {
					var self = this, popOverlay=self.popOverlay, cfg = this.config;;
	                if (self.showing) {
	                    if (elem.find('img').length) elem = elem.find('img').eq(0);
	                    var offset = elem.offset(),
	                        left = Math.min(offset.left - 20, $(window).width()-popOverlay.outerWidth()-30);
	                    if(cfg.autoDirection){
		                    var top_px = offset.top-popOverlay.outerHeight()-4;
		                    var show_arrow_top = false;
		                    if(top_px<5){
		                    	top_px = offset.top+$(elem).outerHeight()+4;
		                    	show_arrow_top = true;
		                    }
		                    popOverlay.css({left: left,top: top_px});
		                    if(show_arrow_top){
		                    	popOverlay.find('.arrow_t').css({
			                        left: offset.left - left,
			                        display: 'block'
			                    });
		                    	popOverlay.find('.arrow_b').css({display: 'none'});
		                	}else{
		                		popOverlay.find('.arrow_b').css({
			                        left: offset.left - left,
			                        display: 'block'
			                    });
		                		popOverlay.find('.arrow_t').css({display: 'none'});
		                	}
	                    }else{
	                    	var top_px = offset.top+$(elem).outerHeight()+4;
		                    popOverlay.css({left: left,top: top_px});
		                    popOverlay.find('.arrow_t').css({
			                       left: offset.left - left,
			                       display: 'block'
			                });
	                    }
	                    
	                }
	            },
	            hide : function () {
	            	var self = this, popOverlay=self.popOverlay;
	            	self.showing = false;
	            	popOverlay && popOverlay.hide();
	            },
	            laterHide : function () {
	            	var self = this, showTimer = self.showTimer;
	            	self.hideTimer = setTimeout(function() {
	            		self.hide();
	                }, 300);
	                if (showTimer) {
	                    clearTimeout(showTimer);
	                    self.showTimer = null;
	                }
	            },
	            show : function (elem) {
	            	var self = this, cfg = this.config;
	            	self.showing = true;
	                if (!self.popOverlay) {
	                	var dialog = $(cfg.outer_tpl.replace(/%ID/, cfg.id).replace(
								/%CLASSNAME/, cfg.className));
	                	self.popOverlay = $(dialog).appendTo('body');
	                	self.popOverlay.hover(function() {
	                        if (self.hideTimer) {
	                            clearTimeout(self.hideTimer);
	                            self.hideTimer = null;
	                        }
	                    }, function(){self.laterHide();});
	                }
	                var dataId = elem.attr(cfg.data_id_mark);
	                
	                if (self.popOverlay.data('data-id') === dataId) {
	                	self.popOverlay.show();
	                	self.align(elem);
	                    return;
	                }
	                self.popOverlay.data('data-id', dataId);

	                if (self.popData[dataId]) {
	                	self.popOverlay.html(self.popData[dataId]).show();
	                	self.align(elem);
	                    return;
	                }

	                self.popOverlay.addClass('loading').html(cfg.loading_tpl).show();

	                self.align(elem);
	                $.ajax({
	                    url: cfg.remote,
	        			type: 'get',
	                    data : {'dataid':dataId,'radom': random(3)},
	                    dataType: 'json'
	                }).success(function(result) {
	                	if (result.success === !0) {
		                	self.popOverlay.html(Mustache.to_html($('#'+cfg.tpl_mark).html(), result));
		                	self.popOverlay.removeClass('loading');
		                	self.align(elem);
		                	self.popData[dataId] = self.popOverlay.html();
	                	}else{
		                	self.popOverlay.html(cfg.error_tpl.replace(/%MESSAGE/, result.message));
		                	self.popOverlay.removeClass('loading');
		                	self.align(elem);
	                	}
	                }).error(function(e) {
	                	self.popOverlay.html(cfg.error_tpl);
	                	self.popOverlay.removeClass('loading');
	                	self.align(elem);
	                });
	            },

				_createHTML : function() {
					var self = this, cfg = self.config;
					self.popData={},self.popOverlay=null,self.showing=false,self.hideTimer=null,self.showTimer=null;
					$(cfg.elem_mark).live('mouseenter', function() {
		                var elem = $(this);
		                if (self.hideTimer) {
		                    clearTimeout(self.hideTimer);
		                    self.hideTimer = null;
		                }
		                if (self.showTimer) clearTimeout(self.showTimer);
		                self.showTimer = setTimeout(function() {
		                	self.show(elem);
		                }, 600);
		            }).live('mouseleave', function(){self.laterHide();});
				}
			});

	function mix(r, s) {
		for ( var k in s) {
			r[k] = s[k];
		}
		return r;
	}

	function counter() {
		return _id_counter++;
	}

})(jQuery);