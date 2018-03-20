{* Insert javascript customisation based on the options entered in the module config screen
*}

<!-- MODULE cookieslaw -->
<script type="text/javascript">
//<![CDATA[ 
{literal}
var user_options = {
{/literal}
"cookieTop":"{$cl_CookieTop}",
"messageContent1":"{l s='We use ' mod='cookieslaw'}",
"messageContent2":"{l s='cookies' mod='cookieslaw'}",
"messageContent3":"{l s=' to give you the best experience.' mod='cookieslaw'}",
"messageContent4":"{l s='If you do nothing, we\'ll assume that\'s OK.' mod='cookieslaw'}",
"cookieUrl":"{$cl_CookieUrl}",
"redirectLink":"{$cl_RedirectLink}",
"okText":"{l s='OK, I don\'t mind using cookies' mod='cookieslaw'}",
"notOkText":"{l s='No thanks' mod='cookieslaw'}",
"cookieName":"{$cl_CookieName}",
"cookiePath":"{$cl_CookiePath}",
"cookieDomain":"{$cl_CookieDomain}",
"ajaxUrl":"{$cl_ajaxUrl}"
{literal}
};
{/literal}
// ]]> 
</script>
<!-- /MODULE cookieslaw -->
