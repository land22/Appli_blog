{if $node.id}
	{assign var="id" value=$node.id}
{else if $node.id_cms_category}
	{assign var="id" value=$node.id_cms_category}
{/if}
{if isset($node.is_cms) && $node.is_cms}
	{assign var='item' value="CMS_CAT{$id}"}
{else if isset($node.is_cms_page) && $node.is_cms_page}
	{assign var='item' value="CMS{$id}"}
{else}
	{assign var='item' value="CAT{$id}"}
{/if}
<option value="{$item}"{if $item == $active} selected="selected"{/if} {if isset($node.level_depth) && $node.level_depth}style="padding-left:{7*$node.level_depth}px"{/if} class="{if isset($node.is_cms) && $node.is_cms}cms{else if isset($node.is_cms_page) && $node.is_cms_page}cms_page{else}category{/if}">
	{$node.name|escape:'html':'UTF-8'}
	{if isset($node.children) && $node.children|@count > 0}
		{foreach from=$node.children item=child name=categoryTreeBranch}
			{include file="$branche_tpl_path" node=$child active=$active}
		{/foreach}
  	{/if}
    {if isset($node.pages) && $node.pages|@count > 0}
		{foreach from=$node.pages item=child name=categoryTreeBranch}
			{include file="$branche_tpl_path" node=$child active=$active}
		{/foreach}
  	{/if}
</option>
