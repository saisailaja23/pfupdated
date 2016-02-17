/*
Copyright (c) 2009, www.redips.net  All rights reserved.
Code licensed under the BSD License: http://www.redips.net/license/

http://www.redips.net/javascript/dialog-box/
version 1.3.0
Aug 17, 2010.
*/

/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false */



/* enable strict mode */
"use strict";

// create REDIPS namespace
var REDIPS = {};



REDIPS.dialog = (function () {
		// function declaration
	var	init,
		show,
		hide,
		image_tag,			// prepare image tags
		position,
		visibility,
		fade,
		input_html,			// function prepares input tag HTML 
		
		// properties
		op_high = 60,		// highest opacity level
		op_low = 0,			// lowest opacity level (should be the same as initial opacity in the CSS)
		fade_speed = 18,	// set default speed - 18ms
		
		// private parameters
		shade,				// shade div (object reference)
		dialog_box,			// dialog box (object reference)
		dialog_width = 0,	// initialize dialog width
		dialog_height = 0,	// initialize dialog height
		function_call,		// name of function to call after clicking on button
		function_param;		// optional function parameter


	// initialization
	init = function () {
		// create dialog box
		dialog_box = document.createElement('div');
		dialog_box.setAttribute('id', 'dialog');
		// create shade div
		shade = document.createElement('div');
		shade.setAttribute('id', 'shade');
		// append dialog box and shade to the page body
		var body = document.getElementsByTagName('body')[0];
		body.appendChild(shade);
		body.appendChild(dialog_box);
		// define onscroll & onresize event handler for FF, Chrome		
		if (window.addEventListener) {
			window.addEventListener('scroll', position, false);
			window.addEventListener('resize', position, false);
		}
		// IE
		else if (window.attachEvent) {
			window.attachEvent('onscroll', position);
			window.attachEvent('onresize', position);
		}
		// other (hopefully this will not be needed)
		else {
			window.onscroll = position;
			window.onresize = position;
		}
	};
	
	
	// show dialog box
	show = function (width, height, text, button1, button2) {
		var input1 = '',		// define input1 button
			input2 = '',		// define input2 (optional) button
			param  = '',		// optional function parameter
			img_extensions,		// needed for image search
			div_img = '',		// prepared DIV with images
			div_text = '',		// text wrapped with DIV
			img_text = '';		// text under image
		//  set button1 default value
		if (button1 === undefined) {
			button1 = 'Close';
		}
		// set dialog width, height and calculate central position
		dialog_width  = width;
		dialog_height = height;
		position();
		// if text ends with jpg, jpeg, gif or png, then prepare img tag
		img_extensions = /(\.jpg|\.jpeg|\.gif|\.png)$/i;
		if (img_extensions.test(text)) {
			// text can precede jpg, jpeg, gif or png image, so search for separator
			img_text = text.split('|');
			// separator doesn't exist, display only image without text
			if (img_text.length === 1) {
				div_img = image_tag(img_text[0]);
			}
			// otherwise, prepare image and text DIV
			else {
				div_img = image_tag(img_text[1]);
				div_text = '<DIV class="text">' + img_text[0] + '</DIV>';
			}
		}
		// else prepare text within DIV
		else {
			div_text = '<DIV class="text">' + text + '</DIV>';
		}
		// prepare input1 HTML
		input1 = input_html(button1);
		// prepare optional button2 
		if (button2 !== undefined) {
			// prepare input2 HTML
			input2  = input_html(button2);
		}
		// set HTML for dialog box - use table to vertical align content inside
		// dialog box (this should work in all browsers)
		dialog_box.innerHTML = '<TABLE><TR><TD valign="center" height="' + height + '" width="' + width + '">' + 
								 div_img +
								 div_text +
								 '<!--DIV>' + input1 + input2 + '</DIV-->' +
								 '</TD></TR></TABLE>';
		// show shade and dialog box
		shade.style.display = dialog_box.style.display = 'block';
		shade.style.zIndex = dialog_box.style.zIndex = 2000;
		// hide dropdown menus, iframes & flash
		visibility('hidden');
		// show shaded div slowly
		fade(REDIPS.dialog.op_low, 10);
	};
	
	
	// hide dialog box and shade
	hide = function (fnc, param) {
		// set function call
		function_call = fnc;
		// set function parameter
		function_param = param;
		// start fade out
		fade(REDIPS.dialog.op_high, -20);
		// hide dialog box
		dialog_box.style.display = 'none';
		
		// show dropdown menu, iframe & flash
		visibility('visible');
	};


	// function prepares input tag HTML based on "Yes|button2|hello" syntax
	input_html = function (button) {
		var param,	// optional parameter for function hide
			html;	// input tag HTML
		// split button values
		button = button.split('|');
		// define parameter (this is last value in composed string)
		param = button[2];
		// prepare optional function parameter
		if (param !== undefined) {
			param = '\',\'' + param;
		}
		else {
			param = '';
		}
		// prepare input tag HTML
		html = '<INPUT type="button" onclick="REDIPS.dialog.hide(\'' + button[1] + param + '\');" value="' + button[0] + '">';
		// return result
		return html;
	};


	// prepare img tags (one or more) 
	image_tag = function (image) {
		var img,	// prepared img HTML
			images,	// array containing separated images
			i;		// variable used in loop
		// split input image parameter separated with ,
		images = image.split(',');
		// array contain only one image - simple
		if (images.length === 1) {
			img = '<IMG src="./images/' + images[0] + '" height="' + (dialog_height - 40) + '"/>';
		}
		// otherwise run loop for more images (images are placed in a table row)
		else {
			img = '<DIV class="image" style="width:' + (dialog_width - 8) + 'px"><TABLE><TR>';
			for (i = 0; i < images.length; i++) {
				img += '<TD><IMG src="./images/' + images[i] + '" height="' + (dialog_height - 40) + '"/></TD>';
			}
			img += '</TR></TABLE></DIV>';
		}
		// return prepared img HTML
		return img; 
	};


	// function sets dialog position to the center and maximize shade div
	position = function () {
		// define local variables
		var window_width, window_height, scrollX, scrollY;
		// Non-IE (Netscape compliant)
		if (typeof(window.innerWidth) === 'number') {
			window_width  = window.innerWidth;
			window_height = window.innerHeight;
			scrollX = window.pageXOffset;
			scrollY = window.pageYOffset;
		}
		// IE 6 standards compliant mode
		else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
			window_width  = document.documentElement.clientWidth;
			window_height = document.documentElement.clientHeight;
			scrollX = document.documentElement.scrollLeft;
			scrollY = document.documentElement.scrollTop;
			// IE hack because #shade{width:100%;height:100%;} will not work for IE if body{height:100%} isn't set also ?!
			shade.style.width   = window_width  + 'px';
			shade.style.height  = window_height + 'px';
		}
		// DOM compliant
		else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
			window_width  = document.body.clientWidth;
			window_height = document.body.clientHeight;
			scrollX = document.body.scrollLeft;
			scrollY = document.body.scrollTop;
		}
		// place dialog box to the center
		dialog_box.style.left = (scrollX + ((window_width  - dialog_width)  / 2)) + 'px';
		dialog_box.style.top  = (scrollY + ((window_height - dialog_height) / 2)) + 'px';
		// set shade offset
		shade.style.top  = scrollY + 'px';
		shade.style.left = scrollX + 'px';
	};
	
	
	// show/hide dropdown menu, iframe and flash objects (work-around for dropdown menu problem in IE6)
	visibility = function (p) {
		var obj = [],	// define tag array
			x, y;		// variables used in local loops
		obj[0] = document.getElementsByTagName('select');
		obj[1] = document.getElementsByTagName('iframe');
		obj[2] = document.getElementsByTagName('object');
		// loop through fetched elements
		for (x = 0; x < obj.length; x++) {
			for (y = 0; y < obj[x].length; y++) {
				obj[x][y].style.visibility = p;
			}
		}
	};
	
	
	// shade fade in / fade out
	fade = function (opacity, step) {
		// set opacity for FF and IE
		shade.style.opacity = opacity / 100;
		shade.style.filter  = 'alpha(opacity=' + opacity + ')';
		// update opacity level
		opacity += step;
		// recursive call if opacity is between 'low' and 'high' values
		if (REDIPS.dialog.op_low <= opacity && opacity <= REDIPS.dialog.op_high) {
			setTimeout(
				function () {
					fade(opacity, step);
				}, REDIPS.dialog.fade_speed); // fade speed is public parameter
		}
		// hide shade div when fade out ends and make function call 
		else if (REDIPS.dialog.op_low > opacity) {
			shade.style.display = 'none';
			if (function_call !== 'undefined') {
				// call function after button is clicked because functions are defined in global scope
				window[function_call](function_param);
			}
		}
	};

	
	return {
		// public properties
		op_high			: op_high,		// highest opacity level
		op_low			: op_low,		// lowest opacity level (should be the same as initial opacity in the CSS)
		fade_speed		: fade_speed,	// fade speed (default is 18ms)
        
		// public methods
		init			: init,			// initialization
		show			: show,			// show dialog box
		hide			: hide			// hide dialog box
	};
			
}());
