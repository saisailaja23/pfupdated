/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-cssanimations-csstransitions-touch-shiv-cssclasses-prefixed-teststyles-testprop-testallprops-prefixes-domprefixes-load
 */
;
window.Modernizr = function(a, b, c) {
  function z(a) {
    j.cssText = a
  }

  function A(a, b) {
    return z(m.join(a + ";") + (b || ""))
  }

  function B(a, b) {
    return typeof a === b
  }

  function C(a, b) {
    return !!~("" + a).indexOf(b)
  }

  function D(a, b) {
    for (var d in a) {
      var e = a[d];
      if (!C(e, "-") && j[e] !== c)
	return b == "pfx" ? e : !0
    }
    return !1
  }

  function E(a, b, d) {
    for (var e in a) {
      var f = b[a[e]];
      if (f !== c)
	return d === !1 ? a[e] : B(f, "function") ? f.bind(d || b) : f
    }
    return !1
  }

  function F(a, b, c) {
    var d = a.charAt(0).toUpperCase() + a.slice(1),
      e = (a + " " + o.join(d + " ") + d).split(" ");
    return B(b, "string") || B(b, "undefined") ? D(e, b) : (e = (a + " " + p.join(d + " ") + d).split(" "), E(e, b, c))
  }
  var d = "2.6.2",
    e = {},
    f = !0,
    g = b.documentElement,
    h = "modernizr",
    i = b.createElement(h),
    j = i.style,
    k, l = {}.toString,
    m = " -webkit- -moz- -o- -ms- ".split(" "),
    n = "Webkit Moz O ms",
    o = n.split(" "),
    p = n.toLowerCase().split(" "),
    q = {},
    r = {},
    s = {},
    t = [],
    u = t.slice,
    v, w = function(a, c, d, e) {
      var f, i, j, k, l = b.createElement("div"),
	m = b.body,
	n = m || b.createElement("body");
      if (parseInt(d, 10))
	while (d--)
	  j = b.createElement("div"), j.id = e ? e[d] : h + (d + 1), l.appendChild(j);
      return f = ["&#173;", '<style id="s', h, '">', a, "</style>"].join(""), l.id = h, (m ? l : n).innerHTML += f, n.appendChild(l), m || (n.style.background = "", n.style.overflow = "hidden", k = g.style.overflow, g.style.overflow = "hidden", g.appendChild(n)), i = c(l, a), m ? l.parentNode.removeChild(l) : (n.parentNode.removeChild(n), g.style.overflow = k), !!i
    },
    x = {}.hasOwnProperty,
    y;
  !B(x, "undefined") && !B(x.call, "undefined") ? y = function(a, b) {
    return x.call(a, b)
  } : y = function(a, b) {
    return b in a && B(a.constructor.prototype[b], "undefined")
  }, Function.prototype.bind || (Function.prototype.bind = function(b) {
    var c = this;
    if (typeof c != "function")
      throw new TypeError;
    var d = u.call(arguments, 1),
      e = function() {
	if (this instanceof e) {
	  var a = function() {
	  };
	  a.prototype = c.prototype;
	  var f = new a,
	    g = c.apply(f, d.concat(u.call(arguments)));
	  return Object(g) === g ? g : f
	}
	return c.apply(b, d.concat(u.call(arguments)))
      };
    return e
  }), q.touch = function() {
    var c;
    return "ontouchstart" in a || a.DocumentTouch && b instanceof DocumentTouch ? c = !0 : w(["@media (", m.join("touch-enabled),("), h, ")", "{#modernizr{top:9px;position:absolute}}"].join(""), function(a) {
      c = a.offsetTop === 9
    }), c
  }, q.cssanimations = function() {
    return F("animationName")
  }, q.csstransitions = function() {
    return F("transition")
  };
  for (var G in q)
    y(q, G) && (v = G.toLowerCase(), e[v] = q[G](), t.push((e[v] ? "" : "no-") + v));
  return e.addTest = function(a, b) {
    if (typeof a == "object")
      for (var d in a)
	y(a, d) && e.addTest(d, a[d]);
    else {
      a = a.toLowerCase();
      if (e[a] !== c)
	return e;
      b = typeof b == "function" ? b() : b, typeof f != "undefined" && f && (g.className += " " + (b ? "" : "no-") + a), e[a] = b
    }
    return e
  }, z(""), i = k = null,
    function(a, b) {
      function k(a, b) {
	var c = a.createElement("p"),
	  d = a.getElementsByTagName("head")[0] || a.documentElement;
	return c.innerHTML = "x<style>" + b + "</style>", d.insertBefore(c.lastChild, d.firstChild)
      }

      function l() {
	var a = r.elements;
	return typeof a == "string" ? a.split(" ") : a
      }

      function m(a) {
	var b = i[a[g]];
	return b || (b = {}, h++, a[g] = h, i[h] = b), b
      }

      function n(a, c, f) {
	c || (c = b);
	if (j)
	  return c.createElement(a);
	f || (f = m(c));
	var g;
	return f.cache[a] ? g = f.cache[a].cloneNode() : e.test(a) ? g = (f.cache[a] = f.createElem(a)).cloneNode() : g = f.createElem(a), g.canHaveChildren && !d.test(a) ? f.frag.appendChild(g) : g
      }

      function o(a, c) {
	a || (a = b);
	if (j)
	  return a.createDocumentFragment();
	c = c || m(a);
	var d = c.frag.cloneNode(),
	  e = 0,
	  f = l(),
	  g = f.length;
	for (; e < g; e++)
	  d.createElement(f[e]);
	return d
      }

      function p(a, b) {
	b.cache || (b.cache = {}, b.createElem = a.createElement, b.createFrag = a.createDocumentFragment, b.frag = b.createFrag()), a.createElement = function(c) {
	  return r.shivMethods ? n(c, a, b) : b.createElem(c)
	}, a.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + l().join().replace(/\w+/g, function(a) {
	  return b.createElem(a), b.frag.createElement(a), 'c("' + a + '")'
	}) + ");return n}")(r, b.frag)
      }

      function q(a) {
	a || (a = b);
	var c = m(a);
	return r.shivCSS && !f && !c.hasCSS && (c.hasCSS = !!k(a, "article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")), j || p(a, c), a
      }
      var c = a.html5 || {},
	d = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,
	e = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,
	f, g = "_html5shiv",
	h = 0,
	i = {},
	j;
      (function() {
	try {
	  var a = b.createElement("a");
	  a.innerHTML = "<xyz></xyz>", f = "hidden" in a, j = a.childNodes.length == 1 || function() {
	    b.createElement("a");
	    var a = b.createDocumentFragment();
	    return typeof a.cloneNode == "undefined" || typeof a.createDocumentFragment == "undefined" || typeof a.createElement == "undefined"
	  }()
	} catch (c) {
	  f = !0, j = !0
	}
      })();
      var r = {
	elements: c.elements || "abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",
	shivCSS: c.shivCSS !== !1,
	supportsUnknownElements: j,
	shivMethods: c.shivMethods !== !1,
	type: "default",
	shivDocument: q,
	createElement: n,
	createDocumentFragment: o
      };
      a.html5 = r, q(b)
    }(this, b), e._version = d, e._prefixes = m, e._domPrefixes = p, e._cssomPrefixes = o, e.testProp = function(a) {
    return D([a])
  }, e.testAllProps = F, e.testStyles = w, e.prefixed = function(a, b, c) {
    return b ? F(a, b, c) : F(a, "pfx")
  }, g.className = g.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + (f ? " js " + t.join(" ") : ""), e
}(this, this.document),
  function(a, b, c) {
    function d(a) {
      return "[object Function]" == o.call(a)
    }

    function e(a) {
      return "string" == typeof a
    }

    function f() {
    }

    function g(a) {
      return !a || "loaded" == a || "complete" == a || "uninitialized" == a
    }

    function h() {
      var a = p.shift();
      q = 1, a ? a.t ? m(function() {
	("c" == a.t ? B.injectCss : B.injectJs)(a.s, 0, a.a, a.x, a.e, 1)
      }, 0) : (a(), h()) : q = 0
    }

    function i(a, c, d, e, f, i, j) {
      function k(b) {
	if (!o && g(l.readyState) && (u.r = o = 1, !q && h(), l.onload = l.onreadystatechange = null, b)) {
	  "img" != a && m(function() {
	    t.removeChild(l)
	  }, 50);
	  for (var d in y[c])
	    y[c].hasOwnProperty(d) && y[c][d].onload()
	}
      }
      var j = j || B.errorTimeout,
	l = b.createElement(a),
	o = 0,
	r = 0,
	u = {
	  t: d,
	  s: c,
	  e: f,
	  a: i,
	  x: j
	};
      1 === y[c] && (r = 1, y[c] = []), "object" == a ? l.data = c : (l.src = c, l.type = a), l.width = l.height = "0", l.onerror = l.onload = l.onreadystatechange = function() {
	k.call(this, r)
      }, p.splice(e, 0, u), "img" != a && (r || 2 === y[c] ? (t.insertBefore(l, s ? null : n), m(k, j)) : y[c].push(l))
    }

    function j(a, b, c, d, f) {
      return q = 0, b = b || "j", e(a) ? i("c" == b ? v : u, a, b, this.i++, c, d, f) : (p.splice(this.i++, 0, a), 1 == p.length && h()), this
    }

    function k() {
      var a = B;
      return a.loader = {
	load: j,
	i: 0
      }, a
    }
    var l = b.documentElement,
      m = a.setTimeout,
      n = b.getElementsByTagName("script")[0],
      o = {}.toString,
      p = [],
      q = 0,
      r = "MozAppearance" in l.style,
      s = r && !!b.createRange().compareNode,
      t = s ? l : n.parentNode,
      l = a.opera && "[object Opera]" == o.call(a.opera),
      l = !!b.attachEvent && !l,
      u = r ? "object" : l ? "script" : "img",
      v = l ? "script" : u,
      w = Array.isArray || function(a) {
	return "[object Array]" == o.call(a)
      },
      x = [],
      y = {},
      z = {
	timeout: function(a, b) {
	  return b.length && (a.timeout = b[0]), a
	}
      },
    A, B;
    B = function(a) {
      function b(a) {
	var a = a.split("!"),
	  b = x.length,
	  c = a.pop(),
	  d = a.length,
	  c = {
	    url: c,
	    origUrl: c,
	    prefixes: a
	  },
	e, f, g;
	for (f = 0; f < d; f++)
	  g = a[f].split("="), (e = z[g.shift()]) && (c = e(c, g));
	for (f = 0; f < b; f++)
	  c = x[f](c);
	return c
      }

      function g(a, e, f, g, h) {
	var i = b(a),
	  j = i.autoCallback;
	i.url.split(".").pop().split("?").shift(), i.bypass || (e && (e = d(e) ? e : e[a] || e[g] || e[a.split("/").pop().split("?")[0]]), i.instead ? i.instead(a, e, f, g, h) : (y[i.url] ? i.noexec = !0 : y[i.url] = 1, f.load(i.url, i.forceCSS || !i.forceJS && "css" == i.url.split(".").pop().split("?").shift() ? "c" : c, i.noexec, i.attrs, i.timeout), (d(e) || d(j)) && f.load(function() {
	  k(), e && e(i.origUrl, h, g), j && j(i.origUrl, h, g), y[i.url] = 2
	})))
      }

      function h(a, b) {
	function c(a, c) {
	  if (a) {
	    if (e(a))
	      c || (j = function() {
		var a = [].slice.call(arguments);
		k.apply(this, a), l()
	      }), g(a, j, b, 0, h);
	    else if (Object(a) === a)
	      for (n in m = function() {
		var b = 0,
		  c;
		for (c in a)
		  a.hasOwnProperty(c) && b++;
		return b
	      }(), a)
		a.hasOwnProperty(n) && (!c && !--m && (d(j) ? j = function() {
		  var a = [].slice.call(arguments);
		  k.apply(this, a), l()
		} : j[n] = function(a) {
		  return function() {
		    var b = [].slice.call(arguments);
		    a && a.apply(this, b), l()
		  }
		}(k[n])), g(a[n], j, b, n, h))
	  } else
	    !c && l()
	}
	var h = !!a.test,
	  i = a.load || a.both,
	  j = a.callback || f,
	  k = j,
	  l = a.complete || f,
	  m, n;
	c(h ? a.yep : a.nope, !!i), i && c(i)
      }
      var i, j, l = this.yepnope.loader;
      if (e(a))
	g(a, 0, l, 0);
      else if (w(a))
	for (i = 0; i < a.length; i++)
	  j = a[i], e(j) ? g(j, 0, l, 0) : w(j) ? B(j) : Object(j) === j && h(j, l);
      else
	Object(a) === a && h(a, l)
    }, B.addPrefix = function(a, b) {
      z[a] = b
    }, B.addFilter = function(a) {
      x.push(a)
    }, B.errorTimeout = 1e4, null == b.readyState && b.addEventListener && (b.readyState = "loading", b.addEventListener("DOMContentLoaded", A = function() {
      b.removeEventListener("DOMContentLoaded", A, 0), b.readyState = "complete"
    }, 0)), a.yepnope = k(), a.yepnope.executeStack = h, a.yepnope.injectJs = function(a, c, d, e, i, j) {
      var k = b.createElement("script"),
	l, o, e = e || B.errorTimeout;
      k.src = a;
      for (o in d)
	k.setAttribute(o, d[o]);
      c = j ? h : c || f, k.onreadystatechange = k.onload = function() {
	!l && g(k.readyState) && (l = 1, c(), k.onload = k.onreadystatechange = null)
      }, m(function() {
	l || (l = 1, c(1))
      }, e), i ? k.onload() : n.parentNode.insertBefore(k, n)
    }, a.yepnope.injectCss = function(a, c, d, e, g, i) {
      var e = b.createElement("link"),
	j, c = i ? h : c || f;
      e.href = a, e.rel = "stylesheet", e.type = "text/css";
      for (j in d)
	e.setAttribute(j, d[j]);
      g || (n.parentNode.insertBefore(e, n), m(c, 0))
    }
  }(this, document), Modernizr.load = function() {
  yepnope.apply(window, [].slice.call(arguments, 0))
};

/* ------------------------------------------------------------------------
 Class: prettyPhoto
 Use: Lightbox clone for jQuery
 Author: Stephane Caron (http://www.no-margin-for-errors.com)
 Version: 3.1.5
 ------------------------------------------------------------------------- */
