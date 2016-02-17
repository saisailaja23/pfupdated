<?php
error_reporting(0);
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 * @package default
 */


// get the HTML

ob_start();

$invoice_id = $_GET["invoice_id"]; // mandatory, must to be unique
$invoice_pay_for_desc = $_GET["pay_for_desc"];// mandatory. payment description
$fees = $_GET['fees'];
$subtotal = $_GET['subtotal'];
$total = $_GET['total'];
$currency = $_GET["currency"]; // mandatory, must to be unique

$pdf_saving_path = $_GET["pdf_saving_path"]; // mandatory, must to be unique

if (isset( $_GET["timezone"] )) {
    date_default_timezone_set( $_GET["timezone"] );
}
else {
    date_default_timezone_set( 'America/New_York' );
}



// $agency_name = $_GET["agency_name"]; // mandatory
// $agency_email = $_GET["agency_email"]; // mandatory
// $agency_address1 = $_GET["agency_address1"]; // mandatory
// $agency_address2 = $_GET["agency_address2"]; // not mandatory
// $agency_city = $_GET["agency_city"]; // mandatory
// $agency_state = $_GET["agency_state"]; // mandatory
// $agency_zipcode = $_GET["agency_zipcode"]; // mandatory
// $agency_country = $_GET["agency_country"]; // mandatory]; two ISO letters
// $agency_phonenumber = $_GET["agency_phonenumber"]; // mandatory
// $agency_faxnumber = $_GET["agency_faxnumber"]; // not mandatory

$agency_name = "CAIRS Solutions"; // mandatory
$agency_email = "Mark.Livings@cairsolutions.com"; // mandatory
$agency_address1 = "LLC11643 Grove Street North"; // mandatory
$agency_address2 = " "; // not mandatory
$agency_city = "Seminole"; // mandatory
$agency_state = "FL"; // mandatory
$agency_zipcode = "33772"; // mandatory
$agency_country = "USA"; // mandatory]; two ISO letters
$agency_phonenumber = "+1 727-394-3980"; // mandatory
$agency_faxnumber = "+1 727-394-3980"; // not mandatory
$terms_and_conditions = $_GET["terms_and_conditions"]; // not mandatory

$customer_firstName = $_GET["customer_firstName"]; // mandatory
$customer_lastName = $_GET["customer_lastName"]; // mandatory
$customer_email = $_GET["customer_email"]; // mandatory
$customer_address1 = $_GET["customer_address1"]; // mandatory
// ====== not mandatory area
$customer_address2 = $_GET["customer_address2"]; // not mandatory
$customer_city = $_GET["customer_city"]; // not mandatory
$customer_state = $_GET["customer_state"]; // not mandatory
$customer_zipcode = $_GET["customer_zipcode"]; // not mandatory
$customer_country = $_GET["customer_country"]; // not mandatory, two ISO letters
$customer_phonenumber = $_GET["customer_phonenumber"]; // not mandatory
$customer_mobilenumber = $_GET["customer_mobilenumber"]; // not mandatory


if (isset($_GET["agency_logo"])) {
    $agency_logo = $_GET["agency_logo"]; // not mandatory
}
else {
    $agency_logo = '../../../asserts/logos/cairs_logo.png'; // not mandatory
}

$itemsarray = $_GET["itemsarray"]; // not mandatory
$nrows = $_GET["nrows"];

$payment_status = $_GET["payment_status"];


$paid_gateway = $_GET["paid_gateway"];
$paid_transactionID = $_GET["paid_transactionID"];
$paid_token = $_GET["paid_token"];
$paid_date = $_GET["paid_date"];

parse_str($itemsarray);

