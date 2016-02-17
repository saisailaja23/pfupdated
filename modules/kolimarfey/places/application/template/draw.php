<?php include('_header.php') ?>

<?php ob_start(); ?>

<div class="bx_sys_default_padding">

<table cellspacing="0" cellpadding="0" style="margin-bottom:10px; width:100%">
    <td style="width:10%"><?php $html->url('Places Return', 'view/' . $aPlace['pl_uri'], 1, array(), 1, 'arrow_undo'); ?></td>
    <td style="text-align:center; font-weight:bold;"><?php echo $aPlace['pl_name']; ?></td>
</table>

<?php echo $sMap; ?>

<div style="margin-top: 10px;">
    <?php echo $this->t('Places drawing desc'); ?>
</div>

</div>

<?php echo DesignBoxContent ($this->t('Places Draw'), ob_get_clean(), 1 ); ?>

<?php include('_footer.php') ?>
