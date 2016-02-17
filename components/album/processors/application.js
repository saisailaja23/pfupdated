$(document).ready(function () {

  var $content = $('#content'),
    $grid = $('#lb-grid'),
    $name = $('#name'),
    $albums = $('.lb-thumb'),
    $images = $('.albums'),
    $close = $('#close'),
    $loader = $('<div class="loader"><i></i><i></i><i></i><i></i><i></i><i></i><span>Loading...</span></div>').insertBefore($content);
  $content.hide();
  var makeSortable = function (el) {
    el.sortable({
      handle: '.fa-arrows'
    }).bind('sortupdate', function () {
      var arrSort, listSort = [];
      el.find('a').each(function (index) {
        arrSort = $(this).attr('id').split("_");
        listSort.push({
          id: arrSort[1],
          sort: index
        });
      });
      console.log(listSort);
      noty({
        dismissQueue: true,
        force: true,
        layout: 'center',
        theme: 'defaultTheme',
        text: 'Position of the photos saved successfully!',
        type: 'success',
        timeout: 1500
      });
    });
  };

  var featherEditor = new Aviary.Feather({
    apiKey: '7946996a88618cde',
    apiVersion: 3,
    theme: 'light',
    tools: 'all',
    appendTo: '',
    postUrl: 'http://cdmap01.myadoptionportal.com/modules/childconnect/processors/saveEdit.php',
    onSave: function (imageID, newURL) {
      var img = $("#" + imageID.replace("_fake", "")).find("img");

      var maxWidth = 200;
      var maxHeight = 150;
      var ratio = 0;
      var width = $(img[0]).width();
      var height = $(img[0]).height();

      if (width > height) {
        height = (height / width) * maxHeight;

      } else if (height > width) {
        maxWidth = (width / height) * maxWidth;
      }
      $(img[0]).css("width", maxWidth);
      $(img[0]).css("height", maxHeight);

      img[0].src = newURL;
    },
    onError: function (errorObj) {
      alert(errorObj.message);
    }
  });

  var editTitle = function (arg) {
    $(".edit").editable("save.php", {
      indicator: "<img src='images/loading_2.gif'>",
      tooltip: "Doubleclick to edit...",
      event: "dblclick",
      style: "inherit",
      submitdata: function (value, settings) {
        if (arg == "Life book") {
          var titleID = $($(this).closest("li")[0]).find("img").attr("id");
        } else {
          var titleID = $($(this).closest("li")[0]).find("a").attr("id");
        }
        return {
          id: titleID,
          type: arg
        };
      }
    });
  };

  var publish = function (arg) {
    $('.publish').on('click', function (e) {
      var btn_publish = this;
      var albumID = $(this).data("id");

      if ($(btn_publish).find("i").hasClass("fa-arrow-circle-o-down")) {
        var action = "unPublish";
        var action_text = "<h1>Lifebook is locked!</h1>Do you wish to request to open to make changes?";
      } else {
        var action = "Publish";
        var action_text = "Are you sure you want to <b>Publish</b> this Lifebook?";
      }

      var n = noty({
        text: action_text,
        type: 'alert',
        dismissQueue: true,
        layout: 'center',
        theme: 'defaultTheme',
        modal: true,
        timeout: true,
        buttons: [{
          addClass: 'btn btn-primary',
          text: 'Yes',
          onClick: function ($noty) {

            if (action == "unPublish") {
              $noty.close();
              var n2 = noty({
                text: '<h1>Unpublish Reason</h1><br><textarea id="up_reason"></textarea>',
                type: 'alert',
                dismissQueue: true,
                layout: 'center',
                theme: 'defaultTheme',
                modal: true,
                timeout: true,
                buttons: [{
                  addClass: 'btn btn-primary',
                  text: 'Send',
                  onClick: function ($noty) {
                    var up_reason = $('#up_reason').val();
                    $noty.close();
                    $.post("processors/publish.php", {
                      "id": albumID,
                      "action": action,
                      "reason": up_reason
                    })
                      .done(function (data) {
                        var n1 = noty({
                          dismissQueue: true,
                          force: true,
                          layout: 'center',
                          theme: 'defaultTheme',
                          text: 'Your request has been sent to the agency<br>Please wait.',
                          type: 'success'
                        });
                        setTimeout(function () {
                          n1.close();
                        }, 2500);
                      });
                  }
                }, {
                  addClass: 'btn btn-danger',
                  text: 'Cancel',
                  onClick: function ($noty) {
                    $noty.close();
                    var n1 = noty({
                      dismissQueue: true,
                      force: true,
                      layout: 'center',
                      theme: 'defaultTheme',
                      text: 'Action canceled, the Lifebook was not ' + action + 'ed!',
                      type: 'error'
                    });
                    setTimeout(function () {
                      n1.close();
                    }, 2500);
                  }
                }]
              }); //Noty 2
            } else { //if unPublish
              $noty.close();
              $.post("processors/publish.php", {
                "id": albumID,
                "action": action
              })
                .done(function (data) {
                  var n1 = noty({
                    dismissQueue: true,
                    force: true,
                    layout: 'center',
                    theme: 'defaultTheme',
                    text: 'Lifebook ' + action + 'ed successfully!',
                    type: 'success'
                  });
                  setTimeout(function () {
                    n1.close();
                  }, 2500);
                });
            }
          }
        }, {
          addClass: 'btn btn-danger',
          text: 'No',
          onClick: function ($noty) {
            $noty.close();
            var n1 = noty({
              dismissQueue: true,
              force: true,
              layout: 'center',
              theme: 'defaultTheme',
              text: 'Action canceled, the Lifebook was not ' + action + 'ed!',
              type: 'error'
            });
            setTimeout(function () {
              n1.close();
            }, 1500);
          }
        }]
      }); //Noty

    });
  };

  var deleteAlbum = function (arg) {
    $('.delete').on('click', function (event) {
      if ($(this).data("id")) {
        var photoID = $(this).data("id");
        arg = "Life book";
      } else {
        var photo = $(this).closest("li");
        var photoID = $(photo[0]).find("a").attr("id");
        arg = "photo";
      }

      console.log(photoID);

      var n = noty({
        text: 'Are you sure you want to delete this ' + arg + '?',
        type: 'alert',
        dismissQueue: true,
        layout: 'center',
        theme: 'defaultTheme',
        modal: true,
        timeout: true,
        buttons: [{
          addClass: 'btn btn-primary',
          text: 'Yes',
          onClick: function ($noty) {
            $.post("processors/delete.php", {
              "type": arg,
              "id": photoID
            })
              .done(function (data) {

                $noty.close();
                var n1 = noty({
                  dismissQueue: true,
                  force: true,
                  layout: 'center',
                  theme: 'defaultTheme',
                  text: arg + ' deleted successfully!',
                  type: 'success'
                });

                setTimeout(function () {
                  n1.close();
                }, 1500);

                if (arg == "Life book") {
                  $close.click();
                } else {
                  photo.hide();
                }

              });
          }
        }, {
          addClass: 'btn btn-danger',
          text: 'No',
          onClick: function ($noty) {
            $noty.close();
            var n1 = noty({
              dismissQueue: true,
              force: true,
              layout: 'center',
              theme: 'defaultTheme',
              text: 'Action canceled, the ' + arg + ' was not erased!',
              type: 'error'
            });
            setTimeout(function () {
              n1.close();
            }, 1500);
          }
        }]
      }); //Noty

    });
  };

  var openAlbum = function () {

    $(".lb-thumb").on("click", function () {
      var albumID = $(this).attr("id"),
        albumName = $(this).attr('alt');

      $loader.show();
      $content.hide();
      $close.show();
      $(".control").css("display", "block");
      $name.html(albumName);

      $.post("ajax.php", {
        "ID": albumID,
        "action": "listAlbum"
      })
        .done(function (data) {
          var lastClicked = "";
          $content.html(data).show();
          $loader.hide();
          makeSortable($(".albums"));
          deleteAlbum("photo");
          editTitle("photo");
          filterAlbums();
          publish();
          addAlbum();
          $(".tooltip").tipTip({
            attribute: 'data-title'
          });
          $('.control a[rel^="prettyPhoto"]').prettyPhoto({
            social_tools: '',
            animation_speed: 'normal',
            theme: 'light_square',
            slideshow: 3000,
            show_title: false,
            callback: function (e) {
              $close.click();
              $content.hide();
              $loader.show();
              setTimeout(function () {
                var id = $("#" + $(lastClicked).data("id"));
                //	console.log(id);
                $(id).click();
              }, 1000);
            }
          }).click(function () {
            lastClicked = $(this);
          });
          $('.tools a[rel^="prettyIframe"]').prettyPhoto({
            social_tools: '',
            animation_speed: 'normal',
            theme: 'light_square',
            slideshow: 3000,
            show_title: false
          });

          $('.fa-pencil-square-o').on('click', function () {
            var el = $(this).parent().parent().find("a");
            var url = document.location + el.attr("href");
            var id = el.attr("id");
            var fakeID = id + "_fake";

            var $fakeimage = $("<img src='" + el.attr("href") + "' id='" + fakeID + "' />").insertBefore($content).hide();

            featherEditor.launch({
              image: fakeID,
              url: url,
              postUrl: 'http://cdmap01.myadoptionportal.com/modules/childconnect/processors/saveEdit.php',
              postData: id
            });
            return false;

          });


          $('li a[rel^="prettyPhoto"]').prettyPhoto({
            animation_speed: 'normal',
            theme: 'light_square',
            slideshow: 3000,
            social_tools: '',
            markup: '<div class="pp_pic_holder"> \
											<div class="ppt">&nbsp;</div> \
											<div class="pp_top"> \
												<div class="pp_left"></div> \
												<div class="pp_middle"></div> \
												<div class="pp_right"> \
													<a class="pp_close" href="#">Close</a> \
												</div> \
											</div> \
											<div class="pp_content_container"> \
												<div class="pp_left"> \
													<div class="pp_right"> \
														<div class="pp_content"> \
															<div class="aside">\
																<p class="pp_description"></p> \
																<p class="motofoto tooltip" title="Buy!"><i class="fa fa-shopping-cart"></i></p>\
																<div id="comments" class="scroller"></div>\
															</div> \
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
            changepicturecallback: function ($pp_pic_holder) {
              var photoID = $pp_pic_holder.find("#pp_full_res img").attr("rel");
              //console.log($pp_pic_holder.find("#pp_full_res img").attr("rel"));
              $("#comments").hide();
              $(".aside").show();
              $("#comments").load("processors/comments.php", {
                "photoID": photoID
              })
                .fadeIn()
                .slimScroll({
                  height: '84%',
                  scrollTo: $('.comment-box').height()
                });
              $(".motofoto").on('click', function () {
                FOTOMOTO.API.showWindow(FOTOMOTO.API.PRINT, "fullResImage");
              });
            }
          });
        });
    });
  };

  $('#lb-grid').imagesLoaded(function (instance) {
    $loader.hide();
    $content.show();
    $('#lb-grid').show();
    openAlbum();
    deleteAlbum("Life book");
    editTitle("Life book");
    $(".tooltip").tipTip({
      attribute: 'data-title'
    });
    filterAlbums();
    publish();
    addAlbum();
    $('a[rel^="prettyPhoto"]').prettyPhoto({
      social_tools: '',
      animation_speed: 'normal',
      theme: 'light_square',
      slideshow: 3000,
      show_title: false
    });
    //makeSortable($('#lb-grid'));
  }); //

  var addAlbum = function (arg) {
    $('.addAlbum').on('click', function (e) {

      var n = noty({
        text: '<h1>Create new Lifebook</h1><br><label>Lifebook Name</label><br><input type="text" id="lb-name" /><br><label>Description</label><br><textarea id="lb-desc"></textarea><br>',
        type: 'alert',
        dismissQueue: true,
        layout: 'center',
        theme: 'defaultTheme',
        modal: true,
        timeout: true,
        buttons: [{
          addClass: 'btn btn-primary',
          text: 'Create',
          onClick: function ($noty) {
            $.post("processors/create.php", {
              "name": $("#lb-name").val(),
              "desc": $("#lb-desc").val()
            })
              .done(function (data) {
                var result = jQuery.parseJSON(data);

                $noty.close();
                var n1 = noty({
                  dismissQueue: true,
                  force: true,
                  layout: 'center',
                  theme: 'defaultTheme',
                  text: 'Lifebook created successfully!',
                  type: 'success'
                });

                setTimeout(function () {
                  n1.close();
                }, 1500);
                var $newLifebook = $("<li rel='saved'> \
															<span class='tp-info'>" + $("#lb-name").val() + " \
						                               			<span class='lb-views'>0</span> \
															</span> \
															<img class='lb-thumb' id='" + result.albumID + "' src='images/10/img_05_thumb.jpg' alt='" + $("#lb-name").val() + "' /> \
														</li>").appendTo($grid);
              });
            openAlbum();
          }
        }, {
          addClass: 'btn btn-danger',
          text: 'Cancel',
          onClick: function ($noty) {
            $noty.close();
            var n1 = noty({
              dismissQueue: true,
              force: true,
              layout: 'center',
              theme: 'defaultTheme',
              text: 'Action canceled, the Lifebook was not created!',
              type: 'error'
            });
            setTimeout(function () {
              n1.close();
            }, 1500);
          }
        }]
      }); //Noty

    });
  };

  $close.on('click', function () {
    $loader.show();
    $close.hide();
    $(".control").hide();
    $name.empty();
    $(".albums").empty();
    $.post("ajax.php", {
      "action": "listGrid"
    })
      .done(function (data) {
        $content.html(data).show();
        $loader.hide();
        openAlbum();
        $('#lb-grid').show();
        editTitle("Life book");
        deleteAlbum();
        filterAlbums();
        publish();
        addAlbum();
        $(".tooltip").tipTip({
          attribute: 'data-title'
        });
        $('a[rel^="prettyPhoto"]').prettyPhoto({
          social_tools: '',
          animation_speed: 'normal',
          theme: 'light_square',
          slideshow: 3000,
          show_title: false
        });
      });

  });

  var filterAlbums = function () {
    $('.filter-menu li a').on('click', function (e) {
      e.preventDefault();

      $('.filter-menu li').removeClass('btn');
      $(this).parent('li').addClass('btn');

      thisItem = $(this).attr('rel');

      if (thisItem != "all") {

        $("#lifebooksCont span").text($('#lb-grid li[rel=' + thisItem + ']').length) //Update number of albums

        $('#lb-grid li[rel=' + thisItem + ']').stop()
          .animate({
            'width': '235px',
            'opacity': 1,
            'marginRight': '.5em',
            'marginLeft': '.5em'
          });

        $('#lb-grid li[rel!=' + thisItem + ']').stop()
          .animate({
            'width': 0,
            'opacity': 0,
            'marginRight': 0,
            'marginLeft': 0
          });
      } else {
        $("#lifebooksCont span").text($('#lb-grid li').length) //Update number of albums
        $('#lb-grid li').stop()
          .animate({
            'opacity': 1,
            'width': '235px',
            'marginRight': '.5em',
            'marginLeft': '.5em'
          });
      }
    })
  }


}); // Ready