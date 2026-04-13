<?php
/**
 * WP_Query builder — fetches published posts and returns them as an array.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */

declare(strict_types=1);

namespace SatoriPostCarousel;

use WP_Post;

/**
 * Accepts sanitised query arguments and returns WP_Post objects.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */
class Query {

	/**
	 * Runs a hardened WP_Query and returns the resulting posts.
	 *
	 * Fetches all published posts of the given type. The posts_per_page
	 * attribute controls how many slides are visible at once (layout only),
	 * not how many posts are queried.
	 *
	 * @param array $args Sanitised query arguments from Sanitizer::sanitize().
	 *                    Key used: post_type (string).
	 *
	 * @return WP_Post[] Array of published post objects.
	 */
	public static function get_posts( array $args ): array {
		$query = new \WP_Query(
			[
				'post_type'              => $args['post_type'],
				'posts_per_page'         => -1,
				'post_status'            => 'publish',
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			]
		);

		return $query->posts;
	}
}
