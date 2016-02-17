<?php include('_header.php') ?>

<?php ob_start(); ?>
    
<?php if ($ret): ?>

<font color="green"><?php echo ($this->t('Places Place has been successfully deleted')); ?></font>

<?php else: ?>
      
<font color="red"><?php echo ($this->t('Places Place delete failed')); ?></font>
        
<?php endif; ?>
        
<?php echo DesignBoxContent ($this->t('Places Delete'), ob_get_clean(), 1 ); ?>

<?php include('_footer.php') ?>
