$( document ).ready(function() {
			
			var $content   = $( '#content' ),
			$grid      = $( '#lb-grid' ),
			$name = $( '#name' ),
			$albums    = $( ".lb-thumb" ),
			$close = $( '#close' ),
			$loader    = $( '<div class="loader"><i></i><i></i><i></i><i></i><i></i><i></i><span>Loading...</span></div>' ).insertBefore( $content );
			
			var makeSortable = function (el) {
				el.sortable({
					handle: '.fa-arrows'
				}).bind('sortupdate', function() {				
					var arrSort, listSort = [];

					$albums.each(function( index ) {
						arrSort = $( this ).attr('id').split("_");

						listSort.push({
							id: arrSort[0],
							sortt: arrSort[1]
						});
					});
					noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Position of the photos saved successfully!', type: 'success', timeout: 1500});
//					console.log(JSON.stringify(listSort));
				});
			};
			
			var featherEditor = new Aviary.Feather({
       			apiKey: '7946996a88618cde',
				apiVersion: 3,
				theme: 'light', // Check out our new 'light' and 'dark' themes!
				tools: 'all',
				appendTo: '',
				onSave: function(imageID, newURL) {
					var img = document.getElementById(imageID);
					img.src = newURL;
//					console.log(newURL);
//					console.log(imageID);
					//$("#"+imageID).remove();
				},
				onError: function(errorObj) {
					alert(errorObj.message);
				},
				postUrl: 'processors/saveEdit.php',
				postdata : imageID
			});
			
			var deleteAlbum = function (arg) {
				$('.fa-trash-o').on( 'click', function(event) {
					var photoID = $(this).closest( "li" );
					
					var n = noty({
            			text        : 'Are you sure you want to delete this '+arg+'?',
			            type        : 'alert',
			            dismissQueue: true,
			            layout      : 'center',
			            theme       : 'defaultTheme',
						modal		: true,
						timeout		: true,
			            buttons     : [
            			    {addClass: 'btn btn-primary', text: 'Yes', onClick: function ($noty) {
								photoID.hide()
			                    $noty.close();
            			        var n1 = noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: arg+' deleted successfully!', type: 'success'});
								setTimeout(function () {
            						n1.close();
        						}, 1500);
                				}
                			},
                			{addClass: 'btn btn-danger', text: 'No', onClick: function ($noty) {
			                    $noty.close();
            			        var n1 = noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Action canceled, the '+arg+' was not erased!', type: 'error'});
								setTimeout(function () {
            						n1.close();
        						}, 1500);
                				}
                			}
            			]
        			}); //Noty
					
				});
			};
				

			var openAlbum = function() {
				
				$( ".lb-thumb" ).on( "click" , function() {
				var albumID = $( this ).attr("id").split("_")[1], albumName = $( this ).attr('alt');
				
				$loader.show();
				$content.hide();
				$close.show();
				$(".control").css( "display", "block");
				$name.html( albumName );
				
				$.post( "ajax.php", { "ID" : albumID, "action": "listAlbum" })
  					.done(function( data ) {
						$content.html( data ).show();//.addClass("album");//console.log(data);
						$loader.hide();
						makeSortable($( ".albums" ));
						deleteAlbum("photo");
						//$(".tooltip").tipTip();
						$(".tooltip").tipTip({attribute: 'data-title'});
						$('.control a[rel^="prettyPhoto"]').prettyPhoto({
							social_tools: '',
							animation_speed:'normal',
							theme:'light_square',
							slideshow:3000,
							show_title: false
						});
						$('.tools a[rel^="prettyIframe"]').prettyPhoto({
							social_tools: '',
							animation_speed:'normal',
							theme:'light_square',
							slideshow:3000,
							show_title: false
						});
						
						$('.fa-pencil-square-o').on( 'click', function() {
							var el  = $(this).parent().parent().find("a");
							var url = document.location+el.attr("href");
							var id  = el.attr("id")+"_";

							var $fakeimage = $("<img src='"+ el.attr("href") +"' id='"+ id +"' />").insertBefore( $content ).hide();

							featherEditor.launch({
								image: id,
								url: url
							});
							return false;
						
						});
						
					//	$('li a[rel^="prettyPhoto"]').prettyPhoto();
						$('li a[rel^="prettyPhoto"]').prettyPhoto({
								animation_speed:'normal',
								theme:'light_square',
								slideshow:3000,
								social_tools : '',
								markup: '<div class="pp_pic_holder"> \
											<div class="ppt">&nbsp;</div> \
											<div class="pp_top"> \
												<div class="pp_left"></div> \
												<div class="pp_middle"></div> \
												<div class="pp_right"></div> \
												<div class="aside">\
													<a class="pp_close" href="#">Close</a> \
													<p class="pp_description"></p> \
													<p class="motofoto tooltip" title="Buy!"><i class="fa fa-shopping-cart"></i></p>\
													<p>Description test</p>\
													<br><br>\
													<p>Comments....</p>\
													<p>Comments....</p>\
													<p>Comments....</p>\
													<input type="text" />\
												</div> \
											</div> \
											<div class="pp_content_container"> \
												<div class="pp_left"> \
													<div class="pp_right"> \
														<div class="pp_content"> \
															<div class="pp_loaderIcon"></div> \
															<div class="pp_fade"> \
																<div class="pp_hoverContainer"> \
																	<a href="#" class="pp_expand" title="Expand the image">Expand</a> \
																	<a class="pp_next" href="#">next</a> \
																	<a class="pp_previous" href="#">previous</a> \
																</div> \
																<div id="pp_full_res"></div> \
																<div class="pp_details"> \
																	<div class="pp_nav"> \
																		<p class="currentTextHolder">0/0</p> \
																	</div> \
																	{pp_social} \
																</div> \
															</div> \
														</div> \
													</div> \
												</div> \
											</div> \
										</div> \
										<div class="pp_overlay"></div>',
								changepicturecallback: function($pp_pic_holder){
									$(".aside").show();
									$(".motofoto").on( 'click', function() {
										//console.log("clicou")
										FOTOMOTO.API.showWindow(FOTOMOTO.API.PRINT, "fullResImage");
									});
									
										
									//var sourceOfImage = $pp_pic_holder.find('img');
									
									//console.log(sourceOfImage);

								}
							});
  					});
			});
			};
			
			$('#lb-grid').imagesLoaded( function( instance ) {
				$loader.hide();
				$('#lb-grid').show();
				openAlbum();
				deleteAlbum("Life book");
				$(".tooltip").tipTip({attribute: 'data-title'});
				makeSortable($('#lb-grid'));
  			});//
			

			$close.on( 'click', function() {
					//$('.tools').hide();
					$loader.show();
					$close.hide();
					$(".control").hide();
					$name.empty();
					$( ".albums" ).empty();
					//stapel.closePile();
					//$('a[rel^="prettyPhoto"]').prettyPhoto().unbind();
					$.post( "ajax.php", { "action": "listGrid" })
  						.done(function( data ) {
							$content.html( data ).show();//.addClass("album");//console.log(data);
							//$name.html( albumName );
							$loader.hide();
							openAlbum();
							$( '#lb-grid' ).show();
							makeSortable($('#lb-grid'));
							deleteAlbum();
							$(".tooltip").tipTip({attribute: 'data-title'});
  					});

				} );
		
		});// Ready