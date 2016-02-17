
<div class="<?php echo $sClass; ?>">

    <?php if (!$aPlaces): ?>
        
        <?php echo $this->t('Places No Places'); ?>

    <?php else: ?>

        <?php foreach ($aPlaces as $aPlace): ?> 
            <table class="k_snippet" style="<?php echo $sStyle; ?>">
            <tr>
                <td class="k_thumb" valign="top">
            <div class="k_thumb"><a href="<?php $html->href('view/'.$aPlace['pl_uri']); ?>"><?php $html->thumb($aPlace['pl_thumb']); ?></a></div>
                </td>
                <td class="k_info" valign="top">
                    <div class="k_title"><?php $html->url($aPlace['pl_name'], 'view/'.$aPlace['pl_uri'], 0); ?></div>
                    <div class="k_field"><b><?php echo $this->t('Places Category'); ?>:</b> <u><?php echo $this->t($aPlace['pl_cat_name']); ?></u></div>
                    <?php if ($aPlace['NickName']): ?>
                    <div class="k_field"><b><?php echo $this->t('Places Posted By'); ?>:</b> <u><?php $this->helperhtml->url($aPlace['NickName'], getProfileLink($aPlace['pl_author_id']), false); ?></u></div>
                    <?php endif; ?>
                    <div class="k_field"><b><?php echo $this->t('Places Created'); ?>:</b> <u><?php echo $aPlace['pl_created_f']; ?></u></div>
                </td>
            </tr>
            </table>            
        <?php endforeach; ?>
        
        <div class="clear_both"></div>

        <?php if (isset($sLinkAll) && $sLinkAll): ?>
            <div style="text-align:center;"><a href="<?= $sLinkAll ?>"><?= $this->t('View All'); ?></a></div>
        <?php endif; ?>

    <?php endif; ?>

</div>
