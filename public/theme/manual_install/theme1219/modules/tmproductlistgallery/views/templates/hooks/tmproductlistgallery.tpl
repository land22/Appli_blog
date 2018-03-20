{if count($product_images) > 1}
    <ul class="gallery-thumb-list">
        {foreach from=$product_images item=image name=image}
            {assign var=imageId value="`$product.id_product`-`$image.id_image`"}
            {if !empty($image.legend)}
                {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
            {else}
                {assign var=imageTitle value=$product.name}
            {/if}
            {if $smarty.foreach.image.iteration < 4}
                <li id="thumb-{$product.id_product}-{$image.id_image}" class="gallery-image-thumb{if $image.cover == 1} active{/if}">
                    <a href="{$product.link|escape:'html':'UTF-8'}" title="{$imageTitle}" data-href="{$link->getImageLink($product.link_rewrite, $imageId, 'tm_home_default')|escape:'html':'UTF-8'}">
                        <img class="img-responsive" id="thumb-{$image.id_image}" src="{$link->getImageLink($product.link_rewrite, $imageId, 'tm_cart_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" itemprop="image" />
                    </a>
                </li>
            {/if}
        {/foreach}
    </ul>
{/if}