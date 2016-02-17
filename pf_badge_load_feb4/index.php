<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
$templateID=$_GET['tid'];
$gsiteName='https://www.parentfinder.com/';
?>
<title>Template Engine For Badge <?php echo $templateID; ?></title>
<link rel="stylesheet" href="<?php echo $gsiteName; ?>pf_badge_load/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $gsiteName; ?>pf_badge_load/templates/Template_<?php echo $templateID; ?>/css/parentfinder.css" type="text/css" />

<script type="text/javascript"  src="<?php echo $gsiteName; ?>pf_badge_load/js/bootstrap.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script src="<?php echo $gsiteName; ?>pf_badge_load/js/pgwslideshow.js"></script>
<link rel="stylesheet" href="<?php echo $gsiteName; ?>pf_badge_load/css/pgwslideshow.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $gsiteName; ?>pf_badge_load/css/flexslider.css">
<script type="text/javascript" src="<?php echo $gsiteName; ?>pf_badge_load/js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?php echo $gsiteName; ?>/pf_badge_load/jwplayer/jwplayer.js"></script>



<script type="text/javascript">
var jqueryNoConflict = jQuery;

   jqueryNoConflict(document).ready(function(){

  // jqueryNoConflict('head').append( jqueryNoConflict('<link rel="stylesheet" type="text/css" />').attr('href', '<?php echo $gsiteName; ?>pf_badge_load/templates/Template_<?php echo $templateID; ?>/css/parentfinder.css') );
   //jqueryNoConflict('head').append( jqueryNoConflict('<link rel="stylesheet" type="text/css" />').attr('href', '<?php echo $gsiteName; ?>pf_badge_load/css/bootstrap.css') );

   });


//jqueryNoConflict.getScript( "http://www.parentfinder.com/pf_badge_load/js/handlebars.js");

//==============more link function===============//
  jqueryNoConflict(document).on('click','span[data-toggle=modal]',function () {
    var content=jqueryNoConflict(this).attr('data');
    jqueryNoConflict('.pf_popupcl01').html(content);
  });
//==============more link function===============//




//==============scroll loading function===============//
   var offset = 3;
   var end = 3;
   jqueryNoConflict('#data-details').bind('scroll',function() {
          if(jqueryNoConflict('#sskey').attr('cc')==1){
            offset=3;
          }
          if(jqueryNoConflict(this).scrollTop() + jqueryNoConflict(this).innerHeight() >= this.scrollHeight) {
            var val=offset;
          if(val==3){
            var st=3;
          }else{
            var st=val;
          }
          jqueryNoConflict('#pf_loading').show();
          jqueryNoConflict('#sskey').attr('cc',0);
          retriveDataScroll("Template_<?php echo $templateID; ?>/Home.handlebars","<?php echo $_GET['uid'];?>",st,end);
             offset +=3;
          }
   });
//==============scroll loading function===============//




//==============topbar search function===============//
jqueryNoConflict(document).on('click','.pf_content_menu ul li',function(){
   var uid=jqueryNoConflict('#data-container1').attr('uid');
   var tid=jqueryNoConflict('#data-container1').attr('tid');
   var searchKey=this.id;
   var searchType=this.type;
   jqueryNoConflict('#sskey').attr({'sk':searchKey,'st':searchType,'cc':1});
   jqueryNoConflict('#sskey').val(searchKey+'::'+searchType);
   searchData("Template_<?php echo $templateID; ?>/Home.handlebars",uid,0,3,searchKey,searchType,'');
});

jqueryNoConflict(document).on('click','.pf_view_cl027',function(){
   var uid=jqueryNoConflict('#data-container1').attr('uid');
   var searchName=jqueryNoConflict('#searchInput').val();
   searchData("Template_<?php echo $templateID; ?>/Home.handlebars",uid,0,3,'','',searchName);
});

