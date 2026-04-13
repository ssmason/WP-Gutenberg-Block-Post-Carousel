<?php
/**
 * Render callback — accepts validated data and returns the carousel HTML.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */

declare(strict_types=1);

namespace SatoriPostCarousel;

use WP_Block;

/**
 * Builds the carousel HTML string from sanitised attributes and queried posts.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */
class Renderer {

	/**
	 * Block render callback.
	 *
	 * Each post is rendered by re-instantiating the block's inner blocks with
	 * per-post context so that core blocks such as core/post-title and
	 * core/post-featured-image resolve their content correctly.
	 *
	 * Returns an empty string when no posts are found or when no inner blocks
	 * (card template) have been configured.
	 *
	 * @param array    $raw_attributes Raw block attributes from the editor.
	 * @param string   $content        Unused — inner blocks are rendered directly.
	 * @param WP_Block $block          Block instance (provides inner_blocks).
	 *
	 * @return string Rendered HTML string, or empty string.
	 */
	public static function render(
		array $raw_attributes,
		string $content,
		WP_Block $block
	): string {
		unset( $content );

		$attrs = Sanitizer::sanitize( $raw_attributes );
		$posts = Query::get_posts( $attrs );

		if ( empty( $posts ) ) {
			return '';
		}

		$slides_per_view = $attrs['posts_per_page'];
		$wrapper         = get_block_wrapper_attributes(
			[
				'class'                  => 'satori-post-carousel',
				'role'                   => 'region',
				'aria-roledescription'   => 'carousel',
				'aria-label'             => esc_attr__(
					'Posts carousel',
					'satori-post-carousel'
				),
				'data-slides-per-view'   => (string) $slides_per_view,
				'data-autoplay-interval' => (string) $attrs['autoplay_interval'],
				'style'                  => '--spc-slides-per-view:'
					. $slides_per_view . ';',
			]
		);
		$carousel_id     = wp_unique_id( 'satori-carousel-' );
		$track_id        = esc_attr( $carousel_id . '-track' );
		$total           = count( $posts );
		$nav_label       = esc_attr__(
			'Carousel navigation',
			'satori-post-carousel'
		);
		$prev_label      = esc_attr__( 'Previous slide', 'satori-post-carousel' );
		$next_label      = esc_attr__( 'Next slide', 'satori-post-carousel' );
		$pause_label     = esc_attr__( 'Pause carousel', 'satori-post-carousel' );
		$play_label      = esc_attr__( 'Play carousel', 'satori-post-carousel' );

		$slides = self::_build_slides( $posts, $total, $attrs, $block );

		wp_reset_postdata();

		ob_start();
        // phpcs:disable PEAR.WhiteSpace.ScopeIndent
        // phpcs:disable Generic.Files.LineLength
		?>
<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div
		class="satori-post-carousel__track"
		id="<?php echo $track_id; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
		aria-live="polite"
	>
		<?php foreach ( $slides as $slide ) : ?>
		<article
			class="satori-post-carousel__slide<?php echo $slide['is_first'] ? ' is-active' : ''; ?>"
			role="group"
			aria-roledescription="slide"
			aria-label="<?php echo $slide['label']; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
			<?php echo ! $slide['is_visible'] ? 'hidden' : ''; ?>
		>
			<?php echo $slide['content']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</article>
		<?php endforeach; ?>
	</div>

		<?php if ( $total > 1 && ( ! $attrs['hide_nav_buttons'] || ! $attrs['hide_dots'] || ! $attrs['hide_pause_button'] ) ) : ?>
	<nav
		class="satori-post-carousel__nav"
		aria-label="<?php echo $nav_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
	>
			<?php if ( ! $attrs['hide_nav_buttons'] ) : ?>
		<button
			class="satori-post-carousel__btn satori-post-carousel__btn--prev"
			aria-controls="<?php echo $track_id; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
			aria-label="<?php echo $prev_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
		>
			<svg aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 16 16">
				<path d="M10 3L5 8l5 5" stroke="currentcolor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</button>
		<?php endif; ?>
			<?php if ( ! $attrs['hide_dots'] ) : ?>
		<div
			class="satori-post-carousel__dots"
			role="tablist"
			aria-label="<?php echo $nav_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
		>
				<?php foreach ( $slides as $i => $slide ) : ?>
					<?php
					// translators: %d: slide number.
					$dot_label = esc_attr( sprintf( __( 'Slide %d', 'satori-post-carousel' ), $i + 1 ) );
					?>
			<button
				class="satori-post-carousel__dot<?php echo $slide['is_first'] ? ' is-active' : ''; ?>"
				role="tab"
				aria-selected="<?php echo $slide['is_first'] ? 'true' : 'false'; ?>"
				aria-controls="<?php echo $track_id; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
				aria-label="<?php echo $dot_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
				data-index="<?php echo esc_attr( (string) $i ); ?>"
			></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
			<?php if ( ! $attrs['hide_nav_buttons'] ) : ?>
		<button
			class="satori-post-carousel__btn satori-post-carousel__btn--next"
			aria-controls="<?php echo $track_id; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
			aria-label="<?php echo $next_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
		>
			<svg aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 16 16">
				<path d="M6 3l5 5-5 5" stroke="currentcolor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</button>
		<?php endif; ?>
			<?php if ( ! $attrs['hide_pause_button'] ) : ?>
		<button
			class="satori-post-carousel__btn satori-post-carousel__btn--pause"
			aria-pressed="false"
			aria-label="<?php echo $pause_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
			data-label-pause="<?php echo $pause_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
			data-label-play="<?php echo $play_label; // phpcs:ignore WordPress.Security.EscapeOutput ?>"
		>
			<svg class="icon-pause" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 16 16">
				<rect x="4" y="3" width="3" height="10" fill="currentcolor"/>
				<rect x="9" y="3" width="3" height="10" fill="currentcolor"/>
			</svg>
			<svg class="icon-play" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 16 16">
				<path d="M5 3l8 5-8 5V3z" fill="currentcolor"/>
			</svg>
		</button>
		<?php endif; ?>
	</nav>
	<?php endif; ?>
</div>
		<?php
        // phpcs:enable PEAR.WhiteSpace.ScopeIndent
        // phpcs:enable Generic.Files.LineLength
		return (string) ob_get_clean();
	}

