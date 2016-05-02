
 /*
    * Family listing call from API
    */
    function loadFamilies(type){
      $('.lisitngBLocks').html("");
      var url;
      if(type==null){
        url=api_url+"profiles";
      }else{
        var res=type.split("_");
        if(res.length>0){
          var filter_type=res[0];
          var filter_value=res[1];
          url=api_url+"profiles/"+filter_type+"/"+filter_value;
        }
      }
      var i;
      $.ajax({               
                url: url,
                dataType: "jsonp",
                async:false,
                success: function (data) {
                    if(data.status==200){
 
                      var contentDiv='';
                      var profiles=data.profiles;
                      for(i=0; i<profiles.length; i++){

                           if(profiles[i].profile.parent1){
                            var parent1=profiles[i].profile.parent1;
                            var couple_name=parent1.first_name;
                            var age=getAge(parent1.dob);
                          }
                          if(profiles[i].profile.parent2){
                            var parent2=profiles[i].profile.parent2;
                            couple_name+=parent2.first_name;    
                            age+='/'+ getAge(parent2.dob);               
                          }
                          age=checkValue(age);
                          var contact=profiles[i].profile.contactDetails;
                          var faith=checkValue(parent1.faith);
                          var waiting=checkValue(parent1.waiting);
                          var avatar=checkPhoto(parent1.avatar);
                          username=parent1.username;
                         
                       // contentDiv += '<div>'+ profiles[i].parent1.first_name +'</div>';
                        contentDiv+='<div class="item"> <div class="itemBlock">';
                        contentDiv+='<div class="figure">';
                        contentDiv+='<img src="'+avatar+'">'
                               +'<div class="details">'
                                   +' <div class="familyName">'
                                      +'<p><b>AGE:</b> '+age+'</p>'
                                        +'<p><b>WAITING:</b> '+waiting+'</p>'
                                        +'<p><b>FAITH:</b> '+faith+'</p>'
                                       +' <a class="rotate"></a>'
                                        +'<a class="fav"></a>'
                                    +'</div>'
                                    +'<div class="link">'
                                        +'<a href="about.html" class="about">More About Me</a>'
                                        +'<a target="_blank"  class="pics" onclick=getChapters("'+username+'")>Our Pictures</a>'
                                       +' <a href="videos.html" class="videos">Our Videos</a>'
                                        +'<a class="profile">Our Profile</a>'
                                    +'</div> '                              
                                +' </div>';
                        contentDiv+='</div>';

                        contentDiv+='<div class="familyDetail clearfix">'
                                +'<div class="name">'
                                    +'<a href="#">'+couple_name+'</a>'
                                    +'<span class="place">'+contact.country+'</span>'
                                +'</div>'
                                +'<a href="contact.html" class="contactFamily">contact</a>'
                              +'</div>';

                        contentDiv+='</div></div>';
                      
                        }            
                        $('.lisitngBLocks').append(contentDiv);
                        mainIsotope();

                    }else{
                      $('.lisitngBLocks').html("No Families");
                    }
                    

                }

            });
    }

  /* Age calculation 
      *    @param  Request dateString
      *    @return number age

    */
    function getAge(dateString) {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }
    /* Null Check 
      *    @param  string value
      *    @return string value
    */
    function checkValue(value){
      if(value!==null){
            value=value;
      }else{
        value='Not Specified';
      }
      return value;
    }
    function checkPhoto(value){
      if(value!=0){
        value='https://www.parentfinder.com/modules/boonex/avatar/data/favourite/'+value+'.jpg';
      }else{
        value='https://www.parentfinder.com/templates/tmpl_par/images/NO-PHOTOS_icon.png';
      }
      return value;
    }
     function loadMenu(){
        getKidsinFamilyList();
       // getLikeProfilesList();
        getReligionList();
        getRegionList();
        getCountryStateList();
    }


   
    function getRegionList(){
      var i;
      $.ajax({
        url:api_url+"region",
        dataType:"jsonp",
        async:false,
        success:function(data){
          var regionMenu;
          if(data.status==200){
             var region=data.regionDetails;
             regionMenu='<ul class="dropDown" id="regionList">';
             for(i=0; i<region.length; i++){
                regionMenu+='<li id="'+region[i].Region+'"><a href="#">'+region[i].Region+'</a> </li>';               
              }
              regionMenu+='</ul>';
              $('#region').append(regionMenu);
         }
        }
      });
    }
  
    function getReligionList(){
      var i;
      $.ajax({
        url:api_url+"religion",
        dataType:"jsonp",
        async:false,
        success:function(data){
          var religionMenu;
          if(data.status==200){
             var religion=data.religionDetails;
             religionMenu='<ul class="dropDown" id="religionList">';
             for(i=0; i<religion.length; i++){
                religionMenu+='<li id="'+religion[i].Religion+'"><a href="#">'+religion[i].Religion+'</a> </li>';               
              }             
              religionMenu+='</ul>';
              $('#religion').append(religionMenu);
         }else{

         }
        }
      });
    }
    function getLikeProfilesList(){

    }
    function getKidsinFamilyList(){
      var i;
       $.ajax({
        url:api_url+"kids",
        dataType:"jsonp",
        async:false,
        success:function(data){
          var kidsMenu='';
          if(data.status==200){
            var kids=data.KidsDetails;
            kidsMenu='<ul class="dropDown">';
             for(i=0; i<kids.length; i++){
                kidsMenu+='<li id="'+kids[i].kids_id+'"><a href="#">'+kids[i].description+'</a> </li>';               
              } 
            kidsMenu+='</ul>';
           $('#kids').append(kidsMenu);
          }
        }

      });
    }
    
    function getCountryStateList(){
      var i;
      var j;
      var countryMenu;
      $.ajax({
        url:api_url+"country",
        dataType:"jsonp",
        async:false,
        success:function(data){
          
          if(data.status==200){
            var country=data.countryDetails;
            countryMenu='<ul class="dropDown">';
            for(i=0; i<5; i++){
                countryMenu+='<li id="'+country[i].country_id+'"><a href="#">'+country[i].country+'</a>';

                $.ajax({
                  url:api_url+"state/"+country[i].country_id,
                  dataType:"jsonp", 
                  async:false,
                  success:function(data){
                    if(data.status==200){
                      var state=data.stateDetails;
                      countryMenu+='<dl class="dropDownSub">';
                      for(j=0;j<state.length;j++){
                        countryMenu+='<dt id="'+state[j].state_id+'"><a href="#">'+state[j].State+'</a></dt>';
                      }
                      countryMenu+='</dl>';
                      
                    }
                  }

                }); 
                countryMenu+='</li>'; 
            } 
            countryMenu+='</ul>';        
            $('#country').append(countryMenu);

          }
        }

      });
    }

