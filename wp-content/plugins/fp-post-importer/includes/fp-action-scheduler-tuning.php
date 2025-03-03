<?php
defined( 'ABSPATH' ) or die( 'Access forbidden!' );
/** 
 * Action scheduler claims a batch of actions to process in each request. It keeps the batch
 * fairly small (by default, 25) in order to prevent errors, like memory exhaustion.
 *
 * This method increases it so that more actions are processed in each queue, which speeds up the
 * overall queue processing time due to latency in requests and the minimum 1 minute between each
 * queue being processed.
 *
 * For more details, see: https://actionscheduler.org/perf/#increasing-batch-size
 */
function fppi_increase_queue_batch_size( $batch_size ) {
	return $batch_size * 1.5;
}
// add_filter( 'action_scheduler_queue_runner_batch_size', 'fppi_increase_queue_batch_size' );

/**
 * Action scheduler reset actions claimed for more than 5 minutes. Because we're increasing the batch size, we
 * also want to increase the amount of time given to queues before resseting claimed actions.
 */
function fppi_increase_timeout( $timeout ) {
	return $timeout * 2;
}
// add_filter( 'action_scheduler_timeout_period', 'fppi_increase_timeout' );
// add_filter( 'action_scheduler_failure_period', 'fppi_increase_timeout' );

/** 
 * Action scheduler processes queues of actions in parallel to speed up the processing of large numbers
 * If each queue takes a long time, this will result in multiple PHP processes being used to process actions,
 * which can prevent PHP processes being available to serve requests from visitors. This is why it defaults to
 * only 5. However, on high volume sites, this can be increased to speed up the processing time for actions.
 *
 * This method hextuples the default so that more queues can be processed concurrently. Use with caution as doing
 * this can take down your site completely depending on your PHP configuration.
 *
 * For more details, see: https://actionscheduler.org/perf/#increasing-concurrent-batches
 */
function fppi_increase_concurrent_batches( $concurrent_batches ) {
	return $concurrent_batches * 2;
}
//add_filter( 'action_scheduler_queue_runner_concurrent_batches', 'fppi_increase_concurrent_batches' );

/**
 * Action scheduler initiates one queue runner every time the 'action_scheduler_run_queue' action is triggered.
 *
 * Because this action is only triggered at most once every minute, that means it would take 30 minutes to spin
 * up 30 queues. To handle high volume sites with powerful servers, we want to initiate additional queue runners
 * whenever the 'action_scheduler_run_queue' is run, so we'll kick off secure requests to our server to do that.
 */
function fppi_request_additional_runners() {

	// allow self-signed SSL certificates
	add_filter( 'https_local_ssl_verify', '__return_false', 100 );

	for ( $i = 0; $i < 2; $i++ ) {
		$response = wp_remote_post( admin_url( 'admin-ajax.php' ), array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => false,
			'headers'     => array(),
			'body'        => array(
				'action'     => 'fppi_create_additional_runners',
				'instance'   => $i,
				'fppi_nonce' => wp_create_nonce( 'fppi_additional_runner_' . $i ),
			),
			'cookies'     => array(),
		) );
	}
}
//add_action( 'action_scheduler_run_queue', 'fppi_request_additional_runners', 0 );

/**
 * Handle requests initiated by fppi_request_additional_runners() and start a queue runner if the request is valid.
 */
function fppi_create_additional_runners() {

	if ( isset( $_POST['fppi_nonce'] ) && isset( $_POST['instance'] ) && wp_verify_nonce( $_POST['fppi_nonce'], 'fppi_additional_runner_' . $_POST['instance'] ) ) {
		ActionScheduler_QueueRunner::instance()->run();
	}

	exit();
}
//add_action( 'wp_ajax_nopriv_fppi_create_additional_runners', 'fppi_create_additional_runners', 0 );

/**
 * Action Scheduler provides a default maximum of 30 seconds in which to process actions. Increase this to 120
 * seconds for hosts like Pantheon which support such a long time limit, or if you know your PHP and Apache, Nginx
 * or other web server configs support a longer time limit.
 *
 * Note, WP Engine only supports a maximum of 60 seconds - if using WP Engine, this will need to be decreased to 60.
 */
function fppi_increase_time_limit() {
	// just shy of 60
	return 58;
}
add_filter( 'action_scheduler_queue_runner_time_limit', 'fppi_increase_time_limit' );

/**
 * 
 * Completed scheduled actions are safe to delete if you wish. Action Scheduler will automatically remove them after 30 days. 
 * If you’d like to decrease that time window, the action_scheduler_retention_period filter is available. 
 * We’re also considering reducing this value by default.
 * Default is 30 days
 */
function fppi_reduce_action_scheduler_retention() {
	// One day
    return DAY_IN_SECONDS;
}
add_filter( 'action_scheduler_retention_period', 'fppi_reduce_action_scheduler_retention' );