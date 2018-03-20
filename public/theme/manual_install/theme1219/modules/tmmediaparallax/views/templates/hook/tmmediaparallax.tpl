{addJsDef scroll_step=$smooth_scroll_step|intval}
{addJsDef scrool_speed=$smooth_scroll_speed|intval}
{if $smooth_scroll_on == 1}
	<script type="text/javascript">
		$(document).ready(function() {
			if(!device.mobile() && !device.tablet()){
				$.srSmoothscroll({
					step: scroll_step,
					speed: scrool_speed
				});
			}
		});
	</script>
{/if}
<script type="text/javascript">

	function addVideoParallax(selector, path, filename)
	{
		var selector = $(selector);

		selector.addClass('parallax_section');
		selector.attr('data-type-media', 'video_html');
		selector.attr('data-mp4', 'true');
		selector.attr('data-webm', 'true');
		selector.attr('data-ogv', 'true');
		selector.attr('data-poster', 'true');
		selector.wrapInner('<div class="container parallax_content"></div>');
		selector.append('<div class="parallax_inner"><video class="parallax_media" width="100%" height="100%" autoplay loop poster="{$base_path}'+path+filename+'.jpg"><source src="{$base_path}'+path+filename+'.mp4" type="video/mp4"><source src="{$base_path}'+path+filename+'.webdm" type="video/webm"><source src="{$base_path}'+path+filename+'.ogv" type="video/ogg"></video></div>');

		selector.tmMediaParallax();
	}
	
	function addImageParallax(selector, path, filename, width, height)
	{
		var selector = $(selector);

		selector.addClass('parallax_section');
		selector.attr('data-type-media', 'image');
		selector.wrapInner('<div class="container parallax_content"></div>');
		selector.append('<div class="parallax_inner"><img class="parallax_media" src="{$base_path}'+path+filename+'" data-base-width="'+width+'" data-base-height="'+height+'"/></div>');

		selector.tmMediaParallax();
	}

	$(window).load(function(){
		{foreach from=$parallaxitems item=item}
			{if $item.type == 'image'}
				addImageParallax('{$item.selector}','{$media_path}','{$item.filename}','{$item.width}','{$item.height}');
			{/if}
			{if $item.type == 'video'}
				addVideoParallax('{$item.selector}','{$media_path}','{$item.filename}');
			{/if}
		{/foreach}
	});
</script>
