var photoAlbum = (function() {
    $.ajaxSetup({ cache: false });
    var
        $photoResult,
        $wrap, $topbar, $loader, $close, $content, $name, $albumID,
        $albumCount, $grid, $addAlbum, $albums, $albumEdit, $photoEdit,
        $images, $close, $controls;
    return {
        init: function(pr) {
            $photoResult = pr;
            this.buildAlbumFrame();
            this.fetchAlbum();
        },
        topBarEvents: function() {
            var self = this;
            $close.on('click', function(e) {
                e.preventDefault();
                if ($(this).hasClass('back')) {
                    $(this).removeClass('back').html("&#8592;");
                    self.buildAlbumFrame();
                    self.fetchAlbum();
                } else {
                    $wrap.parent().slideUp(500, function() {
                        $(this).css({
                            'min-height': 0
                        });
                        $wrap.remove();
                    });
                }
            });
        },
        toolbarEvents: function() {
            var self = this;
            $addAlbum.off('click');
            $addAlbum.on('click', function(e) {
                e.preventDefault();
                self.addAlbum();
            });
            $filterMenu.find('a').on('click', function(e) {
                e.preventDefault();
                self.filterAlbums($(this));
            });
        },
        albumEvents: function() {
            var self = this;
            $album.on('click', function(e) {
                e.preventDefault();
                self.openAlbum($(this));
            });
            self.editTitle("albumName");
        },
        fetchAlbum: function() {
            var self = this;

            if (mem == 25) { // This condition was written by Satya to show a message instead of Album for membership type 25.
                $content.show();
                $loader.hide();
                $grid = $content.find("#lb-grid");
                $grid.html("<div>Your current membership basic doesn't allow you to access photos.</div><div>Please upgrade <u><a href='extra_member.php'>here</a></u></div>");
                return;

            }
            $.get(site_url + "components/album/processors/album/PhotoAlbum.php?action=list", function(d) {
                var d = $.parseJSON(d);
                if (d.status === "success") {
                    self.buildAlbumGrid(d.data);
                } else {
                    $content.show();
                    $loader.hide();
                    $grid = $content.find("#lb-grid");
                    $grid.html("No Albums yet! Create new album");
                    self.regContentVars();
                    self.toolbarEvents();
                }
            });
        },
        buildAlbumGrid: function(data) {
            var i, html = "";
            for (i = 0; i < data.length; i++) {
                var editClass = (decodeURIComponent(data[i].Caption) === "Home Pictures" || decodeURIComponent(data[i].Caption) === "Profile Pictures") ? '' : 'edit';
                var view = (data[i].Views === null) ? 0 : data[i].Views;
                var status = (data[i].Status === "active") ? "active " : "pending ";
                status += (data[i].AllowAlbumView === 3) ? "published" : "published";
                var img = !data[i].Hash ? site_url + 'templates/tmpl_par/images/no-img.jpg' : site_url + 'm/photos/get_image/browse/' + data[i].Hash + '.' + data[i].Ext;
                html +=
                    '<li rel="' + status + '" >' +
                    '	<span class="tp-info">' +
                    '   <span class="' + editClass + '" albumid="' + data[i].ID + '" title="' + decodeURIComponent(data[i].Caption) + '">' + decodeURIComponent(data[i].Caption) + '</span>' +
                    '		<span class="lb-views">' + view + '</span>' +
                    '	</span>' +
                    '	<div class="wrap-image">' +
                    '		<img data-pubstatus="' + data[i].AllowAlbumView + '" class="lb-thumb" id="' + data[i].ID + '" src="' + img + '" alt="' + decodeURIComponent(data[i].Caption) + '">' +
                    '	</div>' +
                    '	<i class="fa fa-eye">' + view + '</i>' +
                    '</li>';
            }
            $content.show();
            $albumCount = $wrap.find("#lifebooksCont > span"),
            $albumCount.html(data.length);
            $grid = $content.find("#lb-grid");
            $grid.html(html);
            $loader.hide();
            this.regContentVars();
            this.toolbarEvents();
            this.albumEvents();
        },
        buildAlbumFrame: function() {

            var sHtml =
                '<div class="wrapper">' +
                '	<div class="topbar"> ' +
                '		<span id="close">&#8593;</span>' +
                '		<h2>Photo Album</h2> <span id="name" style="font-size: 19px;float: right;color: #aaa;margin-top: 2px;"></span>' +
                '	</div> <!-- LifeBook Grid -->' +
                ' <div class="loader2"><i></i><i></i><i></i><i></i><i></i><i></i><span>Loading...</span></div>' +
                ' <div id="content" style="display: none;">';
            if (mem != 25) { // This condition was written by Satya to show a message instead of Album for membership type 25.
                sHtml +=
                    '   <ul class="filter-menu">' +
                    '     <li class="plain-btn"><a href="#" rel="all">All</a> </li>' +
                    '     <li><a href="#" rel="published">Published</a> </li>' +
                    '     <li><a href="#" rel="unpublished">Private</a> </li>' +
                    '     <li><a href="#" rel="active">Active</a> </li>' +
                    '     <li><a href="#" rel="pending">Pending Review</a> </li>' +
                    '   </ul>' +
                    '   <div id="lifebooksCont"> <span>0</span> Albums</div>' +
                    '   <a href="album_list.php" class="teal-btn downloadAlbum" style="position:relative;float:right;margin-top:-50px; margin-right:105px;">' +
                    '     <i class="fa fa-download"></i> Download Images' +
                    '   </a>' +	
                    '   <a href="#" class="teal-btn addAlbum" title="Create a New Album.">' +
                    '     <i class="fa fa-plus"></i> Create Album' +
                    '   </a>';
            }
            sHtml +=
                '		<ul id="lb-grid" ></ul>' +
                '	</div> <!-- #content-->' +
                '</div>';

            $photoResult.html(sHtml);
            $photoResult.slideDown();
            this.regTopBarVars();
            this.topBarEvents();
        },
        regTopBarVars: function() {
            $wrap = $('.wrapper');
            $topbar = $wrap.find('.topbar');
            $loader = $wrap.find('.loader2');
            $content = $('#content');
            $close = $('#close');
            $name = $wrap.find('#name')

            // $grid = $('#lb-grid'),
            // $name = $('#name'),
            // $albums = $('.lb-thumb'),
            // $images = $('.albums'),
            // $close = $('#close'),
            // $loader = $('.loader2');
        },
        regContentVars: function() {
            $addAlbum = $content.find('.addAlbum');
            $filterMenu = $content.find('.filter-menu');
            $album = $content.find('.lb-thumb');
            $albumEdit = $content.find('li').find('.edit');
        },
        //functionality
        addAlbum: function() {
            var createFormHtml =
                '<h1>Create new Album</h1>' +
                '<br><label>Album Name </label><label id="albmCnt">(40 characters remaining)</label><br>' +
                '<input type="text" id="lb-name" /><br>' +
                '<label>Privacy</label><br />' +
                '<select name="lb-privacy" id="lb-privacy">' +
                //   '  <option value="2">Private</option>' +
                '  <option value="4">Member</option>' +
                '  <option value="3">Public</option>' +
                '</select><br />' +
                '<label>Description</label><br>' +
                '<textarea id="lb-desc"></textarea><br>' +
                '';

            var self = this,
                n1, n = noty({
                    text: createFormHtml,
                    type: 'alert',
                    dismissQueue: true,
                    layout: 'center',
                    theme: 'defaultTheme',
                    modal: true,
                    timeout: true,
                    buttons: [{
                        addClass: 'teal-btn',
                        text: 'Create',
                        onClick: function($noty) {
                            if ($("#lb-name").val() == "" || $("#lb-desc").val() == "") {
                                alert("Please fill in the required fields");
                            } else {
                                $.get(site_url + "components/album/processors/album/PhotoAlbum.php", {
                                    "action": "add",
                                    "name": encodeURIComponent($("#lb-name").val()),
                                    "desc": encodeURIComponent($("#lb-desc").val()),
                                    "privacy": $('#lb-privacy').val()
                                }, function(data) {

                                    var data = $.parseJSON(data);
                                    $noty.close();
                                    if (data.status === "success") {
                                        n1 = noty({
                                            dismissQueue: true,
                                            force: true,
                                            layout: 'center',
                                            theme: 'defaultTheme',
                                            text: 'Album created successfully!',
                                            type: 'success'
                                        });
                                    } else {
                                        n1 = noty({
                                            dismissQueue: true,
                                            force: true,
                                            layout: 'center',
                                            theme: 'defaultTheme',
                                            text: data.msg,
                                            type: 'error'
                                        });
                                    }
                                    setTimeout(function() {
                                        n1.close();
                                    }, 1500);
                                    self.fetchAlbum();
                                });
                            }
                        }
                    }, {
                        addClass: 'pink-btn',
                        text: 'Cancel',
                        onClick: function($noty) {
                            $noty.close();
                            var n1 = noty({
                                dismissQueue: true,
                                force: true,
                                layout: 'center',
                                theme: 'defaultTheme',
                                text: 'Action canceled, the Album was not created!',
                                type: 'error'
                            });
                            setTimeout(function() {
                                n1.close();
                            }, 1500);
                        }
                    }]
                }); //Noty
                $("#lb-name").keyup(function() {
                    var length = this.value.length;
                    var charactersLeft = 40 - length;
                    if(charactersLeft <= 0) {
                        $('#albmCnt').html('(0 characters remaining)');
                        $('#albmCnt').css('cssText','color:red !important');
                        $(this).val($(this).val().substr(0, 40));
                    }
                    else {
                        $('#albmCnt').html('('+charactersLeft+' characters remaining)');
                        $('#albmCnt').css('cssText','color:#76787b !important');
                    }
                });
        },
        filterAlbums: function($this) {
            $filterMenu.find('li').removeClass('plain-btn');
            $this.parent('li').addClass('plain-btn');
            var thisItem = $this.attr('rel');

            if (thisItem != "all") {
                $albumCount.text($grid.find('li[rel*=' + thisItem + ']').length); //Update number of albums
                console.log($grid.find('li[rel*=' + thisItem + ']'));
                $grid.find('li').stop().hide()
                    .animate({
                        'width': 0,
                        'opacity': 0,
                        'marginRight': 0,
                        'marginLeft': 0
                    });

                $grid.find('li[rel*=' + thisItem + ']').stop().show()
                    .animate({
                        'width': '235px',
                        'opacity': 1,
                        'marginRight': '.5em',
                        'marginLeft': '.5em'
                    });


            } else {
                $albumCount.text($grid.find('li').length) //Update number of albums
                $grid.find('li').stop().show()
                    .animate({
                        'opacity': 1,
                        'width': '235px',
                        'marginRight': '.5em',
                        'marginLeft': '.5em'
                    });
            }
        },
        openAlbum: function($this) {
            var albumID = $this.attr("id"),
                albumName = $this.attr('alt'),
                pubstatus = $this.attr('data-pubstatus');
            $albumID = albumID;
            $loader.show();
            $content.hide();
            $close.addClass('back').html('&#8592');
            $name.html(albumName);
            this.buildAlbumView(albumID, albumName, pubstatus);

            this.getPhotos(albumID);
        },
        buildAlbumView: function(id, albumName, pubstatus) {
            var delStr = ' <a href="#" style="float:left;margin-left:25px;" class="pink-btn delete" data-id="' + id + '" title="Delete Album."><i class="fa fa-trash-o "></i> Delete Album</a>';
            var downStr = ' <a href="#" style="float:left;margin-left:25px;" class="pink-btn download" data-id="' + id + '" title="Download Album."><i></i> Download Album</a>';
            var loadstr = ' <div id="LoadingImage" style="display: none;padding-left:25px;"><img src="templates/base/images/loading.gif" style="padding-left:25px" /></div>';




           
           
            if (albumName === "Home Pictures" || albumName === "Profile Pictures") {
                delStr = "";
            }
            // var pubStr = (pubstatus == 2) ? '<a href="#" class="green-btn  right publish" data-id="' + id + '" title="Publish Album."><i class="fa fa-arrow-circle-o-up"></i> Publish Album</a>' : '<a href="#" class="pink-btn  right publish" data-id="' + id + '" title="Unpublish Album."><i class="fa fa-arrow-circle-o-down"></i> Unpublish Album</a>';
            var pubHtml =
                '<div style="position:relative;float:right;"><span style="position:relative;top:3px;">Who can view:</span>\
                <select name="album-privacy" id="album-privacy" data-albumid="' + id + '">' +
                // '  <option value="2">Private</option>' +
                '  <option value="4">Member</option>' +
                '  <option value="3">Everyone</option>' +
                '</select></div>';
            pubHtml = pubHtml.replace('value="' + pubstatus + '"', 'selected="selected" value="' + pubstatus + '"');
            var html =
                '<span class="control" style="overflow: hidden;">' +
                '	<a style="float:left;" href="' + site_url + 'components/album/processors/uploadFiles.php?type=photo&albumID=' + id + '&amp;iframe=true&amp;width=80%&amp;height=420" data-id="23" rel="prettyPhoto" class="pink-btn" title="Upload files."><i class="fa fa-upload"></i> Upload Files</a>' +
                delStr +
                downStr +
                loadstr +
                // '	<a href="#" class="pink-btn  right publish" data-id="' + id + '" title="Unpublish Album."><i class="fa fa-arrow-circle-o-down"></i> Unpublish Album</a>' +
                // '	<a href="#" class="green-btn  right publish" data-id="' + id + '" title="Publish Album."><i class="fa fa-arrow-circle-o-up"></i> Publish Album</a>' +
                 pubHtml +
                '</span>' +
                '<ul class="albums"></ul>';
            $content.html(html);
            $controls = $('.control');
            $albums = $('.albums');
            this.buildAlbumViewEvents();
        },
        buildAlbumViewEvents: function() {
            var self = this;
            var lastClicked = "";
            $('.control a[rel^="prettyPhoto"]').prettyPhoto({
                social_tools: '',
                animation_speed: 'normal',
                theme: 'light_square',
                slideshow: 3000,
                show_title: false,
                callback: function(e) {
                    var btn = $(lastClicked).text();
                    if (btn == " Upload Files") {
                        self.getPhotos($albumID);
                        //$close.click();
                        // $content.hide();
                        // $loader.show();
                        // setTimeout(function () {
                        //   var id = $("#" + $(lastClicked).attr("id"));
                        //   $(id).click();
                        // }, 1000);
                    }
                }
            }).click(function() {
                lastClicked = $(this);
            });
            $('.tools a[rel^="prettyIframe"]').prettyPhoto({
                social_tools: '',
                animation_speed: 'normal',
                theme: 'light_square',
                slideshow: 3000,
                show_title: false
            });
            $('.publish').on('click', function(e) {
                e.preventDefault();
                self.publish($(this));
            });

            $('#album-privacy').on('change', function(e) {
                e.preventDefault();
                self.privacy($(this));
            });


            $('.delete').off('click');
            $('.delete').on('click', function(e) {
                e.preventDefault();
                self.remove($(this));
            });
                 $('.download').off('click');
            $('.download').on('click', function(e) {         
                 e.preventDefault();
                 self.download($(this));
            });
        },
        download: function($this) {            
         var albumID = "";

         if ($this.data("id")) {
         albumID = $this.data("id");
            } 
        url = site_url + "components/album/processors/photodownload/photodownload.php?albumid=" + albumID;
        $("#LoadingImage").show();           
                          
                 $.ajax({
                 url: url,
                 type: 'POST',
                 success: function() {                 
                 window.location = url;             
                 $("#LoadingImage").hide();
                  }
                 });
 
        },
        remove: function($this) {
            var type = "",
                photo,
                photoID = "",
                albumID = "";

            if ($this.data("id")) {
                albumID = $this.data("id");
                type = "album";
            } else {
                photo = $this.closest("li");
                photoID = $(photo[0]).find("a").attr("id");
                albumID = $(photo[0]).find("a").attr("data-albumid");
                type = "photo";
            }

            var n = noty({
                text: 'Are you sure you want to delete this ' + type + '?',
                type: 'alert',
                dismissQueue: true,
                layout: 'center',
                theme: 'defaultTheme',
                modal: true,
                timeout: true,
                buttons: [{
                    addClass: 'teal-btn',
                    text: 'Yes',
                    onClick: function($noty) {
                        var url = "",
                            args = {};
                        if (type === "album") {
                            url = site_url + "components/album/processors/album/PhotoAlbum.php?action=removeAlbum&albumid=" + albumID;
                        } else {
                            url = site_url + "components/album/processors/album/PhotoAlbum.php?action=removePhoto&albumid=" + albumID + "&photoid=" + photoID;
                        }

                        $.get(url, function(data) {
                            $noty.close();
                            var n1 = noty({
                                dismissQueue: true,
                                force: true,
                                layout: 'center',
                                theme: 'defaultTheme',
                                text: type + ' deleted successfully!',
                                type: 'success'
                            });

                            setTimeout(function() {
                                n1.close();
                            }, 1500);

                            if (type == "album") {
                                $close.click();
                            } else {
                                photo.hide();
                            }

                        });
                    }
                }, {
                    addClass: 'pink-btn',
                    text: 'No',
                    onClick: function($noty) {
                        $noty.close();
                        var n1 = noty({
                            dismissQueue: true,
                            force: true,
                            layout: 'center',
                            theme: 'defaultTheme',
                            text: 'Action canceled, the ' + type + ' was not erased!',
                            type: 'error'
                        });
                        setTimeout(function() {
                            n1.close();
                        }, 1500);
                    }
                }]
            }); //Noty
        },
        getPhotos: function(albumid) {
            var self = this;
            $.get(site_url + "components/album/processors/album/PhotoAlbum.php?action=listphotos&albumid=" + albumid, function(d) {
                d = $.parseJSON(d);
                if (d.status === "success") {
                    $loader.hide();
                    $content.show();
                    d.data.albumid = albumid;
                    self.buildPhotosGrid(d.data);
                } else {
                    $loader.hide();
                    $content.show();
                    $albums.html("The Album does not have any images please upload some images.");
                }
            });
        },
        buildPhotosGrid: function(d) {
            var self = this;
            html = "";
            for (var i = 0; i < d.length; i++) {
                html +=
                    '<li>' +
                    '	<a class="photo_main" href="' + site_url + 'm/photos/get_image/file/' + d[i].Hash + '.' + d[i].Ext + '" data-albumid="' + d.albumid + '" id="' + d[i].ID + '" rel="prettyPhoto[Album]" title="' + d[i].titleEnc + '">' +
                    ' <div class="fc-init sys_file_search_pic bx_photos_file_search_pic" style="width: 100%;background-size: contain;background-image: url(' + site_url + 'm/photos/get_image/browse/' + d[i].Hash + '.' + d[i].Ext +'?'+Math.random()+ ')" ></div>' +
                    //'   <img src="' + site_url + 'm/photos/get_image/browse/' + d[i].Hash + '.' + d[i].Ext + '" height="150px" width="200px" class="fc-init" >' +
                    ' </a>' +
                    '	<span class="tp-info" style="width:175px;">' +
                    '		<span class="edit" albumid="' + d[i].ID + '" title="Click to edit...">' + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + '</span>' +
                    '	</span>' +
                    '	<div class="tools">' +
                    '		<i class="fa fa-comments ">' + d[i].CommentsCount + '</i>' +
                    '		<i class="fa fa-eye ">' + d[i].Views + '</i>' +
                    '		<a class="fa fa-pencil-square-o crop-tool" data-photo-id="' + d[i].ID + '"  data-album-id=" " href="' + site_url + 'components/album/crop.php?id=' + d[i].ID + '&img=' + site_url + '/modules/boonex/photos/data/files/' + d[i].ID + '.jpg&iframe=crop&width=100%%&height=100%" rel="prettyPhoto"></a>' +
                    '       <a class="fa fa-pencil-square-o edit-tool" data-photo-id="' + d[i].ID + '"  data-album-id=" "></a>' +                    
                    //'       <img id="fake_' + d[i].ID + '" src="' + site_url + '/modules/boonex/photos/data/files/' + d[i].ID + '.jpg" />' +
                    '		<i class="fa fa-trash-o delete"></i>&nbsp;&nbsp;' +
                    '		<i class="fa fa-arrows"></i>' +
                    '	</div>' +
                    '<div class="grd-priv">' + d[i].Status + '</div>' +
                    '</li>';
            }
            $albums.html(html);
            this.makeSortable($albums);
            $photoEdit = $content.find('li').find('.edit');
            self.editTitle("photoTitle");
            self.loadCropTool();
            this.photoGridEvents();
        },
        loadCropTool: function() {
            var self = this;
            featherEditor = new Aviary.Feather({
               apiKey: 'fd418edeb42a8eca',
               apiVersion: 3,
               theme: 'light', // Check out our new 'light' and 'dark' themes!
               tools: 'all',
               appendTo: '',
		 onReady: function() {
                    window.dispatchEvent(new Event('resize'));
                },
               onSave: function(imageID, newURL) {
                                var photoID = imageID.split('_');
                                $.post(site_url + "components/album/processors/editImage/saveEditedImage.php", {
                                    postdata: photoID[1],
                                    url: newURL
                                });
				$($('#' + imageID.replace('fake_', '')).find('div')[0]).css('background-image','url('+newURL+'?'+Math.random()+')')

               },
               onError: function(errorObj) {
                   alert(errorObj.message);
               }
            });
        },
        photoGridEvents: function() {
            var self = this;
            $('li a[rel^="prettyPhoto"]').prettyPhoto({
                animation_speed: 'normal',
                theme: 'light_square',
                slideshow: 3000,
                social_tools: '',
                markup: self.prettyPhotoMarkup(),
                changepicturecallback: function($pp_pic_holder) {
                    window._pptype = window._pptype || "frame";
                    var photoID = $pp_pic_holder.find("#pp_full_res img").attr("rel");
                    if ($("#fullResImage").length == 0) {
                        var iframeWidth = $pp_pic_holder.find("#pp_full_res iframe").width();
                        $pp_pic_holder.find("#pp_full_res iframe").width(iframeWidth - 250);
                        if (_pptype == "crop") {
                            $pp_pic_holder.find("#pp_full_res iframe").width('100%');
                        }
                        //console.log($("#pp_full_res").width());
                    }
                    $.post(site_url + "components/album/processors/album/PhotoAlbum.php?action=changeViewCount", {
                        photoid: photoID
                    });
                    $("#comments").hide();
                    $(".aside").show();
                    if (_pptype == "crop") {
                        $(".aside").width(0);
                    } else {
                        $(".aside").width(250);
                        self.loadComments(photoID);
                    }
                    //new loadcomments function added
                }
            });
            $('.delete').off('click');
            $('.delete').on('click', function(e) {
                e.preventDefault();
                self.remove($(this));
            });
            // $('.albums').find('li').find('img').on('load', function() {
                $('.edit-tool').on('click', function(e) {                    
                    e.preventDefault();
                    $this = $(this);
                    var album_id = $this, photo_id = $this.attr('data-photo-id');
                    var image_url = site_url + 'modules/boonex/photos/data/files/' + photo_id + '.jpg';
                    var fakeID = 'fake_' + photo_id;
                    var $fakeimage = $("<img src='" + image_url + "' id='" + fakeID + "' />").insertBefore($('#photoResult')).hide();
                    console.log(image_url);
                    console.log(fakeID);
                    console.log($fakeimage);
                    featherEditor.launch({
                        image: fakeID,
                        url: image_url,
                        postUrl: site_url+'components/album/processors/editImage/saveEditedImage.php',
                        postData: photo_id
                    });
                    return false;
                });
            // });

        },
        callFeather: function($this) {
            var album_id = $this,
                photo_id = $this.attr('data-photo-id');
            var image_url = site_url + 'modules/boonex/photos/data/files/' + photo_id + '.jpg';
            var fakeID = 'fake_' + photo_id;
            var $fakeimage = $("<img src='" + image_url + "' id='" + fakeID + "' />").insertBefore($('#footer_content')).hide();
            featherEditor.launch({
                image: fakeID,
                url: image_url,
                // postUrl: 'http://devlocal.parentfinder.com/components/album/processors/crop/crop.php',
                // postData: photo_id
            });
        },
        prettyPhotoMarkup: function() {
            var html =
                '<div class="pp_pic_holder">' +
                '  <div class="ppt">&nbsp;</div>' +
                '  <div class="pp_top">' +
                '    <div class="pp_left"></div>' +
                '    <div class="pp_middle"></div>' +
                '    <div class="pp_right">' +
                '    </div>' +
                '  </div>' +
                '  <div class="pp_content_container">' +
                '    <div class="pp_left">' +
                '      <div class="pp_right">' +
                '        <div class="pp_content">' +
                '          <div class="aside">' +
                '            <a class="pp_close" href="#">Close</a> ' +
                '            <p class="pp_description"></p>' +
                '            <div id="comments" class="scroller"></div>' +
                '          </div>' +
                '          <div class="pp_loaderIcon"></div>' +
                '          <div class="pp_fade">' +
                '            <div class="pp_hoverContainer">' +
                '              <a href="#" class="pp_expand" title="Expand the image">Expand</a>' +
                '              <a class="pp_next" href="#">next</a> ' +
                '              <a class="pp_previous" href="#">previous</a> ' +
                '            </div>' +
                '            <div id="pp_full_res"></div>' +
                '            <div class="pp_details">' +
                '              <div class="pp_nav">' +
                '                <a href="#" class="pp_arrow_previous">Previous</a> ' +
                '                <p class="currentTextHolder">0/0</p>' +
                '                <a href="#" class="pp_arrow_next">Next</a> ' +
                '              </div>' +
                '              {pp_social}' +
                '            </div>' +
                '          </div>' +
                '        </div>' +
                '      </div>' +
                '    </div>' +
                '  </div>' +
                '</div>' +
                '<div class="pp_overlay"></div>';

            return html;
        },
        loadComments: function(fileID) {

            $.getScript(site_url + "components/album/js/comments.js", function() {
                Comments.init($("#comments"), fileID, "photos");
                $("#comments").fadeIn()
                    .slimScroll({
                        height: '84%',
                        scrollTo: $('.comment-box').height(),

                    });
            });
        },
        makeSortable: function(el) {
            /* el.sortable({
                handle: '.fa-arrows'
            }).unbind('sortupdate'); */
            //$(document).unbind("sortupdate");
            el.sortable({
                handle: '.fa-arrows'
            }).bind('sortupdate', function() {
                var listSort = [];
               el.find('.photo_main').each(function(index) {
                    listSort.push({
                        idFileObject: $(this).attr('id'),
                        seqNumber: index
                    });
                });             
                $.post(site_url + "components/album/processors/album/PhotoAlbum.php?action=sortAlbum", {
                //"id": albumID,
                order: listSort,
                userID: "userID"                        
                })
                /*$.post("#OrderPositionURL", {
                    order: JSON.stringify(listSort),
                    userID: "userID"
                })*/
               .done(function(data) {
                    //console.log(listSort);
                    noty({
                        dismissQueue: true,
                        force: true,
                        layout: 'center',
                        theme: 'defaultTheme',
                        text: 'Position of the photos saved successfully!',
                        type: 'success',
                        timeout: 1500
                    });
                }).fail(function() {
                    alert("Error saving the new position!");
                });
            });
        },
        editTitle: function(field) {
            var editNode = "",
                url = site_url + "components/album/processors/album/PhotoAlbum.php?action=edit";
            if (field === "albumName") {
                editNode = $albumEdit;
                url = site_url + "components/album/processors/album/PhotoAlbum.php?action=edit";
            } else if (field === "file_desc") {
                editNode = $albumEdit;
                url = site_url + "components/album/processors/album/PhotoAlbum.php?action=edit";
            } else if (field === "photoTitle") {
                editNode = $photoEdit;
                url = site_url + "components/album/processors/album/PhotoAlbum.php?action=editPhoto";
            }
            // console.log(editNode);
            $('.edit').click(function() {
                var parentSpan = $(this).parent();
                if(field === "photoTitle")
                    parentSpan.css('top','35px');
                else
                    parentSpan.css('top','50px');
            });
            editNode.editable(url, {
                indicator: 'Saving...',
                type      : "charcounter",
                submit: 'Save',
                tooltip: "Click to edit...",
                // event: "dblclick",
                style: 'display: inline',
                width: '140',
                height: '30',
                onblur: 'ignore',
                charcounter : {
                   characters : 40
                },
                callback : function(value, settings){
                    if(field === "photoTitle") {
                        $($(this).closest('li').find('span')[0]).css('top','68px');
                    }
                    else {
                        $($(this).closest('li').find('span')[0]).css('top','78px');
                        $(this).closest('li').find('img').attr('alt',value)
                    }
                },
                onreset : function(value, settings){
                    if(field === "photoTitle")
                        $($(this).closest('li').find('span')[0]).css('top','68px');
                    else
                        $($(this).closest('li').find('span')[0]).css('top','78px');
                },
                submitdata: function(value, settings) {
                    return {
                        id: $(this).attr('albumid'),
                    };
                }
            });
        },
        privacy: function($this) {
            var albumID = $this.attr("data-albumid");
            var action_text = "Are you sure you want to change this Album's privacy status?";
            var n = noty({
                text: action_text,
                type: 'alert',
                dismissQueue: true,
                layout: 'center',
                theme: 'defaultTheme',
                modal: true,
                timeout: true,
                buttons: [{
                    addClass: 'teal-btn',
                    text: 'Yes',
                    onClick: function($noty) {
                        $noty.close();
                        $.post(site_url + "components/album/processors/album/PhotoAlbum.php?action=editAlbum", {
                            "id": albumID,
                            "privacy": $this.val()
                        }).done(function(data) {
                            var n1 = noty({
                                dismissQueue: true,
                                force: true,
                                layout: 'center',
                                theme: 'defaultTheme',
                                text: 'Album privacy changed successfully!',
                                type: 'success'
                            });
                            setTimeout(function() {
                                n1.close();
                            }, 2500);
                        });
                    }
                }, {
                    addClass: 'pink-btn',
                    text: 'No',
                    onClick: function($noty) {
                        $noty.close();
                        var n1 = noty({
                            dismissQueue: true,
                            force: true,
                            layout: 'center',
                            theme: 'defaultTheme',
                            text: 'Action canceled, the Lifebook was not ' + action + 'ed!',
                            type: 'error'
                        });
                        setTimeout(function() {
                            n1.close();
                        }, 1500);
                    }
                }]
            }); //Noty

            var pubstatus = (action === "Publish") ? 3 : 4;
            this.buildAlbumView(albumID, $this.val());
            this.getPhotos(albumID);
        },
        publish: function($this) {
            var albumID = $this.data("id");

            if ($this.find("i").hasClass("fa-arrow-circle-o-down")) {
                var action = "unPublish";
                var action_text = "Are you sure you want to <b>Un Publish</b> the Album?";
            } else {
                var action = "Publish";
                var action_text = "Are you sure you want to <b>Publish</b> this Album?";
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
                    addClass: 'teal-btn',
                    text: 'Yes',
                    onClick: function($noty) {

                        if (action == "unPublish") {
                            $noty.close();
                            $.post(site_url + "components/album/processors/album/PhotoAlbum.php?action=editAlbum", {
                                "id": albumID,
                                "action": action,
                            })
                                .done(function(data) {
                                    var n1 = noty({
                                        dismissQueue: true,
                                        force: true,
                                        layout: 'center',
                                        theme: 'defaultTheme',
                                        text: 'Album ' + action + 'ed successfully!',
                                        type: 'success'
                                    });
                                    setTimeout(function() {
                                        n1.close();
                                    }, 2500);
                                });
                        } else { //if unPublish
                            $noty.close();
                            $.post(site_url + "components/album/processors/album/PhotoAlbum.php?action=editAlbum", {
                                "id": albumID,
                                "action": action
                            })
                                .done(function(data) {
                                    var n1 = noty({
                                        dismissQueue: true,
                                        force: true,
                                        layout: 'center',
                                        theme: 'defaultTheme',
                                        text: 'Album ' + action + 'ed successfully!',
                                        type: 'success'
                                    });
                                    setTimeout(function() {
                                        n1.close();
                                    }, 2500);
                                });
                        }
                    }
                }, {
                    addClass: 'pink-btn',
                    text: 'No',
                    onClick: function($noty) {
                        $noty.close();
                        var n1 = noty({
                            dismissQueue: true,
                            force: true,
                            layout: 'center',
                            theme: 'defaultTheme',
                            text: 'Action canceled, the Lifebook was not ' + action + 'ed!',
                            type: 'error'
                        });
                        setTimeout(function() {
                            n1.close();
                        }, 1500);
                    }
                }]
            }); //Noty

            var pubstatus = (action === "Publish") ? 3 : 4;
            this.buildAlbumView(albumID, pubstatus);
            this.getPhotos(albumID);
        },
        closeTheIFrameImDone: function() {
            var albumid = $('#album-privacy').attr('data-albumid');
            this.getPhotos(albumid);
            $(".pp_close").click();
        }
    }
}());