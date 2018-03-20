{assign var=back_page value = $link->getPageLink('index')|escape:'html':'UTF-8'}
{if $f_status}
    <a class="btn btn-default btn-sm btn-login-facebook" {if isset($back) && $back}href="{$link->getModuleLink('tmsociallogin', 'facebooklogin', [], true)}"{else}href="{$link->getModuleLink('tmsociallogin', 'facebooklogin', ['back' => $back_page], true)}"{/if} title="{l s='Login with Your Facebook Account' mod='tmsociallogin'}">
         {l s='Facebook Login' mod='tmsociallogin'}
    </a>
{/if}
{if $g_status}
    <a class="btn btn-default btn-sm btn-login-google" {if isset($back) && $back}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', ['back' => $back], true)}"{else}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', ['back' => $back_page], true)}"{/if} title="{l s='Login with Your Google Account' mod='tmsociallogin'}">
         {l s='Google Login' mod='tmsociallogin'}
    </a>
{/if}