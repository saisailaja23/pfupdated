$(document).ready(function() {
    var res = '';
    var par = '';
    $.ajax({
        url: site_url + "ProfilebuilderComponent/processors/fastFact.php?id=" + id,
        type: "POST",
        cache: false,
        datatype: "json",
        success: function(data) {
            res = JSON.parse(data);
            console.log(res.data);
            if (res.status == 'success') {
                par = res.data;
                if (par.p2_FirstName == null) {
                    $('#p_name').html(par.p1_FirstName);
                } else {
                    $('#p_name').html(par.p1_FirstName + ' AND ' + par.p2_FirstName);
                }

                //pdf link
                var pdf_url = site_url + "ProfilebuilderComponent/pdf.php?id=" + id;
                $("#pdfDownload").attr('href', pdf_url);

                var imgLtStat = 0;
                var imgLtStat2 = 0;
                var img_let_div = '';

                // if (par.alb_imgs.status == 'success' && par.alb_imgs.data[0]) {
                if (par.Avatar) {
                    imgLtStat = 1;
                    var img_path = site_url + par.Avatar;
                    //var img_path = site_url + 'ProfilebuilderComponent/resize.php?src=' + site_url + par.alb_imgs.data[0] + '&h=248&mxw=208&mxh=248';
                    //var img_path = site_url + 'ProfilebuilderComponent/resize.php?src=' + site_url + par.Avatar + '&h=248&mxw=208&mxh=248';

                    img_let_div = "<div style='float:left; width:300px; height:228px; overflow:hidden;position:relative;'> <img src='" + img_path + "' style='position:absolute;margin:auto;top:0;bottom:0;left:0;' ></div>";
                } else {
                    img_let_div = "<div style='float:left; width:300px; height:228px; ' ></div>";
                }


                if (par.p2_FirstName != null) {
                    if (par.letter_aboutThem != '') {
                        imgLtStat2 = 1;
                        var letter_about_them = par.letter_aboutThem;
                        if (letter_about_them.length > 700) {
                            letter_about_them = letter_about_them.substring(0, 700) + '...';
                        }
                        img_let_div = img_let_div + "<div id='letter_aboutThem' style='float:left; width:240px; padding-left:15px; height:247px; overflow: hidden; color:#FFF; font-size:11px; line-height:13px; '>" + letter_about_them + "</div>"
                    } else if (par.p2_FirstName == null) {
                        img_let_div = img_let_div + "<div id='letter_aboutThem' style='float:left; width:240px; padding-left:15px; height:247px; overflow: hidden; color:#FFF; font-size:11px; line-height:13px; '></div>"
                    }

                } else {
                    if (par.DescriptionMe != '') {
                        imgLtStat2 = 1;
                        var letter_about_them = par.DescriptionMe;
                        if (letter_about_them.length > 700) {
                            letter_about_them = letter_about_them.substring(0, 700) + '...';
                        }
                        img_let_div = img_let_div + "<div id='letter_aboutThem' style='float:left; width:240px; padding-left:15px; height:247px; overflow: hidden; color:#FFF; font-size:11px; line-height:13px; '>" + letter_about_them + "</div>"
                    } else if (par.p2_FirstName == null) {
                        img_let_div = img_let_div + "<div id='letter_aboutThem' style='float:left; width:240px; padding-left:15px; height:247px; overflow: hidden; color:#FFF; font-size:11px; line-height:13px; '></div>"
                    }
                }

                console.log(imgLtStat);
                console.log(imgLtStat2);

                if (imgLtStat == 1 || imgLtStat2 == 1) {
                    $('#img_let_div').css('height', '231px');
                    $('#img_let_div').html(img_let_div);
                }

                var Avatar = '';

                if (par.alb_imgs.status == 'success') {
                    if (par.alb_imgs.data[1]) {
                        var album_img = site_url + 'ProfilebuilderComponent/resize.php?src=' + site_url + par.alb_imgs.data[1] + '&h=148&mxw=168&mxh=148';

                        Avatar = "<div style='width: 168px; height: 148px; border: 5px solid #A0CF6D;position:relative;' ><img src='" + album_img + "' style='position:absolute;margin:auto;top:0;bottom:0;left:0;right:0;'></div>";
                        $('#Avatar').html(Avatar);
                    }
                }

                $('#p1_FirstName').html(par.p1_FirstName);
                $('#p2_FirstName').html(par.p2_FirstName);

                if (par.p1_faith != '') {
                    $('#p1_faith').html(par.p1_faith);
                } else {
                    $('#p1_faith').html('Not Specified');
                }

                if (par.p2_FirstName != null) {
                    if (par.p2_faith != '') {
                        $('#p2_faith').html(par.p2_faith);
                    } else {
                        $('#p2_faith').html('Not Specified');
                    }
                }

                if (par.p1_Ethnicity == '' || par.p1_Ethnicity == null) {
                    $('#p1_Ethnicity').html('Not Specified');
                } else {

                    $('#p1_Ethnicity').html(par.p1_Ethnicity);
                }

                if (par.p2_FirstName != null) {
                    if (par.p2_Ethnicity != null) {
                        $('#p2_Ethnicity').html(par.p2_Ethnicity);
                    } else {
                        $('#p2_Ethnicity').html('Not Specified');
                    }
                }

                if (par.p1_Education == null || par.p1_Education == '') {
                    $('#p1_Education').html('Not Specified');

                } else {
                    $('#p1_Education').html(par.p1_Education);
                }


                if (par.p2_FirstName != null) {
                    if (par.p2_Education != null) {
                        $('#p2_Education').html(par.p2_Education);
                    } else {
                        $('#p2_Education').html('Not Specified');
                    }
                }


                if (par.p1_DateOfBirth != null) {
                    $('#p1_DateOfBirth').html(par.p1_DateOfBirth + ' Years Old');
                } else {
                    $('#p1_DateOfBirth').html('Not Specified');
                }

                if (par.p2_FirstName != null) {
                    if (par.p2_DateOfBirth != null) {
                        $('#p2_DateOfBirth').html(par.p2_DateOfBirth + ' Years Old');
                    } else {
                        $('#p2_DateOfBirth').html('Not Specified');
                    }
                }

                if (par.p1_Sex != '') {
                    $('#p1_Sex').html(par.p1_Sex.toUpperCase());
                } else {
                    $('#p1_Sex').html('Not Specified');
                }

                if (par.p2_FirstName != null) {
                    if (par.p2_Sex != '') {
                        $('#p2_Sex').html(par.p2_Sex.toUpperCase());
                    } else {
                        $('#p2_Sex').html('Not Specified');
                    }
                }
                if (par.p1_RelationshipStatus) {
                    $('#p1_RelationshipStatus').html(par.p1_RelationshipStatus);
                } else {
                    $('#p1_RelationshipStatus').html('Not Specified');
                }

                if (par.p1_State) {
                    $('#p1_State').html(par.p1_State);
                } else {
                    $('#p1_State').html('Not Specified');
                }

                if (par.p1_waiting) {
                    $('#p1_waiting').html(par.p1_waiting);
                } else {
                    $('#p1_waiting').html('Not Specified');
                }

                if (par.FamilyStructure) {
                    $('#FamilyStructure').html(par.FamilyStructure);
                } else {
                    $('#FamilyStructure').html('Not Specified');
                }

                $('#Email').html(par.agency_email);
                if (par.portal_link) {
                    $('#portal_link').html(par.portal_link);
                } else {
                    $('#portal_link').html('Not Specified');
                }




                if (par.ChildAge) {
                    $('#ChildAge').html(par.ChildAge.replace(/,/g, ", "));
                } else {
                    $('#ChildAge').html('Not Specified');
                }

                if (par.ChildGender) {
                    $('#ChildGender').html(par.ChildGender.replace(/,/g, ", "));
                } else {
                    $('#ChildGender').html('Not Specified');
                }

                if (par.ChildEthnicity) {
                    $('#ChildEthnicity').html(par.ChildEthnicity.replace(/,/g, ", "));
                } else {
                    $('#ChildEthnicity').html('Not Specified');
                }

                if (par.Adoptiontype) {
                    $('#AdoptionType').html(par.Adoptiontype.replace(/,/g, ", "));
                } else {
                    $('#AdoptionType').html('Not Specified');
                }
                if (par.agency_name) {
                    $('#AdoptioAgency').html(par.agency_name);
                } else {
                    $('#AdoptioAgency').html('Not Specified');
                }




                /**/
                var alb_imgs1_src = '';
                var alb_imgs1 = '';
                var bottom_image = '<div style="background-color:#A0CF6D; overflow:hidden; padding:10px 20px;">';
                if (par.alb_imgs.status == 'success') {
                    if (par.alb_imgs.data[2]) {
                        alb_imgs1_src = site_url + 'ProfilebuilderComponent/resize.php?src=' + site_url + par.alb_imgs.data[2] + '&h=156&mxw=176&mxh=156';

                        bottom_image = bottom_image + '<div style="float:left; width:176px; height:156px;position:relative;padding:0px 10px 0px 0px;border:1px solid #5b862c;"><img src="' + alb_imgs1_src + '" style="position:absolute;margin:auto;top:0;bottom:0;left:0;right:0;"></div>';
                    }

                    if (par.alb_imgs.data[3]) {
                        alb_imgs1_src = site_url + 'ProfilebuilderComponent/resize.php?src=' + site_url + par.alb_imgs.data[3] + '&h=156&mxw=176&mxh=156';

                        bottom_image = bottom_image + '<div style="float:left; width:176px; height:156px;position:relative;padding:0px 10px 0px 0px;border:1px solid #5b862c;"><img src="' + alb_imgs1_src + '" style="position:absolute;margin:auto;top:0;bottom:0;left:0;right:0;"></div>';
                    }

                    if (par.alb_imgs.data[4]) {
                        alb_imgs1_src = site_url + 'ProfilebuilderComponent/resize.php?src=' + site_url + par.alb_imgs.data[4] + '&h=156&mxw=176&mxh=156';

                        bottom_image = bottom_image + '<div style="float:left; width:176px; height:156px;position:relative;border:1px solid #5b862c;"><img src="' + alb_imgs1_src + '" style="position:absolute;margin:auto;top:0;bottom:0;left:0;right:0;"></div>';
                    }
                }
                bottom_image = bottom_image + '</div>';

                $('#bottom_div').html(bottom_image);

                /**/

                if (par.show_contact == 1) {
                    if (par.phonenumber != 0) {
                        $('.p_contact').html(par.phonenumber);
                    } else {
                        $('.contact_div').hide();
                    }
                } else {
                    if (par.agency_contact != 0) {
                        $('.p_contact').html(par.agency_contact);
                    } else {
                        $('.contact_div').hide();
                    }
                }




            }
        }
    });
});