?>
<style type="text/css">
<!--
    div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;border:solid #999 0mm}
  table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
  table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}
    h1 { padding: 0; margin: 0; color: #DD0000; font-size: 6mm; }
    h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
  table.detail{ font-size: 3mm; }
  table.detail th{ font-size: 3.2mm; padding:1mm;}
  table.detail td{ padding:1mm; }
-->
</style>

<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 12pt" style="font: arial;">

    <div style="rotate: 90; position: absolute; width: 100mm; height: 4mm; left: 190mm; top: 0; font-style: italic; font-weight: normal; text-align: center; font-size: 2.5mm;">
        Powered by CAIRS technology
    </div>
    <table style="width: 99%;border: none;" cellspacing="4mm" cellpadding="0">
        <tr>
            <td style="width: 100%">
                <div class="zone" style="height: 25mm;position: relative;font-size: 3mm;">
                    <div style="position: absolute; right: 3mm; top: 3mm; text-align: right; font-size: 3mm; ">
                        <span style=" font-size: 4mm; "><b><?php echo $agency_name; ?></b></span>
                        <br><?php echo $agency_address1;?> <?php echo $agency_address2;?> <br />
                        <?php echo $agency_state;?> <?php echo $agency_zipcode;?> <br />
            <?php echo $agency_country;?><br>
            Phone: <?php echo $agency_phonenumber;?><br />
            <?php if ( strlen( $agency_faxnumber ) > 3 )echo 'Fax: ' . $agency_faxnumber;?>
                    </div>
                    <img src="<?php echo $agency_logo;?>" alt="logo" width="320">
                </div>
                <div class="zone" style="height: 3mm;position: relative;font-size: 3mm; background:url(../../../asserts/imgs/invoice_header.jpg);">
                </div>
              <div class="zone" style="height: 30mm;position: relative;font-size: 3mm; top:6mm; top:10mm;">



                    <div style="position: absolute; left: 1mm; text-align: right; font-size: 3mm;">
                        <table width="307" border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td width="307" bgcolor="#EAEAEA" align="left" style="font-size:4mm;"><strong>Bill to:</strong></td>
                          </tr>
                          <tr>
                            <td width="307" align="left">
                                <b><?php echo $customer_firstName; ?> <?php echo $customer_lastName; ?></b><br />
                                <?php echo $customer_address1; ?>, <?php echo $customer_address2; ?><br />
                                <?php echo $customer_city; ?>,  <?php echo $customer_state; ?>  <?php echo $customer_zipcode; ?><br />
                                <?php echo $customer_country; ?>
                            </td>
                          </tr>
            </table>
                    </div>

                    <div style="position: absolute; right: 3mm; text-align: right; font-size: 3mm; ">

                        <table width="235" border="0" cellspacing="2" cellpadding="1" style="font-size: 3.5mm; ">
                          <tr>
                            <td width="105" align="left" valign="middle" bgcolor="#EAEAEA"><strong>Invoice #:</strong></td>
                            <td width="123" align="left" valign="middle"><?php echo $invoice_id;?></td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle" bgcolor="#EAEAEA"><strong>Invoice date:</strong></td>
                            <td align="left" valign="middle"><?php echo date('M/d/y  H:i:s');?></td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle" bgcolor="#EAEAEA"><strong>Surcharge fee :</strong></td>
                            <td align="left" valign="middle"><?php echo $currency;?> <?php echo $fees;?></td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle" bgcolor="#EAEAEA"><strong>Subtotal :</strong></td>
                            <td align="left" valign="middle"><?php echo $currency;?> <?php echo $subtotal;?></td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle" bgcolor="#EAEAEA"><strong>Total :</strong></td>
                            <td align="left" valign="middle"><?php echo $currency;?> <?php echo $total;?></td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle" bgcolor="#EAEAEA"><strong>Status :</strong></td>
                            <td align="left" valign="middle"><?php echo $payment_status;?></td>
                          </tr>
                        </table>

                    </div>

              </div>

            </td>
        </tr>

        <tr>
            <td colspan="2" style="width: 100%">
                <div class="zone" style="height: 40mm;font-size:2mm; top:10mm;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3" class="detail">
                      <tr bgcolor="#CAE4FF">
                        <th width="200" bgcolor="#EAEAEA">Item</th>
                        <th width="200" bgcolor="#EAEAEA">Payment description</th>
                        <th width="200" bgcolor="#EAEAEA" align="right">Amount</th>
                      </tr>

                      <?php
                        for ($i = 0; $i <= $nrows; $i++) {
                          $arrr = explode(" | ", $itemsarray[$i]);
                          echo '
                                        <tr>
                                          <td align="left">'.$arrr[0].'</td>
                                          <td width="200" align="left">'.$arrr[1].'</td>
                                          <td  align="right">'.$arrr[2].'</td>
                                          </tr>
                                      ';
                        };?>

                    </table>
                </div>
            </td>
         </tr>
         <tr>
            <td colspan="2" style="width: 100%;">
                <div class="zone" style="height: 40mm;font-size:3mm; top:10mm;">
                    <table width="100%" border="0" cellspacing="1" cellpadding="2">
                      <tr>
                        <td width="22%">Thanks for your business.</td>
                        <td width="78%" style="padding-left:40mm;">
                          <table width="47%" border="0" cellspacing="0" cellpadding="1" style="font-size:3.5mm;">
                              <tr>
                                <td width="108" style="border-bottom-color:#333; border-bottom-style:solid; border-bottom-width:0.5mm; padding-bottom:3mm;">Subtotal:</td>
                                <td width="268" align="right" style="border-bottom-color:#333; border-bottom-style:solid; border-bottom-width:0.5mm; padding-bottom:2mm;"><?php echo $currency . ' ' .$subtotal;?></td>
                              </tr>
                              <tr>
                                <td style=" padding-bottom:2mm; padding-top:2mm;">Total: </td>
                                <td style=" padding-bottom:2mm; padding-top:2mm;" align="right"><?php echo $currency . ' ' .$total;?></td>
                              </tr>

                              <?php
                                if ($payment_status == "paid") {
                                  ?>
                                  <tr>
                                    <td style="border-bottom-color:#333; border-bottom-style:solid; border-bottom-width:0.5mm; padding-bottom:2mm;">Payment made:</td>
                                    <td align="right" style="border-bottom-color:#333; border-bottom-style:solid; border-bottom-width:0.5mm; padding-bottom:3mm;">
                                  <?php
                                    echo $currency . ' ' .'(-) 0.00';
                                  ?>
                                   </td>
                                  </tr>
                                  <?php
                                  }
                                  else {
                              ?>

                               <tr>
                                <td style=" padding-top:2mm;">Balance Due:</td>
                                <td align="right" style=" padding-top:2mm;">
                              <?php
                                  echo $currency . ' ' . $total;
                                ?>
                                </td>
                              </tr>
                              <?php
                              }
                              ?>
                            </table>
                        </td>
                      </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width: 100%;">
                <div class="zone" style="height: 40mm;font-size:3.5mm; top:10mm;">
                    <strong>Terms & Conditions</strong><br />
          <?php echo $terms_and_conditions;?>
                </div>
            </td>
        </tr>
    </table>
</page>
<?php
//exit;
$content = ob_get_clean();




require_once('../html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    //$html2pdf->pdf->IncludeJS("print(true);");
    $html2pdf->writeHTML($content);
    $html2pdf->Output('invoice_'.$invoice_id.'.pdf');
    $html2pdf->Output($pdf_saving_path.'receipt_'.$invoice_id.'.pdf', 'F');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}