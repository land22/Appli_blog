{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Manage my account' mod='tmsociallogin'}" rel="nofollow">{l s='My account' mod='tmsociallogin'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Google account' mod='tmsociallogin'}{/capture}
{include file="$tpl_dir./errors.tpl"}
<div class="sociallogininfo">
    {if $google_status == 'error'}
        <div class="alert alert-danger">
            {$google_massage|escape:'htmlall'}
        </div>
        <div class="box clearfix">
            {if isset($google_picture)}<div class="social-avatar"><img class="img-responsive" src="{$google_picture}"></div>{/if}
            <h4 class="social-name">{$google_name|escape:'htmlall'}<strong></strong>
        </div>
    {else if $google_status == 'linked' || $google_status == 'confirm'}
        <div class="alert alert-success">
            {$google_massage|escape:'htmlall'}
        </div>
        <div class="box clearfix">
            {if isset($google_picture)}<div class="social-avatar"><img class="img-responsive" src="{$google_picture}"></div>{/if}
            <h4 class="social-name">{$google_name|escape:'htmlall'}</h4>
        </div>
    {else}
        <div class="alert alert-danger">
            {l s='Sorry, there was error with Google Profile Connect.' mod='tmsociallogin'}
        </div>
    {/if}
</div>