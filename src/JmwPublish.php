<?php
/**
 * Main plugin file.
 *
 * @package JMichaelWard\JmwPublish
 */

namespace JMichaelWard\JmwPublish;

use Exception;
use WebDevStudios\OopsWP\Structure\Plugin\Plugin;
use WebDevStudios\OopsWP\Utility\Hookable;
use WP_Post;

/**
 * Class JmwPublish
 *
 * @package JMichaelWard\JmwPublish
 */
class JmwPublish extends Plugin implements Hookable {
	/**
	 * Run the plugin.
	 *
	 * Note: I'm choosing to override the parent method here instead of setting
	 * up services for the plugin, as for now I'd like to explore exactly what
	 * is needed to handle the publishing flow into an API. This logic will
	 * more than likely get split out later.
	 */
	public function run() {
		parent::run();
		$this->register_hooks();
	}

	/**
	 * Register plugin hooks with WordPress.
	 */
	public function register_hooks() {
		add_action( 'save_post', [ $this, 'publish_to_api' ], 10, 2 );
	}

	/**
	 * Publish post content into an external API.
	 *
	 * @param int     $post_id The post ID of the post.
	 * @param WP_Post $post    The WordPress post object.
	 *
	 * @throws Exception If the post fails to submit to the API.
	 * @return mixed
	 */
	public function publish_to_api( $post_id, $post ) {
		if ( 'publish' !== $post->post_status ) {
			return $post_id;
		}

		$request = wp_remote_post(
			"{$this->get_api_url()}$post_id",
			[ 'body' => [ 'post_data' => json_encode( $post ) ] ] // @codingStandardsIgnoreLine
		);

		if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
			return $post_id;
		}

		/*
		 * @TODO This prevents WordPress from publishing the post and it displays a notification on the edit screen.
		 *      It's not particularly helpful, but it works for now for this initial proof of concept.
		 */
		throw new Exception( 'Failed to post to the API.' );
	}

	/**
	 * Get the URL to the API.
	 *
	 * @TODO I might consider making this a setting, but want to leave the option available of
	 *       using an environment variable for now.
	 *
	 * @return string
	 */
	private function get_api_url() {
		$url = getenv( 'API_URL' );

		return $url ? trailingslashit( $url ) : '';
	}
}
