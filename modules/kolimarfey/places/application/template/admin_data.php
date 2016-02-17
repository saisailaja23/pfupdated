<?php include('_header.php') ?>

    <script type="text/javascript">
            
        function dataDelete (iId)
        {
            _dataRequest ('<?php $html->href($sActionDelete); ?>/' + iId + '?' + (new Date()), true);
        }

        function _dataRequest (sUrl, isConfirmRequired)
        {
            if (isConfirmRequired && !confirm('<?php echo $html->t('Are you sure?'); ?>'))
                return;
            jQuery.get(sUrl, function(s) {
                if (1 == parseInt(s))
                    document.location = '<?php $html->href($sLocation); ?>';
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
                
                <?php if (!$aData): ?>

                    <?php echo $this->t('Places No data so far'); ?>

                <?php else: ?>

                    <table class="k_data">
                    <?php foreach ($aData as $aRow): ?>
                        
                        <tr>
                            <?php if ($sIconField): ?>
                            <td valign="top" class="k_data_icon">
                                <img src="places/application/icons/<?php echo $aRow[$sIconField] ? $aRow[$sIconField] : '0.png'; ?>" />
                            </td>
                            <?php endif; ?>
                            <td valign="top" class="k_data_name">
                                <?= $isTranslateName ? $this->t($aRow[$sNameField]) : $aRow[$sNameField]; ?>
                            </td>
                            <td valign="top" class="k_data_actions">
                                <?php $html->url('Places Delete General', 'javascript:void(0);', 1, array('onclick' => "dataDelete('{$aRow[$sIdField]}')"), 1, 'delete'); ?> 
                                <br />
                                <?php $html->url('Places Edit General', "{$sActionEdit}/{$aRow[$sIdField]}", 1, array(), 1, 'page_edit'); ?>
                            </td>
                        </tr>                        

                    <?php endforeach; ?>
                    </table>                                
                        
                <?php endif; ?>

                </div>

                <?php echo DesignBoxContent ($sListBoxName, ob_get_clean(), 1 ); ?>
        
            </td>

            <td width="50%" valign="top">

                <?php ob_start(); ?>

                <div class="bx_sys_default_padding">

                <div class="k_data_form_hint">
                    <?php echo $sFormHint; ?>
                </div>

                <?php $form->formErrors(); ?>
        
                <?php $form->display(); ?>
                    
                <div style="text-align:center; margin:10px;">
                    <?php $html->url('Places Return', 'administration', 1, array(), 1, 'arrow_undo'); ?>
                </div>

                </div>

                <?php echo DesignBoxContent ($sAddBoxName, ob_get_clean(), 1 ); ?>
                
            </td>

        </tr>
        
    </table>
        
<?php include('_footer.php') ?>
