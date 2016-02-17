<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
       	$qb_log_file = "paymentlog"."_".date('Y-m-d').".txt";
        ob_start();
        echo "\n  Date : ".date("Y-m-d"). "\n";
        print_r($_POST);
        $out1 = ob_get_contents();
        ob_end_clean();
        $log = fopen($qb_log_file, 'a+') ;
        fwrite($log, $out1 );
        fclose($log);
?>
