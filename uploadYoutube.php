<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 'On');

require_once 'inc/header.inc.php';
require_once 'inc/profiles.inc.php';
require_once 'inc/db.inc.php';

require_once 'YoutubeLoginComponent/processors/AccessToken.php';

$obj = new AccessToken();
//echo '<pre>';print_r($obj);

$accessToken = $obj->getAcessToken();
//echo $accessToken;

//$agencyToken = $obj->getAcessToken();
//$cairs = 0;
//if($accessToken != $agencyToken){
//    $cairs = 1;
//}

$sql1 = "SELECT p.firstname,p.nickname,p.couple,p.state, agen.title as agency
            FROM Profiles p, bx_groups_main agen
            WHERE p.adoptionagency = agen.id
            AND p.id = ( 
                SELECT album.Owner
                FROM sys_albums album
                WHERE album.ID =".$_GET['albumID']. ")";
$tags = db_arr($sql1);
$addrTag = 'Looking for adoption in ';
$agencyTag = $tags['agency'];
$userTag = $tags['firstname'];
$nickname = $tags['nickname'];
$state = explode('(',$tags['state']);
if($state[0] == 'US'){
    $addrTag .= 'US';
} else if($state[0] == 'NON US'){
    $addr = explode(')',$state[1]);
    $addrTag .= $addr[0];   
} else{
    $addrTag .= $state[0];       
}  

if($tags['couple']){
    $sql2 = "SELECT firstname
                FROM Profiles
                WHERE id = ".$tags['couple'];
    $couple = db_arr($sql2);
    $userTag .= ' and ' . $couple['firstname'];
    
}
$userTag .= ' adoption';
//echo $addrTag;
//echo $userTag;
//echo $agencyTag;
//exit;
?>
<form id="upload-form">
    <div style="color: #A4CA70;padding: 30px;font-weight: bold;">Upload videos to YouTube</div>
    <div style="padding-left: 30px;">
        <input id="file" type="file" multiple width="200" accept="video/*" style="border: 0px;">
        <input class="pink-btn" id="submit" type="submit" value="Upload" style="width: 93px; margin-right: 125px; float: right">        
    </div>
    <div class="maindiv" style="overflow-y: scroll; width: 100%; height: 349px;">
        <div class="ytheading" style="padding: 30px;">
            <span style="color: #A4CA70; padding-left: 25px;float: left">File</span>
            <span style="color: #A4CA70; padding-left: 65px;float: left">Name</span>
            <span style="color: #A4CA70; padding-left:  138px;float: left">Description</span>
            <span style="color: #A4CA70; padding-left:  138px;float: left">Status</span>
            <!--<span style="color: #A4CA70; padding-top: 10px;padding-left: 90px;float: left">TagName</span>-->
        </div>

        <div class="ytvideo" id="1" style="padding-left: 30px;">
            <span id="videoID" style="padding-top: 40px;padding-left: 25px;float: left">Video1</span>
            <input type="text"  placeholder=" Video Name" id="ytName" style="margin-top:40px;margin-left: 25px;float: left;width: 100px;height: 22px; font-size: 11px">
            <textarea id="ytDesc" placeholder="Please add description to your video here" style="margin: 10px 20px; float: left; width: 200px; height:83px; font-size: 11px; border-color: rgb(139, 140, 142);"></textarea>
            <div class="uploadStatus" style="padding: 38px 0;float: left;  width: 180px;margin: 10px 10px;"></div>
            <!--<input type="text" id="ytTagName" style="margin-top: 10px;margin-left: 25px;float: left;width: 100px;height: 22px;">-->
            <br>
        </div>
    </div>
    
</form>
<!--<div id="filesList" style="padding-top: 4px;padding-left: 25px;float: right;width: 317px;border: 1px solid rgb(171, 214, 207);margin-right: 25px;margin-top: 10px;height: 106px;overflow-y: scroll;"></div>-->

<style>
    .video {
        width: 300px;
        height: 10px;
        /*border: 1px solid red;*/
    }
    .videoContainer {
        float: left;
    }
    .videoStatus {
        float: right;
        margin-right: 30px;
    }
