<?php include('_header.php') ?>
    
    
    <table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">

            <?php ob_start(); ?>

                <div class="bx_sys_default_padding">

                <?php if ($aCats) : ?>

                    <?php foreach ($aCats as $a) : ?>
                    
                        <?php
                            if ($a['pl_cat_id'] <= 1) 
                                continue;
                            ?>
                            <div class="k_drilldown_cat_row">
                                <img src="<?php echo PLACES_ICONS_PATH . ($a['pl_cat_icon'] ? $a['pl_cat_icon'] : '0.png'); ?>" />
                                <?php
                            $html->url ($a['pl_cat_name'], "category/".$a['pl_cat_id'], true);
                                ?> (<?=$a['num'] ?>)
                            </div>

                    <?php endforeach; ?>

                <?php else : ?>

                    <?php echo $this->t('Places No data so far'); ?>

                <?php endif; ?>

                </div>

            <?php echo DesignBoxContent ($this->t('Places Drilldown Cats'), ob_get_clean(), 1 ); ?>
    
        </td>        
        <td width="10">
            &#160;
        </td>
        <td valign="top">

            <?php ob_start(); ?>

                <div class="bx_sys_default_padding">

                <?php if ($aCountries) : ?>

                    <?php foreach ($aCountries as $a) : ?>
                    
                        <div class="k_drilldown_country_row">

                            <img src="<?php echo BX_DOL_URL_ROOT . 'media/images/flags/' . strtolower($a['code']) . '.gif'; ?>" />

                        <?php $html->url ('_'.$a['name'], "country/".$a['code'], true); ?> (<?=$a['num'] ?>) <br />

                        </div>

                    <?php endforeach; ?>

                <?php else : ?>

                    <?php echo $this->t('Places No data so far'); ?>

                <?php endif; ?>                            

                </div>

            <?php echo DesignBoxContent ($this->t('Places Drilldown Countries'), ob_get_clean(), 1 ); ?>
    
        </td>        
    </tr>
    </table>


<?php include('_footer.php') ?>