(function($) {
  $.prettyPhoto = {
    version: '3.1.5'
  };

  $.fn.prettyPhoto = function(pp_settings) {
    pp_settings = jQuery.extend({
      hook: 'rel',
      /* the attribute tag to use for prettyPhoto hooks. default: 'rel'. For HTML5, use "data-rel" or similar. */
      animation_speed: 'fast',
      /* fast/slow/normal */
      ajaxcallback: function() {
      },
      slideshow: 5000,
      /* false OR interval time in ms */
      autoplay_slideshow: false,
      /* true/false */
      opacity: 0.80,
      /* Value between 0 and 1 */
      show_title: true,
      /* true/false */
      allow_resize: true,
      /* Resize the photos bigger than viewport. true/false */
      allow_expand: false,
      /* Allow the user to expand a resized image. true/false */
      default_width: 500,
      default_height: 344,
      min_image_width: 200,
      counter_separator_label: '/',
      /* The separator for the gallery counter 1 "of" 2 */
      theme: 'pp_default',
      /* light_rounded / dark_rounded / light_square / dark_square / facebook */
      horizontal_padding: 20,
      /* The padding on each side of the picture */
      hideflash: false,
      /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
      wmode: 'opaque',
      /* Set the flash wmode attribute */
      autoplay: true,
      /* Automatically start videos: True/False */
      modal: false,
      /* If set to true, only the close button will close the window */
      deeplinking: false,
      /* Allow prettyPhoto to update the url to enable deeplinking. */
      overlay_gallery: true,
      /* If set to true, a gallery will overlay the fullscreen image on mouse over */
      overlay_gallery_max: 250,
      /* Maximum number of pictures in the overlay gallery */
      keyboard_shortcuts: true,
      /* Set to false if you open forms inside prettyPhoto */
      changepicturecallback: function() {
      },
      /* Called everytime an item is shown/changed */
      callback: function() {
      },
      /* Called when prettyPhoto is closed */
      ie6_fallback: true,
      markup: '<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_top"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
						<div class="pp_content_container"> \
							<div class="pp_left"> \
							<div class="pp_right"> \
								<div class="pp_content"> \
								<a class="pp_close" href="#">Close</a> \
									<div class="pp_loaderIcon"></div> \
									<div class="pp_fade"> \
										<a href="#" class="pp_expand" title="Expand the image">Expand</a> \
										<div class="pp_hoverContainer"> \
											<a class="pp_next" href="#">next</a> \
											<a class="pp_previous" href="#">previous</a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
											<div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous">Previous</a> \
												<p class="currentTextHolder">0/0</p> \
												<a href="#" class="pp_arrow_next">Next</a> \
											</div> \
											<p class="pp_description"></p> \
										</div> \
									</div> \
								</div> \
							</div> \
							</div> \
						</div> \
					</div> \
					<div class="pp_overlay"></div>',
      gallery_markup: '<div class="pp_gallery"> \
								<a href="#" class="pp_arrow_previous">Previous</a> \
								<div> \
									<ul> \
										{gallery} \
									</ul> \
								</div> \
								<a href="#" class="pp_arrow_next">Next</a> \
							</div>',
      image_markup: '<img id="fullResImage" src="{path}" rel="{id}" />',
      flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
      quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
      iframe_markup: '<iframe src ="{path}" rel="{id}" width="{width}" height="{height}" frameborder="no"></iframe>',
      inline_markup: '<div class="pp_inline">{content}</div>',
      custom_markup: '',
      social_tools: '<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="//www.facebook.com/plugins/like.php?locale=en_US&href={location_href}&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div>' /* html or false to disable */
    }, pp_settings);

    // Global variables accessible only by prettyPhoto
    var matchedObjects = this,
      percentBased = false,
      pp_dimensions, pp_open,
      // prettyPhoto container specific
      pp_contentHeight, pp_contentWidth, pp_containerHeight, pp_containerWidth,
      // Window size
      windowHeight = $(window).height(),
      windowWidth = $(window).width(),
      // Global elements
      pp_slideshow;

    doresize = true, scroll_pos = _get_scroll();

    // Window/Keyboard events
    $(window).unbind('resize.prettyphoto').bind('resize.prettyphoto', function() {
      _center_overlay();
      _resize_overlay();
    });

    if (pp_settings.keyboard_shortcuts) {
      $(document).unbind('keydown.prettyphoto').bind('keydown.prettyphoto', function(e) {
	if (typeof $pp_pic_holder != 'undefined') {
	  if ($pp_pic_holder.is(':visible')) {
	    switch (e.keyCode) {
	      case 37:
		$.prettyPhoto.changePage('previous');
		e.preventDefault();
		break;
	      case 39:
		$.prettyPhoto.changePage('next');
		e.preventDefault();
		break;
	      case 27:
		if (!settings.modal)
		  $.prettyPhoto.close();
		e.preventDefault();
		break;
	    }
	    ;
	    // return false;
	  }
	  ;
	}
	;
      });
    }
    ;

    /**
     * Initialize prettyPhoto.
     */
    $.prettyPhoto.initialize = function() {

      settings = pp_settings;

      if (settings.theme == 'pp_default')
	settings.horizontal_padding = 16;

      // Find out if the picture is part of a set
      theRel = $(this).attr(settings.hook);
      galleryRegExp = /\[(?:.*)\]/;
      isSet = (galleryRegExp.exec(theRel)) ? true : false;

      // Put the SRCs, TITLEs, ALTs into an array.

      pp_id = (isSet) ? jQuery.map(matchedObjects, function(n, i) {
	if ($(n).attr(settings.hook).indexOf(theRel) != -1)
	  return $(n).attr('id');
      }) : $.makeArray($(this).attr('id'));
      //console.log(pp_id);
      pp_images = (isSet) ? jQuery.map(matchedObjects, function(n, i) {
	if ($(n).attr(settings.hook).indexOf(theRel) != -1)
	  return $(n).attr('href');
      }) : $.makeArray($(this).attr('href'));
      pp_titles = (isSet) ? jQuery.map(matchedObjects, function(n, i) {
	if ($(n).attr(settings.hook).indexOf(theRel) != -1)
	  return ($(n).find('img').attr('alt')) ? $(n).find('img').attr('alt') : "";
      }) : $.makeArray($(this).find('img').attr('alt'));
      pp_descriptions = (isSet) ? jQuery.map(matchedObjects, function(n, i) {
	if ($(n).attr(settings.hook).indexOf(theRel) != -1)
	  return ($(n).attr('title')) ? $(n).attr('title') : "";
      }) : $.makeArray($(this).attr('title'));

      if (pp_images.length > settings.overlay_gallery_max)
	settings.overlay_gallery = false;

      set_position = jQuery.inArray($(this).attr('href'), pp_images); // Define where in the array the clicked item is positionned
      rel_index = (isSet) ? set_position : $("a[" + settings.hook + "^='" + theRel + "']").index($(this));

      _build_overlay(this); // Build the overlay {this} being the caller

      if (settings.allow_resize)
	$(window).bind('scroll.prettyphoto', function() {
	  _center_overlay();
	});


      $.prettyPhoto.open();

      return false;
    }


    /**
     * Opens the prettyPhoto modal box.
     * @param image {String,Array} Full path to the image to be open, can also be an array containing full images paths.
     * @param title {String,Array} The title to be displayed with the picture, can also be an array containing all the titles.
     * @param description {String,Array} The description to be displayed with the picture, can also be an array containing all the descriptions.
     */
    $.prettyPhoto.open = function(event) {
      if (typeof settings == "undefined") { // Means it's an API call, need to manually get the settings and set the variables

	settings = pp_settings;
	pp_images = $.makeArray(arguments[0]);
	pp_titles = (arguments[1]) ? $.makeArray(arguments[1]) : $.makeArray("");
	pp_descriptions = (arguments[2]) ? $.makeArray(arguments[2]) : $.makeArray("");
	isSet = (pp_images.length > 1) ? true : false;
	set_position = (arguments[3]) ? arguments[3] : 0;
	_build_overlay(event.target); // Build the overlay {this} being the caller
      }

      if (settings.hideflash)
	$('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css('visibility', 'hidden'); // Hide the flash

      _checkPosition($(pp_images).size()); // Hide the next/previous links if on first or last images.

      $('.pp_loaderIcon').show();

      if (settings.deeplinking)
	setHashtag();

      // Rebuild Facebook Like Button with updated href
      if (settings.social_tools) {
	facebook_like_link = settings.social_tools.replace('{location_href}', encodeURIComponent(location.href));
	$pp_pic_holder.find('.pp_social').html(facebook_like_link);
      }

      // Fade the content in
      if ($ppt.is(':hidden'))
	$ppt.css('opacity', 0).show();
      $pp_overlay.css('opacity', 0.8);
      $pp_overlay.show().fadeTo(settings.animation_speed, settings.opacity);
      // Display the current position
      $pp_pic_holder.find('.currentTextHolder').text((set_position + 1) + settings.counter_separator_label + $(pp_images).size());

      // Set the description
      if (typeof pp_descriptions[set_position] != 'undefined' && pp_descriptions[set_position] != "") {
	$pp_pic_holder.find('.pp_description').show().html(unescape(pp_descriptions[set_position]));
      } else {
	$pp_pic_holder.find('.pp_description').hide();
      }

      // Get the dimensions
      movie_width = (parseFloat(getParam('width', pp_images[set_position]))) ? getParam('width', pp_images[set_position]) : settings.default_width.toString();
      movie_height = (parseFloat(getParam('height', pp_images[set_position]))) ? getParam('height', pp_images[set_position]) : settings.default_height.toString();

      // If the size is % based, calculate according to window dimensions
      percentBased = false;
      if (movie_height.indexOf('%') != -1) {
	movie_height = parseFloat(($(window).height() * parseFloat(movie_height) / 100) - 150);
	percentBased = true;
      }
      if (movie_width.indexOf('%') != -1) {
	movie_width = parseFloat(($(window).width() * parseFloat(movie_width) / 100) - 150);
	percentBased = true;
      }

      // Fade the holder
      $pp_pic_holder.fadeIn(function() {
	// Set the title
	(settings.show_title && pp_titles[set_position] != "" && typeof pp_titles[set_position] != "undefined") ? $ppt.html(unescape(pp_titles[set_position])) : $ppt.html('&nbsp;');

	imgPreloader = "";
	skipInjection = false;
	//If we're loading a new clip/image and the last one was jwplayer, have it clean up the pp_full_res div.


	if (typeof jwplayer != 'undefined') {
	  try {
	    jwplayer("pp_full_res").remove();

	  } catch (e) {
	    //console.log("catch "+ e);
	  }
	}

	// Inject the proper content
	switch (_getFileType(pp_images[set_position])) {
	  case 'image':
	    imgPreloader = new Image();

	    // Preload the neighbour images
	    nextImage = new Image();
	    if (isSet && set_position < $(pp_images).size() - 1)
	      nextImage.src = pp_images[set_position + 1];
	    prevImage = new Image();
	    if (isSet && pp_images[set_position - 1])
	      prevImage.src = pp_images[set_position - 1];

	    $pp_pic_holder.find('#pp_full_res')[0].innerHTML = settings.image_markup.replace(/{path}/g, pp_images[set_position]).replace(/{id}/g, pp_id[set_position]);
	    console.log(imgPreloader.width + 'X' + imgPreloader.height);
	    imgPreloader.onload = function() {
	      // Fit item to viewport
	      pp_dimensions = _fitToViewport(imgPreloader.width, imgPreloader.height);

	      _showContent();
	    };

	    imgPreloader.onerror = function() {
	      alert('Image cannot be loaded. Make sure the path is correct and image exist.');
	      $.prettyPhoto.close();
	    };

	    imgPreloader.src = pp_images[set_position];
	    break;

	  case 'youtube':
//	    pp_dimensions = _fitToViewport(movie_width, movie_height); // Fit item to viewport
//
//	    // Regular youtube link
//	    movie_id = getParam('v', pp_images[set_position]);
//
//	    // youtu.be link
//	    if (movie_id == "") {
//	      movie_id = pp_images[set_position].split('youtu.be/');
//	      movie_id = movie_id[1];
//	      if (movie_id.indexOf('?') > 0)
//		movie_id = movie_id.substr(0, movie_id.indexOf('?')); // Strip anything after the ?
//
//	      if (movie_id.indexOf('&') > 0)
//		movie_id = movie_id.substr(0, movie_id.indexOf('&')); // Strip anything after the &
//	    }
//
//	    movie = 'http://www.youtube.com/embed/' + movie_id;
//	    (getParam('rel', pp_images[set_position])) ? movie += "?rel=" + getParam('rel', pp_images[set_position]) : movie += "?rel=1";
//
//	    if (settings.autoplay)
//	      movie += "&autoplay=1";
//
//	    toInject = settings.iframe_markup.replace(/{width}/g, pp_dimensions['width']).replace(/{height}/g, pp_dimensions['height']).replace(/{wmode}/g, settings.wmode).replace(/{path}/g, movie);
//	    break;
	  case 'jwplayer':
	    pp_dimensions = _fitToViewport(movie_width, movie_height); // Fit item to viewport
	    controlbar_height = 29; //Allow for JWplayer's bar
	    pp_dimensions['height'] += controlbar_height;
	    pp_dimensions['contentHeight'] += controlbar_height;
	    pp_dimensions['containerHeight'] += controlbar_height;
	    jwplayer_settings = {
              file: pp_images[set_position],
	      width: pp_dimensions['width'] - 250,
	      height: pp_dimensions['height'],
	      autostart: true
	    };
//	    jwplayer_settings.aspectratio = 'auto';
	    /* include jwplayer JS embedder */

	    $.getScript('components/album/js/jwplayer/jwplayer.js', function() {
	      jwplayer.key = "TFY8XJrqkQZEPwil4aTGwJmtN3tA+5VcSuvtE7lSeWI=";
	      jwplayer("pp_full_res").setup(jwplayer_settings);
	    });

	    break;
	  case 'vimeo':
	    pp_dimensions = _fitToViewport(movie_width, movie_height); // Fit item to viewport

	    movie_id = pp_images[set_position];
	    var regExp = /http(s?):\/\/(www\.)?vimeo.com\/(\d+)/;
	    var match = movie_id.match(regExp);

	    movie = 'http://player.vimeo.com/video/' + match[3] + '?title=0&amp;byline=0&amp;portrait=0';
	    if (settings.autoplay)
	      movie += "&autoplay=1;";

	    vimeo_width = pp_dimensions['width'] + '/embed/?moog_width=' + pp_dimensions['width'];

	    toInject = settings.iframe_markup.replace(/{width}/g, vimeo_width).replace(/{height}/g, pp_dimensions['height']).replace(/{path}/g, movie);
	    break;
	  case 'quicktime':
	    pp_dimensions = _fitToViewport(movie_width, movie_height); // Fit item to viewport
	    pp_dimensions['height'] += 15;
	    pp_dimensions['contentHeight'] += 15;
	    pp_dimensions['containerHeight'] += 15; // Add space for the control bar

	    toInject = settings.quicktime_markup.replace(/{width}/g, pp_dimensions['width']).replace(/{height}/g, pp_dimensions['height']).replace(/{wmode}/g, settings.wmode).replace(/{path}/g, pp_images[set_position]).replace(/{autoplay}/g, settings.autoplay);
	    break;
	  case 'flash':
	    pp_dimensions = _fitToViewport(movie_width, movie_height); // Fit item to viewport

	    flash_vars = pp_images[set_position];
	    flash_vars = flash_vars.substring(pp_images[set_position].indexOf('flashvars') + 10, pp_images[set_position].length);

	    filename = pp_images[set_position];
	    filename = filename.substring(0, filename.indexOf('?'));

	    toInject = settings.flash_markup.replace(/{width}/g, pp_dimensions['width']).replace(/{height}/g, pp_dimensions['height']).replace(/{wmode}/g, settings.wmode).replace(/{path}/g, filename + '?' + flash_vars);
	    break;
	  case 'iframe':

	    pp_dimensions = _fitToViewport(movie_width, movie_height); // Fit item to viewport

	    frame_url = pp_images[set_position];
	    frame_url = frame_url.substr(0, frame_url.indexOf('iframe') - 1);

	    toInject = settings.iframe_markup.replace(/{width}/g, pp_dimensions['width']).replace(/{height}/g, pp_dimensions['height']).replace(/{path}/g, frame_url, pp_images[set_position]).replace(/{id}/g, pp_id[set_position]);
	    break;
	  case 'crop':
	    imgPreloader = new Image();
	    frame_url = pp_images[set_position];
	    var img = frame_url.split('?');
	    img = img[1].split('&');
	    img = img[1].split('=');
	    img = img[1];
	    skipInjection = true;
	    imgPreloader.src = img;

	    imgPreloader.onload = function() {
	      console.log(imgPreloader.width);
	      console.log(imgPreloader.height);
	      if (imgPreloader.width > 900 || imgPreloader.height > 800) {
		var temp_img_src = img.split('/');
		var last = temp_img_src.slice(-1);
		last = "temp_" + last;
		temp_img_src[temp_img_src.length - 1] = last;

		var temp_img = temp_img_src.join('/');
		$.get(site_url + "components/album/get_dim.php?img=" + temp_img, function(d) {
		  imgPreloader.width = d.width;
		  imgPreloader.height = d.height;
		  console.log(imgPreloader.width);
		  console.log(imgPreloader.height);
		  call_dim();
		});
	      } else {
		pp_dimensions = _fitToViewport(imgPreloader.width + 100, imgPreloader.height + 60);
		frame_url = frame_url.substr(0, frame_url.indexOf('iframe') - 1);
		toInject = settings.iframe_markup.replace(/{width}/g, imgPreloader.width + 100).replace(/{height}/g, imgPreloader.height + 150).replace(/{path}/g, frame_url, pp_images[set_position]);
		$pp_pic_holder.find('#pp_full_res')[0].innerHTML = toInject;
		// Show content
		_showContent(_getFileType(pp_images[set_position]));


	      }
	      var call_dim = function() {
		pp_dimensions = _fitToViewport(imgPreloader.width + 100, imgPreloader.height + 60);
		frame_url = frame_url.substr(0, frame_url.indexOf('iframe') - 1);
		toInject = settings.iframe_markup.replace(/{width}/g, imgPreloader.width + 100).replace(/{height}/g, imgPreloader.height + 150).replace(/{path}/g, frame_url, pp_images[set_position]);
		$pp_pic_holder.find('#pp_full_res')[0].innerHTML = toInject;
		// Show content
		_showContent(_getFileType(pp_images[set_position]));

	      }

	    };

	    imgPreloader.onerror = function() {
	      alert('Image cannot be loaded. Make sure the path is correct and image exist.');
	      $.prettyPhoto.close();
	    };

	    break;
	  case 'ajax':
	    doresize = false; // Make sure the dimensions are not resized.
	    pp_dimensions = _fitToViewport(movie_width, movie_height);
	    doresize = true; // Reset the dimensions

	    skipInjection = true;
	    $.get(pp_images[set_position], function(responseHTML) {
	      toInject = settings.inline_markup.replace(/{content}/g, responseHTML);
	      $pp_pic_holder.find('#pp_full_res')[0].innerHTML = toInject;
	      _showContent();
	    });

	    break;
	  case 'custom':
	    pp_dimensions = _fitToViewport(movie_width, movie_height); // Fit item to viewport

	    toInject = settings.custom_markup;
	    break;
	  case 'inline':
	    // to get the item height clone it, apply default width, wrap it in the prettyPhoto containers , then delete
	    myClone = $(pp_images[set_position]).clone().append('<br clear="all" />').css({
	      'width': settings.default_width
	    }).wrapInner('<div id="pp_full_res"><div class="pp_inline"></div></div>').appendTo($('body')).show();
	    doresize = false; // Make sure the dimensions are not resized.
	    pp_dimensions = _fitToViewport($(myClone).width(), $(myClone).height());
	    doresize = true; // Reset the dimensions
	    $(myClone).remove();
	    toInject = settings.inline_markup.replace(/{content}/g, $(pp_images[set_position]).html());
	    break;
	}
	;

	if (!imgPreloader && !skipInjection) {
	  $pp_pic_holder.find('#pp_full_res')[0].innerHTML = toInject;
	  // Show content
	  _showContent(_getFileType(pp_images[set_position]));
	}
	;
      });

      return false;
    };


    /**
     * Change page in the prettyPhoto modal box
     * @param direction {String} Direction of the paging, previous or next.
     */
    $.prettyPhoto.changePage = function(direction) {
      currentGalleryPage = 0;

      if (direction == 'previous') {
	set_position--;
	if (set_position < 0)
	  set_position = $(pp_images).size() - 1;
      } else if (direction == 'next') {
	set_position++;
	if (set_position > $(pp_images).size() - 1)
	  set_position = 0;
      } else {
	set_position = direction;
      }
      ;

      rel_index = set_position;

      if (!doresize)
	doresize = true; // Allow the resizing of the images
      if (settings.allow_expand) {
	$('.pp_contract').removeClass('pp_contract').addClass('pp_expand');
      }

      _hideContent(function() {
	$.prettyPhoto.open();
      });
    };


    /**
     * Change gallery page in the prettyPhoto modal box
     * @param direction {String} Direction of the paging, previous or next.
     */
    $.prettyPhoto.changeGalleryPage = function(direction) {
      if (direction == 'next') {
	currentGalleryPage++;

	if (currentGalleryPage > totalPage)
	  currentGalleryPage = 0;
      } else if (direction == 'previous') {
	currentGalleryPage--;

	if (currentGalleryPage < 0)
	  currentGalleryPage = totalPage;
      } else {
	currentGalleryPage = direction;
      }
      ;

      slide_speed = (direction == 'next' || direction == 'previous') ? settings.animation_speed : 0;

      slide_to = currentGalleryPage * (itemsPerPage * itemWidth);

      $pp_gallery.find('ul').animate({
	left: -slide_to
      }, slide_speed);
    };


    /**
     * Start the slideshow...
     */
    $.prettyPhoto.startSlideshow = function() {
      if (typeof pp_slideshow == 'undefined') {
	$pp_pic_holder.find('.pp_play').unbind('click').removeClass('pp_play').addClass('pp_pause').click(function() {
	  $.prettyPhoto.stopSlideshow();
	  return false;
	});
	pp_slideshow = setInterval($.prettyPhoto.startSlideshow, settings.slideshow);
      } else {
	$.prettyPhoto.changePage('next');
      }
      ;
    }


    /**
     * Stop the slideshow...
     */
    $.prettyPhoto.stopSlideshow = function() {
      $pp_pic_holder.find('.pp_pause').unbind('click').removeClass('pp_pause').addClass('pp_play').click(function() {
	$.prettyPhoto.startSlideshow();
	return false;
      });
      clearInterval(pp_slideshow);
      pp_slideshow = undefined;
    }


    /**
     * Closes prettyPhoto.
     */
    $.prettyPhoto.close = function() {
      if ($pp_overlay.is(":animated"))
	return;

      $.prettyPhoto.stopSlideshow();

      $pp_pic_holder.stop().find('object,embed').css('visibility', 'hidden');

      $('div.pp_pic_holder,div.ppt,.pp_fade').fadeOut(settings.animation_speed, function() {
	$(this).remove();
      });

      $pp_overlay.fadeOut(settings.animation_speed, function() {

	if (settings.hideflash)
	  $('object,embed,iframe[src*=youtube],iframe[src*=vimeo]').css('visibility', 'visible'); // Show the flash

	$(this).remove(); // No more need for the prettyPhoto markup

	$(window).unbind('scroll.prettyphoto');

	clearHashtag();

	settings.callback();

	doresize = true;

	pp_open = false;

	delete settings;
      });
    };

    /**
     * Set the proper sizes on the containers and animate the content in.
     */
    function _showContent(type) {
      $('.pp_loaderIcon').hide();

      // Calculate the opened top position of the pic holder
      projectedTop = scroll_pos['scrollTop'] + ((windowHeight / 2) - (pp_dimensions['containerHeight'] / 2));
      if (projectedTop < 0)
	projectedTop = 0;

      $ppt.fadeTo(settings.animation_speed, 1);

      // Resize the content holder
      $pp_pic_holder.find('.pp_content')
	.animate({
	  height: pp_dimensions['contentHeight'],
	  width: pp_dimensions['contentWidth']
	}, settings.animation_speed);

      // Resize picture the holder
      $pp_pic_holder.animate({
	'top': projectedTop,
	'left': ((windowWidth / 2) - (pp_dimensions['containerWidth'] / 2) < 0) ? 0 : (windowWidth / 2) - (pp_dimensions['containerWidth'] / 2),
	width: pp_dimensions['containerWidth']
      }, settings.animation_speed, function() {
	$pp_pic_holder.find('.pp_hoverContainer,#fullResImage').height(pp_dimensions['height']).width(pp_dimensions['width']);

	$pp_pic_holder.find('.pp_fade').fadeIn(settings.animation_speed); // Fade the new content

	// Show the nav
	if (isSet && _getFileType(pp_images[set_position]) == "image") {
	  $pp_pic_holder.find('.pp_hoverContainer').show();
	} else {
	  $pp_pic_holder.find('.pp_hoverContainer').hide();
	}

	if (settings.allow_expand) {
	  if (pp_dimensions['resized']) { // Fade the resizing link if the image is resized
	    $('a.pp_expand,a.pp_contract').show();
	  } else {
	    $('a.pp_expand').hide();
	  }
	}

	if (settings.autoplay_slideshow && !pp_slideshow && !pp_open)
	  $.prettyPhoto.startSlideshow();
	settings.changepicturecallback($pp_pic_holder); // Callback!
	pp_open = true;
      });

      _insert_gallery();
      pp_settings.ajaxcallback();
      if (type === "crop") {
	window._pptype = "crop";
	$pp_pic_holder.find('.aside').hide();
	$pp_pic_holder.find('#pp_full_res > iframe').width('100%');
      } else {
	window._pptype = "iframe";
      }
    }
    ;

    /**
     * Hide the content...DUH!
     */
    function _hideContent(callback) {
      // Fade out the current picture
      $pp_pic_holder.find('#pp_full_res object,#pp_full_res embed').css('visibility', 'hidden');
      $pp_pic_holder.find('.pp_fade').fadeOut(settings.animation_speed, function() {
	$('.pp_loaderIcon').show();

	callback();
      });
    }
    ;

    /**
     * Check the item position in the gallery array, hide or show the navigation links
     * @param setCount {integer} The total number of items in the set
     */
    function _checkPosition(setCount) {
      (setCount > 1) ? $('.pp_nav').show() : $('.pp_nav').hide(); // Hide the bottom nav if it's not a set.
    }
    ;

    /**
     * Resize the item dimensions if it's bigger than the viewport
     * @param width {integer} Width of the item to be opened
     * @param height {integer} Height of the item to be opened
     * @return An array containin the "fitted" dimensions
     */

    function _fitToViewport(width, height) {

      resized = false;

      wh_ratio = width / height; // used with min_image_width

      _getDimensions(width, height);

      imageWidth = width, imageHeight = height;

      if (((pp_containerWidth > windowWidth) || (pp_containerHeight > windowHeight) || imageWidth < settings.min_image_width) && doresize && settings.allow_resize && !percentBased) {

	resized = true, fitting = false;

	stop_resizing = false; // used with min_image_width

	while (!fitting) {

	  if ((pp_containerWidth > windowWidth)) {
	    imageWidth = (windowWidth - (pp_containerWidth - imageWidth) - 100);
	    imageHeight = (imageWidth / width) * height;
	  } else if ((pp_containerHeight > windowHeight)) {
	    imageHeight = (windowHeight - (pp_containerHeight - imageHeight) - 50);
	    imageWidth = (imageHeight / height) * width;
	  } else {
	    fitting = true;
	  }
	  ;

	  // enforce minimum width
	  if (imageWidth < settings.min_image_width) {

	    imageWidth = settings.min_image_width;
	    imageHeight = imageWidth / wh_ratio;

	    stop_resizing = true;

	    break;

	  }

	  pp_containerHeight = imageHeight, pp_containerWidth = imageWidth;

	}
	;

	_getDimensions(imageWidth, imageHeight);

	if (!stop_resizing) { // used with min_image_width

	  if ((pp_containerWidth > windowWidth || pp_containerHeight > windowHeight)) {
	    _fitToViewport(imageWidth, imageHeight)
	  }
	  ;

	}

      }
      ;


      return {
	width: Math.floor(imageWidth),
	height: Math.floor(imageHeight),
	containerHeight: Math.floor(pp_containerHeight),
	containerWidth: Math.floor(pp_containerWidth) + (settings.horizontal_padding * 2),
	contentHeight: Math.floor(pp_contentHeight),
	contentWidth: Math.floor(pp_contentWidth),
	resized: resized
      };
    }
    ;

    /**
     * Get the containers dimensions according to the item size
     * @param width {integer} Width of the item to be opened
     * @param height {integer} Height of the item to be opened
     */
    function _getDimensions(width, height) {
      width = parseFloat(width);
      height = parseFloat(height);


      // Get the details height, to do so, I need to clone it since it's invisible
      $pp_details = $pp_pic_holder.find('.pp_details');
      $pp_details.width(width);
      detailsHeight = parseFloat($pp_details.css('marginTop')) + parseFloat($pp_details.css('marginBottom'));

      $pp_details = $pp_details.clone().addClass(settings.theme).width(width).appendTo($('body')).css({
	'position': 'absolute',
	'top': -10000
      });
      detailsHeight += $pp_details.height();
      detailsHeight = (detailsHeight <= 34) ? 36 : detailsHeight; // Min-height for the details
      $pp_details.remove();

      // Get the titles height, to do so, I need to clone it since it's invisible
      $pp_title = $pp_pic_holder.find('.ppt');
      $pp_title.width(width);
      titleHeight = parseFloat($pp_title.css('marginTop')) + parseFloat($pp_title.css('marginBottom'));
      $pp_title = $pp_title.clone().appendTo($('body')).css({
	'position': 'absolute',
	'top': -10000
      });
      titleHeight += $pp_title.height();
      $pp_title.remove();

      // Get the container size, to resize the holder to the right dimensions
      pp_contentHeight = height + detailsHeight;
      pp_contentWidth = width;
      pp_containerHeight = pp_contentHeight + titleHeight + $pp_pic_holder.find('.pp_top').height() + $pp_pic_holder.find('.pp_bottom').height();
      //console.log(_getFileType(pp_images[set_position]))
      if (_getFileType(pp_images[set_position]) == "image") {
	pp_containerWidth = width + 250;
      } else {
	pp_containerWidth = width;
      }
    }

    function _getFileType(itemSrc) {
      if (itemSrc.match(/youtube\.com\/watch/i) || itemSrc.match(/youtu\.be/i)) {
	return 'youtube';
      } else if (itemSrc.match(/vimeo\.com/i)) {
	return 'vimeo';
      } else if (itemSrc.match(/\b.mov\b/i)) {
	return 'quicktime';
      } else if (itemSrc.match(/\b.swf\b/i)) {
	return 'flash';
      } else if (itemSrc.match(/\b.mov\b/i)) {
	return 'jwplayer';
      } else if (itemSrc.match(/\b.mp4\b/i)) {
	return 'jwplayer';
      } else if (itemSrc.match(/\b.flv\b/i)) {
	return 'jwplayer';
      } else if (itemSrc.match(/\b.f4v\b/i)) {
	return 'jwplayer';
      } else if (itemSrc.match(/\b.swf\b/i)) {
	return 'jwplayer';
      } else if (itemSrc.match(/\biframe=true\b/i)) {
	return 'iframe';
      } else if (itemSrc.match(/\bajax=true\b/i)) {
	return 'ajax';
      } else if (itemSrc.match(/\bcustom=true\b/i)) {
	return 'custom';
      } else if (itemSrc.substr(0, 1) == '#') {
	return 'inline';
      } else if (itemSrc.match(/\biframe=crop\b/i)) {
	return 'crop';
      } else {
	return 'image';
      }
      ;
    }
    ;

    function _center_overlay() {
      if (doresize && typeof $pp_pic_holder != 'undefined') {
	scroll_pos = _get_scroll();
	contentHeight = $pp_pic_holder.height(), contentwidth = $pp_pic_holder.width();

	projectedTop = (windowHeight / 2) + scroll_pos['scrollTop'] - (contentHeight / 2);
	if (projectedTop < 0)
	  projectedTop = 0;

	if (contentHeight > windowHeight)
	  return;

	$pp_pic_holder.css({
	  'top': projectedTop,
	  'left': (windowWidth / 2) + scroll_pos['scrollLeft'] - (contentwidth / 2)
	});
      }
      ;
    }
    ;

    function _get_scroll() {
      if (self.pageYOffset) {
	return {
	  scrollTop: self.pageYOffset,
	  scrollLeft: self.pageXOffset
	};
      } else if (document.documentElement && document.documentElement.scrollTop) { // Explorer 6 Strict
	return {
	  scrollTop: document.documentElement.scrollTop,
	  scrollLeft: document.documentElement.scrollLeft
	};
      } else if (document.body) { // all other Explorers
	return {
	  scrollTop: document.body.scrollTop,
	  scrollLeft: document.body.scrollLeft
	};
      }
      ;
    }
    ;

    function _resize_overlay() {
      windowHeight = $(window).height(), windowWidth = $(window).width();

      if (typeof $pp_overlay != "undefined")
	$pp_overlay.height($(document).height()).width(windowWidth);
    }
    ;

    function _insert_gallery() {
      if (isSet && settings.overlay_gallery && _getFileType(pp_images[set_position]) == "image") {
	itemWidth = 52 + 5; // 52 beign the thumb width, 5 being the right margin.
	navWidth = (settings.theme == "facebook" || settings.theme == "pp_default") ? 50 : 30; // Define the arrow width depending on the theme

	itemsPerPage = Math.floor((pp_dimensions['containerWidth'] - 100 - navWidth) / itemWidth);
	itemsPerPage = (itemsPerPage < pp_images.length) ? itemsPerPage : pp_images.length;
	totalPage = Math.ceil(pp_images.length / itemsPerPage) - 1;

	// Hide the nav in the case there's no need for links
	if (totalPage == 0) {
	  navWidth = 0; // No nav means no width!
	  $pp_gallery.find('.pp_arrow_next,.pp_arrow_previous').hide();
	} else {
	  $pp_gallery.find('.pp_arrow_next,.pp_arrow_previous').show();
	}
	;

	galleryWidth = itemsPerPage * itemWidth;
	fullGalleryWidth = pp_images.length * itemWidth;
	//console.log("pp_c "+pp_contentWidth +" - gw " +galleryWidth +" - fgw "+fullGalleryWidth +" - nw "+navWidth);
	// Set the proper width to the gallery items
	$pp_gallery
	  .css('margin-left', -(((pp_contentWidth - 35) / 2) + (navWidth / 2)))
	  //.css('position', 'relative')
	  //.find('div:first').width(galleryWidth+5)
	  .find('div:first').width(pp_contentWidth - 35)
	  .find('ul').width(fullGalleryWidth)
	  //.find('ul').width(pp_contentWidth)
	  .find('li.selected').removeClass('selected');

	goToPage = (Math.floor(set_position / itemsPerPage) < totalPage) ? Math.floor(set_position / itemsPerPage) : totalPage;

	$.prettyPhoto.changeGalleryPage(goToPage);

	$pp_gallery_li.filter(':eq(' + set_position + ')').addClass('selected');
      } else {
	$pp_pic_holder.find('.pp_content').unbind('mouseenter mouseleave');
	if (typeof $pp_gallery !== 'undefined') {
	  $pp_gallery.hide();
	}
      }
    }

    function _build_overlay(caller) {
      // Inject Social Tool markup into General markup
      if (settings.social_tools)
	facebook_like_link = settings.social_tools.replace('{location_href}', encodeURIComponent(location.href));

      settings.markup = settings.markup.replace('{pp_social}', '');

      $('body').append(settings.markup); // Inject the markup

      $pp_pic_holder = $('.pp_pic_holder'), $ppt = $('.ppt'), $pp_overlay = $('div.pp_overlay'); // Set my global selectors

      // Inject the inline gallery!
      if (isSet && settings.overlay_gallery) {
	currentGalleryPage = 0;
	toInject = "";
	for (var i = 0; i < pp_images.length; i++) {
	  if (!pp_images[i].match(/\b(jpg|jpeg|png|gif)\b/gi)) {
	    classname = 'default';
	    img_src = '';
	  } else {
	    classname = '';
	    img_src = pp_images[i];
	  }
	  toInject += "<li class='" + classname + "'><a href='#'><img src='" + img_src + "' width='50' alt='' /></a></li>";
	}
	;

	toInject = settings.gallery_markup.replace(/{gallery}/g, toInject);

	$pp_pic_holder.find('#pp_full_res').after(toInject);

	$pp_gallery = $('.pp_pic_holder .pp_gallery'), $pp_gallery_li = $pp_gallery.find('li'); // Set the gallery selectors

	$pp_gallery.find('.pp_arrow_next').click(function() {
	  $.prettyPhoto.changeGalleryPage('next');
	  $.prettyPhoto.stopSlideshow();
	  return false;
	});

	$pp_gallery.find('.pp_arrow_previous').click(function() {
	  $.prettyPhoto.changeGalleryPage('previous');
	  $.prettyPhoto.stopSlideshow();
	  return false;
	});

	$pp_pic_holder.find('.pp_content').hover(
	  function() {
	    $pp_pic_holder.find('.pp_gallery:not(.disabled)').fadeIn();
	  },
	  function() {
	    $pp_pic_holder.find('.pp_gallery:not(.disabled)').fadeOut();
	  });

	itemWidth = 52 + 5; // 52 beign the thumb width, 5 being the right margin.
	$pp_gallery_li.each(function(i) {
	  $(this)
	    .find('a')
	    .click(function() {
	      $.prettyPhoto.changePage(i);
	      $.prettyPhoto.stopSlideshow();
	      return false;
	    });
	});
      }
      ;


      // Inject the play/pause if it's a slideshow
      if (settings.slideshow) {
	$pp_pic_holder.find('.pp_nav').prepend('<a href="#" class="pp_play">Play</a>')
	$pp_pic_holder.find('.pp_nav .pp_play').click(function() {
	  $.prettyPhoto.startSlideshow();
	  return false;
	});
      }

      $pp_pic_holder.attr('class', 'pp_pic_holder ' + settings.theme); // Set the proper theme

      $pp_overlay
	.css({
	  'opacity': 0,
	  'height': $(document).height(),
	  'width': $(window).width()
	})
	.bind('click', function() {
	  if (!settings.modal)
	    $.prettyPhoto.close();
	});

      $('a.pp_close').bind('click', function(e) {
	e.preventDefault();
	$.prettyPhoto.close();
	return false;
      });


      if (settings.allow_expand) {
	$('a.pp_expand').bind('click', function(e) {
	  // Expand the image
	  if ($(this).hasClass('pp_expand')) {
	    $(this).removeClass('pp_expand').addClass('pp_contract');
	    doresize = false;
	  } else {
	    $(this).removeClass('pp_contract').addClass('pp_expand');
	    doresize = true;
	  }
	  ;

	  _hideContent(function() {
	    $.prettyPhoto.open();
	  });

	  return false;
	});
      }

      $pp_pic_holder.find('.pp_previous, .pp_nav .pp_arrow_previous').bind('click', function() {
	$.prettyPhoto.changePage('previous');
	$.prettyPhoto.stopSlideshow();
	return false;
      });

      $pp_pic_holder.find('.pp_next, .pp_nav .pp_arrow_next').bind('click', function() {
	$.prettyPhoto.changePage('next');
	$.prettyPhoto.stopSlideshow();
	return false;
      });

      _center_overlay(); // Center it
    }
    ;

    if (!pp_alreadyInitialized && getHashtag()) {
      pp_alreadyInitialized = true;

      // Grab the rel index to trigger the click on the correct element
      hashIndex = getHashtag();
      hashRel = hashIndex;
      hashIndex = hashIndex.substring(hashIndex.indexOf('/') + 1, hashIndex.length - 1);
      hashRel = hashRel.substring(0, hashRel.indexOf('/'));

      // Little timeout to make sure all the prettyPhoto initialize scripts has been run.
      // Useful in the event the page contain several init scripts.
      setTimeout(function() {
	$("a[" + pp_settings.hook + "^='" + hashRel + "']:eq(" + hashIndex + ")").trigger('click');
      }, 50);
    }

    return this.unbind('click.prettyphoto').bind('click.prettyphoto', $.prettyPhoto.initialize); // Return the jQuery object for chaining. The unbind method is used to avoid click conflict when the plugin is called more than once
  };

  function getHashtag() {
    var url = location.href;
    hashtag = (url.indexOf('#prettyPhoto') !== -1) ? decodeURI(url.substring(url.indexOf('#prettyPhoto') + 1, url.length)) : false;

    return hashtag;
  }
  ;

  function setHashtag() {
    if (typeof theRel == 'undefined')
      return; // theRel is set on normal calls, it's impossible to deeplink using the API
    location.hash = theRel + '/' + rel_index + '/';
  }
  ;

  function clearHashtag() {
    if (location.href.indexOf('#prettyPhoto') !== -1)
      location.hash = "prettyPhoto";
  }

  function getParam(name, url) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(url);
    return (results == null) ? "" : results[1];
  }

})(jQuery);

