<?php include('_header.php') ?>

    <script type="text/javascript">
                
        function videoDeleteVideo (iId)
        {
            _videoVideosRequest ('<?php $html->href('video_delete/'); ?>' + iId, true);
        }

        function _videoVideosRequest (sUrl, isConfirmRequired)
        {
            if (isConfirmRequired && !confirm('<?php echo $html->t('Are you sure?'); ?>'))
                return;
            jQuery.get(sUrl, function(s) {
                if (1 == parseInt(s))
                    document.location = '<?php $html->href('videos/' . $aPlace['pl_id']); ?>';
                else
                    alert('<?php echo $this->t('Places Error Occured'); ?>');
            });
        }
    
    </script>
    
    <table width="100%" cellspacing="0" cellpadding="0">

        <tr>
        
            <td width="50%" valign="top" style="padding-right:10px;">
        
                <?php ob_start(); ?>

                <div class="bx_sys_default_padding">
                
                <?php if (!$aPlaceVideos): ?>
                    
                    <?= $this->t('Places No videos yet'); ?>

                <?php else: ?>

                    <?php foreach ($aPlaceVideos as $aVideo): ?>

                        <table class="k_snippet_video">
                        <tr>
                            <td class="k_thumb_video" valign="top">
                                <div class="k_thumb_video"><img src="<?= $aVideo['pl_video_thumb']; ?>" /></div>
                            </td>
                            <td class="k_info_video" valign="top">                                
                                <?php $html->url('Places Delete Video', 'javascript:void(0);', 1, array('onclick' => "videoDeleteVideo('{$aVideo['pl_video_id']}')"), 1, 'delete'); ?>
                            </td>
                        </tr>
                        </table>            

                    <?php endforeach; ?>
                    
                    <div class="clear_both"></div>
                        
                <?php endif; ?>

                </div>

                <?php echo DesignBoxContent ($aPlace['pl_name'] . ' ' . $this->t('Places Videos'), ob_get_clean(), 1 ); ?>
        
            </td>

            <td width="50%" valign="top">

                <?php ob_start(); ?>

                <div class="bx_sys_default_padding">

                <?php $form->formErrors(); ?>
        
                <?php $form->display(); ?>
                    
                <br />
                <br />

                <?php $form_upload->formErrors(); ?>
        
                <?php $form_upload->display(); ?>

                <div style="text-align:center; margin:10px;">
                    <?php $html->url('Places Return', 'view/' . $aPlace['pl_uri'], 1, array(), 1, 'arrow_undo'); ?>
                </div>

                </div>

                <?php echo DesignBoxContent ($this->t('Places Add Video'), ob_get_clean(), 1 ); ?>
                
            </td>

        </tr>
        
    </table>
        
<?php include('_footer.php') ?>
