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
/*window.onerror = new Function('return true;');*/
/* (function($) {
		    if ($.ui){
				var a = $.ui.mouse._mouseMove;
			    $.ui.mouse._mouseMove = function(b) {
			        if ($.browser.msie && document.documentMode >= 9) {
			            b.button = 1
			        };
			        a.apply(this, [b]);
			    }
			}
		} (jQuery));
 */
function AqbComposer(oParams){
	this.url = oParams.url == undefined ? '' : oParams.url;
	this.updating = false;
	this.owner = oParams.owner == undefined ? '' : oParams.owner;
	this.admin = oParams.admin == undefined ? 0 : oParams.admin;
	this.change_privacy = oParams.change_privacy == undefined ? 0 : oParams.change_privacy;
	this.viewer = oParams.viewer == undefined ? '' : oParams.viewer;
	this.menu_items = oParams.menu_items == undefined ? '' : oParams.menu_items;
	this.custom_prefix = oParams.custom_prefix == undefined ? '' : oParams.custom_prefix;
}

AqbComposer.prototype.serialize = function() {
  var $this = this;
  var aBlocks = new Array();
  var pCustomBlock = eval('/' + this.custom_prefix + '/');
  var sId = '';
  var sColumn = '';
  
  this.updating = true;
  $('.column').each(function()
  {
	  $('> div.groupItem', this).each(function(){
          	if (pCustomBlock.test($(this).attr('id')))
				sId = $(this).attr('id').replace('page_block_aqb_','');
			else sId = 's_' + $(this).attr('id').replace('page_block_','');
			sColumn += sId + '|';
	   });
		
		aBlocks.push(sColumn);
		sColumn = '';
  })
  this.updatePositions(aBlocks.toString());
  this.updating = false;
};

AqbComposer.prototype.updatePositions = function(sStrings){
	var oDate = new Date();
	$.post(this.url + 'reorder/' + this.owner,{_t:oDate.getTime(),data:sStrings});
}

AqbComposer.prototype.updatePositions = function(sStrings){
	var oDate = new Date();
	$.post(this.url + 'reorder/' + this.owner,{_t:oDate.getTime(),data:sStrings});
}

AqbComposer.prototype.wrap = function(e, sId){
	var oDate = new Date();
	var $block = $('#page_block_' + sId + ' .boxContent');
			
	if($block.is('.aqb_clped')) 
	 {
	  $(e).text(this.menu_items.rollup);
	  $block.show(300);
	  $block.removeClass('aqb_clped');	
	 }	
	else 
	{  
	  $(e).text(this.menu_items.unwrap);
	  $block.hide(300);
	  $block.addClass('aqb_clped');
	}
	
	$.post(this.url + 'collapse/' + this.owner + '/' + sId, {_t:oDate.getTime()});	
}

AqbComposer.prototype.unwrapBlock = function(e, sId){
	var oDate = new Date();
	var $block = $('#page_block_' + sId + ' .boxContent');
			
	if($block.is('.aqb_clped')) 
	 {
	  $('> img', e).attr('src', 'modules/aqb/pcomposer/templates/base/images/icons/unwrapped.png');
	  $block.show(300);
	  $block.removeClass('aqb_clped');	
	 }	
	else 
	{  
	  $('> img', e).attr('src', 'modules/aqb/pcomposer/templates/base/images/icons/wrapped.png');
	  $block.hide(300);
	  $block.addClass('aqb_clped');
	}
}

AqbComposer.prototype.shareBlock = function(e, sId){
	var oDate = new Date();
	var sMenuItem = $('#aqb_bMenu' + sId);
			
	if(sMenuItem.is('.aqb_my') && sMenuItem.is('.aqb_shared')) 
	{
	  $(e).text(this.menu_items.share);
	  sMenuItem.removeClass('aqb_shared');
	  sMenuItem.addClass('aqb_unshared');	  
	 }	
	else if(sMenuItem.is('.aqb_my') && sMenuItem.is('.aqb_unshared'))  
	{  
	  $(e).text(this.menu_items.unshare);
	  sMenuItem.removeClass('aqb_unshared');
	  sMenuItem.addClass('aqb_shared');	  
	 }
	
	$.post(this.url + 'share/' + this.owner + '/' + sId, {_t:oDate.getTime()});	
}

