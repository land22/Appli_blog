{if $f_status}
    <a class="btn btn-default btn-sm btn-login-facebook" href="{$link->getModuleLink('tmsociallogin', 'facebookregistration', [], true)}" title="{l s='Register with Your Facebook Account' mod='tmsociallogin'}">
         {l s='Register with Your Facebook Account' mod='tmsociallogin'}
    </a>
{/if}
{if $g_status}
    <a class="btn btn-default btn-sm btn-login-google" {if isset($back) && $back}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', ['back' => $back], true)}"{else}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', [], true)}"{/if} title="{l s='Register with Your Google Account' mod='tmsociallogin'}">
         {l s='Register with Your Google Account' mod='tmsociallogin'}
    </a>
{/if}
