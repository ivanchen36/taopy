(function(a, b, d) {
	function c(b) {
		var f = a(this),
			d = f.closest(":data(" + e + ")");
		if (d.length) {
			var c = arguments,
				h = d.data(e).options;
			if (!h.disabled) {
				var d = f.attr(h.paramsAttr),
					o = b.type.substr(0, 1).toUpperCase() + b.type.substr(1),
					s = h.controller[h.actionPrefix + a.trim(f.attr(h.actionAttr)) + o],
					o = h.controller[h.actionPrefix + h.defaultAction + o],
					f = h.context ? "element" === h.context ? f[0] : h.context : h.controller;
				d ? (d = a.map(d.split(","), function(b) {
					return a.trim(b)
				}), d = g.call(c, 0).concat(d)) : d = c;
				if ("function" === typeof o && !1 === o.apply(f, d)) return !1;
				if ("function" === typeof s) return s.apply(f, d)
			}
		}
	}
	var e = "actionController",
		f = a(b),
		h = {},
		g = Array.prototype.slice,
		b = {
			controller: d,
			events: "click",
			context: "element",
			actionAttr: "data-action",
			paramsAttr: "data-params",
			actionPrefix: "",
			defaultAction: "action",
			disabled: !1
		};
	a.fn[e] = function(b, f) {
		"object" === typeof b && (f = b, b = null);
		var d;
		this.each(function() {
			var h = a.data(this, e) || a.data(this, e, new a[e](this, f));
			b && (d = h[b](f))
		});
		return d || this
	};
	a[e] = function(b, d) {
		var g = a.extend({}, a[e].defaults, d),
			k = "";
		this.element = a(b);
		this.options = g;
		a.each(g.events.split(" "), function(a, b) {
			h[b] && 0 < h[b] ? h[b]++ : (h[b] = 1, k += b + " ")
		});
		k && f.delegate("[" + g.actionAttr + "]", k, c)
	};
	a[e].defaults = b;
	a[e].prototype = {
		destroy: function() {
			var b = this.options;
			a.each(b.events.split(" "), function(a, e) {
				1 < h[e] ? h[e]-- : f.undelegate("[" + b.actionAttr + "]", e, c)
			});
			this.element.removeData(e)
		},
		enable: function() {
			this.options.disabled = !1
		},
		disable: function() {
			this.options.disabled = !0
		}
	};
	a.expr[":"].data = function(b, f, e) {
		return !!a.data(b, e[3])
	}
})(jQuery, window.document);
(function(a, b) {
	var d = b.event,
		c;
	d.special.smartresize = {
		setup: function() {
			b(this).bind("resize", d.special.smartresize.handler)
		},
		teardown: function() {
			b(this).unbind("resize", d.special.smartresize.handler)
		},
		handler: function(a, b) {
			var d = this,
				g = arguments;
			a.type = "smartresize";
			c && clearTimeout(c);
			c = setTimeout(function() {
				jQuery.event.handle.apply(d, g)
			}, "execAsap" === b ? 0 : 100)
		}
	};
	b.fn.smartresize = function(a) {
		return a ? this.bind("smartresize", a) : this.trigger("smartresize", ["execAsap"])
	};
	b.Mason = function(a, f) {
		this.element = b(f);
		this._create(a);
		this._init()
	};
	b.Mason.settings = {
		isResizable: !0,
		isAnimated: !1,
		animationOptions: {
			queue: !1,
			duration: 500
		},
		gutterWidth: 0,
		isRTL: !1,
		isFitWidth: !1,
		containerStyle: {
			position: "relative"
		}
	};
	b.Mason.prototype = {
		_filterFindBricks: function(a) {
			var b = this.options.itemSelector;
			return !b ? a : a.filter(b).add(a.find(b))
		},
		_getBricks: function(a) {
			return this._filterFindBricks(a).css({
				position: "absolute"
			}).addClass("masonry-brick")
		},
		_create: function(e) {
			this.options = b.extend(!0, {}, b.Mason.settings, e);
			this.styleQueue = [];
			e = this.element[0].style;
			this.originalStyle = {
				height: e.height || ""
			};
			var f = this.options.containerStyle,
				d;
			for (d in f) this.originalStyle[d] = e[d] || "";
			this.element.css(f);
			this.horizontalDirection = this.options.isRTL ? "right" : "left";
			this.offset = {
				x: parseInt(this.element.css("padding-" + this.horizontalDirection), 10),
				y: parseInt(this.element.css("padding-top"), 10)
			};
			this.isFluid = this.options.columnWidth && "function" === typeof this.options.columnWidth;
			var c = this;
			setTimeout(function() {
				c.element.addClass("masonry")
			}, 0);
			this.options.isResizable && b(a).bind("smartresize.masonry", function() {
				c.resize()
			});
			this.reloadItems()
		},
		_init: function(a) {
			this._getColumns();
			this._reLayout(a)
		},
		option: function(a) {
			b.isPlainObject(a) && (this.options = b.extend(!0, this.options, a))
		},
		layout: function(a, b) {
			for (var d = 0, c = a.length; d < c; d++) this._placeBrick(a[d]);
			c = {};
			c.height = Math.max.apply(Math, this.colYs);
			if (this.options.isFitWidth) {
				for (var i = 0, d = this.cols; --d && 0 === this.colYs[d];) i++;
				c.width = (this.cols - i) * this.columnWidth - this.options.gutterWidth
			}
			this.styleQueue.push({
				$el: this.element,
				style: c
			});
			for (var i = !this.isLaidOut ? "css" : this.options.isAnimated ? "animate" : "css", j = this.options.animationOptions, l, d = 0, c = this.styleQueue.length; d < c; d++) l = this.styleQueue[d], l.$el[i](l.style, j);
			this.styleQueue = [];
			b && b.call(a);
			this.isLaidOut = !0
		},
		_getColumns: function() {
			var a = (this.options.isFitWidth ? this.element.parent() : this.element).width();
			this.columnWidth = this.isFluid ? this.options.columnWidth(a) : this.options.columnWidth || this.$bricks.outerWidth(!0) || a;
			this.columnWidth += this.options.gutterWidth;
			this.cols = Math.floor((a + this.options.gutterWidth) / this.columnWidth);
			this.cols = Math.max(this.cols, 1)
		},
		_placeBrick: function(a) {
			var a = b(a),
				f, d, c, i, j;
			f = Math.ceil(a.outerWidth(!0) / (this.columnWidth + this.options.gutterWidth));
			f = Math.min(f, this.cols);
			if (1 === f) c = this.colYs;
			else {
				d = this.cols + 1 - f;
				c = [];
				for (j = 0; j < d; j++) i = this.colYs.slice(j, j + f), c[j] = Math.max.apply(Math, i)
			}
			j = Math.min.apply(Math, c);
			d = f = 0;
			for (i = c.length; d < i; d++) if (c[d] === j) {
				f = d;
				break
			}
			c = {
				top: j + this.offset.y
			};
			c[this.horizontalDirection] = this.columnWidth * f + this.offset.x;
			this.styleQueue.push({
				$el: a,
				style: c
			});
			a = j + a.outerHeight(!0);
			c = this.cols + 1 - i;
			for (d = 0; d < c; d++) this.colYs[f + d] = a
		},
		resize: function() {
			var a = this.cols;
			this._getColumns();
			(this.isFluid || this.cols !== a) && this._reLayout()
		},
		_reLayout: function(a) {
			var b = this.cols;
			for (this.colYs = []; b--;) this.colYs.push(0);
			this.layout(this.$bricks, a)
		},
		reloadItems: function() {
			this.$bricks = this._getBricks(this.element.children())
		},
		reload: function(a) {
			this.reloadItems();
			this._init(a)
		},
		appended: function(a, b, d) {
			if (b) {
				this._filterFindBricks(a).css({
					top: this.element.height()
				});
				var c = this;
				setTimeout(function() {
					c._appended(a, d)
				}, 1)
			} else this._appended(a, d)
		},
		_appended: function(a, b) {
			var d = this._getBricks(a);
			this.$bricks = this.$bricks.add(d);
			this.layout(d, b)
		},
		remove: function(a) {
			this.$bricks = this.$bricks.not(a);
			a.remove()
		},
		destroy: function() {
			this.$bricks.removeClass("masonry-brick").each(function() {
				this.style.position = "";
				this.style.top = "";
				this.style.left = ""
			});
			var d = this.element[0].style,
				f;
			for (f in this.originalStyle) d[f] = this.originalStyle[f];
			this.element.unbind(".masonry").removeClass("masonry").removeData("masonry");
			b(a).unbind(".masonry")
		}
	};
	b.fn.imagesLoaded = function(a) {
		function f() {
			a.call(c, i)
		}
		function d(a) {
			a = a.target;
			a.src !== l && -1 === b.inArray(a, k) && (k.push(a), 0 >= --j && (setTimeout(f), i.unbind(".imagesLoaded", d)))
		}
		var c = this,
			i = c.find("img").add(c.filter("img")),
			j = i.length,
			l = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",
			k = [];
		j || f();
		i.bind("load.imagesLoaded error.imagesLoaded", d).each(function() {
			var a = this.src;
			this.src = l;
			this.src = a
		});
		return c
	};
	b.fn.imagesReady = function(a, b) {
		function d() {
			b.call(this, c)
		}
		var c = a,
			i = [],
			j = null,
			l = [
				["width", "height"],
				["naturalWidth", "naturalHeight"]
			],
			k = Number("number" === typeof document.createElement("img").naturalHeight),
			p = function() {
				for (var a = 0; a < i.length; a++) i[a].end ? i.splice(a--, 1) : o.call(i[a], null);
				i.length && (j = setTimeout(p, 50)) || (j = null)
			},
			o = function() {
				if (this[l[k][0]] !== this.__width || this[l[k][1]] !== this.__height || 1024 < this[l[k][0]] * this[l[k][1]]) this.onready.call(this, null), this.end = !0
			},
			b = b || new Function;
		c.onerror = function() {
			d();
			c.end = !0;
			c = c.onload = c.onerror = c.onreadystatechange = null
		};
		if (c) if (c.__width = c[l[k][0]], c.__height = c[l[k][1]], c.complete) d();
		else return c.onready = function() {
			d()
		}, o.call(c, null), c.onload = c.onreadystatechange = function() {
			if (!c || !c.readyState || !("loaded" != c.readyState && "complete" != c.readyState))!c.end && o.call(c, null), c = c.onload = c.onerror = c.onreadystatechange = null
		}, c.end || (i.push(c), null === j && (j = setTimeout(p, 50))), c;
		else d()
	};
	b.fn.imagesPreLoaded = function(a) {
		function f() {
			a.call(c, i)
		}
		function d(a) {
			a.src !== l && -1 === b.inArray(a, k) && (k.push(a), 0 >= --j && setTimeout(f))
		}
		var c = this,
			i = c.find("img").add(c.filter("img")),
			j = i.length,
			l = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",
			k = [];
		j || f();
		i.each(function() {
			var a = this.src;
			this.src = l;
			this.src = a;
			b(this).imagesReady(this, d)
		});
		return c
	};
	b.fn.masonry = function(c) {
		if ("string" === typeof c) {
			var f = Array.prototype.slice.call(arguments, 1);
			this.each(function() {
				var d = b.data(this, "masonry");
				d ? !b.isFunction(d[c]) || "_" === c.charAt(0) ? a.console && a.console.error("no such method '" + c + "' for masonry instance") : d[c].apply(d, f) : a.console && a.console.error("cannot call methods on masonry prior to initialization; attempted to call method '" + c + "'")
			})
		} else this.each(function() {
			var a = b.data(this, "masonry");
			a ? (a.option(c || {}), a._init()) : b.data(this, "masonry", new b.Mason(c, this))
		});
		return this
	}
})(window, jQuery);
(function(a, b, d) {
	b.infinitescroll = function(a, c, d) {
		this.element = b(d);
		this._create(a, c) || (this.failed = !0)
	};
	b.infinitescroll.defaults = {
		loading: {
			finished: d,
			finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
			img: "http://www.infinite-scroll.com/loading.gif",
			msg: null,
			msgText: "<em>Loading the next set of posts...</em>",
			selector: null,
			speed: "fast",
			start: d
		},
		state: {
			isDuringAjax: !1,
			isInvalidPage: !1,
			isDestroyed: !1,
			isDone: !1,
			isPaused: !1,
			currPage: 1
		},
		callback: d,
		debug: !1,
		behavior: d,
		binder: b(a),
		nextSelector: "div.navigation a:first",
		navSelector: "div.navigation",
		contentSelector: null,
		extraScrollPx: 250,
		itemSelector: "div.post",
		animate: !1,
		pathParse: d,
		dataType: "html",
		appendCallback: !0,
		bufferPx: 40,
		errorCallback: function() {},
		infid: 0,
		pixelsFromNavToBottom: d,
		resetPage: "0",
		path: d
	};
	b.infinitescroll.prototype = {
		_binding: function(a) {
			var b = this,
				c = b.options;
			c.v = "2.0b2.111027";
			if (c.behavior && this["_binding_" + c.behavior] !== d) this["_binding_" + c.behavior].call(this);
			else {
				if ("bind" !== a && "unbind" !== a) return this._debug("Binding value  " + a + " not valid"), !1;
				if ("unbind" == a) this.options.binder.unbind("smartscroll.infscr." + b.options.infid);
				else this.options.binder[a]("smartscroll.infscr." + b.options.infid, function() {
					b.scroll()
				});
				this._debug("Binding", a)
			}
		},
		_create: function(a, c) {
			var e = b.extend(!0, {}, b.infinitescroll.defaults, a);
			if (!this._validate(a)) return !1;
			this.options = e;
			var i = b(e.nextSelector).attr("href");
			if (!i) return this._debug("Navigation selector not found"), !1;
			e.path = this._determinepath(i);
			i = b(e.nextSelector).attr("data-reset");
			e.resetPage = i;
			e.contentSelector = e.contentSelector || this.element;
			e.loading.selector = e.loading.selector || e.contentSelector;
			e.loading.msg = b('<div id="infscr-loading"><img alt="Loading..." src="' + e.loading.img + '" /><div>' + e.loading.msgText + "</div></div>");
			(new Image).src = e.loading.img;
			e.pixelsFromNavToBottom = b(document).height() - b(e.navSelector).offset().top;
			e.loading.start = e.loading.start ||
			function() {
				b(e.navSelector).hide();
				e.loading.msg.appendTo(e.loading.selector).show(e.loading.speed, function() {
					beginAjax(e)
				})
			};
			e.loading.finished = e.loading.finished ||
			function() {
				e.loading.msg.fadeOut("normal")
			};
			e.callback = function(a, f) {
				e.behavior && a["_callback_" + e.behavior] !== d && a["_callback_" + e.behavior].call(b(e.contentSelector)[0], f);
				c && c.call(b(e.contentSelector)[0], f, e)
			};
			this._setup();
			return !0
		},
		_debug: function() {
			if (this.options && this.options.debug) return a.console && console.log.call(console, arguments)
		},
		_determinepath: function(a) {
			var b = this.options;
			if (b.behavior && this["_determinepath_" + b.behavior] !== d) this["_determinepath_" + b.behavior].call(this, a);
			else {
				if (b.pathParse) return this._debug("pathParse manual"), b.pathParse(a, this.options.state.currPage + 1);
				if (a.match(/^(.*?)\b2\b(.*?$)/)) a = a.match(/^(.*?)\b2\b(.*?$)/).slice(1);
				else if (a.match(/^(.*?)2(.*?$)/)) {
					if (a.match(/^(.*?page=)2(\/.*|$)/)) return a = a.match(/^(.*?page=)2(\/.*|$)/).slice(1);
					a = a.match(/^(.*?)2(.*?$)/).slice(1)
				} else {
					if (a.match(/^(.*?page=)1(\/.*|$)/)) return a = a.match(/^(.*?page=)1(\/.*|$)/).slice(1);
					this._debug("Sorry, we couldn't parse your Next (Previous Posts) URL. Verify your the css selector points to the correct A tag. If you still get this error: yell, scream, and kindly ask for help at infinite-scroll.com.");
					b.state.isInvalidPage = !0
				}
				this._debug("determinePath", a);
				return a
			}
		},
		_error: function(a) {
			var b = this.options;
			b.behavior && this["_error_" + b.behavior] !== d ? this["_error_" + b.behavior].call(this, a) : ("destroy" !== a && "end" !== a && (a = "unknown"), this._debug("Error", a), "end" == a && this._showdonemsg(), b.state.isDone = !0, b.state.currPage = 1, b.state.isPaused = !1, this._binding("unbind"))
		},
		_loadcallback: function(c, e) {
			var g = this.options,
				i = this.options.callback,
				j = g.state.isDone ? "done" : !g.appendCallback ? "no-append" : "append";
			if (g.behavior && this["_loadcallback_" + g.behavior] !== d) this["_loadcallback_" + g.behavior].call(this, c, e);
			else {
				switch (j) {
				case "done":
					return this._showdonemsg(), !1;
				case "no-append":
					"html" == g.dataType && (e = b("<div>" + e + "</div>").find(g.itemSelector));
					break;
				case "append":
					this._debug("data changed!", c);
					if (j = b(c).find(g.nextSelector)) {
						var l = b(j).attr("href"),
							k = b(j).attr("data-reset");
						l && (this._debug("path changed!"), g.path = this._determinepath(l));
						g.resetPage = "1" == k ? "1" : "0";
						b(j).remove();
						(j = b(c).find(g.navSelector)) && b(j).remove()
					}
					b(c).find(".extra") && this._debug("found extra");
					l = c.children();
					if (0 == l.length) return this._error("end");
					for (j = document.createDocumentFragment(); c[0].firstChild;) j.appendChild(c[0].firstChild);
					this._debug("contentSelector", b(g.contentSelector)[0]);
					b(g.contentSelector)[0].appendChild(j);
					e = l.get()
				}
				g.loading.finished.call(b(g.contentSelector)[0], g);
				g.animate && (j = b(a).scrollTop() + b("#infscr-loading").height() + g.extraScrollPx + "px", b("html,body").animate({
					scrollTop: j
				}, 800, function() {
					g.state.isDuringAjax = !1
				}));
				g.animate || (g.state.isDuringAjax = !1);
				i(this, e)
			}
		},
		_nearbottom: function() {
			var c = this.options,
				e = 0 + b(document).height() - c.binder.scrollTop() - b(a).height();
			if (c.behavior && this["_nearbottom_" + c.behavior] !== d) return this["_nearbottom_" + c.behavior].call(this);
			this._debug("math:", e, c.pixelsFromNavToBottom);
			return e - c.bufferPx < c.pixelsFromNavToBottom
		},
		_pausing: function(a) {
			var b = this.options;
			if (b.behavior && this["_pausing_" + b.behavior] !== d) this["_pausing_" + b.behavior].call(this, a);
			else {
				"pause" !== a && ("resume" !== a && null !== a) && this._debug("Invalid argument. Toggling pause value instead");
				switch (a && ("pause" == a || "resume" == a) ? a : "toggle") {
				case "pause":
					b.state.isPaused = !0;
					break;
				case "resume":
					b.state.isPaused = !1;
					break;
				case "toggle":
					b.state.isPaused = !b.state.isPaused
				}
				this._debug("Paused", b.state.isPaused);
				return !1
			}
		},
		_setup: function() {
			var a = this.options;
			if (a.behavior && this["_setup_" + a.behavior] !== d) this["_setup_" + a.behavior].call(this);
			else return this._binding("bind"), !1
		},
		_showdonemsg: function() {
			var a = this.options;
			a.behavior && this["_showdonemsg_" + a.behavior] !== d ? this["_showdonemsg_" + a.behavior].call(this) : (a.loading.msg.find("img").hide().parent().find("div").html(a.loading.finishedMsg).animate({
				opacity: 1
			}, 2E3, function() {
				b(this).parent().fadeOut("normal")
			}), a.errorCallback.call(b(a.contentSelector)[0], "done"))
		},
		_validate: function(a) {
			for (var c in a) if (c.indexOf && -1 < c.indexOf("Selector") && 0 === b(a[c]).length) return this._debug("Your " + c + " found no elements."), !1;
			return !0
		},
		bind: function() {
			this._binding("bind")
		},
		destroy: function() {
			this.options.state.isDestroyed = !0;
			return this._error("destroy")
		},
		pause: function() {
			this._pausing("pause")
		},
		resume: function() {
			this._pausing("resume")
		},
		retrieve: function(a) {
			var c = this,
				e = c.options,
				i = e.path,
				j, l, k, p, a = a || null;
			beginAjax = function(a) {
				"1" == a.resetPage && (a.state.currPage = 0);
				a.state.currPage++;
				c._debug("heading into ajax", i);
				j = b(a.contentSelector).is("table") ? b("<tbody/>") : b("<div/>");
				l = i.join(a.state.currPage);
				k = "html" == a.dataType || "json" == a.dataType ? a.dataType : "html+callback";
				a.appendCallback && "html" == a.dataType && (k += "+callback");
				switch (k) {
				case "html+callback":
					c._debug("Using HTML via .load() method");
					j.load(l + " " + a.itemSelector, null, function(a) {
						c._loadcallback(j, a)
					});
					break;
				case "html":
				case "json":
					c._debug("Using " + k.toUpperCase() + " via $.ajax() method"), b.ajax({
						url: l,
						dataType: a.dataType,
						complete: function(a, b) {
							(p = typeof a.isResolved !== "undefined" ? a.isResolved() : b === "success" || b === "notmodified") ? c._loadcallback(j, a.responseText) : c._error("end")
						}
					})
				}
			};
			if (e.behavior && this["retrieve_" + e.behavior] !== d) this["retrieve_" + e.behavior].call(this, a);
			else {
				if (e.state.isDestroyed) return this._debug("Instance is destroyed"), !1;
				e.state.isDuringAjax = !0;
				e.loading.start.call(b(e.contentSelector)[0], e)
			}
		},
		scroll: function() {
			var a = this.options,
				b = a.state;
			a.behavior && this["scroll_" + a.behavior] !== d ? this["scroll_" + a.behavior].call(this) : !b.isDuringAjax && !b.isInvalidPage && !b.isDone && !b.isDestroyed && !b.isPaused && this._nearbottom() && this.retrieve()
		},
		toggle: function() {
			this._pausing()
		},
		unbind: function() {
			this._binding("unbind")
		},
		update: function(a) {
			b.isPlainObject(a) && (this.options = b.extend(!0, this.options, a))
		}
	};
	b.fn.infinitescroll = function(a, c) {
		switch (typeof a) {
		case "string":
			var d = Array.prototype.slice.call(arguments, 1);
			this.each(function() {
				var c = b.data(this, "infinitescroll");
				if (!c || !b.isFunction(c[a]) || "_" === a.charAt(0)) return !1;
				c[a].apply(c, d)
			});
			break;
		case "object":
			this.each(function() {
				var d = b.data(this, "infinitescroll");
				d ? d.update(a) : (d = new b.infinitescroll(a, c, this), d.failed || b.data(this, "infinitescroll", d))
			})
		}
		return this
	};
	var c = b.event,
		e;
	c.special.smartscroll = {
		setup: function() {
			b(this).bind("scroll", c.special.smartscroll.handler)
		},
		teardown: function() {
			b(this).unbind("scroll", c.special.smartscroll.handler)
		},
		handler: function(a, c) {
			var d = this,
				i = arguments;
			a.type = "smartscroll";
			e && clearTimeout(e);
			e = setTimeout(function() {
				b.event.handle.apply(d, i)
			}, "execAsap" === c ? 0 : 100)
		}
	};
	b.fn.smartscroll = function(a) {
		return a ? this.bind("smartscroll", a) : this.trigger("smartscroll", ["execAsap"])
	}
})(window, jQuery);
(function(a, b, d) {
	function c() {
		h = b[g](function() {
			e.each(function() {
				var b = a(this),
					c = b.width(),
					d = b.height(),
					e = a.data(this, j);
				if (c !== e.w || d !== e.h) b.trigger(i, [e.w = c, e.h = d])
			});
			c()
		}, f[l])
	}
	var e = a([]),
		f = a.resize = a.extend(a.resize, {}),
		h, g = "setTimeout",
		i = "resize",
		j = i + "-special-event",
		l = "delay";
	f[l] = 250;
	f.throttleWindow = !0;
	a.event.special[i] = {
		setup: function() {
			if (!f.throttleWindow && this[g]) return !1;
			var b = a(this);
			e = e.add(b);
			a.data(this, j, {
				w: b.width(),
				h: b.height()
			});
			1 === e.length && c()
		},
		teardown: function() {
			if (!f.throttleWindow && this[g]) return !1;
			var b = a(this);
			e = e.not(b);
			b.removeData(j);
			e.length || clearTimeout(h)
		},
		add: function(b) {
			function c(b, f, i) {
				var h = a(this),
					g = a.data(this, j);
				g.w = f !== d ? f : h.width();
				g.h = i !== d ? i : h.height();
				e.apply(this, arguments)
			}
			if (!f.throttleWindow && this[g]) return !1;
			var e;
			if (a.isFunction(b)) return e = b, c;
			e = b.handler;
			b.handler = c
		}
	}
})(jQuery, this);
var Mustache = "undefined" !== typeof module && module.exports || {};
(function(a) {
	function b(a) {
		return ("" + a).replace(/&(?!\w+;)|[<>"']/g, function(a) {
			return C[a] || a
		})
	}
	function d(a, b, c, d) {
		for (var d = d || "<template>", e = b.split("\n"), f = Math.max(c - 3, 0), i = Math.min(e.length, c + 3), e = e.slice(f, i), h = 0, j = e.length; h < j; ++h) i = h + f + 1, e[h] = (i === c ? " >> " : "    ") + e[h];
		a.template = b;
		a.line = c;
		a.file = d;
		a.message = [d + ":" + c, e.join("\n"), "", a.message].join("\n");
		return a
	}
	function c(a, b, c) {
		if ("." === a) return b[b.length - 1];
		for (var a = a.split("."), d = a.length - 1, e = a[d], f, i, h = b.length, j, g; h;) {
			g = b.slice(0);
			i = b[--h];
			for (j = 0; j < d;) {
				i = i[a[j++]];
				if (null == i) break;
				g.push(i)
			}
			if (i && "object" === typeof i && e in i) {
				f = i[e];
				break
			}
		}
		"function" === typeof f && (f = f.call(g[g.length - 1]));
		return null == f ? c : f
	}
	function e(a, b, d, e) {
		var f = "",
			a = c(a, b);
		if (e) {
			if (null == a || !1 === a || o(a) && 0 === a.length) f += d()
		} else if (o(a)) s(a, function(a) {
			b.push(a);
			f += d();
			b.pop()
		});
		else if ("object" === typeof a) b.push(a), f += d(), b.pop();
		else if ("function" === typeof a) var h = b[b.length - 1],
			f = f + (a.call(h, d(), function(a) {
				return i(a, h)
			}) || "");
		else a && (f += d());
		return f
	}

	function f(b, c) {
		for (var c = c || {}, e = c.tags || a.tags, f = e[0], i = e[e.length - 1], h = ['var buffer = "";', "\nvar line = 1;", "\ntry {", '\nbuffer += "'], j = [], g = !1, k = !1, l = function() {
				if (g && !k && !c.space) for (; j.length;) h.splice(j.pop(), 1);
				else j = [];
				k = g = !1
			}, o = [], p, y, C, E = function(a) {
				e = A(a).split(/\s+/);
				y = e[0];
				C = e[e.length - 1]
			}, s = function(a) {
				h.push('";', p, '\nvar partial = partials["' + A(a) + '"];', "\nif (partial) {", "\n  buffer += render(partial,stack[stack.length - 1],partials);", "\n}", '\nbuffer += "')
			}, B = function(a, e) {
				var f = A(a);
				if ("" === f) throw d(Error("Section name may not be empty"), b, z, c.file);
				o.push({
					name: f,
					inverted: e
				});
				h.push('";', p, '\nvar name = "' + f + '";', "\nvar callback = (function () {", "\n  return function () {", '\n    var buffer = "";', '\nbuffer += "')
			}, T = function(a) {
				B(a, !0)
			}, P = function(a) {
				var a = A(a),
					e = 0 != o.length && o[o.length - 1].name;
				if (!e || a != e) throw d(Error('Section named "' + a + '" was never opened'), b, z, c.file);
				a = o.pop();
				h.push('";', "\n    return buffer;", "\n  };", "\n})();");
				a.inverted ? h.push("\nbuffer += renderSection(name,stack,callback,true);") : h.push("\nbuffer += renderSection(name,stack,callback);");
				h.push('\nbuffer += "')
			}, w = function(a) {
				h.push('";', p, '\nbuffer += lookup("' + A(a) + '",stack,"");', '\nbuffer += "')
			}, I = function(a) {
				h.push('";', p, '\nbuffer += escapeHTML(lookup("' + A(a) + '",stack,""));', '\nbuffer += "')
			}, z = 1, q, x, r = 0, u = b.length; r < u; ++r) if (b.slice(r, r + f.length) === f) {
			r += f.length;
			q = b.substr(r, 1);
			p = "\nline = " + z + ";";
			y = f;
			C = i;
			g = !0;
			switch (q) {
			case "!":
				r++;
				x = null;
				break;
			case "=":
				r++;
				i = "=" + i;
				x = E;
				break;
			case ">":
				r++;
				x = s;
				break;
			case "#":
				r++;
				x = B;
				break;
			case "^":
				r++;
				x = T;
				break;
			case "/":
				r++;
				x = P;
				break;
			case "{":
				i = "}" + i;
			case "&":
				r++;
				k = !0;
				x = w;
				break;
			default:
				k = !0, x = I
			}
			q = b.indexOf(i, r);
			if (-1 === q) throw d(Error('Tag "' + f + '" was not closed properly'), b, z, c.file);
			f = b.substring(r, q);
			x && x(f);
			for (x = 0;~ (x = f.indexOf("\n", x));) z++, x++;
			r = q + i.length - 1;
			f = y;
			i = C
		} else switch (q = b.substr(r, 1), q) {
		case '"':
		case "\\":
			k = !0;
			h.push("\\" + q);
			break;
		case "\r":
			break;
		case "\n":
			j.push(h.length);
			h.push("\\n");
			l();
			z++;
			break;
		default:
			J.test(q) ? j.push(h.length) : k = !0, h.push(q)
		}
		if (0 != o.length) throw d(Error('Section "' + o[o.length - 1].name + '" was not closed properly'), b, z, c.file);
		l();
		h.push('";', "\nreturn buffer;", "\n} catch (e) { throw {error: e, line: line}; }");
		i = h.join("").replace(/buffer \+= "";\n/g, "");
		c.debug && ("undefined" != typeof console && console.log ? console.log(i) : "function" === typeof print && print(i));
		return i
	}
	function h(a, h) {
		var j = f(a, h),
			g = new Function("view,partials,stack,lookup,escapeHTML,renderSection,render", j);
		return function(f, j) {
			var j = j || {},
				k = [f];
			try {
				return g(f, j, k, c, b, e, i)
			} catch (l) {
				throw d(l.error, a, l.line, h.file);
			}
		}
	}
	function g(a, b) {
		b = b || {};
		return !1 !== b.cache ? (E[a] || (E[a] = h(a, b)), E[a]) : h(a, b)
	}
	function i(a, b, c) {
		return g(a)(b, c)
	}
	a.name = "mustache.js";
	a.version = "0.5.0-dev";
	a.tags = ["{{", "}}"];
	a.parse = f;
	a.compile = g;
	a.render = i;
	a.clearCache = function() {
		E = {}
	};
	a.to_html = function(a, b, c, d) {
		a = i(a, b, c);
		if ("function" === typeof d) d(a);
		else return a
	};
	var j = Object.prototype.toString,
		l = Array.isArray,
		k = Array.prototype.forEach,
		p = String.prototype.trim,
		o;
	o = l ? l : function(a) {
		return "[object Array]" === j.call(a)
	};
	var s;
	s = k ?
	function(a, b, c) {
		return k.call(a, b, c)
	} : function(a, b, c) {
		for (var d = 0, e = a.length; d < e; ++d) b.call(c, a[d], d, a)
	};
	var J = /^\s*$/,
		A;
	if (p) A = function(a) {
		return null == a ? "" : p.call(a)
	};
	else {
		var B, y;
		J.test("\u00a0") ? (B = /^\s+/, y = /\s+$/) : (B = /^[\s\xA0]+/, y = /[\s\xA0]+$/);
		A = function(a) {
			return a == null ? "" : ("" + a).replace(B, "").replace(y, "")
		}
	}
	var C = {
		"&": "&amp;",
		"<": "&lt;",
		">": "&gt;",
		'"': "&quot;",
		"'": "&#39;"
	},
		E = {}
})(Mustache);
(function(a) {
	function b(b) {
		var c = b.data;
		b.isDefaultPrevented() || (b.preventDefault(), a(this).ajaxSubmit(c))
	}
	function d(b) {
		var c = b.target,
			d = a(c);
		if (!d.is(":submit,input:image")) {
			c = d.closest(":submit");
			if (0 == c.length) return;
			c = c[0]
		}
		var g = this;
		g.clk = c;
		"image" == c.type && (void 0 != b.offsetX ? (g.clk_x = b.offsetX, g.clk_y = b.offsetY) : "function" == typeof a.fn.offset ? (d = d.offset(), g.clk_x = b.pageX - d.left, g.clk_y = b.pageY - d.top) : (g.clk_x = b.pageX - c.offsetLeft, g.clk_y = b.pageY - c.offsetTop));
		setTimeout(function() {
			g.clk = g.clk_x = g.clk_y = null
		}, 100)
	}
	function c() {
		if (a.fn.ajaxSubmit.debug) {
			var b = "[jquery.form] " + Array.prototype.join.call(arguments, "");
			window.console && window.console.log ? window.console.log(b) : window.opera && window.opera.postError && window.opera.postError(b)
		}
	}
	a.fn.ajaxSubmit = function(b) {
		function d(c) {
			for (var f = new FormData, i = 0; i < c.length; i++)"file" != c[i].type && f.append(c[i].name, c[i].value);
			j.find("input:file:enabled").each(function() {
				var b = a(this).attr("name"),
					c = this.files;
				if (b) for (var d = 0; d < c.length; d++) f.append(b, c[d])
			});
			if (b.extraData) for (var h in b.extraData) f.append(h, b.extraData[h]);
			b.data = null;
			c = a.extend(!0, {}, a.ajaxSettings, b, {
				contentType: !1,
				processData: !1,
				cache: !1,
				type: "POST"
			});
			c.data = null;
			var g = c.beforeSend;
			c.beforeSend = function(a, c) {
				c.data = f;
				a.upload && (a.upload.onprogress = function(a) {
					c.progress(a.position, a.total)
				});
				g && g.call(c, a, b)
			};
			a.ajax(c)
		}
		function h(d) {
			function f() {
				function b() {
					try {
						var a = (t.contentWindow ? t.contentWindow.document : t.contentDocument ? t.contentDocument : t.document).readyState;
						c("state = " + a);
						"uninitialized" == a.toLowerCase() && setTimeout(b, 50)
					} catch (d) {
						c("Server abort: ", d, " (", d.name, ")"), i(H), F && clearTimeout(F), F = void 0
					}
				}
				var d = j.attr("target"),
					e = j.attr("action");
				h.setAttribute("target", p);
				g || h.setAttribute("method", "POST");
				e != m.url && h.setAttribute("action", m.url);
				!m.skipEncodingOverride && (!g || /post/i.test(g)) && j.attr({
					encoding: "multipart/form-data",
					enctype: "multipart/form-data"
				});
				m.timeout && (F = setTimeout(function() {
					L = !0;
					i(G)
				}, m.timeout));
				var k = [];
				try {
					if (m.extraData) for (var l in m.extraData) k.push(a('<input type="hidden" name="' + l + '">').attr("value", m.extraData[l]).appendTo(h)[0]);
					m.iframeTarget || (s.appendTo("body"), t.attachEvent ? t.attachEvent("onload", i) : t.addEventListener("load", i, !1));
					setTimeout(b, 15);
					h.submit()
				} finally {
					h.setAttribute("action", e), d ? h.setAttribute("target", d) : j.removeAttr("target"), a(k).remove()
				}
			}
			function i(b) {
				if (!n.aborted && !D) {
					try {
						v = t.contentWindow ? t.contentWindow.document : t.contentDocument ? t.contentDocument : t.document
					} catch (d) {
						c("cannot access response document: ", d), b = H
					}
					if (b === G && n) n.abort("timeout");
					else if (b == H && n) n.abort("server abort");
					else if (v && v.location.href != m.iframeSrc || L) {
						t.detachEvent ? t.detachEvent("onload", i) : t.removeEventListener("load", i, !1);
						var b = "success",
							e;
						try {
							if (L) throw "timeout";
							var f = "xml" == m.dataType || v.XMLDocument || a.isXMLDoc(v);
							c("isXml=" + f);
							if (!f && (window.opera && (null == v.body || "" == v.body.innerHTML)) && --R) {
								c("requeing onLoad callback, DOM not available");
								setTimeout(i, 250);
								return
							}
							var h = v.body ? v.body : v.documentElement;
							n.responseText = h ? h.innerHTML : null;
							n.responseXML = v.XMLDocument ? v.XMLDocument : v;
							f && (m.dataType = "xml");
							n.getResponseHeader = function(a) {
								return {
									"content-type": m.dataType
								}[a]
							};
							h && (n.status = Number(h.getAttribute("status")) || n.status, n.statusText = h.getAttribute("statusText") || n.statusText);
							var j = (m.dataType || "").toLowerCase(),
								g = /(json|script|text)/.test(j);
							if (g || m.textarea) {
								var r = v.getElementsByTagName("textarea")[0];
								if (r) n.responseText = r.value, n.status = Number(r.getAttribute("status")) || n.status, n.statusText = r.getAttribute("statusText") || n.statusText;
								else if (g) {
									var u = v.getElementsByTagName("pre")[0],
										k = v.getElementsByTagName("body")[0];
									u ? n.responseText = u.textContent ? u.textContent : u.innerText : k && (n.responseText = k.textContent ? k.textContent : k.innerText)
								}
							} else "xml" == j && (!n.responseXML && null != n.responseText) && (n.responseXML = N(n.responseText));
							try {
								M = O(n, j, m)
							} catch (l) {
								b = "parsererror", n.error = e = l || b
							}
						} catch (U) {
							c("error caught: ", U), b = "error", n.error = e = U || b
						}
						n.aborted && (c("upload aborted"), b = null);
						n.status && (b = 200 <= n.status && 300 > n.status || 304 === n.status ? "success" : "error");
						"success" === b ? (m.success && m.success.call(m.context, M, "success", n), o && a.event.trigger("ajaxSuccess", [n, m])) : b && (void 0 == e && (e = n.statusText), m.error && m.error.call(m.context, n, b, e), o && a.event.trigger("ajaxError", [n, m, e]));
						o && a.event.trigger("ajaxComplete", [n, m]);
						o && !--a.active && a.event.trigger("ajaxStop");
						m.complete && m.complete.call(m.context, n, b);
						D = !0;
						m.timeout && clearTimeout(F);
						setTimeout(function() {
							m.iframeTarget || s.remove();
							n.responseXML = null
						}, 100)
					}
				}
			}
			var h = j[0],
				k, l, m, o, p, s, t, n, L, F;
			k = !! a.fn.prop;
			if (d) if (k) for (l = 0; l < d.length; l++) k = a(h[d[l].name]), k.prop("disabled", !1);
			else for (l = 0; l < d.length; l++) k = a(h[d[l].name]), k.removeAttr("disabled");
			if (a(":input[name=submit],:input[id=submit]", h).length) alert('Error: Form elements must not have name or id of "submit".');
			else if (m = a.extend(!0, {}, a.ajaxSettings, b), m.context = m.context || m, p = "jqFormIO" + (new Date).getTime(), m.iframeTarget ? (s = a(m.iframeTarget), k = s.attr("name"), null == k ? s.attr("name", p) : p = k) : (s = a('<iframe name="' + p + '" src="' + m.iframeSrc + '" />'), s.css({
				position: "absolute",
				top: "-1000px",
				left: "-1000px"
			})), t = s[0], n = {
				aborted: 0,
				responseText: null,
				responseXML: null,
				status: 0,
				statusText: "n/a",
				getAllResponseHeaders: function() {},
				getResponseHeader: function() {},
				setRequestHeader: function() {},
				abort: function(b) {
					var d = b === "timeout" ? "timeout" : "aborted";
					c("aborting upload... " + d);
					this.aborted = 1;
					s.attr("src", m.iframeSrc);
					n.error = d;
					m.error && m.error.call(m.context, n, d, b);
					o && a.event.trigger("ajaxError", [n, m, d]);
					m.complete && m.complete.call(m.context, n, d)
				}
			}, (o = m.global) && !a.active++ && a.event.trigger("ajaxStart"), o && a.event.trigger("ajaxSend", [n, m]), m.beforeSend && !1 === m.beforeSend.call(m.context, n, m)) m.global && a.active--;
			else if (!n.aborted) {
				if (d = h.clk) if ((k = d.name) && !d.disabled) m.extraData = m.extraData || {}, m.extraData[k] = d.value, "image" == d.type && (m.extraData[k + ".x"] = h.clk_x, m.extraData[k + ".y"] = h.clk_y);
				var G = 1,
					H = 2,
					d = a("meta[name=csrf-token]").attr("content");
				if ((k = a("meta[name=csrf-param]").attr("content")) && d) m.extraData = m.extraData || {}, m.extraData[k] = d;
				m.forceSync ? f() : setTimeout(f, 10);
				var M, v, R = 50,
					D, N = a.parseXML ||
				function(a, b) {
					if (window.ActiveXObject) {
						b = new ActiveXObject("Microsoft.XMLDOM");
						b.async = "false";
						b.loadXML(a)
					} else b = (new DOMParser).parseFromString(a, "text/xml");
					return b && b.documentElement && b.documentElement.nodeName != "parsererror" ? b : null
				}, S = a.parseJSON ||
				function(a) {
					return window.eval("(" + a + ")")
				}, O = function(b, c, d) {
					var e = b.getResponseHeader("content-type") || "",
						f = c === "xml" || !c && e.indexOf("xml") >= 0,
						b = f ? b.responseXML : b.responseText;
					f && b.documentElement.nodeName === "parsererror" && a.error && a.error("parsererror");
					d && d.dataFilter && (b = d.dataFilter(b, c));
					typeof b === "string" && (c === "json" || !c && e.indexOf("json") >= 0 ? b = S(b) : (c === "script" || !c && e.indexOf("javascript") >= 0) && a.globalEval(b));
					return b
				}
			}
		}
		if (!this.length) return c("ajaxSubmit: skipping submit process - no element selected"), this;
		var g, i, j = this;
		"function" == typeof b && (b = {
			success: b
		});
		g = this.attr("method");
		i = this.attr("action");
		(i = (i = "string" === typeof i ? a.trim(i) : "") || window.location.href || "") && (i = (i.match(/^([^#]+)/) || [])[1]);
		b = a.extend(!0, {
			url: i,
			success: a.ajaxSettings.success,
			type: g || "GET",
			iframeSrc: /^https/i.test(window.location.href || "") ? "javascript:false" : "about:blank"
		}, b);
		i = {};
		this.trigger("form-pre-serialize", [this, b, i]);
		if (i.veto) return c("ajaxSubmit: submit vetoed via form-pre-serialize trigger"), this;
		if (b.beforeSerialize && !1 === b.beforeSerialize(this, b)) return c("ajaxSubmit: submit aborted via beforeSerialize callback"), this;
		var l = b.traditional;
		void 0 === l && (l = a.ajaxSettings.traditional);
		var k, p = this.formToArray(b.semantic);
		b.data && (b.extraData = b.data, k = a.param(b.data, l));
		if (b.beforeSubmit && !1 === b.beforeSubmit(p, this, b)) return c("ajaxSubmit: submit aborted via beforeSubmit callback"), this;
		this.trigger("form-submit-validate", [p, this, b, i]);
		if (i.veto) return c("ajaxSubmit: submit vetoed via form-submit-validate trigger"), this;
		i = a.param(p, l);
		k && (i = i ? i + "&" + k : k);
		"GET" == b.type.toUpperCase() ? (b.url += (0 <= b.url.indexOf("?") ? "&" : "?") + i, b.data = null) : b.data = i;
		var o = [];
		b.resetForm && o.push(function() {
			j.resetForm()
		});
		b.clearForm && o.push(function() {
			j.clearForm(b.includeHidden)
		});
		if (!b.dataType && b.target) {
			var s = b.success ||
			function() {};
			o.push(function(c) {
				var d = b.replaceTarget ? "replaceWith" : "html";
				a(b.target)[d](c).each(s, arguments)
			})
		} else b.success && o.push(b.success);
		b.success = function(a, c, d) {
			for (var f = b.context || b, i = 0, h = o.length; i < h; i++) o[i].apply(f, [a, c, d || j, j])
		};
		l = a("input:file:enabled[value]", this);
		k = 0 < l.length;
		i = "multipart/form-data" == j.attr("enctype") || "multipart/form-data" == j.attr("encoding");
		l = !(!k || !l.get(0).files || !window.FormData);
		c("fileAPI :" + l);
		!1 !== b.iframe && (b.iframe || (k || i) && !l) ? b.closeKeepAlive ? a.get(b.closeKeepAlive, function() {
			h(p)
		}) : h(p) : (k || i) && l ? (b.progress = b.progress || a.noop, d(p)) : a.ajax(b);
		this.trigger("form-submit-notify", [this, b]);
		return this
	};
	a.fn.ajaxForm = function(e) {
		e = e || {};
		e.delegation = e.delegation && a.isFunction(a.fn.on);
		if (!e.delegation && 0 === this.length) {
			var f = this.selector,
				h = this.context;
			if (!a.isReady && f) return c("DOM not ready, queuing ajaxForm"), a(function() {
				a(f, h).ajaxForm(e)
			}), this;
			c("terminating; zero elements found by selector" + (a.isReady ? "" : " (DOM not ready)"));
			return this
		}
		return e.delegation ? (a(document).off("submit.form-plugin", this.selector, b).off("click.form-plugin", this.selector, d).on("submit.form-plugin", this.selector, e, b).on("click.form-plugin", this.selector, e, d), this) : this.ajaxFormUnbind().bind("submit.form-plugin", e, b).bind("click.form-plugin", e, d)
	};
	a.fn.ajaxFormUnbind = function() {
		return this.unbind("submit.form-plugin click.form-plugin")
	};
	a.fn.formToArray = function(b) {
		var c = [];
		if (0 === this.length) return c;
		var d = this[0],
			g = b ? d.getElementsByTagName("*") : d.elements;
		if (!g) return c;
		var i, j, l, k, p, o;
		i = 0;
		for (p = g.length; i < p; i++) if (j = g[i], l = j.name) if (b && d.clk && "image" == j.type)!j.disabled && d.clk == j && (c.push({
			name: l,
			value: a(j).val(),
			type: j.type
		}), c.push({
			name: l + ".x",
			value: d.clk_x
		}, {
			name: l + ".y",
			value: d.clk_y
		}));
		else if ((k = a.fieldValue(j, !0)) && k.constructor == Array) {
			j = 0;
			for (o = k.length; j < o; j++) c.push({
				name: l,
				value: k[j]
			})
		} else null !== k && "undefined" != typeof k && c.push({
			name: l,
			value: k,
			type: j.type
		});
		if (!b && d.clk && (b = a(d.clk), g = b[0], (l = g.name) && !g.disabled && "image" == g.type)) c.push({
			name: l,
			value: b.val()
		}), c.push({
			name: l + ".x",
			value: d.clk_x
		}, {
			name: l + ".y",
			value: d.clk_y
		});
		return c
	};
	a.fn.formSerialize = function(b) {
		return a.param(this.formToArray(b))
	};
	a.fn.fieldSerialize = function(b) {
		var c = [];
		this.each(function() {
			var d = this.name;
			if (d) {
				var g = a.fieldValue(this, b);
				if (g && g.constructor == Array) for (var i = 0, j = g.length; i < j; i++) c.push({
					name: d,
					value: g[i]
				});
				else null !== g && "undefined" != typeof g && c.push({
					name: this.name,
					value: g
				})
			}
		});
		return a.param(c)
	};
	a.fn.fieldValue = function(b) {
		for (var c = [], d = 0, g = this.length; d < g; d++) {
			var i = a.fieldValue(this[d], b);
			null === i || ("undefined" == typeof i || i.constructor == Array && !i.length) || (i.constructor == Array ? a.merge(c, i) : c.push(i))
		}
		return c
	};
	a.fieldValue = function(b, c) {
		var d = b.name,
			g = b.type,
			i = b.tagName.toLowerCase();
		void 0 === c && (c = !0);
		if (c && (!d || b.disabled || "reset" == g || "button" == g || ("checkbox" == g || "radio" == g) && !b.checked || ("submit" == g || "image" == g) && b.form && b.form.clk != b || "select" == i && -1 == b.selectedIndex)) return null;
		if ("select" == i) {
			var j = b.selectedIndex;
			if (0 > j) return null;
			for (var d = [], i = b.options, l = (g = "select-one" == g) ? j + 1 : i.length, j = g ? j : 0; j < l; j++) {
				var k = i[j];
				if (k.selected) {
					var p = k.value;
					p || (p = k.attributes && k.attributes.value && !k.attributes.value.specified ? k.text : k.value);
					if (g) return p;
					d.push(p)
				}
			}
			return d
		}
		return a(b).val()
	};
	a.fn.clearForm = function(b) {
		return this.each(function() {
			a("input,select,textarea", this).clearFields(b)
		})
	};
	a.fn.clearFields = a.fn.clearInputs = function(a) {
		var b = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;
		return this.each(function() {
			var c = this.type,
				d = this.tagName.toLowerCase();
			b.test(c) || "textarea" == d || a && /hidden/.test(c) ? this.value = "" : "checkbox" == c || "radio" == c ? this.checked = !1 : "select" == d && (this.selectedIndex = -1)
		})
	};
	a.fn.resetForm = function() {
		return this.each(function() {
			("function" == typeof this.reset || "object" == typeof this.reset && !this.reset.nodeType) && this.reset()
		})
	};
	a.fn.enable = function(a) {
		void 0 === a && (a = !0);
		return this.each(function() {
			this.disabled = !a
		})
	};
	a.fn.selected = function(b) {
		void 0 === b && (b = !0);
		return this.each(function() {
			var c = this.type;
			"checkbox" == c || "radio" == c ? this.checked = b : "option" == this.tagName.toLowerCase() && (c = a(this).parent("select"), b && (c[0] && "select-one" == c[0].type) && c.find("option").selected(!1), this.selected = b)
		})
	};
	a.fn.ajaxSubmit.debug = !1
})(jQuery);
(function(a) {
	a.extend(a.fn, {
		validate: function(b) {
			if (this.length) {
				var d = a.data(this[0], "validator");
				if (d) return d;
				this.attr("novalidate", "novalidate");
				d = new a.validator(b, this[0]);
				a.data(this[0], "validator", d);
				d.settings.onsubmit && (b = this.find("input, button"), b.filter(".cancel").click(function() {
					d.cancelSubmit = !0
				}), d.settings.submitHandler && b.filter(":submit").click(function() {
					d.submitButton = this
				}), this.submit(function(b) {
					function e() {
						if (d.settings.submitHandler) {
							if (d.submitButton) var b = a("<input type='hidden'/>").attr("name", d.submitButton.name).val(d.submitButton.value).appendTo(d.currentForm);
							d.settings.submitHandler.call(d, d.currentForm);
							d.submitButton && b.remove();
							return !1
						}
						return !0
					}
					d.settings.debug && b.preventDefault();
					if (d.cancelSubmit) return d.cancelSubmit = !1, e();
					if (d.form()) return d.pendingRequest ? (d.formSubmitted = !0, !1) : e();
					d.focusInvalid();
					return !1
				}));
				return d
			}
			b && b.debug && window.console && console.warn("nothing selected, can't validate, returning nothing")
		},
		valid: function() {
			if (a(this[0]).is("form")) return this.validate().form();
			var b = !0,
				d = a(this[0].form).validate();
			this.each(function() {
				b &= d.element(this)
			});
			return b
		},
		removeAttrs: function(b) {
			var d = {},
				c = this;
			a.each(b.split(/\s/), function(a, b) {
				d[b] = c.attr(b);
				c.removeAttr(b)
			});
			return d
		},
		rules: function(b, d) {
			var c = this[0];
			if (b) {
				var e = a.data(c.form, "validator").settings,
					f = e.rules,
					h = a.validator.staticRules(c);
				switch (b) {
				case "add":
					a.extend(h, a.validator.normalizeRule(d));
					f[c.name] = h;
					d.messages && (e.messages[c.name] = a.extend(e.messages[c.name], d.messages));
					break;
				case "remove":
					if (!d) return delete f[c.name], h;
					var g = {};
					a.each(d.split(/\s/), function(a, b) {
						g[b] = h[b];
						delete h[b]
					});
					return g
				}
			}
			c = a.validator.normalizeRules(a.extend({}, a.validator.metadataRules(c), a.validator.classRules(c), a.validator.attributeRules(c), a.validator.staticRules(c)), c);
			c.required && (e = c.required, delete c.required, c = a.extend({
				required: e
			}, c));
			return c
		}
	});
	a.extend(a.expr[":"], {
		blank: function(b) {
			return !a.trim("" + b.value)
		},
		filled: function(b) {
			return !!a.trim("" + b.value)
		},
		unchecked: function(a) {
			return !a.checked
		}
	});
	a.validator = function(b, d) {
		this.settings = a.extend(!0, {}, a.validator.defaults, b);
		this.currentForm = d;
		this.init()
	};
	a.validator.format = function(b, d) {
		if (1 == arguments.length) return function() {
			var c = a.makeArray(arguments);
			c.unshift(b);
			return a.validator.format.apply(this, c)
		};
		2 < arguments.length && d.constructor != Array && (d = a.makeArray(arguments).slice(1));
		d.constructor != Array && (d = [d]);
		a.each(d, function(a, d) {
			b = b.replace(RegExp("\\{" + a + "\\}", "g"), d)
		});
		return b
	};
	a.extend(a.validator, {
		defaults: {
			messages: {},
			groups: {},
			rules: {},
			errorClass: "error",
			validClass: "valid",
			errorElement: "label",
			focusInvalid: !0,
			errorContainer: a([]),
			errorLabelContainer: a([]),
			onsubmit: !0,
			ignore: ":hidden",
			ignoreTitle: !1,
			onfocusin: function(a) {
				this.lastActive = a;
				this.settings.focusCleanup && !this.blockFocusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, a, this.settings.errorClass, this.settings.validClass), this.addWrapper(this.errorsFor(a)).hide())
			},
			onfocusout: function(a) {
				!this.checkable(a) && (a.name in this.submitted || !this.optional(a)) && this.element(a)
			},
			onkeyup: function(a) {
				(a.name in this.submitted || a == this.lastElement) && this.element(a)
			},
			onclick: function(a) {
				a.name in this.submitted ? this.element(a) : a.parentNode.name in this.submitted && this.element(a.parentNode)
			},
			highlight: function(b, d, c) {
				"radio" === b.type ? this.findByName(b.name).addClass(d).removeClass(c) : a(b).addClass(d).removeClass(c)
			},
			unhighlight: function(b, d, c) {
				"radio" === b.type ? this.findByName(b.name).removeClass(d).addClass(c) : a(b).removeClass(d).addClass(c)
			}
		},
		setDefaults: function(b) {
			a.extend(a.validator.defaults, b)
		},
		messages: {
			required: "This field is required.",
			remote: "Please fix this field.",
			email: "Please enter a valid email address.",
			url: "Please enter a valid URL.",
			date: "Please enter a valid date.",
			dateISO: "Please enter a valid date (ISO).",
			number: "Please enter a valid number.",
			digits: "Please enter only digits.",
			creditcard: "Please enter a valid credit card number.",
			equalTo: "Please enter the same value again.",
			accept: "Please enter a value with a valid extension.",
			maxlength: a.validator.format("Please enter no more than {0} characters."),
			minlength: a.validator.format("Please enter at least {0} characters."),
			rangelength: a.validator.format("Please enter a value between {0} and {1} characters long."),
			range: a.validator.format("Please enter a value between {0} and {1}."),
			max: a.validator.format("Please enter a value less than or equal to {0}."),
			min: a.validator.format("Please enter a value greater than or equal to {0}.")
		},
		autoCreateRanges: !1,
		prototype: {
			init: function() {
				function b(b) {
					var c = a.data(this[0].form, "validator"),
						d = "on" + b.type.replace(/^validate/, "");
					c.settings[d] && c.settings[d].call(c, this[0], b)
				}
				this.labelContainer = a(this.settings.errorLabelContainer);
				this.errorContext = this.labelContainer.length && this.labelContainer || a(this.currentForm);
				this.containers = a(this.settings.errorContainer).add(this.settings.errorLabelContainer);
				this.submitted = {};
				this.valueCache = {};
				this.pendingRequest = 0;
				this.pending = {};
				this.invalid = {};
				this.reset();
				var d = this.groups = {};
				a.each(this.settings.groups, function(b, c) {
					a.each(c.split(/\s/), function(a, c) {
						d[c] = b
					})
				});
				var c = this.settings.rules;
				a.each(c, function(b, d) {
					c[b] = a.validator.normalizeRule(d)
				});
				a(this.currentForm).validateDelegate("[type='text'], [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'] ", "focusin focusout keyup", b).validateDelegate("[type='radio'], [type='checkbox'], select, option", "click", b);
				this.settings.invalidHandler && a(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler)
			},
			form: function() {
				this.checkForm();
				a.extend(this.submitted, this.errorMap);
				this.invalid = a.extend({}, this.errorMap);
				this.valid() || a(this.currentForm).triggerHandler("invalid-form", [this]);
				this.showErrors();
				return this.valid()
			},
			checkForm: function() {
				this.prepareForm();
				for (var a = 0, d = this.currentElements = this.elements(); d[a]; a++) this.check(d[a]);
				return this.valid()
			},
			element: function(b) {
				this.lastElement = b = this.validationTargetFor(this.clean(b));
				this.prepareElement(b);
				this.currentElements = a(b);
				var d = this.check(b);
				d ? delete this.invalid[b.name] : this.invalid[b.name] = !0;
				this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers));
				this.showErrors();
				return d
			},
			showErrors: function(b) {
				if (b) {
					a.extend(this.errorMap, b);
					this.errorList = [];
					for (var d in b) this.errorList.push({
						message: b[d],
						element: this.findByName(d)[0]
					});
					this.successList = a.grep(this.successList, function(a) {
						return !(a.name in b)
					})
				}
				this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
			},
			resetForm: function() {
				a.fn.resetForm && a(this.currentForm).resetForm();
				this.submitted = {};
				this.lastElement = null;
				this.prepareForm();
				this.hideErrors();
				this.elements().removeClass(this.settings.errorClass)
			},
			numberOfInvalids: function() {
				return this.objectLength(this.invalid)
			},
			objectLength: function(a) {
				var d = 0,
					c;
				for (c in a) d++;
				return d
			},
			hideErrors: function() {
				this.addWrapper(this.toHide).hide()
			},
			valid: function() {
				return 0 == this.size()
			},
			size: function() {
				return this.errorList.length
			},
			focusInvalid: function() {
				if (this.settings.focusInvalid) try {
					a(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
				} catch (b) {}
			},
			findLastActive: function() {
				var b = this.lastActive;
				return b && 1 == a.grep(this.errorList, function(a) {
					return a.element.name == b.name
				}).length && b
			},
			elements: function() {
				var b = this,
					d = {};
				return a(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function() {
					!this.name && b.settings.debug && window.console && console.error("%o has no name assigned", this);
					return this.name in d || !b.objectLength(a(this).rules()) ? !1 : d[this.name] = !0
				})
			},
			clean: function(b) {
				return a(b)[0]
			},
			errors: function() {
				return a(this.settings.errorElement + "." + this.settings.errorClass, this.errorContext)
			},
			reset: function() {
				this.successList = [];
				this.errorList = [];
				this.errorMap = {};
				this.toShow = a([]);
				this.toHide = a([]);
				this.currentElements = a([])
			},
			prepareForm: function() {
				this.reset();
				this.toHide = this.errors().add(this.containers)
			},
			prepareElement: function(a) {
				this.reset();
				this.toHide = this.errorsFor(a)
			},
			check: function(b) {
				var b = this.validationTargetFor(this.clean(b)),
					d = a(b).rules(),
					c = !1,
					e;
				for (e in d) {
					var f = {
						method: e,
						parameters: d[e]
					};
					try {
						var h = a.validator.methods[e].call(this, b.value.replace(/\r/g, ""), b, f.parameters);
						if ("dependency-mismatch" == h) c = !0;
						else {
							c = !1;
							if ("pending" == h) {
								this.toHide = this.toHide.not(this.errorsFor(b));
								return
							}
							if (!h) return this.formatAndAdd(b, f), !1
						}
					} catch (g) {
						throw this.settings.debug && window.console && console.log("exception occured when checking element " + b.id + ", check the '" + f.method + "' method", g), g;
					}
				}
				if (!c) return this.objectLength(d) && this.successList.push(b), !0
			},
			customMetaMessage: function(b, d) {
				if (a.metadata) {
					var c = this.settings.meta ? a(b).metadata()[this.settings.meta] : a(b).metadata();
					return c && c.messages && c.messages[d]
				}
			},
			customMessage: function(a, d) {
				var c = this.settings.messages[a];
				return c && (c.constructor == String ? c : c[d])
			},
			findDefined: function() {
				for (var a = 0; a < arguments.length; a++) if (void 0 !== arguments[a]) return arguments[a]
			},
			defaultMessage: function(b, d) {
				return this.findDefined(this.customMessage(b.name, d), this.customMetaMessage(b, d), !this.settings.ignoreTitle && b.title || void 0, a.validator.messages[d], "<strong>Warning: No message defined for " + b.name + "</strong>")
			},
			formatAndAdd: function(a, d) {
				var c = this.defaultMessage(a, d.method),
					e = /\$?\{(\d+)\}/g;
				"function" == typeof c ? c = c.call(this, d.parameters, a) : e.test(c) && (c = jQuery.format(c.replace(e, "{$1}"), d.parameters));
				this.errorList.push({
					message: c,
					element: a
				});
				this.errorMap[a.name] = c;
				this.submitted[a.name] = c
			},
			addWrapper: function(a) {
				this.settings.wrapper && (a = a.add(a.parent(this.settings.wrapper)));
				return a
			},
			defaultShowErrors: function() {
				for (var a = 0; this.errorList[a]; a++) {
					var d = this.errorList[a];
					this.settings.highlight && this.settings.highlight.call(this, d.element, this.settings.errorClass, this.settings.validClass);
					this.showLabel(d.element, d.message)
				}
				this.errorList.length && (this.toShow = this.toShow.add(this.containers));
				if (this.settings.success) for (a = 0; this.successList[a]; a++) this.showLabel(this.successList[a]);
				if (this.settings.unhighlight) {
					a = 0;
					for (d = this.validElements(); d[a]; a++) this.settings.unhighlight.call(this, d[a], this.settings.errorClass, this.settings.validClass)
				}
				this.toHide = this.toHide.not(this.toShow);
				this.hideErrors();
				this.addWrapper(this.toShow).show()
			},
			validElements: function() {
				return this.currentElements.not(this.invalidElements())
			},
			invalidElements: function() {
				return a(this.errorList).map(function() {
					return this.element
				})
			},
			showLabel: function(b, d) {
				var c = this.errorsFor(b);
				c.length ? (c.removeClass(this.settings.validClass).addClass(this.settings.errorClass), c.attr("generated") && c.html(d)) : (c = a("<" + this.settings.errorElement + "/>").attr({
					"for": this.idOrName(b),
					generated: !0
				}).addClass(this.settings.errorClass).html(d || ""), this.settings.wrapper && (c = c.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()), this.labelContainer.append(c).length || (this.settings.errorPlacement ? this.settings.errorPlacement(c, a(b)) : c.insertAfter(b)));
				!d && this.settings.success && (c.text(""), "string" == typeof this.settings.success ? c.addClass(this.settings.success) : this.settings.success(c));
				this.toShow = this.toShow.add(c)
			},
			errorsFor: function(b) {
				var d = this.idOrName(b);
				return this.errors().filter(function() {
					return a(this).attr("for") == d
				})
			},
			idOrName: function(a) {
				return this.groups[a.name] || (this.checkable(a) ? a.name : a.id || a.name)
			},
			validationTargetFor: function(a) {
				this.checkable(a) && (a = this.findByName(a.name).not(this.settings.ignore)[0]);
				return a
			},
			checkable: function(a) {
				return /radio|checkbox/i.test(a.type)
			},
			findByName: function(b) {
				var d = this.currentForm;
				return a(document.getElementsByName(b)).map(function(a, e) {
					return e.form == d && e.name == b && e || null
				})
			},
			getLength: function(b, d) {
				switch (d.nodeName.toLowerCase()) {
				case "select":
					return a("option:selected", d).length;
				case "input":
					if (this.checkable(d)) return this.findByName(d.name).filter(":checked").length
				}
				return b.length
			},
			depend: function(a, d) {
				return this.dependTypes[typeof a] ? this.dependTypes[typeof a](a, d) : !0
			},
			dependTypes: {
				"boolean": function(a) {
					return a
				},
				string: function(b, d) {
					return !!a(b, d.form).length
				},
				"function": function(a, d) {
					return a(d)
				}
			},
			optional: function(b) {
				return !a.validator.methods.required.call(this, a.trim(b.value), b) && "dependency-mismatch"
			},
			startRequest: function(a) {
				this.pending[a.name] || (this.pendingRequest++, this.pending[a.name] = !0)
			},
			stopRequest: function(b, d) {
				this.pendingRequest--;
				0 > this.pendingRequest && (this.pendingRequest = 0);
				delete this.pending[b.name];
				d && 0 == this.pendingRequest && this.formSubmitted && this.form() ? (a(this.currentForm).submit(), this.formSubmitted = !1) : !d && (0 == this.pendingRequest && this.formSubmitted) && (a(this.currentForm).triggerHandler("invalid-form", [this]), this.formSubmitted = !1)
			},
			previousValue: function(b) {
				return a.data(b, "previousValue") || a.data(b, "previousValue", {
					old: null,
					valid: !0,
					message: this.defaultMessage(b, "remote")
				})
			}
		},
		classRuleSettings: {
			required: {
				required: !0
			},
			email: {
				email: !0
			},
			url: {
				url: !0
			},
			date: {
				date: !0
			},
			dateISO: {
				dateISO: !0
			},
			dateDE: {
				dateDE: !0
			},
			number: {
				number: !0
			},
			numberDE: {
				numberDE: !0
			},
			digits: {
				digits: !0
			},
			creditcard: {
				creditcard: !0
			}
		},
		addClassRules: function(b, d) {
			b.constructor == String ? this.classRuleSettings[b] = d : a.extend(this.classRuleSettings, b)
		},
		classRules: function(b) {
			var d = {};
			(b = a(b).attr("class")) && a.each(b.split(" "), function() {
				this in a.validator.classRuleSettings && a.extend(d, a.validator.classRuleSettings[this])
			});
			return d
		},
		attributeRules: function(b) {
			var d = {},
				b = a(b),
				c;
			for (c in a.validator.methods) {
				var e;
				(e = "required" === c && "function" === typeof a.fn.prop ? b.prop(c) : b.attr(c)) ? d[c] = e : b[0].getAttribute("type") === c && (d[c] = !0)
			}
			d.maxlength && /-1|2147483647|524288/.test(d.maxlength) && delete d.maxlength;
			return d
		},
		metadataRules: function(b) {
			if (!a.metadata) return {};
			var d = a.data(b.form, "validator").settings.meta;
			return d ? a(b).metadata()[d] : a(b).metadata()
		},
		staticRules: function(b) {
			var d = {},
				c = a.data(b.form, "validator");
			c.settings.rules && (d = a.validator.normalizeRule(c.settings.rules[b.name]) || {});
			return d
		},
		normalizeRules: function(b, d) {
			a.each(b, function(c, e) {
				if (!1 === e) delete b[c];
				else if (e.param || e.depends) {
					var f = !0;
					switch (typeof e.depends) {
					case "string":
						f = !! a(e.depends, d.form).length;
						break;
					case "function":
						f = e.depends.call(d, d)
					}
					f ? b[c] = void 0 !== e.param ? e.param : !0 : delete b[c]
				}
			});
			a.each(b, function(c, e) {
				b[c] = a.isFunction(e) ? e(d) : e
			});
			a.each(["minlength", "maxlength", "min", "max"], function() {
				b[this] && (b[this] = Number(b[this]))
			});
			a.each(["rangelength", "range"], function() {
				b[this] && (b[this] = [Number(b[this][0]), Number(b[this][1])])
			});
			if (a.validator.autoCreateRanges && (b.min && b.max && (b.range = [b.min, b.max], delete b.min, delete b.max), b.minlength && b.maxlength)) b.rangelength = [b.minlength, b.maxlength], delete b.minlength, delete b.maxlength;
			b.messages && delete b.messages;
			return b
		},
		normalizeRule: function(b) {
			if ("string" == typeof b) {
				var d = {};
				a.each(b.split(/\s/), function() {
					d[this] = !0
				});
				b = d
			}
			return b
		},
		addMethod: function(b, d, c) {
			a.validator.methods[b] = d;
			a.validator.messages[b] = void 0 != c ? c : a.validator.messages[b];
			3 > d.length && a.validator.addClassRules(b, a.validator.normalizeRule(b))
		},
		methods: {
			required: function(b, d, c) {
				if (!this.depend(c, d)) return "dependency-mismatch";
				switch (d.nodeName.toLowerCase()) {
				case "select":
					return (b = a(d).val()) && 0 < b.length;
				case "input":
					if (this.checkable(d)) return 0 < this.getLength(b, d);
				default:
					return 0 < a.trim(b).length
				}
			},
			remote: function(b, d, c) {
				if (this.optional(d)) return "dependency-mismatch";
				var e = this.previousValue(d);
				this.settings.messages[d.name] || (this.settings.messages[d.name] = {});
				e.originalMessage = this.settings.messages[d.name].remote;
				this.settings.messages[d.name].remote = e.message;
				c = "string" == typeof c && {
					url: c
				} || c;
				if (this.pending[d.name]) return "pending";
				if (e.old === b) return e.valid;
				e.old = b;
				var f = this;
				this.startRequest(d);
				var h = {};
				h[d.name] = b;
				a.ajax(a.extend(!0, {
					url: c,
					mode: "abort",
					port: "validate" + d.name,
					dataType: "json",
					data: h,
					success: function(c) {
						f.settings.messages[d.name].remote = e.originalMessage;
						var i = c === true;
						if (i) {
							var h = f.formSubmitted;
							f.prepareElement(d);
							f.formSubmitted = h;
							f.successList.push(d);
							f.showErrors()
						} else {
							h = {};
							c = c || f.defaultMessage(d, "remote");
							h[d.name] = e.message = a.isFunction(c) ? c(b) : c;
							f.showErrors(h)
						}
						e.valid = i;
						f.stopRequest(d, i)
					}
				}, c));
				return "pending"
			},
			minlength: function(b, d, c) {
				return this.optional(d) || this.getLength(a.trim(b), d) >= c
			},
			maxlength: function(b, d, c) {
				return this.optional(d) || this.getLength(a.trim(b), d) <= c
			},
			rangelength: function(b, d, c) {
				b = this.getLength(a.trim(b), d);
				return this.optional(d) || b >= c[0] && b <= c[1]
			},
			min: function(a, d, c) {
				return this.optional(d) || a >= c
			},
			max: function(a, d, c) {
				return this.optional(d) || a <= c
			},
			range: function(a, d, c) {
				return this.optional(d) || a >= c[0] && a <= c[1]
			},
			email: function(a, d) {
				return this.optional(d) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(a)
			},
			url: function(a, d) {
				return this.optional(d) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)
			},
			date: function(a, d) {
				return this.optional(d) || !/Invalid|NaN/.test(new Date(a))
			},
			dateISO: function(a, d) {
				return this.optional(d) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(a)
			},
			number: function(a, d) {
				return this.optional(d) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(a)
			},
			digits: function(a, d) {
				return this.optional(d) || /^\d+$/.test(a)
			},
			creditcard: function(a, d) {
				if (this.optional(d)) return "dependency-mismatch";
				if (/[^0-9 -]+/.test(a)) return !1;
				for (var c = 0, e = 0, f = !1, a = a.replace(/\D/g, ""), h = a.length - 1; 0 <= h; h--) {
					e = a.charAt(h);
					e = parseInt(e, 10);
					if (f && 9 < (e *= 2)) e -= 9;
					c += e;
					f = !f
				}
				return 0 == c % 10
			},
			accept: function(a, d, c) {
				c = "string" == typeof c ? c.replace(/,/g, "|") : "png|jpe?g|gif";
				return this.optional(d) || a.match(RegExp(".(" + c + ")$", "i"))
			},
			equalTo: function(b, d, c) {
				c = a(c).unbind(".validate-equalTo").bind("blur.validate-equalTo", function() {
					a(d).valid()
				});
				return b == c.val()
			}
		}
	});
	a.format = a.validator.format
})(jQuery);
(function(a) {
	var b = {};
	if (a.ajaxPrefilter) a.ajaxPrefilter(function(a, d, f) {
		d = a.port;
		"abort" == a.mode && (b[d] && b[d].abort(), b[d] = f)
	});
	else {
		var d = a.ajax;
		a.ajax = function(c) {
			var e = ("port" in c ? c : a.ajaxSettings).port;
			return "abort" == ("mode" in c ? c : a.ajaxSettings).mode ? (b[e] && b[e].abort(), b[e] = d.apply(this, arguments)) : d.apply(this, arguments)
		}
	}
})(jQuery);
(function(a) {
	!jQuery.event.special.focusin && !jQuery.event.special.focusout && document.addEventListener && a.each({
		focus: "focusin",
		blur: "focusout"
	}, function(b, d) {
		function c(b) {
			b = a.event.fix(b);
			b.type = d;
			return a.event.handle.call(this, b)
		}
		a.event.special[d] = {
			setup: function() {
				this.addEventListener(b, c, !0)
			},
			teardown: function() {
				this.removeEventListener(b, c, !0)
			},
			handler: function(b) {
				arguments[0] = a.event.fix(b);
				arguments[0].type = d;
				return a.event.handle.apply(this, arguments)
			}
		}
	});
	a.extend(a.fn, {
		validateDelegate: function(b, d, c) {
			return this.bind(d, function(d) {
				var f = a(d.target);
				if (f.is(b)) return c.apply(f, arguments)
			})
		}
	})
})(jQuery);
eval(function(a, b, d, c, e) {
	for (e = function(a) {
		return (a < b ? "" : e(parseInt(a / b))) + (35 < (a %= b) ? String.fromCharCode(a + 29) : a.toString(36))
	}; d--;) c[d] && (a = a.replace(RegExp("\\b" + e(d) + "\\b", "g"), c[d]));
	return a
}('(m($){1k W=2v.4N,D=2v.4M,F=2v.4L,u=2v.4K;m V(){C $("<4J/>")};$.N=m(T,c){1k O=$(T),2E,A=V(),1i=V(),I=V().r(V()).r(V()).r(V()),B=V().r(V()).r(V()).r(V()),E=$([]),1J,G,l,17={v:0,l:0},Q,M,1j,1f={v:0,l:0},12=0,1I="1G",2k,2j,1s,1r,S,1A,1z,2o,2n,14,1O,a,b,j,g,f={a:0,b:0,j:0,g:0,H:0,L:0},2u=R.4I,$p,d,i,o,w,h,2p;m 1m(x){C x+17.v-1f.v};m 1l(y){C y+17.l-1f.l};m 1a(x){C x-17.v+1f.v};m 19(y){C y-17.l+1f.l};m 1y(3H){C 3H.4H-1f.v};m 1x(3G){C 3G.4G-1f.l};m 13(30){1k 1h=30||1s,1g=30||1r;C{a:u(f.a*1h),b:u(f.b*1g),j:u(f.j*1h),g:u(f.g*1g),H:u(f.j*1h)-u(f.a*1h),L:u(f.g*1g)-u(f.b*1g)}};m 23(a,b,j,g,2Z){1k 1h=2Z||1s,1g=2Z||1r;f={a:u(a/1h||0),b:u(b/1g||0),j:u(j/1h||0),g:u(g/1g||0)};f.H=f.j-f.a;f.L=f.g-f.b};m 1e(){9(!O.H()){C}17={v:u(O.2t().v),l:u(O.2t().l)};Q=O.2X();M=O.3F();17.l+=(O.2Y()-M)>>1;17.v+=(O.2q()-Q)>>1;1A=u(c.4F/1s)||0;1z=u(c.4E/1r)||0;2o=u(F(c.4D/1s||1<<24,Q));2n=u(F(c.4C/1r||1<<24,M));9($().4B=="1.3.2"&&1I=="21"&&!2u["4A"]){17.l+=D(R.1p.2r,2u.2r);17.v+=D(R.1p.2s,2u.2s)}1f=/1G|4z/.1V(1j.q("1o"))?{v:u(1j.2t().v)-1j.2s(),l:u(1j.2t().l)-1j.2r()}:1I=="21"?{v:$(R).2s(),l:$(R).2r()}:{v:0,l:0};G=1m(0);l=1l(0);9(f.j>Q||f.g>M){1S()}};m 1T(3D){9(!1O){C}A.q({v:1m(f.a),l:1l(f.b)}).r(1i).H(w=f.H).L(h=f.L);1i.r(I).r(E).q({v:0,l:0});I.H(D(w-I.2q()+I.2X(),0)).L(D(h-I.2Y()+I.3F(),0));$(B[0]).q({v:G,l:l,H:f.a,L:M});$(B[1]).q({v:G+f.a,l:l,H:w,L:f.b});$(B[2]).q({v:G+f.j,l:l,H:Q-f.j,L:M});$(B[3]).q({v:G+f.a,l:l+f.g,H:w,L:M-f.g});w-=E.2q();h-=E.2Y();2N(E.3b){15 8:$(E[4]).q({v:w>>1});$(E[5]).q({v:w,l:h>>1});$(E[6]).q({v:w>>1,l:h});$(E[7]).q({l:h>>1});15 4:E.3E(1,3).q({v:w});E.3E(2,4).q({l:h})}9(3D!==Y){9($.N.1Z!=2Q){$(R).U($.N.1Z,$.N.3C)}9(c.1R){$(R)[$.N.1Z]($.N.3C=2Q)}}9($.1b.1E&&I.2q()-I.2X()==2){I.q("3B",0);3u(m(){I.q("3B","4y")},0)}};m 22(3A){1e();1T(3A);a=1m(f.a);b=1l(f.b);j=1m(f.j);g=1l(f.g)};m 27(2W,2w){c.1N?2W.4x(c.1N,2w):2W.1q()};m 1c(2V){1k x=1a(1y(2V))-f.a,y=19(1x(2V))-f.b;9(!2p){1e();2p=11;A.1F("4w",m(){2p=Y})}S="";9(c.2C){9(y<=c.1U){S="n"}X{9(y>=f.L-c.1U){S="s"}}9(x<=c.1U){S+="w"}X{9(x>=f.H-c.1U){S+="e"}}}A.q("2U",S?S+"-18":c.26?"4v":"");9(1J){1J.4u()}};m 2R(4t){$("1p").q("2U","");9(c.4s||f.H*f.L==0){27(A.r(B),m(){$(J).1q()})}$(R).U("P",2l);A.P(1c);c.2f(T,13())};m 2B(1W){9(1W.3w!=1){C Y}1e();9(S){$("1p").q("2U",S+"-18");a=1m(f[/w/.1V(S)?"j":"a"]);b=1l(f[/n/.1V(S)?"g":"b"]);$(R).P(2l).1F("1w",2R);A.U("P",1c)}X{9(c.26){2k=G+f.a-1y(1W);2j=l+f.b-1x(1W);A.U("P",1c);$(R).P(2S).1F("1w",m(){c.2f(T,13());$(R).U("P",2S);A.P(1c)})}X{O.1M(1W)}}C Y};m 1v(3z){9(14){9(3z){j=D(G,F(G+Q,a+W(g-b)*14*(j>a||-1)));g=u(D(l,F(l+M,b+W(j-a)/14*(g>b||-1))));j=u(j)}X{g=D(l,F(l+M,b+W(j-a)/14*(g>b||-1)));j=u(D(G,F(G+Q,a+W(g-b)*14*(j>a||-1))));g=u(g)}}};m 1S(){a=F(a,G+Q);b=F(b,l+M);9(W(j-a)<1A){j=a-1A*(j<a||-1);9(j<G){a=G+1A}X{9(j>G+Q){a=G+Q-1A}}}9(W(g-b)<1z){g=b-1z*(g<b||-1);9(g<l){b=l+1z}X{9(g>l+M){b=l+M-1z}}}j=D(G,F(j,G+Q));g=D(l,F(g,l+M));1v(W(j-a)<W(g-b)*14);9(W(j-a)>2o){j=a-2o*(j<a||-1);1v()}9(W(g-b)>2n){g=b-2n*(g<b||-1);1v(11)}f={a:1a(F(a,j)),j:1a(D(a,j)),b:19(F(b,g)),g:19(D(b,g)),H:W(j-a),L:W(g-b)};1T();c.2g(T,13())};m 2l(2T){j=/w|e|^$/.1V(S)||14?1y(2T):1m(f.j);g=/n|s|^$/.1V(S)||14?1x(2T):1l(f.g);1S();C Y};m 1u(3y,3x){j=(a=3y)+f.H;g=(b=3x)+f.L;$.2c(f,{a:1a(a),b:19(b),j:1a(j),g:19(g)});1T();c.2g(T,13())};m 2S(2m){a=D(G,F(2k+1y(2m),G+Q-f.H));b=D(l,F(2j+1x(2m),l+M-f.L));1u(a,b);2m.4r();C Y};m 2h(){$(R).U("P",2h);1e();j=a;g=b;1S();S="";9(!B.2y(":4q")){A.r(B).1q().2D(c.1N||0)}1O=11;$(R).U("1w",1L).P(2l).1F("1w",2R);A.U("P",1c);c.3v(T,13())};m 1L(){$(R).U("P",2h).U("1w",1L);27(A.r(B));23(1a(a),19(b),1a(a),19(b));9(!J 4p $.N){c.2g(T,13());c.2f(T,13())}};m 2z(2i){9(2i.3w!=1||B.2y(":4o")){C Y}1e();2k=a=1y(2i);2j=b=1x(2i);$(R).P(2h).1w(1L);C Y};m 2A(){22(Y)};m 2x(){2E=11;25(c=$.2c({1Q:"4n",26:11,20:"1p",2C:11,1U:10,3t:m(){},3v:m(){},2g:m(){},2f:m(){}},c));A.r(B).q({36:""});9(c.2F){1O=11;1e();1T();A.r(B).1q().2D(c.1N||0)}3u(m(){c.3t(T,13())},0)};1k 2Q=m(16){1k k=c.1R,d,t,2M=16.4m;d=!1K(k.2O)&&(16.2e||16.3q.2e)?k.2O:!1K(k.2a)&&16.3r?k.2a:!1K(k.2b)&&16.3s?k.2b:!1K(k.2P)?k.2P:10;9(k.2P=="18"||(k.2b=="18"&&16.3s)||(k.2a=="18"&&16.3r)||(k.2O=="18"&&(16.2e||16.3q.2e))){2N(2M){15 37:d=-d;15 39:t=D(a,j);a=F(a,j);j=D(t+d,a);1v();1t;15 38:d=-d;15 40:t=D(b,g);b=F(b,g);g=D(t+d,b);1v(11);1t;3p:C}1S()}X{a=F(a,j);b=F(b,g);2N(2M){15 37:1u(D(a-d,G),b);1t;15 38:1u(a,D(b-d,l));1t;15 39:1u(a+F(d,Q-1a(j)),b);1t;15 40:1u(a,b+F(d,M-19(g)));1t;3p:C}}C Y};m 1P(3o,2L){3m(2d 4l 2L){9(c[2d]!==1X){3o.q(2L[2d],c[2d])}}};m 25(K){9(K.20){(1j=$(K.20)).3g(A.r(B))}$.2c(c,K);1e();9(K.2K!=3n){E.1n();E=$([]);i=K.2K?K.2K=="4k"?4:8:0;3c(i--){E=E.r(V())}E.29(c.1Q+"-4j").q({1o:"1G",34:0,1H:12+1||1});9(!4i(E.q("H"))>=0){E.H(5).L(5)}9(o=c.2J){E.q({2J:o,2G:"3j"})}1P(E,{3k:"2I-28",3i:"2H-28",3l:"1d"})}1s=c.4h/Q||1;1r=c.4g/M||1;9(K.a!=3n){23(K.a,K.b,K.j,K.g);K.2F=!K.1q}9(K.1R){c.1R=$.2c({2b:1,2a:"18"},K.1R)}B.29(c.1Q+"-4f");1i.29(c.1Q+"-4e");3m(i=0;i++<4;){$(I[i-1]).29(c.1Q+"-2I"+i)}1P(1i,{4d:"2H-28",4c:"1d"});1P(I,{3l:"1d",2J:"2I-H"});1P(B,{4b:"2H-28",4a:"1d"});9(o=c.3k){$(I[0]).q({2G:"3j",3h:o})}9(o=c.3i){$(I[1]).q({2G:"49",3h:o})}A.3g(1i.r(I).r(1J).r(E));9($.1b.1E){9(o=B.q("3f").3e(/1d=(\\d+)/)){B.q("1d",o[1]/1Y)}9(o=I.q("3f").3e(/1d=(\\d+)/)){I.q("1d",o[1]/1Y)}}9(K.1q){27(A.r(B))}X{9(K.2F&&2E){1O=11;A.r(B).2D(c.1N||0);22()}}14=(d=(c.48||"").47(/:/))[0]/d[1];O.r(B).U("1M",2z);9(c.1D||c.1C===Y){A.U("P",1c).U("1M",2B);$(3d).U("18",2A)}X{9(c.1C||c.1D===Y){9(c.2C||c.26){A.P(1c).1M(2B)}$(3d).18(2A)}9(!c.46){O.r(B).1M(2z)}}c.1C=c.1D=1X};J.1n=m(){25({1D:11});A.r(B).1n()};J.45=m(){C c};J.31=25;J.44=13;J.43=23;J.42=1L;J.41=22;$p=O;3c($p.3b){12=D(12,!1K($p.q("z-3a"))?$p.q("z-3a"):12);9($p.q("1o")=="21"){1I="21"}$p=$p.20(":3Z(1p)")}12=c.1H||12;9($.1b.1E){O.3Y("3X","3W")}$.N.1Z=$.1b.1E||$.1b.3V?"3U":"3T";9($.1b.3S){1J=V().q({H:"1Y%",L:"1Y%",1o:"1G",1H:12+2||2})}A.r(B).q({36:"35",1o:1I,3R:"35",1H:12||"0"});A.q({1H:12+2||2});1i.r(I).q({1o:"1G",34:0});T.33||T.3Q=="33"||!O.2y("3P")?2x():O.1F("3O",2x);9($.1b.1E&&$.1b.3N>=7){T.32=T.32}};$.2w.N=m(Z){Z=Z||{};J.3M(m(){9($(J).1B("N")){9(Z.1n){$(J).1B("N").1n();$(J).3L("N")}X{$(J).1B("N").31(Z)}}X{9(!Z.1n){9(Z.1C===1X&&Z.1D===1X){Z.1C=11}$(J).1B("N",3K $.N(J,Z))}}});9(Z.3J){C $(J).1B("N")}C J}})(3I);', 62, 298, "         if x1 y1 _7   _23 y2   x2  top function    css add   _4 left     _a _d return _2 _e _3 _10 width _c this _53 height _13 imgAreaSelect _8 mousemove _12 document _1c _6 unbind _5 _1 else false _54  true _16 _2c _21 case _4f _11 resize _29 _28 browser _39 opacity _30 _15 sy sx _b _14 var _27 _26 remove position body hide _1b _1a break _44 _41 mouseup evY evX _1e _1d data enable disable msie one absolute zIndex _17 _f isNaN _49 mousedown fadeSpeed _22 _50 classPrefix keys _31 _32 resizeMargin test _3f undefined 100 keyPress parent fixed _35 _2e  _4e movable _37 color addClass ctrl shift extend option altKey onSelectEnd onSelectChange _48 _4b _19 _18 _3d _47 _20 _1f _25 outerWidth scrollTop scrollLeft offset _24 Math fn _4d is _4a _4c _3e resizable fadeIn _9 show borderStyle background border borderWidth handles _52 key switch alt arrows _34 _3b _40 _43 cursor _3a _38 innerWidth outerHeight _2f _2d setOptions src complete fontSize hidden visibility    index length while window match filter append borderColor borderColor2 solid borderColor1 borderOpacity for null _51 default originalEvent ctrlKey shiftKey onInit setTimeout onSelectStart which _46 _45 _42 _36 margin onKeyPress _33 slice innerHeight _2b _2a jQuery instance new removeData each version load img readyState overflow opera keypress keydown safari on unselectable attr not  update cancelSelection setSelection getSelection getOptions persistent split aspectRatio dashed outerOpacity outerColor selectionOpacity selectionColor selection outer imageHeight imageWidth parseInt handle corners in keyCode imgareaselect animated instanceof visible preventDefault autoHide _3c toggle move mouseout fadeOut auto relative getBoundingClientRect jquery maxHeight maxWidth minHeight minWidth pageY pageX documentElement div round min max abs".split(" ")));

function TWinOpen(a, b, d, c) {
	TWin = window.showModalDialog(a, null, "dialogWidth=" + d + "px;dialogHeight=" + c + "px;dialogTop=" + (screen.height - 30 - c) / 2 + "px;dialogLeft=" + (screen.width - 10 - d) / 2 + "px")
}
function iFrameHeight(a) {
	var b = $(a),
		a = document.frames ? document.frames[a].document : b.contentDocument;
	null != b && null != a && (b.height = a.body.scrollHeight)
}
function random(a) {
	for (var b = "", d, c = 1; c <= a; c++) d = parseInt(35 * Math.random()), b += "ab1cd2ef3gh4ij5kl6mn7opq8rst9uvw0xyz".charAt(d);
	return b
}

function mb_cutstr(a, b, d) {
	for (var c = 0, e = "", d = "", b = b - d.length, f = 0; f < a.length; f++) {
		c++;
		127 < a.charCodeAt(f) && c++;
		if (c > b) {
			e += d;
			break
		}
		e += a.substr(f, 1)
	}
	return e
}
function strLenCalc(a, b, d) {
	var c = a.value,
		e = str_length(c);
	d >= e ? $("#" + b).html(d - e) : a.value = mb_cutstr(c, d, 0)
}
function getTip(a) {
	var b = $("#data-tips").attr("data-" + a);
	return null != b && void 0 != b && "" !== b ? b : a
}
function check_message() {
	return !0
}

function show_message(a, b, d, c) {
	var e = $.oDialog("messageDialog", {
		id: "ui_message_dialog",
		width: "100",
		center: !0,
		mask: !1,
		close: !d,
		className: "ui-message",
		tpl: '<div id="%ID" class="%CLASSNAME"><a class="ui-close" href="#close">X</a><div class="bd"><div class="ui-loading">\u6b63\u5728\u52a0\u8f7d...</div></div></div>'
	}),
		f = 8 * str_length(b) + 30,
		f = 500 < f ? 500 : f;
	e.width(170 > f ? 170 : f).body(b).show();
	e.elem.addClass("message-" + a);
	e.show();
	d && setTimeout(function() {
		e.hide()
	}, c)
}

function show_loading(a) {
	var b = $.oDialog("loadingDialog", {
		id: "ui_loading_dialog",
		width: "250",
		center: !0,
		mask: !1,
		close: !1,
		className: "ui-message",
		tpl: '<div id="%ID" class="%CLASSNAME"><a class="ui-close" href="#close">X</a><div class="bd progress progress-striped active"><div class="bar" style="width:20%;"></div></div></div>'
	});
	b.width(280).show();
	b.elem.addClass("progress-" + a);
	b.show()
}

function hide_loading() {
	var a = $.oDialog("loadingDialog");
	setTimeout(function() {
		a.elem.find(".bar").css("width", "100%")
	}, 500);
	setTimeout(function() {
		a.hide();
		a.elem.find(".bar").css("width", "20%")
	}, 1E3)
}
function str_length(a) {
	for (var b = a.length, d = 0; d < a.length; d++) 127 < a.charCodeAt(d) && b++;
	return b
}
var imageReady = function() {
		var a = [],
			b = null,
			d = [
				["width", "height"],
				["naturalWidth", "naturalHeight"]
			],
			c = Number("number" === typeof document.createElement("img").naturalHeight),
			e = function() {
				for (var c = 0; c < a.length; c++) a[c].end ? a.splice(c--, 1) : f.call(a[c], null);
				a.length && (b = setTimeout(e, 50)) || (b = null)
			},
			f = function() {
				if (this[d[c][0]] !== this.__width || this[d[c][1]] !== this.__height || 1024 < this[d[c][0]] * this[d[c][1]]) this.onready.call(this, null), this.end = !0
			};
		return function(h, g, i, j, l) {
			var g = g || "",
				i = i || new Function(g),
				j = j || new Function(g),
				l = l || new Function(g),
				k = "string" == typeof h ? new Image : h;
			k.onerror = function() {
				l.call(k, g);
				k.end = !0;
				k = k.onload = k.onerror = k.onreadystatechange = null
			};
			"string" == typeof h && (k.src = h);
			k && (k.__width = k[d[c][0]], k.__height = k[d[c][1]], k.complete ? (i.call(k, g), j.call(k, g)) : (k.onready = function() {
				i.call(k, g)
			}, f.call(k, null), k.onload = k.onreadystatechange = function() {
				if (!k || !k.readyState || !(k.readyState != "loaded" && k.readyState != "complete")) {
					!k.end && f.call(k, null);
					j.call(k, g);
					k = k.onload = k.onerror = k.onreadystatechange = null
				}
			}, k.end || (a.push(k), null === b && (b = setTimeout(e, 50)))))
		}
	}();

function preloadImages(a, b, d, c, e) {
	var e = e || new Function,
		b = b || new Function,
		c = c || new Function,
		d = d || new Function,
		f = a.length;
	0 === f && e.call(a, null);
	for (var h = 0; h < f; h++) imageReady(a[h], function() {
		b.call(this, null);
		h == f && e.call(a, null)
	}, function() {
		d.call(this, null)
	}, function() {
		c.call(this, null);
		h == f && e.call(a, null)
	})
}
$.fn.extend({
	insertAtCaret: function(a, b) {
		return this.each(function() {
			if (document.selection) this.focus(), sel = document.selection.createRange(), sel.text = a, b && (sel.moveStart("character", -(a.length - 1)), sel.moveEnd("character", -1), sel.select());
			else if (this.selectionStart || "0" == this.selectionStart) {
				var d = this.selectionStart,
					c = this.selectionEnd,
					e = this.scrollTop;
				this.value = this.value.substring(0, d) + a + this.value.substring(c, this.value.length);
				this.focus();
				b ? (this.selectionStart = d + 1, this.selectionEnd = d + a.length - 1) : (this.selectionStart = d + a.length, this.selectionEnd = d + a.length);
				this.scrollTop = e
			} else this.value += a, this.focus()
		})
	}
});

function utf8_encode(a) {
	if (null === a || "undefined" === typeof a) return "";
	var a = a + "",
		b = "",
		d, c, e = 0;
	d = c = 0;
	for (var e = a.length, f = 0; f < e; f++) {
		var h = a.charCodeAt(f),
			g = null;
		128 > h ? c++ : g = 127 < h && 2048 > h ? String.fromCharCode(h >> 6 | 192) + String.fromCharCode(h & 63 | 128) : String.fromCharCode(h >> 12 | 224) + String.fromCharCode(h >> 6 & 63 | 128) + String.fromCharCode(h & 63 | 128);
		null !== g && (c > d && (b += a.slice(d, c)), b += g, d = c = f + 1)
	}
	c > d && (b += a.slice(d, e));
	return b
}

function serialize(a) {
	var b = function(a) {
			for (var b = 0, c = 0, d = a.length, e = "", c = 0; c < d; c++) e = a.charCodeAt(c), b = 128 > e ? b + 1 : 2048 > e ? b + 2 : b + 3;
			return b
		},
		d = function(a) {
			var b = typeof a,
				c, d;
			if ("object" === b && !a) return "null";
			if ("object" === b) {
				if (!a.constructor) return "object";
				a = a.constructor.toString();
				(c = a.match(/(\w+)\(/)) && (a = c[1].toLowerCase());
				c = ["boolean", "number", "string", "array"];
				for (d in c) if (a == c[d]) {
					b = c[d];
					break
				}
			}
			return b
		},
		c = d(a),
		e = "";
	switch (c) {
	case "function":
		b = "";
		break;
	case "boolean":
		b = "b:" + (a ? "1" : "0");
		break;
	case "number":
		b = (Math.round(a) == a ? "i" : "d") + ":" + a;
		break;
	case "string":
		b = "s:" + b(a) + ':"' + a + '"';
		break;
	case "array":
	case "object":
		var b = "a",
			f = 0,
			h = "",
			g;
		for (g in a) a.hasOwnProperty(g) && (e = d(a[g]), "function" !== e && (e = g.match(/^[0-9]+$/) ? parseInt(g, 10) : g, h += this.serialize(e) + this.serialize(a[g]), f++));
		b += ":" + f + ":{" + h + "}";
		break;
	default:
		b = "N"
	}
	"object" !== c && "array" !== c && (b += ";");
	return b
};
$(document).ready(function() {
	$.fn.wait = function(a, b) {
		a = a || 1E3;
		return this.queue(b || "fx", function() {
			var b = this;
			setTimeout(function() {
				$(b).dequeue()
			}, a)
		})
	};
	$(function() {
		window.PIE && ($(".userbar li").each(function() {
			PIE.attach(this)
		}), $(".userbar li img").each(function() {
			PIE.attach(this)
		}), $(".userbar li a").each(function() {
			PIE.attach(this)
		}), $(".tabBox .tabs a").each(function() {
			PIE.attach(this)
		}), $("#photo").each(function() {
			PIE.attach(this)
		}), $("#photo img").each(function() {
			PIE.attach(this)
		}))
	});
	$(function() {
		$("#userbar li.menu").hover(function() {
			$("ul", this).css("display", "block")
		}, function() {
			$("ul", this).fadeOut(300)
		})
	});
	$(function() {
		function a() {
			var a = b.scrollTop();
			a >= c && !e ? (e = 1, d.addClass("scroll-fixed")) : a <= c && e && (e = 0, d.removeClass("scroll-fixed"))
		}
		var b = $(window),
			d = $(".scroll"),
			c = d.length && d.offset().top,
			e = 0;
		a();
		d.on("click", function() {
			e || setTimeout(function() {
				b.scrollTop(b.scrollTop() - 47)
			}, 10)
		});
		b.on("scroll", a)
	});
	$(function() {
		var a;
		$("#con").hover(function() {
			clearInterval(a)
		}, function() {
			a = setInterval(function() {
				var a = $("#con ul"),
					d = a.find("li:last").height();
				a.animate({
					marginTop: d + 20 + "px"
				}, 1E3, function() {
					a.find("li:last").prependTo(a);
					a.find("li:first").hide();
					a.css({
						marginTop: 0
					});
					a.find("li:first").fadeIn(1E3)
				})
			}, 5E3)
		}).trigger("mouseleave")
	});
	$("input[type=text][title],input[type=password][title],textarea[title]").each(function(a) {
		$(this).addClass("input-prompt-" + a);
		var b = $('<span class="input-prompt"/>');
		$(b).attr("id", "input-prompt-" + a);
		$(b).append($(this).attr("title"));
		$(b).click(function() {
			$(this).hide();
			$("." + $(this).attr("id")).focus()
		});
		"" != $(this).val() && $(b).hide();
		$(this).before(b);
		$(this).focus(function() {
			$("#input-prompt-" + a).hide()
		});
		$(this).blur(function() {
			"" == $(this).val() && $("#input-prompt-" + a).show()
		})
	});
	window.FXL_IE6 = !+"\v1" && !("maxHeight" in document.body.style) ? 1 : 0;
	$("#BackToTop").hide();
	$(function() {
		$(window).scroll(function() {
			100 < $(this).scrollTop() ? ($(".top").addClass("navbar-fixed-top"), $("#BackToTop").fadeIn()) : ($(".top").removeClass("navbar-fixed-top"), $("#BackToTop").fadeOut())
		})
	});
	$(function() {
		$("#slideshow .slideshow").append('<div id="loadingPins" style="width:100%;height:100%;" class="active"><img style="margin-top:100px;" src="' + base_url + '/assets/img/ajax-loader.gif" alt="\u6b63\u5728\u52a0\u8f7d..."></div>');
		$("#slideshow .slideshow").imagesLoaded(function() {
			function a() {
				var a = $("#slideshow .slideshow div.active");
				0 == a.length && (a = $("#slideshow .slideshow div:first"));
				var b = a.prev().length ? a.prev() : $("#slideshow .slideshow div:last");
				a.addClass("last-active");
				b.css({
					opacity: 0
				}).addClass("active").animate({
					opacity: 1
				}, 1E3, function() {
					a.removeClass("active last-active")
				})
			}
			function b() {
				var a = $("#slideshow .slideshow div.active");
				0 == a.length && (a = $("#slideshow .slideshow div:last"));
				var b = a.next().length ? a.next() : $("#slideshow .slideshow div:first");
				a.addClass("last-active");
				b.css({
					opacity: 0
				}).addClass("active").animate({
					opacity: 1
				}, 1E3, function() {
					a.removeClass("active last-active")
				})
			}
			$("#slideshow .slideshow #loadingPins").remove();
			var d = setInterval(function() {
				b()
			}, 5E3);
			$("#prev").click(function(b) {
				b.preventDefault();
				a()
			});
			$("#next").click(function(a) {
				a.preventDefault();
				b()
			});
			$("#slideshow").hover(function() {
				$("#prev").removeClass("hide");
				$("#next").removeClass("hide");
				clearInterval(d)
			}, function() {
				$("#prev").addClass("hide");
				$("#next").addClass("hide");
				d = setInterval(function() {
					b()
				}, 5E3)
			})
		})
	});
	$.oPopover("user_profile", {
		autoDirection: !0,
		remote: $("#data-actions").attr("data-userprofile-url"),
		elem_mark: 'a[data-user-profile="1"]',
		data_id_mark: "data-user-id",
		tpl_mark: "user_profile_tpl"
	});
	$.oPopover("tags_pop", {
		autoDirection: !0,
		remote: $("#data-actions").attr("data-tags-url"),
		elem_mark: 'a[data-tags="1"]',
		data_id_mark: "data-category-id",
		tpl_mark: "tags_pop_tpl"
	})
});
(function(a) {
	function b(a) {
		var b = this.config,
			b = d({
				id: null,
				className: "popover",
				autoDirection: !0,
				remote: null,
				elem_mark: 'a[data-popover="1"]',
				data_id_mark: "data-id",
				tpl_mark: "popover_tpl",
				outer_tpl: '<div id="%ID" class="ui-dialog %CLASSNAME"></div>',
				loading_tpl: '<p class="message"><img src="' + base_url + '/assets/img/s_loading.gif" />Loading...</p><b class="arrow_t"><i class="arrow_inner"></i></b>',
				error_tpl: '<p class="message">%MESSAGE</p><b class="arrow_t"><i class="arrow_inner"></i></b>'
			}, a || {});
		b.id = b.id || "ui-popover-" + e++;
		this.config = b;
		this._createHTML()
	}
	function d(a, b) {
		for (var c in b) a[c] = b[c];
		return a
	}
	var c = {};
	a.oPopover = function(a, d) {
		if (!a || !c[a]) {
			var e = new b(d),
				a = !a ? e.elem[0].id : a;
			c[a] = e
		}
		return c[a]
	};
	var e = 0;
	d(b.prototype, {
		width: function(a) {
			this.elem.width(a);
			this.center();
			return this
		},
		align: function(b) {
			var c = this.popOverlay,
				d = this.config;
			if (this.showing) {
				b.find("img").length && (b = b.find("img").eq(0));
				var e = b.offset(),
					j = Math.min(e.left - 20, a(window).width() - c.outerWidth() - 30);
				if (d.autoDirection) {
					var d = e.top - c.outerHeight() - 4,
						l = !1;
					5 > d && (d = e.top + a(b).outerHeight() + 4, l = !0);
					c.css({
						left: j,
						top: d
					});
					l ? (c.find(".arrow_t").css({
						left: e.left - j,
						display: "block"
					}), c.find(".arrow_b").css({
						display: "none"
					})) : (c.find(".arrow_b").css({
						left: e.left - j,
						display: "block"
					}), c.find(".arrow_t").css({
						display: "none"
					}))
				} else d = e.top + a(b).outerHeight() + 4, c.css({
					left: j,
					top: d
				}), c.find(".arrow_t").css({
					left: e.left - j,
					display: "block"
				})
			}
		},
		hide: function() {
			var a = this.popOverlay;
			this.showing = !1;
			a && a.hide()
		},
		laterHide: function() {
			var a = this,
				b = a.showTimer;
			a.hideTimer = setTimeout(function() {
				a.hide()
			}, 300);
			b && (clearTimeout(b), a.showTimer = null)
		},
		show: function(b) {
			var c = this,
				d = this.config;
			c.showing = !0;
			if (!c.popOverlay) {
				var e = a(d.outer_tpl.replace(/%ID/, d.id).replace(/%CLASSNAME/, d.className));
				c.popOverlay = a(e).appendTo("body");
				c.popOverlay.hover(function() {
					c.hideTimer && (clearTimeout(c.hideTimer), c.hideTimer = null)
				}, function() {
					c.laterHide()
				})
			}
			var j = b.attr(d.data_id_mark);
			c.popOverlay.data("data-id") === j ? (c.popOverlay.show(), c.align(b)) : (c.popOverlay.data("data-id", j), c.popData[j] ? (c.popOverlay.html(c.popData[j]).show(), c.align(b)) : (c.popOverlay.addClass("loading").html(d.loading_tpl).show(), c.align(b), a.ajax({
				url: d.remote,
				type: "get",
				data: {
					dataid: j,
					radom: random(3)
				},
				dataType: "json"
			}).success(function(e) {
				!0 === e.success ? (c.popOverlay.html(Mustache.to_html(a("#" + d.tpl_mark).html(), e)), c.popOverlay.removeClass("loading"), c.align(b), c.popData[j] = c.popOverlay.html()) : (c.popOverlay.html(d.error_tpl.replace(/%MESSAGE/, e.message)), c.popOverlay.removeClass("loading"), c.align(b))
			}).error(function() {
				c.popOverlay.html(d.error_tpl);
				c.popOverlay.removeClass("loading");
				c.align(b)
			})))
		},
		_createHTML: function() {
			var b = this,
				c = b.config;
			b.popData = {};
			b.popOverlay = null;
			b.showing = !1;
			b.hideTimer = null;
			b.showTimer = null;
			a(c.elem_mark).live("mouseenter", function() {
				var c = a(this);
				b.hideTimer && (clearTimeout(b.hideTimer), b.hideTimer = null);
				b.showTimer && clearTimeout(b.showTimer);
				b.showTimer = setTimeout(function() {
					b.show(c)
				}, 600)
			}).live("mouseleave", function() {
				b.laterHide()
			})
		}
	})
})(jQuery);
(function(a) {
	function b(a) {
		var b = this.config,
			b = d({
				id: null,
				width: "630",
				center: !0,
				mask: !0,
				close: !0,
				beforeShow: function() {},
				afterShow: function() {},
				beforeHide: function() {},
				onHide: function() {},
				scrollTimer: null,
				className: "ui-dialog",
				tpl: '<div id="%ID" class="%CLASSNAME"><div class="hd">\u6b63\u5728\u52a0\u8f7d</div><div class="bd"><div class="ui-loading">\u6b63\u5728\u52a0\u8f7d...</div></div><a class="ui-close" href="#close">X</a></div>'
			}, a || {});
		b.id = b.id || "ui-dialog-" + e++;
		this.config = b;
		this._createHTML()
	}

	function d(a, b) {
		for (var c in b) a[c] = b[c];
		return a
	}
	var c = {};
	a.oDialog = function(a, d) {
		if (!a || !c[a]) {
			var e = new b(d),
				a = !a ? e.elem[0].id : a;
			c[a] = e
		}
		return c[a]
	};
	var e = 0;
	d(b.prototype, {
		_status: 400,
		isShow: function() {
			return 200 === this._status
		},
		width: function(a) {
			this.elem.width(a);
			this.center();
			return this
		},
		center: function() {
			if (400 !== this._status) {
				var b = this.elem,
					c = a(window),
					d = c.width(),
					e = c.height();
				b.css("top", (e - b.height()) / 2 + (this._isIE6 ? c.scrollTop() : 0));
				b.css("left", (d - b.width()) / 2 + (this._isIE6 ? c.scrollLeft() : 0));
				this.config.mask && this._isIE6 && this.mask.css("height", a(document).height());
				a(this).triggerHandler("center");
				return this
			}
		},
		head: function(a) {
			this.elem.children("div.hd").html(a);
			return this
		},
		body: function(a) {
			this.elem.children("div.bd").html(a);
			return this
		},
		show: function() {
			var b = this,
				c = this.config,
				d = b.elem,
				e = {
					visibility: "",
					opacity: 0
				},
				j = parseInt(a.browser.version, 10);
			a(b).triggerHandler("beforeShow");
			c.beforeShow();
			b._status = 200;
			!0 === c.center && d.imagesLoaded(function() {
				b.center()
			});
			c.mask && b.mask.css({
				visibility: ""
			});
			a.browser.msie && 9 > j ? (b._isIE6 && c.mask && b.mask.css({
				position: "absolute",
				height: a(document).height()
			}), d.css({
				visibility: ""
			}), a(b).triggerHandler("show")) : (d.css(e), d.animate({
				opacity: 1
			}, 200, function() {
				a(b).triggerHandler("show")
			}));
			return b
		},
		hide: function() {
			var b = this,
				c = b.config,
				d = b.elem;
			if (400 !== b._status) return a(b).triggerHandler("beforeHide"), c.beforeHide(), b._status = 400, d.animate({
				opacity: 0
			}, 300, function() {
				d.css({
					visibility: "hidden",
					opacity: ""
				});
				c.mask && b.mask.css({
					visibility: "hidden"
				});
				a(b).triggerHandler("hide");
				c.onHide()
			}), b
		},
		_createHTML: function() {
			var b = this,
				c = b.config,
				d = null,
				e = a(c.tpl.replace(/%ID/, c.id).replace(/%CLASSNAME/, c.className));
			e.css({
				visibility: "hidden",
				width: c.width
			});
			!0 === c.close ? a(e).find("> a.ui-close").click(function(a) {
				a.preventDefault();
				b.hide()
			}) : a(e).find("> a.ui-close").remove();
			b.elem = e;
			a("body").append(e);
			!0 === c.mask && (c = a('<div id="' + c.id + '_mask" class="ui-mask"></div>'), c.css({
				visibility: "hidden"
			}), b.mask = c, a("body").append(c));
			a(window).resize(function() {
				d === null && (d = setTimeout(function() {
					d = null;
					b.center()
				}, 300))
			});
			a(b.elem).resize(function() {
				d === null && (d = setTimeout(function() {
					d = null;
					b.center()
				}, 300))
			});
			a.browser.msie && 7 > parseInt(a.browser.version, 10) && (b._isIE6 = !0, a(window).scroll(function() {
				d === null && (d = setTimeout(function() {
					d = null;
					b.center()
				}, 300))
			}))
		}
	})
})(jQuery);
(function() {
	function a(a, b, c) {
		if (a.addEventListener) a.addEventListener(b, c, !1);
		else if (a.attachEvent) a.attachEvent("on" + b, function() {
			c.call(a)
		});
		else throw Error("not supported or DOM not loaded");
	}
	function b(a, b) {
		for (var c in b) b.hasOwnProperty(c) && (a.style[c] = b[c])
	}
	function d(a, b) {
		RegExp("\\b" + b + "\\b").test(a.className) || (a.className += " " + b)
	}
	function c(a, b) {
		a.className = a.className.replace(RegExp("\\b" + b + "\\b"), "")
	}
	function e(a) {
		a.parentNode.removeChild(a)
	}
	var f = document.documentElement.getBoundingClientRect ?
	function(a) {
		var b = a.getBoundingClientRect(),
			c = a.ownerDocument,
			a = c.body,
			c = c.documentElement,
			d = c.clientTop || a.clientTop || 0,
			e = c.clientLeft || a.clientLeft || 0,
			f = 1;
		a.getBoundingClientRect && (f = a.getBoundingClientRect(), f = (f.right - f.left) / a.clientWidth);
		1 < f && (e = d = 0);
		return {
			top: b.top / f + (window.pageYOffset || c && c.scrollTop / f || a.scrollTop / f) - d,
			left: b.left / f + (window.pageXOffset || c && c.scrollLeft / f || a.scrollLeft / f) - e
		}
	} : function(a) {
		var b = 0,
			c = 0;
		do b += a.offsetTop || 0, c += a.offsetLeft || 0, a = a.offsetParent;
		while (a);
		return {
			left: c,
			top: b
		}
	}, h = function() {
		var a = document.createElement("div");
		return function(b) {
			a.innerHTML = b;
			return a.removeChild(a.firstChild)
		}
	}(), g = function() {
		var a = 0;
		return function() {
			return "ValumsAjaxUpload" + a++
		}
	}();
	window.AjaxUpload = function(b, c) {
		this._settings = {
			action: "upload.php",
			name: "userfile",
			multiple: !1,
			data: {},
			autoSubmit: !0,
			responseType: !1,
			hoverClass: "hover",
			focusClass: "focus",
			disabledClass: "disabled",
			onChange: function() {},
			onSubmit: function() {},
			onComplete: function() {}
		};
		for (var d in c) c.hasOwnProperty(d) && (this._settings[d] = c[d]);
		b.jquery ? b = b[0] : "string" == typeof b && (/^#.*/.test(b) && (b = b.slice(1)), b = document.getElementById(b));
		if (!b || 1 !== b.nodeType) throw Error("Please make sure that you're passing a valid element");
		"A" == b.nodeName.toUpperCase() && a(b, "click", function(a) {
			if (a && a.preventDefault) a.preventDefault();
			else if (window.event) window.event.returnValue = false
		});
		this._button = b;
		this._input = null;
		this._disabled = !1;
		this.enable();
		this._rerouteClicks()
	};
	AjaxUpload.prototype = {
		setData: function(a) {
			this._settings.data = a
		},
		disable: function() {
			d(this._button, this._settings.disabledClass);
			this._disabled = !0;
			var a = this._button.nodeName.toUpperCase();
			("INPUT" == a || "BUTTON" == a) && this._button.setAttribute("disabled", "disabled");
			this._input && this._input.parentNode && (this._input.parentNode.style.visibility = "hidden")
		},
		enable: function() {
			c(this._button, this._settings.disabledClass);
			this._button.removeAttribute("disabled");
			this._disabled = !1
		},
		_createInput: function() {
			var e = this,
				f = document.createElement("input");
			f.setAttribute("type", "file");
			f.setAttribute("name", this._settings.name);
			this._settings.multiple && f.setAttribute("multiple", "multiple");
			b(f, {
				position: "absolute",
				right: 0,
				margin: 0,
				padding: 0,
				fontSize: "480px",
				fontFamily: "sans-serif",
				cursor: "pointer"
			});
			var g = document.createElement("div");
			b(g, {
				display: "block",
				position: "absolute",
				overflow: "hidden",
				margin: 0,
				padding: 0,
				opacity: 0,
				direction: "ltr",
				zIndex: 2147483583
			});
			if ("0" !== g.style.opacity) {
				if ("undefined" == typeof g.filters) throw Error("Opacity not supported by the browser");
				g.style.filter = "alpha(opacity=0)"
			}
			a(f, "change", function() {
				if (f && "" !== f.value) {
					var a = f.value.replace(/.*(\/|\\)/, "");
					!1 === e._settings.onChange.call(e, a, -1 !== a.indexOf(".") ? a.replace(/.*[.]/, "") : "") ? e._clearInput() : e._settings.autoSubmit && e.submit()
				}
			});
			a(f, "mouseover", function() {
				d(e._button, e._settings.hoverClass)
			});
			a(f, "mouseout", function() {
				c(e._button, e._settings.hoverClass);
				c(e._button, e._settings.focusClass);
				f.parentNode && (f.parentNode.style.visibility = "hidden")
			});
			a(f, "focus", function() {
				d(e._button, e._settings.focusClass)
			});
			a(f, "blur", function() {
				c(e._button, e._settings.focusClass)
			});
			g.appendChild(f);
			document.body.appendChild(g);
			this._input = f
		},
		_clearInput: function() {
			this._input && (e(this._input.parentNode), this._input = null, this._createInput(), c(this._button, this._settings.hoverClass), c(this._button, this._settings.focusClass))
		},
		_rerouteClicks: function() {
			var c = this;
			a(c._button, "mouseover", function() {
				var a;
				if (!c._disabled) {
					c._input || c._createInput();
					var d = c._input.parentNode,
						e = c._button,
						g;
					a = f(e);
					g = a.left;
					a = a.top;
					b(d, {
						position: "absolute",
						left: g + "px",
						top: a + "px",
						width: e.offsetWidth + "px",
						height: e.offsetHeight + "px"
					});
					d.style.visibility = "visible"
				}
			})
		},
		_createIframe: function() {
			var a = g(),
				b = h('<iframe src="javascript:false;" name="' + a + '" />');
			b.setAttribute("id", a);
			b.style.display = "none";
			document.body.appendChild(b);
			return b
		},
		_createForm: function(a) {
			var b = this._settings,
				c = h('<form method="post" enctype="multipart/form-data"></form>');
			c.setAttribute("action", b.action);
			c.setAttribute("target", a.name);
			c.style.display = "none";
			document.body.appendChild(c);
			for (var d in b.data) b.data.hasOwnProperty(d) && (a = document.createElement("input"), a.setAttribute("type", "hidden"), a.setAttribute("name", d), a.setAttribute("value", b.data[d]), c.appendChild(a));
			return c
		},
		_getResponse: function(b, c) {
			var d = !1,
				f = this,
				g = this._settings;
			a(b, "load", function() {
				if ("javascript:'%3Chtml%3E%3C/html%3E';" == b.src || "javascript:'<html></html>';" == b.src) d && setTimeout(function() {
					e(b)
				}, 0);
				else {
					var a = b.contentDocument ? b.contentDocument : window.frames[b.id].document;
					if (!(a.readyState && "complete" != a.readyState) && !(a.body && "false" == a.body.innerHTML)) {
						var h;
						a.XMLDocument ? h = a.XMLDocument : a.body ? (h = a.body.innerHTML, g.responseType && "json" == g.responseType.toLowerCase() && (a.body.firstChild && "PRE" == a.body.firstChild.nodeName.toUpperCase() && (a.normalize(), h = a.body.firstChild.firstChild.nodeValue), h = h ? eval("(" + h + ")") : {})) : h = a;
						g.onComplete.call(f, c, h);
						d = !0;
						b.src = "javascript:'<html></html>';"
					}
				}
			})
		},
		submit: function() {
			var a = this._settings;
			if (this._input && "" !== this._input.value) {
				var b = this._input.value.replace(/.*(\/|\\)/, "");
				if (!1 === a.onSubmit.call(this, b, -1 !== b.indexOf(".") ? b.replace(/.*[.]/, "") : "")) this._clearInput();
				else {
					var a = this._createIframe(),
						d = this._createForm(a);
					e(this._input.parentNode);
					c(this._button, this._settings.hoverClass);
					c(this._button, this._settings.focusClass);
					d.appendChild(this._input);
					d.submit();
					e(d);
					e(this._input);
					this._input = null;
					this._getResponse(a, b);
					this._createInput()
				}
			}
		}
	}
})();
$(document).ready(function(a) {
	(function(a) {
		a.oValidate = function(j) {
			var l = {
				save_share_form: d,
				register_form: g,
				social_register_form: i,
				update_userinfo: e,
				update_password_form: f,
				edit_share_form: c,
				login_form: h
			};
			j && (l[j] && a("#" + j)) && a("#" + j).validate(l[j])
		};
		var d = {
			rules: {
				intro: {
					required: !0,
					byteRangeLength: [4, 4E3]
				},
				title: {
					required: !0,
					byteRangeLength: [4, 80]
				}
			},
			messages: {
				intro: {
					required: getTip("required-field"),
					byteRangeLength: getTip("intro_length_not_valid")
				},
				title: {
					required: getTip("required-field"),
					byteRangeLength: getTip("title_length_not_valid")
				}
			},
			submitHandler: function() {
				var c = !1,
					d = [];
					e = {
						url: a("#save_share_form #cover_filename").val(),
						desc: "",
						cover: 1
					};
					d.push(e);

				a("#save_share_form #all_files").val(serialize(d));
				a("#save_share_form").ajaxSubmit({
					url: a("#save_share_form").attr("data-url"),
					data: a("#save_share_form").formSerialize(),
					type: "POST",
					dataType: "json",
					beforeSubmit: function() {
						var c = a("#save_share_form #cover_filename").val();
						if (null == c || "" == c) return a("#ajax_share_message").html(getTip("no_img_share")), !1;
						c = a("#save_share_form .album_select_id").val();
						if (null == c || "" == c || 0 == c) return a("#ajax_share_message").html(getTip("album_cannot_be_null")), !1;
						a("#ajax_share_message").html(getTip("loading-detail"))
					},
					success: function(c) {
						"true" == a.trim(c.success) ? (show_message("success", getTip("success") + ": " + c.message, !1, 0), setTimeout(function() {
							window.location.href = a("#save_share_form").attr("next-url")
						}, 2E3)) : show_message("error", getTip("error") + ": " + c.message, !1, 0)
					},
					error: function() {
						show_message("error", getTip("error") + ": " + getTip("server-error"), !1, 0)
					}
				});
				return !1
			}
		},
			c = {
				rules: {
					intro: {
						required: !0,
						byteRangeLength: [4, 4E3]
					},
					title: {
						required: !0,
						byteRangeLength: [4, 80]
					}
				},
				messages: {
					intro: {
						required: getTip("required-field"),
						byteRangeLength: getTip("intro_length_not_valid")
					},
					title: {
						required: getTip("required-field"),
						byteRangeLength: getTip("title_length_not_valid")
					}
				},
				submitHandler: function() {
					var c = [],
						d = !1;
					a("#publish_image_list li.selected").each(function() {
						var e = a(this).find("input").val();
						if (140 < str_length(e)) return a("#ajax_share_message").html(getTip("img_desc_not_valid")), !1;
						a(this).hasClass("cover") ? (e = {
							id: a(this).attr("data-id"),
							url: a(this).attr("data-url"),
							desc: e,
							cover: !0
						}, c.push(e), d = !0) : (e = {
							id: a(this).attr("data-id"),
							url: a(this).attr("data-url"),
							desc: e,
							cover: !1
						}, c.push(e))
					});
					if (!d) return a("#ajax_share_message").html(getTip("no_img_cover_selected")), !1;
					a("#edit_share_form #all_files").val(serialize(c));
					a("#edit_share_form").ajaxSubmit({
						url: a("#data-actions").attr("data-editshare-url"),
						data: a("#edit_share_form").formSerialize(),
						type: "POST",
						dataType: "json",
						beforeSubmit: function() {
							var c = a("#edit_share_form .album_select_id").val();
							if (null == c || "" == c || 0 == c) return a("#ajax_share_message").html(getTip("album_cannot_be_null")), !1;
							a("#ajax_share_message").html(getTip("loading-detail"))
						},
						success: function(c) {
							"true" == a.trim(c.success) ? (a("#ajax_share_message").html(c.message), window.location.href = a("#edit_share_form").attr("next-url")) : a("#ajax_share_message").html(c.message)
						},
						error: function() {
							show_message("error", getTip("error") + ": " + getTip("server-error"), !1, 0)
						}
					});
					return !1
				}
			},
			e = {
				rules: {
					nickname: {
						required: !0,
						byteRangeLength: [4, 20],
						remote: function() {
							return a("#data-actions").attr("data-ajax-updatenickname")
						}
					}
				},
				messages: {
					nickname: {
						required: getTip("required-field"),
						byteRangeLength: getTip("nick_not_valid"),
						remote: getTip("nick_already_existed")
					}
				},
				errorElement: "span",
				submitHandler: function() {
					a("#update_userinfo").ajaxSubmit({
						url: a("#data-actions").attr("data-updateuser-url"),
						data: a("#update_userinfo").formSerialize(),
						type: "POST",
						dataType: "json",
						beforeSubmit: function() {
							a("#update_userinfo #ajax_message").html(getTip("loading-detail"))
						},
						success: function(c) {
							!0 === c.success ? (a("#update_userinfo #ajax_message").html(c.message), window.location.href = a("#data-actions").attr("data-mybasicsettings-url")) : a("#update_userinfo #ajax_message").html(c.message)
						}
					});
					return !1
				}
			},
			f = {
				rules: {
					email: {
						required: !0,
						email: !0,
						remote: function() {
							return a("#data-actions").attr("data-ajax-email")
						}
					},
					org_passwd: {
						required: !0,
						rangelength: [6, 15]
					},
					new_passwd: {
						required: !0,
						rangelength: [6, 15]
					},
					new_verify_passwd: {
						required: !0,
						rangelength: [6, 15],
						equalTo: "#new_passwd"
					}
				},
				messages: {
					email: {
						required: getTip("required-field"),
						email: getTip("not_valid"),
						remote: getTip("email_already_existed")
					},
					org_passwd: {
						required: getTip("required-field"),
						rangelength: getTip("password_not_valid")
					},
					new_passwd: {
						required: getTip("required-field"),
						rangelength: getTip("password_not_valid")
					},
					new_verify_passwd: {
						required: getTip("required-field"),
						rangelength: getTip("password_not_valid"),
						equalTo: getTip("password_not_match")
					}
				},
				errorElement: "span",
				submitHandler: function() {
					a("#update_password_form").ajaxSubmit({
						url: a("#data-actions").attr("data-ajax-resetpasswd"),
						data: a("#update_password_form").formSerialize(),
						type: "POST",
						dataType: "json",
						beforeSubmit: function() {
							a("#update_password_form #ajax_message").html(getTip("loading-detail"))
						},
						success: function(c) {
							!0 === c.success ? (a("#update_password_form #ajax_message").html(c.message), window.location.href = a("#data-actions").attr("data-mybasicsettings-url")) : a("#update_password_form #ajax_message").html(c.message)
						}
					});
					return !1
				}
			},
			h = {
				rules: {
					email: {
						required: !0,
						email: !0
					},
					password: {
						required: !0,
						minlength: 6
					}
				},
				errorElement: "span",
				messages: {
					email: {
						required: getTip("required-field"),
						email: getTip("not_valid")
					},
					password: {
						required: getTip("required-field"),
						minlength: getTip("not_valid")
					}
				},
				submitHandler: function() {
					a("#login_form").ajaxSubmit({
						url: a("#data-actions").attr("data-login-url"),
						data: a("#login_form").formSerialize(),
						type: "POST",
						dataType: "json",
						beforeSubmit: function() {
							a("#ajax_message").html(getTip("loading-detail"))
						},
						success: function(c) {
							if (!0 === c.success) return a("#ajax_message").html(c.msg), setTimeout(function() {
								window.location.href = a("#data-actions").attr("data-loginredirect-url")
							}, 1E3), !0;
							a("#ajax_message").html(c.msg);
							return !1
						}
					});
					return !1
				}
			},
			g = {
				rules: {
					nickname: {
						required: !0,
						byteRangeLength: [4, 20],
						remote: function() {
							return a("#data-actions").attr("data-ajax-nickname")
						}
					},
					email: {
						required: !0,
						email: !0,
						remote: function() {
							return a("#data-actions").attr("data-ajax-email")
						}
					},
					password: {
						required: !0,
						rangelength: [6, 15]
					},
					passconf: {
						required: !0,
						rangelength: [6, 15],
						equalTo: "#password"
					},
					terms: {
						required: !0
					}
				},
				messages: {
					nickname: {
						required: getTip("required-field"),
						byteRangeLength: getTip("nick_not_valid"),
						remote: getTip("nick_already_existed")
					},
					email: {
						required: getTip("required-field"),
						email: getTip("not_valid"),
						remote: getTip("email_already_existed")
					},
					password: {
						required: getTip("required-field"),
						rangelength: getTip("password_not_valid")
					},
					passconf: {
						required: getTip("required-field"),
						rangelength: getTip("password_not_valid"),
						equalTo: getTip("password_not_match")
					},
					terms: {
						required: getTip("required-field")
					}
				},
				errorElement: "span",
				submitHandler: function() {
					a("#register_form").ajaxSubmit({
						url: a("#data-actions").attr("data-register-url"),
						data: a("#register_form").formSerialize(),
						type: "POST",
						dataType: "json",
						beforeSubmit: function() {
							a("#ajax_message").html(getTip("loading-detail"))
						},
						success: function(c) {
							!0 === c.success ? (a("#ajax_message").html(c.msg), window.location.href = a("#data-actions").attr("data-regredirect-url")) : a("#ajax_message").html(c.msg)
						},
						error: function(c) {
							a("#ajax_message").html(c)
						}
					});
					return !1
				}
			},
			i = {
				rules: {
					nickname: {
						required: !0,
						byteRangeLength: [4, 20],
						remote: function() {
							return a("#data-actions").attr("data-ajax-nickname")
						}
					}
				},
				messages: {
					nickname: {
						required: getTip("required-field"),
						byteRangeLength: getTip("nick_not_valid"),
						remote: getTip("nick_already_existed")
					}
				},
				errorElement: "span",
				submitHandler: function() {
					a("#social_register_form").ajaxSubmit({
						url: a("#social_register_form").attr("data-url"),
						data: a("#social_register_form").formSerialize(),
						type: "POST",
						dataType: "json",
						beforeSubmit: function() {
							a("#ajax_message").html(getTip("loading-detail"))
						},
						success: function(c) {
							"true" == a.trim(c.success) ? (a("#ajax_message").html(c.message), window.location.href = a("#social_register_form").attr("data-redirect-url")) : a("#ajax_message").html(c.message)
						},
						error: function() {
							show_message("error", getTip("error") + ": " + getTip("server-error"), !1, 0)
						}
					});
					return !1
				}
			}
	})(jQuery);
	jQuery.validator.addMethod("byteRangeLength", function(a, d, c) {
		for (var e = a.length, f = 0; f < a.length; f++) 127 < a.charCodeAt(f) && e++;
		return this.optional(d) || e >= c[0] && e <= c[1]
	}, getTip("length_rang"));
	jQuery.validator.addMethod("notDigit", function(a) {
		return /^[0-9]{1,20}$/.test(a) ? !1 : !0
	}, getTip("not_all_digit"));
	a.oValidate("save_share_form");
	a.oValidate("login_form");
	a.oValidate("social_register_form");
	a.oValidate("update_password_form");
	a.oValidate("update_userinfo")
});
$(document).ready(function(a) {
	function b() {
		if (null == I || void 0 == I) I = a.oDialog("pDialog", {
			id: "ui_push_dialog",
			beforeShow: function() {
				null != w && void 0 != w && w.remove()
			},
			beforeHide: function() {
				null != w && void 0 != w && w.remove()
			}
		});
		return I
	}
	function d(a, b) {
		hide_loading();
		show_message("success", getTip("success") + ": " + getTip(a), !0, 5E3);
		null != b && (window.location.href = b)
	}
	function c(b) {
		hide_loading();
		"not-login" == a.trim(b) ? G() : null != b ? show_message("error", getTip("error") + ": " + getTip(a.trim(b)), !1, 0) : show_message("error", getTip("error") + ": " + getTip("server-error"), !1, 0)
	}
	function e(d, e, f) {
		show_loading("success");
		a.ajax({
			type: "post",
			url: q.attr("data-fetchtpl-url"),
			dataType: "json",
			data: {
				tpl: d
			}
		}).error(function() {
			hide_loading();
			c()
		}).success(function(g) {
			hide_loading();
			if (!0 === g.success) {
				var g = a(Mustache.to_html(g.data.tpl, e)),
					g = b().body(g),
					i = g.elem.find("#" + d);
				g.head(i.attr("data-title"));
				g.width(i.attr("data-width"));
				g.elem.addClass(i.attr("data-css-class"));
				g.show();
				f()
			} else c(g.message)
		})
	}
	function f(e) {
		show_loading("success");
		a.ajax({
			type: "post",
			url: q.attr("data-delalbum-url"),
			dataType: "json",
			data: {
				album_id: e
			}
		}).error(function() {
			hide_loading();
			c()
		}).success(function(a) {
			hide_loading();
			!0 === a.success ? (b().hide(), d(a.message, q.attr("data-myalbum-url"))) : c(a.message)
		})
	}
	function h(e, u, f) {
		show_loading("success");
		a.ajax({
			type: "post",
			url: q.attr("data-editalbum-url"),
			dataType: "json",
			data: {
				album_id: e,
				album_title: u,
				category_id: f
			}
		}).error(function() {
			hide_loading();
			c()
		}).success(function(a) {
			hide_loading();
			!0 === a.success ? (b().hide(), d(a.message, q.attr("data-myalbum-url"))) : c(a.message)
		})
	}
	function g(d, e, f) {
		show_loading("success");
		a.ajax({
			type: "post",
			url: q.attr("data-ajaxgetshare-url"),
			dataType: "json",
			data: {
				sid: d
			}
		}).error(function() {
			hide_loading();
			c()
		}).success(function(g) {
			hide_loading();
			if (!0 === g.success) {
				var i = a("#" + f),
					h = a(Mustache.to_html(i.html(), g)),
					h = b().width(630).head(i.attr("data-title")).body(h);
				P();
				"forwarding" == e ? (h.elem.addClass("dialog_upload"), a("#forwarding_div").attr("data-sid", d), a("#forwarding_div").attr("data-uid", 0), y("forwarding_div", null, null)) : "edit_forwarding" == e ? (h.head(i.attr("data-edit-title")), h.elem.addClass("dialog_upload"), a("#forwarding_div").attr("data-sid", d), a("#forwarding_div").attr("data-edit", "1"), y("forwarding_div", g.data.share.category_id, g.data.share.category_name_cn)) : "edit_item" == e && (h.width(750), h.head(i.attr("data-edit-title")), h.elem.addClass("dialog_upload"), a("#item_detail_div").attr("data-sid", d), y("item_detail_div", g.data.share.category_id, g.data.share.category_name_cn), C("edit_smiles_div", "edit_publish_intro"), a.oValidate("edit_share_form"));
				h.show()
			} else c(g.message)
		})
	}
	function ii(b) {
		0 == b ? (a("#website_publish").addClass("selected"), a("#upload_publish").removeClass("selected"), a("#upload_input").css("display", "none"), a("#website_input").css("display", "block"), a("#save_share_form").attr("data-url", a("#publish_tpl").attr("data-fetch-save-url")), a("#fetch_remote_btn").attr("data-params", 0)) : 1 == b ? (a("#upload_publish").addClass("selected"), a("#website_publish").removeClass("selected"), a("#upload_input").css("display", "block"), a("#website_input").css("display", "none"), a("#share_type").val("upload"), a("#save_share_form").attr("data-url", a("#publish_tpl").attr("data-upload-save-url"))) : (a("#upload_publish").removeClass("selected"), a("#website_publish").removeClass("selected"), a("#upload_input").css("display", "none"), a("#website_input").css("display", "block"), a("#save_share_form").attr("data-url", a("#publish_tpl").attr("data-video-save-url")), a("#fetch_remote_btn").attr("data-params", 2))
	}
	function j(b) {
		a("#crop_dialog_tpl").attr("data-style", b);
		a("#push_" + b).parent().find(".push_style").addClass("hide");
		a("#push_" + b).parent().find(".selected").removeClass("selected");
		a("#push_" + b).removeClass("hide");
		a("#link_" + b).addClass("selected")
	}
	function l(b, d) {
		var e = 0 == d ? a("#publish_tpl").attr("data-fetch-url") : a("#publish_tpl").attr("data-video-fetch-url");
		a("#ajax_fetch_message").html(getTip("loading-detail"));
		a.ajax({
			type: "post",
			url: e,
			dataType: "json",
			data: {
				remote_url: b
			}
		}).error(function() {
			c()
		}).success(function(b) {
			if (!0 === b.success) {
				var d = b.data.type;
				if ("images" == d) {
					if (0 == b.data.images.length || void 0 == b.data.images.length) {
						a("#ajax_fetch_message").html(getTip("no-image-fetch"));
						return
					}
					var d = random(4),
						e = b.data.images.length,
						f = 0;
					a("#publish_image_list").html("");
					for (var u = 0; u < e; u++) imageReady(b.data.images[u].src + "?" + d, "", function() {
						console.log(b.data.images[u].src);
						console.log(this.src);
						if (this.width > min_fetch_width && this.height > min_fetch_height) {
							var b = a('<li data-action="publishPinItem" data-name="' + this.src + '"><b><img src="' + this.src + '"/></b><i></i><input type="text" name="desc" placeholder="' + getTip("type_some") + '"/></li>');
							a("#publish_image_list").prepend(b)
						}
					}, function() {
						f++;
						f === e && k(this.src)
					}, function() {
						f++
					});
					a("#reference_url").val(a("#remote_url").val());
					a("#share_type").val("images");
					b.data.title && (a("#title").val(b.data.title).focus(), a("#publish_intro").val(b.data.title).focus());
					a("#channel").val("others")
				} else "channel" == d && (k(b.data.orgin_image_url), a("#share_type").val("channel"), a("#item_id").val(b.data.item_id), a("#channel").val(b.data.channel), a("#title").val(b.data.name).focus(), a("#price").val(b.data.price).focus(), a("#old_price").val(b.data.price).focus(), a("#promotion_url").val(b.data.promotion_url).focus(), a("#publish_intro").val(b.data.name).focus(), a("#reference_url").val(a("#remote_url").val()));
				a("#ajax_fetch_message").html(getTip("fetch-success"))
			} else a("#ajax_fetch_message").html(getTip("fetch-faild")), c(b.message)
		})
	}
	function k(src) {
		a("#upload_imgview_div").html('<img src="' + src + '"/>');
		a("#cover_filename").val(src)
	}
	function p() {
		var b = a("#album_select_div");
		a("body").click(function(c) {
			c = a(c.target).attr("id");
			if (!("album_name" == c || "create_board" == c)) return !b || b.removeClass("btn_select_hover")
		});
		b.hasClass("btn_select_disabled") || b.addClass("btn_select_hover")
	}
	function o(b) {
		var c = a("#" + b);
		a("body").click(function() {
			return !c || c.removeClass("btn_select_hover")
		});
		c.hasClass("btn_select_disabled") || c.addClass("btn_select_hover")
	}
	function s(b, c) {
		var d = a("#album_select_div");
		d.hasClass("btn_select_hover") && d.removeClass("btn_select_hover");
		null == b ? (d.find(".album_select_title").html(getTip("pls_select")), d.find(".album_select_id").val("")) : (d.find(".album_select_title").html(c), d.find(".album_select_id").val(b))
	}
	function J(b, c, d, e) {
		d = a("#" + d);
		d.hasClass("btn_select_hover") && d.removeClass("btn_select_hover");
		d.find(".category_select_title").html(c);
		d.find(".category_select_id").val(b);
		null != e && void 0 != e && n(e, b, null, null)
	}
	function A() {
		var b = a("#album_select_create");
		a.ajax({
			type: "post",
			url: b.attr("data-url"),
			dataType: "json",
			data: {
				album_title: b.prev().val(),
				category_id: a("#category_select_id").val()
			}
		}).error(function() {
			c()
		}).success(function(d) {
			!0 === d.success ? (a(Mustache.to_html('{{#data}}<li><a href="javascript:;" data-action="albumPopItem" data-params="{{album_id}},{{album_title}}">{{album_title}}</a></li>{{/data}}', d)).insertBefore(b.parent()), s(d.data.album_id, d.data.album_title), b.prev().val("")) : c(d.message)
		})
	}
	function B(b, d) {
			var g = a("#" + d).find(".tg_select_list");
			var j = g.find("ul");
			h = g.attr("data-init");
			f = q.attr("data-tags-url");
		a.ajax({
			type: "get",
			dataType: "json",
			data: {
				dataid: b,
				radom: random(5)
			},
			url: f
		}).error(function() {
			c()
		}).success(function(b) {
			if (!0 === b.success) {
					var k = b.data[0];
					b = a(Mustache.render('{{#data}}<li><a href="javascript:;" data-action="tgItem" data-params="{{tag_id}},{{tag_group_name_cn}},' + d + '">{{tag_group_name_cn}}</a></li>{{/data}}', b));
					j.html(b);
					g.attr("data-init", 1);
					g.find(".tg_select_title").html(k.tag_group_name_cn);
					g.find(".tg_select_id").val(k.tag_id);
			} else c(b.message)
		})
	}
	function y(d, e, f) {
		var g = a("#" + d).find(".category_select_list");
			h = g.attr("data-init");
			i = g.attr("data-find-album");
		if ("0" == h) {
			var j = g.find("ul");
				h = q.attr("data-categorylist-url");
			a.ajax({
				type: "get",
				dataType: "json",
				data: {
					radom: random(5)
				},
				url: h
			}).error(function() {
				b().hide();
				hide_loading();
				c()
			}).success(function(h) {
				hide_loading();
				if (!0 === h.success) {
					var k = a(Mustache.render('{{#data}}<li><a href="javascript:;" data-action="categoryItem" data-params="{{category_id}},{{category_name_cn}},' + d + "," + i + '">{{category_name_cn}}</a></li>{{/data}}', h));
					j.prepend(k);
					null == e || void 0 == e || null == f || void 0 == f ? Q(h.data[0].category_id, h.data[0].category_name_cn, d, i) : Q(e, f, d, i);
					g.attr("data-init", 1)
				} else b().hide(), c(h.message)
			})
		}
	}
	function C(d, e) {
		var f = a("#" + d).find(".smiles");
		if ("0" == f.attr("data-init")) {
			var g = q.attr("data-smiles-url");
			a.ajax({
				type: "get",
				dataType: "json",
				data: {
					radom: random(5)
				},
				url: g
			}).error(function() {
				b().hide();
				hide_loading();
				c()
			}).success(function(d) {
				hide_loading();
				!0 === d.success ? (d = a(Mustache.render('{{#data}}<li><a href="javascript:;" onclick="javascript:$(\'#' + e + "').insertAtCaret('{{code}}',0);\"><img src=\"" + base_url + 'assets/img/smiles/default/{{url}}"></a></li>{{/data}}', d)), f.prepend(d), f.attr("data-init", 1)) : (b().hide(), c(d.message))
			})
		}
	}
	function E(b) {
		var c = a("#" + b).find(".category_select_list");
		a("body").click(function() {
			return !c || c.removeClass("btn_select_hover")
		});
		c.hasClass("btn_select_disabled") || c.addClass("btn_select_hover")
	}
	function E1(b) {
		var c = a("#" + b).find(".tg_select_list");
		a("body").click(function() {
			return !c || c.removeClass("btn_select_hover")
		});
		c.hasClass("btn_select_disabled") || c.addClass("btn_select_hover")
	}
	function E2(b) {
		var c = a("#" + b).find(".weibo_select_list");
		a("body").click(function() {
			return !c || c.removeClass("btn_select_hover")
		});
		c.hasClass("btn_select_disabled") || c.addClass("btn_select_hover")
	}
	function m(b) {
		var c = a("#" + b).find(".album_select_list");
		a("body").click(function(b) {
			b = a(b.target).attr("data-id");
			if (!("album_name" == b || "create_board" == b)) return !c || c.removeClass("btn_select_hover")
		});
		c.hasClass("btn_select_disabled") || c.addClass("btn_select_hover")
	}
	function V(b, d) {
		var e = a("#" + b),
			f = e.find(".album_name"),
			e = e.find(".category_select_id");
		a.ajax({
			type: "post",
			url: q.attr("data-ajax-albumcreate"),
			dataType: "json",
			data: {
				album_title: f.val(),
				category_id: e.val(),
				uid: d
			}
		}).error(function() {
			c()
		}).success(function(d) {
			!0 === d.success ? (a(Mustache.to_html('{{#data}}<li><a href="javascript:;" data-aid="{{album_id}}" data-action="albumItem" data-params="{{album_id}},{{album_title}},' + b + '">{{album_title}}</a></li>{{/data}}', d)).insertBefore(f.parent()), K(d.data.album_id, d.data.album_title, b), f.val("")) : c(d.message)
		})
	}
	function Q(b, c, d, e) {
		var f = a("#" + d),
			g = f.find(".category_select_list");
		g.hasClass("btn_select_hover") && g.removeClass("btn_select_hover");
		g.find(".category_select_title").html(c);
		g.find(".category_select_id").val(b);
		B(b, d);
		0 != e && (null != e && "" != e && void 0 != e) && ((e = f.find(".album_select_list")) ? (c = e.attr("data-album-id"), e = e.attr("data-album-name"), t(d, b, c, e)) : t(d, b, null, null));	
	}
	function Q1(b, c, d) {
		var f = a("#" + d),
		g = f.find(".tg_select_list");
		g.hasClass("btn_select_hover") && g.removeClass("btn_select_hover");
		g.find(".tg_select_title").html(c);
		g.find(".tg_select_id").val(b);
	}
	function Q2(b, c, d) {
		var f = a("#" + d),
		g = f.find(".weibo_select_list");
		g.hasClass("btn_select_hover") && g.removeClass("btn_select_hover");
		g.find(".weibo_title").html(c);
		g.find(".weibo").val(b);
	}
	function K(b, c, d) {
		d = a("#" + d).find(".album_select_list");
		d.hasClass("btn_select_hover") && d.removeClass("btn_select_hover");
		var e = !1;
		null != b && d.find("li").each(function() {
			var c = a(this).find("a").attr("data-aid");
			b == c && (e = !0)
		});
		e ? (d.find(".album_select_title").html(c), d.find(".album_select_id").val(b)) : (d.find(".album_select_title").html(getTip("pls_select")), d.find(".album_select_id").val(""))
	}
	function t(d, e, f, g) {
		var h = a("#" + d),
			i = h.find(".album_select_list"),
			j = i.find("ul"),
			k = q.attr("data-albumlist-url"),
			h = h.attr("data-uid");
		a.ajax({
			type: "get",
			dataType: "json",
			data: {
				cid: e,
				uid: h,
				radom: random(5)
			},
			url: k
		}).error(function() {
			b().hide();
			hide_loading();
			c()
		}).success(function(b) {
			hide_loading();
			if (!0 === b.success) {
				var c = a(Mustache.render('{{#data}}<li><a href="javascript:;" data-aid="{{album_id}}" data-action="albumItem" data-params="{{album_id}},{{album_title}},' + d + '">{{album_title}}</a></li>{{/data}}', b));
				j.html(j.children(":last"));
				c.insertBefore(j.children(":last"));
				null == f || void 0 == f || null == g || void 0 == g ? K(b.data[0].album_id, b.data[0].album_title, d) : K(f, g, d);
				i.attr("data-init", 1)
			} else b = j.children(":last"), j.html(b), K(null, null, d)
		})
	}
	function n(d, e, f, g) {
		var h = a("#" + d),
			i = h.find("ul"),
			d = q.attr("data-albumlist-url");
		a.ajax({
			type: "get",
			dataType: "json",
			data: {
				cid: e,
				radom: random(5)
			},
			url: d
		}).error(function() {
			b().hide();
			hide_loading();
			c()
		}).success(function(b) {
			hide_loading();
			if (!0 === b.success) {
				var c = a(Mustache.render('{{#data}}<li><a href="javascript:;" data-action="albumPopItem" data-params="{{album_id}},{{album_title}}">{{album_title}}</a></li>{{/data}}', b));
				i.html(i.children(":last"));
				c.insertBefore(i.children(":last"));
				null == f || void 0 == f || null == g || void 0 == g ? s(b.data[0].album_id, b.data[0].album_title) : s(f, g);
				h.attr("data-init", 1)
			} else b = i.children(":last"), i.html(b), s(null, null)
		})
	}
	function L(b, d) {
		a.ajax({
			type: "post",
			url: q.attr("data-delcomment-url"),
			dataType: "json",
			data: "sid=" + b + "&hash=" + d
		}).error(function() {
			c()
		}).success(function(b) {
			!0 === b.success ? a("#comment_" + d).fadeTo(1E3, "hide", 0) : c(b.message)
		})
	}
	function F(c) {
		var d = c.split(":"),
			e = a("#crop_dialog_tpl");
		e.attr("data-width", d[0]);
		e.attr("data-height", d[1]);
		var f = e.attr("data-sid"),
			d = {
				path: e.attr("data-imgpath"),
				sid: f
			},
			d = a(Mustache.to_html(e.html(), d));
		b().width(600).head(e.attr("data-title")).body(d).show();
		a("#ui_push_dialog").imagesLoaded(function() {
			w = a("#crop_image_" + f).imgAreaSelect({
				zIndex: 2E3,
				instance: !0,
				aspectRatio: c,
				show: !0,
				x1: 5,
				y1: 5,
				x2: 100,
				y2: 100,
				handles: !0
			})
		})
	}
	function G() {
		var b = a("#login_box_tpl"),
			c = a.oDialog("loginDialog", {
				id: "ui_login_dialog"
			});
		c.width(650).head(b.attr("data-title")).body(b.html());
		c.elem.addClass("dialog-login");
		c.show();
		a.oValidate("login_form");
		a.oValidate("bbs_login_form")
	}
	function H(d, e) {
		show_loading("success");
		var f = a("#push_dialog_tpl");
		a.ajax({
			type: "post",
			url: f.attr("data-fetch-url"),
			dataType: "json",
			data: "cid=" + e
		}).error(function() {
			c()
		}).success(function(c) {
			hide_loading();
			if (!0 === c.success) {
				var d = a(Mustache.to_html(f.html(), c));
				b().width(1E3).head(f.attr("data-title")).body(d).show();
				j(c.data.style)
			} else {
				if (null == z || void 0 == z) z = a.oDialog("alert_message").width(400).head(getTip("error")).body(getTip("server-error"));
				z.body(c.message).show()
			}
		})
	}
	function M(b, d, e, f, g, h, i, j, k) {
		show_loading("success");
		var l = a("#crop_dialog_tpl"),
			m = l.attr("data-width"),
			n = l.attr("data-height"),
			o = l.attr("data-type"),
			p = l.attr("data-style"),
			q = l.attr("data-uid");
		a.ajax({
			type: "post",
			url: l.attr("data-crop-url"),
			dataType: "json",
			data: {
				sid: b,
				cid: d,
				uid: q,
				position: e,
				x: f,
				y: g,
				w: h,
				h: i,
				js_w: j,
				js_h: k,
				ww: m,
				hh: n,
				sy: p
			}
		}).error(function() {
			c()
		}).success(function(a) {
			hide_loading();
			!0 === a.success ? "home" == o ? H(b, d) : "star" == o && starOpen(q, d, b) : c(a.message)
		})
	}
	function v(b) {
		a.ajax({
			type: "post",
			url: q.attr("data-delshare-url"),
			dataType: "json",
			data: "sid=" + b
		}).error(function() {
			c()
		}).success(function(d) {
			!0 === d.success ? (a("#" + b).fadeTo(1E3, "hide", 0), setTimeout(function() {
				a("#waterfall").masonry("remove", a("#" + b));
				a("#waterfall").masonry("reload");
				a("#timeline-box").masonry("remove", a("#" + b));
				a("#timeline-box").masonry("reload", function() {
					resetArrow()
				})
			}, 1E3)) : c(d.message)
		})
	}
	function R(b, d, e, f, g) {
		var h = a("#" + b),
			b = q.attr("data-addcomment-url"),
			i = h.attr("reload");
		a.ajax({
			url: b,
			data: {
				sid: d,
				comment: e,
				type: f
			},
			type: "POST",
			dataType: "json",
			error: function() {
				c()
			},
			success: function(b) {
				!0 === b.success ? (b = a(Mustache.to_html(h.html(), b)), g ? a("#" + d + "_comments").prepend(b) : a("#" + d + "_comments").append(b), b.fadeIn(2E3), a("#" + d + "_commentbox").val(""), i && (a("#" + d + "_commentdiv").addClass("hide"), a("#waterfall").masonry("reload")), check_message("reward", !0, 3E3, 700)) : c(b.message)
			}
		})
	}
	function D(a, b) {
		return ["toolbar=0,status=0,resizable=1,width=" + a + ",height=" + b + ",left=", (screen.width - a) / 2, ",top=", (screen.height - b) / 2].join("")
	}
	function N(b, d, e, f) {
		var g;
		"share" == b ? g = {
			sid: d
		} : "album" == b && (g = {
			aid: d
		});
		a.ajax({
			url: f,
			data: g,
			type: "post",
			dataType: "json",
			success: function(b) {
				!0 === b.success ? (a("#" + e).append('<div id="' + d + '_like_added" class="like_add">' + getTip("add-like") + "</div>"), a("#" + d + "_like_added").fadeTo(3E3, "hide", 0), setTimeout(function() {
					a("#" + d + "_like_added").remove()
				}, 3E3), b = a("#" + d + "_likenum").find("em").text(), a("#" + d + "_likenum").find("em").text(Number(b) + 1), check_message("reward", !0, 3E3, 700)) : "not_login" == a.trim(b.message) ? G() : "like_already" == a.trim(b.message) ? (a("#" + e).append('<div id="' + d + '_like_already" class="like_already">' + getTip("like-already") + "</div>"), a("#" + d + "_like_already").fadeTo(3E3, "hide", 0), setTimeout(function() {
					a("#" + d + "_like_already").remove()
				}, 3E3)) : "like_self" == a.trim(b.message) ? (a("#" + e).append('<div id="' + d + '_like_self" class="like_self">' + getTip("like-self") + "</div>"), a("#" + d + "_like_self").fadeTo(3E3, "hide", 0), setTimeout(function() {
					a("#" + d + "_like_self").remove()
				}, 3E3)) : c(b.message)
			},
			error: function() {
				c()
			}
		})
	}
	function S(b, d, e, f) {
		var g;
		"share" == b ? g = {
			sid: d
		} : "album" == b && (g = {
			aid: d
		});
		a.ajax({
			url: f,
			data: g,
			type: "POST",
			dataType: "json",
			success: function() {
				if (!0 === result.success) {
					a("#" + e).append('<div id="' + d + '_like_added" class="like_add">' + getTip("remove-like") + "</div>");
					a("#" + d + "_like_added").fadeTo(3E3, "hide", 0);
					setTimeout(function() {
						a("#" + d + "_like_added").remove()
					}, 3E3);
					var b = a("#" + d + "_likenum").find("em").text();
					a("#" + d + "_likenum").find("em").text(Number(b) + 1)
				} else "not_login" == a.trim(result.message) ? G() : "not_liked" == a.trim(result.message) ? (a("#" + e).append('<div id="' + d + '_like_already" class="like_already">' + getTip("not_liked") + "</div>"), a("#" + d + "_like_already").fadeTo(3E3, "hide", 0), setTimeout(function() {
					a("#" + d + "_like_already").remove()
				}, 3E3)) : "like_self" == a.trim(result.message) ? (a("#" + e).append('<div id="' + d + '_like_self" class="like_self">' + getTip("like-self") + "</div>"), a("#" + d + "_like_self").fadeTo(3E3, "hide", 0), setTimeout(function() {
					a("#" + d + "_like_self").remove()
				}, 3E3)) : c(result.message)
			},
			error: function() {
				c()
			}
		})
	}
	function O(b, d, e, f, g, h, i) {
		var j = a("#" + d),
			k;
		new AjaxUpload(j, {
			action: b,
			name: "qqfile",
			responseType: "json",
			onSubmit: function() {
				j.text(getTip("select_file"));
				this.disable();
				k = window.setInterval(function() {
					var a = j.text();
					13 > a.length ? j.text(a + ".") : j.text(getTip("uploading"))
				}, 200)
			},
			onComplete: function(b, d) {
				j.text(getTip("select_file"));
				window.clearInterval(k);
				this.enable();
				if (!0 === d.success) {
					var u = d.data.filename + "." + d.data.ext,
						r = base_url + "data/attachments/tmp/" + u;
					g ? (a("#" + f).html('<img class="image_croped" src="' + r + '" style="max-width:' + h + "px;max-height: " + i + 'px;"/>'), a("#" + e).val(u), a("#" + f).imagesLoaded(function() {
						null != w && void 0 != w && w.remove();
						w = a("#" + f + " .image_croped").imgAreaSelect({
							zIndex: 2E3,
							instance: !0,
							show: !0,
							aspectRatio: h + ":" + i,
							x1: 0,
							y1: 0,
							x2: 100,
							y2: 100,
							handles: !0
						})
					})) : (a("#" + e).val(u), a("#" + f).html('<img src="' + r + '" style="max-width:' + h + "px;max-height: " + i + 'px;"/>'))
				} else c(d.message)
			}
		})
	}
	function T(b, d, e, f, g, h, i, j, k) {
		show_loading("success");
		a.ajax({
			type: "post",
			url: b,
			dataType: "json",
			data: "x=" + d + "&y=" + e + "&w=" + f + "&h=" + g + "&js_w=" + h + "&js_h=" + i + "&filename=" + j + "&type=" + k
		}).error(function() {
			c()
		}).success(function(b) {
			hide_loading();
			!0 === b.success ? (null != w && void 0 != w && w.remove(), "avatar" == k ? (a("#avatar_upload_file").val(""), a("#avatar_img_div .image_croped").addClass("hide"), a("#avatar_large_div").removeClass("hide").html('<img src="' + base_url + b.data.avatar_local + "_large.jpg?" + b.data.hash + '" width="150" height="150"/>'), a("#avatar_middle_div").removeClass("hide").html('<img src="' + base_url + b.data.avatar_local + "_middle.jpg?" + b.data.hash + '" width="50" height="50"/>'), a("#avatar_small_div").removeClass("hide").html('<img src="' + base_url + b.data.avatar_local + "_small.jpg?" + b.data.hash + '" width="16" height="16"/>'), a("#upload_avatar_div .actions").html('<button type="submit" data-action="closePushDialog" class="btn btn_red"><span>' + getTip("done") + "</span></button>"), check_message("reward", !0, 3E3, 1500)) : "banner" == k && (a("#banner_upload_file").val(""), a("#banner_img_div").removeClass("hide").html('<img src="' + base_url + b.data.avatar_local + "_banner.jpg?" + b.data.hash + '" width="500" height="158"/>'), a("#upload_banner_div .actions").html('<button type="submit" data-action="closePushDialog" class="btn btn_red"><span>' + getTip("done") + "</span></button>"))) : c(b.message)
		})
	}
	function P() {
		a("input[type=text][title],input[type=password][title],textarea[title]").each(function(b) {
			a(this).addClass("input-prompt-" + b);
			var c = a('<span class="input-prompt"/>');
			a(c).attr("id", "input-prompt-" + b);
			a(c).append(a(this).attr("title"));
			a(c).click(function() {
				a(this).hide();
				a("." + a(this).attr("id")).focus()
			});
			"" != a(this).val() && a(c).hide();
			a(this).before(c);
			a(this).focus(function() {
				a("#input-prompt-" + b).hide()
			});
			a(this).change(function() {
				"" == a(this).val() && a("#input-prompt-" + b).hide()
			});
			a(this).blur(function() {
				"" == a(this).val() && a("#input-prompt-" + b).show()
			})
		})
	}
	var w, I, z, q = a("#data-actions");
	a("#body").actionController({
		controller: {
			focusClick: function(b, c) {
				a("#" + c).focus();
				return !1
			},
			openLoginDialogClick: function() {
				G();
				return !1
			},
			openRegisterDialogClick: function() {
				var b = a.oDialog("loginDialog").width(700).head(getTip("welcome")).body(a("#register_box_tpl").html());
				b.elem.addClass("dialog-login");
				b.show();
				a.oValidate("register_form");
				return !1
			},
			openAvatarClick: function() {
				var c = a("#avatar_tpl");
				b().width(600).head(c.attr("data-title")).body(c.html());
				b().show();
				O(q.attr("data-avatarupload-url"), "avatar_upload_btn", "avatar_upload_file", "avatar_img_div", !0, 180, 180);
				return !1
			},
			closePushDialogClick: function() {
				b().hide();
				return !1
			},
			saveAvatarClick: function() {
				var b = a("#avatar_upload_file").val();
				if (null == b || void 0 == b || "" == b) c(getTip("no-selection"));
				else {
					var d = a("#avatar_img_div .image_croped"),
						e = w.getSelection(),
						f = e.x2 - e.x1,
						g = e.y2 - e.y1,
						h = d.height(),
						d = d.width();
					T(q.attr("data-avatarsave-url"), e.x1, e.y1, f, g, d, h, b, "avatar");
					return !1
				}
			},
			deleteShareClick: function(a, b) {
				confirm(getTip("confirm-delete-share")) && v(b);
				return !1
			},
			delCommentClick: function(a, b, c) {
				confirm(getTip("confirm-delete-comment")) && L(b, c);
				return !1
			},
			addLikeClick: function(a, b) {
				var c = q.attr("data-likeshare-url");
				N("share", b, b + "_image", c);
				return !1
			},
			socialShareClick: function(b, c, d) {
				var b = encodeURIComponent("\u62fc\u56fe\u79c0"),
					e = a("#" + c + "_image").find("img"),
					f = "";
				0 < e.length && (f = a(e[0]).attr("orgin_src"));
				var e = a("#" + c + "_image").attr("href"),
					g = a("#" + c + "_image").find(".video_icon");
				0 < g.length && (e = a(g).attr("orgin_url"), f = a(g).attr("orgin_src"));
				c = a("#" + c + " .share_desc").text();
				g = encodeURIComponent(x(c, 100, "..."));
				e = encodeURIComponent(e);
				f = encodeURIComponent(f);
				"sina" == d ? (b = "http://service.weibo.com/share/share.php?appkey=" + sina_key + "&pic=" + f + "&title=" + g + "&url=" + e + "&relateuid=2664239401", f = D(615, 505), window.open(b, "social_share", f)) : "renren" == d ? (b = "http://share.renren.com/share/buttonshare?link=" + e + "&title=" + g, f = D(626, 436), window.open(b, "social_share", f)) : "qq" == d ? (b = "http://v.t.qq.com/share/share.php?appkey=" + qq_key + "&pic=" + f + "&title=" + g + "&source=" + b + "&url=" + e, f = D(642, 468), window.open(b, "social_share", f)) : "qzone" == d ? (d = encodeURIComponent(c), c = encodeURIComponent(x(c, 30, "...")), b = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?pics=" + f + "&url=" + e + "&title=" + c + "&summary=" + d + "&site=" + b, f = D(634, 668), window.open(b, "social_share", f)) : "twitter" == d && (b = "https://twitter.com/intent/tweet?original_referer=" + e + "&source=tweetbutton&text=" + g + e + "&url=" + e, f = D(634, 505), window.open(b, "social_share", f));
				return !1
			},
			addLikeAlbumClick: function(a, b) {
				var c = q.attr("data-likealbum-url");
				N("album", b, b + "_album", c);
				return !1
			},
			removeLikeAlbumClick: function(a, b) {
				var c = q.attr("data-removelikealbum-url");
				S("album", b, b + "_album", c);
				return !1
			},
			addCommentBoxClick: function(b, c) {
				a("#waterfall").find(".commentdiv").addClass("hide");
				a("#" + c + "_commentdiv").removeClass("hide");
				a("#waterfall").masonry("reload");
				a("#" + c + "_commentbox").focus();
				return !1
			},
			addCommentClick: function(b, d, e, f) {
				b = a("#" + d + "_commentbox").val();
				if (null == b || void 0 == b || "" == a.trim(b)) return c(getTip("not-null")), !1;
				R(e, d, b, "comment", void 0 == f || null == f ? !1 : !0);
				return !1
			},
			openCropClick: function(b, c, d) {
				a("#crop_dialog_tpl").attr("data-position", c);
				F(d);
				return !1
			},
			openPushDialogClick: function(b, c, d, e) {
				a("#crop_dialog_tpl").attr("data-sid", c);
				a("#crop_dialog_tpl").attr("data-cid", d);
				a("#crop_dialog_tpl").attr("data-type", "home");
				a("#crop_dialog_tpl").attr("data-crop-url", a("#push_dialog_tpl").attr("data-crop-url"));
				a("#crop_dialog_tpl").attr("data-imgpath", base_url + e + "_large.jpg");
				H(c, d);
				return !1
			},
			cropBtnClick: function() {
				var b = a("#crop_dialog_tpl").attr("data-sid"),
					c = a("#crop_dialog_tpl").attr("data-cid"),
					d = a("#crop_dialog_tpl").attr("data-position"),
					e = w.getSelection(),
					f = e.x2 - e.x1,
					g = e.y2 - e.y1,
					h = a("#crop_image_" + b).height(),
					i = a("#crop_image_" + b).width();
				M(b, c, d, e.x1, e.y1, f, g, i, h);
				return !1
			},
			openPublishSelectDialogClick: function() {
				var c = a("#publish_select_tpl");
				b().width(c.attr("data-width")).head(c.attr("data-title")).body(c.html());
				b().elem.removeClass("dialog_upload");
				b().elem.addClass("dialog_tools");
				b().show();
				return !1
			},
			openPublishDialogClick: function(c, d) {
				var e = a("#publish_tpl"),
					f = b().width(750).head(e.attr("data-title")).body(e.html());
				f.elem.addClass("dialog_upload");
				f.show();
				a.oValidate("save_share_form");
				O(e.attr("data-upload-url"), "item_upload_btn", "cover_filename", "upload_imgview_div", !1, 150, 180);
				y("category_select_div", null, null);
				C("smiles_div", "publish_intro");
				ii(d);
				P();
				return !1
			},
			listTagsClick: function(b, c, d) {
				b = a("#" + d);
				B(c, b)
			},
			listCategoriesClick: function(a, b) {
				y(b, null, null)
			},
			listSmilesClick: function(a, b, c) {
				C(b, c)
			},
			editAlbumSaveClick: function(b, c, d) {
				d = a("#" + d);
				b = d.find(".album_title").val();
				d = d.find(".category_select_id").val();
				h(c, b, d)
			},
			deleteAlbumClick: function(a, b) {
				confirm(getTip("confirm-delete-album")) && f(b);
				return !1
			},
			confirmRedirectClick: function(a, b, c) {
				confirm(getTip("confirm-" + c)) && (window.location.href = b);
				return !1
			},
			editAlbumOpenClick: function(a, b, c, d, f) {
				e("edit_album_tpl", {
					album_id: b,
					album_title: c
				}, function() {
					y("edit_album_tpl", d, f)
				})
			},
			albumItemClick: function(a, b, c, d) {
				K(b, c, d)
			},
			categoryItemClick: function(a, b, c, d, e) {
				Q(b, c, d, e)
			},
			tgItemClick: function(a, b, c, d) {
				Q1(b, c, d)
			},
			weiboItemClick: function(a, b, c, d) {
				Q2(b, c, d)
			},
			albumItemPopupClick: function(a, b) {
				m(b)
			},
			categoryItemPopupClick: function(a, b) {
				E(b)
			},
			tgItemPopupClick: function(a, b) {
				E1(b)
			},
			weiboPopupClick: function(a, b) {
				E2(b)
			},
			albumListPopupClick: function() {
				p()
			},
			albumPopItemClick: function(a, b, c) {
				s(b, c)
			},
			categoryListPopupClick: function(a, b) {
				o(b)
			},
			categoryPopItemClick: function(a, b, c) {
				J(b, c, "category_select_div", "album_select_div")
			},
			openCatItemClick: function(a, b, c, d) {
				J(b, c, d, null)
			},
			albumCreateClick: function() {
				A()
			},
			albumPopCreateClick: function(a, b, c) {
				V(b, c)
			},
			switchPublishClick: function(a, b) {
				ii(b)
			},
			switchPushStyleClick: function(a, b) {
				j(b)
			},
			fetchRemoteClick: function(b, c) {
				var d = a("#remote_url").val();
				if (null == d || "" == d) return a("#ajax_fetch_message").html(getTip("link-not-null")), !1;
				a("#ajax_fetch_message").html("");
				l(d, c);
				return !1
			},
			preImageClick: function() {
				var b;
				b = a("#publish_image_list").find("li.cover");
				var c = a("#publish_image_list").find("li");
				0 == b.length && a("#upload_imgview_div").html("");
				c = a(b).prev().length ? a(b).prev() : c[c.length - 1];
				a(b).removeClass("cover");
				a(c).addClass("cover");
				b = a(c).find("img").attr("src");
				a("#upload_imgview_div").html('<img src="' + b + '"/>');
				a("#cover_filename").val(a(c).attr("data-name"));
				return !1
			},
			nextImageClick: function() {
				k();
				return !1
			},
			fetchFileItemClick: function(b, c) {
				a(this).parent().parent().find("li").removeClass("active");
				a(this).parent().addClass("active");
				a(this).parent().parent().find("i").addClass("hide");
				a(this).parent().find(".active").removeClass("hide");
				a("#filename").val(c);
				return !1
			},
			editOShareOpenClick: function(a, b) {
				g(b, "edit_item", "item_edit_tpl");
				return !1
			},
			editShareClick: function() {
				return !1
			},
			publishPinItemClick: function() {
				a(this).hasClass("selected") ? a(this).removeClass("selected") : a(this).addClass("selected");
				return !1
			}
		},
		events: "click"
	});
	var x = function(a, b, c) {
			if (a.length <= b) return a;
			for (var d = /https?:\/\/[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)+([-_A-Z0-9a-z\$\.\+\!\*\/,:;@&=\?\~\#\%]*)*/gi, e; null != (e = d.exec(a));) if (e.index < b && e.index + e[0].length > b) return a.substr(0, e.index + e[0].length) + c;
			return a.substr(0, b) + c
		}
});
$(document).ready(function(a) {
	var b = a("#waterfall");
	if (null != b && void 0 != b) {
		var d = Number(b.attr("data-pin-width")),
			c = Number(b.attr("data-animated")),
			c = 1 == c ? !0 : !1;
		a(window).resize(function() {
			if (1 == a("#waterfall_outer").attr("data-fullscreen")) {
				var c = 0,
					f = a("#waterfall_outer .scroll-container");
				null != f && void 0 != f && (c = f.width());
				var h = a(this).width(),
					g = Math.floor(h / d) * d,
					i = (h - g + 10) / 2;
				a("#waterfall_outer").css("width", h);
				a("#waterfall").css("width", g - c);
				c ? (f.css("margin-left", i), a("#waterfall").addClass("inside"), a("#waterfall").css("margin-left", 0)) : (a("#waterfall").addClass("inside"), a("#waterfall").css("margin-left", i));
				a("#header").css("width", g);
				a("#userbar").css("margin-right", 20);
				a("#nav-cat").css("width", g)
			} else if (h = b.width(), g = Math.floor(h / d) * d, f = a("#waterfall_outer .scroll-container"), c = f.width(), null == c || void 0 == c || 0 == c) a("#waterfall_outer").css("width", h), a("#waterfall").css("width", g), a("#waterfall").css("margin-left", (h - g + 10) / 2)
		});
		a(window).resize();
		(function(b) {
			a(b).find(".pin").fadeIn();
			a(b).find(".pin").removeClass("hide");
			a(b).masonry({
				columnWidth: d,
				isAnimated: c,
				itemSelector: ".pin"
			});
			a("#loadingPins").addClass("hide")
		})(b);
		b.infinitescroll({
			navSelector: "#page-nav",
			nextSelector: "#page-nav a",
			itemSelector: ".pin",
			bufferPx: 500,
			loading: {
				finishedMsg: getTip("load_complete"),
				img: base_url + "assets/img/ajax-loader.gif",
				msg: "test mesg",
				msgText: getTip("loading")
			}
		}, function(c) {
			c = a(c);
			a(c).removeClass("hide");
			a(c).fadeIn();
			b.masonry("appended", a(c), !0)
		})
	}
});
(function(a) {
	function b() {}
	var d = null;
	a.sclogin = function() {
		d || (d = new b);
		return d
	};
	b.prototype = {
		scrollTimer: null,
		_position: 0,
		startScroll: function() {
			var b = this;
			b._position = a(window).scrollTop();
			b.scrollTimer = setInterval(function() {
				b._position += 5;
				a("body,html").animate({
					scrollTop: b._position
				}, 220);
				a("body").css("overflow", "hidden")
			}, 220)
		},
		stopScroll: function() {
			this.scrollTimer && (clearInterval(this.scrollTimer), this.scrollTimer = null, a("body, html").clearQueue().stop(!0, !0), a("body").css("overflow", ""))
		},
		start: function() {
			var b = this;
			setTimeout(function() {
				var d = a.oDialog("loginDialog");
				if (!d || !d.elem || !("none" !== d.elem.css("display") && "hidden" !== d.elem.css("visibility"))) a("#login_point").click(), d = a(a.oDialog("loginDialog")), d.bind("hide", function() {
					b.stopScroll()
				}), d[0].elem.find("input").bind("focus", function() {
					b.stopScroll()
				}), b.startScroll(), setTimeout(function() {
					b.stopScroll()
				}, 12E4)
			}, 1E4)
		}
	}
})(jQuery);
(function(a) {
	function b(b, e, f) {
		var h = a('<ul class="pages"></ul>');
		h.append(d("\u9996\u9875", b, e, f)).append(d("\u4e0a\u4e00\u9875", b, e, f));
		var g = 1,
			i = 9;
		4 < b && (g = b - 4, i = b + 4);
		i > e && (g = e - 8, i = e);
		for (1 > g && (g = 1); g <= i; g++) {
			var j = a('<li class="page-number">' + g + "</li>");
			g == b ? j.addClass("pgCurrent") : j.click(function() {
				f(this.firstChild.data)
			});
			j.appendTo(h)
		}
		h.append(d("\u4e0b\u4e00\u9875", b, e, f)).append(d("\u672b\u9875", b, e, f));
		return h
	}
	function d(b, d, f, h) {
		var g = a('<li class="pgNext">' + b + "</li>"),
			i = 1;
		switch (b) {
		case "\u9996\u9875":
			i = 1;
			break;
		case "\u4e0a\u4e00\u9875":
			i = d - 1;
			break;
		case "\u4e0b\u4e00\u9875":
			i = d + 1;
			break;
		case "\u672b\u9875":
			i = f
		}
		"\u9996\u9875" == b || "\u4e0a\u4e00\u9875" == b ? 1 >= d ? g.addClass("pgEmpty") : g.click(function() {
			h(i)
		}) : d >= f ? g.addClass("pgEmpty") : g.click(function() {
			h(i)
		});
		return g
	}
	a.fn.pager = function(c) {
		a.extend({}, a.fn.pager.defaults, c);
		return this.each(function() {
			a(this).empty().append(b(parseInt(c.pagenumber), parseInt(c.pagecount), c.buttonClickCallback));
			a(".pages li").mouseover(function() {
				document.body.style.cursor = "pointer"
			}).mouseout(function() {
				document.body.style.cursor = "auto"
			})
		})
	};
	a.fn.pager.defaults = {
		pagenumber: 1,
		pagecount: 1
	}
})(jQuery);
