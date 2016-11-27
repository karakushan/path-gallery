jQuery(document).ready(function($) {
	// открываем файл-менеджер
	$('[data-pg-action="open-filemanager"]').on('click', function(event) {
		event.preventDefault();
		$($(this).attr('href')).toggle(200);
		
	});
	// добавлем путь в поле "путь к папке"
	$('#pg-filemanager').on('click', '[data-pg-action="add-path"]', function(event) {
		event.preventDefault();
		$('#gallery_path').val($(this).data('pg-path'));
	});

	// получаем вложенные папки по аякс
	$('#pg-filemanager').on('click', '[data-pg-action="get-directories"]', function(event) {
		event.preventDefault();
		var parent=$(this).parent();
		var site_path=$(this).attr('href');
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {action: 'pg_files_list',site_path:site_path},
		})
		.done(function(json) {
			json=$.parseJSON(json);
			var ul_length=parent.find('>ul').length;
			if (ul_length<1) { parent.append(json.files_list); }
		});
	});

});