var pp_alreadyInitialized = false; // Used for the deep linking to make sure not to call the same function several times.


/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.ir/projects/html5sortable
 *
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
(function($) {
  var dragging, placeholders = $();
  $.fn.sortable = function(options) {
    var method = String(options);
    options = $.extend({
      connectWith: false
    }, options);
    return this.each(function() {
      if (/^enable|disable|destroy$/.test(method)) {
	var items = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
	if (method == 'destroy') {
	  items.add(this).removeData('connectWith items')
	    .off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
	}
	return;
      }
      var isHandle, index, items = $(this).children(options.items);
      var placeholder = $('<' + (/^ul|ol$/i.test(this.tagName) ? 'li' : 'div') + ' class="sortable-placeholder">');
      items.find(options.handle).mousedown(function() {
	isHandle = true;
      }).mouseup(function() {
	isHandle = false;
      });
      $(this).data('items', options.items)
      placeholders = placeholders.add(placeholder);
      if (options.connectWith) {
	$(options.connectWith).add(this).data('connectWith', options.connectWith);
      }
      items.attr('draggable', 'true').on('dragstart.h5s', function(e) {
	if (options.handle && !isHandle) {
	  return false;
	}
	isHandle = false;
	var dt = e.originalEvent.dataTransfer;
	dt.effectAllowed = 'move';
	dt.setData('Text', 'dummy');
	index = (dragging = $(this)).addClass('sortable-dragging').index();
      }).on('dragend.h5s', function() {
	if (!dragging) {
	  return;
	}
	dragging.removeClass('sortable-dragging').show();
	placeholders.detach();
	if (index != dragging.index()) {
	  dragging.parent().trigger('sortupdate', {
	    item: dragging
	  });
	}
	dragging = null;
      }).not('a[href], img').on('selectstart.h5s', function() {
	this.dragDrop && this.dragDrop();
	return false;
      }).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function(e) {
	if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
	  return true;
	}
	if (e.type == 'drop') {
	  e.stopPropagation();
	  placeholders.filter(':visible').after(dragging);
	  dragging.trigger('dragend.h5s');
	  return false;
	}
	e.preventDefault();
	e.originalEvent.dataTransfer.dropEffect = 'move';
	if (items.is(this)) {
	  if (options.forcePlaceholderSize) {
	    placeholder.height(dragging.outerHeight());
	  }
	  dragging.hide();
	  $(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
	  placeholders.not(placeholder).detach();
	} else if (!placeholders.is(this) && !$(this).children(options.items).length) {
	  placeholders.detach();
	  $(this).append(placeholder);
	}
	return false;
      });
    });
  };
})(jQuery);

