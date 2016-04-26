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
        var css_link1 = $("<link>", { 
            rel: "stylesheet", 
            type: "text/css", 
            href: "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
        });
        var css_link2 = $("<link>", { 
            rel: "stylesheet", 
            type: "text/css", 
            href: "stylesheets/screen.css" 
        });
        var css_link3 = $("<link>", { 
            rel: "stylesheet", 
            type: "text/css", 
            href: "https://fonts.googleapis.com/css?family=Montserrat:400,700" 
        });
         var fav_icon = $("<link>", { 
            rel: "shortcut icon", 
            type: "image/x-icon", 
            href: "images/favicon.ico" 
        });

        fav_icon.appendTo('head');
        
        css_link1.appendTo('head'); 
        css_link2.appendTo('head');
        css_link3.appendTo('head');
        $.ajax({
            url: "http://ctpf01.parentfinder.com/samplebadge.php?callback=?",
            dataType: 'json',
            success: function (data) {
                var contentDiv = '';
                for(i=0; i<data.length; i++){
                    contentDiv += '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
                    contentDiv += '<a href="http://www.parentfinder.com/'+ data[i].fullName +'" target="_blank"><img src="' + data[i].profile_image + '"></img></a>';
                    contentDiv += '<span>'+ data[i].fullName +'</span>'
                    contentDiv += '</div>';
                }
                $('#contentDiv').append(contentDiv);

            }

        });
    });
}

})(); // We call our anonymous function immediately