AqbComposer.prototype.removeBlock = function(sId){
	var $this = this;
	$('#aqb_pc_block_menu').remove();
	$block = $('#page_block_' + sId);
	$block.animate({
                     opacity: 0    
                   },function () {
                       $(this).slideUp(function () {
                         $(this).remove();
						 
						 var oDate = new Date();
						 $.post($this.url + 'remove_block/' + $this.owner + '/' + sId, {_t:oDate.getTime()});
					});
                  });
}

AqbComposer.prototype.addLink = function(sFunc, sTitle){
  return '<li><a href="javascript:void(0)" onclick="javascript:' + sFunc + ';">' + sTitle + '</a></li>';
}

AqbComposer.prototype.submitBlock = function(sType, sWornMessage){
   var $this = this;
   try{
	   var  title = $('#aqb_pc_' + sType + '_block_title').val();
	   var  body = '';
	   
	   if (sType == 'text') body = tinyMCE.get('aqb_text_block_body').getContent();
	   else  body = $('#aqb_pc_' + sType + '_block_body').val();

	   if (title.length == 0 || body.length == 0) 
	   {
		 alert(sWornMessage);
		 return; 
	   }
	   
	   $('#aqb_pc_' + sType  + '_save').attr('disabled','disabled');
	   
	   var oDate = new Date();
	   $.post(this.url + 'save_block/' + this.owner, {_t:oDate.getTime(), type: sType, body: body, title: title, block_id: $('#block_id').val()}, 

	   function(oData){
		alert(oData.message);
		if (parseInt(oData.id) > 0 )
		{
			$.post($this.url + 'get_block/' + oData.id + '/' + 'c', {_t:oDate.getTime(), type: sType},
				function(data){
				//  if (sType == 'html') { 
				//	window.reload();
				//    return;
				//  }
				  
				  if ($('#page_block_' + $this.custom_prefix + oData.id).length > 0) $('#page_block_' + $this.custom_prefix + oData.id).replaceWith(data);
			      else $('#page_column_1').prepend(data);
				  
				//  $('#page_block_' + $this.custom_prefix + oData.id).effect('pulsate',{}, 400,function(){});

				  $this.initStyles('#page_block_' + $this.custom_prefix + oData.id);
				  $this.makeSortable();

				  $('div.RSSAggrCont' ).dolRSSFeed();
				  $('#aqb_popup').dolPopupHide({});
				  
				},'html'); 
	 	}
		if (parseInt(oData.code) == 3 && $('#page_block_' + $this.custom_prefix + $('#block_id').val()).length > 0) $('#page_block_' + $this.custom_prefix + $('#block_id').val()).remove();
		if (parseInt(oData.code) == 3 || parseInt(oData.code) == 1) $('#aqb_popup').dolPopupHide({});
		
	   }, 'json');
	}
	catch(e){
		alert(e.toString());
	}	
}

AqbComposer.prototype.sendRemoveRequest = function(sId){
    var oDate = new Date();
	
	$.post(this.url + 'remove_request/' + this.owner + '/' + sId , {_t:oDate.getTime()}, 
    function(oData){
			alert(oData.message);
	        if (parseInt(oData.code) >= 0) 
			{
				$('#aqb_pc_block_menu').remove();
				$block = $('#page_block_' + sId);
				$block.animate({
			                     opacity: 0    
			                   },function () {
			                       $(this).slideUp(function () {
			                         $(this).remove();
								});
			                  });
			}			
	}, 'json');
} 		

AqbComposer.prototype.editBlock = function(sId){
    var $this = this;
	var oDate = new Date();
	
	$.post(this.url + 'check_block_edit/' + sId , {_t:oDate.getTime()}, 
    function(oData){
			if (parseInt(oData.code) == 0) 
			{
			    AqbPCMain.showPopup($this.url + 'edit_block/' + sId);
		   } else alert(oData.message);			
	}, 'json');
}

