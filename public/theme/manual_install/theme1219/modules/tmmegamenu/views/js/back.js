$(document).ready(function(e) {
	initItemsButtons();
	updateRowsInfo();

	if ($('input[name="addnewmega"]:checked').val() == 1)
		$('#is_simple_menu').hide();
	else
		$('.mega_menu').hide();
		

	if ($('input[name="issimplemenu"]:checked').val() == 1)
		$('#is_mega_menu').hide();
	else
		$('.simple_menu').hide();

	if ($('#tmmegamenu_url_type').val() == 1)
		$('.tmmegamenu_url_text input').show();
	else
		$('.tmmegamenu_url_text select').show()

	$(document).on('change', '#tmmegamenu_url_type', function(){
		if($(this).val() == 1)
			$('.tmmegamenu_url_text select').hide(),
			$('.tmmegamenu_url_text input').show()
		else
			$('.tmmegamenu_url_text input').hide(),
			$('.tmmegamenu_url_text select').show()
	});

	$(document).on('change', 'input[name="addnewmega"]', function(){
		if($(this).val() == 1)
			$('#is_simple_menu').hide(),
			$('.mega_menu').show();
		else
			$('#is_simple_menu').show(),
			$('.mega_menu').hide();
	});

	$(document).on('change', 'input[name="issimplemenu"]', function(){
		if($(this).val() == 1)
			$('#is_mega_menu').hide(),
			$('.simple_menu').show();
		else
			$('#is_mega_menu').show(),
			$('.simple_menu').hide();
	});

	megamenuConstructor();
});

function initItemsButtons()
{
	$('#menuOrderUp').click(function(e){
		e.preventDefault();
		move(true);
	});

	$('#menuOrderDown').click(function(e){
		e.preventDefault();
		move();
	});

	$("#simplemenu_items").closest('form').on('submit', function(e) {
		$("#simplemenu_items option").prop('selected', true);
	});

	$("#addItem").click(add);
	$("#availableItems").dblclick(add);
	$("#removeItem").click(remove);
	$("#simplemenu_items").dblclick(remove);

	function add()
	{
		$(".simple_menu #availableItems option:selected").each(function(i){
			var val = $(this).val();
			var text = $(this).text();
			text = text.replace(/(^\s*)|(\s*$)/gi,"");

			if (val == "PRODUCT")
			{
				val = prompt(product_add_text);
				if (val == null || val == "" || isNaN(val))
					return;
				text = product_id+val;
				val = "PRD"+val;
			}
			if (val == "PRODUCTINFO")
			{
				val = prompt(product_add_text);
				if (val == null || val == "" || isNaN(val))
					return;
				text = product_id+val+' (info)';
				val = "PRDI"+val;
			}

			$(".simple_menu #simplemenu_items").append('<option value="'+val+'" selected="selected">'+text+'</option>');
		});
		serialize();
		return false;
	}

	function remove()
	{
		$("#simplemenu_items option:selected").each(function(i){
			$(this).remove();
		});

		serialize();

		return false;
	}

	function serialize()
	{
		var options = "";

		$("#simplemenu_items option").each(function(i){
			options += $(this).val()+",";
		});

		$("#itemsInput").val(options.substr(0, options.length - 1));
	}

	function move(up)
	{
			var tomove = $('#simplemenu_items option:selected');

			if (tomove.length >1)
			{
					alert(move_warning);
					return false;
			}

			if (up)
					tomove.prev().insertAfter(tomove);
			else
					tomove.next().insertBefore(tomove);

			serialize();

			return false;
	}
}

