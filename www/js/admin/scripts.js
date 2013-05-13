$(document).ready(function() {

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

	$('form').on('change', '.select2.multiple', function() {
		console.log('multiple select changed');
		var values = this.value.split(',');
		console.log(values);
	});
});
