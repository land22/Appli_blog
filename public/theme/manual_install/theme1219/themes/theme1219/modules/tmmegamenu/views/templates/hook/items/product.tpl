{if isset($product) && $product}
	{if !isset($priceDisplayPrecision)}
		{assign var='priceDisplayPrecision' value=2}
	{/if}
	{if !$priceDisplay || $priceDisplay == 2}
		{assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, $priceDisplayPrecision)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
	{elseif $priceDisplay == 1}
		{assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, $priceDisplayPrecision)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
	{/if}
		<!--{$product|@var_dump}-->
	<div class="product product-{$product->id}">
    	<div class="product-image">
        	<a href="{$link->getProductLink($product)}" title="{$product->name}">
        		<img class="img-responsive" src="{$link->getImageLink($product->link_rewrite, $image, 'tm_home_default')|escape:'html':'UTF-8'}" alt="{$product->name|escape:'html':'UTF-8'}" />
            </a>
        </div>
        <div class="info_block">
            <h5 class="product-name">
                <a href="{$link->getProductLink($product)}" title="{$product->name}">
                    {$product->name|truncate:30:'...'|escape:'html':'UTF-8'}
                </a>
            </h5>
            <div class="product-description">
                {if $product->description_short}{$product->description_short}{/if}
            </div>
            {if !$PS_CATALOG_MODE && $product->show_price}
                {if $productPriceWithoutReduction > $productPrice}
                    <span class="price new-price">{convertPrice price=$productPrice}</span>
                    <span class="price old-price">{convertPrice price=$productPriceWithoutReduction}</span>
                {else}
                    <span class="price">{convertPrice price=$productPrice}</span>
                {/if}
            {/if}
         </div>   
    </div>
{/if}
