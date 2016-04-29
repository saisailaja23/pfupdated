var base_url="http://localhost/parentfinderApi/PARENTFINDER/Badge/";
var api_url="http://localhost/parentfinderApi/PARENTFINDER/LaravelApi/";
var username;
(function() {

    // Localize jQuery variable
    var jQuery;

    /******** Load jQuery if not present *********/

        var script_tag = document.createElement('script');
        script_tag.setAttribute("type","text/javascript");
        script_tag.setAttribute("src",
            "https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js");
        if (script_tag.readyState) {
          script_tag.onreadystatechange = function () { // For old versions of IE
              if (this.readyState == 'complete' || this.readyState == 'loaded') {
                  scriptLoadHandler();
              }
          };
        } else {
          script_tag.onload = scriptLoadHandler;
        }
      
   
    
    
      (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);


      /******** Called once jQuery JQueryhas loaded ******/
      function scriptLoadHandler() {
          
          // Call our main function
          main(); 
      }
     function loadScript(url, callback) {

            var script = document.createElement("script")
            script.type = "text/javascript";

            if (script.readyState) { //IE
                script.onreadystatechange = function () {
                    if (script.readyState == "loaded" || script.readyState == "complete") {
                        script.onreadystatechange = null;
                        callback();
                    }
                };
            } else { //Others
                script.onload = function () {
                    callback();
                };
            }

            script.src = url;
            document.getElementsByTagName("head")[0].appendChild(script);
        }

    /******** Our main function ********/
    function main() { 
            /*Loading main html */
            $("#content").load(base_url+"index.html");
            loadFamilies();  
       
    }

    function loadMenu(){
        getKidsinFamilyList();
       // getLikeProfilesList();
        getReligionList();
        getRegionList();
        getCountryStateList();
    }


    /*
    * Family listing call from API
    */
    function loadFamilies(){
      var i;
      $.ajax({
                url: api_url+"profiles",
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
                                        +'<a target="_blank" href="javascript:void(0);" class="pics" onclick=getChapters("'+username+'")>Our Pictures</a>'
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

                    }else{
                      $('.lisitngBLocks').append("");
                    }
                    loadMenu();

                }

            });
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
             regionMenu='<ul class="dropDown">';
             for(i=0; i<region.length; i++){
                regionMenu+='<li id="'+region[i].RegionId+'"><a href="#">'+region[i].Region+'</a> </li>';               
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
             religionMenu='<ul class="dropDown">';
             for(i=0; i<religion.length; i++){
                religionMenu+='<li id="'+religion[i].ReligionId+'"><a href="#">'+religion[i].Religion+'</a> </li>';               
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
   

})(); // We call our anonymous function immediately


function getChapters(name){
  username=name;
  $("#content").load(base_url+"chapters.html");       
}


