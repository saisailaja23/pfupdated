$(function () {


  var path = location.pathname;
  if (path.indexOf('extra_profile_view_18') != -1) {
    $('.icoOurHome').addClass('active');
  }

  if (path.indexOf('extra_profile_view_19') != -1) {
    $('.icoOurLetters').addClass('active');
  }
  if (path.indexOf('extra_agency_view_27') != -1) {
    $("a[href$='agencies.php']").parent().addClass('active');
    //$('table.topMenu td.top').addClass('active');
  }

  $(".splash .side").height(window.outerHeight);
  $(window).on('resize', function () {
    $(".splash .side").height(window.outerHeight);
  });
});