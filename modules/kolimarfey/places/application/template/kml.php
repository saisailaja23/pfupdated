<?php include('_header.php') ?>

    <script type="text/javascript">
                
        function kmlDeleteKml (iId)
        {
            _kmlKmlRequest ('<?php $html->href('kml_delete/'); ?>' + iId, true);
        }

        function _kmlKmlRequest (sUrl, isConfirmRequired)
        {
            if (isConfirmRequired && !confirm('<?php echo $html->t('Are you sure?'); ?>'))
                return;
            jQuery.get(sUrl, function(s) {
                if (1 == parseInt(s))
                    document.location = '<?php $html->href('kml/' . $aPlace['pl_id']); ?>';
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
                
                <?php if (!$aPlaceKmlFiles): ?>
                    
                    <?= $this->t('Places No KML files yet'); ?>

                <?php else: ?>

                    <?php foreach ($aPlaceKmlFiles as $aKml): ?>

                        <div>
                            <b><?php echo $aKml['pl_kml_name']; ?></b>
                            <span><?php $html->url('Places Delete KML', 'javascript:void(0);', 1, array('onclick' => "kmlDeleteKml('{$aKml['pl_kml_id']}')"), 1, 'delete'); ?></span>
                        </div>

                    <?php endforeach; ?>
                    
                    <div class="clear_both"></div>
                        
                <?php endif; ?>

                </div>

                <?php echo DesignBoxContent ($aPlace['pl_name'] . ' ' . $this->t('Places KML files'), ob_get_clean(), 1 ); ?>
        
            </td>

            <td width="50%" valign="top">

                <?php ob_start(); ?>

                <div class="bx_sys_default_padding">

                <?php $form->formErrors(); ?>
        
                <?php $form->display(); ?>
                    
                <br />

                <div style="text-align:center; margin:10px;">
                    <?php $html->url('Places Return', 'view/' . $aPlace['pl_uri'], 1, array(), 1, 'arrow_undo'); ?>
                </div>

                </div>

                <?php echo DesignBoxContent ($this->t('Places Add KMl file'), ob_get_clean(), 1 ); ?>
                
            </td>

        </tr>
        
    </table>
        
<?php include('_footer.php') ?>
