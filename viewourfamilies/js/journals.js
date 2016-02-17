$(document).ready(function () {

  var getUrlVars = function() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
      vars[key] = value;
    });
    return vars;
  };
  
  console.log(getUrlVars());

  $.getJSON(siteurl + 'viewourfamilies/processors/journalList.php?loadFrom=' + loadFrom + '&jorID=jorList&id=' + id, function (jsonData) {
    if (jsonData.blog_posts.rows[0]) {
      for (i in jsonData.blog_posts.rows) {
        jouHead = $("<div>", {class: "jorHead"});
        jLink = jsonData.blog_posts.rows[i].data[3].replace(/[^a-zA-Z1-9 ]/g, "");
        //jLink =jsonData.blog_posts.rows[i].data[3];
        jLink = encodeURIComponent(jLink);
        $(jouHead).html('<a href="' + siteurl + nickName + '/journal/' + jLink + '/' + jsonData.blog_posts.rows[i].data[4] + '/'+ loadFrom + '">' + jsonData.blog_posts.rows[i].data[3] + '</a>');
        jouBody = $("<div>", {class: "jorBody"});
        if(jsonData.blog_posts.rows[i].data[1].trim() != '')
            $(jouBody).html(jsonData.blog_posts.rows[i].data[1] + '<br><br>' + '<span>' + jsonData.blog_posts.rows[i].data[0] + '</span> ' + '<br><br>');
        else
            $(jouBody).html('<span>' + jsonData.blog_posts.rows[i].data[0] + '</span> ' + '<br><br>');
        $('.ourjournal').append(jouHead);
        $('.ourjournal').append(jouBody);
      }
      $('#seemore a').attr('href', siteurl + 'modules/boonex/blogs/blogs.php?action=show_member_blog&ownerID=' + id);
    }
    else {
      $('.ourjournal1').css('display', 'none');
    }
  });

  if (loadFrom == 'badge') {
    $('.back').css('display', 'block');
    $('.back').click(function () {
      window.history.back();
    });
  }
  else {
    $('.back').css('display', 'none');
  }
});