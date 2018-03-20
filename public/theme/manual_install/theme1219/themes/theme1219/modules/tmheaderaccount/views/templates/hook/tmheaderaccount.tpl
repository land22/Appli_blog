<div id="header-login">
    <div class="currentBox header_user_info {if $is_logged}account {else}login{/if}"><a href="#" onclick="return false;"><span>{if $is_logged}{l s='Your Account' mod='tmheaderaccount'}{else}{l s='Sign in' mod='tmheaderaccount'}{/if}</span></a></div>
    <ul id="header-login-content" class="toogle_content_box">
        {if $is_logged}
            <li>
            	<ul>
                	<li><a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='My orders' mod='tmheaderaccount'}" rel="nofollow">{l s='My orders' mod='tmheaderaccount'}</a></li>
                    {if $returnAllowed}<li><a href="{$link->getPageLink('order-follow', true)|escape:'html'}" title="{l s='My returns' mod='tmheaderaccount'}" rel="nofollow">{l s='My merchandise returns' mod='tmheaderaccount'}</a></li>{/if}
                    <li><a href="{$link->getPageLink('order-slip', true)|escape:'html'}" title="{l s='My credit slips' mod='tmheaderaccount'}" rel="nofollow">{l s='My credit slips' mod='tmheaderaccount'}</a></li>
                    <li><a href="{$link->getPageLink('addresses', true)|escape:'html'}" title="{l s='My addresses' mod='tmheaderaccount'}" rel="nofollow">{l s='My addresses' mod='tmheaderaccount'}</a></li>
                    <li><a href="{$link->getPageLink('identity', true)|escape:'html'}" title="{l s='Manage my personal information' mod='tmheaderaccount'}" rel="nofollow">{l s='My personal info' mod='tmheaderaccount'}</a></li>
                    {if $voucherAllowed}<li><a href="{$link->getPageLink('discount', true)|escape:'html'}" title="{l s='My vouchers' mod='tmheaderaccount'}" rel="nofollow">{l s='My vouchers' mod='tmheaderaccount'}</a></li>{/if}
                    {if isset($HOOK_BLOCK_MY_ACCOUNT) && $HOOK_BLOCK_MY_ACCOUNT !=''}
                        {$HOOK_BLOCK_MY_ACCOUNT}
                    {/if}
                </ul>
                <p class="logout">
                	<a href="{$link->getPageLink('index')}?mylogout" title="{l s='Sign out' mod='tmheaderaccount'}" rel="nofollow"><span>{l s='Sign out' mod='tmheaderaccount'}</span></a>
                </p>
            </li>
        {else}
            <li>
                <form action="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" method="post" id="header_login_form">
                    <div id="create_header_account_error" class="alert alert-danger" style="display:none;"></div>
                    <div class="form_content clearfix">
                        <div class="form-group">
                            <input class="is_required validate account_input form-control" data-validate="isEmail" type="text" id="header-email" name="header-email" placeholder="{l s='Email address' mod='tmheaderaccount'}" value="{if isset($smarty.post.email)}{$smarty.post.email|stripslashes}{/if}" />
                        </div>
                        <div class="form-group">
                            <span><input class="is_required validate account_input form-control" type="password" data-validate="isPasswd" id="header-passwd" name="header-passwd" placeholder="{l s='Password' mod='tmheaderaccount'}" value="{if isset($smarty.post.passwd)}{$smarty.post.passwd|stripslashes}{/if}" autocomplete="off" /></span>
                        </div>
                        <p class="submit">
                            <button type="button" id="HeaderSubmitLogin" name="HeaderSubmitLogin"><span>{l s='Sign in' mod='tmheaderaccount'}</span></button>
                        </p>
                        <p>
                        	<a href="{$link->getPageLink('my-account', true)|escape:'html'}" class="create"><span>{l s='Create an account' mod='tmheaderaccount'}</span></a>
                        </p>
                        <div class="clearfix">
                        	{hook h="displayHeaderLoginButtons"}
                        </div>
                    </div>
                </form>
            </li>
        {/if}
    </ul>
</div>