</style>
<script>

    var GOOGLE_PLUS_SCRIPT_URL = 'https://apis.google.com/js/client:plusone.js';
    var CHANNELS_SERVICE_URL = 'https://www.googleapis.com/youtube/v3/channels';
    var VIDEOS_UPLOAD_SERVICE_URL = 'https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet,status';
    var VIDEOS_SERVICE_URL = 'https://www.googleapis.com/youtube/v3/videos';
    var INITIAL_STATUS_POLLING_INTERVAL_MS = 15 * 1000;

    var accessToken = '<?= $accessToken ?> ';

    $("#file").change(function(){
        if($('#file').get(0).files.length > 1){
            var ytDiv = $('#1'),ytDivHTML = ytDiv.html();
            for (i = 1; i < $('#file').get(0).files.length; i++) {
                var ytDiv2 = $("<div>", {class: "new ytvideo", id: i+1});
                ytDiv2.html(ytDivHTML);
                ytDiv2.css('padding-left','30px');
                ytDiv2.css('padding-top','90px');
                var m = i+1;
                var str = 'video'+m;                                
                $('.maindiv').append(ytDiv2);                
                $('#'+m).find('#videoID').html(str);
            }
        }
    });
    $('#upload-form').submit(function (e) {
        var file, k = 0;        
        e.preventDefault();
        var videoIds, filenames, videoId;
        file = $('#file').get(0).files;

        var label = '', name, flag = 1, flg = 1;

        for(i = 1; i < file.length+1 ; i++){            
            validation = $("#"+i).find("#ytName").val();
            if(validation.trim() == ''){                
                flg = 0;
            }
            flag = flag*flg;
        }
        
        if(flag == 1){    
        for (i = 0; i < file.length; i++) {
            name = '';
//            debugger;
            if (file[i].name.length > 20) {
                for (l = 0; l < 17; l++)
                    name += file[i].name[l];
                name += '...';
            } else
                name = file[i].name;
                var m=i+1; 
                $("#"+m).find('.uploadStatus').append($('<span>', {class: 'video', id: file[i].size + '' + i}).html(' - Waiting...'));
                $('#'+m).find('.uploadStatus').append('<br>');                

        }

            uploadVideoYt(file, 0, '', '', 0);           
            
        } else{
            dhtmlx.alert("Please enter a name for the video");
        }

        function uploadVideoYt(file, k, videoIds, fileNames, tokenFlag ) {
            var te = file[k].name.split('.'), fileName = '', channelID;
            for (i = 0; i < te.length - 1; i++) {
                if (i == te.length - 2)
                    fileName += te[i];
                else
                    fileName += te[i] + '.';
            }
//            debugger;
            m = k+1;
            ytTitle = $("#"+m).find("#ytName").val();
            ytDescription = $("#"+m).find("#ytDesc").val();            
            ytDescription += '\nwww.parentfinder.com/'+'<?= $nickname ?>';
            
            var metadata = {
                snippet: {
                    title: ytTitle,
                    description: ytDescription,
                    tags: ['<?= $addrTag ?>','<?= $userTag ?>','<?= $agencyTag ?>','Parentfinder families']
//                categoryId: 22
                }
            };
            $.ajax({
                url: VIDEOS_UPLOAD_SERVICE_URL,
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    Authorization: 'Bearer ' + accessToken,
                    'x-upload-content-length': file[k].size,
                    'x-upload-content-type': file[k].type
                },
                data: JSON.stringify(metadata)
            }).done(function (data, textStatus, jqXHR) {
                videoId = '';
                k1 = 0;
//                console.log(jqXHR.getResponseHeader('Location'));
                $.ajax({
                    url: jqXHR.getResponseHeader('Location'),
                    method: 'PUT',
                    contentType: file[k].type,
                    headers: {
                        'Content-Range': 'bytes 0' + '-' + (file[k].size - 1) + '/' + file[k].size
                    },
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) {
                            xhr.upload.addEventListener(
                                    'progress',
                                    function (e) {
                                        if (e.lengthComputable) {
                                            var bytesTransferred = e.loaded;
                                            var totalBytes = e.total;
                                            var percentage = Math.round(100 * bytesTransferred / totalBytes);
                                            $('#' + file[k].size + '' + k).html((percentage < 100) ? ' - Uploading - ' + percentage + '%' : ' - Uploaded - ' + percentage + '%');
                                        }
                                    },
                                    false
                                    );
                        }
                        k1++;
                        return xhr;
                    },
                    processData: false,
                    data: file[k]
                }).done(function (response) { 
                    channelID = response.snippet.channelId;
                    fileName = response.snippet.title;
                    videoId = response.id;
                    console.log(videoIds);
                    if (videoIds != '') {
                        videoIds += ',' + videoId;
                        fileNames += ',' + fileName;
                    }
                    else {
                        videoIds = videoId;
                        fileNames = fileName;
                    }
                    if (file[++k]){
                        uploadVideoYt(file, k, videoIds, fileNames,0);                        
                    }
                    else {
                        var params = 'url=' + videoIds + '&filename=' + fileNames + '&upload=1&albumID=<?= $_GET['albumID'] ?>&ytchannel=' + channelID + '&files=' + file; 
//                        console.log(params);
                        dhtmlxAjax.post(site_url + "components/album/processors/youtubeSelect.php", params, function (loader) {
                            var data = loader.xmlDoc.responseText;
                            noty({
                                dismissQueue: true,
                                force: true,
                                layout: 'center',
                                theme: 'defaultTheme',
                                text: 'Videos uploaded to your YouTube channel successfully.',
                                type: 'alert',
                                timeout: 2000,
                                buttons: [{
                                        addClass: 'teal-btn',
                                        text: 'OK',
                                        onClick: function ($noty) {
                                            $noty.close();
                                            noty({
                                                dismissQueue: true,
                                                force: true,
                                                layout: 'center',
                                                theme: 'defaultTheme',
                                                text: 'Currently your videos are under processing, kindly check back in some time.',
                                                type: 'alert',
                                                timeout: 2000,
                                                buttons: [{
                                                        addClass: 'teal-btn',
                                                        text: 'OK',
                                                        onClick: function ($noty) {
                                                            $noty.close();
                                                            addWinYt.close();
                                                        }
                                                    }]
                                            });
                                        }
                                    }]
                            });

                        });
                    }
                });
            });
        }
    });

</script>
