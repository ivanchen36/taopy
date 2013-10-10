function TWinOpen(url, id, iWidth, iHeight) {
	var iTop = (screen.height - 30 - iHeight) / 2; // 获得窗口的垂直位置;
	var iLeft = (screen.width - 10 - iWidth) / 2; // 获得窗口的水平位置;
	TWin = window.showModalDialog(url, null, "dialogWidth=" + iWidth
			+ "px;dialogHeight=" + iHeight + "px;dialogTop=" + iTop
			+ "px;dialogLeft=" + iLeft + "px");
}

function iFrameHeight(frame) {
	var ifm = $(frame);
	var subWeb = document.frames ? document.frames[frame].document
			: ifm.contentDocument;
	if (ifm != null && subWeb != null) {
		ifm.height = subWeb.body.scrollHeight;
	}
}
function random(length) {
	var charactors = "ab1cd2ef3gh4ij5kl6mn7opq8rst9uvw0xyz";
	var value = '', i;
	for ( var j = 1; j <= length; j++) {
		i = parseInt(35 * Math.random());
		value = value + charactors.charAt(i);
	}
	return value;
}

function mb_cutstr(str, maxlen, dot) {
	var len = 0;
	var ret = '';
	var dot = '';
	maxlen = maxlen - dot.length;
	for ( var i = 0; i < str.length; i++) {
		len++;
		if (str.charCodeAt(i) > 127) {
			len++;
		}
		if (len > maxlen) {
			ret += dot;
			break;
		}
		ret += str.substr(i, 1);
	}
	return ret;
}
function strLenCalc(obj, checklen, maxlen) {
	var v = obj.value, curlen = maxlen, len = str_length(v);
	if (curlen >= len) {
		$('#' + checklen).html(curlen - len);
	} else {
		obj.value = mb_cutstr(v, maxlen, 0);
	}
}

function getTip(type){
	var tips = $("#data-tips");
	var message = tips.attr('data-'+type);
	if(message!=null&&message!=undefined&&message!=='')
		return message;
	return type;
}
//reward,alert
function check_message(event,autohide,time,waittime){
	return true;
}
//tip,success,error,danger,info
function show_message(type,message,autohide,time){
	var messageDialog = $.oDialog('messageDialog', {
		id: 'ui_message_dialog',
		width : '100',
		center : true,
		mask : false,
		close : !autohide,
		className : 'ui-message',
		tpl : '<div id="%ID" class="%CLASSNAME"><a class="ui-close" href="#close">X</a><div class="bd"><div class="ui-loading">正在加载...</div></div></div>'
	});
	var str_width = str_length(message)*8+30;
	var d_width = str_width>500?500:str_width;
	d_width = d_width<170?170:d_width;
	messageDialog.width(d_width).body(message).show();
	messageDialog.elem.addClass('message-'+type);
	messageDialog.show();
	if(autohide){
		setTimeout(function() {
			messageDialog.hide();
		}, time);
	}
}
function show_loading(type){
	var messageDialog = $.oDialog('loadingDialog', {
		id: 'ui_loading_dialog',
		width : '250',
		center : true,
		mask : false,
		close : false,
		className : 'ui-message',
		tpl : '<div id="%ID" class="%CLASSNAME"><a class="ui-close" href="#close">X</a><div class="bd progress progress-striped active"><div class="bar" style="width:20%;"></div></div></div>'
	});
	messageDialog.width(280).show();
	messageDialog.elem.addClass('progress-'+type);
	//messageDialog.elem.find('.bar').css('width','50%');
	messageDialog.show();
}

