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

use JMichaelWard\JmwPublish\JmwPublish;
use Symfony\Component\Dotenv\Dotenv;

$autoload = __DIR__ . '/vendor/autoload.php';

if ( is_readable( $autoload ) ) {
	require $autoload;
}

try {
	add_action(
		'plugins_loaded',
		function() {
			// @TODO Most implementations might check for environment variables from the app. Make this better.
			$env = __DIR__ . '/.env';

			if ( is_readable( $env ) ) {
				$dotenv = new Dotenv( true );
				$dotenv->load( $env );
			}

			( new JmwPublish() )->run();
		}
	);
} catch ( Throwable $e ) {
	// @TODO Handle this differently after scaffolding is finished.
	error_log( $e->getMessage() ); // @codingStandardsIgnoreLine
}
