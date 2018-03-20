<form method="post" action="" enctype="multipart/form-data" class="defaultForm form-horizontal">
	<div class="panel tmmegamenu">
        <div class="panel-heading">
            {if isset($item) && $item}{l s='Update tab' mod='tmmegamenu'}{else}{l s='Add new tab' mod='tmmegamenu'}{/if}
        </div>
        <div class="form-wrapper">
        	<div class="form-group">
                <label class="control-label col-lg-3 text-right required"> {l s='Enter tab name' mod='tmmegamenu'}</label>
                {foreach from=$languages item=language}
                	{assign var='title_text' value="title_{$language.id_lang|escape:'intval'}"}
                    <div class="translatable-field lang-{$language.id_lang|escape:'intval'}">
                        <div class="col-lg-2">
                            <input type="text" id="name_{$language.id_lang|escape:'intval'}" class="tagify CurrentText" name="name_{$language.id_lang|escape:'intval'}" value="{if isset($item) && $item}{$item.$title_text|escape:'html'}{/if}" />
                        </div>
                        <div class="col-lg-1">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                {$language.iso_code|escape:'html'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=language}
                                <li>
                                    <a href="javascript:hideOtherLanguage({$language.id_lang});">{$language.name|escape:'html'}</a>
                                </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/foreach}
            </div>
        	<div class="form-group">
                <label class="col-lg-3 control-label">{l s='Active' mod='tmmegamenu'}</label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="addnewactive" id="addnewactive_on" value="1" {if isset($item) && $item && $item.active == 1}checked="checked"{/if}>
                        <label for="addnewactive_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='tmmegamenu'}
                        </label>
                        <input type="radio" name="addnewactive" id="addnewactive_off" value="0" {if isset($item) && $item}{if $item.active == 0}checked="checked"{/if}{else}checked="checked"{/if}>
                        <label for="addnewactive_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='tmmegamenu'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
            <div class="form-group">
            	<label class="control-label col-lg-3 text-right">{l s='Link' mod='tmmegamenu'}</label>
            	<div class="col-lg-2">
            		<select id="tmmegamenu_url_type" name="tab_url_type">
            			<option value="1" {if isset($item) && $item && $item.is_custom_url}selected="selected"{/if}>Url</option>
            			<option value="0" {if isset($item) && $item && !$item.is_custom_url}selected="selected"{/if}>Existing Url</option>
            		</select>
            	</div>
            </div>
            <div class="form-group tmmegamenu_url_text">
            	<label class="control-label col-lg-3 text-right"></label>
            	<div class="col-lg-2">
                	{if isset($item) && $item}
                    	{if $item.url}
                    		{assign var='active' value=$item.url}
                        {else}
                        	{assign var='active' value=''}
                        {/if}
                    {else}
                    	{assign var='active' value=''}
                    {/if}
                	<input name="tab_url_custom" value="{if isset($item) && $item && $item.is_custom_url}{$item.url}{/if}" type="text" placeholder="{l s='Custom Url' mod='tmmegamenu'}" autocomplete="off" style="display:none;" />
            		<select name="tab_url" style="display:none;">
                    	<option disabled="disabled">{l s='Categories' mod='tmmegamenu'}</option>
            			{foreach from=$categTree.children item=child name=categTree}
                            {include file="$branche_tpl_path" node=$child active=$active}
                        {/foreach}
                        <option disabled="disabled">{l s='Cms Categories' mod='tmmegamenu'}</option>
                        {foreach from=$cmsCatTree item=child name=categTree}
                            {include file="$branche_tpl_path" node=$child active=$active}
                        {/foreach}
            		</select>
            	</div>
            </div>
           	<div class="selector item-field form-group">
            	<label class="control-label col-lg-3">{l s='Sort order' mod='tmmegamenu'}</label>
            	<div class="col-lg-1">
                    <input type="text" name="sort_order" value="{if isset($item) && $item}{$item.sort_order}{/if}">
                </div>
            </div>
            <div class="item-field form-group">
                <label class="control-label col-lg-3">{l s='Specific class' mod='tmmegamenu'}</label>
                <div class="col-lg-2">
                    <input type="text" name="specific_class" value="{if isset($item) && $item}{$item.specific_class}{/if}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 text-right"> {l s='Enter tab badge' mod='tmmegamenu'}</label>
                {foreach from=$languages item=language}
                	{if isset($item) && $item}
                		{assign var='badge_text' value="badge_{$language.id_lang|escape:'intval'}"}
                    {/if}
                    <div class="translatable-field lang-{$language.id_lang|escape:'intval'}">
                        <div class="col-lg-2">
                            <input type="text" id="badge_{$language.id_lang|escape:'intval'}" class="tagify CurrentText" name="badge_{$language.id_lang|escape:'intval'}" value="{if isset($item) && $item}{$item.$badge_text}{/if}" />
                        </div>
                        <div class="col-lg-1">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                {$language.iso_code|escape:'html'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=language}
                                <li>
                                    <a href="javascript:hideOtherLanguage({$language.id_lang});">{$language.name|escape:'html'}</a>
                                </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/foreach}
            </div>
            <div id="is_mega_menu" class="form-group">
                <label class="col-lg-3 control-label">{l s='It is Mega Menu' mod='tmmegamenu'}</label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="addnewmega" id="addnewmega_on" value="1" {if isset($item) && $item && $item.is_mega}checked="checked"{/if}>
                        <label for="addnewmega_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='tmmegamenu'}
                        </label>
                        <input type="radio" name="addnewmega" id="addnewmega_off" value="0" {if isset($item) && $item}{if !$item.is_mega}checked="checked"{/if}{else}checked="checked"{/if}>
                        <label for="addnewmega_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='tmmegamenu'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
            <div id="is_simple_menu" class="form-group">
                <label class="col-lg-3 control-label">{l s='Use simple menu' mod='tmmegamenu'}</label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="issimplemenu" id="issimplemenu_on" value="1" {if isset($item) && $item && $item.is_simple}checked="checked"{/if}>
                        <label for="issimplemenu_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='tmmegamenu'}
                        </label>
                        <input type="radio" name="issimplemenu" id="issimplemenu_off" value="0" {if isset($item) && $item}{if !$item.is_simple}checked="checked"{/if}{else}checked="checked"{/if}>
                        <label for="issimplemenu_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='tmmegamenu'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
            <div class="simple_menu">
            	<div class="form-group">
                    <label class="control-label col-lg-3"> </label>
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-4">
                                <h4>{l s='Available items' mod='tmmegamenu'}</h4>
                                <div class="form-group">
                                	{$option_select}
                                </div>
                                <button id="addItem" class="btn btn-default">{l s='Add' mod='tmmegamenu'} -></button>
                            </div>
                            <div class="col-lg-1 order-buttons">
                            	<button id="menuOrderUp" class="btn btn-default btn-block">{l s='Up' mod='tmmegamenu'}</button>
                                <button id="menuOrderDown" class="btn btn-default btn-block">{l s='Down' mod='tmmegamenu'}</button>
                            </div>
                            <div class="col-lg-4">	
                                <h4>{l s='Selected items' mod='tmmegamenu'}</h4>
                                <div class="form-group">
                                	{$option_selected}
                                </div>
                                <button id="removeItem" class="btn btn-default"><- {l s='Remove' mod='tmmegamenu'}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mega_menu">
            	<div id="megamenu-content">
                	{if isset($megamenu) && $megamenu}{$megamenu}{/if}
                </div>
            	<a class="btn btn-default" id="add-megamenu-row" href="#" onclick="return false;">{l s='Add row' mod='tmmegamenu'}</a>
                <input type="hidden" value="" name="megamenu_options" />
            </div>
            <input type="hidden" name="id_tab" value="{if isset($item) && $item}{$item.id_item}{/if}" />
            <input type="hidden" name="id_item" value="{if isset($item) && $item}{$item.id_item}{/if}" />
        </div>
        <div class="panel-footer">
        	<button type="submit" name="updateItem" class="button-new-item-save btn btn-default pull-right" onClick="this.form.submit();"><i class="process-icon-save"></i> {l s='Save' mod='tmmegamenu'}</button>
          	<button type="submit" name="updateItemStay" class="button-new-item-save-stay btn btn-default pull-right" onClick="this.form.submit();"><i class="process-icon-save"></i> {l s='Save & Stay' mod='tmmegamenu'}</button>
            <a class="btn btn-default" href="{$url_enable}">
            	<i class="process-icon-cancel"></i>
                {l s='Cancel' mod='tmmegamenu'}
            </a>
        </div>
    </div>