	/**
	 * Returns the card template blocks from inside a core/query > core/post-template
	 * structure, falling back to direct inner blocks for blocks saved with the
	 * legacy layout (no core/query wrapper).
	 *
	 * @param WP_Block $block The carousel block instance.
	 *
	 * @return WP_Block[] Flat array of template blocks.
	 */
	private static function _get_template_blocks( WP_Block $block ): array {
		foreach ( $block->inner_blocks as $inner ) {
			if ( 'core/query' === $inner->name ) {
				foreach ( $inner->inner_blocks as $query_child ) {
					if ( 'core/post-template' === $query_child->name ) {
						$tpl = [];
						foreach ( $query_child->inner_blocks as $tpl_block ) {
							$tpl[] = $tpl_block;
						}
						return $tpl;
					}
				}
			}
		}

		// Fallback: blocks saved before the core/query wrapper was introduced.
		$tpl = [];
		foreach ( $block->inner_blocks as $inner ) {
			$tpl[] = $inner;
		}
		return $tpl;
	}

	/**
	 * Renders each inner block with per-post context and returns slide data.
	 *
	 * Context-aware core blocks (core/post-title, core/post-featured-image,
	 * core/post-excerpt) receive the correct postId and postType for each post
	 * so they resolve their content at render time.
	 *
	 * Each element has keys: label (string), is_first (bool),
	 * is_visible (bool), content (string).
	 *
	 * @param \WP_Post[] $posts Array of post objects.
	 * @param int        $total Total number of posts.
	 * @param array      $attrs Sanitised block attributes.
	 * @param WP_Block   $block Block instance whose inner_blocks are the template.
	 *
	 * @return array Indexed array of slide data arrays.
	 */
	private static function _build_slides(
		array $posts,
		int $total,
		array $attrs,
		WP_Block $block
	): array {
		$slides          = [];
		$template_blocks = self::_get_template_blocks( $block );

		foreach ( $posts as $index => $post ) {
			$label = esc_attr(
				sprintf(
					/* translators: 1: current slide number, 2: total slides */
					__( 'Slide %1$d of %2$d', 'satori-post-carousel' ),
					$index + 1,
					$total
				)
			);

			$content = '';

			if ( count( $template_blocks ) > 0 ) {
				/*
				 * The theme blanks all titles via a the_title filter.
				 * Add a high-priority counter-filter scoped to this post's ID
				 * so core/post-title resolves the correct title, then remove it.
				 */
				$title_fix = function ( string $t, int $id ) use ( $post ): string {
					return (int) $id === (int) $post->ID ? $post->post_title : $t;
				};
				add_filter( 'the_title', $title_fix, 999, 2 );

				foreach ( $template_blocks as $inner_block ) {
					$content .= ( new WP_Block(
						$inner_block->parsed_block,
						[
							'postType' => $post->post_type,
							'postId'   => $post->ID,
						]
					) )->render();
				}

				remove_filter( 'the_title', $title_fix, 999 );
			} else {
				$thumbnail = (string) get_the_post_thumbnail(
					$post,
					'large',
					[ 'class' => 'satori-post-carousel__image' ]
				);
				if ( $thumbnail ) {
					$content .= '<div class="satori-post-carousel__image-wrap">'
						. $thumbnail . '</div>';
				}
				$content .= '<div class="satori-post-carousel__content">'
					. '<h3 class="satori-post-carousel__title">'
					. esc_html( $post->post_title )
					. '</h3></div>';
			}

			$slides[] = [
				'label'      => $label,
				'is_first'   => 0 === $index,
				'is_visible' => $index < $attrs['posts_per_page'],
				'content'    => $content,
			];
		}

		return $slides;
	}
}
