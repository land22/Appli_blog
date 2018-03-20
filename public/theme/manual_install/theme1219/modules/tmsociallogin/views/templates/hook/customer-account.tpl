{if $f_status}
    <li>
        <a href="{$link->getModuleLink('tmsociallogin', 'facebooklink', [], true)}" title="{l s='Facebook Login Manager' mod='tmsociallogin'}">
           <i class="fa fa-facebook"></i>
           <span>{if $facebook_status}{l s='Connect Whith Facebook' mod='tmsociallogin'}{else}{l s='Facebook Login Manager' mod='tmsociallogin'}{/if}</span>
        </a>
    </li>
{/if}
{if $g_status}
    <li>
        <a {if isset($back) && $back}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', ['back' => $back], true)}"{else}href="{$link->getModuleLink('tmsociallogin', 'googlelogin', [], true)}"{/if} title="{l s='Google Login Manager' mod='tmsociallogin'}">
            <i class="fa fa-google"></i>
            <span>{if $google_status}{l s='Connect Whith Google' mod='tmsociallogin'}{else}{l s='Google Login Manager' mod='tmsociallogin'}{/if}</span>
        </a>
    </li>
{/if}
