{if $f_status}
	<a title="Login with your Facebook Account" class="button_large btn btn-default btn-login-facebook" {if isset($back) && $back}href="{$link->getModuleLink('tmsociallogin', 'facebooklogin', ['back' => $back], true)}"{else}href="{$link->getModuleLink('tmsociallogin', 'facebooklogin', [], true)}"{/if}>
    	{l s='Facebook Login' mod='tmsociallogin'}
    </a>
{/if}
{if $g_status}
    <a title="Login with your Google Account" class="button_large btn btn-default btn-login-google" {if isset($back) && $back}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', ['back' => $back], true)}"{else}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', [], true)}"{/if}>
    	{l s='Google Login' mod='tmsociallogin'}
    </a>
{/if}