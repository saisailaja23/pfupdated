var baseurl="http://localhost/parentfinderApi/PARENTFINDER/Badge/";
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
       var script_tag1 = document.createElement('script');
        script_tag1.setAttribute("type","text/javascript");
        script_tag1.setAttribute("src",
            "http://localhost/Badge/javascripts/jquery.isotope.min.js");

   
    
    
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
            $("#content").load(baseurl+"index.html");
            loadFamilies();           
       
    }

    /*
    * Family listing call from API
    */
    function loadFamilies(){
      $.ajax({
                url: "http://localhost/parentfinderApi/PARENTFINDER/LaravelApi/profiles",
                dataType: "jsonp",
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
                          var contact=profiles[i].profile.contactDetails;
                         
                       // contentDiv += '<div>'+ profiles[i].parent1.first_name +'</div>';
                        contentDiv+='<div class="item"> <div class="itemBlock">';
                        contentDiv+='<div class="figure">';
                        contentDiv+='<img src="https://www.parentfinder.com/modules/boonex/avatar/data/favourite/1520.jpg">'
                               +'<div class="details">'
                                   +' <div class="familyName">'
                                      +'<p><b>AGE:</b> '+age+'</p>'
                                        +'<p><b>WAITING:</b> '+parent1.waiting+'</p>'
                                        +'<p><b>FAITH:</b> '+parent1.faith+'</p>'
                                       +' <a class="rotate"></a>'
                                        +'<a class="fav"></a>'
                                    +'</div>'
                                    +'<div class="link">'
                                        +'<a href="about.html" class="about">More About Me</a>'
                                        +'<a href="photos.html" class="pics">Our Pictures</a>'
                                       +' <a href="chapters.html" class="videos">Our Videos</a>'
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
})(); // We call our anonymous function immediately


