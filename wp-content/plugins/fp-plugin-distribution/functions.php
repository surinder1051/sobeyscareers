<?php

if ( ! function_exists('fp_dist_log') ) :

function fp_dist_log( $message = '', $level = 'update' ) {
	$fp_logger = FP_Plugin_Distribution_Logger::getInstance();
	return $fp_logger->write_to_log( $message, $level );
}

endif;

if ( ! function_exists('fp_comp_dist_log') ) :

	function fp_comp_dist_log( $message = '', $level = 'debug' ) {
		$fp_logger = FP_Plugin_Distribution_Logger::getInstance();
		if ((defined('WP_CLI') && WP_CLI)) {
			if ($level === 'debug') {
				WP_CLI::debug($message);
			}
			else if ($level === 'error') {
				WP_CLI::warning($message);
			} else if ($level === 'update') {
				WP_CLI::line($message);
			} else {
				WP_CLI::line($message);
			}
		}
		return $fp_logger->write_to_log( $message, $level );
	}
	
endif;

if ( ! function_exists('fp_dist_log_close') ) :

function fp_dist_log_close( $message = '', $level = 'update' ) {
	$fp_logger = FP_Plugin_Distribution_Logger::getInstance();
	return $fp_logger->close_log();
}

endif;

if ( ! function_exists('fp_dist_log_write_out') ) :

function fp_dist_log_write_out( $message = '', $level = 'update' ) {
	$fp_logger = FP_Plugin_Distribution_Logger::getInstance();
	return $fp_logger->write_to_log_file();
}

endif;