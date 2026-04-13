<?php
/**
 * PSR-4-style autoloader for the SatoriPostCarousel namespace.
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
 * Maps SatoriPostCarousel class names to their file paths under includes/.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */
class Autoloader {

	/**
	 * Registers this autoloader with the SPL autoload stack.
	 *
	 * @return void
	 */
	public function register(): void {
		spl_autoload_register( [ $this, '_load' ] );
	}

	/**
	 * Loads a class file if it belongs to the SatoriPostCarousel namespace.
	 *
	 * @param string $class Fully-qualified class name.
	 *
	 * @return void
	 */
	private function _load( string $class ): void {
		$prefix = 'SatoriPostCarousel\\';
		$length = strlen( $prefix );

		if ( strncmp( $prefix, $class, $length ) !== 0 ) {
			return;
		}

		$relative = substr( $class, $length );
		$filename = 'class-' . strtolower(
			str_replace( '_', '-', $relative )
		) . '.php';

		$file = SATORI_PC_DIR . 'includes/' . $filename;

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
}
