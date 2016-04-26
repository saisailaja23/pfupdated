$(document).ready(function() {
	
  // Navigation
 
  $('.menu_mobile').click(function(){
	$(this).toggleClass('collapsed');
	$('.menu').toggleClass('active');
  });

  $('.mobileSearch').click(function(){
	$(this).toggleClass('collapsed');
	$('.searchbox').toggleClass('active');
  });

	


});

