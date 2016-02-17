/***************************************************************************
* 
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*      
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY. 
* To be able to use this product for another domain names you have to order another copy of this product (license).
* 
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
* 
* This notice may not be removed from the source code.
* 
***************************************************************************/

$('#tabs').ready(function(){
	$("#tabs").tabs({
			   show: function(event, ui) {
				 if (2==1 & $(ui.panel).attr('id') == 'text_tab' && ((window.tinyMCE && window.tinyMCE.activeEditor) || (window.tinyMCE && window.tinyMCE.activeEditor == null)))
                                     {
                                         
                                           tinyMCE.settings =  {
                                                                        //[START DeeEmm TinyBrowser MOD]
                                                                       // file_browser_callback : "tinyBrowser",
                                                                        //[END DeeEmm TinyBrowser MOD]
                                                            convert_urls : false,
                                                                        mode : "textareas",
                                                                        theme : "advanced",
                                                                //[START DeeEmm TinyBrowser MOD]
                                                                        file_browser_callback : "imageManager",
                                                                        //[END DeeEmm TinyBrowser MOD]
                                                                        editor_selector : /(group_edit_html|story_edit_area|classfiedsTextArea|blogText|comment_textarea|form_input_html)/,

                                                                        plugins : "paste,imagemanager,filemanager,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,directionality,fullscreen",

                                                                        theme_advanced_buttons1_add : "fontselect,fontsizeselect",
                                                                        theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,image,separator,search,replace,separator",
                                                                        theme_advanced_buttons2_add : "separator,insertdate,inserttime,separator,forecolor,backcolor",
                                                                        theme_advanced_buttons3_add : "emotions,insertfile,insertimage,fullscreen",
                                                                        theme_advanced_toolbar_location : "top",
                                                                        theme_advanced_toolbar_align : "left",
                                                                        theme_advanced_statusbar_location : "bottom",

                                                                        plugi2n_insertdate_dateFormat : "%Y-%m-%d",
                                                                        plugi2n_insertdate_timeFormat : "%H:%M:%S",
                                                                        theme_advanced_resizing : true,
                                                            theme_advanced_resize_horizontal : false,

                                                            entity_encoding : "named",

                                                            paste_use_dialog : false,
                                                                        paste_auto_cleanup_on_paste : true,
                                                                        paste_convert_headers_to_strong : false,
                                                                        paste_strip_class_attributes : "all",
                                                                        paste_remove_spans : false,
                                                                        paste_remove_styles : false
                                                        };
                                            tinyMCE.execCommand('mceAddControl', true, 'aqb_text_block_body');
                                         
                                     }
					
				 else if (1==1)
                                     {
alert('alsdj');
                                            tinyMCE.settings =  {
                                                                        //[START DeeEmm TinyBrowser MOD]
                                                                       // file_browser_callback : "tinyBrowser",
                                                                        //[END DeeEmm TinyBrowser MOD]
                                                            convert_urls : false,
                                                                        mode : "textareas",
                                                                        theme : "advanced",
                                                                //[START DeeEmm TinyBrowser MOD]
                                                                        file_browser_callback : "imageManager",
                                                                        //[END DeeEmm TinyBrowser MOD]
                                                                        editor_selector : /(group_edit_html|story_edit_area|classfiedsTextArea|blogText|comment_textarea|form_input_html)/,

                                                                        plugins : "imagemanager,filemanager,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,directionality,fullscreen",

                                                                        theme_advanced_buttons1_add : "fontselect,fontsizeselect",
                                                                        theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,image,separator,search,replace,separator",
                                                                        theme_advanced_buttons2_add : "separator,insertdate,inserttime,separator,forecolor,backcolor",
                                                                        theme_advanced_buttons3_add : "emotions,insertfile,insertimage,fullscreen",
                                                                        theme_advanced_toolbar_location : "top",
                                                                        theme_advanced_toolbar_align : "left",
                                                                        theme_advanced_statusbar_location : "bottom",

                                                                        plugi2n_insertdate_dateFormat : "%Y-%m-%d",
                                                                        plugi2n_insertdate_timeFormat : "%H:%M:%S",
                                                                        theme_advanced_resizing : true,
                                                            theme_advanced_resize_horizontal : false,

                                                            entity_encoding : "named",

                                                            paste_use_dialog : false,
                                                                        paste_auto_cleanup_on_paste : true,
                                                                        paste_convert_headers_to_strong : false,
                                                                        paste_strip_class_attributes : "all",
                                                                        paste_remove_spans : false,
                                                                        paste_remove_styles : false
                                                         };
             
                                                tinyMCE.execCommand('mceAddControl', true , 'aqb_text_block_body');
                                        
                                     }
					
			   },
	});
z});

