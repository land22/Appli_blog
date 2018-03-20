$(document).ready(function() {
    images_view();
});

function images_view()
{
	$(document).on('mouseenter', '.product-image-container .product_img_link', function(e) 
	{
		if ($(this).children('img.hover-image').length)
		{
			$(this).children('img:not(.hover-image)').stop().animate({opacity: 0});
			$(this).children('img.hover-image').stop().animate({opacity: 1});
		}
	});
	$(document).on('mouseleave', '.product-image-container .product_img_link', function(e) 
	{
		if ($(this).children('img.hover-image').length)
		{
			$(this).children('img:not(.hover-image)').stop().animate({opacity: 1});
			$(this).children('img.hover-image').stop().animate({opacity: 0});
		}
	});
}