jqueryNoConflict(document).on('click','#pf_kids_resmenu a',function(){
   jqueryNoConflict('.pf_filter_cl03').parent().removeClass('pf_filter_cl05');
   var uid=jqueryNoConflict('#data-container1').attr('uid');
   var tid=jqueryNoConflict('#data-container1').attr('tid');
   var searchKey=this.id;
   var searchType=this.type;
   jqueryNoConflict('#sskey').attr({'sk':searchKey,'st':searchType,'cc':1});
   jqueryNoConflict('#sskey').val(searchKey+'::'+searchType);
   searchData("Template_<?php echo $templateID; ?>/Home.handlebars",uid,0,3,searchKey,searchType,'');
});

jqueryNoConflict(document).on('click','#pf_region_resmenu a',function(){
   jqueryNoConflict('.pf_filter_cl03').parent().removeClass('pf_filter_cl05');
   var uid=jqueryNoConflict('#data-container1').attr('uid');
   var tid=jqueryNoConflict('#data-container1').attr('tid');
   var searchKey=this.id;
   var searchType=this.type;
   jqueryNoConflict('#sskey').attr({'sk':searchKey,'st':searchType,'cc':1});
   jqueryNoConflict('#sskey').val(searchKey+'::'+searchType);
   searchData("Template_<?php echo $templateID; ?>/Home.handlebars",uid,0,3,searchKey,searchType,'');
});
jqueryNoConflict(document).on('click',".pf_more_cl011",function(){
  if (jqueryNoConflict(this).parent().parent().is('.pf_more_panel_open')) {
  jqueryNoConflict(this).parent().parent().removeClass("pf_more_panel_open");
  } else {
  jqueryNoConflict(this).parent().parent().addClass("pf_more_panel_open");
  }
});

jqueryNoConflict(document).on('click',".pf_more_cl014",function(){
  if (jqueryNoConflict(this).parent().hasClass('pf_more_cl023')) {
  jqueryNoConflict(this).parent().removeClass("pf_more_cl023");
  } else {
  jqueryNoConflict(this).parent().addClass("pf_more_cl023");
  }
  });

//==============topbar search function===============//




//=================refresh page======================//
jqueryNoConflict(document).on('click','#pf_reload',function(){
jqueryNoConflict(".pf_view_cl01").empty();
retriveData("Template_<?php echo $templateID; ?>/Home.handlebars","<?php echo $_GET['uid'];?>",0,3);
});
//=================refresh page======================//




//==============back button click function===============//
jqueryNoConflict(document).on('click','.backb',function(){
  if(this.id=='1'){
   // alert(this.id);
    jqueryNoConflict('#pf_search').hide(); 
    jqueryNoConflict('#data-detailsAbout').show();
    jqueryNoConflict('#data-detailsChild').hide(); 
  }else{
    jqueryNoConflict('#pf_search').show(); 
    jqueryNoConflict('#data-detailsAbout').hide();
    jqueryNoConflict('#data-details').show(); 
  }

});
//==============back button click function===============//





//==============footer click function===============//
jqueryNoConflict(document).on('click','.footer',function(){
   console.log('Event Fired');
   alert( 'Footer Element Clicked!');
});
//==============footer click function===============//





//==============like button click function===============//
jqueryNoConflict(document).on('click','.pf_more_cl012',function(){
var profileId=this.id;
//doLike(profileId);
});
jqueryNoConflict(document).on('click','.pf_view_cl04 img',function(){
var profileId=this.id;
//doLike(profileId);
});
function doLike(id){
       var profileId=id;
        jqueryNoConflict.ajax({
        url: "<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page=PreLike",
        type: "POST",
        cache: false,
        data: {Profileid: profileId},
       // datatype: "json",
        success: function(data) {
        var n = data;
            if (n.status == "success") {
                  if (n.Profiles_value.rows != 0 && n.Profiles_value.profile_typoe == 4) {
                            jqueryNoConflict.ajax({
                            url: siteurl + "<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page=userLike",
                            type: "POST",
                            cache: false,
                            data: {userid: profileId, Birthmotherid: "obj.profile_id"},
                            success: function(dataLike) {
                              var n = dataLike;
                              if (n.status == "success") {
                                jqueryNoConflict('#likeAdded .data').html('Added to "Families I Like" list');
                                jqueryNoConflict('.likeClick').trigger('click');
                              }
                              else {
                                jqueryNoConflict('#likeAdded .data').html(n.response);
                                jqueryNoConflict('.likeClick').trigger('click');
                              }
                            }
                            });
                              } else if (n.Profiles_value.rows != 0 && n.Profiles_value.profile_typoe != 4) {
                                jqueryNoConflict('#likeAdded .data').html("Birth mothers can only like this page");
                                jqueryNoConflict('.likeClick').trigger('click');
                              } else {
                              //alert('open page');
                              jqueryNoConflict('.likeDialog')[0].click();
                              //$('.likeDialog').trigger('click');
                              }
            }
        }
        });
}