function hide_loading(){
	var messageDialog = $.oDialog('loadingDialog');
	setTimeout(function() {messageDialog.elem.find('.bar').css('width','100%');}, 500);
	setTimeout(function() {messageDialog.hide();messageDialog.elem.find('.bar').css('width','20%');}, 1000);
}
function str_length(value) {
	var length = value.length;
	for ( var i = 0; i < value.length; i++) {
		if (value.charCodeAt(i) > 127) {
			length++;
		}
	}
	return length;
}
var imageReady = (function() {
	var list = [], timer = null, prop = [ [ 'width', 'height' ],
			[ 'naturalWidth', 'naturalHeight' ] ], natural = Number(typeof document
			.createElement('img').naturalHeight === 'number'), // 是否支持HTML5新增的
	// naturalHeight
	tick = function() {
		for ( var i = 0; i < list.length; i++) {
			list[i].end ? list.splice(i--, 1) : check.call(list[i], null);
		}
		list.length && (timer = setTimeout(tick, 50)) || (timer = null);
	},
	/**
	 * overflow: 检测图片尺寸的改变 img.__width,img.__height: 初载入时的尺寸
	 */
	check = function() {
		if (this[prop[natural][0]] !== this.__width
				|| this[prop[natural][1]] !== this.__height
				|| this[prop[natural][0]] * this[prop[natural][1]] > 1024) {
			this.onready.call(this, null);
			this.end = true;
		}
	};
	return function(_img,r_url, onready, onload, onerror) {
		r_url = r_url || '';
		onready = onready || new Function(r_url);
		onload = onload || new Function(r_url);
		onerror = onerror || new Function(r_url);
		var img = typeof _img == 'string' ? new Image() : _img;
		img.onerror = function() {// ie && ie<=8 的浏览器必须在src赋予前定义onerror
			onerror.call(img, r_url);
			img.end = true;
			img = img.onload = img.onerror = img.onreadystatechange = null;
		};
		if (typeof _img == 'string')
			img.src = _img;
		if (!img)
			return;
		img.__width = img[prop[natural][0]];
		img.__height = img[prop[natural][1]];
		if (img.complete) {
			onready.call(img, r_url);
			onload.call(img, r_url);
			return;
		}
		img.onready = function(){onready.call(img, r_url);};
		check.call(img, null);
		img.onload = img.onreadystatechange = function() {
			if (img && img.readyState && img.readyState != 'loaded'
					&& img.readyState != 'complete') {
				return;
			}
			!img.end && check.call(img, null);
			onload.call(img, r_url);
			img = img.onload = img.onerror = img.onreadystatechange = null;
		};
		if (!img.end) {
			list.push(img);
			if (timer === null)
				timer = setTimeout(tick, 50);
		}
	};
})();

function preloadImages(images, onready, onload, onerror, callback) {
	callback = callback || new Function();
	onready = onready || new Function();
	onerror = onerror || new Function();
	onload = onload || new Function();
	var count = images.length;
	if (count === 0) {
		callback.call(images, null);
	}
	var loaded = 0;
	for ( var i = 0; i < count; i++) {
		var img = images[i];
		imageReady(img, function() {
			// loaded++;
			onready.call(this, null);
			if (i == count) {
				callback.call(images, null);
			}
		}, function() {
			onload.call(this, null);
		}, function() {
			// loaded++;
			onerror.call(this, null);
			if (i == count) {
				callback.call(images, null);
			}
		});
	}

	/*
	 * $(images).each(function() { imageReady(this, function() { loaded++;
	 * onready.call(this, null); if (loaded == count) { callback.call(images,
	 * null); } },function() { onload.call(this, null); }, function() {
	 * loaded++; onerror.call(this, null); if (loaded == count) {
	 * callback.call(images, null); } }); });
	 */
	// alert(count);
}
/*
 * function preloadImages(images, callback) { var count = images.length;
 * if(count === 0) { callback(); } var loaded = 0; $(images).each(function() {
 * imageReady(this, function() { loaded++; if (loaded === count) { callback(); }
 * }); }); };
 */
