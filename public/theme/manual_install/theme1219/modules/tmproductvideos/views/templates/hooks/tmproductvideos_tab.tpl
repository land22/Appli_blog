{if isset($videos) && $videos}
    <li class="product-video-tab">
        <a href="#product-video-tab-content" data-toggle="tab">{if count($videos) > 1}{l s='Videos' mod='tmproductvideos'}{else}{l s='Video' mod='tmproductvideos'}{/if}</a>
    </li>
{/if}