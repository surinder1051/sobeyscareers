(function($) {

    var timerId;
    var fetchInterval = 3000;
    var $results_log;
    var $api_options_form;
    var $progress_bar;

    $(document).ready(function() {
        // Hide/Show Client vs. Host options.
        hideShowClientOptions();

        // Ensure we have required JS vars.
        if (typeof fppi === 'undefined') {
            console.warn("Missing fppi localized vars");
            return;
        }
        if (typeof wpApiSettings === 'undefined') {
            console.warn("Missing WP-API localized vars - ensure wp-api is enqueued");
            return;
        }

		// Client 
		setDefaultManualType();

        setDefaultsEl();
        // Event handlers.
        bindEvents();
        // If the importer is running already in the background i.e. pending jobs
        if (fppi.client.is_running) {
            triggerRunningImportDefaults();
        }

    });
	

	/**
	 * Show/hide the display of client manual sync fields by type select 
	 */
	function setDefaultManualType(){
		if ( $('select#client-manual-sync-type').length ) {
			var select = $('#client-manual-sync-type');
			var modifiedDate = $('#client-modified-date');
			var postIds = $('#client-import-by-post-ids');
			select.on('change', function(){
				// 'date' or 'post_id'
				type = select.val();
				
				if (type == 'post_id') {
					postIds.show();
					modifiedDate.hide();
				} else {
					modifiedDate.show();
					postIds.hide();
					postIds.find('#fppi_post_ids').val('');
				}
			}).trigger('change');
		}
	}

    /**
     * Set up element vars.
     */
    function setDefaultsEl() {
        $results_log = $('#triggered-import-results-log');
        $api_options_form = $('form#get_posts_options');
        $progress_bar = $('.fppi-progress-bar');
    }

    /**
     * Bind to events.
     */
    function bindEvents() {
        $('.test-connection-btn').on('click', testConnection);
        $('.trigger-brightcove-import-btn').on('click', triggerImport);
        $('.trigger-brightcove-clear-process-btn').on('click', clearProcessLock);
        $('.generate-token-btn').on('click', generateToken);
    }

    /**
     * Generage a random token, used for host settings.
     * @param event e 
     */
    function generateToken(e) {
        var random_token = $('#fppi_host_token_random').val();
        $('#fppi_host_token').val(random_token);
    }
    
    /**
     * Handler to test our client/host connection.
     * @param e 
     */
    function testConnection(e) {
        var hostURL = $('#fppi_host_url').val();
        if (hostURL == '') {
            alert('Missing Host URL');
            return;
        }

        var btn = $(this);
        var buttonText = btn.text();
        btn.prop('disabled', true);
        btn.text('Connecting...');
        var postData = { host: hostURL };
        $.ajax({
                url: fppi.client.endpoints.test,
                method: 'POST',
                beforeSend: function(xhr){
                xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
                },
                data: postData
            }).done(function(response){
                $('.connection-status').html("Fetched Settings. Connected.");
                $('#submit').click();
                //hideShowClientOptionsOn();
            }).fail(function(response){
                console.log( response );
                $('.connection-status').html("Failed.");
            }).always(function(){
                btn.prop('disabled', false);
                btn.text( buttonText );
        });
    }

    /**
     * Handler to test our client/host connection.
     * @param e 
     */
    function clearProcessLock(e) {
        var btn = $(this);
        var buttonText = btn.text();
        btn.prop('disabled', true);
        btn.text('Clearing...');
        $.ajax({
            url: fppi.client.endpoints.clear,
            method: 'GET',
            beforeSend: function(xhr){
            xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
            }
        }).done(function(response){
            console.log('Cleared process lock.');
        }).fail(function(response){
            console.log( response );
            alert('Failed to clear process lock.');
        }).always(function(){
            btn.prop('disabled', false);
            btn.text( 'Cleared' );
        });
    }

    /**
     * Reset the import button back to defaults.
     */
    function resetImportButton() {
        var btn = $('.trigger-brightcove-import-btn');
        btn.addClass('button-primary');
        btn.removeClass('button-secondary');
        var default_text = btn.data('default-text');
        btn.text( default_text );
        btn.off('click', cancelRequest);
        btn.on('click', triggerImport);
    }

    /**
     * Set the import button to `running` mode.
     */
    function setImportButtonRunning() {
        var btn = $('.trigger-brightcove-import-btn');
        // Stop listening for more events here until this is done.
        btn.removeClass('button-primary');
        btn.addClass('button-secondary');
        btn.text('Stop Import');
        btn.on('click', cancelRequest);
        btn.off('click', triggerImport);
    }

    /**
     * Callback to initiate the cancel request.
     */
    function cancelRequest() {
        updateStatusText('Cancelling...');
        $.ajax({
            url: fppi.client.endpoints.cancel,
            method: 'GET',
            beforeSend: function(xhr){
            xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
            }
        }).done(function(response){
            updateStatusText('Canceled');
        }).fail(function(response){
            updateStatusText('Cancel request failed');
        }).always(function() {
            stopRunningImportDefaults();
        });
    }

    function updateProgressBar( value ) {
        $progress_bar.find('.progress-value').html(value);
        $progress_bar.find('.progress-bar').width(value+"%");
        if (value === 100) {
            $progress_bar.find('.progress-bar').removeClass('progress-bar-striped'); 
        } else {
            if ( ! $progress_bar.find('.progress-bar').hasClass('progress-bar-striped') ) {
                $progress_bar.find('.progress-bar').addClass('progress-bar-striped')
            }
        }
    }

    /**
     * Set the importer to running mode, update the log and scroll the log window.
     */
    function triggerRunningImportDefaults() {
        setImportButtonRunning();
        updateStatusText('Running import...');
        $results_log.html("");
        $results_log.animate({ scrollTop: 0 });
        $results_log.css({'backgroundColor': 'white'});
        $progress_bar.slideDown();
        updateProgressBar(0);
		/**
		 * Fetch the importer log
		 * Recursively request the log depending on the length of time the server responds, with, increasing delay if the server is busy
		 * Performs garbage collection via
		 */
		timerId = setTimeout( function fetchLog() {
			$.ajax({
				url: fppi.client.endpoints.log,
				method: 'GET',
				beforeSend: function(xhr){
				// console.log('fetching log...')
				xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
				}
			}).done(function(response){
				// console.log('log fetched!')
				var log = response.data.log;
				var percent = response.data.percent;
				var isComplete = (/Finished\s+\-\s+Total\s+Time/gi).test(log);
				if (isComplete) {
					stopRunningImportDefaults();
					updateProgressBar(100);
				} else {
					updateProgressBar(percent);
					// Restart timer
					timerId = setTimeout(fetchLog, fetchInterval);
				}

				$results_log.html(log);
				
				if ( ! $('#disable_log_tail').is(':checked') ) {
					$results_log.animate({ scrollTop: $results_log[0].scrollHeight+"px" });
				}

			}).fail(function(response){
				// Clear the log display
				$results_log.html("");
				// explain what's happening
				$results_log.html("An error occured trying to fetch the log. Retrying...");
				
				// double the delay to reduce server load
				fetchInterval *= 2;
				console.log('Log fetch failed. Delay is now ' + fetchInterval);
				// Restart timer
				timerId = setTimeout(fetchLog, fetchInterval);				
			});
		}, fetchInterval);
    }

    /**
     * Disable the import running mode. 
     */
    function stopRunningImportDefaults() {
        resetImportButton();
        clearTimeout(timerId);
        updateStatusText('Import Complete');
        //$progress_bar.slideUp();
    }

    /**
     * Update the status text.
     * @param {string} msg 
     */
    function updateStatusText(msg) {
        $('#fppi-status-text').html(msg);
    }

    /**
     * Callback to initiate an import.
     * @param event e 
     */
    function triggerImport(e) {
        var form_options = $api_options_form.serializeArray();
        var should_download = $('#download_attachments').is(":checked") == true ? 'true' : 'false';
        form_options.push( {'name': 'download_attachments', 'value': should_download } );
        var postData = form_options;
        // Running
        triggerRunningImportDefaults();

        $.ajax({
            url: fppi.client.endpoints.import,
            method: 'POST',
            beforeSend: function(xhr){
         	   xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
            },
            data: postData
        }).done(function(response, textStatus, jqXHR){
			updateProgressBar(100);
            //$('.fppi_start_date').val( response.data );
        }).fail(function(response){
			updateProgressBar(0);
			if ( response.status == 400 ) {
        	    updateStatusText('Failed with error: '+ response.status + ' ' + response.responseJSON.data);
			} else {
				updateStatusText('Failed with error: '+ response.status);
			}
        });
    }

    function hideShowClientOptionsOn() {
        var type = $("input[name='fppi_type']:checked").val();
        if ( type == 'host' ) {
            $('.client-setting-on').hide();
            $('.host-setting-on').show();
        }

        if ( type == 'client' ) {
            $('.host-setting-on').hide();
            $('.client-setting-on').show();
        }
    }

    function hideShowClientOptions() {
        var type = $("input[name='fppi_type']:checked").val();
        if ( type == 'host' ) {
            $('.client-setting').hide();
            $('.host-setting').show();
        }

        if ( type == 'client' ) {
            $('.host-setting').hide();
            $('.client-setting').show();
        }
    }

})(jQuery);