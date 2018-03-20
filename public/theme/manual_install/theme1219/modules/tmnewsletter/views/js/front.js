$(document).ready(function() {
	initTemplate();
	if(popup_status)
	{
		if(!user_newsletter_status)
			setTimeout('setTemplate(html)', 4000);
		else if (user_newsletter_status == 2)
			setTimeout('setTemplate(html_1)', 4000);

		$(document).on('click', '#newsletter_popup', function(event){
			event.stopPropagation();
		});
		$(document).on('click', '.tmnewsletter-close, .newsletter-overlay', function(){
			closePopup();
			updateDate();
		});
		$(document).on('click', '.tmnewsletter-submit', function(){
			submitNewsletter();
		});
	}
});

function initTemplate()
{
	/*********** no autorized user ************/
	html = '';
	html += '<div id="newsletter_popup" class="tmnewsletter">';
		html += '<div class="tmnewsletter-inner">';
			html += '<div class="tmnewsletter-close icon">';
			html += '</div>';
			html += '<div class="tmnewsletter-header">';
			html +='<h4>'+text_heading+'</h4>';
			html += '</div>';
			html += '<div class="tmnewsletter-content">';
				html += '<div class="status-message"></div>';
				html += '<p class="description">'+text_description+'</p>';
				html += '<div class="form-group">';
					html += '<input class="form-control" placeholder="'+text_placeholder+'" type="email" name="email" />';
				html +='</div>';
			html += '</div>';
			html += '<div class="tmnewsletter-footer">';
				html += '<button class="btn btn-default tmnewsletter-close">'+text_close+'</button>';
				html += '<button class="btn btn-default tmnewsletter-submit">'+text_sign+'</button>';
			html += '</div>';
		html += '</div>';
	html += '</div>';

	/****************** autorized user but no newsletter *********************/
	html_1 = '';
	html_1 += '<div id="newsletter_popup" class="tmnewsletter tmnewsletter-autorized">';
		html_1 += '<div class="tmnewsletter-inner">';
			html_1 += '<div class="tmnewsletter-close icon">';
			html_1 += '</div>';
			html_1 += '<div class="tmnewsletter-header">';
			html_1 +='<h4>'+text_heading_1+'</h4>';
			html_1 += '</div>';
			html_1 += '<div class="tmnewsletter-content">';
				html_1 += '<div class="tmnewsletter-content">';
					html_1 += '<div class="status-message"></div>';
					html_1 += '<p class="description">'+text_description+'</p>';
					html_1 += '<div class="form-group">';
						html_1 += '<label>'+text_email+'</label>';
						html_1 += '<input class="form-control" placeholder="'+text_placeholder+'" type="email" name="email" />';
					html_1 += '</div>';
				html_1 += '</div>';
			html_1 += '</div>';
			html_1 += '<div class="tmnewsletter-footer">';
				html_1 += '<div class="checkbox"><input type="checkbox" name="disable_popup" />'+text_remove+'</div>';
				html_1 += '<button class="btn btn-default tmnewsletter-close">'+text_close+'</button>';
				html_1 += '<button class="btn btn-default tmnewsletter-submit">'+text_sign+'</button>';
			html_1 += '</div>';
		html_1 += '</div>';
	html_1 += '</div>';	
}

function setTemplate(html)
{
	$('body').append('<div class="newsletter-overlay">'+html+'</div>');
	$("#newsletter_popup .checkbox input").uniform();
}

function displaySuccess(message)
{
	successMessage = '';
	successMessage += '<div class="success-message alert alert-success">'+message+'</div>';
	$('body').find('#newsletter_popup .status-message').html(successMessage);	
}

function displayError(message)
{
	/****************** error message *********************/
	errormessage = '';
	errormessage += '<div class="error-message alert alert-danger">'+message+'</div>';
	$('body').find('#newsletter_popup .status-message').html(errormessage);
}

function closePopup()
{
	$('#newsletter_popup, .newsletter-overlay').fadeOut(300, function(){$('#newsletter_popup').remove()});
}

function validateEmail(email)
{
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test(email);
}

function submitNewsletter()
{
	$('#newsletter_popup.tmnewsletter-autorized .checker > span').hasClass('checked')?status = 1:status = 0;
	email_field = $('#newsletter_popup').find('input');
	email = email_field.val();
	if(!email || !validateEmail(email))
		email_field.css('border-color', 'red');
	else
	{
		$.ajax({
			type:'POST',
			url: baseDir +'modules/tmnewsletter/tmnewsletter-ajax.php',
			data: 'action=sendemail&email='+email+'&status='+status,
			dataType:"json",
			success:function(response) {
				if (response.success_status)
				{
					displaySuccess(response.success_status);
				}
				else
				{
					displayError(response.error_status);
				}
			}
		});
	}
}

function updateDate()
{
	$('#newsletter_popup.tmnewsletter-autorized .checker > span').hasClass('checked')?status = 1:status = 0;
	$.ajax({
		type: 'POST',
		url: baseDir +'modules/tmnewsletter/tmnewsletter-ajax.php',
		async: true,
		cache: false,
		dataType : "json",
		data: 
		{
			action:'updatedate',
			status: status,
		},
		error: function(XMLHttpRequest, textStatus, errorThrown)
		{
			error = "TECHNICAL ERROR: unable to close form.\n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus;
			alert(error);
		}
	});
}