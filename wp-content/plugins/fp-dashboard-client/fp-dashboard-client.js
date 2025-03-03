(function ($) {

	$( document ).ready(function() {

		var $fp_client_settings = $('#fp_client_settings');
		if (!$fp_client_settings.length) {
			return; // Return if component isn't on the page
		}

		// create query

		$('.create_query').click(function() {
			if ( ! validate() ) return;
			arg_1 = $('.arg_1').val();
			arg_2 = $('.arg_2').val();
			arg_3 = $('.arg_3').val();
			arg_4 = $('.arg_4').val();
			arg_5 = $('.arg_5').val();
			add_query( arg_1, arg_2, arg_3, arg_4, arg_5 );
		})

		$('.save_query').click(function() {
			if ( ! validate( true ) ) return;
			arg_1 = $('.arg_1').val();
			arg_2 = $('.arg_2').val();
			arg_3 = $('.arg_3').val();
			arg_4 = $('.arg_4').val();
			arg_5 = $('.arg_5').val();
			save_query( arg_1, arg_2, arg_3, arg_4, arg_5 );
		})

		// delete query

		$('body').on('click', '#fp_client_settings td.delete', function() {
			jQuery(this).parent().remove();
			create_json_object();
			jQuery('.save_warning').show();
		})

		// edit query

		$('body').on('click', '#fp_client_settings td.edit', function() {
			jQuery('.save_warning').show();
			jQuery(this).parent().addClass('edit');
			edit_query();
			$('.save_query').css('display','inline-block');
		})

		saved = $('[name="custom_queries_option"]').text();
		if ( saved.length ) {
			decoded = JSON.parse(saved);
			for (var i = 0; i < decoded.length; i++) {
				$row = JSON.parse(decoded[i]);
				arg_1 = $row.arg_1;;
				arg_2 = $row.arg_2
				arg_3 = $row.arg_3;
				if (typeof $row.arg_4 != 'undefined') {
					arg_4 = $row.arg_4;
				} else {
					arg_4 = '';
				}
				if (typeof $row.arg_5 != 'undefined') {
					arg_5 = $row.arg_5;
				} else {
					arg_5 = '';
				}
				add_query( arg_1, arg_2, arg_3, arg_4, arg_5 );
			}
		}

	})

	function validate( save=false ) {
		console.log(save);
		errors='';
		if ( '' == $('.arg_1').val() ) {
			errors+='No API variable added\n';
		}
		if ( duplicates( $('.arg_1').val() ) && ! save ) {
			errors+='Variable already used, please use unqiue API variables\n';
		}
		if ( '' == $('.arg_2').val() ) {
			errors+='No Select statement\n';
		}
		if ( '' == $('.arg_3').val() ) {
			errors+='No FROM statement\n';
		}
		if (errors) {
			alert(errors);
		} else {
			jQuery('.save_warning').show();
			return true;
		}
	}

	function duplicates( check ) {
		for (var i = 1; i < $('table.queries tr').length + 1; i++) {
			key = $('table.queries tr:nth-child('+i+') td.key').text();
			if ( check == key) return true;
		}
		return false;
	}

	function add_query( arg_1, arg_2, arg_3, arg_4, arg_5 ) {
		var variables = {};
		variables.arg_1 = arg_1;
		variables.arg_2 = arg_2;
		variables.arg_3 = arg_3;
		td1 = '<td class="key">' + arg_1 + '</td>';
		td3 = '<td class="query">SELECT ' + arg_2;
		td3 += ' FROM ' + arg_3;
		if ( '' != arg_4 ) {
			td3 += ' LEFT JOIN ' + arg_4;
			variables.arg_4 = arg_4;
		}
		if ( '' != arg_5 ) {
			td3 += ' WHERE ' + arg_5;
			variables.arg_5 = arg_5;
		}
		td2 = '<td class="variables">' + JSON.stringify(variables) + '</td>';
		query = '<tr>' + td1 + td2 + td3 + '<td class="delete">delete</td><td class="edit">edit</td></tr>';
		$('#fp_client_settings .queries tbody').append(query);
		create_json_object();
	}

	function edit_query() {
		variables = $('table.queries tr.edit td.variables').text();
		variables = JSON.parse(variables);
		variables.arg_1 = arg_1;
		variables.arg_2 = arg_2;
		variables.arg_3 = arg_3;
		$('#fp_client_settings .arg_1').val(arg_1);
		$('#fp_client_settings .arg_2').val(arg_2);
		$('#fp_client_settings .arg_3').val(arg_3);
		if ( '' != arg_4 ) {
			variables.arg_4 = arg_4;
			$('#fp_client_settings .arg_4').val(arg_4);
		}
		if ( '' != arg_5 ) {
			variables.arg_5 = arg_5;
			$('#fp_client_settings .arg_5').val(arg_5);
		}
	}

	function save_query() {
		variables = $('table.queries tr.edit td.variables').text();
		variables = JSON.parse(variables);
		variables.arg_1 = arg_1;
		variables.arg_2 = arg_2;
		variables.arg_3 = arg_3;
		query = 'SELECT ' + arg_2;
		query += ' FROM ' + arg_3;
		if ( '' != arg_4 ) {
			query += ' LEFT JOIN ' + arg_4;
			variables.arg_4 = arg_4;
		}
		if ( '' != arg_5 ) {
			query += ' WHERE ' + arg_5;
			variables.arg_5 = arg_5;
		}
		$('#fp_client_settings tr.edit .variables').text(JSON.stringify(variables));
		$('#fp_client_settings tr.edit .query').text(query);
		$('tr.edit').removeClass('edit');
		create_json_object();
	}


	function create_json_object() {
		var $json = [];
		for (var i = 1; i < $('table.queries tr').length + 1; i++) {
			key = $('table.queries tr:nth-child('+i+') td.key').text();
			variables = $('table.queries tr:nth-child('+i+') td.variables').text();
			$json.push( variables );
		}
		$json.shift();
		$('[name="custom_queries_option"]').text(JSON.stringify($json));
	}

})(jQuery);
