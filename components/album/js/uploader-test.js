$(function() {

    var content = $('#mainpane');
    var loading = $("<div id='loading' class='spinner overlay'>Loading</div>");
    //var type = $(".albumid").attr('data-type');

    $(".directory").on("click", function(e) {
        e.preventDefault();
        content.html(loading);
        try {
            var url = $(this).attr("href");

            $.ajax({
                url: url
                //cache: false
            })
                .done(function(html) {
                    content.html(html);
                });
        } catch (e) {
            console.log(e.stack);
        }
    });

    if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
        //var defaultruntimes = 'flash,silverlight,html4,html5';
        var defaultruntimes = 'html5,flash,silverlight,html4';
    } else {
        var defaultruntimes = 'html5,flash,silverlight,html4';
    }

    //$("[data-client=computer]").click();
    var type = $(".albumid").attr('data-type'),
        albumid = $(".albumid").attr('data-albumid'),
        typeallow = [];

    if (type === "photo") {
        typeallow = [{
            title: "Image files",
            extensions: "jpg,gif,png,tiff,jpeg"
        }];
    } else {
        typeallow = [{
            title: "Video files",
            extensions: "mov,avi,wmv,mp4,mpeg,webm"
        }];
    }


    $("#uploader").plupload({
        // General settings
        runtimes: defaultruntimes, //'html5,flash,silverlight,html4',
        url: '../processors/upload-test.php',
        multipart_params: {
            "albumID": albumid,
            "type": type
        },
        //required_features: 'access_binary',
        // User can upload no more then 20 files in one go (sets multiple_queues to false)
        max_file_count: 100,

        // chunk_size: '20mb',

        filters: {
            // Maximum file size
            max_file_size: '200mb',
            // Specify what files to browse for
            mime_types: typeallow
        },

        // Rename files by clicking on their titles
        rename: true,

        // Sort files
        //sortable: true,

        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,

        //autostart: true,

        // Views to activate
        views: {
            list: true,
            thumbs: true, // Show thumbs
            active: 'thumbs'
        },

        // Flash settings
        flash_swf_url: '../js/Moxie.swf',
        // Silverlight settings
        silverlight_xap_url: '../js/Moxie.xap',


        // Post init events, bound after the internal events
        init: {
            FilesAdded: function(up, files) {
                setTimeout(function() {
                    up.start();
                }, 500);
            },

            UploadComplete: function(up, files) {
                // Called when all files are either uploaded or failed
                /*var n1 = noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Files Uploaded successfully', type: 'success'});
                                                setTimeout(function () {
                                        n1.close();
                                        window.parent.closeTheIFrameImDone();
                                        }, 1500);*/


                if (type == "video") {
                    var cnote = noty({
                        text: 'Your videos are being converted. Please be patient...',
                        type: 'alert',
                        dismissQueue: true,
                        layout: 'center',
                        theme: 'defaultTheme',
                        modal: true,
                        // timeout: true,
                        // buttons: [{
                        //     addClass: 'btn btn-primary',
                        //     text: 'Ok',
                        //     onClick: function($noty) {
                        //         $noty.close();
                        //         window.parent.videoAlbum.closeTheIFrameImDone();
                        //     }
                        // }]
                    });
                    var iflag = false;
                    for (var i = 0; i < files.length; i++) {
                        var video_name = files[i].name;
                        var url = 'upload.php?type=video&convert=ture&albumid=' + albumid + '&files=' + files[i].name;
                        $.get(url, function() {
                            if (i >= files.length - 1) {
                                console.log("video " + video_name + " converted successfully");
                                iflag = true;
                                // cnote.close();
                                // notify();
                            }
                        });
                    };
                    setInterval(function() {
                        if (iflag) {
                            cnote.close();
                            // $noty.close();
                            window.parent.videoAlbum.closeTheIFrameImDone();
                        }
                    }, 100);
                } else {
                    notify();
                }

                function notify() {
                    if (n) {
                        n.close();
                    }
                    var n = noty({
                        text: 'Files uploaded successfully. Click "OK" to exit',
                        type: 'alert',
                        dismissQueue: true,
                        layout: 'center',
                        theme: 'defaultTheme',
                        modal: true,
                        timeout: true,
                        buttons: [{
                            addClass: 'btn btn-primary',
                            text: 'Ok',
                            onClick: function($noty) {
                                $noty.close();
                                if (type == "video") {
                                    window.parent.videoAlbum.closeTheIFrameImDone();
                                } else {
                                    window.parent.photoAlbum.closeTheIFrameImDone();
                                }

                            }
                        }]
                    });
                }

            },
            Error: function(up, args) {
                // Called when error occurs
                //log('[Error] ', args);
                console.log(args);
            }
    
        }


    }); //upload

}); // ready

(function(window) {
    var finishAuth = (function(e) {
        $("[data-client=" + e + "]").click();
    })
    window.finishAuth = finishAuth;
})(window);