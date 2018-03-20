jQuery(document).ready(function() {

	var sdi_module_url = moduleSDIUrl;
	
	// add tab to menu
	$('body').find('#nav-sidebar .menu, #nav-topbar .menu').append('<li id="maintab-SampleDataInstall" class="maintab"><a class="title" href="'+sdi_module_url+'"><i class="icon-SampleDataInstall"></i><span>'+moduleSDILink+'</span></a></li>');
	
	// show info message (one time only)
	if(SDI_show_message == 1) {
		SDI_popup =  '<div class="modal fade" id="moduleMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
		SDI_popup += '	<div class="modal-dialog">';
		SDI_popup += '		<div class="modal-content">';
		SDI_popup += '			<div class="modal-header">';
		SDI_popup += '				<button type="button" class="close SDI-info-close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">'+SDI_title_close+'</span></button>';
		SDI_popup += '				<h4 class="modal-title" id="myModalLabel">'+moduleSDILink+'</h4>';
		SDI_popup += '			</div>';
		SDI_popup += '			<div class="modal-body">'+SDI_text_install+'</div>';
		SDI_popup += '			<div class="modal-footer">';
		SDI_popup += '				<button type="button" class="btn btn-success SDI-info-go" data-dismiss="modal">'+SDI_title_use+'</button>';
		SDI_popup += '			</div>';
		SDI_popup += '		</div>';
		SDI_popup += '	</div>';
		SDI_popup += '</div>';
		
		$('#content').append(SDI_popup);
		
		$('#moduleMessage').modal({
			show: true
		});
		
		$('#moduleMessage').on('hidden.bs.modal', function () {closePopup()});
		
		$('.SDI-info-go').on('click', function(){
			window.location = sdi_module_url;
		});
	}
});

function closePopup(){
	jQuery.ajax({
		url: SDI_ajax_dir,
		data: 'action=removeMessage',
		type:'POST',
		success:function() {},
		error:function() {}
	});
}