function getChapters(name){
  username=name;
  $(".family_Widget_2016").load(base_url+"chapters.html");       
}
function mainIsotope(){
   $(".itemBlock .figure > img").click(function () {
            $(this).parent().parent(".itemBlock").addClass("active");
        });
        $(".familyName .rotate").click(function () {
            $(this).parent().parent().parent().parent(".itemBlock").removeClass("active");
        });


          var $container = $('.grid'),
        colWidth = function () {
            var w = $container.width(),
                columnNum = 1;
            //columnWidth = 0;
            if (w > 1200) {
                columnNum = 3;
            } else if (w > 900) {
                columnNum = 3;
            } else if (w > 600) {
                columnNum = 2;
            } else if (w > 300) {
                columnNum = 1;
            }
            columnWidth = Math.floor(w / columnNum);
            $container.find('.item').each(function () {
                var $item = $(this),
                    multiplier_w = $item.attr('class').match(/item-w(\d)/),
                    width = multiplier_w ? columnWidth * multiplier_w[1] - 4 : columnWidth - 4
                $item.css({
                    width: width
                });
            });
            return columnWidth;
        },
        isotope = function () {
            $container.isotope({
                resizable: false,
                itemSelector: '.item',
                masonry: {
                    columnWidth: colWidth(),
                    gutterWidth: 0
                }
            });
        };
        isotope();
        $(window).smartresize(isotope);
}
function letterIsotope()
    {
      var $container = $('.grid'),
        colWidth = function () {
          var w = $container.width(), 
            columnNum = 1,
            columnWidth = 0;
          if (w > 1200) {
            columnNum  = 2;
          } else if (w > 900) {
            columnNum  = 2;
          } else if (w > 600) {
            columnNum  = 1;
          } else if (w > 300) {
            columnNum  = 1;
          }
          columnWidth = Math.floor(w/columnNum);
          $container.find('.item').each(function() {
            var $item = $(this),
              multiplier_w = $item.attr('class').match(/item-w(\d)/),
              // multiplier_h = $item.attr('class').match(/item-h(\d)/),
              width = multiplier_w ? columnWidth*multiplier_w[1]-4 : columnWidth-4
              //height = multiplier_h ? columnWidth*multiplier_h[1]*0.5-4 : columnWidth*0.5-4;
            $item.css({
              width: width
              //height: height
            });
          });
          return columnWidth;
        },
        isotope = function () {
          $container.isotope({
            resizable: false,
            itemSelector: '.item',
            masonry: {
              columnWidth: colWidth(),
              gutterWidth: 5
            }
          });
        };
      isotope();
      $(window).smartresize(isotope);
    }