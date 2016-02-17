<?php include('_header.php') ?>

    <?php ob_start(); ?>

    <div class="bx_sys_default_padding">

    <script type="text/javascript"><!--

        function onPlacesSearchFormSubmit(f) {
            return true;
        }

    //-->
    </script>

    <form action="<?php $html->href('search/0'); ?>" method="GET" onsubmit="return onPlacesSearchFormSubmit(this)">

        <table cellpadding="5" align="center">
            <tr>
                <td align="right"><?= $this->t('Places Search Country:'); ?></td>
                <td align="left">
                    <select id="places_search_r" name="r" style="width:150px;">
                        <option value=""><?php echo $this->t('Places All'); ?></option>
                    <?php foreach ($aCountries as $k => $v): ?>
                        <option value="<?php echo $k; ?>"><?php echo $this->t("_$v"); ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><?= $this->t('Places Search City:'); ?></td>
                <td align="left"><input id="places_search_c" name="c" /></td>
            </tr>
            <tr>
                <td align="right"><?= $this->t('Places Search Keyword:'); ?></td>
                <td align="left"><input id="places_search_q" name="q" /></td>
            </tr>
            <tr>
                <td>&#160;</td>
                <td><input type="submit" name="s" value="<?= $this->t('Places Submit'); ?>" /></td>
            </tr>
        </table>

    </form>

    </div>

    <?php 
        if ($isDesignBox) 
            echo DesignBoxContent ($this->t('Places Search'), ob_get_clean(), 1 ); 
        else 
            echo ob_get_clean(); 
    ?>
        
<?php include('_footer.php') ?>
