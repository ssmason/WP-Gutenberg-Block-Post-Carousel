<?php
/**
 * Attribute sanitisation — validates all raw block attributes.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */

declare(strict_types=1);

namespace SatoriPostCarousel;

/**
 * Validates and normalises raw block attributes into typed values.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */
class Sanitizer {

	/**
	 * Maximum posts visible per view.
	 *
	 * @var int
	 */
	const MAX_POSTS = 20;

	/**
	 * Maximum autoplay interval in seconds.
	 *
	 * @var int
	 */
	const MAX_INTERVAL = 30;

	/**
	 * Post type slugs that must never be queried by this block.
	 *
	 * @var string[]
	 */
	const EXCLUDED_POST_TYPES = [
		'attachment',
		'revision',
		'nav_menu_item',
		'custom_css',
		'customize_changeset',
		'oembed_cache',
		'user_request',
		'wp_block',
		'wp_template',
		'wp_template_part',
		'wp_global_styles',
		'wp_navigation',
		'wp_font_face',
		'wp_font_family',
	];

	/**
	 * Validates raw block attributes and returns a typed array.
	 *
	 * @param array $raw Raw attributes from the block editor.
	 *
	 * @return array{
	 *     post_type: string,
	 *     posts_per_page: int,
	 *     autoplay_interval: int,
	 *     show_dots: bool,
	 *     show_nav_buttons: bool,
	 *     show_pause_button: bool,
	 * }
	 */
	public static function sanitize( array $raw ): array {
		return [
			'post_type'         => self::_sanitize_post_type(
				$raw['postType'] ?? 'post'
			),
			'posts_per_page'    => self::_sanitize_posts_per_page(
				$raw['postsPerPage'] ?? 3
			),
			'autoplay_interval' => self::_sanitize_autoplay_interval(
				$raw['autoplayInterval'] ?? 5
			),
			'show_dots'         => (bool) ( $raw['showDots'] ?? true ),
			'show_nav_buttons'  => (bool) ( $raw['showNavButtons'] ?? true ),
			'show_pause_button' => (bool) ( $raw['showPauseButton'] ?? true ),
		];
	}

	/**
	 * Sanitises a post type value.
	 *
	 * Falls back to 'post' if the value is excluded or does not exist.
	 *
	 * @param mixed $value Raw post type value.
	 *
	 * @return string Valid, registered post type slug.
	 */
	private static function _sanitize_post_type( mixed $value ): string {
		$sanitized = sanitize_key( (string) $value );

		if ( in_array( $sanitized, self::EXCLUDED_POST_TYPES, true ) ) {
			return 'post';
		}

		if ( ! post_type_exists( $sanitized ) ) {
			return 'post';
		}

		return $sanitized;
	}

	/**
	 * Sanitises a posts-per-page value, clamped between 1 and MAX_POSTS.
	 *
	 * @param mixed $value Raw posts-per-page value.
	 *
	 * @return int Clamped integer between 1 and MAX_POSTS.
	 */
	private static function _sanitize_posts_per_page( mixed $value ): int {
		return max( 1, min( self::MAX_POSTS, absint( $value ) ) );
	}

	/**
	 * Sanitises an autoplay interval value, clamped between 1 and MAX_INTERVAL.
	 *
	 * @param mixed $value Raw autoplay interval in seconds.
	 *
	 * @return int Clamped integer between 1 and MAX_INTERVAL.
	 */
	private static function _sanitize_autoplay_interval( mixed $value ): int {
		return max( 1, min( self::MAX_INTERVAL, absint( $value ) ) );
	}
}
