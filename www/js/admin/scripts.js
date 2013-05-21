$(document).ready(function() {
	var myConsole = { log : function(text) {} };
	var console = window.console || myConsole;

	$('.select2.multiple').select2({
		tags: []
	});

	$('[data-autocomplete]').each(function() {
		console.log($(this).data('autocomplete'));
		var data = $(this).data('autocomplete').split(',');
		console.log(data);
		$(this).select2({
			tags: data
		})
	});

	/** Set menu title according to page title */
	var pageName = $('.page-select').text();
	$('.menu-title').val(pageName);

	$('.menu-form').on('change', '.page-select', function() {
		var selectedText = $(this).find("option:selected").text();
		console.log($(this).find("option:selected").text());
		$('.menu-title').val(selectedText);
	});

	$('form').on('change', '.select2.multiple', function() {
		console.log('multiple select changed');
		var values = this.value.split(',');
		console.log(values);
	});
});
