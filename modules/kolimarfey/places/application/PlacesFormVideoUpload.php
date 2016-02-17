<?php

require_once(BX_DIRECTORY_PATH_ROOT . K_SYS_PATH . 'KForm.php');

class PlacesFormVideoUpload extends KForm
{    
    var $aForm = array (

        'name' => 'form3_upload',

        'attributes' => array(
            'enctype' => 'multipart/form-data',
        ),

        'fields' => array (
            
            'pl_video' => array (
                'label' => 'Places Select Video File',
                'type' => 'file',
                                
                'required' => 1,
                'error' => 'Places Please select video file',
            ),

            'submit' => array (
                'type' => 'submit',
                'required' => 0,
                'val' => 'Places Upload Video',
            ),        
        ),
        
    );

    function PlacesFormVideoUpload ()
    {
        parent::KForm();
    }

    function insertUploadedVideo($sPostName, $iPlaceId, $iAuthorId)
    {         
        $sInputFile = BX_DIRECTORY_PATH_ROOT . PLACES_VIDEOS_PATH . 'tmp' . $iAuthorId;
        if (!move_uploaded_file($_FILES[$sPostName]['tmp_name'], $sInputFile)) {
            return false;
        }

        $this->_load('Model', 'Form');

        if (!($iVideoId = $this->model->insertVideo($iPlaceId, '', ''))) {
            return false;
        }

        $sOutputVideo = $iVideoId . '.flv'; 
        $sOutputImage = $iVideoId . '.jpg'; 
        $sOutputThumb = 't' . $iVideoId . '.jpg'; 
        if (!$this->_convert($sInputFile, $sOutputVideo, $sOutputThumb, $sOutputImage, BX_DIRECTORY_PATH_ROOT . PLACES_VIDEOS_PATH)) {
            $this->model->deleteVideo ($iVideoId, $iAuthorId);
            return false;
        }

        $sThumb = "places/video/thumb/{$iVideoId}.jpg";
        $sRootUrl = $GLOBALS['site']['url'];
        $sEmbed = <<<EOF
<object width="320" height="240" data="places/application/template/swf/flowplayer.swf" type="application/x-shockwave-flash">
    <param name="movie" value="places/application/template/swf/flowplayer.swf" />
    <param name="allowfullscreen" value="true" />
    <param name="allowscriptaccess" value="always" />
    <param name="flashvars" value='config={"clip":{"url":"{$sRootUrl}places/video/file/{$iVideoId}.flv"},"playlist":["{$sRootUrl}places/video/preview/{$iVideoId}.jpg",{"url":"{$sRootUrl}places/video/file/{$iVideoId}.flv","autoPlay":false,"autoBuffering":false}]}' />
</object>
EOF;

        $this->model->updateVideoData ($iVideoId, $sThumb, $sEmbed);

        return $iVideoId;
    }

    function _convert($sInputFile, $sOutputVideo, $sOutputThumb, $sOutputImage, $sPath)
    {        
        $sFfmpegPath = BX_DIRECTORY_PATH_ROOT . 'flash/modules/global/app/ffmpeg.exe';

        //ffmpeg -i video_origine.avi -ab 56 -ar 44100 -b 200 -r 15 -s 320x240 -f flv video_finale.flv

        // convert video
        @set_time_limit(1000);
        $sInput = ' -y -i "' . $sInputFile . '"';
        $sOptions = " -ab 56 -ar 44100 -b 512k -r 15 -s 320x240 -f flv "; // -vcodec flv
        @chdir($sPath);
        
        $sCommand = $sFfmpegPath . $sInput . $sOptions . $sOutputVideo;
        @exec($sCommand);
        if(!file_exists($sPath . $sOutputVideo) || filesize($sPath . $sOutputVideo) == 0) {
            @unlink ($sInputFile);
            @unlink ($sOutputVideo);
            return false;
        }

        // get video images
        $sCommand = $sFfmpegPath . $sInput . " -ss 1 -vframes 1 -an -sameq -f image2 -s 320x240 " . $sOutputImage;        
        @exec($sCommand);
        if(!file_exists($sPath . $sOutputImage) || filesize($sPath . $sOutputImage) == 0) {
            @unlink ($sInputFile);
            @unlink ($sOutputVideo);
            @unlink ($sOutputImage);
            return false;
        }
        
        $sCommand = $sFfmpegPath . $sInput . " -ss 1 -vframes 1 -an -sameq -f image2 -s 110x80 " . $sOutputThumb;        
        @exec($sCommand);
        if(!file_exists($sPath . $sOutputThumb) || filesize($sPath . $sOutputThumb) == 0) {
            @unlink ($sInputFile);
            @unlink ($sOutputVideo);
            @unlink ($sOutputImage);
            @unlink ($sOutputThumb);
            return false;
        }

        @unlink ($sInputFile);
        
        return true;
    }
}

?>
