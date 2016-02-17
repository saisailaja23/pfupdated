$(document).ready(function () {

  $.getJSON(siteurl + 'viewourfamilies/processors/journalList.php?loadFrom=' + loadFrom + '&jorID='+journalId+'&id=' + id, function (jsonData) {
    if (jsonData.status == 'success') {
      jLink = encodeURIComponent(jsonData.blog_posts.rows[0].data[2].replace(/[^a-zA-Z1-9 ]/g, ""));
      $('.jorHead').html('<a href="' + siteurl + nickName + '/journal/' + jLink + '/' + jsonData.blog_posts.rows[0].data[3] + '/' + loadFrom + '">' + jsonData.blog_posts.rows[0].data[2] + '</a>');
//      $('.jorID').html('Journal ID: ' + jsonData.blog_posts.rows[0].data[3] + '</a>');
      $('.jorBody').html(jsonData.blog_posts.rows[0].data[1] + '<div style="clear:both"></div><br><br>' + '<span>' + jsonData.blog_posts.rows[0].data[0] + '</span> ' + '<br><br>');
      if (head.mobile) {
        $('.jorBody').find('img').css('max-width','280px');
        $('.jorBody').find('img').css('height','auto');
      }
    }
    else {
      $('.ourjournal').html('THE REQUESTED JOURNAL NOT FOUND.');
      $('.ourjournal').css('font-size', '14px');
      $('.ourjournal').css('font-weight', 'bolder');
      $('.ourjournal').css('color', '#f195bf');
      $('.ourjournal').css('text-align', 'center');
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