</form>
<script type="text/javascript">
	hideOtherLanguage({$default_language.id_lang|escape:'html'});
</script>
{strip}
	{addJsDefL name=product_add_text}{l s='Indicate the ID number for the product' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=product_id}{l s='Product ID #' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=move_warning}{l s='Please select just one item' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=add_megamenu_column}{l s='Add column' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_width_label}{l s='Set column width' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_width_text}{l s='Set column width (2 min -> 12 max)' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_width_alert_min_text}{l s='Column width can not be less than 2' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_width_alert_max_text}{l s='Column width can not be  less than 2 and more than 12' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_items_text}{l s='Set content' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_items_selected_text}{l s='Selected item' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_class_text}{l s='Enter specific class' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_content_type_text}{l s='Content type' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_html_text}{l s='HTML' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=col_links_text}{l s='Links' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=add_col_content_text}{l s='Add content' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=error_type_text}{l s='Please select column content type!' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=btn_add_text}{l s='Add' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=btn_remove_text}{l s='Remove' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=btn_remove_column_text}{l s='Remove block' mod='tmmegamenu'}{/addJsDefL}
   	{addJsDefL name=btn_remove_row_text}{l s='Remove row' mod='tmmegamenu'}{/addJsDefL}
    {addJsDefL name=warning_class_text}{l s='Can not contain special chars, only _ is allowed.(Will be automatically replaced)' mod='tmmegamenu'}{/addJsDefL}
    {addJsDef option_list=$option_select}
{/strip}