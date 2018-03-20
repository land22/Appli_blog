{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Manage my account' mod='tmsociallogin'}" rel="nofollow">{l s='My account' mod='tmsociallogin'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Facebook account' mod='tmsociallogin'}{/capture}
{include file="$tpl_dir./errors.tpl"}
<div class="sociallogininfo">
    {if $facebook_status == 'error'}
        <div class="alert alert-danger">
            {$facebook_massage|escape:'htmlall'}
        </div>
        <div class="box clearfix">
            {if isset($facebook_picture)}<div class="social-avatar"><img class="img-responsive" src="{$facebook_picture|escape:'htmlall'}"></div>{/if}
            <h4 class="social-name">{$facebook_name|escape:'htmlall'}</h4>
        </div>
    {else if $facebook_status == 'linked' || $facebook_status == 'confirm'}
        <div class="alert alert-success">
            {$facebook_massage|escape:'htmlall'}
        </div>
        <div class="box clearfix">
            {if isset($facebook_picture)}<div class="social-avatar"><img class="img-responsive" src="{$facebook_picture|escape:'htmlall'}"></div>{/if}
            <h4 class="social-name">{$facebook_name|escape:'htmlall'}</h4>
        </div>
    {else}
        <div class="alert alert-danger">
            {l s='Sorry, there was error with Facebook Profile Connect.' mod='tmsociallogin'}
        </div>
    {/if}
</div>