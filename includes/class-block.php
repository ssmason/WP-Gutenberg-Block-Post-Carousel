<?php
/**
 * Block registration — calls register_block_type() and nothing else.
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
 * Registers the satori-digital/post-carousel block type on init.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */
class Block {

	/**
	 * Registers the block type using block.json metadata.
	 *
	 * @return void
	 */
	public static function register(): void {
		register_block_type(
			SATORI_PC_DIR . 'block.json',
			[
				'render_callback' => [ Renderer::class, 'render' ],
			]
		);
	}
}
