(function($) {

	var $pdf_archive = $('[data-js-pdf-archive]');
	if (! $pdf_archive.length) {
		return; // Return if component isn't on the page
	}

	jQuery(document).ready(function() {

		var nameType = $.fn.dataTable.absoluteOrder('<a href="/">...Go Back</a>');

		$('[data-js-pdf-archive]').DataTable( {
			order: [],
			pageLength: 25,
			autoWidth: false,
			searching: false,
			columnDefs: [
				{ targets: 0, type: nameType }
			],
			language: {

				// search: "Filter collections:"
				search: 'Search:'
			},
			fnDrawCallback: function(oSettings) {
				if (1 == oSettings.iDraw) {
					if (25 > $('table tr').length) {
						$('.dataTables_length,.dataTables_info, .dataTables_paginate, thead').hide();
						$('table').css( { 'border': 'none' } );
					} else {
						$('.dataTables_length,.dataTables_info, .dataTables_paginate, thead').show();
						$('table').css( { 'border': 'none' } );
					}
				}
			}
		} );

	} );


}(jQuery) );

