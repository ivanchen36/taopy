(function($) {
	var sc =null;
	$.sclogin = function() {
        if(!sc) {
        	sc = new sclogin();
        }
        return sc;
    };
    function sclogin() {}
    sclogin.prototype = {
    	scrollTimer:null,
    	_position:0,
    	startScroll:function(){
    		var a = this;
    		a._position = $(window).scrollTop();
    		a.scrollTimer = setInterval(function() {
    			a._position += 5,
    			$("body,html").animate({
    				scrollTop: a._position
    			},
    			220);
    			$("body").css("overflow", "hidden");
    		},
    		220);
    	},
    	stopScroll:function() {
    		var a = this;
    		a.scrollTimer && (clearInterval(a.scrollTimer), a.scrollTimer = null, $("body, html").clearQueue().stop(!0, !0), $("body").css("overflow", ""));
    	},
    	start:function(){
    		var a = this;
    		setTimeout(function(){
    			var d = $.oDialog('loginDialog');
    			if (d && d.elem && d.elem.css('display') !== 'none' && d.elem.css('visibility') !== 'hidden') return;
    			$('#login_point').click();
    			d = $($.oDialog('loginDialog'));
    			d.bind("hide",function() {a.stopScroll();});
    			d[0].elem.find("input").bind("focus",function() {a.stopScroll();});
    			a.startScroll();
    			setTimeout(function() {
                    a.stopScroll();
                }, 120000);
    		},10000);
    	}
    };
})(jQuery);