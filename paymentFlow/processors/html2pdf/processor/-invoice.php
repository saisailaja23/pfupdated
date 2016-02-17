<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
    // get the HTML
	
    ob_start();
	
	$invoice_id = $_GET["invoice_id"];	// mandatory, must to be unique				
	$invoice_pay_for_desc = $_GET["pay_for_desc"];// mandatory. payment description
	$fees = $_GET['fees'];
	$subtotal = $_GET['subtotal'];
	$total = $_GET['total'];
	$currency = $_GET["currency"];	// mandatory, must to be unique
	
	if(isset( $_GET["timezone"] ))
	{
		date_default_timezone_set( $_GET["timezone"] );
	}
	else
	{
		date_default_timezone_set( 'America/New_York' );	
	}
	
	
	
	$agency_name = $_GET["agency_name"];	// mandatory
	$agency_email = $_GET["agency_email"];	// mandatory
	$agency_address1 = $_GET["agency_address1"];	// mandatory
	$agency_address2 = $_GET["agency_address2"];	// not mandatory
	$agency_city = $_GET["agency_city"];	// mandatory
	$agency_state = $_GET["agency_state"];	// mandatory
	$agency_zipcode = $_GET["agency_zipcode"];	// mandatory
	$agency_country = $_GET["agency_country"];	// mandatory]; two ISO letters
	$agency_phonenumber = $_GET["agency_phonenumber"];	// mandatory
	$agency_mobilenumber = $_GET["agency_mobilenumber"];	// not mandatory
	
	
	if(isset($_GET["agency_logo"]))
	{
		$agency_logo = '../../../logos/' . $_GET["agency_logo"];	// not mandatory
	}
	else
	{
		$agency_logo = '../../../logos/cairs_logo.png';	// not mandatory
	}
	
	$itemsarray = $_GET["itemsarray"];	// not mandatory
	$nrows = $_GET["nrows"];
	
	$payment_status = $_GET["payment_status"];
	
	
	$paid_gateway = $_GET["paid_gateway"];
	$paid_transactionID = $_GET["paid_transactionID"];
	$paid_token = $_GET["paid_token"];
	
	
	parse_str($itemsarray);
	
	
?>
<style type="text/css">
<!--
    div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;border:solid #999 0.2mm}
 	table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
	table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}
    h1 { padding: 0; margin: 0; color: #DD0000; font-size: 6mm; }
    h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
	table.detail{ font-size: 3.5mm; }
	table.detail th{ font-size: 4mm; padding:1mm;}
	table.detail td{ padding:1mm; }
-->
</style>
<!-- <page format="100x200" orientation="L" backcolor="#AAAACC" style="font: arial;"> -->
<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 12pt" style="font: arial;">
    
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 50%; text-align: left">
                    Document title
                </td>
                <td style="width: 50%; text-align: right">
                    PaymentFlow application
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 33%; text-align: left;">
                   <?php echo $agency_name; ?>
                </td>
                <td style="width: 34%; text-align: center">
                    page [[page_cu]]/[[page_nb]]
                </td>
                <td style="width: 33%; text-align: right">
                    &copy;CAIRS <?php echo date('Y');?>
                </td>
            </tr>
        </table>
    </page_footer>
    <div style="rotate: 90; position: absolute; width: 100mm; height: 4mm; left: 190mm; top: 0; font-style: italic; font-weight: normal; text-align: center; font-size: 2.5mm;">
        Powered by CAIRS technology
    </div>
    <table style="width: 99%;border: none;" cellspacing="4mm" cellpadding="0">
        <tr>
            <td style="width: 100%">
                <div class="zone" style="height: 60mm;position: relative;font-size: 3mm;">
                    <div style="position: absolute; right: 3mm; top: 3mm; text-align: right; font-size: 3.5mm; ">
                        <b><?php echo $agency_name; ?></b><br><?php echo $agency_address1;?> <?php echo $agency_address2;?> <br /> <?php echo $agency_state;?> - <?php echo $agency_country;?><br><?php echo $agency_phonenumber;?><?php if( strlen( $agency_mobilenumber ) > 3 )echo ' - ' . $agency_mobilenumber;?>
                    </div>
                    <div style="position: absolute; right: 3mm; top: 40mm; text-align: right; font-size: 4mm; ">
                      	Invoice #: <b><?php echo $invoice_id;?></b><br />
                        Surcharge fee : <b><?php echo $currency;?> <?php echo $fees;?></b><br />
                        Subtotal : <b><?php echo $currency;?> <?php echo $subtotal;?></b><br />
                    	Total : <b><?php echo $currency;?> <?php echo $total;?></b><br>
                    </div>
                    <img src="<?php echo $agency_logo;?>" alt="logo" style="margin-top: 3mm; ">
                    <div style="position: absolute; left: 3mm; top: 35mm; text-align: left; ">
                      	<?php 
					
							if($payment_status == "unpaid")
							{
								
							}
							else
							{
								echo '<br>
									<h1>Payment details</h1>';
								echo '&nbsp;&nbsp;&nbsp;&nbsp;<b>'. $payment_status .' - ' .date('d/m/Y  H:i:s'). '</b><br />';
							}
						
						?>
						<?php echo'&nbsp;&nbsp;&nbsp;&nbsp;Payment type: ' . $paid_gateway;?> 
						<?php if( strlen( $paid_transactionID ) > 3 )echo ' - authorization: ' . $paid_transactionID;?>
						<?php if( strlen( $paid_token ) > 3 )echo ' - token: ' . $paid_token;?>
						<br /><br />
						<?php echo $invoice_pay_for_desc; ?>
                    </div>
                    
                    
                </div>
                
                
            </td>
        </tr>
       
        <tr>
            <td colspan="2" style="width: 100%">
                <div class="zone" style="height: 40mm;vertical-align: middle; text-align: justify">
                    <table width="100%" border="0" cellspacing="1" cellpadding="3" class="detail">
                      <tr bgcolor="#CAE4FF">
                        <th width="490">Payment description</th>
                        <th width="150">Cost</th>
                      </tr>
                      
                      <?php 
					  	for ($i = 0; $i <= $nrows; $i++) {
							//echo $itemsarray[$i];
							
							$arrr = explode(" | ", $itemsarray[$i]);
							echo '
								<tr>
									<td align="left">'.$arrr[0].'</td>
									<td align="right">'.$arrr[1].'</td>
							  	</tr>
							';
						};?>

                    </table>

                </div>
            </td>
        </tr>
    </table>
</page>
<?php
	//exit;
     $content = ob_get_clean();

    // convert
    require_once(dirname(__FILE__).'/../html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 0);
        $html2pdf->pdf->SetDisplayMode('fullpage');
		//$html2pdf->pdf->IncludeJS("print(true);");
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('ticket.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

