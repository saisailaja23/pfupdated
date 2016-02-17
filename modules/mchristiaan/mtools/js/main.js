function hideit(){
				  var o = document.getElementById("hidethisone");
				  o.style.display = "none";
				}
window.onload = function(){
  setTimeout("hideit()",5000); // 5 seconds after user (re)load the page
};

if (window.attachEvent)
	window.attachEvent( "onload", InitTiny );
else
	window.addEventListener( "load", InitTiny, false);

function InitTiny() {
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		convert_urls : false,
		remove_linebreaks : false,
		mode : "specific_textareas",
		theme : "advanced",

		editor_selector : /(form_input_textarea form_input_html)/,
		
		plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
		
		// Theme options

		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",

		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen",

		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,cite,styleprops,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",

		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",

		plugi2n_insertdate_dateFormat : "%Y-%m-%d",
		plugi2n_insertdate_timeFormat : "%H:%M:%S",			
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,

		paste_use_dialog : false,
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false
	});
}