function megamenuConstructor()
{
	var megamenuContent = $('#megamenu-content');
	var megamenuCol = '';

	/***
		Add one more row to the menu tab
	***/
	$(document).on('click', '#add-megamenu-row', function(){
		megamenuContent.append(megamenuRowConstruct());
	});

	/***
		Add one more col to the row of the menu tab
	***/
	$(document).on('click', '.add-megamenu-col', function(){
		columnWidth = prompt('Select column type', 2);
		if(columnWidth < 2 || columnWidth > 12)
		{
			alert(col_width_alert_max_text);
			return;
		}
		parrentRow = $(this).parents('.megamenu-row').attr('id');

		$(this).parents('.megamenu-row').append(megamenuColConstruct(parrentRow, columnWidth));

		updateRowInfo('#'+parrentRow); // update row information after new col added
	});

	/***
		 Update data after any block changes 
	***/
	$(document).on('change', '.mega_menu .megamenu-col input, .mega_menu .megamenu-col select', function(){
		var item_num = $(this).parents('.megamenu-col').attr('id').split('-');
		item_num = item_num[2];
		var col_type = $(this).parents('.megamenu-col').find('select[name="col-item-type"]').val();
		var col_class = $(this).parents('.megamenu-col').find('input[name="col-item-class"]').val();
		var col_content_type = $(this).parents('.megamenu-col').find('select[name="col-content-type"]').val();
		var col_items = '';
		var devider = '';
		$(this).parents('.megamenu-col').find('select[name="col-item-items"] option').each(function() {
           col_items += devider + $(this).val();
		   devider = ',';
        });

		$(this).parents('.megamenu-col').find('input[name="col_content"]').val('{col-'+item_num+'-'+col_type+'-('+col_class+')-'+col_content_type+'-['+col_items+']}'); // build hidde input with parameters

		updateRowInfo('#'+$(this).parents('.megamenu-row').attr('id'));
	});

	/***
		Add multiple selected items to the column parameters
	***/
	$(document).on('click', '.add-item-to-selected', function(){
		var element = $(this).parents('.megamenu-col');
		element.find('.availible_items option:selected').each(function() {
			element.find('select[name="col-item-items"]').append('<option value="'+$(this).val()+'" selected="selected">'+$.trim($(this).text())+'</option>');
		});
		element.find('select[name="col-item-items"]').trigger('change');
	});

	/***
		Add one selected item to the column parameters by doubleclick
	***/
	$(document).on('dblclick', 'select.availible_items option', function(){
		var element = $(this).parents('.megamenu-col');
		var val = $(this).val();
		var text = $.trim($(this).text());
		if (val == "PRODUCT")
		{
			val = prompt(product_add_text);
			if (val == null || val == "" || isNaN(val))
				return;
			text = product_id+val;
			val = "PRD"+val+' (link)';
		}
		if (val == "PRODUCTINFO")
		{
			val = prompt(product_add_text);
			if (val == null || val == "" || isNaN(val))
				return;
			text = product_id+val+' (info)';
			val = "PRDI"+val;
		}
		element.find('select[name="col-item-items"]').append('<option value="'+val+'" selected="selected">'+text+'</option>');
		$('select[name="col-item-items"]').trigger('change');
	});

	/***
		Remove multiple selected items from the column parameters
	***/
	$(document).on('click', '.remove-item-from-selected', function(){
		var element = $(this).parents('.megamenu-col');
		element.find('select[name="col-item-items"] option:selected').each(function() {
			element.find(this).remove();
		});
		element.find('select[name="col-item-items"]').trigger('change');
	});

	/***
		Remove one selected item from the column parameters
	***/
	$(document).on('dblclick', 'select[name="col-item-items"] option', function(){
		var element = $(this).parents('.megamenu-col');
		element.find(this).remove();
		element.find('select[name="col-item-items"]').trigger('change');
	});
	
	/***
		Remove column button
	***/
	$(document).on('click', '.btn-remove-column', function(){
		var column = $(this).parents('.megamenu-col');
		var row = '#'+column.parents('.megamenu-row').attr('id');
		column.remove();
		updateRowInfo(row);
	});

	/***
		Remove row button
	***/
	$(document).on('click', '.btn-remove-row', function(){
		var row = $(this).parents('.megamenu-row');
		row.remove();
		updateRowsInfo();
	});

	/***
		Replace all special chars by "_"
	***/
	$(document).on('change', 'input[name="col-item-class"]', function(){
		var old_class = $(this).val();
		var new_class = old_class.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');
		$(this).val(new_class);	
	})
}

