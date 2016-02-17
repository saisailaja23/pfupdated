<?php include('_header.php') ?>

    <script type="text/javascript">
    
        function photoMakePrimary (iPhotoId)
        {
            _photoPhotosRequest ('<?php $html->href('photo_make_primary/'); ?>' + iPhotoId);
        }
            
        function photoDeletePhoto (iPhotoId)
        {
            _photoPhotosRequest ('<?php $html->href('photo_delete/'); ?>' + iPhotoId, true);
        }

        function _photoPhotosRequest (sUrl, isConfirmRequired)
        {
            if (isConfirmRequired && !confirm('<?php echo $html->t('Are you sure?'); ?>'))
                return;
            jQuery.get(sUrl, function(s) {
                if (1 == parseInt(s))
                    document.location = '<?php $html->href('photos/' . $aPlace['pl_id']); ?>';
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
                
                <?php if (!$aPlacePhotos): ?>

                    <?= $this->t('Places No images yet'); ?>

                <?php else: ?>

                    <?php foreach ($aPlacePhotos as $aPhoto): ?>

                        <table class="k_snippet">
                        <tr>
                            <td class="k_thumb" valign="top">
                                <div class="k_thumb"><?php $html->thumb($aPhoto['pl_img_id']); ?></div>
                            </td>
                            <td class="k_info" valign="top">
                                <?php if ($aPhoto['pl_img_id'] != $aPlace['pl_thumb']): ?> 
                                    <?php $html->url('Places Make Primary', 'javascript:void(0);', 1, array('onclick' => "photoMakePrimary('{$aPhoto['pl_img_id']}')"), 1, 'image'); ?>
                                    <br />
                                <?php endif; ?>
                                <?php $html->url('Places Delete Photo', 'javascript:void(0);', 1, array('onclick' => "photoDeletePhoto('{$aPhoto['pl_img_id']}')"), 1, 'delete'); ?>
                            </td>
                        </tr>
                        </table>            

                    <?php endforeach; ?>
                    
                    <div class="clear_both"></div>
                        
                <?php endif; ?>

                </div>

                <?php echo DesignBoxContent ($aPlace['pl_name'] . ' ' . $this->t('Places Photos'), ob_get_clean(), 1 ); ?>
        
            </td>

            <td width="50%" valign="top">

                <?php ob_start(); ?>

                <div class="bx_sys_default_padding">

                <?php $form->formErrors(); ?>
        
                <?php $form->display(); ?>
                    
                <div style="text-align:center; margin:10px;">
                    <?php $html->url('Places Return', 'view/' . $aPlace['pl_uri'], 1, array(), 1, 'arrow_undo'); ?>
                </div>

                </div>

                <?php echo DesignBoxContent ($this->t('Places Upload Photo'), ob_get_clean(), 1 ); ?>
                
            </td>

        </tr>
        
    </table>
        
<?php include('_footer.php') ?>
