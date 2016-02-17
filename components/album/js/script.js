$(function() {

	/*
		In this code, we are going to do the following:

		1. Accept an image on drag and drop
		2. Create a new canvas element (original), with a max size
		   of 500x500px (customizable) and keep it in memory
		3. Listen for clicks on the filters. When one is selected:
				3.1 Create a clone of the original canvas
				3.2 Remove any canvas elements currently on the page
				3.3 Append the clone to the #photo div
				3.4 If the selected filter is different from the "Normal"
					one, call the Caman library. Otherwise do nothing.
				3.5 Mark the selected filter with the "active" class
		4. Trigger the "Normal" filter

	*/

	var	maxWidth = 500,
		maxHeight = 500,
		photo = $('#photo'),
		originalCanvas = null,
		filters = $('#filters2 li a'),
		filters2 = $('#filters2 li'),
		filterContainer = $('#filterContainer2'),
		imageObj = new Image();

	var img = $('#imagem'),
		imgWidth, newWidth,
		imgHeight, newHeight,
		ratio;

	// Remove canvas elements left on the page
	// from previous image drag/drops.
	photo.find('canvas').remove();
	filters.removeClass('active');

	// When the image is loaded successfully,
	// we can find out its width/height:
	
	//img.load(function() {
	//imageObj.src = "images/10/img_02.jpg";
	//console.log($("#imagem").attr("src"));
	imageObj.src = photoID;
				    
	imageObj.onload = function() {
		
		imgWidth  = img.width();
		imgHeight = img.height();

		// Calculate the new image dimensions, so they fit
		// inside the maxWidth x maxHeight bounding box

		if (imgWidth >= maxWidth || imgHeight >= maxHeight) {

			// The image is too large,
			// resize it to fit a 500x500 square!

			if (imgWidth > imgHeight) {

				// Wide
				ratio = imgWidth / maxWidth;
				newWidth = maxWidth;
				newHeight = imgHeight / ratio;
			} else {

				// Tall or square
				ratio = imgHeight / maxHeight;
				newHeight = maxHeight;
				newWidth = imgWidth / ratio;

			}

		} else {
			newHeight = imgHeight;
			newWidth = imgWidth;
		}

		// Create the original canvas.

		originalCanvas = $('<canvas>');
		if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
			if (typeof window.G_vmlCanvasManager!="undefined") { 
				G_vmlCanvasManager.initElement(originalCanvas);
//		    	c=window.G_vmlCanvasManager.initElement(c);
//			    var cxt=c.getContext("2d");
			}
		}
		var originalContext = originalCanvas[0].getContext('2d');

		// Set the attributes for centering the canvas
		originalCanvas.attr({
			width: newWidth,
			height: newHeight
		}).css({
			marginTop: -newHeight/2,
			marginLeft: -newWidth/2
		});
					
		//    context.drawImage(imageObj, newWidth, newHeight);
		// Draw the dropped image to the canvas
		// with the new dimensions
		originalContext.drawImage(imageObj, 0, 0, newWidth, newHeight);

		// We don't need this any more
		//imageObj.remove();

		filterContainer.fadeIn();

		// Trigger the default "normal" filter
		filters.first().click();
	};
	
	
	// Listen for click rotate
	var rotate = $('#rotate');
	rotate.click(function(e){
		e.preventDefault();
		
		Caman("#photo canvas", function () {
  			this.rotate(90);
			this.render();
		});
		
		Caman("#imageFull", function () {
  			this.rotate(90);
			this.render();
		});
		Caman("#imageThumb", function () {
  			this.rotate(90);
			this.render();
		});
		
	});
	
	$("#apply").click(function(e){
		e.preventDefault();
		
		var n = noty({
			text : 'Are you sure you want to apply this effect?<br>Attention! This action can not be undone.',
			            type        : 'alert',
			            dismissQueue: true,
			            layout      : 'center',
			            theme       : 'defaultTheme',
						modal		: true,
						timeout		: true,
			            buttons     : [
            			    {addClass: 'btn btn-primary', text: 'Yes', onClick: function ($noty) {
								//photoID.hide()
								var photObjF = $('#imageFull'),photObjT = $('#imageThumb');
								var photoUrlF = photObjF[0].toDataURL("image/jpeg","1");
								var photoUrlT = photObjT[0].toDataURL("image/jpeg","1");								
								
								$.post( "caman_upload.php", { photoF: photoUrlF, photoT: photoUrlT, filename : photoID})
									.done(function( data ) {
										//alert( "Data Loaded: " + data );
										$noty.close();
			           			        var n1 = noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Effect applied successfully', type: 'success'});
										setTimeout(function () {
            								n1.close();
			       						}, 1500);
									});
                				}
                			},
                			{addClass: 'btn btn-danger', text: 'No', onClick: function ($noty) {
			                    $noty.close();
            			        var n1 = noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Action canceled, the effect was not applied!', type: 'error'});
								setTimeout(function () {
            						n1.close();
        						}, 1500);
                				}
                			}
            			]
        			}); //Noty
		
	});

	// Listen for clicks on the filters

	filters.click(function(e){

		e.preventDefault();

		var f = $(this);

		if(f.is('.active')){
			// Apply filters only once
			return false;
		}

		filters.removeClass('active');
		f.addClass('active');

		// Clone the canvas
		var clone = originalCanvas.clone();

		// Clone the image stored in the canvas as well
		clone[0].getContext('2d').drawImage(originalCanvas[0],0,0);


		// Add the clone to the page and trigger
		// the Caman library on it

		photo.find('canvas').remove().end().append(clone);

		var effect = $.trim(f[0].id);
		
		Caman(clone[0], function () {

			// If such an effect exists, use it:

			if( effect in this){
				this[effect]();
				this.render();

				// Show the download button
				$("#filters2").mCustomScrollbar("scrollTo","#"+effect);
				showDownload(clone[0]);
			}
			else{
				hideDownload();
			}
		});
		
		Caman("#imageFull", function () {
			// If such an effect exists, use it:
			if( effect in this){
				this[effect]();
				this.render();
				//showDownload(this);
			}
		});
		Caman("#imageThumb", function () {
			// If such an effect exists, use it:
			if( effect in this){
				this[effect]();
				this.render();
				showDownload(this);
			}
		});

	});
	
	filters.each(function(index) {

		var filter = this.id;
		Caman("#"+filter+" img", function() {
		//console.log(filter);			
			if( filter in this){
				this[filter]();
				this.render();
			}
		});
		
		/*if (index = filters.length){
			Caman.Event.listen("renderFinished", function (job) {
  				console.log("Start:", job);

			});
			$("#filters22").show();
		}*/
		
	});

	// Use the mousewheel plugin to scroll
	// scroll the div more intuitively

	filterContainer.find('ul').on('mousewheel',function(e, delta){

		this.scrollTop -= (delta * 50);
		e.preventDefault();

	});

	var downloadImage = $('#download');

	function showDownload(canvas){

		downloadImage.off('click').click(function(){
			
			// When the download link is clicked, get the
			// DataURL of the image and set it as href:
			var testobj = $('#imageFull');
			var url = testobj[0].toDataURL("image/jpeg");
			downloadImage.attr('href', url).attr("target","_blank");
			
		}).fadeIn();

	}

	function hideDownload(){
		downloadImage.fadeOut();
	}

});