/*!
 * imagesLoaded PACKAGED v3.1.1
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function() {
  function e() {
  }

  function t(e, t) {
    for (var n = e.length; n--; )
      if (e[n].listener === t)
	return n;
    return -1
  }

  function n(e) {
    return function() {
      return this[e].apply(this, arguments)
    }
  }
  var i = e.prototype,
    r = this,
    o = r.EventEmitter;
  i.getListeners = function(e) {
    var t, n, i = this._getEvents();
    if ("object" == typeof e) {
      t = {};
      for (n in i)
	i.hasOwnProperty(n) && e.test(n) && (t[n] = i[n])
    } else
      t = i[e] || (i[e] = []);
    return t
  }, i.flattenListeners = function(e) {
    var t, n = [];
    for (t = 0; e.length > t; t += 1)
      n.push(e[t].listener);
    return n
  }, i.getListenersAsObject = function(e) {
    var t, n = this.getListeners(e);
    return n instanceof Array && (t = {}, t[e] = n), t || n
  }, i.addListener = function(e, n) {
    var i, r = this.getListenersAsObject(e),
      o = "object" == typeof n;
    for (i in r)
      r.hasOwnProperty(i) && -1 === t(r[i], n) && r[i].push(o ? n : {
	listener: n,
	once: !1
      });
    return this
  }, i.on = n("addListener"), i.addOnceListener = function(e, t) {
    return this.addListener(e, {
      listener: t,
      once: !0
    })
  }, i.once = n("addOnceListener"), i.defineEvent = function(e) {
    return this.getListeners(e), this
  }, i.defineEvents = function(e) {
    for (var t = 0; e.length > t; t += 1)
      this.defineEvent(e[t]);
    return this
  }, i.removeListener = function(e, n) {
    var i, r, o = this.getListenersAsObject(e);
    for (r in o)
      o.hasOwnProperty(r) && (i = t(o[r], n), -1 !== i && o[r].splice(i, 1));
    return this
  }, i.off = n("removeListener"), i.addListeners = function(e, t) {
    return this.manipulateListeners(!1, e, t)
  }, i.removeListeners = function(e, t) {
    return this.manipulateListeners(!0, e, t)
  }, i.manipulateListeners = function(e, t, n) {
    var i, r, o = e ? this.removeListener : this.addListener,
      s = e ? this.removeListeners : this.addListeners;
    if ("object" != typeof t || t instanceof RegExp)
      for (i = n.length; i--; )
	o.call(this, t, n[i]);
    else
      for (i in t)
	t.hasOwnProperty(i) && (r = t[i]) && ("function" == typeof r ? o.call(this, i, r) : s.call(this, i, r));
    return this
  }, i.removeEvent = function(e) {
    var t, n = typeof e,
      i = this._getEvents();
    if ("string" === n)
      delete i[e];
    else if ("object" === n)
      for (t in i)
	i.hasOwnProperty(t) && e.test(t) && delete i[t];
    else
      delete this._events;
    return this
  }, i.removeAllListeners = n("removeEvent"), i.emitEvent = function(e, t) {
    var n, i, r, o, s = this.getListenersAsObject(e);
    for (r in s)
      if (s.hasOwnProperty(r))
	for (i = s[r].length; i--; )
	  n = s[r][i], n.once === !0 && this.removeListener(e, n.listener), o = n.listener.apply(this, t || []), o === this._getOnceReturnValue() && this.removeListener(e, n.listener);
    return this
  }, i.trigger = n("emitEvent"), i.emit = function(e) {
    var t = Array.prototype.slice.call(arguments, 1);
    return this.emitEvent(e, t)
  }, i.setOnceReturnValue = function(e) {
    return this._onceReturnValue = e, this
  }, i._getOnceReturnValue = function() {
    return this.hasOwnProperty("_onceReturnValue") ? this._onceReturnValue : !0
  }, i._getEvents = function() {
    return this._events || (this._events = {})
  }, e.noConflict = function() {
    return r.EventEmitter = o, e
  }, "function" == typeof define && define.amd ? define("eventEmitter/EventEmitter", [], function() {
    return e
  }) : "object" == typeof module && module.exports ? module.exports = e : this.EventEmitter = e
}).call(this),
  function(e) {
    function t(t) {
      var n = e.event;
      return n.target = n.target || n.srcElement || t, n
    }
    var n = document.documentElement,
      i = function() {
      };
    n.addEventListener ? i = function(e, t, n) {
      e.addEventListener(t, n, !1)
    } : n.attachEvent && (i = function(e, n, i) {
      e[n + i] = i.handleEvent ? function() {
	var n = t(e);
	i.handleEvent.call(i, n)
      } : function() {
	var n = t(e);
	i.call(e, n)
      }, e.attachEvent("on" + n, e[n + i])
    });
    var r = function() {
    };
    n.removeEventListener ? r = function(e, t, n) {
      e.removeEventListener(t, n, !1)
    } : n.detachEvent && (r = function(e, t, n) {
      e.detachEvent("on" + t, e[t + n]);
      try {
	delete e[t + n]
      } catch (i) {
	e[t + n] = void 0
      }
    });
    var o = {
      bind: i,
      unbind: r
    };
    "function" == typeof define && define.amd ? define("eventie/eventie", o) : e.eventie = o
  }(this),
  function(e) {
    function t(e, t) {
      for (var n in t)
	e[n] = t[n];
      return e
    }

    function n(e) {
      return "[object Array]" === f.call(e)
    }

    function i(e) {
      var t = [];
      if (n(e))
	t = e;
      else if ("number" == typeof e.length)
	for (var i = 0, r = e.length; r > i; i++)
	  t.push(e[i]);
      else
	t.push(e);
      return t
    }

    function r(e, n) {
      function r(e, n, s) {
	if (!(this instanceof r))
	  return new r(e, n);
	"string" == typeof e && (e = document.querySelectorAll(e)), this.elements = i(e), this.options = t({}, this.options), "function" == typeof n ? s = n : t(this.options, n), s && this.on("always", s), this.getImages(), o && (this.jqDeferred = new o.Deferred);
	var c = this;
	setTimeout(function() {
	  c.check()
	})
      }

      function f(e) {
	this.img = e
      }

      function a(e) {
	this.src = e, h[e] = this
      }
      r.prototype = new e, r.prototype.options = {}, r.prototype.getImages = function() {
	this.images = [];
	for (var e = 0, t = this.elements.length; t > e; e++) {
	  var n = this.elements[e];
	  "IMG" === n.nodeName && this.addImage(n);
	  for (var i = n.querySelectorAll("img"), r = 0, o = i.length; o > r; r++) {
	    var s = i[r];
	    this.addImage(s)
	  }
	}
      }, r.prototype.addImage = function(e) {
	var t = new f(e);
	this.images.push(t)
      }, r.prototype.check = function() {
	function e(e, r) {
	  return t.options.debug && c && s.log("confirm", e, r), t.progress(e), n++, n === i && t.complete(), !0
	}
	var t = this,
	  n = 0,
	  i = this.images.length;
	if (this.hasAnyBroken = !1, !i)
	  return this.complete(), void 0;
	for (var r = 0; i > r; r++) {
	  var o = this.images[r];
	  o.on("confirm", e), o.check()
	}
      }, r.prototype.progress = function(e) {
	this.hasAnyBroken = this.hasAnyBroken || !e.isLoaded;
	var t = this;
	setTimeout(function() {
	  t.emit("progress", t, e), t.jqDeferred && t.jqDeferred.notify(t, e)
	})
      }, r.prototype.complete = function() {
	var e = this.hasAnyBroken ? "fail" : "done";
	this.isComplete = !0;
	var t = this;
	setTimeout(function() {
	  if (t.emit(e, t), t.emit("always", t), t.jqDeferred) {
	    var n = t.hasAnyBroken ? "reject" : "resolve";
	    t.jqDeferred[n](t)
	  }
	})
      }, o && (o.fn.imagesLoaded = function(e, t) {
	var n = new r(this, e, t);
	return n.jqDeferred.promise(o(this))
      }), f.prototype = new e, f.prototype.check = function() {
	var e = h[this.img.src] || new a(this.img.src);
	if (e.isConfirmed)
	  return this.confirm(e.isLoaded, "cached was confirmed"), void 0;
	if (this.img.complete && void 0 !== this.img.naturalWidth)
	  return this.confirm(0 !== this.img.naturalWidth, "naturalWidth"), void 0;
	var t = this;
	e.on("confirm", function(e, n) {
	  return t.confirm(e.isLoaded, n), !0
	}), e.check()
      }, f.prototype.confirm = function(e, t) {
	this.isLoaded = e, this.emit("confirm", this, t)
      };
      var h = {};
      return a.prototype = new e, a.prototype.check = function() {
	if (!this.isChecked) {
	  var e = new Image;
	  n.bind(e, "load", this), n.bind(e, "error", this), e.src = this.src, this.isChecked = !0
	}
      }, a.prototype.handleEvent = function(e) {
	var t = "on" + e.type;
	this[t] && this[t](e)
      }, a.prototype.onload = function(e) {
	this.confirm(!0, "onload"), this.unbindProxyEvents(e)
      }, a.prototype.onerror = function(e) {
	this.confirm(!1, "onerror"), this.unbindProxyEvents(e)
      }, a.prototype.confirm = function(e, t) {
	this.isConfirmed = !0, this.isLoaded = e, this.emit("confirm", this, t)
      }, a.prototype.unbindProxyEvents = function(e) {
	n.unbind(e.target, "load", this), n.unbind(e.target, "error", this)
      }, r
    }
    var o = e.jQuery,
      s = e.console,
      c = s !== void 0,
      f = Object.prototype.toString;
    "function" == typeof define && define.amd ? define(["eventEmitter/EventEmitter", "eventie/eventie"], r) : e.imagesLoaded = r(e.EventEmitter, e.eventie)
  }(window);

/*
 * TipTip
 * Copyright 2010 Drew Wilson
 * www.drewwilson.com
 * code.drewwilson.com/entry/tiptip-jquery-plugin
 *
 * Version 1.3   -   Updated: Mar. 23, 2010
 *
 * This Plug-In will create a custom tooltip to replace the default
 * browser tooltip. It is extremely lightweight and very smart in
 * that it detects the edges of the browser window and will make sure
 * the tooltip stays within the current window size. As a result the
 * tooltip will adjust itself to be displayed above, below, to the left
 * or to the right depending on what is necessary to stay within the
 * browser window. It is completely customizable as well via CSS.
 *
 * This TipTip jQuery plug-in is dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function($) {
  $.fn.tipTip = function(options) {
    var defaults = {
      activation: "hover",
      keepAlive: false,
      maxWidth: "200px",
      edgeOffset: 3,
      defaultPosition: "top",
      delay: 400,
      fadeIn: 200,
      fadeOut: 200,
      attribute: "title",
      content: false,
      enter: function() {
      },
      exit: function() {
      }
    };
    var opts = $.extend(defaults, options);
    if ($("#tiptip_holder").length <= 0) {
      var tiptip_holder = $('<div id="tiptip_holder" style="max-width:' + opts.maxWidth + ';"></div>');
      var tiptip_content = $('<div id="tiptip_content"></div>');
      var tiptip_arrow = $('<div id="tiptip_arrow"></div>');
      $("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')))
    } else {
      var tiptip_holder = $("#tiptip_holder");
      var tiptip_content = $("#tiptip_content");
      var tiptip_arrow = $("#tiptip_arrow")
    }
    return this.each(function() {
      var org_elem = $(this);
      if (opts.content) {
	var org_title = opts.content
      } else {
	var org_title = org_elem.attr(opts.attribute)
      }
      if (org_title != "") {
	if (!opts.content) {
	  org_elem.removeAttr(opts.attribute)
	}
	var timeout = false;
	if (opts.activation == "hover") {
	  org_elem.hover(function() {
	    active_tiptip()
	  }, function() {
	    if (!opts.keepAlive) {
	      deactive_tiptip()
	    }
	  });
	  if (opts.keepAlive) {
	    tiptip_holder.hover(function() {
	    }, function() {
	      deactive_tiptip()
	    })
	  }
	} else if (opts.activation == "focus") {
	  org_elem.focus(function() {
	    active_tiptip()
	  }).blur(function() {
	    deactive_tiptip()
	  })
	} else if (opts.activation == "click") {
	  org_elem.click(function() {
	    active_tiptip();
	    return false
	  }).hover(function() {
	  }, function() {
	    if (!opts.keepAlive) {
	      deactive_tiptip()
	    }
	  });
	  if (opts.keepAlive) {
	    tiptip_holder.hover(function() {
	    }, function() {
	      deactive_tiptip()
	    })
	  }
	}

	function active_tiptip() {
	  opts.enter.call(this);
	  tiptip_content.html(org_title);
	  tiptip_holder.hide().removeAttr("class").css("margin", "0");
	  tiptip_arrow.removeAttr("style");
	  var top = parseInt(org_elem.offset()['top']);
	  var left = parseInt(org_elem.offset()['left']);
	  var org_width = parseInt(org_elem.outerWidth());
	  var org_height = parseInt(org_elem.outerHeight());
	  var tip_w = tiptip_holder.outerWidth();
	  var tip_h = tiptip_holder.outerHeight();
	  var w_compare = Math.round((org_width - tip_w) / 2);
	  var h_compare = Math.round((org_height - tip_h) / 2);
	  var marg_left = Math.round(left + w_compare);
	  var marg_top = Math.round(top + org_height + opts.edgeOffset);
	  var t_class = "";
	  var arrow_top = "";
	  var arrow_left = Math.round(tip_w - 12) / 2;
	  if (opts.defaultPosition == "bottom") {
	    t_class = "_bottom"
	  } else if (opts.defaultPosition == "top") {
	    t_class = "_top"
	  } else if (opts.defaultPosition == "left") {
	    t_class = "_left"
	  } else if (opts.defaultPosition == "right") {
	    t_class = "_right"
	  }
	  var right_compare = (w_compare + left) < parseInt($(window).scrollLeft());
	  var left_compare = (tip_w + left) > parseInt($(window).width());
	  if ((right_compare && w_compare < 0) || (t_class == "_right" && !left_compare) || (t_class == "_left" && left < (tip_w + opts.edgeOffset + 5))) {
	    t_class = "_right";
	    arrow_top = Math.round(tip_h - 13) / 2;
	    arrow_left = -12;
	    marg_left = Math.round(left + org_width + opts.edgeOffset);
	    marg_top = Math.round(top + h_compare)
	  } else if ((left_compare && w_compare < 0) || (t_class == "_left" && !right_compare)) {
	    t_class = "_left";
	    arrow_top = Math.round(tip_h - 13) / 2;
	    arrow_left = Math.round(tip_w);
	    marg_left = Math.round(left - (tip_w + opts.edgeOffset + 5));
	    marg_top = Math.round(top + h_compare)
	  }
	  var top_compare = (top + org_height + opts.edgeOffset + tip_h + 8) > parseInt($(window).height() + $(window).scrollTop());
	  var bottom_compare = ((top + org_height) - (opts.edgeOffset + tip_h + 8)) < 0;
	  if (top_compare || (t_class == "_bottom" && top_compare) || (t_class == "_top" && !bottom_compare)) {
	    if (t_class == "_top" || t_class == "_bottom") {
	      t_class = "_top"
	    } else {
	      t_class = t_class + "_top"
	    }
	    arrow_top = tip_h;
	    marg_top = Math.round(top - (tip_h + 5 + opts.edgeOffset))
	  } else if (bottom_compare | (t_class == "_top" && bottom_compare) || (t_class == "_bottom" && !top_compare)) {
	    if (t_class == "_top" || t_class == "_bottom") {
	      t_class = "_bottom"
	    } else {
	      t_class = t_class + "_bottom"
	    }
	    arrow_top = -12;
	    marg_top = Math.round(top + org_height + opts.edgeOffset)
	  }
	  if (t_class == "_right_top" || t_class == "_left_top") {
	    marg_top = marg_top + 5
	  } else if (t_class == "_right_bottom" || t_class == "_left_bottom") {
	    marg_top = marg_top - 5
	  }
	  if (t_class == "_left_top" || t_class == "_left_bottom") {
	    marg_left = marg_left + 5
	  }
	  tiptip_arrow.css({
	    "margin-left": arrow_left + "px",
	    "margin-top": arrow_top + "px"
	  });
	  tiptip_holder.css({
	    "margin-left": marg_left + "px",
	    "margin-top": marg_top + "px"
	  }).attr("class", "tip" + t_class);
	  if (timeout) {
	    clearTimeout(timeout)
	  }
	  timeout = setTimeout(function() {
	    tiptip_holder.stop(true, true).fadeIn(opts.fadeIn)
	  }, opts.delay)
	}

	function deactive_tiptip() {
	  opts.exit.call(this);
	  if (timeout) {
	    clearTimeout(timeout)
	  }
	  tiptip_holder.fadeOut(opts.fadeOut)
	}
      }
    })
  }
})(jQuery);

/*
 * ************ Noty
 */