AqbComposer.prototype.blockMenu = function(sId){
	var $this = $('#aqb_bMenu' + sId), Menu = '', $block = $('#page_block_' + sId);
	
	var offset = $this.offset();
	var pCustomBlock = eval('/' + this.custom_prefix + '/');

	
	if($('.boxContent', $block).is('.aqb_clped')) 
		Menu += this.addLink("AqbPC.wrap(this,'" + sId +"')",this.menu_items.unwrap);
	else if (!$this.is('.aqb_cpb')) 
		Menu += this.addLink("AqbPC.wrap(this,'" + sId +"')",this.menu_items.rollup);
	
	
	if(!$this.is('.aqb_rmv')) Menu += this.addLink("AqbPC.removeBlock('" + sId +"')", this.menu_items.remove);	
	
	if (this.change_privacy == 1 || this.admin == 1)
	{
		if (!pCustomBlock.test(sId))
			Menu += this.addLink("ps_page_toggle('profile', '" + this.owner + "','" + sId + "');",this.menu_items.change_privacy);
		else
			Menu += this.addLink("AqbPC.changePrivacy('" + sId + "');", this.menu_items.change_privacy);
	}	
	
	if (pCustomBlock.test(sId) && $this.is('.aqb_my')) 
	{
		Menu += this.addLink("AqbPC.editBlock('" + sId + "')", this.menu_items.edit);
		
	  if ($this.is('.aqb_shared'))	
		  Menu += this.addLink("AqbPC.shareBlock(this,'" + sId + "')", this.menu_items.unshare);
	  else
	  if ($this.is('.aqb_unshared'))
		  Menu += this.addLink("AqbPC.shareBlock(this,'" + sId + "')", this.menu_items.share);
	   
	  // Menu += this.addLink("AqbPC.sendRemoveRequest('" + sId + "')", this.menu_items.remove_request);
	}
        //
        // following code added by Joseph to bring the menu of standared block
        // similar to AQB Block
        //alert(sId);
        
        if(sId==611)// Birth Parent Block PFblockstd_DearBirthParent -
            {
                Menu += this.addLink("tinymice4_init('PFblockstd_DearBirthParent');document.getElementById('PFblockstd_DearBirthParentlight').style.display='block';document.getElementById('PFblockstd_DearBirthParentfade').style.display='block';document.getElementById('PFblockstd_DearBirthParent_ifr').style.width='100%';document.getElementById('PFblockstd_DearBirthParent_tbl').style.width='100%'", this.menu_items.edit);
            // Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_DearBirthParent');document.getElementById('PFblockstd_DearBirthParentlight').style.display='block';document.getElementById('PFblockstd_DearBirthParentfade').style.display='block';document.getElementById('PFblockstd_DearBirthParent_ifr').style.width='100%';document.getElementById('PFblockstd_DearBirthParent_tbl').style.width='100%'", this.menu_items.edit);
            }
           if(sId==651)// Second Parent About Info
            {
                Menu += this.addLink("tinymice4_init('PFblockstd_description22');document.getElementById('PFblockstd_description22light').style.display='block';document.getElementById('PFblockstd_description22fade').style.display='block';document.getElementById('PFblockstd_description22_ifr').style.width='100%';document.getElementById('PFblockstd_description22_tbl').style.width='100%'", this.menu_items.edit);
            // Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_description22');document.getElementById('PFblockstd_description22light').style.display='block';document.getElementById('PFblockstd_description22fade').style.display='block';document.getElementById('PFblockstd_description22_ifr').style.width='100%';document.getElementById('PFblockstd_description22_tbl').style.width='100%'", this.menu_items.edit);
            }
            if(sId==623)// Our Home
            {
                Menu += this.addLink("tinymice4_init('PFblockstd_About_our_home');document.getElementById('PFblockstd_About_our_homelight').style.display='block';document.getElementById('PFblockstd_About_our_homefade').style.display='block';document.getElementById('PFblockstd_About_our_home_ifr').style.width='100%';document.getElementById('PFblockstd_About_our_home_tbl').style.width='100%'", this.menu_items.edit);
            //Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_About_our_home');document.getElementById('PFblockstd_About_our_homelight').style.display='block';document.getElementById('PFblockstd_About_our_homefade').style.display='block';document.getElementById('PFblockstd_About_our_home_ifr').style.width='100%';document.getElementById('PFblockstd_About_our_home_tbl').style.width='100%'", this.menu_items.edit);
            }
            if(sId==655)// Hobbies of Second Parent
            {
                Menu += this.addLink("tinymice4_init('PFblockstd_Hobbies2');document.getElementById('PFblockstd_Hobbies2light').style.display='block';document.getElementById('PFblockstd_Hobbies2fade').style.display='block';document.getElementById('PFblockstd_Hobbies2_ifr').style.width='100%';document.getElementById('PFblockstd_Hobbies2_tbl').style.width='100%'", this.menu_items.edit);
            //Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_Hobbies2');document.getElementById('PFblockstd_Hobbies2light').style.display='block';document.getElementById('PFblockstd_Hobbies2fade').style.display='block';document.getElementById('PFblockstd_Hobbies2_ifr').style.width='100%';document.getElementById('PFblockstd_Hobbies2_tbl').style.width='100%'", this.menu_items.edit);
            }
            if(sId==654)//Interest of Second Parent
            {
                Menu += this.addLink("tinymice4_init('PFblockstd_Interests2');document.getElementById('PFblockstd_Interests2light').style.display='block';document.getElementById('PFblockstd_Interests2fade').style.display='block';document.getElementById('PFblockstd_Interests2_ifr').style.width='100%';document.getElementById('PFblockstd_Interests2_tbl').style.width='100%'", this.menu_items.edit);
            //Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_Interests2');document.getElementById('PFblockstd_Interests2light').style.display='block';document.getElementById('PFblockstd_Interests2fade').style.display='block';document.getElementById('PFblockstd_Interests2_ifr').style.width='100%';document.getElementById('PFblockstd_Interests2_tbl').style.width='100%'", this.menu_items.edit);
            }
            if(sId==650)//Hobbies of First Parent
            {
                Menu += this.addLink("tinymice4_init('#PFblockstd_Hobbies1');document.getElementById('PFblockstd_Hobbies1light').style.display='block';document.getElementById('PFblockstd_Hobbies1fade').style.display='block';document.getElementById('PFblockstd_Hobbies1_ifr').style.width='100%';document.getElementById('PFblockstd_Hobbies1_tbl').style.width='100%'", this.menu_items.edit);
            //Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_Hobbies1');document.getElementById('PFblockstd_Hobbies1light').style.display='block';document.getElementById('PFblockstd_Hobbies1fade').style.display='block';document.getElementById('PFblockstd_Hobbies1_ifr').style.width='100%';document.getElementById('PFblockstd_Hobbies1_tbl').style.width='100%'", this.menu_items.edit);
            }
            if(sId==649)// Interest of First Parent
            {
                Menu += this.addLink("tinymice4_init('PFblockstd_Interests1');document.getElementById('PFblockstd_Interests1light').style.display='block';document.getElementById('PFblockstd_Interests1fade').style.display='block';document.getElementById('PFblockstd_Interests1_ifr').style.width='100%';document.getElementById('PFblockstd_Interests1_tbl').style.width='100%'", this.menu_items.edit);
            //Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_Interests1');document.getElementById('PFblockstd_Interests1light').style.display='block';document.getElementById('PFblockstd_Interests1fade').style.display='block';document.getElementById('PFblockstd_Interests1_ifr').style.width='100%';document.getElementById('PFblockstd_Interests1_tbl').style.width='100%'", this.menu_items.edit);
            }
            if(sId==172)// About Info of First Parent
            {
               // Menu += this.addLink("tinyMCE.execCommand('mceAddControl', false, 'PFblockstd_description1');document.getElementById('PFblockstd_description1light').style.display='block';document.getElementById('PFblockstd_description1fade').style.display='block';document.getElementById('PFblockstd_description1_ifr').style.width='100%';document.getElementById('PFblockstd_description1_tbl').style.width='100%'", this.menu_items.edit);
               Menu += this.addLink("tinymice4_init('PFblockstd_description1');document.getElementById('PFblockstd_description1light').style.display='block';document.getElementById('PFblockstd_description1fade').style.display='block';document.getElementById('PFblockstd_description1_ifr').style.width='100%';document.getElementById('PFblockstd_description1_tbl').style.width='100%'", this.menu_items.edit);
            } 

            if(sId==645)// About Us
            {
                Menu += this.addLink("window.location.href = 'pedit.php?ID=" + this.owner + "'", this.menu_items.edit);
            }

           if(sId==648)// Child Preferences
            {
                  Menu += this.addLink("window.location.href = 'pedit.php?ID=" + this.owner + "'", this.menu_items.edit);
             }
         
            /// End of code for AQB Block Menu  - Joseph
	Menu += this.addLink(' ','&nbsp;');
	
	$('#aqb_pc_block_menu').remove();
	
	$('<div id="aqb_pc_block_menu" class="aqb_pc_pop_up_menu"><ul class = "sub">' + Menu + '</ul></div>').appendTo('body');
	$('#aqb_pc_block_menu').css('top', offset.top + 16);
	$('#aqb_pc_block_menu').css('left', offset.left + 2);	
	
	$('#aqb_pc_block_menu').hover(function() {
		
	}, function() {
		$('#aqb_pc_block_menu').remove();
	});
	
}
AqbComposer.prototype.changePrivacy = function(sId) {
	if($('body').find('div#dbPrivacyMenu' + sId).length > 0){
		$('body').find('div#dbPrivacyMenu' + sId).dolPopup({
	        fog: {
	        	color: '#fff', 
	        	opacity: .7
	    	}
		});
		return;
	}
	
	var oDate = new Date();
	
	$.post(this.url + 'get_privacy/' + this.owner + '/' + sId, 
        {
            _t:oDate.getTime()
        },
        function(oData) {
      		if(parseInt(oData.code) == 0) {
            	$('body').append($(oData.data).addClass('dbPrivacyMenu'));
            	$('body').find('div#dbPrivacyMenu' + sId).dolPopup({
        	        fog: {
        	        	color: '#fff', 
        	        	opacity: .7
        	    	}
        		});
            }
        },
        'json'
    );
}

