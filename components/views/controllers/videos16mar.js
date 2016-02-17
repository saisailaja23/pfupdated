var videoAlbum = (function() {
    $.ajaxSetup({ cache: false });
    var
        $videoResult,
        $owner_id,
        $wrap, 
        $topbar, 
        $loader, 
        $close, 
        $content, 
        $name, 
        $albumID,
        $albumCount, $grid, $addAlbum, $albums, $albumEdit, $videoEdit,
        $images, $close, $controls,$logged_id,$user_name;
    return {
        init: function(pr) {
            var self=this;
            $videoResult = pr;
            $owner_id=this.getUrlVars()["id"];
             $.get(site_url + "components/views/processors/Videos.php?act=get_logged_id&user_id="+$owner_id,function(d){
                $logged_id=d.logged_id;
                $user_name=d.user_name;
                self.buildAlbumFrame();
                self.fetchAlbum();
             });  
        },
        buildAlbumFrame: function() {
            var sHtml =
                '<div class="wrapper">' +
                '   <div class="topbar"> ' +
                '       <span id="close">&#8593;</span>' +
                '       <h2>Video Album</h2> <span id="name" style="font-size: 19px;float: right;color: #aaa;margin-top: 2px;"></span>' +
                '   </div> <!-- LifeBook Grid -->' +
                '   <div class="loader2"><i></i><i></i><i></i><i></i><i></i><i></i><span>Loading...</span></div>' +
                '   <div id="content" style="display: none;">'+
                '       <ul id="lb-grid">No albums to display</ul>' +
                '   </div> <!-- #content-->' +
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
            $name.html($user_name+"'s Videos");
        },
        topBarEvents: function() {
            var self = this;
            $close.on('click', function(e) {
                e.preventDefault();
                if ($(this).hasClass('back')) {
                    $(this).removeClass('back').html("&#8592;");
                    self.buildAlbumFrame();
                    self.fetchAlbum();
                } else{
                    window.location= site_url +"extra_profile_view_17.php?id="+$owner_id;
                 }
            });
        },
        fetchAlbum: function() {
            var self = this;  
            $.get(site_url + "components/views/processors/Videos.php?act=get_albums&owner_id="+$owner_id, function(d) {
                if (d.status == "success") {
                    self.buildAlbumGrid(d.data);
                } else {
                    $content.show();
                    $loader.hide();
                    $grid = $content.find("#lb-grid");
                    $grid.html("No Albums yet! Create new album");
                    self.regContentVars();
                    // self.toolbarEvents();
                }
            });
        },
        buildAlbumGrid: function(data) {
            var i, html = "";
            for (i = 0; i < data.length; i++) {
               if($logged_id == 0 && data[i].AllowAlbumView != "3" || !data[i].Hash){
                    continue;
                }
              
                var editClass = '';
                var view = (data[i].Views === null) ? 0 : data[i].Views;
                // var status = (data[i].Status === "active") ? "active " : "pending ";
                // status += (data[i].AllowAlbumView === 3) ? "published" : "published";
                var img = !data[i].Hash ? site_url + 'templates/tmpl_par/images/no-img.jpg' : site_url + 'flash/modules/video/files/_' + data[i].Hash + '_small.jpg';
                html +=
                    '<li rel="" >' +
                    '    <span class="tp-info">' +
                    '   <span class="' + editClass + '" albumid="' + data[i].ID + '" title="' + decodeURIComponent(data[i].Caption) + '">' + decodeURIComponent(data[i].Caption) + '</span>' +
                    '        <span class="lb-views">' + view + '</span>' +
                    '    </span>' +
                    '    <div class="wrap-image">' +
                    '        <img data-pubstatus="' + data[i].AllowAlbumView + '" class="lb-thumb" id="' + data[i].ID + '" src="' + img + '" alt="' + decodeURIComponent(data[i].Caption) + '">' +
                    '    </div>' +
                    '    <i class="fa fa-eye">' + view + '</i>' +
                    '</li>';
            }
            $content.show();
            $albumCount = $wrap.find("#lifebooksCont > span"),
            $albumCount.html(data.length);
            $grid = $content.find("#lb-grid");
            if(html != ""){
                $grid.html(html);
            }
            $loader.hide();
             this.regContentVars();
            // this.toolbarEvents();
            this.albumEvents();
        },
        regContentVars: function() {
            $addAlbum = $content.find('.addAlbum');
            // $filterMenu = $content.find('.filter-menu');
            $album = $content.find('.lb-thumb');
            // $albumEdit = $content.find('li').find('.edit');
        },
        albumEvents: function() {
                var self = this;
                $album.on('click', function(e) {
                    e.preventDefault();
                    self.openAlbum($(this));
                });
        },
        getUrlVars: function() {
            var 
                vars = {},
            parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
              vars[key] = value;
            });
            return vars;
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
        buildAlbumView: function(id, albumName, pubstatus) {
            var html='<ul class="albums">No videos available</ul>';
            $content.html(html);
            $albums = $('.albums');
            // this.buildAlbumViewEvents();
        },
        buildAlbumViewEvents: function() {
            var self = this;
            var lastClicked = "";
            $('.control a[rel^="prettyVideo"]').prettyphoto({
                social_tools: '',
                animation_speed: 'normal',
                theme: 'light_square',
                slideshow: 3000,
                show_title: false,
                callback: function(e) {
                    var btn = $(lastClicked).text();
                    if (btn == " Upload Files") {
                        self.getVideos($albumID);
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
        },
        getVideos: function(albumid) {
            var self = this;

            $.get(site_url + "components/views/processors/Videos.php?act=get_videos&album_id="+albumid, function(d) {
                // d = $.parseJSON(d);
                if (d.status === "success") {
                    $loader.hide();
                    $content.show();
                    d.data.albumid = albumid;
                    self.buildVideosGrid(d.data);
                } else {
                    $loader.hide();
                    $content.show();
                    $albums.html("The Album does not have any images please upload some images.");
                }
            });
        },
        buildVideosGrid: function(d) {
            var self = this;
            html = "";
            for (var i = 0; i < d.length; i++) {
                if(d[i].Status != "approved"){
                    continue;
                }
                d[i].Title = (d[i].Title == "")? "No Title": d[i].Title;
                html +=
                    '<li>' +
                    ' <a href="flash/modules/video/files/_' + d[i].ID + '.flv?width=864&amp;height=486" data-albumid="' + d.albumid + '" id="' + d[i].ID + '" rel="prettyPhoto[Album]" title="' + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + '">' +
                    '     <img src="' + site_url + 'flash/modules/video/files/_' + d[i].ID + '_small.jpg" height="150px" width="200px" class="fc-init" >' +
                    ' </a>' +
                    ' <span class="tp-info" style="width:175px;">' +
                    '     <span class="edit" albumid="' + d[i].ID + '" title="Click to edit...">' + d[i].Title.replace(/_/g, " ").replace(/-/g, " ") + '</span>' +
                    ' </span>' +
                    '    <div class="tools">' +
                    '        <i class="fa fa-comments ">' + d[i].CommentsCount + '&nbsp;&nbsp;</i>' +
                    '        <i class="fa fa-eye ">' + d[i].Views + '&nbsp;&nbsp;</i>' +
                    '</li>';
            }
            if(html != ""){
                $albums.html(html);
            }
            this.videoGridEvents();
        },
        videoGridEvents: function() {
            var self = this;
            // $('li a[rel^="prettyPhoto"]').each(function() {
            //     var fileID = $(this).attr('id')

            // });
            $('li a[rel^="prettyPhoto"]').each(function(){
                 var fileID = $(this).attr('id')
                 
                  $(this).prettyPhoto({
                    animation_speed: 'normal',
                    theme: 'light_square',
                    slideshow: 3000,
                    social_tools: '',
                    markup: self.prettyPhotoMarkup(fileID),
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
                          //  FOTOMOTO.API.showWindow(FOTOMOTO.API.PRINT, "fullResImage");
                        });
                    }
                });
            });          
        },
        prettyPhotoMarkup: function(fileID) {
           
            var html =
                '<div class="pp_pic_holder" data-file="' + fileID + '"> \
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
                                                          <p class="motofoto " title="Buy!"><i class="fa fa-shopping-cart"></i></p>\
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
          <div class="pp_overlay"></div>';

            return html;
        },
        loadComments: function(fileID) {
            $.getScript(site_url + "components/album/js/comments.js", function() {
                Comments.init($("#comments"), fileID, "videos");
                $("#comments").fadeIn()
                    .slimScroll({
                        height: '84%',
                        scrollTo: $('.comment-box').height(),

                    });
            });
        }
    }
}());

