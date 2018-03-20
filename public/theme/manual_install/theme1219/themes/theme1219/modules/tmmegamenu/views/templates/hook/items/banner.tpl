{if isset($banner) && $banner}
	<li {if $banner.specific_class}class="{$banner.specific_class}"{/if}>
    	<a href="{$banner.url}" {if $banner.blank}target="_blank"{/if}>
        	<img class="img-responsive" src="{$image_baseurl}{$banner.image}" alt="{$banner.title}" />
        </a>
    </li>
{/if}
