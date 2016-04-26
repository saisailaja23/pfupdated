var baseurl="http://localhost/Badge/";
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
    $(document).ready(function($) {    

            // Flip
            $(".itemBlock .figure > img").click(function () {
                $(this).parent().parent(".itemBlock").addClass("active");
            });
            $(".familyName .rotate").click(function () {
                $(this).parent().parent().parent().parent(".itemBlock").removeClass("active");
            });            
       
      
        /* Family listing page */
        $("#content").load(baseurl+"index.html");
        $.ajax({
            url: "http://localhost/parentfinderApi/PARENTFINDER/LaravelApi/letters/dhanyas",
            dataType: "jsonp",
            success: function (data) {alert(data.status);
                if(data.status==200){
                    // var profiles=data.profiles;
                    // for(i=0; i<profiles.length; i++){
                    // contentDiv += '<div>'+ profiles[i].parent1.first_name +'</div>'
                  
                    //}
                }else{

                }
                

            }

        });
          
        
    });
}

})(); // We call our anonymous function immediately
