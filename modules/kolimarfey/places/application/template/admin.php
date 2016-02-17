<?php include('_header.php') ?>
    
    
    <table width="100%">
        <td valign="top">            
            <?php ob_start(); ?>
            <div class="bx_sys_default_padding">
                <table>
                    <tr><td><?php echo $this->t('Places Stat Total:'); ?></td><td><?php echo ($aStat['all']); ?></td></tr>
                    <tr><td><?php echo $this->t('Places Stat 24h:'); ?></td><td><?php echo ($aStat['24h']); ?></td></tr>
                    <tr><td><?php echo $this->t('Places Stat Month:'); ?></td><td><?php echo ($aStat['month']); ?></td></tr>
                    <tr><td><?php echo $this->t('Places Stat Pending Approval:'); ?></td><td><?php echo ($aStat['pending']); ?></td></tr>
                </table>                    
            </div>
            <?php echo DesignBoxContent ($this->t('Places Admin Stat'), ob_get_clean(), 1 ); ?>
        </td>
        <td valign="top" align="right">
            <?php ob_start(); ?>
            <div class="bx_sys_default_padding">
            <ul class="k_list">
                <li><?php $html->url('Places View Place Map Settings', 'settings/2'); ?></li>
                <li><?php $html->url('Places Home Map Settings', 'settings/4'); ?></li>
                <li><?php $html->url('Places Edit Place Map Settings', 'settings/8'); ?></li>
                <li><?php $html->url('Places Categories Editor', 'admin_categories'); ?></li>
                <li><?php $html->url('Places Pending Approval', 'pending'); ?></li>
            </ul>
            </div>
            <?php echo DesignBoxContent ($this->t('Places Admin Links'), ob_get_clean(), 1 ); ?>


            <?php ob_start(); ?>

            <div class="bx_sys_default_padding">

            <?php $form->formErrors(); ?>
    
            <?php $form->display(); ?>

            </div>

            <?php echo DesignBoxContent ($this->t('Places Basic settings'), ob_get_clean(), 1 ); ?>
    
        </td>        
    </table>


<?php include('_footer.php') ?>
