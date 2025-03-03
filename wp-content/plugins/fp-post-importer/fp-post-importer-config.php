<?php
/**
 * FP_PostImporter Config file.
 *
 * @package fp-postimporter
 */

// basename.
define( 'FP_POST_IMPORTER_PLUGIN', plugin_basename( __FILE__ ) );

// Plugin Name.
define( 'FP_POST_IMPORTER_PLUGIN_NAME', trim( dirname( FP_POST_IMPORTER_PLUGIN ), '/' ) );

// Plugin directory.
define( 'FP_POST_IMPORTER_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . FP_POST_IMPORTER_PLUGIN_NAME );

// Libs directory.
define( 'FP_POST_IMPORTER_LIB', FP_POST_IMPORTER_PLUGIN_DIR . '/libs' );

// Assets dir.
define( 'FP_POST_IMPORTER_ASSETS_DIR', FP_POST_IMPORTER_PLUGIN_DIR . '/assets' );

// Vendor directory.
define( 'AUTOLOAD_PHP', FP_POST_IMPORTER_PLUGIN_DIR . '/vendor/autoload.php' );

// Includes directory.
define( 'FP_POST_IMPORTER_INC', FP_POST_IMPORTER_PLUGIN_DIR . '/includes' );

// Libraries directory.
define( 'FP_POST_IMPORTER_LIBS', FP_POST_IMPORTER_PLUGIN_DIR . '/libs' );

// ACF JSON directory.
define( 'FP_POST_IMPORTER_ACF_JSON', FP_POST_IMPORTER_PLUGIN_DIR . '/acf-json' );

// Plugin URL.
define( 'FP_POST_IMPORTER_PLUGIN_URL', plugins_url( FP_POST_IMPORTER_PLUGIN_NAME ) . '/' );

// Media Path.
define( 'FP_POST_IMPORTER_ASSETS', plugins_url( FP_POST_IMPORTER_PLUGIN_NAME ) . '/assets' );

// Locale.
define( 'FP_POST_IMPORTER_LOCALE', 'fp_pi' );

// Token.
define( 'FP_POST_API_TOKEN_KEY', 'fp_api_token' );

// Global Settings. Can be overwritten with a filter
define( 'FP_POST_IMPORTER_API_CLIENT_CREATE_TERMS', true );
define( 'FP_POST_IMPORTER_API_CLIENT_APPEND_TERMS', true );
define( 'FP_POST_IMPORTER_API_CLIENT_SEARCH_REPLACE', true );

// API Namepsace.
define( 'FP_POST_IMPORTER_API_NAMESPACE', 'fppi' );
define( 'FP_POST_IMPORTER_API_HOST_SETTINGS', '/host/settings' );
define( 'FP_POST_IMPORTER_API_HOST_DELETED_POSTS_IDS', '/host/postids/deleted' );
define( 'FP_POST_IMPORTER_API_HOST_ALL_POSTS_IDS', '/host/postids/all' );
define( 'FP_POST_IMPORTER_API_CLIENT_TEST', '/client/test' );
define( 'FP_POST_IMPORTER_API_CLIENT_IMPORT', '/client/import' );
define( 'FP_POST_IMPORTER_API_CLIENT_LOG', '/client/log' );
define( 'FP_POST_IMPORTER_API_CLIENT_CLEAR_PROCESS_LOCK', '/client/clear-process' );
define( 'FP_POST_IMPORTER_API_CLIENT_CANCEL_PROCESS', '/client/cancel-process');