jqueryNoConflict(document).on('click','.createAcc',function(){
    jqueryNoConflict('#likeLogin .closebtn').trigger('click');
    jqueryNoConflict(jqueryNoConflict('.topMenuJoinBlock')[0]).trigger('click');
  });
jqueryNoConflict(document).on('click','.loginAcc',function(){
    jqueryNoConflict('#likeLogin .closebtn').trigger('click');
    jqueryNoConflict(jqueryNoConflict('.topMenuJoinBlock')[1]).trigger('click');
  });
jqueryNoConflict(document).on('click','.NoAcc',function(){
    jqueryNoConflict('#likeLogin .closebtn').trigger('click');
  });

//==============like button click function===============//




//============== page load function===============// 
jqueryNoConflict(document).on('click','.pf_view_cl08',function(){
pageData("About","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_view_cl09',function(){
pageData("OurHome","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_view_cl011',function(){
pageData("Contact","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_view_cl010',function(){
pageData("Letter","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_view_cl012',function(){
pageData("Video","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_more_cl07',function(){
pageData("Letter","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_more_cl06',function(){
pageData("OurHome","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_more_cl06home',function(){
pageData("OurHome","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_more_cl08',function(){
pageData("Contact","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_more_videos',function(){
//pageDataChild("MoreVideos","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_more_fotos',function(){
//pageDataChild("MorePhotos","Template_<?php echo $templateID; ?>",this.id);
});

jqueryNoConflict(document).on('click','.pf_view_cl03 img',function(){
pageData("About","Template_<?php echo $templateID; ?>",this.id);
});

////============== page load function===============//


//=================pdf download function==============//
/*jqueryNoConflict(document).on('click','.pf_view_cl013',function(){
var url=this.id;
if(url=='javascript:void(0)'){
  jqueryNoConflict('#likeAdded .data').html('<h4>No printable book available</h4>');
  jqueryNoConflict('#likeAdded .okhome').html('OK');
  jqueryNoConflict('.likeClick').trigger('click'); 
}else{
   window.open(url,'_blank');
}
});*/

/*jqueryNoConflict(document).on('click','.pf_more_cl09',function(){
var url=this.id;
if(url=='javascript:void(0)'){
  jqueryNoConflict('#likeAdded1 .data').html('<h4>No printed profile available</h4>');
  jqueryNoConflict('.likeClick1').trigger('click'); 
}else{
   window.open(url,'_blank');
}
});*/
 jqueryNoConflict(document).on('click','#pdf_multi',function(){
                jqueryNoConflict('.printClick').show();
                jqueryNoConflict('.print_more').toggle();


});
 jqueryNoConflict(document).on('click','.printClick',function(){
            jqueryNoConflict('.print_more').toggle();
            jqueryNoConflict('.printClick').hide();
        });
 jqueryNoConflict(document).on('click','#pdf_multi_home',function(){
                jqueryNoConflict('.print_more_home').hide();
                jqueryNoConflict(this).parent().parent().find('.print_more_home').toggle();
                
               


            });
 jqueryNoConflict(document).on('click','body',function(event){

            if(jqueryNoConflict('.print_more_home:visible').length > 0 && event.target.id != "pdf_multi_home"){              
              jqueryNoConflict('.print_more_home').hide();
            }
            
            //jqueryNoConflict('.printClick').hide();
        });
//ebook download //
jqueryNoConflict(document).on('click','.pf_more_cl05',function(){
var url=this.id;
if(url==''|| url=='undefined'){
  jqueryNoConflict('#likeAdded1 .data').html('<h4>No printed profile available</h4>');
  jqueryNoConflict('.likeClick1').trigger('click'); 
}else{
 window.open(url,'_blank'); 
}

});

//=================pdf download function==============//

//==============document ready function===============//
jqueryNoConflict(document).ready(function(){

    jqueryNoConflict(document).on('click','#Contactsubmit',function(){
    validateForm();

                    function validateForm(){
                        var valid = 1;
                        var nameReg = /^[A-Za-z]+$/;
                        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

                        var name = jqueryNoConflict('input[name=name]').val();
                        var email = jqueryNoConflict('input[name=email]').val();
                        var subject = jqueryNoConflict('input[name=subject]').val();
                        var message = jqueryNoConflict('#messagecontact').val();
                        var inputVal = new Array(name, email, subject, message);

                            if(inputVal[0] == ""){
                               jqueryNoConflict('input[name=name]').attr('style', 'border-color: red !important');
                              //jqueryNoConflict('input[name=name]').css('border-color','red');
                                valid = 0;
                            } 
                            else{
                                jqueryNoConflict('input[name=name]').css('border-color','');
                            }

                            if(inputVal[1] == ""){
                                jqueryNoConflict('input[name=email]').attr('style', 'border-color: red !important');
                                valid = 0;
                            } 
                            else if(!emailReg.test(email)){
                                jqueryNoConflict('input[name=email]').attr('style', 'border-color: red !important');
                                valid = 0;
                            }else{
                                jqueryNoConflict('input[name=email]').css('border-color','');
                            }

                            if(inputVal[2] == ""){
                                jqueryNoConflict('input[name=subject]').attr('style', 'border-color: red !important');
                                valid = 0;
                            } 
                            else{
                                jqueryNoConflict('input[name=subject]').css('border-color','');
                            }

                            if(inputVal[3] == ""){
                                jqueryNoConflict('#messagecontact').attr('style', 'border-color: red !important');
                                valid = 0;
                            }
                            else{
                                jqueryNoConflict('#messagecontact').css('border-color','');
                            }  
                              if (!valid)
                              return false;
                              else formSubmit();     
                    }   

                     
                      function formSubmit(){
                      jqueryNoConflict('#pf_loading').show();
                      var formData = {
                          'id'                : jqueryNoConflict('input[name=check]').val(),
                          'name'              : jqueryNoConflict('input[name=name]').val(),
                          'email'             : jqueryNoConflict('input[name=email]').val(),
                          'subject'           : jqueryNoConflict('input[name=subject]').val(),
                          'body'              : jqueryNoConflict('#messagecontact').val()
                      };

                      jqueryNoConflict.ajax({
                          type        : 'POST', 
                          url         : '<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page=ContactSubmit',
                          data        : formData, 
                          dataType    : 'json', 
                          encode      : true
                      }).done(function(data) {
                           if(data.message=='1'){
                            jqueryNoConflict('#ajaxMessage').html('YOUR MESSAGE HAS BEEN SENT');
                            jqueryNoConflict('#contact-form')[0].reset();
                            jqueryNoConflict('#pf_loading').hide();
                           }else{
                             jqueryNoConflict('#ajaxMessage').html('<br />MAIL SENDING FAILED!<br />');
                           }
                          });
                      event.preventDefault();
                      }
    });




    jqueryNoConflict('.pf_filter_cl03').click(function(){
    if(jqueryNoConflict(this).parent().is('.pf_filter_cl05')){
      jqueryNoConflict(this).parent().removeClass('pf_filter_cl05');
    }else{
      jqueryNoConflict(this).parent().addClass('pf_filter_cl05');
    }
    });
//intialise loading and first page
   // jqueryNoConflict('#loading').show();
    retriveData("Template_<?php echo $templateID; ?>/Home.handlebars","<?php echo $_GET['uid'];?>",0,3);
});
//==============document ready function===============//


function pageData(page,templateid,dataid) {
      jqueryNoConflict('#pf_search').hide(); 
      jqueryNoConflict('#data-detailsAbout').empty();
      jqueryNoConflict('#pf_loading').show();
        jqueryNoConflict.getJSON('<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page='+page+'&uid='+dataid+'&start=0&end=1', function(data) { 
        
          handlebarsDebugHelper();
          jqueryNoConflict('#data-details').hide();
          jqueryNoConflict('#data-detailsAbout').show();

          //getTemplateAjax('templates/'+templateid+'/'+page+'.handlebars', function(template) {
          getTemplateAjax('<?php echo $gsiteName; ?>pf_badge_load/templates/'+templateid+'/'+page+'.handlebars', function(template) {
          console.log(template);
            jqueryNoConflict('#data-detailsAbout').html(template(data));
            jqueryNoConflict('#pf_loading').hide();
          });

      });
};


function pageDataChild(page,templateid,dataid) {
      jqueryNoConflict('#pf_search').hide(); 
      //jqueryNoConflict('#data-detailsAbout').empty();
      jqueryNoConflict('#data-detailsChild').empty();
      jqueryNoConflict('#pf_loading').show();
        jqueryNoConflict.getJSON('<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page='+page+'&uid='+dataid+'&start=0&end=1', function(data) { 
        
          handlebarsDebugHelper();
          jqueryNoConflict('#data-details').hide();
          jqueryNoConflict('#data-detailsAbout').hide();
          jqueryNoConflict('#data-detailsChild').show();

          //getTemplateAjax('templates/'+templateid+'/'+page+'.handlebars', function(template) {
          getTemplateAjax('<?php echo $gsiteName; ?>pf_badge_load/templates/'+templateid+'/'+page+'.handlebars', function(template) {
          console.log(template);
            //jqueryNoConflict('#data-detailsAbout').html(template(data));
            jqueryNoConflict('#data-detailsChild').html(template(data));
            jqueryNoConflict('#pf_loading').hide();
          });

      });
};


// grab data

function retriveDataScroll(id,uid,s,e) {

var sk=jqueryNoConflict('#sskey').attr('sk');
var st=jqueryNoConflict('#sskey').attr('st');
var seedsc=jqueryNoConflict('#sskey').attr('seed');
var sn=jqueryNoConflict('#searchInput').val();

 if(sk || st || sn) {
 var url='<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page=index&sstat=scroll&uid='+uid+'&start='+s+'&end='+e+'&sortvalue='+sk+'&type='+st+'&name='+sn+'&seed='+seedsc;
 }else{
 var url='<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page=index&sstat=scroll&uid='+uid+'&start='+s+'&end='+e+'&type=Sortby&sortvalue=random&seed='+seedsc;
 }

        jqueryNoConflict.getJSON(url, function(data) {    
            console.log(data.objects);
            if(data.objects=='nodata'){
            jqueryNoConflict('#pf_loading').hide(); 
            }else{
            renderDataVisualsTemplate1(data,id);  
            }         
        });
};

function retriveData(id,uid,s,e) {
        var seed = Math.floor(Math.random() * 9000000000 + 1000000000);
        jqueryNoConflict('#sskey').attr('seed',seed);
        jqueryNoConflict.getJSON('<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page=index&sortvalue=random&type=Sortby&uid='+uid+'&start='+s+'&end='+e+'&seed='+seed, function(data) { 
            renderDataVisualsTemplate(data,id);      
        });
};

function searchData(id,uid,s,e,searchKey,searchType,searchName) {
        jqueryNoConflict('#pf_loading').show();
        if(searchKey){
          jqueryNoConflict(".pf_view_cl01").empty();        
        }
        jqueryNoConflict.getJSON('<?php echo $gsiteName; ?>pf_badge_load/json-data.php?page=index&uid='+uid+'&start='+s+'&end='+e+'&sortvalue='+searchKey+'&type='+searchType+'&name='+searchName, function(data) { 
            renderDataVisualsTemplate(data,id);      
        });
};

function renderDataVisualsTemplate1(data,id){
    handlebarsDebugHelper();
    //renderHandlebarsTemplate1('templates/'+id, '#data-details', data);
     renderHandlebarsTemplate1('<?php echo $gsiteName; ?>/pf_badge_load/templates/'+id, '.pf_view_cl01', data);
};

function renderHandlebarsTemplate1(withTemplate,inElement,withData){
    getTemplateAjax(withTemplate, function(template) {
        console.log('2');
        jqueryNoConflict(inElement).append(template(withData));
    })
    jqueryNoConflict('#pf_loading').hide();
};

// render compiled handlebars template
function renderDataVisualsTemplate(data,id){
    handlebarsDebugHelper();
   // jqueryNoConflict("#data-details").empty();
   // jqueryNoConflict('#data-details').css( "overflow", "hidden" );
   // renderHandlebarsTemplate('templates/'+id, '#data-details', data);

    if(id=='Template_<?php echo $templateID; ?>/About.handlebars'){
    jqueryNoConflict('#data-details').hide();
    jqueryNoConflict('#data-detailsAbout').show();
    //renderHandlebarsTemplate('templates/'+id, '#data-detailsAbout', data);
    renderHandlebarsTemplate('<?php echo $gsiteName; ?>pf_badge_load/templates/'+id, '#data-detailsAbout', data);
    }else{
      jqueryNoConflict('#data-details').show();
      jqueryNoConflict('#data-detailsAbout').hide();
      //renderHandlebarsTemplate('templates/'+id, '#data-details', data); 
      renderHandlebarsTemplate('<?php echo $gsiteName; ?>pf_badge_load/templates/'+id, '.pf_view_cl01', data); 
    }

};

// render handlebars templates via ajax
function getTemplateAjax(path, callback) {
    var source, template;
    jqueryNoConflict.ajax({
        url: path,
        //dataType:'html',
        cache: false,
        success: function (data) {
            source = data;
            template = Handlebars.compile(source);
            if (callback) callback(template);
        }
    });
};

// function to compile handlebars template
function renderHandlebarsTemplate(withTemplate,inElement,withData){
    getTemplateAjax(withTemplate, function(template) {
        jqueryNoConflict(inElement).html(template(withData));
    })
    jqueryNoConflict('#pf_loading').hide();
};

// add handlebars debugger
function handlebarsDebugHelper(){
    Handlebars.registerHelper("debug", function(optionalValue) {
        console.log("Current Context");
        console.log("===============");
        console.log(this);
    });
};
</script>



       <?php include('header.php');?>
        <div id="data-container1" tid="<?php echo $templateID; ?>" uid="<?php echo $_REQUEST['uid'];?>">
        <div id="pf_loading" class="pf_loading" style="display:none">
         <img src="<?php echo $gsiteName; ?>/pf_badge_load/templates/Template_<?php echo $templateID; ?>/images/loader.gif" title="Loading.." /> 
        </div>
        <div id="data-details" class="pf_data-details">
                    <div class="pf_modal pf_popup" id="more"><!-- tooltip dialog responsive-->
                      <div class="pf_modal-dialog">
                          <div class="pf_modal-content">
                            <div class="pf_modal-body">
                              <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                                 <div class="pf_popupcl01">
                                 
                               </div>
                            </div>
                          </div>
                        </div>
                    </div>

    <div class="likeClick" data-toggle="modal" data-target="#likeAdded"></div>
    <div class="pf_modal pf_popup" id="likeAdded"><!-- tooltip dialog responsive-->
        <div class="pf_modal-dialog">
            <div class="pf_modal-content">
                <div class="pf_modal-body">
                    <div class="closebtn" data-dismiss="modal"><!-- close button--></div>
                    <div class="pf_popupcl01">
                        <div class="data ok"></div>
                        <div class="likeButtons">
                            <div class="likepop pf_popup_login_cl08 okhome" data-dismiss="modal"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <div class='pf_view_cl01'></div> </div>
        <div id="data-detailsAbout" class="pf_data-detailsAbout"> </div>
        <div id="data-detailsChild" class="pf_data-detailsChild"> </div>
       </div>
       <?php include('footer.php');?>
       

