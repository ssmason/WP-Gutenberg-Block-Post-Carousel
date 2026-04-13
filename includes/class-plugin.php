<?php
/**
 * Singleton bootstrap — wires all hooks; owns no business logic.
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
 * Plugin singleton. Boots on plugins_loaded, wires init hooks.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */
class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $_instance = null;

	/**
	 * Returns (and creates if needed) the singleton instance.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Private constructor — use instance() instead.
	 */
	private function __construct() {
	}

	/**
	 * Wires all init-time hooks.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'init', [ static::class, 'load_textdomain' ], 5 );
		add_action( 'init', [ Block::class, 'register' ], 20 );
	}

	/**
	 * Loads the plugin text domain for translations.
	 *
	 * @return void
	 */
	public static function load_textdomain(): void {
		load_plugin_textdomain(
			'satori-post-carousel',
			false,
			dirname( plugin_basename( SATORI_PC_FILE ) ) . '/languages'
		);
	}
}
