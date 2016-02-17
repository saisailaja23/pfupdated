var videoAlbum = (function() {
    $.ajaxSetup({
        cache: false
    });
    var
            $videoResult = "",accessToken ='',
            $wrap, $topbar, $loader, $close, $content, $name, $albumID,
            $albumCount, $grid, $addAlbum, $albums, $albumEdit, $videoEdit,
            $images, $close, $controls;
    return {
        
        init: function (pr) {
            $videoResult = pr;
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
                $grid.html("<div>Your current membership basic doesn't allow you to access videos.</div><div>Please upgrade <u><a href='extra_member.php'>here</a></u></div>");
                return;
            }
            $.get(site_url + "components/album/processors/album/VideoAlbum.php?action=list", function(d) {
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
        buildAlbumGrid: function (data) {
            var i, html = "", img;
            for (i = 0; i < data.length; i++) {
                var editClass = (decodeURIComponent(data[i].Caption) === "Home Videos") ? '' : 'edit';
                var view = (data[i].Views === null) ? 0 : data[i].Views;
                var status = (data[i].Status === "active") ? "active " : "pending ";
                status += (data[i].AllowAlbumView === 3) ? "published" : "published";
                if (data[i].YoutubeLink == 1)
        	{
          		//img = !data[i].YoutubeLink ? site_url + 'templates/tmpl_par/images/no-img.jpg' : 'http://img.youtube.com/vi/' + data[i]['youtubeCode'] + '/0.jpg';
          		img = 'http://img.youtube.com/vi/' + data[i]['Uri'] + '/0.jpg';
          		//console.log("youtube link is 1");
        	}
        	else
        	{
          		img = !data[i].Hash ? site_url + 'templates/tmpl_par/images/no-img.jpg' : site_url + 'flash/modules/video/files/_' + data[i].Hash + '_small.jpg';
          		//console.log("youtube link is 0");
        	}
		//var img = !data[i].Hash ? site_url + 'templates/tmpl_par/images/no-img.jpg' : site_url + 'flash/modules/video/files/_' + data[i].Hash + '_small.jpg';
                html +=
                        '<li rel="' + status + '" >' +
                        '	<span class="tp-info">' +
                        '   <span class="' + editClass + '" albumid="' + data[i].ID + '" title="' + decodeURIComponent(data[i].Caption) + '">' + decodeURIComponent(data[i].Caption) + '</span>' +
                        //'		<span class="lb-views">' + view + '</span>' +
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
                    '		<h2>Video Album</h2> <span id="name" style="font-size: 19px;float: right;color: #aaa;margin-top: 2px;"></span>' +
                    '	</div> <!-- LifeBook Grid -->' +
                    ' <div class="loader2"><i></i><i></i><i></i><i></i><i></i><i></i><span>Loading...</span></div>' +
                    ' <div id="content" style="display: none;">';
            if (mem != 25) { // This condition was written by Satya to show a message instead of Album for membership type 25. instead of Album
                sHtml +=
                        '   <ul class="filter-menu">' +
                        '     <li class="plain-btn"><a href="#" rel="all">All</a> </li>' +
                        '     <li><a href="#" rel="published">Published</a> </li>' +
                        '     <li><a href="#" rel="unpublished">Private</a> </li>' +
                        '     <li><a href="#" rel="active">Active</a> </li>' +
                        '     <li><a href="#" rel="pending">Pending Review</a> </li>' +
                        '   </ul>' +
                        '   <div id="lifebooksCont"> <span>0</span> Albums</div>' +
                        '   <a href="#" class="teal-btn addAlbum" title="Create a New Album.">' +
                        '     <i class="fa fa-plus"></i> Create Album' +
                        '   </a>';
            }
            sHtml +=
                    '		<ul id="lb-grid" ></ul>' +
                    '	</div> <!-- #content-->' +
                    '</div>';
            $videoResult.html(sHtml);
            $videoResult.slideDown();
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
                    //  '  <option value="2">Private</option>' +
                    '  <option value="4">Member</option>' +
                    '  <option value="3">Public</option>' +
                    '</select><br />' +
                    '<label>Description</label><br>' +
                    '<textarea id="lb-desc"></textarea><br>';
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
                                        $.get(site_url + "components/album/processors/album/VideoAlbum.php", {
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
                if (charactersLeft <= 0) {
                    $('#albmCnt').html('(0 characters remaining)');
                    $('#albmCnt').css('cssText', 'color:red !important');
                    $(this).val($(this).val().substr(0, 40));
                }
                else {
                    $('#albmCnt').html('(' + charactersLeft + ' characters remaining)');
                    $('#albmCnt').css('cssText', 'color:#76787b !important');
                }
            });
        },
        filterAlbums: function($this) {
            $filterMenu.find('li').removeClass('plain-btn');
            $this.parent('li').addClass('plain-btn');
            var thisItem = $this.attr('rel');

            if (thisItem != "all") {
                $albumCount.text($grid.find('li[rel*=' + thisItem + ']').length); //Update number of albums

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
            this.getVideos(albumID);
        },
        youtubeAccessToken: function(callback){
            var self = this,accessToken = '';
            postStr = "i=2";
            dhtmlxAjax.post(site_url + "YoutubeLoginComponent/processors/getAccessToken.php", postStr, function(loader) {
                var json = JSON.parse(loader.xmlDoc.responseText);
//                console.log(json);
                if (json.status == "success") {
                    self.accessToken = json.response;
                    if(callback)
                        callback();
                }                  
                try {
                } catch (e) {
                    dhtmlx.message({
                        type: "error",
                        text: "Fatal error on server side: " + loader.xmlDoc.responseText
                    });
                    // console.log(e.stack);
                }
            });
            return accessToken;
        },
        buildAlbumView: function (id, albumName, pubstatus) {
            var self = this;
            self.youtubeAccessToken(function() {     
                if(self.accessToken){                   
                    $('.serverUpload').remove();  
                    $(".youtubeUploadCh").show(); 
                }
            });;
            var serverUpload = ' <a style="float:left;" href="' + site_url + 'components/album/processors/uploadFiles.php?type=video&albumID=' + id + '&amp;iframe=true&amp;width=80%&amp;height=420" data-id="23" rel="prettyPhoto" class="pink-btn serverUpload" title="Upload files."><i class="fa fa-upload"></i> Upload Files</a>';
            var delStr = ' <a href="#" style="float:left;margin-left:25px;" class="pink-btn delete" data-id="' + id + '" title="Delete Album."><i class="fa fa-trash-o "></i> Delete Album</a>';
            var ytUpload = ' <span style="float:left;margin-left:25px; cusrsor:pointer;" class="pink-btn youtube" data-id="' + id + '" title="Link your Youtube videos"><i class="fa fa-youtube-play"></i> Add Youtube Video</span>';
//            var ytLogout = ' <span style="float:left;margin-left:25px; cusrsor:pointer;" class="pink-btn youtubeLogout" data-id="' + id + '" title="Link your Youtube videos"><i class="fa fa-youtube-play"></i> Youtube Logout</span>';
            var ytUploadChannel = ' <span style="float:left;margin-left:25px; cusrsor:pointer;" class="pink-btn youtubeUploadCh" data-id="' + id + '" title="Upload videos to your Youtube channel"><i class="fa fa-youtube-play"></i> Upload Files</span>';
            if (albumName === "Home Videos") {
                delStr = "";
            }
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
//                    ' <a style="float:left;" href="' + site_url + 'components/album/processors/uploadFiles.php?type=video&albumID=' + id + '&amp;iframe=true&amp;width=80%&amp;height=420" data-id="23" rel="prettyPhoto" class="pink-btn" title="Upload files."><i class="fa fa-upload"></i> Upload Files</a>' +
                    ytUploadChannel + serverUpload + delStr + pubHtml + ytUpload + 
//                    ((token_flag == 1) ? ytLogout : '') +
                    '</span>' +
                '<div class = "ytStausMessages"></div><ul class="albums"></ul>';
            $content.html(html);
            if(!self.accessToken){
                $(".youtubeUploadCh").hide(); 
            }
            $controls = $('.control');
            $albums = $('.albums');
            this.buildAlbumViewEvents(id, albumName);
        },
        buildAlbumViewEvents: function(id, albumName) {
            var self = this,
                    lastClicked = "";
            $('.control a[rel^="prettyPhoto"]').prettyPhoto({
                social_tools: '',
                animation_speed: 'normal',
                theme: 'light_square',
                slideshow: 3000,
                show_title: false,
                callback: function(e) {
                    var btn = $(lastClicked).text();
                    if (btn == " Upload Files") {
                        self.getVideos($albumID);
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
            $('.youtube').off('click');
            $('.youtube').on('click', function(e) {
                e.preventDefault();
                self.addYoutube(id, albumName);
            });
            $('.youtubeLogout').off('click');
            $('.youtubeLogout').on('click', function(e) {
                e.preventDefault();
                self.youtubeLogout();
            });
            $('.youtubeUploadCh').off('click');
            $('.youtubeUploadCh').on('click', function (e) {
                e.preventDefault();
                self.youtubeUploadCh($albumID);
            });
        },
        remove: function($this) {
            var self = this, type = "",
                    video,
                    videoID = "",
                    albumID = "";
            if ($this.attr("data-id")) {
                albumID = $this.attr("data-id");
                type = "album";
            } else {

                video = $this.closest("li");
                videoID = $(video[0]).find("a").attr("id");
                albumID = $(video[0]).find("a").attr("data-albumid");
                type = "video";
                // var el = $(video[0]).parent().find("a");
                //console.log(el);
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
                                    args = {},
					el = $('.albums');
                            if (type === "album") {
                                url = site_url + "components/album/processors/album/VideoAlbum.php?action=removeAlbum&albumid=" + albumID;
                            } else {
                                url = site_url + "components/album/processors/album/VideoAlbum.php?action=removeVideo&albumid=" + albumID + "&videoid=" + videoID;
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
                                   	video.remove();
                  			var el = $('.albums');
                  			//console.log(el.find('a'));
                  			self.saveSort(el);
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
            }); // Noty
        },
        youtubeUploadCh: function (id) {            
            var self = this;            
            if(self.accessToken) {
                addWinObj = new dhtmlXWindows();
                addWinObj.setImagePath('../dhtmlxfull3.5/imgs/dhtmlxtoolbar_imgs/');
                addWinYt = addWinObj.createWindow('addYTWin', '0', '0', 700, 500);
                addWinYt.setModal(true);
                addWinYt.button('park').hide()
                addWinYt.button('minmax1').hide()
                addWinYt.setText('');
                addWinYt.denyMove(true);
                self.dhtmlxWidowCustomPostion(addWinYt, true, '', true);
                addWinYt.attachEvent("onClose", function (win) {
                    delete addWinYt;
                    return true;
                });
                addLinkLayout = addWinYt.attachLayout('1C');
                addLinkLayout.cells('a').hideHeader();

                var buttonText = 'Login to your Youtube account';
                var offsetLeft = '230';
//                if (token_flag == 1) {
//                    buttonText = 'Select your Youtube videos';
//                    offsetLeft = '252';
//                }
                addLinkLayout.cells('a').attachURL(site_url+"uploadYoutube.php?albumID="+id, true);
//                var addLinkForm = addLinkLayout.cells('a').attachForm([{
//                        type: "label",
//                        name: "form_label_1",
//                        label: "Add your youtube video link in the below input field"
//                    }, {
//                        type: "upload",
//                        name: "youtubeUpload",
//                        inputWidth: 280,
//                        mode: "html4",
//                        _swfLogs: "enabled",
//                        autoStart: true,
//                        url: "https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet",
//                        swfPath: "../../../plugins/dhtmlx/dhtmlxForm/codebase/ext/uploader.swf",
//                        swfUrl: "https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet"
//                      }
//                  ]);
//                addLinkForm.attachEvent("onFileAdd",function(realName){
//                    var youtubeUpload = addLinkForm.getUploader('youtubeUpload');
//                    debugger;
//                });
            }
        },
        addYoutube: function(id, albumName) {
            var self = this;
            var addWinObj = new dhtmlXWindows();
            addWinObj.setImagePath('../dhtmlxfull3.5/imgs/dhtmlxtoolbar_imgs/');
            var addWin = addWinObj.createWindow('addYTWin', '0', '0', 500, 250);
            addWin.setModal(true);
            addWin.button('park').hide()
            addWin.button('minmax1').hide()
            addWin.setText('');
            addWin.denyMove(true);
            self.dhtmlxWidowCustomPostion(addWin, true, '', true);
            addWin.attachEvent("onClose", function(win) {
                delete addWin;
                return true;
            });
            var addLinkLayout = addWin.attachLayout('1C');
            addLinkLayout.cells('a').hideHeader();
            var addLinkForm = addLinkLayout.cells('a').attachForm([{
                    type: "label",
                    name: "form_label_1",
                    label: "Add your youtube video link in the below input field"
                }, {
                    type: "input",
                    name: "videoname",
                    label: 'Video Name',
                    labelWidth: '80',
                    offsetLeft: "35",
                    offsetTop: "30",
                    inputWidth: 330
                }, {
                    type: "input",
                    name: "youtubelink",
                    label: 'Youtube URL',
                    labelWidth: '80',
                    offsetLeft: "35",
                    offsetTop: "15",
                    inputWidth: 330
                }, {
                    type: "label",
                    name: "example",
                    label: '<span style="color:#76787b;font-weight:lighter;">Ex: https://www.youtube.com/watch?v=GXge4Vf1yJM</span>',
                    offsetLeft: "112",
                    offsetTop: "4",
                    inputWidth: 330
                }, {
                    type: 'button',
                    name: 'saveLink',
                    value: 'Save',
                    offsetLeft: "113",
                    offsetTop: "15",
                }
//                , {
//                    type: 'button',
//                    name: 'loginYoutube',
//                    value: buttonText,
//                    offsetLeft: offsetLeft,
//                    inputTop: '158',
//                    position: 'absolute'
//                }
            ]);

            addLinkForm.attachEvent('onButtonClick', function(name) {
                if (name == 'saveLink') {
                var url = addLinkForm.getItemValue("youtubelink");
                if (url != '') {
                    var params = 'url=' + url + '&filename=' + addLinkForm.getItemValue("videoname") + '&albumID=' + id;
                    dhtmlxAjax.post(site_url + "components/album/processors/saveYoutubeLink.php", params, function(loader) {
                        var data = loader.xmlDoc.responseText;
                        if (data > 0) {
                            addWin.close();
                            self.getVideos(id);
                        }
                    });
                }
                }
//                else if (name == 'loginYoutube') {
//                    if (token_flag == 1) {
//                        addWin.close();
//                      self.selectYoutubeVideos(id, albumName);
//                    }
//                    else
//                      window.location.assign("https://accounts.google.com/o/oauth2/auth?client_id=303988347715-1g1lbd3hdoup9neb4l00aclu39289n3c.apps.googleusercontent.com&approval_prompt=auto&redirect_uri="+siteurl+"oauth2callback.php&scope=https://www.googleapis.com/auth/youtube&response_type=code&access_type=offline");
//                }
            });
        },
        selectYoutubeVideos: function(id, albumName) {
            var self = this;
            var addWinObj = new dhtmlXWindows();
            addWinObj.setImagePath('../dhtmlxfull3.5/imgs/dhtmlxtoolbar_imgs/');
            var addWin = addWinObj.createWindow('addYTWin', '0', '0', 690, 530);
            addWin.setModal(true);
            addWin.button('park').hide()
            addWin.button('minmax1').hide()
            addWin.setText('');
            addWin.denyMove(true);
            self.dhtmlxWidowCustomPostion(addWin, true, '', true);
            addWin.attachEvent("onClose", function(win) {
                delete addWin;
                return true;
            });
            var addLinkLayout = addWin.attachLayout('1C');
            addLinkLayout.cells('a').hideHeader();

            var addLinkForm = addLinkLayout.cells('a').attachForm([
                {type: "settings"},
                {type: "block", className: "dataButtons", list: [
                {type: "newcolumn"},
                {type: "button", name: "save", label: "Save", value: "Save", width: "100", offsetLeft: 507, offsetTop: '27', position: 'absolute'},
                {type: "newcolumn"},
                {type: "button", name: "select", label: "Select All", value: "Select All", width: "100", offsetTop: '27', offsetLeft: 350, position: 'absolute'},
                ]},
                {type: "block", className: "dataContainer", list: [
                    {type: "container", name: "data_container", className: "dContainer", label: "", inputWidth: 640, inputHeight: 380, offsetTop: '80', position: 'absolute'}
                ]}
            ]);
            $('.dhxform_container').css('overflow-y', 'scroll !important');
            var videoView = new dhtmlXDataView({
                container: addLinkForm.getContainer("data_container"),
                // height: "auto",
                type: {
                    template: "<div class='YtVideo'>\n\
                                <div class='videoCheck' style='float:left;'>\n\
                                    <label><input type='checkbox' value='{obj.video_id}' name='{obj.video_id}' video-name='{obj.video_title}' class='selectThisVideo'/></label>\n\
                                </div>\n\
                                <div class='videoData' style='float:right;'>\n\
                                    <div>{obj.video_thumbnail}</div>\n\
                                    <div class=''>{obj.video_title}</div>\n\
                                </div>\n\
                            </div>",
                    width: 138, // width of single item
                    //                height: 280, // height of single item
                    padding: 0,
                    margin: 8
                }
            });
            videoView.load(siteurl + "getChannelList.php", 'json', function(data) {
                if (JSON.parse(data).length <= 12) {
                    addWin.setDimension('640', '530');
                }

            });
            videoView.attachEvent("onBeforeSelect", function(id, state) {
                return false;
            });
            addLinkForm.attachEvent("onButtonClick", function(name) {
                if (name == 'save') {
                    var selctedCount = $("input:checked").length;
                  var selectedIds = '', selectedVideoName = '';

                    if (selctedCount > 1) {
                        for (var i = 0; i < selctedCount; i++) {
                            if (i == selctedCount - 1)
                                selectedIds += $("input:checked")[i].value;
                            else
                                selectedIds += $("input:checked")[i].value + ',';

                            if (i == selctedCount - 1)
                                selectedVideoName += $($("input:checked")[i]).attr('video-name');
                            else
                                selectedVideoName += $($("input:checked")[i]).attr('video-name') + ',';
                        }
                    var params = 'url='+selectedIds+'&filename='+selectedVideoName+'&albumID='+id;
                    dhtmlxAjax.post(site_url + "components/album/processors/youtubeSelect.php", params, function (loader) {
                        var data = loader.xmlDoc.responseText;
                        if (data > 0) {
                            addWin.close();
                            self.getVideos(id);
                        }
                    });
                }
                  else{
                    dhtmlx.alert("You should select atleast one video");
                    addWin.open();
                  }                  
                }
                if(name == 'select') {
                    if($("input:checked").length < $('.selectThisVideo').length) {
                        $('.selectThisVideo').attr('checked', 'checked');
                        addLinkForm.setItemLabel("select", "Deselect All");
                    }
                    else {
                        $('.selectThisVideo').removeAttr('checked');
                        addLinkForm.setItemLabel("select", "Select All");
                    }
                }
            });
        },
        dhtmlxWidowCustomPostion: function(widowObj, centerOnScreen, yPosition, scrolltop) {
            if (centerOnScreen == true)
                widowObj.centerOnScreen();
            else
                widowObj.center();
            var position = widowObj.getPosition();
            var splitPosition = String(position).split(",");
            var xPosition = splitPosition[0];
            if (xPosition < 0)
                xPosition = 50;
            widowObj.setPosition(xPosition, yPosition);
            if (scrolltop == true)
                document.body.scrollTop = 0;
        },
        getVideos: function(albumid) {
            var self = this;
            $.get(site_url + "components/album/processors/album/VideoAlbum.php?action=listvideos&albumid=" + albumid, function(d) {
                d = $.parseJSON(d);
                if (d.status === "success") {
                    $loader.hide();
                    $content.show();
                    d.data.albumid = albumid;
                    self.buildVideosGrid(d.data);
                } else {
                    $loader.hide();
                    $content.show();
                    $albums.html("The Album does not have any videos please upload some videos.");
                    $('.ytStausMessages').hide();
                }
            });
        },
        buildVideosGrid: function(d) {
            var self = this,imageURL;
            html = "", ytmessages = "<h1>YouTube videos status messages</h1>";
            fav_video = "";
                
            for (var i = 0; i < d.length; i++) {
                
                if(d[i]['ytStatus'] != 'processed' && d[i]['ytStatusFlag'] == 0){                    
                        uploadstatus = d[i]['ytStatus'].split('/');
                        if(uploadstatus[1]){
                            ytmessages += "<span>The video " + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + " is " + uploadstatus[0] + " because it is " + uploadstatus[1] + "</span>";
                        }
                        else if(uploadstatus[0] == 'removed'){
                            ytmessages += "<span>The video " + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + " is " + d[i]['ytStatus'] + " by the agency</span>";
                        }
                        else{     
                            ytmessages += "<span>The video " + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + " is still processing</span>";
                        }
                    
                }
                else{
                    if(d[i]['ytStatus'] == 'processed'){
                        if( d[i]['ytStatusFlag'] == 0)
                            ytmessages += "<span>The video " + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + " is " + d[i]['ytStatus'] + " successfully</span>";
                    
                if (d[i]['YoutubeLink'] == 1) {
                    imageURL = 'http://img.youtube.com/vi/' + d[i]['youtubeCode'] + '/0.jpg';
                        } else {
                    imageURL = site_url + 'flash/modules/video/files/_' + d[i].ID + '_small.jpg';
                }

                //fav_video = d[i].YoutubeLink == 1? '   <i class="fa fa-star fav_video" style="color:red"></i>':'';

                if (d[i].YoutubeLink == 1) {
	                if (d[i].featured == 24 || d[i].featured == 15 || d[i].featured == 14) {
					if(d[i].caption == 'Home Videos' || d[i].caption == 'home videos' || d[i].caption == 'Home videos' || d[i].caption == 'home Videos'){
						fav_video = '   <i class="fa fa-star fav_video"></i>';
						if (d[i].home == 1) {
							fav_video = '   <i class="fa fa-star fav_video marked" style="color:red"></i>'
						}
					}
			}
                } else {
                    fav_video = '';
                }

                html +=
                        '<li>' +
                            ' <a href="' + d[i].videoURL + '?width=864&amp;height=486" data-albumid="' + d.albumid + '" id="' + d[i].ID + '" rel="prettyPhoto[Album]" title="' + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + '">' +
                            '   <img src="' + imageURL + '" height="150px" width="200px" class="fc-init" >' +
                            ' </a>' +
                            ' <span class="tp-info" style="width:175px;">' +
                            '   <span class="edit" albumid="' + d[i].ID + '" title="Click to edit...">' + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + '</span>' +
                            ' </span>' +
                            ' <div class="tools">' +
                            '   <i class="fa fa-comments ">' + d[i].CommentsCount + '&nbsp;&nbsp;</i>' +
                            '   <i class="fa fa-eye ">' + d[i].Views + '&nbsp;&nbsp;</i>' +
                            '   <i class="fa fa-trash-o delete"></i>&nbsp;&nbsp;' +
                            '   <i class="fa fa-arrows "></i>' +
			fav_video +
                            ' </div>' +
                        '<div class="grd-priv">' + d[i].Status + '</div>' +
                        '</li>';
            }
                }
            }
            $albums.html(html);
            if(ytmessages != '<h1>YouTube videos status messages</h1>'){
                $('.ytStausMessages').html(ytmessages);
            }
            else{
                $('.ytStausMessages').hide();
            }
            this.makeSortable($albums);
            $videoEdit = $content.find('li').find('.edit');
            self.editTitle("videoTitle");
            this.videoGridEvents();
		var el = $('.albums');
      		self.saveSort(el);
        },
        videoGridEvents: function() {
            var self = this;
            $('.fa-pencil-square-o').on('click', function() {
                var el = $(this).parent().parent().find("a");
                var url = document.location + el.attr("href");
                var id = el.attr("id");
                var fakeID = id + "_fake";
                var fakeimageURL = url.replace("https", "http");
                var $fakeimage = $("<img src='" + fakeimageURL + "' id='" + fakeID + "' />").insertBefore($content).hide();
            });
            // $('.albums li img').fakecrop();
            $('li a[rel^="prettyPhoto"]').each(function() {
                var fileID = $(this).attr('id')
                $(this).prettyPhoto({
                    animation_speed: 'normal',
                    theme: 'light_square',
                    slideshow: 3000,
                    social_tools: '',
                    markup: '<div class="pp_pic_holder" data-file="' + fileID + '"> \
                  <div class="ppt">&nbsp;</div> \
                  <div class="pp_top"> \
                          <div class="pp_left"></div> \
                          <div class="pp_middle"></div> \
                          <div class="pp_right"> \
                          </div> \
                  </div> \
                  <div class="pp_content_container"> \
                          <div class="pp_left"> \
                                  <div class="pp_right"> \
                                          <div class="pp_content"> \
                                                  <div class="aside">\
                                                          <a class="pp_close" href="#">Close</a> \
                                                          <p class="pp_description"></p> \
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
                                                                          <a href="#" class="pp_arrow_previous">Previous</a> \
                                                                          <p class="currentTextHolder">0/0</p> \
                                                                          <a href="#" class="pp_arrow_next">Next</a> \
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
                    changepicturecallback: function($pp_pic_holder) {

                        var videoID = $pp_pic_holder.attr('data-file');
                        if ($("#fullResImage").length == 0) {
                            var iframeWidth = $pp_pic_holder.find("#pp_full_res iframe").width();
                            $pp_pic_holder.find("#pp_full_res iframe").width(iframeWidth - 250);
                        }
                        $.post(site_url + "components/album/processors/album/VideoAlbum.php?action=changeViewCount", {
                            videoid: videoID
                        });
                        $("#comments").hide();
                        $(".aside").show();
                        self.loadComments(videoID);
                        $(".motofoto").on('click', function() {
                            // FOTOMOTO.API.showWindow(FOTOMOTO.API.PRINT,
                            // "fullResImage");
                        });
                    }
                });
            });
            $('.delete').off('click');
            $('.delete').on('click', function(e) {
                e.preventDefault();
                self.remove($(this));
            });

            $('.fav_video').on('click', function(e) {
				if($(this).hasClass('marked')){
				
					alert("The video selected already..!");
				}else{
					$this = $(this);
					var type = "",
						video,
						videoID = "",
						albumID = "";
					if ($this.attr("data-id")) {
						albumID = $this.attr("data-id");
						type = "album";
					} else {

						video = $this.closest("li");
						videoID = $(video[0]).find("a").attr("id");
						albumID = $(video[0]).find("a").attr("data-albumid");
						type = "video";
					}

					var n = noty({
						text: 'Are you sure you want to make this video appear on website home page?',
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
								if (type === "video") {

									url = site_url + "components/album/processors/album/VideoAlbum.php?action=homeVideo&albumid=" + albumID + "&videoid=" + videoID;


									$.get(url, function(data) {
										var tex, typ;
										if (data == 1) {
											txt = 'Video selected successfully!';
											typ = 'success';
											$('.fav_video').css('color', 'gray');
											$('.fav_video').removeClass('marked');
											$(selff).css('color', 'red');
											$(selff).addClass('marked');
										} else {
											txt = 'Something went wrong, please try again!';
											typ = 'warning';
										}

										$noty.close();
										var n1 = noty({
											dismissQueue: true,
											force: true,
											layout: 'center',
											theme: 'defaultTheme',
											text: txt,
											type: typ
										});
										setTimeout(function() {
											n1.close();
										}, 1500);

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
									text: 'Action canceled, the video was not selected!',
									type: 'error'
								});
								setTimeout(function() {
									n1.close();
								}, 1500);
							}
						}]
					}); // Noty

					/**/



				}
            });
            this.editTitle("videoTitle");
        },
        loadComments: function(fileID) {

            $.getScript(site_url + "components/album/js/comments.js", function() {
                Comments.init($("#comments"), fileID, "videos");
                $("#comments").fadeIn()
                        .slimScroll({
                            height: '84%',
                            scrollTo: $('.comment-box').height()
                        });
            });
        },
        makeSortable: function(el) {
            el.sortable({
                handle: '.fa-arrows'
            }).bind('sortupdate', function() {
                var listSort = [];
                el.find('a').each(function(index) {
                    listSort.push({
                        idFileObject: $(this).attr('id'),
                        seqNumber: index
                    });
                });

                //$.post("#OrderPositionURL", {
                $.post(site_url + "components/album/processors/album/VideoAlbum.php?action=sortAlbum", {
                    order: listSort, // JSON.stringify(listSort),
                    userID: "userID"
                }).done(function(data) {

                    noty({
                        dismissQueue: true,
                        force: true,
                        layout: 'center',
                        theme: 'defaultTheme',
                        text: 'New positions are saved successfully!',
                        type: 'success',
                        timeout: 1500
                    });
                }).fail(function() {
                    alert("Error saving the new position!");
                });
            });
        },
        saveSort: function (el) {
      var listSort = [];
      el.find('a').each(function (index) {
        listSort.push({
          idFileObject: $(this).attr('id'),
          seqNumber: index
        });
      });
      //$.post("#OrderPositionURL", {
      $.post(site_url + "components/album/processors/album/VideoAlbum.php?action=sortAlbum", {
        order: listSort, //JSON.stringify(listSort),
        userID: "userID"
      });
    },
        editTitle: function (field) {
            var editNode = "",
                    url = site_url + "components/album/processors/album/VideoAlbum.php?action=edit";
            if (field === "albumName") {
                editNode = $albumEdit;
                url = site_url + "components/album/processors/album/VideoAlbum.php?action=edit";
            } else if (field === "file_desc") {
                editNode = $albumEdit;
                url = site_url + "components/album/processors/album/VideoAlbum.php?action=edit";
            } else if (field === "videoTitle") {
                editNode = $videoEdit;
                url = site_url + "components/album/processors/album/VideoAlbum.php?action=editVideo";
            }
            $('.edit').click(function() {
                var parentSpan = $(this).parent();
                if (field === "photoTitle")
                    parentSpan.css('top', '35px');
                else
                    parentSpan.css('top', '50px');
            });
            editNode.editable(url, {
                indicator: 'Saving...',
                type: "charcounter",
                tooltip: "Click to edit...",
                submit: 'Save',
                // event: "dblclick",
                style: 'display: inline',
                width: '140',
                height: '30',
                charcounter: {
                    characters: 40
                },
                callback: function(value, settings) {
                    if (field === "videoTitle") {
                        $($(this).closest('li').find('span')[0]).css('top', '68px');
                    } else {
                        $($(this).closest('li').find('span')[0]).css('top', '78px');
                        $(this).closest('li').find('img').attr('alt', value)
                    }
                },
                onreset: function(value, settings) {
                    if (field === "videoTitle")
                        $($(this).closest('li').find('span')[0]).css('top', '68px');
                    else
                        $($(this).closest('li').find('span')[0]).css('top', '78px');
                },
                submitdata: function(value, settings) {
                    console.log($(this).parents('li').find('a').attr('id'));
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
            this.getVideos(albumID);
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
                                $.post(site_url + "components/album/processors/album/VideoAlbum.php?action=editAlbum", {
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
                                $.post(site_url + "components/album/processors/album/VideoAlbum.php?action=editAlbum", {
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
            this.getVideos(albumID);
        },
        closeTheIFrameImDone: function() {
            //this.getVideos(albumID);
            $(".pp_close").click();
        }
    }
}());