 function  printorder (id){
	$.ajax({
		url: ''+siteurl+'viewourfamilies/processors/pdf_profile_view.php?Profileid=' + id,
		type: "POST",
		cache: false,
		data: {
		approve: ''
	},
	datatype: "json",
	success: function(data) {	
		console.log(data.status);
      if (data.status == 'success') {
		 if (data.printprofile) {
			
			if (data.deafulttempid == 0) {
				var windowSize = "width=" + window.innerWidth + ",height=" + window.innerHeight + ",scrollbars=no";
				window.open(data.printprofile,'popup', windowSize);
			}else{
				$.ajax({
				url: siteurl + "PDFUser/regenearate_pdf.php",
				type: "POST",
				cache: false,
				data: {
					sel_tmpuser_ID: data.printprofile
				}, 
				datatype: "json",
				success: function (data) {
					var windowSize = "width=" + window.innerWidth + ",height=" + window.innerHeight + ",scrollbars=no";
					window.open(data.filename,'popup', windowSize);
					return true;
				}
				});
			}
		 }else{
				dhtmlx.confirm({
				type: "confirm",
				text: "There is no printed profile available.Would you like to send the family a request ?",
				ok: "Yes",
				cancel: "No",
				callback: function(result) {
					if (result == true) {

					var poststr = "Profileid=" + id;            
					dhtmlxAjax.post(''+siteurl+"Expctantparentsearchfamilies/processors/pdf_profile_request.php", poststr, function(loader) {
						var json = JSON.parse(loader.xmlDoc.responseText);
						if (json.status == "success") {
							dhtmlx.alert({
							text: "Your request has been sent to the family please check back soon"
							});

						} else {
							dhtmlx.message({
							type: "error",
							text: json.message
							});
						}
					});

					}else {

					}
				}
				});		
		}
	  }else{
			dhtmlx.confirm({
			type: "confirm",
			text: "There is no printed profile available.Would you like to send the family a request ?",
			ok: "Yes",
			cancel: "No",
			callback: function(result) {
				if (result == true) {
					var poststr = "Profileid=" + id;            
					dhtmlxAjax.post(''+siteurl+"Expctantparentsearchfamilies/processors/pdf_profile_request.php", poststr, function(loader) {
						var json = JSON.parse(loader.xmlDoc.responseText);
						if (json.status == "success") {
							dhtmlx.alert({
							text: "Your request has been sent to the family please check back soon"
							});
						} else {
							dhtmlx.message({
							type: "error",
							text: json.message
							});
						}
					});
				}else {

				}
			}
			});
	  }
	}
	});
  }