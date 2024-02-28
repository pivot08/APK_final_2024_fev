$(document).ready(function () {

	$("#demoFour").listnav({
		includeNums: false,
	});

	$('#news-list').masonry({
		itemSelector: '.news-list-item',
	});

	$(".submenulink").click(function () {
		$(this).toggleClass('active');
		$(this).next(".submenu").slideToggle("slow");
	});

	$("#search").click(function () {
		$("#busca").slideToggle();
	});

	$("#toggle").click(function () {
		$(this).toggleClass("active");
		$("#naveg").toggleClass("naveg-active");
		$(".logo").toggleClass("logo-active");
		$(".search").toggleClass("search-active");
		$("body").toggleClass("overflow-hidden");
	});

});
