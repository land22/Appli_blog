$(document).ready(function() {
    gallery_view();
});

function gallery_view()
{
	$(document).on('click', '.gallery-thumb-list li a', function(e) 
	{
		e.preventDefault();
		var imgURL = $(this).attr('data-href');
		var imgContainer = $(this).parent().parent().prev('.product_img_link').find('img');
		$(imgContainer).attr('src',imgURL);
		$(this).parent().parent().find('li.active').removeClass('active');
		$(this).parent().addClass('active');
	});
}