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
      
        var script_tag_main = document.createElement('script');
        script_tag_main.setAttribute("type","text/javascript");
        script_tag_main.setAttribute("src",base_url+ "utility.js");
    
    
      (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
      (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag_main);


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
            loadMenu();
            loadFamilies(null);         
    }

})(); // We call our anonymous function immediately


