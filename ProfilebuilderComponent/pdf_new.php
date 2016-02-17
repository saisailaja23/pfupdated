<?php

//ini_set('display_errors', '1');
//error_reporting(E_ALL);
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

require_once ('../inc/header.inc.php');
$id = $_GET['id']; 
$site_url = $site['url'];
$image_path='/var/www/html/pf/';
$con = file_get_contents($site_url.'ProfilebuilderComponent/processors/fastFact.php?id='.$id,false, stream_context_create($arrContextOptions));

$a = json_decode($con);
$site_url = 'https://www.parentfinder.com/';
if($a->status == 'success'){
	$pnt = $a->data;
	$p1_FirstName = $pnt->p1_FirstName;
	$p2_FirstName = $pnt->p2_FirstName;
	if($p2_FirstName){ $p_name = $p1_FirstName.' AND '.$p2_FirstName; }
	else{ $p_name = $p1_FirstName; }

	if($pnt->show_contact == 1){
		$contact_no = $pnt->phonenumber;
	}else{
		$contact_no = $pnt->agency_contact;
	}
	if($pnt->p1_RelationshipStatus){

		$p1_RelationshipStatus = $pnt->p1_RelationshipStatus;
	}
	else{
		$p1_RelationshipStatus = 'Not Specified';
	}
	if($pnt->p1_State){

		$p1_State = $pnt->p1_State;
	}
	else{
		$p1_State = 'Not Specified';
	}
	if($pnt->p1_waiting){

		$p1_waiting = $pnt->p1_waiting;
	}
	else{
		$p1_waiting = 'Not Specified';
	}
	if($pnt->FamilyStructure){

		$FamilyStructure = $pnt->FamilyStructure;
	}
	else{
		$FamilyStructure = 'Not Specified';
	}
	
	
	if($pnt->Avatar){
		//$img_them_src = $site_url.$pnt->alb_imgs->data[0];
		$size=getimagesize($site_url.$pnt->Avatar);
		
		if($size[0]>0){
			$img_them_src = $site_url.$pnt->Avatar;
			$h_img = 	'<td valign="middle" style="width:300px; height:228px;">						
							
							<img src="'.$img_them_src.'" alt="'.$p_name.'">
							
					</td>';
		}else{
			$img_them_src = '';
			$h_img = '<td valign="middle" style="width:300px; height:228px;"><div style="float:right; width: 300px;"></div></td>';
	}		
	}else{
		$img_them_src = '';
		$h_img = '<td valign="middle" style="width:300px; height:228px;"><div style="float:right; width: 300px;"></div></td>';
	}
	
	//attaching letter
	/**/
	if($p2_FirstName != ''){
		if($pnt->letter_aboutThem != ''){
		$letter_them = 1;
		$letter_about_them=$pnt->letter_aboutThem;
		if(strlen($letter_about_them)>700){
			$letter_about_them=substr($letter_about_them, 0, 700).'...';
		}
		
		$letter = 	'<td style="color:#FFF; font-size:11px; line-height:13px; height:220px; width:280px; overflow:hidden; padding-left:10px">
						'.preg_replace("/<img[^>]+\>/i", "", $letter_about_them).'	
					</td>';
		
		}else{
			$letter_them = 0;
			$letter = 	'<td></td>';
		}
	
	}else{
		if($pnt->DescriptionMe != ''){
		$letter_them = 1;
		$letter_about_them=$pnt->DescriptionMe;
		if(strlen($letter_about_them)>700){
			$letter_about_them=substr($letter_about_them, 0, 700).'...';
		}
		$letter = 	'<td style="color:#FFF; font-size:11px; line-height:13px; height:220px; width:250px; overflow:hidden; padding-left:10px">
						'.preg_replace("/<img[^>]+\>/i", "", $letter_about_them).' 	
					</td>';
		
		}else{
			$letter_them = 0;
			$letter = 	'<td></td>';
		}
	}
	
	
	if($img_them_src == '' && $letter_them == 0){
		$tr_height = '';
	}else{
		$tr_height = 'height: 231px;';
	}
	
	$img_let_div = $h_img.$letter;
	
	//second album image right
	if($pnt->alb_imgs->status == 'success' && $pnt->alb_imgs->data[1]){
		$size=getimagesize($site_url.$pnt->alb_imgs->data[1]);
		if($size[0]>0){
		$alb_src = $site_url.'ProfilebuilderComponent/resize.php?src='.$site_url.$pnt->alb_imgs->data[1].'&h=148&mxw=168&mxh=148';
		$right_image = 	'<img src="'.$alb_src.'">
						';
		}else{
		$right_image = 	'<div style="float:right; width: 175px;"></div>';
		}
	}else{
		$right_image = 	'<div style="float:right; width: 175px;"></div>';
	}

	/**/
	$cnt = 2;

	//1
	$t_cnt = round(strlen($pnt->p1_Religion)/35);
	if($t_cnt==0) $cnt++;
	else $cnt = $cnt+$t_cnt;

	//2
	$t_cnt = round(strlen($pnt->p1_Ethnicity)/35);
	if($t_cnt==0) $cnt++;
	else $cnt = $cnt+$t_cnt;

	//3
	$t_cnt = round(strlen($pnt->p1_Education)/35);
	if($t_cnt==0) $cnt++;
	else $cnt = $cnt+$t_cnt;

	//4
	$t_cnt = round(strlen($pnt->p1_DateOfBirth)/35);
	if($t_cnt==0) $cnt++;
	else $cnt = $cnt+$t_cnt;

	//5
	$t_cnt = round(strlen($pnt->p1_Sex)/35);
	if($t_cnt==0) $cnt++;
	else $cnt = $cnt+$t_cnt;

	$height = ($cnt*16.18)-16;

	if($height < 160) $height = 160; 

	
	$p_det = '';

	$p1_Religion = $p2_Religion ='Religion Not Specified';
	$p1_Ethnicity = $p2_Ethnicity ='Ethnicity Not Specified'; 
	$p1_Education = $p2_Education = 'Education Not Specified'; 
	$p1_DateOfBirth = $p2_DateOfBirth =  'Date of birth Not Specified';
	$p1_Sex = $p2_Sex =  'Sex Not Specified';
	$ChildAge = $ChildGender =  $ChildEthnicity = $AdoptionType =  "Not Specified";
	
	if($pnt->p1_Religion != '' && $pnt->p1_Religion != 'Not Specified') $p1_Religion = $pnt->p1_Religion;
	if($pnt->p2_FirstName != ''){
		if($pnt->p2_Religion != ''  && $pnt->p2_Religion != 'Not Specified') $p2_Religion = $pnt->p2_Religion;
	}else{
		$p2_Religion = '';
	}

	if($pnt->p1_Ethnicity != ''  && $pnt->p1_Ethnicity != 'Not Specified') $p1_Ethnicity = $pnt->p1_Ethnicity;
	if($pnt->p2_FirstName != ''){
		if($pnt->p2_Ethnicity != '' && $pnt->p2_Ethnicity != 'Not Specified') $p2_Ethnicity = $pnt->p2_Ethnicity;
	}else{
		$p2_Ethnicity='';
	}
	
	if($pnt->p1_Education != '' && $pnt->p1_Education != 'Not Specified') $p1_Education = $pnt->p1_Education;
	if($pnt->p2_FirstName != ''){
		if($pnt->p2_Education != '' && $pnt->p2_Education != 'Not Specified') $p2_Education = $pnt->p2_Education;
	}else{
		$p2_Education ='';
	}
	
	if($pnt->p1_DateOfBirth != '' && $pnt->p1_DateOfBirth != 'Not Specified') $p1_DateOfBirth = $pnt->p1_DateOfBirth.' Years Old';
	if($pnt->p2_FirstName != ''){
		if($pnt->p2_DateOfBirth != '' && $pnt->p2_DateOfBirth != 'Not Specified') $p2_DateOfBirth = $pnt->p2_DateOfBirth.' Years Old';
	}else{
		$p2_DateOfBirth='';
	}
	
	if($pnt->p1_Sex != '' && $pnt->p1_Sex != 'Not Specified') $p1_Sex = $pnt->p1_Sex;
	if($pnt->p2_FirstName != ''){
		if($pnt->p2_Sex != '' && $pnt->p2_Sex != 'Not Specified') $p2_Sex = $pnt->p2_Sex;	
	}else{
		 $p2_Sex ='';
	}
	
	if($pnt->ChildAge != '') $ChildAge = str_replace(",", ", ", strip_tags($pnt->ChildAge));
	if($pnt->ChildGender != '') $ChildGender = str_replace(",", ", ", strip_tags($pnt->ChildGender));
	if($pnt->ChildEthnicity != '') $ChildEthnicity = str_replace(",", ", ", strip_tags($pnt->ChildEthnicity));
	if($pnt->Adoptiontype != '') $AdoptionType =  str_replace(",", ", ", strip_tags($pnt->Adoptiontype));
	
	$p_det .= 	'<tr>
					<td style="padding: 3px 0px;" valign="top">'.$p1_Religion.'</td>
					<td style="padding: 3px 0px;" valign="top">'.$p2_Religion.'</td>
				</tr>
				<tr>
					<td style="padding: 3px 0px;" valign="top">'.$p1_Ethnicity.'</td>
					<td style="padding: 3px 0px;" valign="top">'.$p2_Ethnicity.'</td>
				</tr>
				<tr>
					<td style="padding: 3px 0px;" valign="top">'.$p1_Education.'</td>
					<td style="padding: 3px 0px;" valign="top">'.$p2_Education.'</td>
				</tr>
				<tr>
					<td style="padding: 3px 0px;" valign="top">'.$p1_DateOfBirth.'</td>
					<td style="padding: 3px 0px;" valign="top">'.$p2_DateOfBirth.'</td>
				</tr>
				<tr>
					<td style="padding: 3px 0px;" valign="top" style="padding: 3px 0px;">'.ucfirst($p1_Sex).'</td>
					<td style="padding: 3px 0px;" valign="top" style="padding: 3px 0px;">'.ucfirst($p2_Sex).'</td>
				</tr>';
				

/**/
	//$bottom_image = '<div style="background-color:#A0CF6D; overflow:hidden; padding:10px 20px;">';
	$bottom_image = '';
	if($pnt->alb_imgs->status == 'success'){
		if($pnt->alb_imgs->data[2]){
		$size=getimagesize($site_url.$pnt->alb_imgs->data[2]);
		if($size[0]>0){
			$alb_imgs1_src = $site_url.'ProfilebuilderComponent/resize.php?src='.$site_url.$pnt->alb_imgs->data[2].'&h=156&mxw=176&mxh=156';
			$bottom_image .= '<td style=" width:176px; height:156px;text-align:center;border:1px solid #5b862c;">
										<img src="'.$alb_imgs1_src.'">

								
							</td>';
		}else{
			$bottom_image .= '<td style=" width:176px; height:156px;">&nbsp;</td>';
		}	
		}else{
			$bottom_image .= '<td style=" width:176px; height:156px;">&nbsp;</td>';
		}	
		
		if($pnt->alb_imgs->data[3]){
		$size=getimagesize($site_url.$pnt->alb_imgs->data[3]);
		if($size[0]>0){
			$alb_imgs1_src = $site_url.'ProfilebuilderComponent/resize.php?src='.$site_url.$pnt->alb_imgs->data[3].'&h=156&mxw=176&mxh=156';
			$bottom_image .= '<td style=" width:176px; height:156px;text-align:center;border:1px solid #5b862c;">								
									<img src="'.$alb_imgs1_src.'" >
								
							</td>';
			}else{
			$bottom_image .= '<td style=" width:176px; height:156px;">&nbsp;</td>';
		}	
		}else{
			$bottom_image .= '<td style=" width:176px; height:156px;">&nbsp;</td>';
		}				
		
		if($pnt->alb_imgs->data[4]){
		$size=getimagesize($site_url.$pnt->alb_imgs->data[4]);
		if($size[0]>0){
			$alb_imgs1_src = $site_url.'ProfilebuilderComponent/resize.php?src='.$site_url.$pnt->alb_imgs->data[4].'&h=156&mxw=176&mxh=156';
			$bottom_image .= '<td style=" width:176px; height:156px;text-align:center;border:1px solid #5b862c;">							
										<img src="'.$alb_imgs1_src.'" >
									
							</td>';
		}else{
			$bottom_image .= '<td style=" width:176px; height:156px;">&nbsp;</td>';
		}	
		}else{
			$bottom_image .= '<td style=" width:176px; height:156px;">&nbsp;</td>';
		}
		
	}

	if(strlen($contact_no) > 2){
		$contact_top = 'Contact Us At:  <span class="p_contact">'.$contact_no.'</span><img src="'.$image_path.'templates/tmpl_par/images/icon_phone_header.png" style="margin-left:10px; ">';
		$contact_bottom = 'Contact Us At: '.$contact_no.'<img src="'.$image_path.'/templates/tmpl_par/images/icon_phone_header.png" style="margin-left:10px; ">';
	}else{ $contact_bottom = ''; $contact_top = ''; }


	require_once "dompdf/dompdf_config.inc.php";

	$dompdf = new DOMPDF();

	$html='
		<body style="font-family:sans-serif;">
			<div style="width:595px; height:auto; margin:auto;">
				<table style="width:595px;border:0px solid;">
					<tbody>
						<tr>
							<td style="color:#43A8DE !important;width:300px;border:0px solid;"><h1 id="p_name" style="color:#43A8DE !important; float:left; margin:0px; padding:10px 0px 0px 0px;font-size:15px; "><span  style="color:#43A8DE !important;">'.$p_name.'</span></h1></td>
							<td style="text-align:right;border:0px solid;"><h3 class="contact_div" style="color:#43A8DE;float:right;font-size:15px;padding:10px 0px 0px 0px;margin:0px;">'.$contact_top.'</h3></td>
						</tr>
					</tbody>
				</table>
				<div id="img_let_div" style="overflow: hidden;width:595px; padding: 3px 0px; '.$tr_height.' background-color: rgb(160, 207, 109);">
					<table>
						<tr>
							'.$img_let_div.'
						</tr>
					</table>
				</div>
				<div style="margin:10px 5px;width:560px;">
					
					<table>
						<tbody>
							<tr>
								<td valign="top">
									<table width="360" style="font-size:10px;font-weight:normal;color:rgb(58, 53, 54);line-height:normal">  <!-- parent details -->
										<tbody>
										<tr style="color:#43A8DE; text-align:left; font-size:12px; padding: 5px 0px 5px 0px;font-weight:bold;">';	
										    if($pnt->p1_FirstName && $pnt->p2_FirstName){
											$html.='<td width="184px" id="p1_FirstName" >'.$pnt->p1_FirstName.'</td>';
											$html.='<td width="184px" id="p2_FirstName" >'.$pnt->p2_FirstName.'</td>';
										}
										else if( $pnt->p1_FirstName){
											$html.='<td width="184px" id="p1_FirstName" >'.$pnt->p1_FirstName.'</td>';
											
										} else{
											
											$html.='<td width="184px" id="p2_FirstName">'.$pnt->p2_FirstName.'</td>';
										}
											
									$html.='</tr>
										'.$p_det.'
										<tr style="color:#43A8DE; text-align:left; font-size:12px; font-weight:bold;">
												<td colspan="2"><h3 style="margin: 11px 0px 0px 0px; ">';
													if($pnt->p1_FirstName && $pnt->p2_FirstName){
														$html.="Our Adoption Preferences";
													}else{
														$html.="My Adoption Preferences";
													}
												$html.='</h3></td>
											</tr>	
										</tbody>
									</table>
								</td>
								<td style="width:168px;height:148px;border: 5px solid #A0CF6D;text-align:center;">
										'.$right_image.'
								</td>
							</tr>
							
							<tr><!-- adoption preferences -->
								<td valign="top">
									<table width="370" style="table-layout:fixed;font-size:10px;font-weight:normal;color:rgb(58, 53, 54);line-height:normal;overflow:hidden">  
										<tbody>
											
											<tr>
												<td valign="top" style="width:80px;padding: 4px 0px;">AGE</td>
												<td valign="top" style="width:5px;padding: 4px 0px; ">:</td>
												<td valign="top" style="width:252px;padding: 4px 0px; ">
													<div id="ChildAge" style="width:250px;">
														'.$ChildAge.'
													</div>
												</td>
											</tr>
											<tr>
												<td valign="top" style="width:80px;padding: 4px 0px;">GENDER</td>
												<td valign="top" style="width:5px;padding: 4px 0px;">:</td>
												<td valign="top" style="width:252px;padding: 4px 0px;">
													<div id="ChildGender">'.$ChildGender.'</div>
												</td>
											</tr>
											<tr>
												<td valign="top" style="width:80px;padding: 4px 0px;">ETHNICITY</td>
												<td valign="top" style="width:5px;padding: 4px 0px;">:</td>
												<td valign="top" style="width:252px;padding: 4px 0px;">
													<div style="width:252px; "><span>'.$ChildEthnicity.'</span></div>
												</td>
											</tr>
											<tr>
												<td valign="top" style="width:80px;padding: 4px 0px;">TYPE</td>
												<td valign="top" style="width:5px;padding: 4px 0px;">:</td>
												<td valign="top" style="width:252px;padding: 4px 0px;">
													<div id="AdoptionType" style="width:252px;">'.$AdoptionType.'</div>
												</td>
											</tr>
											<tr style="color:#43A8DE; text-align:left; font-size:12px; padding: 3px 0px ;font-weight:bold">
												<td valign="top" style="padding: 2px 0px; width:80px;">Online Profile</td>
												<td valign="top" style="width:5px; padding: 2px 0px;">:</td>
												<td valign="top" width="252" style="padding: 2px 0px;width:252px ">
													<span id="portal_link">'.$pnt->portal_link.'</span>
												</td>
											</tr>
											<tr style="color:#43A8DE; text-align:left; font-size:12px; padding: 3px 0px ;font-weight:bold">
												<td valign="top" style="padding: 2px 0px; width:80px;">Email</td>
												<td valign="top" style="width:5px; padding: 2px 0px;">:</td>
												<td valign="top" style="padding: 2px 0px;width:252px ">
													<span id="Email">'.$pnt->agency_email.'</span>
												</td>
											</tr>	
										</tbody>
									</table>
								</td>

								<td style="float:right;width:178px;" valign="top">
									<table style="table-layout:fixed;font-size:10px;font-weight:normal;color:rgb(58, 53, 54);line-height:normal">  
										<tbody>
											
											<tr>
												<td  valign="top" style="width:60px;padding:3px 0px">Relationship</td>
												<td  valign="top" style="padding:3px 0px;">
													: </td>
													<td  valign="top" style="padding:3px 0px;">'.$p1_RelationshipStatus.'</td>
												
												
											</tr
											<tr>
												<td  valign="top" style="width:60px;padding:3px 0px">State</td>
												<td  valign="top" style="padding:3px 0px;">:</td>
												<td  valign="top" style="padding:3px 0px;">'.$p1_State.'</td>
												

											</tr>
											<tr>
												<td valign="top" style="width:60px;padding:3px 0px">Waiting</td>
												<td  valign="top" style="padding:3px 0px;">
													 : </td>
													<td  valign="top" style="padding:3px 0px;">'.$p1_waiting.'</td>
											
												
											</tr>
											<tr>
												<td  valign="top" style="width:60px;padding:3px 0px">Family</td>
												<td  valign="top" style="padding:3px 0px;">
													 : </td>
													<td  valign="top" style="padding:3px 0px;">'.$FamilyStructure.'</td>
																								
												
											</tr>
											
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>

					
				</div>
				 
				

				<div id="bottom_div" style="clear:left">
				
					<table  style="table-layout:fixed;width:100%;background-color:#A0CF6D; padding:6px 15px;page-break-inside:avoid">
					
						<tr >
						'.$bottom_image.'
						</tr>
					</table>
				
				</div>

				<table style="width:595px;">
					<tr>
						<td style="width:300px;"><h3 class="contact_div" style="color:#43A8DE; float:left; margin:0px; padding:0px;font-size:15px; ">'.$pnt->agency_name.'</h3></td>
						<td style="text-align:right;"><h3 class="contact_div" style="color:#43A8DE;float:right;font-size:15px; color:#43A8DE;margin:0px;padding:0">'.$contact_bottom.'</h3></td>
					</tr>
				</table>

					
				
			</div>
				
		</body>';
		
	//echo $html; exit;

	$dompdf->load_html($html);
	$dompdf->render();

	$dompdf->stream($p_name.".pdf");

}

?>