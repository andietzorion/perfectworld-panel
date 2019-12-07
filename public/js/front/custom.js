$('.navbar-black .menu-toggler').click(function(e){
	e.preventDefault();
	$('.navbar-black .navbar-nav').toggleClass('active');
	$('.navbar-black .navbar-black-content').toggleClass('active');
	$(this).toggleClass('active');
	$('body').toggleClass('ov-h');
})

$('.navbar-black').css('height', $('.navbar-black .container-fluid').outerHeight());