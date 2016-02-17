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

AqbPCMain = new AqbPCF();

function AqbPCF(){};

AqbPCF.prototype.showPopup = function(sUrl) {
    var oPopupOptions = {
        fog: {color: '#fff', opacity: .7},        
    };

    try{
		if ($('#aqb_popup').length > 0) tinyMCE.execCommand('mceRemoveControl', false, 'aqb_text_block_body');	
	}catch(e){}	
	
	$('#aqb_popup').remove();
	
	var oDate = new Date();
	$.get(sUrl, { _t:oDate.getTime() }, function(data) {   
		 $(data).appendTo('body').dolPopup(oPopupOptions); 
	});
}

AqbPCF.prototype.addBlockToMyProfile = function(sBlockID, sUrl){
	var oDate = new Date();
	$.post(sUrl + '/' + sBlockID,{_t:oDate.getTime()},
	        function(oData){
	           try{ 
					alert(oData.message);
					if (oData.code == 0) $('#' + sBlockID).animate({opacity: 0}, 
																	function () {
										                            $(this).slideUp(function () {$(this).remove();});
										                        });
				}catch(e){}
	        },
	        'json'
	     );
}