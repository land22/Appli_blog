<!-- Block currencies module -->
<div class="currency_language">
<div class="currentBox"></div>
<div class="toogle_content_box">
{if count($currencies) > 1}
	<div id="currencies-block-top">
		<form id="setCurrency" action="{$request_uri}" method="post">
			<div class="current">
				<input type="hidden" name="id_currency" id="id_currency" value=""/>
				<input type="hidden" name="SubmitCurrency" value="" />
				<span class="cur-label">{l s='Currency' mod='blockcurrencies'} :</span>
				{foreach from=$currencies key=k item=f_currency}
					{if $cookie->id_currency == $f_currency.id_currency}<strong>{$f_currency.iso_code}</strong>{/if}
				{/foreach}
			</div>
			<ul id="first-currencies" class="currencies_ul toogle_content">
				{foreach from=$currencies key=k item=f_currency}
                	{if strpos($f_currency.name, '('|cat:$f_currency.iso_code:')') === false}
						{assign var="currency_name" value={l s='%s (%s)' sprintf=[$f_currency.name, $f_currency.iso_code]}}
					{else}
						{assign var="currency_name" value=$f_currency.name}
					{/if}
					<li {if $cookie->id_currency == $f_currency.id_currency}class="selected"{/if}>
						<a href="javascript:setCurrency({$f_currency.id_currency});" rel="nofollow" title="{$currency_name}">
							{$currency_name}
						</a>
					</li>
				{/foreach}
			</ul>
		</form>
	</div>
{/if}
<!-- /Block currencies module -->

<!-- Block languages module -->
{if count($languages) > 1}
	<div id="languages-block-top" class="languages-block">
		{foreach from=$languages key=k item=language name="languages"}
			{if $language.iso_code == $lang_iso}
				<div class="current">
					<span>{$language.name|regex_replace:"/\s\(.*\)$/":""}</span>
				</div>
			{/if}
		{/foreach}
		<ul id="first-languages" class="languages-block_ul toogle_content">
			{foreach from=$languages key=k item=language name="languages"}
				<li {if $language.iso_code == $lang_iso}class="selected"{/if}>
				{if $language.iso_code != $lang_iso}
					{assign var=indice_lang value=$language.id_lang}
					{if isset($lang_rewrite_urls.$indice_lang)}
						<a href="{$lang_rewrite_urls.$indice_lang|escape:'html':'UTF-8'}" title="{$language.name}">
					{else}
						<a href="{$link->getLanguageLink($language.id_lang)|escape:'html':'UTF-8'}" title="{$language.name}">
					{/if}
				{/if}
						<span>{$language.name|regex_replace:"/\s\(.*\)$/":""}</span>
				{if $language.iso_code != $lang_iso}
					</a>
				{/if}
				</li>
			{/foreach}
		</ul>
	</div>
{/if}
</div></div>
<!-- /Block languages module -->