"function" != typeof Object.create && (Object.create = function(a) {
  function b() {
  }
  return b.prototype = a, new b
}),
  function(a) {
    var b = {
      init: function(b) {
	return this.options = a.extend({}, a.noty.defaults, b), this.options.layout = this.options.custom ? a.noty.layouts.inline : a.noty.layouts[this.options.layout], a.noty.themes[this.options.theme] ? this.options.theme = a.noty.themes[this.options.theme] : b.themeClassName = this.options.theme, delete b.layout, delete b.theme, this.options = a.extend({}, this.options, this.options.layout.options), this.options.id = "noty_" + (new Date).getTime() * Math.floor(1e6 * Math.random()), this.options = a.extend({}, this.options, b), this._build(), this
      },
      _build: function() {
	var b = a('<div class="noty_bar noty_type_' + this.options.type + '"></div>').attr("id", this.options.id);
	if (b.append(this.options.template).find(".noty_text").html(this.options.text), this.$bar = null !== this.options.layout.parent.object ? a(this.options.layout.parent.object).css(this.options.layout.parent.css).append(b) : b, this.options.themeClassName && this.$bar.addClass(this.options.themeClassName).addClass("noty_container_type_" + this.options.type), this.options.buttons) {
	  this.options.closeWith = [], this.options.timeout = !1;
	  var c = a("<div/>").addClass("noty_buttons");
	  null !== this.options.layout.parent.object ? this.$bar.find(".noty_bar").append(c) : this.$bar.append(c);
	  var d = this;
	  a.each(this.options.buttons, function(b, c) {
	    var e = a("<button/>").addClass(c.addClass ? c.addClass : "gray").html(c.text).attr("id", c.id ? c.id : "button-" + b).appendTo(d.$bar.find(".noty_buttons")).bind("click", function() {
	      a.isFunction(c.onClick) && c.onClick.call(e, d)
	    })
	  })
	}
	this.$message = this.$bar.find(".noty_message"), this.$closeButton = this.$bar.find(".noty_close"), this.$buttons = this.$bar.find(".noty_buttons"), a.noty.store[this.options.id] = this
      },
      show: function() {
	var b = this;
	return b.options.custom ? b.options.custom.find(b.options.layout.container.selector).append(b.$bar) : a(b.options.layout.container.selector).append(b.$bar), b.options.theme && b.options.theme.style && b.options.theme.style.apply(b), "function" === a.type(b.options.layout.css) ? this.options.layout.css.apply(b.$bar) : b.$bar.css(this.options.layout.css || {}), b.$bar.addClass(b.options.layout.addClass), b.options.layout.container.style.apply(a(b.options.layout.container.selector)), b.showing = !0, b.options.theme && b.options.theme.style && b.options.theme.callback.onShow.apply(this), a.inArray("click", b.options.closeWith) > -1 && b.$bar.css("cursor", "pointer").one("click", function(a) {
	  b.stopPropagation(a), b.options.callback.onCloseClick && b.options.callback.onCloseClick.apply(b), b.close()
	}), a.inArray("hover", b.options.closeWith) > -1 && b.$bar.one("mouseenter", function() {
	  b.close()
	}), a.inArray("button", b.options.closeWith) > -1 && b.$closeButton.one("click", function(a) {
	  b.stopPropagation(a), b.close()
	}), -1 == a.inArray("button", b.options.closeWith) && b.$closeButton.remove(), b.options.callback.onShow && b.options.callback.onShow.apply(b), b.$bar.animate(b.options.animation.open, b.options.animation.speed, b.options.animation.easing, function() {
	  b.options.callback.afterShow && b.options.callback.afterShow.apply(b), b.showing = !1, b.shown = !0
	}), b.options.timeout && b.$bar.delay(b.options.timeout).promise().done(function() {
	  b.close()
	}), this
      },
      close: function() {
	if (!(this.closed || this.$bar && this.$bar.hasClass("i-am-closing-now"))) {
	  var b = this;
	  if (this.showing)
	    return b.$bar.queue(function() {
	      b.close.apply(b)
	    }), void 0;
	  if (!this.shown && !this.showing) {
	    var c = [];
	    return a.each(a.noty.queue, function(a, d) {
	      d.options.id != b.options.id && c.push(d)
	    }), a.noty.queue = c, void 0
	  }
	  b.$bar.addClass("i-am-closing-now"), b.options.callback.onClose && b.options.callback.onClose.apply(b), b.$bar.clearQueue().stop().animate(b.options.animation.close, b.options.animation.speed, b.options.animation.easing, function() {
	    b.options.callback.afterClose && b.options.callback.afterClose.apply(b)
	  }).promise().done(function() {
	    b.options.modal && (a.notyRenderer.setModalCount(-1), 0 == a.notyRenderer.getModalCount() && a(".noty_modal").fadeOut("fast", function() {
	      a(this).remove()
	    })), a.notyRenderer.setLayoutCountFor(b, -1), 0 == a.notyRenderer.getLayoutCountFor(b) && a(b.options.layout.container.selector).remove(), "undefined" != typeof b.$bar && null !== b.$bar && (b.$bar.remove(), b.$bar = null, b.closed = !0), delete a.noty.store[b.options.id], b.options.theme.callback && b.options.theme.callback.onClose && b.options.theme.callback.onClose.apply(b), b.options.dismissQueue || (a.noty.ontap = !0, a.notyRenderer.render()), b.options.maxVisible > 0 && b.options.dismissQueue && a.notyRenderer.render()
	  })
	}
      },
      setText: function(a) {
	return this.closed || (this.options.text = a, this.$bar.find(".noty_text").html(a)), this
      },
      setType: function(a) {
	return this.closed || (this.options.type = a, this.options.theme.style.apply(this), this.options.theme.callback.onShow.apply(this)), this
      },
      setTimeout: function(a) {
	if (!this.closed) {
	  var b = this;
	  this.options.timeout = a, b.$bar.delay(b.options.timeout).promise().done(function() {
	    b.close()
	  })
	}
	return this
      },
      stopPropagation: function(a) {
	a = a || window.event, "undefined" != typeof a.stopPropagation ? a.stopPropagation() : a.cancelBubble = !0
      },
      closed: !1,
      showing: !1,
      shown: !1
    };
    a.notyRenderer = {}, a.notyRenderer.init = function(c) {
      var d = Object.create(b).init(c);
      return d.options.killer && a.noty.closeAll(), d.options.force ? a.noty.queue.unshift(d) : a.noty.queue.push(d), a.notyRenderer.render(), "object" == a.noty.returns ? d : d.options.id
    }, a.notyRenderer.render = function() {
      var b = a.noty.queue[0];
      "object" === a.type(b) ? b.options.dismissQueue ? b.options.maxVisible > 0 ? a(b.options.layout.container.selector + " li").length < b.options.maxVisible && a.notyRenderer.show(a.noty.queue.shift()) : a.notyRenderer.show(a.noty.queue.shift()) : a.noty.ontap && (a.notyRenderer.show(a.noty.queue.shift()), a.noty.ontap = !1) : a.noty.ontap = !0
    }, a.notyRenderer.show = function(b) {
      b.options.modal && (a.notyRenderer.createModalFor(b), a.notyRenderer.setModalCount(1)), b.options.custom ? 0 == b.options.custom.find(b.options.layout.container.selector).length ? b.options.custom.append(a(b.options.layout.container.object).addClass("i-am-new")) : b.options.custom.find(b.options.layout.container.selector).removeClass("i-am-new") : 0 == a(b.options.layout.container.selector).length ? a("body").append(a(b.options.layout.container.object).addClass("i-am-new")) : a(b.options.layout.container.selector).removeClass("i-am-new"), a.notyRenderer.setLayoutCountFor(b, 1), b.show()
    }, a.notyRenderer.createModalFor = function(b) {
      0 == a(".noty_modal").length && a("<div/>").addClass("noty_modal").data("noty_modal_count", 0).css(b.options.theme.modal.css).prependTo(a("body")).fadeIn("fast")
    }, a.notyRenderer.getLayoutCountFor = function(b) {
      return a(b.options.layout.container.selector).data("noty_layout_count") || 0
    }, a.notyRenderer.setLayoutCountFor = function(b, c) {
      return a(b.options.layout.container.selector).data("noty_layout_count", a.notyRenderer.getLayoutCountFor(b) + c)
    }, a.notyRenderer.getModalCount = function() {
      return a(".noty_modal").data("noty_modal_count") || 0
    }, a.notyRenderer.setModalCount = function(b) {
      return a(".noty_modal").data("noty_modal_count", a.notyRenderer.getModalCount() + b)
    }, a.fn.noty = function(b) {
      return b.custom = a(this), a.notyRenderer.init(b)
    }, a.noty = {}, a.noty.queue = [], a.noty.ontap = !0, a.noty.layouts = {}, a.noty.themes = {}, a.noty.returns = "object", a.noty.store = {}, a.noty.get = function(b) {
      return a.noty.store.hasOwnProperty(b) ? a.noty.store[b] : !1
    }, a.noty.close = function(b) {
      return a.noty.get(b) ? a.noty.get(b).close() : !1
    }, a.noty.setText = function(b, c) {
      return a.noty.get(b) ? a.noty.get(b).setText(c) : !1
    }, a.noty.setType = function(b, c) {
      return a.noty.get(b) ? a.noty.get(b).setType(c) : !1
    }, a.noty.clearQueue = function() {
      a.noty.queue = []
    }, a.noty.closeAll = function() {
      a.noty.clearQueue(), a.each(a.noty.store, function(a, b) {
	b.close()
      })
    };
    var c = window.alert;
    a.noty.consumeAlert = function(b) {
      window.alert = function(c) {
	b ? b.text = c : b = {
	  text: c
	}, a.notyRenderer.init(b)
      }
    }, a.noty.stopConsumeAlert = function() {
      window.alert = c
    }, a.noty.defaults = {
      layout: "top",
      theme: "defaultTheme",
      type: "alert",
      text: "",
      dismissQueue: !0,
      template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
      animation: {
	open: {
	  height: "toggle"
	},
	close: {
	  height: "toggle"
	},
	easing: "swing",
	speed: 500
      },
      timeout: !1,
      force: !1,
      modal: !1,
      maxVisible: 5,
      killer: !1,
      closeWith: ["click"],
      callback: {
	onShow: function() {
	},
	afterShow: function() {
	},
	onClose: function() {
	},
	afterClose: function() {
	},
	onCloseClick: function() {
	}
      },
      buttons: !1
    }, a(window).resize(function() {
      a.each(a.noty.layouts, function(b, c) {
	c.container.style.apply(a(c.container.selector))
      })
    })
  }(jQuery), window.noty = function(a) {
  return jQuery.notyRenderer.init(a)
},
  function(a) {
    a.noty.layouts.bottom = {
      name: "bottom",
      options: {},
      container: {
	object: '<ul id="noty_bottom_layout_container" />',
	selector: "ul#noty_bottom_layout_container",
	style: function() {
	  a(this).css({
	    bottom: 0,
	    left: "5%",
	    position: "fixed",
	    width: "90%",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 9999999
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.bottomCenter = {
      name: "bottomCenter",
      options: {},
      container: {
	object: '<ul id="noty_bottomCenter_layout_container" />',
	selector: "ul#noty_bottomCenter_layout_container",
	style: function() {
	  a(this).css({
	    bottom: 20,
	    left: 0,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  }), a(this).css({
	    left: (a(window).width() - a(this).outerWidth(!1)) / 2 + "px"
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.bottomLeft = {
      name: "bottomLeft",
      options: {},
      container: {
	object: '<ul id="noty_bottomLeft_layout_container" />',
	selector: "ul#noty_bottomLeft_layout_container",
	style: function() {
	  a(this).css({
	    bottom: 20,
	    left: 20,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  }), window.innerWidth < 600 && a(this).css({
	    left: 5
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.bottomRight = {
      name: "bottomRight",
      options: {},
      container: {
	object: '<ul id="noty_bottomRight_layout_container" />',
	selector: "ul#noty_bottomRight_layout_container",
	style: function() {
	  a(this).css({
	    bottom: 20,
	    right: 20,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  }), window.innerWidth < 600 && a(this).css({
	    right: 5
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.center = {
      name: "center",
      options: {},
      container: {
	object: '<ul id="noty_center_layout_container" />',
	selector: "ul#noty_center_layout_container",
	style: function() {
	  a(this).css({
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  });
	  var b = a(this).clone().css({
	    visibility: "hidden",
	    display: "block",
	    position: "absolute",
	    top: 0,
	    left: 0
	  }).attr("id", "dupe");
	  a("body").append(b), b.find(".i-am-closing-now").remove(), b.find("li").css("display", "block");
	  var c = b.height();
	  b.remove(), a(this).hasClass("i-am-new") ? a(this).css({
	    left: (a(window).width() - a(this).outerWidth(!1)) / 2 + "px",
	    top: (a(window).height() - c) / 2 + "px"
	  }) : a(this).animate({
	    left: (a(window).width() - a(this).outerWidth(!1)) / 2 + "px",
	    top: (a(window).height() - c) / 2 + "px"
	  }, 500)
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.centerLeft = {
      name: "centerLeft",
      options: {},
      container: {
	object: '<ul id="noty_centerLeft_layout_container" />',
	selector: "ul#noty_centerLeft_layout_container",
	style: function() {
	  a(this).css({
	    left: 20,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  });
	  var b = a(this).clone().css({
	    visibility: "hidden",
	    display: "block",
	    position: "absolute",
	    top: 0,
	    left: 0
	  }).attr("id", "dupe");
	  a("body").append(b), b.find(".i-am-closing-now").remove(), b.find("li").css("display", "block");
	  var c = b.height();
	  b.remove(), a(this).hasClass("i-am-new") ? a(this).css({
	    top: (a(window).height() - c) / 2 + "px"
	  }) : a(this).animate({
	    top: (a(window).height() - c) / 2 + "px"
	  }, 500), window.innerWidth < 600 && a(this).css({
	    left: 5
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.centerRight = {
      name: "centerRight",
      options: {},
      container: {
	object: '<ul id="noty_centerRight_layout_container" />',
	selector: "ul#noty_centerRight_layout_container",
	style: function() {
	  a(this).css({
	    right: 20,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  });
	  var b = a(this).clone().css({
	    visibility: "hidden",
	    display: "block",
	    position: "absolute",
	    top: 0,
	    left: 0
	  }).attr("id", "dupe");
	  a("body").append(b), b.find(".i-am-closing-now").remove(), b.find("li").css("display", "block");
	  var c = b.height();
	  b.remove(), a(this).hasClass("i-am-new") ? a(this).css({
	    top: (a(window).height() - c) / 2 + "px"
	  }) : a(this).animate({
	    top: (a(window).height() - c) / 2 + "px"
	  }, 500), window.innerWidth < 600 && a(this).css({
	    right: 5
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.inline = {
      name: "inline",
      options: {},
      container: {
	object: '<ul class="noty_inline_layout_container" />',
	selector: "ul.noty_inline_layout_container",
	style: function() {
	  a(this).css({
	    width: "100%",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 9999999
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.top = {
      name: "top",
      options: {},
      container: {
	object: '<ul id="noty_top_layout_container" />',
	selector: "ul#noty_top_layout_container",
	style: function() {
	  a(this).css({
	    top: 0,
	    left: "5%",
	    position: "fixed",
	    width: "90%",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 9999999
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.topCenter = {
      name: "topCenter",
      options: {},
      container: {
	object: '<ul id="noty_topCenter_layout_container" />',
	selector: "ul#noty_topCenter_layout_container",
	style: function() {
	  a(this).css({
	    top: 20,
	    left: 0,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  }), a(this).css({
	    left: (a(window).width() - a(this).outerWidth(!1)) / 2 + "px"
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.topLeft = {
      name: "topLeft",
      options: {},
      container: {
	object: '<ul id="noty_topLeft_layout_container" />',
	selector: "ul#noty_topLeft_layout_container",
	style: function() {
	  a(this).css({
	    top: 20,
	    left: 20,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  }), window.innerWidth < 600 && a(this).css({
	    left: 5
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.layouts.topRight = {
      name: "topRight",
      options: {},
      container: {
	object: '<ul id="noty_topRight_layout_container" />',
	selector: "ul#noty_topRight_layout_container",
	style: function() {
	  a(this).css({
	    top: 20,
	    right: 20,
	    position: "fixed",
	    width: "310px",
	    height: "auto",
	    margin: 0,
	    padding: 0,
	    listStyleType: "none",
	    zIndex: 1e7
	  }), window.innerWidth < 600 && a(this).css({
	    right: 5
	  })
	}
      },
      parent: {
	object: "<li />",
	selector: "li",
	css: {}
      },
      css: {
	display: "none",
	width: "310px"
      },
      addClass: ""
    }
  }(jQuery),
  function(a) {
    a.noty.themes.defaultTheme = {
      name: "defaultTheme",
      helpers: {
	borderFix: function() {
	  if (this.options.dismissQueue) {
	    var b = this.options.layout.container.selector + " " + this.options.layout.parent.selector;
	    switch (this.options.layout.name) {
	      case "top":
		a(b).css({
		  borderRadius: "0px 0px 0px 0px"
		}), a(b).last().css({
		  borderRadius: "0px 0px 5px 5px"
		});
		break;
	      case "topCenter":
	      case "topLeft":
	      case "topRight":
	      case "bottomCenter":
	      case "bottomLeft":
	      case "bottomRight":
	      case "center":
	      case "centerLeft":
	      case "centerRight":
	      case "inline":
		a(b).css({
		  borderRadius: "0px 0px 0px 0px"
		}), a(b).first().css({
		  "border-top-left-radius": "5px",
		  "border-top-right-radius": "5px"
		}), a(b).last().css({
		  "border-bottom-left-radius": "5px",
		  "border-bottom-right-radius": "5px"
		});
		break;
	      case "bottom":
		a(b).css({
		  borderRadius: "0px 0px 0px 0px"
		}), a(b).first().css({
		  borderRadius: "5px 5px 0px 0px"
		})
	    }
	  }
	}
      },
      modal: {
	css: {
	  position: "fixed",
	  width: "100%",
	  height: "100%",
	  backgroundColor: "#000",
	  zIndex: 1e4,
	  opacity: .6,
	  display: "none",
	  left: 0,
	  top: 0
	}
      },
      style: function() {
	switch (this.$bar.css( {
	    overflow: "hidden",
	    background: "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAoCAYAAAAPOoFWAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAPZJREFUeNq81tsOgjAMANB2ov7/7ypaN7IlIwi9rGuT8QSc9EIDAsAznxvY4pXPKr05RUE5MEVB+TyWfCEl9LZApYopCmo9C4FKSMtYoI8Bwv79aQJU4l6hXXCZrQbokJEksxHo9KMOgc6w1atHXM8K9DVC7FQnJ0i8iK3QooGgbnyKgMDygBWyYFZoqx4qS27KqLZJjA1D0jK6QJcYEQEiWv9PGkTsbqxQ8oT+ZtZB6AkdsJnQDnMoHXHLGKOgDYuCWmYhEERCI5gaamW0bnHdA3k2ltlIN+2qKRyCND0bhqSYCyTB3CAOc4WusBEIpkeBuPgJMAAX8Hs1NfqHRgAAAABJRU5ErkJggg==') repeat-x scroll left top #fff"
	  }), this.$message.css({
	    fontSize: "13px",
	    lineHeight: "16px",
	    textAlign: "center",
	    padding: "8px 10px 9px",
	    width: "auto",
	    position: "relative"
	  }), this.$closeButton.css({
	    position: "absolute",
	    top: 4,
	    right: 4,
	    width: 10,
	    height: 10,
	    background: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAATpJREFUeNoszrFqVFEUheG19zlz7sQ7ijMQBAvfYBqbpJCoZSAQbOwEE1IHGytbLQUJ8SUktW8gCCFJMSGSNxCmFBJO7j5rpXD6n5/P5vM53H3b3T9LOiB5AQDuDjM7BnA7DMPHDGBH0nuSzwHsRcRVRNRSysuU0i6AOwA/02w2+9Fae00SEbEh6SGAR5K+k3zWWptKepCm0+kpyRoRGyRBcpPkDsn1iEBr7drdP2VJZyQXERGSPpiZAViTBACXKaV9kqd5uVzCzO5KKb/d/UZSDwD/eyxqree1VqSu6zKAF2Z2RPJJaw0rAkjOJT0m+SuT/AbgDcmnkmBmfwAsJL1dXQ8lWY6IGwB1ZbrOOb8zs8thGP4COFwx/mE8Ho9Go9ErMzvJOW/1fY/JZIJSypqZfXX3L13X9fcDAKJct1sx3OiuAAAAAElFTkSuQmCC)",
	    display: "none",
	    cursor: "pointer"
	  }), this.$buttons.css({
	    padding: 5,
	    textAlign: "right",
	    borderTop: "1px solid #ccc",
	    backgroundColor: "#fff"
	  }), this.$buttons.find("button").css({
	    marginLeft: 5
	  }), this.$buttons.find("button:first").css({
	    marginLeft: 0
	  }), this.$bar.bind({
	    mouseenter: function() {
	      a(this).find(".noty_close").stop().fadeTo("normal", 1)
	    },
	    mouseleave: function() {
	      a(this).find(".noty_close").stop().fadeTo("normal", 0)
	    }
	  }), this.options.layout.name) {
	  case "top":
	    this.$bar.css({
	      borderRadius: "0px 0px 5px 5px",
	      borderBottom: "2px solid #eee",
	      borderLeft: "2px solid #eee",
	      borderRight: "2px solid #eee",
	      boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
	    });
	    break;
	  case "topCenter":
	  case "center":
	  case "bottomCenter":
	  case "inline":
	    this.$bar.css({
	      borderRadius: "5px",
	      border: "1px solid #eee",
	      boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
	    }), this.$message.css({
	      fontSize: "13px",
	      textAlign: "center"
	    });
	    break;
	  case "topLeft":
	  case "topRight":
	  case "bottomLeft":
	  case "bottomRight":
	  case "centerLeft":
	  case "centerRight":
	    this.$bar.css({
	      borderRadius: "5px",
	      border: "1px solid #eee",
	      boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
	    }), this.$message.css({
	      fontSize: "13px",
	      textAlign: "left"
	    });
	    break;
	  case "bottom":
	    this.$bar.css({
	      borderRadius: "5px 5px 0px 0px",
	      borderTop: "2px solid #eee",
	      borderLeft: "2px solid #eee",
	      borderRight: "2px solid #eee",
	      boxShadow: "0 -2px 4px rgba(0, 0, 0, 0.1)"
	    });
	    break;
	  default:
	    this.$bar.css({
	      border: "2px solid #eee",
	      boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
	    })
	}
	switch (this.options.type) {
	  case "alert":
	  case "notification":
	    this.$bar.css({
	      backgroundColor: "#FFF",
	      borderColor: "#CCC",
	      color: "#444"
	    });
	    break;
	  case "warning":
	    this.$bar.css({
	      backgroundColor: "#FFEAA8",
	      borderColor: "#FFC237",
	      color: "#826200"
	    }), this.$buttons.css({
	      borderTop: "1px solid #FFC237"
	    });
	    break;
	  case "error":
	    this.$bar.css({
	      backgroundColor: "red",
	      borderColor: "darkred",
	      color: "#FFF"
	    }), this.$message.css({
	      fontWeight: "bold"
	    }), this.$buttons.css({
	      borderTop: "1px solid darkred"
	    });
	    break;
	  case "information":
	    this.$bar.css({
	      backgroundColor: "#57B7E2",
	      borderColor: "#0B90C4",
	      color: "#FFF"
	    }), this.$buttons.css({
	      borderTop: "1px solid #0B90C4"
	    });
	    break;
	  case "success":
	    this.$bar.css({
	      backgroundColor: "lightgreen",
	      borderColor: "#50C24E",
	      color: "darkgreen"
	    }), this.$buttons.css({
	      borderTop: "1px solid #50C24E"
	    });
	    break;
	  default:
	    this.$bar.css({
	      backgroundColor: "#FFF",
	      borderColor: "#CCC",
	      color: "#444"
	    })
	}
      },
      callback: {
	onShow: function() {
	  a.noty.themes.defaultTheme.helpers.borderFix.apply(this)
	},
	onClose: function() {
	  a.noty.themes.defaultTheme.helpers.borderFix.apply(this)
	}
      }
    }
  }(jQuery);

/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.2
 *
 */
(function(f) {
  jQuery.fn.extend({
    slimScroll: function(g) {
      var a = f.extend({
	width: "auto",
	height: "250px",
	size: "7px",
	color: "#000",
	position: "right",
	distance: "1px",
	start: "top",
	opacity: 0.4,
	alwaysVisible: !1,
	disableFadeOut: !1,
	railVisible: !1,
	railColor: "#333",
	railOpacity: 0.2,
	railDraggable: !0,
	railClass: "slimScrollRail",
	barClass: "slimScrollBar",
	wrapperClass: "slimScrollDiv",
	allowPageScroll: !1,
	wheelStep: 20,
	touchScrollStep: 200,
	borderRadius: "7px",
	railBorderRadius: "7px"
      }, g);
      this.each(function() {
	function u(d) {
	  if (r) {
	    d = d ||
	      window.event;
	    var c = 0;
	    d.wheelDelta && (c = -d.wheelDelta / 120);
	    d.detail && (c = d.detail / 3);
	    f(d.target || d.srcTarget || d.srcElement).closest("." + a.wrapperClass).is(b.parent()) && m(c, !0);
	    d.preventDefault && !k && d.preventDefault();
	    k || (d.returnValue = !1)
	  }
	}

	function m(d, f, g) {
	  k = !1;
	  var e = d,
	    h = b.outerHeight() - c.outerHeight();
	  f && (e = parseInt(c.css("top")) + d * parseInt(a.wheelStep) / 100 * c.outerHeight(), e = Math.min(Math.max(e, 0), h), e = 0 < d ? Math.ceil(e) : Math.floor(e), c.css({
	    top: e + "px"
	  }));
	  l = parseInt(c.css("top")) / (b.outerHeight() - c.outerHeight());
	  e = l * (b[0].scrollHeight - b.outerHeight());
	  g && (e = d, d = e / b[0].scrollHeight * b.outerHeight(), d = Math.min(Math.max(d, 0), h), c.css({
	    top: d + "px"
	  }));
	  b.scrollTop(e);
	  b.trigger("slimscrolling", ~~e);
	  v();
	  p()
	}

	function C() {
	  window.addEventListener ? (this.addEventListener("DOMMouseScroll", u, !1), this.addEventListener("mousewheel", u, !1)) : document.attachEvent("onmousewheel", u)
	}

	function w() {
	  s = Math.max(b.outerHeight() / b[0].scrollHeight * b.outerHeight(), D);
	  c.css({
	    height: s + "px"
	  });
	  var a = s == b.outerHeight() ? "none" : "block";
	  c.css({
	    display: a
	  })
	}

	function v() {
	  w();
	  clearTimeout(A);
	  l == ~~l ? (k = a.allowPageScroll, B != l && b.trigger("slimscroll", 0 == ~~l ? "top" : "bottom")) : k = !1;
	  B = l;
	  s >= b.outerHeight() ? k = !0 : (c.stop(!0, !0).fadeIn("fast"), a.railVisible && h.stop(!0, !0).fadeIn("fast"))
	}

	function p() {
	  a.alwaysVisible || (A = setTimeout(function() {
	    a.disableFadeOut && r || x || y || (c.fadeOut("slow"), h.fadeOut("slow"))
	  }, 1E3))
	}
	var r, x, y, A, z, s, l, B, D = 30,
	  k = !1,
	  b = f(this);
	if (b.parent().hasClass(a.wrapperClass)) {
	  var n = b.scrollTop(),
	    c = b.parent().find("." + a.barClass),
	    h = b.parent().find("." +
	    a.railClass);
	  w();
	  if (f.isPlainObject(g)) {
	    if ("height" in g && "auto" == g.height) {
	      b.parent().css("height", "auto");
	      b.css("height", "auto");
	      var q = b.parent().parent().height();
	      b.parent().css("height", q);
	      b.css("height", q)
	    }
	    if ("scrollTo" in g)
	      n = parseInt(a.scrollTo);
	    else if ("scrollBy" in g)
	      n += parseInt(a.scrollBy);
	    else if ("destroy" in g) {
	      c.remove();
	      h.remove();
	      b.unwrap();
	      return
	    }
	    m(n, !1, !0)
	  }
	} else {
	  a.height = "auto" == g.height ? b.parent().height() : g.height;
	  n = f("<div></div>").addClass(a.wrapperClass).css({
	    position: "relative",
	    overflow: "hidden",
	    width: a.width,
	    height: a.height
	  });
	  b.css({
	    overflow: "hidden",
	    width: a.width,
	    height: a.height
	  });
	  var h = f("<div></div>").addClass(a.railClass).css({
	    width: a.size,
	    height: "100%",
	    position: "absolute",
	    top: 0,
	    display: a.alwaysVisible && a.railVisible ? "block" : "none",
	    "border-radius": a.railBorderRadius,
	    background: a.railColor,
	    opacity: a.railOpacity,
	    zIndex: 90
	  }),
	  c = f("<div></div>").addClass(a.barClass).css({
	    background: a.color,
	    width: a.size,
	    position: "absolute",
	    top: 0,
	    opacity: a.opacity,
	    display: a.alwaysVisible ?
	      "block" : "none",
	    "border-radius": a.borderRadius,
	    BorderRadius: a.borderRadius,
	    MozBorderRadius: a.borderRadius,
	    WebkitBorderRadius: a.borderRadius,
	    zIndex: 99
	  }),
	  q = "right" == a.position ? {
	    right: a.distance
	  } : {
	    left: a.distance
	  };
	  h.css(q);
	  c.css(q);
	  b.wrap(n);
	  b.parent().append(c);
	  b.parent().append(h);
	  a.railDraggable && c.bind("mousedown", function(a) {
	    var b = f(document);
	    y = !0;
	    t = parseFloat(c.css("top"));
	    pageY = a.pageY;
	    b.bind("mousemove.slimscroll", function(a) {
	      currTop = t + a.pageY - pageY;
	      c.css("top", currTop);
	      m(0, c.position().top, !1)
	    });
	    b.bind("mouseup.slimscroll", function(a) {
	      y = !1;
	      p();
	      b.unbind(".slimscroll")
	    });
	    return !1
	  }).bind("selectstart.slimscroll", function(a) {
	    a.stopPropagation();
	    a.preventDefault();
	    return !1
	  });
	  h.hover(function() {
	    v()
	  }, function() {
	    p()
	  });
	  c.hover(function() {
	    x = !0
	  }, function() {
	    x = !1
	  });
	  b.hover(function() {
	    r = !0;
	    v();
	    p()
	  }, function() {
	    r = !1;
	    p()
	  });
	  b.bind("touchstart", function(a, b) {
	    a.originalEvent.touches.length && (z = a.originalEvent.touches[0].pageY)
	  });
	  b.bind("touchmove", function(b) {
	    k || b.originalEvent.preventDefault();
	    b.originalEvent.touches.length &&
	      (m((z - b.originalEvent.touches[0].pageY) / a.touchScrollStep, !0), z = b.originalEvent.touches[0].pageY)
	  });
	  w();
	  "bottom" === a.start ? (c.css({
	    top: b.outerHeight() - c.outerHeight()
	  }), m(0, !0)) : "top" !== a.start && (m(f(a.start).position().top, null, !0), a.alwaysVisible || c.hide());
	  C()
	}
      });
      return this
    }
  });
  jQuery.fn.extend({
    slimscroll: jQuery.fn.slimScroll
  })
})(jQuery);

/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.2
 *
 */
(function(f) {
  jQuery.fn.extend({
    slimScroll: function(g) {
      var a = f.extend({
	width: "auto",
	height: "250px",
	size: "7px",
	color: "#000",
	position: "right",
	distance: "1px",
	start: "top",
	opacity: 0.4,
	alwaysVisible: !1,
	disableFadeOut: !1,
	railVisible: !1,
	railColor: "#333",
	railOpacity: 0.2,
	railDraggable: !0,
	railClass: "slimScrollRail",
	barClass: "slimScrollBar",
	wrapperClass: "slimScrollDiv",
	allowPageScroll: !1,
	wheelStep: 20,
	touchScrollStep: 200,
	borderRadius: "7px",
	railBorderRadius: "7px"
      }, g);
      this.each(function() {
	function u(d) {
	  if (r) {
	    d = d ||
	      window.event;
	    var c = 0;
	    d.wheelDelta && (c = -d.wheelDelta / 120);
	    d.detail && (c = d.detail / 3);
	    f(d.target || d.srcTarget || d.srcElement).closest("." + a.wrapperClass).is(b.parent()) && m(c, !0);
	    d.preventDefault && !k && d.preventDefault();
	    k || (d.returnValue = !1)
	  }
	}

	function m(d, f, g) {
	  k = !1;
	  var e = d,
	    h = b.outerHeight() - c.outerHeight();
	  f && (e = parseInt(c.css("top")) + d * parseInt(a.wheelStep) / 100 * c.outerHeight(), e = Math.min(Math.max(e, 0), h), e = 0 < d ? Math.ceil(e) : Math.floor(e), c.css({
	    top: e + "px"
	  }));
	  l = parseInt(c.css("top")) / (b.outerHeight() - c.outerHeight());
	  e = l * (b[0].scrollHeight - b.outerHeight());
	  g && (e = d, d = e / b[0].scrollHeight * b.outerHeight(), d = Math.min(Math.max(d, 0), h), c.css({
	    top: d + "px"
	  }));
	  b.scrollTop(e);
	  b.trigger("slimscrolling", ~~e);
	  v();
	  p()
	}

	function C() {
	  window.addEventListener ? (this.addEventListener("DOMMouseScroll", u, !1), this.addEventListener("mousewheel", u, !1)) : document.attachEvent("onmousewheel", u)
	}

	function w() {
	  s = Math.max(b.outerHeight() / b[0].scrollHeight * b.outerHeight(), D);
	  c.css({
	    height: s + "px"
	  });
	  var a = s == b.outerHeight() ? "none" : "block";
	  c.css({
	    display: a
	  })
	}

	function v() {
	  w();
	  clearTimeout(A);
	  l == ~~l ? (k = a.allowPageScroll, B != l && b.trigger("slimscroll", 0 == ~~l ? "top" : "bottom")) : k = !1;
	  B = l;
	  s >= b.outerHeight() ? k = !0 : (c.stop(!0, !0).fadeIn("fast"), a.railVisible && h.stop(!0, !0).fadeIn("fast"))
	}

	function p() {
	  a.alwaysVisible || (A = setTimeout(function() {
	    a.disableFadeOut && r || x || y || (c.fadeOut("slow"), h.fadeOut("slow"))
	  }, 1E3))
	}
	var r, x, y, A, z, s, l, B, D = 30,
	  k = !1,
	  b = f(this);
	if (b.parent().hasClass(a.wrapperClass)) {
	  var n = b.scrollTop(),
	    c = b.parent().find("." + a.barClass),
	    h = b.parent().find("." +
	    a.railClass);
	  w();
	  if (f.isPlainObject(g)) {
	    if ("height" in g && "auto" == g.height) {
	      b.parent().css("height", "auto");
	      b.css("height", "auto");
	      var q = b.parent().parent().height();
	      b.parent().css("height", q);
	      b.css("height", q)
	    }
	    if ("scrollTo" in g)
	      n = parseInt(a.scrollTo);
	    else if ("scrollBy" in g)
	      n += parseInt(a.scrollBy);
	    else if ("destroy" in g) {
	      c.remove();
	      h.remove();
	      b.unwrap();
	      return
	    }
	    m(n, !1, !0)
	  }
	} else {
	  a.height = "auto" == g.height ? b.parent().height() : g.height;
	  n = f("<div></div>").addClass(a.wrapperClass).css({
	    position: "relative",
	    overflow: "hidden",
	    width: a.width,
	    height: a.height
	  });
	  b.css({
	    overflow: "hidden",
	    width: a.width,
	    height: a.height
	  });
	  var h = f("<div></div>").addClass(a.railClass).css({
	    width: a.size,
	    height: "100%",
	    position: "absolute",
	    top: 0,
	    display: a.alwaysVisible && a.railVisible ? "block" : "none",
	    "border-radius": a.railBorderRadius,
	    background: a.railColor,
	    opacity: a.railOpacity,
	    zIndex: 90
	  }),
	  c = f("<div></div>").addClass(a.barClass).css({
	    background: a.color,
	    width: a.size,
	    position: "absolute",
	    top: 0,
	    opacity: a.opacity,
	    display: a.alwaysVisible ?
	      "block" : "none",
	    "border-radius": a.borderRadius,
	    BorderRadius: a.borderRadius,
	    MozBorderRadius: a.borderRadius,
	    WebkitBorderRadius: a.borderRadius,
	    zIndex: 99
	  }),
	  q = "right" == a.position ? {
	    right: a.distance
	  } : {
	    left: a.distance
	  };
	  h.css(q);
	  c.css(q);
	  b.wrap(n);
	  b.parent().append(c);
	  b.parent().append(h);
	  a.railDraggable && c.bind("mousedown", function(a) {
	    var b = f(document);
	    y = !0;
	    t = parseFloat(c.css("top"));
	    pageY = a.pageY;
	    b.bind("mousemove.slimscroll", function(a) {
	      currTop = t + a.pageY - pageY;
	      c.css("top", currTop);
	      m(0, c.position().top, !1)
	    });
	    b.bind("mouseup.slimscroll", function(a) {
	      y = !1;
	      p();
	      b.unbind(".slimscroll")
	    });
	    return !1
	  }).bind("selectstart.slimscroll", function(a) {
	    a.stopPropagation();
	    a.preventDefault();
	    return !1
	  });
	  h.hover(function() {
	    v()
	  }, function() {
	    p()
	  });
	  c.hover(function() {
	    x = !0
	  }, function() {
	    x = !1
	  });
	  b.hover(function() {
	    r = !0;
	    v();
	    p()
	  }, function() {
	    r = !1;
	    p()
	  });
	  b.bind("touchstart", function(a, b) {
	    a.originalEvent.touches.length && (z = a.originalEvent.touches[0].pageY)
	  });
	  b.bind("touchmove", function(b) {
	    k || b.originalEvent.preventDefault();
	    b.originalEvent.touches.length &&
	      (m((z - b.originalEvent.touches[0].pageY) / a.touchScrollStep, !0), z = b.originalEvent.touches[0].pageY)
	  });
	  w();
	  "bottom" === a.start ? (c.css({
	    top: b.outerHeight() - c.outerHeight()
	  }), m(0, !0)) : "top" !== a.start && (m(f(a.start).position().top, null, !0), a.alwaysVisible || c.hide());
	  C()
	}
      });
      return this
    }
  });
  jQuery.fn.extend({
    slimscroll: jQuery.fn.slimScroll
  })
})(jQuery);

/*
 * Jeditable - jQuery in place edit plugin
 *
 * Copyright (c) 2006-2013 Mika Tuupola, Dylan Verheul
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/jeditable
 *
 * Based on editable by Dylan Verheul <dylan_at_dyve.net>:
 *    http://www.dyve.net/jquery/?editable
 *
 */

/**
 * Version 1.7.3
 *
 * ** means there is basic unit tests for this parameter.
 *
 * @name  Jeditable
 * @type  jQuery
 * @param String  target             (POST) URL or function to send edited content to **
 * @param Hash    options            additional options
 * @param String  options[method]    method to use to send edited content (POST or PUT) **
 * @param Function options[callback] Function to run after submitting edited content **
 * @param String  options[name]      POST parameter name of edited content
 * @param String  options[id]        POST parameter name of edited div id
 * @param Hash    options[submitdata] Extra parameters to send when submitting edited content.
 * @param String  options[type]      text, textarea or select (or any 3rd party input type) **
 * @param Integer options[rows]      number of rows if using textarea **
 * @param Integer options[cols]      number of columns if using textarea **
 * @param Mixed   options[height]    'auto', 'none' or height in pixels **
 * @param Mixed   options[width]     'auto', 'none' or width in pixels **
 * @param String  options[loadurl]   URL to fetch input content before editing **
 * @param String  options[loadtype]  Request type for load url. Should be GET or POST.
 * @param String  options[loadtext]  Text to display while loading external content.
 * @param Mixed   options[loaddata]  Extra parameters to pass when fetching content before editing.
 * @param Mixed   options[data]      Or content given as paramameter. String or function.**
 * @param String  options[indicator] indicator html to show when saving
 * @param String  options[tooltip]   optional tooltip text via title attribute **
 * @param String  options[event]     jQuery event such as 'click' of 'dblclick' **
 * @param String  options[submit]    submit button value, empty means no button **
 * @param String  options[cancel]    cancel button value, empty means no button **
 * @param String  options[cssclass]  CSS class to apply to input form. 'inherit' to copy from parent. **
 * @param String  options[style]     Style to apply to input form 'inherit' to copy from parent. **
 * @param String  options[select]    true or false, when true text is highlighted ??
 * @param String  options[placeholder] Placeholder text or html to insert when element is empty. **
 * @param String  options[onblur]    'cancel', 'submit', 'ignore' or function ??
 *
 * @param Function options[onsubmit] function(settings, original) { ... } called before submit
 * @param Function options[onreset]  function(settings, original) { ... } called before reset
 * @param Function options[onerror]  function(settings, original, xhr) { ... } called on error
 *
 * @param Hash    options[ajaxoptions]  jQuery Ajax options. See docs.jquery.com.
 *
 */


(function($) {
  $.fn.editable = function(target, options) {
    if ('disable' == target) {
      $(this).data('disabled.editable', true);
      return;
    }
    if ('enable' == target) {
      $(this).data('disabled.editable', false);
      return;
    }
    if ('destroy' == target) {
      $(this).unbind($(this).data('event.editable')).removeData('disabled.editable').removeData('event.editable');
      return;
    }
    var settings = $.extend({}, $.fn.editable.defaults, {
      target: target
    }, options);
    var plugin = $.editable.types[settings.type].plugin || function() {
    };
    var submit = $.editable.types[settings.type].submit || function() {
    };
    var buttons = $.editable.types[settings.type].buttons || $.editable.types['defaults'].buttons;
    var content = $.editable.types[settings.type].content || $.editable.types['defaults'].content;
    var element = $.editable.types[settings.type].element || $.editable.types['defaults'].element;
    var reset = $.editable.types[settings.type].reset || $.editable.types['defaults'].reset;
    var callback = settings.callback || function() {
    };
    var onedit = settings.onedit || function() {
    };
    var onsubmit = settings.onsubmit || function() {
    };
    var onreset = settings.onreset || function() {
    };
    var onerror = settings.onerror || reset;
    if (settings.tooltip) {
      $(this).attr('title', settings.tooltip);
    }
    settings.autowidth = 'auto' == settings.width;
    settings.autoheight = 'auto' == settings.height;
    return this.each(function() {
      var self = this;
      var savedwidth = $(self).width();
      var savedheight = $(self).height();
      $(this).data('event.editable', settings.event);
      if (!$.trim($(this).html())) {
	$(this).html(settings.placeholder);
      }
      $(this).bind(settings.event, function(e) {
	if (true === $(this).data('disabled.editable')) {
	  return;
	}
	if (self.editing) {
	  return;
	}
	if (false === onedit.apply(this, [settings, self])) {
	  return;
	}
	e.preventDefault();
	e.stopPropagation();
	if (settings.tooltip) {
	  $(self).removeAttr('title');
	}
	if (0 == $(self).width()) {
	  settings.width = savedwidth;
	  settings.height = savedheight;
	} else {
	  if (settings.width != 'none') {
	    settings.width = settings.autowidth ? $(self).width() : settings.width;
	  }
	  if (settings.height != 'none') {
	    settings.height = settings.autoheight ? $(self).height() : settings.height;
	  }
	}
	if ($(this).html().toLowerCase().replace(/(;|")/g, '') == settings.placeholder.toLowerCase().replace(/(;|")/g, '')) {
	  $(this).html('');
	}
	self.editing = true;
	self.revert = $(self).html();
	$(self).html('');
	var form = $('<form />');
	if (settings.cssclass) {
	  if ('inherit' == settings.cssclass) {
	    form.attr('class', $(self).attr('class'));
	  } else {
	    form.attr('class', settings.cssclass);
	  }
	}
	if (settings.style) {
	  if ('inherit' == settings.style) {
	    form.attr('style', $(self).attr('style'));
	    form.css('display', $(self).css('display'));
	  } else {
	    form.attr('style', settings.style);
	  }
	}
	var input = element.apply(form, [settings, self]);
	var input_content;
	if (settings.loadurl) {
	  var t = setTimeout(function() {
	    input.disabled = true;
	    content.apply(form, [settings.loadtext, settings, self]);
	  }, 100);
	  var loaddata = {};
	  loaddata[settings.id] = self.id;
	  if ($.isFunction(settings.loaddata)) {
	    $.extend(loaddata, settings.loaddata.apply(self, [self.revert, settings]));
	  } else {
	    $.extend(loaddata, settings.loaddata);
	  }
	  $.ajax({
	    type: settings.loadtype,
	    url: settings.loadurl,
	    data: loaddata,
	    async: false,
	    success: function(result) {
	      window.clearTimeout(t);
	      input_content = result;
	      input.disabled = false;
	    }
	  });
	} else if (settings.data) {
	  input_content = settings.data;
	  if ($.isFunction(settings.data)) {
	    input_content = settings.data.apply(self, [self.revert, settings]);
	  }
	} else {
	  input_content = self.revert;
	}
	content.apply(form, [input_content, settings, self]);
	input.attr('name', settings.name);
	buttons.apply(form, [settings, self]);
	$(self).append(form);
	plugin.apply(form, [settings, self]);
	$(':input:visible:enabled:first', form).focus();
	if (settings.select) {
	  input.select();
	}
	input.keydown(function(e) {
	  if (e.keyCode == 27) {
	    e.preventDefault();
	    reset.apply(form, [settings, self]);
	  }
	});
	input.keydown(function(e) {
	  if (e.keyCode == 13) {
	    e.preventDefault();
	    form.submit();
	  }
	});
	var t;
	if ('cancel' == settings.onblur) {
	  input.blur(function(e) {
	    t = setTimeout(function() {
	      reset.apply(form, [settings, self]);
	    }, 500);
	  });
	} else if ('submit' == settings.onblur) {
	  input.blur(function(e) {
	    t = setTimeout(function() {
	      form.submit();
	    }, 200);
	  });
	} else if ($.isFunction(settings.onblur)) {
	  input.blur(function(e) {
	    settings.onblur.apply(self, [input.val(), settings]);
	  });
	} else {
	  input.blur(function(e) {
	  });
	}
	form.submit(function(e) {
	  if (t) {
	    clearTimeout(t);
	  }
	  e.preventDefault();
	  if (false !== onsubmit.apply(form, [settings, self])) {
	    if (false !== submit.apply(form, [settings, self])) {
	      if ($.isFunction(settings.target)) {
		var str = settings.target.apply(self, [input.val(), settings]);
		$(self).html(str);
		self.editing = false;
		callback.apply(self, [self.innerHTML, settings]);
		if (!$.trim($(self).html())) {
		  $(self).html(settings.placeholder);
		}
	      } else {
		var submitdata = {};
		submitdata[settings.name] = input.val();
		submitdata[settings.id] = self.id;
		if ($.isFunction(settings.submitdata)) {
		  $.extend(submitdata, settings.submitdata.apply(self, [self.revert, settings]));
		} else {
		  $.extend(submitdata, settings.submitdata);
		}
		if ('PUT' == settings.method) {
		  submitdata['_method'] = 'put';
		}
		$(self).html(settings.indicator);
		var ajaxoptions = {
		  type: 'POST',
		  data: submitdata,
		  dataType: 'html',
		  url: settings.target,
		  success: function(result, status) {
		    if (ajaxoptions.dataType == 'html') {
		      $(self).html(result);
		    }
		    self.editing = false;
		    callback.apply(self, [result, settings]);
		    if (!$.trim($(self).html())) {
		      $(self).html(settings.placeholder);
		    }
		  },
		  error: function(xhr, status, error) {
		    onerror.apply(form, [settings, self, xhr]);
		  }
		};
		$.extend(ajaxoptions, settings.ajaxoptions);
		$.ajax(ajaxoptions);
	      }
	    }
	  }
	  $(self).attr('title', settings.tooltip);
	  return false;
	});
      });
      this.reset = function(form) {
	if (this.editing) {
	  if (false !== onreset.apply(form, [settings, self])) {
	    $(self).html(self.revert);
	    self.editing = false;
	    if (!$.trim($(self).html())) {
	      $(self).html(settings.placeholder);
	    }
	    if (settings.tooltip) {
	      $(self).attr('title', settings.tooltip);
	    }
	  }
	}
      };
    });
  };
  $.editable = {
    types: {
      defaults: {
	element: function(settings, original) {
	  var input = $('<input type="hidden"></input>');
	  $(this).append(input);
	  return (input);
	},
	content: function(string, settings, original) {
	  $(':input:first', this).val(string);
	},
	reset: function(settings, original) {
	  original.reset(this);
	},
	buttons: function(settings, original) {
	  var form = this;
	  if (settings.submit) {
	    if (settings.submit.match(/>$/)) {
	      var submit = $(settings.submit).click(function() {
		if (submit.attr("type") != "submit") {
		  form.submit();
		}
	      });
	    } else {
	      var submit = $('<button type="submit" />');
	      submit.html(settings.submit);
	    }
	    $(this).append(submit);
	  }
	  if (settings.cancel) {
	    if (settings.cancel.match(/>$/)) {
	      var cancel = $(settings.cancel);
	    } else {
	      var cancel = $('<button type="cancel" />');
	      cancel.html(settings.cancel);
	    }
	    $(this).append(cancel);
	    $(cancel).click(function(event) {
	      if ($.isFunction($.editable.types[settings.type].reset)) {
		var reset = $.editable.types[settings.type].reset;
	      } else {
		var reset = $.editable.types['defaults'].reset;
	      }
	      reset.apply(form, [settings, original]);
	      return false;
	    });
	  }
	}
      },
      text: {
	element: function(settings, original) {
	  var input = $('<input />');
	  if (settings.width != 'none') {
	    input.width(settings.width);
	  }
	  if (settings.height != 'none') {
	    input.height(settings.height);
	  }
	  input.attr('autocomplete', 'off');
	  $(this).append(input);
	  return (input);
	}
      },
      textarea: {
	element: function(settings, original) {
	  var textarea = $('<textarea />');
	  if (settings.rows) {
	    textarea.attr('rows', settings.rows);
	  } else if (settings.height != "none") {
	    textarea.height(settings.height);
	  }
	  if (settings.cols) {
	    textarea.attr('cols', settings.cols);
	  } else if (settings.width != "none") {
	    textarea.width(settings.width);
	  }
	  $(this).append(textarea);
	  return (textarea);
	}
      },
      select: {
	element: function(settings, original) {
	  var select = $('<select />');
	  $(this).append(select);
	  return (select);
	},
	content: function(data, settings, original) {
	  if (String == data.constructor) {
	    eval('var json = ' + data);
	  } else {
	    var json = data;
	  }
	  for (var key in json) {
	    if (!json.hasOwnProperty(key)) {
	      continue;
	    }
	    if ('selected' == key) {
	      continue;
	    }
	    var option = $('<option />').val(key).append(json[key]);
	    $('select', this).append(option);
	  }
	  $('select', this).children().each(function() {
	    if ($(this).val() == json['selected'] || $(this).text() == $.trim(original.revert)) {
	      $(this).attr('selected', 'selected');
	    }
	  });
	}
      }
    },
    addInputType: function(name, input) {
      $.editable.types[name] = input;
    }
  };
  $.fn.editable.defaults = {
    name: 'value',
    id: 'id',
    type: 'text',
    width: 'auto',
    height: 'auto',
    event: 'click.editable',
    onblur: 'cancel',
    loadtype: 'GET',
    loadtext: 'Loading...',
    placeholder: 'Click to edit',
    loaddata: {},
    submitdata: {},
    ajaxoptions: {}
  };
})(jQuery);





/**
 * Timeago is a jQuery plugin that makes it easy to support automatically
 * updating fuzzy timestamps (e.g. "4 minutes ago" or "about 1 day ago").
 *
 * @name timeago
 * @version 1.4.1
 * @requires jQuery v1.2.3+
 * @author Ryan McGeary
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 *
 * For usage and examples, visit:
 * http://timeago.yarp.com/
 *
 * Copyright (c) 2008-2013, Ryan McGeary (ryan -[at]- mcgeary [*dot*] org)
 */

(function(factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else {
    // Browser globals
    factory(jQuery);
  }
}(function($) {
  $.timeago = function(timestamp) {
    if (timestamp instanceof Date) {
      return inWords(timestamp);
    } else if (typeof timestamp === "string") {
      return inWords($.timeago.parse(timestamp));
    } else if (typeof timestamp === "number") {
      return inWords(new Date(timestamp));
    } else {
      return inWords($.timeago.datetime(timestamp));
    }
  };
  var $t = $.timeago;

  $.extend($.timeago, {
    settings: {
      refreshMillis: 60000,
      allowPast: true,
      allowFuture: false,
      localeTitle: false,
      cutoff: 0,
      strings: {
	prefixAgo: null,
	prefixFromNow: null,
	suffixAgo: "ago",
	suffixFromNow: "from now",
	inPast: 'any moment now',
	seconds: "less than a minute",
	minute: "about a minute",
	minutes: "%d minutes",
	hour: "about an hour",
	hours: "about %d hours",
	day: "a day",
	days: "%d days",
	month: "about a month",
	months: "%d months",
	year: "about a year",
	years: "%d years",
	wordSeparator: " ",
	numbers: []
      }
    },
    inWords: function(distanceMillis) {
      if (!this.settings.allowPast && !this.settings.allowFuture) {
	throw 'timeago allowPast and allowFuture settings can not both be set to false.';
      }

      var $l = this.settings.strings;
      var prefix = $l.prefixAgo;
      var suffix = $l.suffixAgo;
      if (this.settings.allowFuture) {
	if (distanceMillis < 0) {
	  prefix = $l.prefixFromNow;
	  suffix = $l.suffixFromNow;
	}
      }

      if (!this.settings.allowPast && distanceMillis >= 0) {
	return this.settings.strings.inPast;
      }

      var seconds = Math.abs(distanceMillis) / 1000;
      var minutes = seconds / 60;
      var hours = minutes / 60;
      var days = hours / 24;
      var years = days / 365;

      function substitute(stringOrFunction, number) {
	var string = $.isFunction(stringOrFunction) ? stringOrFunction(number, distanceMillis) : stringOrFunction;
	var value = ($l.numbers && $l.numbers[number]) || number;
	return string.replace(/%d/i, value);
      }

      var words = seconds < 45 && substitute($l.seconds, Math.round(seconds)) ||
	seconds < 90 && substitute($l.minute, 1) ||
	minutes < 45 && substitute($l.minutes, Math.round(minutes)) ||
	minutes < 90 && substitute($l.hour, 1) ||
	hours < 24 && substitute($l.hours, Math.round(hours)) ||
	hours < 42 && substitute($l.day, 1) ||
	days < 30 && substitute($l.days, Math.round(days)) ||
	days < 45 && substitute($l.month, 1) ||
	days < 365 && substitute($l.months, Math.round(days / 30)) ||
	years < 1.5 && substitute($l.year, 1) ||
	substitute($l.years, Math.round(years));

      var separator = $l.wordSeparator || "";
      if ($l.wordSeparator === undefined) {
	separator = " ";
      }
      return $.trim([prefix, words, suffix].join(separator));
    },
    parse: function(iso8601) {
      var s = $.trim(iso8601);
      s = s.replace(/\.\d+/, ""); // remove milliseconds
      s = s.replace(/-/, "/").replace(/-/, "/");
      s = s.replace(/T/, " ").replace(/Z/, " UTC");
      s = s.replace(/([\+\-]\d\d)\:?(\d\d)/, " $1$2"); // -04:00 -> -0400
      s = s.replace(/([\+\-]\d\d)$/, " $100"); // +09 -> +0900
      return new Date(s);
    },
    datetime: function(elem) {
      var iso8601 = $t.isTime(elem) ? $(elem).attr("datetime") : $(elem).attr("title");
      return $t.parse(iso8601);
    },
    isTime: function(elem) {
      // jQuery's `is()` doesn't play well with HTML5 in IE
      return $(elem).get(0).tagName.toLowerCase() === "time"; // $(elem).is("time");
    }
  });

  // functions that can be called via $(el).timeago('action')
  // init is default when no action is given
  // functions are called with context of a single element
  var functions = {
    init: function() {
      var refresh_el = $.proxy(refresh, this);
      refresh_el();
      var $s = $t.settings;
      if ($s.refreshMillis > 0) {
	this._timeagoInterval = setInterval(refresh_el, $s.refreshMillis);
      }
    },
    update: function(time) {
      var parsedTime = $t.parse(time);
      $(this).data('timeago', {
	datetime: parsedTime
      });
      if ($t.settings.localeTitle)
	$(this).attr("title", parsedTime.toLocaleString());
      refresh.apply(this);
    },
    updateFromDOM: function() {
      $(this).data('timeago', {
	datetime: $t.parse($t.isTime(this) ? $(this).attr("datetime") : $(this).attr("title"))
      });
      refresh.apply(this);
    },
    dispose: function() {
      if (this._timeagoInterval) {
	window.clearInterval(this._timeagoInterval);
	this._timeagoInterval = null;
      }
    }
  };

  $.fn.timeago = function(action, options) {
    var fn = action ? functions[action] : functions.init;
    if (!fn) {
      throw new Error("Unknown function name '" + action + "' for timeago");
    }
    // each over objects here and call the requested function
    this.each(function() {
      fn.call(this, options);
    });
    return this;
  };

  function refresh() {
    var data = prepareData(this);
    var $s = $t.settings;

    if (!isNaN(data.datetime)) {
      if ($s.cutoff == 0 || Math.abs(distance(data.datetime)) < $s.cutoff) {
	$(this).text(inWords(data.datetime));
      }
    }
    return this;
  }

  function prepareData(element) {
    element = $(element);
    if (!element.data("timeago")) {
      element.data("timeago", {
	datetime: $t.datetime(element)
      });
      var text = $.trim(element.text());
      if ($t.settings.localeTitle) {
	element.attr("title", element.data('timeago').datetime.toLocaleString());
      } else if (text.length > 0 && !($t.isTime(element) && element.attr("title"))) {
	element.attr("title", text);
      }
    }
    return element.data("timeago");
  }

  function inWords(date) {
    return $t.inWords(distance(date));
  }

  function distance(date) {
    return (new Date().getTime() - date.getTime());
  }

  // fix for IE6 suckage
  document.createElement("abbr");

  document.createElement("time");
}));