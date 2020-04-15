<?php
/**
 * Plugin name: JMW Publish
 * Description: Publish WordPress content to external APIs.
 * Author: J. Michael Ward
 * Author URI: https://jmichaelward.com
 * License: Proprietary
 * Text Domain: jmw-publish
 *
 * @package JMichaelWard\JmwPublish
 */

$autoload = __DIR__ . '/vendor/autoload.php';

if ( is_readable( $autoload ) ) {
	require $autoload;
}

try {
	add_action(
		'plugins_loaded',
		function() {
			( new \JMichaelWard\JmwPublish\JmwPublish() )->run();
		}
	);
} catch ( Throwable $e ) {
	// @TODO Handle this differently after scaffolding is finished.
	error_log( $e->getMessage() ); // @codingStandardsIgnoreLine
}


