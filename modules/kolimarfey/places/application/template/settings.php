<?php include('_header.php') ?>


<?php ob_start(); ?>

<div class="bx_sys_default_padding">

<?php $form->formErrors(); ?>
    
<?php $form->display(); ?>

</div>

<?php echo DesignBoxContent ($this->t($aDesc['title']), ob_get_clean(), 1 ); ?>


<?php ob_start(); ?>

<div class="bx_sys_default_padding">

<?php echo $this->helperhtml->{$aDesc['func']}($aDesc['args']); ?>

</div>

<?php echo DesignBoxContent ($this->t('Places Preview'), ob_get_clean(), 1 ); ?>


<?php include('_footer.php') ?>
