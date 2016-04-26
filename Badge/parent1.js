(function() {

// Localize jQuery variable
var jQuery;

/******** Load jQuery if not present *********/
if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.4.2') {
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type","text/javascript");
    script_tag.setAttribute("src",
        "http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js");
    if (script_tag.readyState) {
      script_tag.onreadystatechange = function () { // For old versions of IE
          if (this.readyState == 'complete' || this.readyState == 'loaded') {
              scriptLoadHandler();
          }
      };
    } else {
      script_tag.onload = scriptLoadHandler;
    }
    // Try to find the head, otherwise default to the documentElement
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
} else {
    // The jQuery version on the window is the one we want to use
    jQuery = window.jQuery;
    main();
}

/******** Called once jQuery has loaded ******/
function scriptLoadHandler() {
    // Restore $ and window.jQuery to their previous values and store the
    // new jQuery in our local jQuery variable
    jQuery = window.jQuery.noConflict(true);
    // Call our main function
    main(); 
}

/******** Our main function ********/
function main() { 
    jQuery(document).ready(function($) {    
        /******* Load CSS *******/
//        alert("df");
//        $( "#contentDiv" ).load( "http://localhost/Badge/index.html", function() {
//   alert( "Load was performed." );
// });
      
      
        $.ajax({
            url: "http://localhost/parentfinderApi/PARENTFINDER/LaravelApi/letters/dhanyas",
            dataType: "jsonp",
            success: function (data) {
                var letters=data.letters
                var contentDiv = '';
                for(i=0; i<letters.length; i++){
                    contentDiv += '<div>'+ letters[i].Title +'</div>'
                  
                }
                $('#contentDiv').append(contentDiv);

            }

        });
    });
}

})(); // We call our anonymous function immediately
