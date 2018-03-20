{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Manage my account' mod='tmsociallogin'}" rel="nofollow">{l s='My account' mod='tmsociallogin'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Facebook registration' mod='tmsociallogin'}{/capture}
{include file="$tpl_dir./errors.tpl"}
<div id="fb-root"></div><script src="{$protocol}connect.facebook.net/en_US/all.js#appId={$appid}&xfbml=1"></script>
<div class="clear">
{literal}
<fb:registration 
	fields='[
		{"name":"name"},
		{"name":"first_name"},
		{"name":"last_name"},
		{"name":"email"},
		{"name":"birthday"},
		{"name":"gender"}]'
		redirect-uri="{/literal}{$redirect}{literal}" width="530"></fb:registration>
{/literal}
</div>