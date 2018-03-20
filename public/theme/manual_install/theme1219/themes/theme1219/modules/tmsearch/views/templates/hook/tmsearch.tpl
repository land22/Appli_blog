<div id="tmsearch" class="clearfix">
	<form id="tmsearchbox" method="get" action="{$link->getPageLink('search', null, null, null, false, null, true)|escape:'html':'UTF-8'}" >
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
        <div class="currentBox"></div>
		<input class="tm_search_query form-control toogle_content_box" type="text" id="tm_search_query" name="search_query" placeholder="{l s='Search' mod='tmsearch'}" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
		<button type="submit" name="tm_submit_search" class="btn btn-default button-search">
			<span>{l s='Search' mod='tmsearch'}</span>
		</button>
	</form>
</div>