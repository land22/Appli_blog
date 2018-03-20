<div class="panel tmmegamenu">
    <h3>
        {l s='Tabs list' mod='tmmegamenu'}
        <span class="panel-heading-action">
        <a id="desc-product-new" class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addItem">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
    {if isset($tabs) && $tabs}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='Tab id' mod='tmmegamenu'}</th>
                        <th>{l s='Tab name' mod='tmmegamenu'}</th>
                        <th>{l s='Tab code' mod='tmmegamenu'}</th>
                        <th>{l s='Sort order' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th>{l s='Badge' mod='tmmegamenu'}</th>
                        <th>{l s='Type' mod='tmmegamenu'}</th>
                        <th>{l s='Status' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$tabs item=tab name='tab'}
                        <tr {if $smarty.foreach.tab.iteration is div by 2}class = "odd"{/if}>
                            <td>{$tab.id_item|escape:'intval'}</td>
                            <td>{$tab.title|escape:'intval'}</td>
                            <td>{$tab.url}</td>
                            <td>{$tab.sort_order|escape:'intval'}</td>
                            <td>{if $tab.specific_class}{$tab.specific_class|escape:'htmlall'}{else}-{/if}</td>
                            <td>{if $tab.badge}{$tab.badge}{else}-{/if}</td>
                            <td>{if $tab.is_mega}{l s='Is mega' mod='tmmegamenu'}{elseif $tab.is_simple}{l s='Is simple' mod='tmmegamenu'}{else}-{/if}</td>
                            <td>
                                <a class="list-action-enable{if $tab.active} action-enabled{else} action-disabled{/if}" href="{$url_enable|escape:'html':'UTF-8'}&updateItemStatus&id_item={$tab.id_item|escape:'intval'}&itemstatus={$tab.active|escape:'intval'}" title="{if $tab.active}{l s='Enabled' mod='tmmegamenu'}{else}{l s='Disabled' mod='tmmegamenu'}{/if}">
                                    <i class="icon-check{if !$tab.active} hidden{/if}"></i>
                                    <i class="icon-remove{if $tab.active} hidden{/if}"></i>
                                </a>
                            </td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editItem&id_item={$tab.id_item|escape:'intval'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteItem&id_item={$tab.id_item|escape:'intval'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-html">
	<h3>
        {l s='HTML list' mod='tmmegamenu'}
        <span class="panel-heading-action">
        <a id="desc-product-new" class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addHtml">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($html_items) && $html_items}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='HTML id' mod='tmmegamenu'}</th>
                        <th>{l s='HTML name' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$html_items item=item name='html'}
                        <tr {if $smarty.foreach.html.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'intval'}</td>
                            <td>{$item.title|escape:'intval'}</td>
                            <td>{if $item.specific_class}{$item.specific_class|escape:'htmlall'}{else}-{/if}</td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editHtml&id_item={$item.id_item|escape:'intval'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteHtml&id_item={$item.id_item|escape:'intval'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-link">
	<h3>
        {l s='Links list' mod='tmmegamenu'}
        <span class="panel-heading-action">
        <a id="desc-product-new" class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addLink">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($links) && $links}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='Link id' mod='tmmegamenu'}</th>
                        <th>{l s='Link name' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th>{l s='URL' mod='tmmegamenu'}</th>
                        <th>{l s='Target blank' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$links item=item name='link'}
                        <tr {if $smarty.foreach.link.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'intval'}</td>
                            <td>{$item.title|escape:'intval'}</td>
                            <td>{if $item.specific_class}{$item.specific_class|escape:'htmlall'}{else}-{/if}</td>
                            <td><a href="{$item.url|escape:'htmlall'}">{$item.url|escape:'htmlall'}</a></td>
                            <td>{if $item.blank}true{else}false{/if}</td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editLink&id_item={$item.id_item|escape:'intval'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteLink&id_item={$item.id_item|escape:'intval'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>
<div class="panel tmmegamenu-link">
	<h3>
        {l s='Banners list' mod='tmmegamenu'}
        <span class="panel-heading-action">
        <a id="desc-product-new" class="list-toolbar-btn" href="{$url_enable|escape:'html':'UTF-8'}&addBanner">
            <span class="label-tooltip" data-placement="top" data-html="true" data-original-title="Add new" data-toggle="tooltip" title="">
                <i class="process-icon-new"></i>
            </span>
        </a>
    </span>
    </h3>
	{if isset($banners) && $banners}
        <div class="table-responsive-row clearfix">
            <table class="table">
                <thead>
                    <tr>
                        <th>{l s='Banner id' mod='tmmegamenu'}</th>
                        <th>{l s='Banner name' mod='tmmegamenu'}</th>
                        <th>{l s='Image' mod='tmmegamenu'}</th>
                        <th>{l s='Specific class' mod='tmmegamenu'}</th>
                        <th>{l s='URL' mod='tmmegamenu'}</th>
                        <th>{l s='Target blank' mod='tmmegamenu'}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$banners item=item name='link'}
                        <tr {if $smarty.foreach.link.iteration is div by 2}class = "odd"{/if}>
                            <td>{$item.id_item|escape:'intval'}</td>
                            <td>{$item.title|escape:'intval'}</td>
                            <td><img class="banner-thumbnail" src="{$image_baseurl}{$item.image|escape:'htmlall'}" alt="{$item.title|escape:'intval'}" /></td>
                            <td>{if $item.specific_class}{$item.specific_class|escape:'htmlall'}{else}-{/if}</td>
                            <td><a href="{$item.url|escape:'htmlall'}">{$item.url|escape:'htmlall'}</a></td>
                            <td>{if $item.blank}true{else}false{/if}</td>
                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        <a class="edit btn btn-default" title="{l s='Edit' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&editBanner&id_item={$item.id_item|escape:'intval'}"><i class="icon-pencil"></i> {l s='Edit' mod='tmmegamenu'}</a>
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-caret-down"></i>&nbsp;
                                    </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="delete" title="{l s='Delete' mod='tmmegamenu'}" href="{$url_enable|escape:'html':'UTF-8'}&deleteBanner&id_item={$item.id_item|escape:'intval'}"><i class="icon-trash"></i> {l s='Delete' mod='tmmegamenu'}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else}
    	{l s='There is no item yet.' mod='tmmegamenu'}
    {/if}
</div>