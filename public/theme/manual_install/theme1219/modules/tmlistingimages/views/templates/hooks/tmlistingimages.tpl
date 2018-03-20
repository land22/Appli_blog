{if count($product_images) > 1}
	{foreach from=$product_images item=image}
		{assign var=imageId value="`$product.id_product`-`$image.id_image`"}
		{if !empty($image.legend)}
			{assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
		{else}
			{assign var=imageTitle value=$product.name}
       {/if}
	   {if $image.cover != 1}
            <img class="img-responsive hover-image" id="thumb-{$image.id_image}" src="{$link->getImageLink($product.link_rewrite, $imageId, 'tm_home_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" itemprop="image" />
            {break}
       {/if}
    {/foreach}
{/if}