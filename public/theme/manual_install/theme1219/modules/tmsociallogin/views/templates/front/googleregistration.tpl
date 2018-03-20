{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Manage my account' mod='tmsociallogin'}" rel="nofollow">{l s='My account' mod='tmsociallogin'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Google registration' mod='tmsociallogin'}{/capture}
{include file="$tpl_dir./errors.tpl"}
<form action="{$link->getModuleLink('tmsociallogin', 'googleregistration', array(), true)}" class="box">
	<div class="row">
        <div class="form-group col-lg-6">
            <img class="img-responsive" src="{$profile_image_url}" alt="{$user_name|escape:'htmlall'}" />
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>{l s='First name' mod='tmsociallogin'}</label>
                <input type="text" class="form-control" name="given_name" value="{$given_name|escape:'htmlall'}" />
            </div>
            <div class="form-group">
                <label>{l s='Last name' mod='tmsociallogin'}</label>
                <input type="text" class="form-control" name="family_name" value="{$family_name|escape:'htmlall'}" />
            </div>
            <div class="form-group">
                <label>{l s='Gender' mod='tmsociallogin'}</label>
                <label class="radio-inline">
                    <input type="radio" value="1" name="gender" {if $gender == 'male' || $gender == 1}checked{/if} />{l s='Male' mod='tmsociallogin'}
                </label>
                <label class="radio-inline">
                    <input type="radio" value="2" name="gender" {if $gender == 'famale' || $gender == 2}checked{/if} />{l s='Famale' mod='tmsociallogin'}
                </label>
            </div>
            <div class="form-group">
                <label>{l s='Email' mod='tmsociallogin'}</label>
                <input class="form-control" name="user_email" value="{$email|escape:'htmlall'}" disabled />
            </div>
        </div>
    </div>
	<input type="hidden" name="user_id" value="{$user_id|escape:'intval'}" />
    <input type="hidden" name="profile_image_url" value="{$profile_image_url}" />
    <input type="hidden" name="email" value="{$email|escape:'htmlall'}" />
    <input type="hidden" name="done" value="1" />
    <div class="text-right">
    	<button class="btn btn-primary" type="submit">{l s='Register' mod='tmsociallogin'}</button>
    </div>
</form>