$.fn.extend({
	insertAtCaret : function(myValue, needSelect) {
		return this.each(function(i) {
			if (document.selection) {
				// For browsers like Internet Explorer
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				if (needSelect) {
					sel.moveStart("character", -(myValue.length - 1));
					sel.moveEnd("character", -1);
					sel.select();
				}
			} else if (this.selectionStart || this.selectionStart == '0') {
				// For browsers like Firefox and Webkit based
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				this.value = this.value.substring(0, startPos) + myValue
						+ this.value.substring(endPos, this.value.length);
				this.focus();

				if (needSelect) {
					this.selectionStart = startPos + 1;
					this.selectionEnd = startPos + myValue.length - 1;
				} else {
					this.selectionStart = startPos + myValue.length;
					this.selectionEnd = startPos + myValue.length;
				}
				this.scrollTop = scrollTop;
			} else {
				this.value += myValue;
				this.focus();
			}
		});
	}
});
function utf8_encode(argString) {
	// Encodes an ISO-8859-1 string to UTF-8
	// 
	// version: 1109.2015
	// discuss at: http://phpjs.org/functions/utf8_encode
	// + original by: Webtoolkit.info (http://www.webtoolkit.info/)
	// + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// + improved by: sowberry
	// + tweaked by: Jack
	// + bugfixed by: Onno Marsman
	// + improved by: Yves Sucaet
	// + bugfixed by: Onno Marsman
	// + bugfixed by: Ulrich
	// + bugfixed by: Rafal Kukawski
	// * example 1: utf8_encode('Kevin van Zonneveld');
	// * returns 1: 'Kevin van Zonneveld'
	if (argString === null || typeof argString === "undefined") {
		return "";
	}

	var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g,
									// "\n");
	var utftext = "", start, end, stringl = 0;

	start = end = 0;
	stringl = string.length;
	for ( var n = 0; n < stringl; n++) {
		var c1 = string.charCodeAt(n);
		var enc = null;

		if (c1 < 128) {
			end++;
		} else if (c1 > 127 && c1 < 2048) {
			enc = String.fromCharCode((c1 >> 6) | 192)
					+ String.fromCharCode((c1 & 63) | 128);
		} else {
			enc = String.fromCharCode((c1 >> 12) | 224)
					+ String.fromCharCode(((c1 >> 6) & 63) | 128)
					+ String.fromCharCode((c1 & 63) | 128);
		}
		if (enc !== null) {
			if (end > start) {
				utftext += string.slice(start, end);
			}
			utftext += enc;
			start = end = n + 1;
		}
	}

	if (end > start) {
		utftext += string.slice(start, stringl);
	}

	return utftext;
}
function serialize(mixed_value) {
	// Returns a string representation of variable (which can later be
	// unserialized)
	// 
	// version: 1109.2015
	// discuss at: http://phpjs.org/functions/serialize
	// + original by: Arpad Ray (mailto:arpad@php.net)
	// + improved by: Dino
	// + bugfixed by: Andrej Pavlovic
	// + bugfixed by: Garagoth
	// + input by: DtTvB
	// (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
	// + bugfixed by: Russell Walker (http://www.nbill.co.uk/)
	// + bugfixed by: Jamie Beck (http://www.terabit.ca/)
	// + input by: Martin (http://www.erlenwiese.de/)
	// + bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
	// + improved by: Le Torbi (http://www.letorbi.de/)
	// + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
	// + bugfixed by: Ben (http://benblume.co.uk/)
	// - depends on: utf8_encode
	// % note: We feel the main purpose of this function should be to ease the
	// transport of data between php & js
	// % note: Aiming for PHP-compatibility, we have to translate objects to
	// arrays
	// * example 1: serialize(['Kevin', 'van', 'Zonneveld']);
	// * returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
	// * example 2: serialize({firstName: 'Kevin', midName: 'van', surName:
	// 'Zonneveld'});
	// * returns 2:
	// 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
	var _utf8Size = function(str) {
		var size = 0, i = 0, l = str.length, code = '';
		for (i = 0; i < l; i++) {
			code = str.charCodeAt(i);
			if (code < 0x0080) {
				size += 1;
			} else if (code < 0x0800) {
				size += 2;
			} else {
				size += 3;
			}
		}
		return size;
	};
	var _getType = function(inp) {
		var type = typeof inp, match;
		var key;

		if (type === 'object' && !inp) {
			return 'null';
		}
		if (type === "object") {
			if (!inp.constructor) {
				return 'object';
			}
			var cons = inp.constructor.toString();
			match = cons.match(/(\w+)\(/);
			if (match) {
				cons = match[1].toLowerCase();
			}
			var types = [ "boolean", "number", "string", "array" ];
			for (key in types) {
				if (cons == types[key]) {
					type = types[key];
					break;
				}
			}
		}
		return type;
	};
	var type = _getType(mixed_value);
	var val, ktype = '';

	switch (type) {
	case "function":
		val = "";
		break;
	case "boolean":
		val = "b:" + (mixed_value ? "1" : "0");
		break;
	case "number":
		val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":"
				+ mixed_value;
		break;
	case "string":
		val = "s:" + _utf8Size(mixed_value) + ":\"" + mixed_value + "\"";
		break;
	case "array":
	case "object":
		val = "a";
		/*
		 * if (type == "object") { var objname =
		 * mixed_value.constructor.toString().match(/(\w+)\(\)/); if (objname ==
		 * undefined) { return; } objname[1] = this.serialize(objname[1]); val =
		 * "O" + objname[1].substring(1, objname[1].length - 1); }
		 */
		var count = 0;
		var vals = "";
		var okey;
		var key;
		for (key in mixed_value) {
			if (mixed_value.hasOwnProperty(key)) {
				ktype = _getType(mixed_value[key]);
				if (ktype === "function") {
					continue;
				}

				okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
				vals += this.serialize(okey) + this.serialize(mixed_value[key]);
				count++;
			}
		}
		val += ":" + count + ":{" + vals + "}";
		break;
	case "undefined":
		// Fall-through
	default:
		// if the JS object has a property which contains a null value, the
		// string cannot be unserialized by PHP
		val = "N";
		break;
	}
	if (type !== "object" && type !== "array") {
		val += ";";
	}
	return val;
}