function megamenuRowConstruct()
{
	html = '';
	var num = [];

	$('.megamenu-row').each(function() { // build array of existing rows ids
       tmp_num = $(this).attr('id').split('-');
	   tmp_num = tmp_num[2];
	   num.push(tmp_num);
    });
	if($.isEmptyObject(num)) // check if any row already exist if not set 1
		num = 1;
	else // check if any row already exist if yes set max + 1
		num = Math.max.apply(Math,num) + 1;

	html += '<div id="megamenu-row-'+num+'" class="megamenu-row row">';
		html += '<div class="clearfix">';
			html += '<div class="add-column-button-container col-lg-6">';
				html += '<a href="#" onclick="return false;" class="btn btn-success add-megamenu-col">'+add_megamenu_column+'</a>';
			html += '</div>';
			html += '<div class="remove-row-button col-lg-6 text-right">';
				html += '<a class="btn btn-danger btn-remove-row" href="#" onclick="return false;">'+btn_remove_row_text+'</a>';
			html += '</div">';
		html += '</div>';
		html += '<input type="hidden" name="row_content" />';
	html += '</div>';

	return html;	
}

function megamenuColConstruct(parrentRow, columnWidth)
{
	var html = '';
	var num = [];
	var parrentId = parseInt(parrentRow.replace ( /[^\d.]/g, '' ));
	
	$('#'+parrentRow+' .megamenu-col').each(function() {
       tmp_num = $(this).attr('id').split('-');
	   tmp_num = tmp_num[2];
	   num.push(tmp_num);
    });

	if($.isEmptyObject(num))
		num = 1;
	else
		num = Math.max.apply(Math,num) + 1;

	html +='<div id="column-'+parrentId+'-'+num+'" class="megamenu-col megamenu-col-'+num+' col-lg-'+columnWidth+'">';
		html += '<div class="megamenu-col-inner">';
			html += '<div class="form-group">';
				html +='<label>'+col_width_label+'</label>';
					html += '<select class="form-control" name="col-item-type">';
								for(i=1; i<=getReminingWidth(parrentRow); i++)
								{
									columnWidth==i?selected='selected="selected"':selected='';
									html += '<option '+selected+' value="'+i+'">col-'+i+'</option>';	
								}
					html += '</select>';
				html += '</div>';
			html += '<div class="form-group">';
				html +='<label>'+col_class_text+'</label>';
				html += '<input class="form-control" type="text" name="col-item-class" />';
				html += '<p class="help-block">'+warning_class_text+'</p>';
			html += '</div>';
			html += '<div class="form-group">';
				html +='<label>'+col_items_text+'</label>';
				html += option_list;
			html += '</div>';
			html += '<div class="form-group buttons-group">';
				html += '<a class="add-item-to-selected btn btn-default" href="#" onclick="return false;">'+btn_add_text+'</a>';
				html += '<a class="remove-item-from-selected btn btn-default" href="#" onclick="return false;">'+btn_remove_text+'</a>';
			html += '</div>';
			html += '<div class="form-group">';
				html +='<label>'+col_items_selected_text+'</label>';
				html += '<select multiple="multiple" style="height: 160px;" name="col-item-items"></select>';
			html += '</div>';
			html += '<div class="remove-block-button">';
				html += '<a href="#" class="btn btn-default btn-remove-column" onclick="return false;">'+btn_remove_column_text+'</a>';
			html += '</div>';
		html += '</div>';
		html += '<input type="hidden" name="col_content" value="{col-'+num+'-'+columnWidth+'-()-0-[]}" />';
	html += '</div>';

	return html;	
}

function updateRowInfo(row)
{
	var data = '';

	$(row+' .megamenu-col').each(function() {
        data += $(this).find('input[name="col_content"]').val();
    });

	$(row+' input[name="row_content"]').val(data);

	updateRowsInfo();
}

function updateRowsInfo()
{
	var data = '';
	var id_row;
	var delimeter = '';

	$('.megamenu-row').each(function() {
		id_row = $(this).attr('id').split('-');
		id_row = id_row[2];
        data += delimeter+'row-'+id_row+$(this).find('input[name="row_content"]').val();
		delimeter = '+';
    });

	$('input[name="megamenu_options"]').val(data);
}

function getReminingWidth(row)
{
	width = 12;

	$('#'+row+' .megamenu-col').each(function() {
		//alert($(this).find('select[name="col-item-type"]').val());
		width = width - $(this).find('select[name="col-item-type"]').val();
	});

	return 12;	
}