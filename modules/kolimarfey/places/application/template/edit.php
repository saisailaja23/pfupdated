<?php include('_header.php') ?>

<?php ob_start(); ?>

<div class="bx_sys_default_padding">

<?php $form->formErrors(); ?>
    
<?php $form->display(); ?>

</div>

<?php echo DesignBoxContent ($sTitle, ob_get_clean(), 1 ); ?>

<?php include('_footer.php') ?>
