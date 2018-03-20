{strip}
    {addJsDef user_newsletter_status = $user_newsletter_status|escape:'intval'}
    {addJsDef popup_status = $popup_status|escape:'intval'}
    {addJsDef module_url = $module_url|escape:'html'}

    {addJsDefL name=text_close}{l s='Close' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_sign}{l s='Subscribe' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_email}{l s='Your E-Mail' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_heading}{l s='Subscribe to our newsletter' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_heading_1}{l s='Subscribe to our newsletter' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_heading_2}{l s='Success' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_heading_3}{l s='Error' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_remove}{l s='Do not show again' mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_description}{l s='Enter your email address to receive all news, updates on new arrivals, special offers and other discount information.'  mod='tmnewsletter'}{/addJsDefL}
    {addJsDefL name=text_placeholder}{l s='Enter your e-mail'  mod='tmnewsletter'}{/addJsDefL}
{/strip}