AqbComposer.prototype.savePrivacy = function(e, sId, iGroup) {
    var oDate = new Date();
	$.post(this.url + 'save_privacy/' + this.owner + '/' + sId + '/' + iGroup,  
        {
            _t:oDate.getTime()
        },
        function(oData) {
            if(parseInt(oData) == 0) {
				 $(e).parents('.dbPrivacyMenu').dolPopupHide().find('.dbPrivacyGroupActive').removeClass('dbPrivacyGroupActive').addClass('dbPrivacyGroup');        
                 $(e).removeClass('dbPrivacyGroup').addClass('dbPrivacyGroupActive');
				
				 $('#dbPrivacy' + sId + ' a:first').attr('title', iGroup);
            }
        }
    );
}
	
AqbComposer.prototype.initStyles = function(s) {	
    $(s).addClass("groupItem");
	$(s + " div.boxFirstHeader").css('cursor', 'move');
	$(s + " embed").attr('width','100%');
	$(s + " object").attr('width','100%');
}    

AqbComposer.prototype.makeSortable = function() {
		if($('div.column').is(':ui-sortable')) {
		$('div.column').sortable('destroy');
		}
		
		var notSortable = '';

        $('div[id^="aqb_bMenu"]').each(function(){
			if ($(this).is('.aqb_mvb')) notSortable += '#page_block_' + $(this).attr('id').replace('aqb_bMenu','') + ',';
		});

		var items = '.groupItem';
		if (notSortable.length > 0) items = $('.column > div:not(' + notSortable + ')');
		
		$(".boxFirstHeader", items).css('cursor', 'move');
		
		$('div.column').sortable({
            items : items,
			connectWith: 'div.column',
            handle: '.boxFirstHeader',
            placeholder: 'aqb-pc-placeholder',
            forcePlaceholderSize: true,
            revert: 100,
            opacity: 0.6,
			helper: 'original',
			containment: 'document',
			tolerance:'pointer',
			appendTo: 'body',
			cursor:'auto',
			update: function(event, ui) 
					{ 
					 try{	
						   AqbPC.serialize(); 
						}catch(e){alert(e.toString())};
					} 
			
	    });
}

AqbComposer.prototype.init = function() {
		$("div.page_column").addClass("column");
	    $(".column > div").addClass("groupItem");
		$(".column embed").attr('width','100%');
		$(".column object").attr('width','100%');
		
		AqbPC.makeSortable();		
		
		window.d = document;
		window.buffer = '';
		d.write = d.writeln = function(s){buffer+=s;}
		d.open = d.close = function(){}
}
function tinymice4_init(id) {
     tinymce.init({
	selector: "#"+id,
         plugins: [
         'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
         'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
         'save table contextmenu directionality emoticons template paste textcolor',
         'image moxiemanager'
          ],
     toolbar: 'image insertimage | insertfile insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
    });
}