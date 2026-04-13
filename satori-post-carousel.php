<?php
/**
 * Plugin Name: Satori Post Carousel
 * Plugin URI:  https://satori.digital
 * Description: An accessible, configurable Gutenberg block carousel for any public post type.
 * Version:     1.0.0
 * Requires at least: 6.4
 * Tested up to: 6.7
 * Requires PHP: 8.2
 * Author:      Steve Mason
 * Author URI:  https://satori.digital
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: satori-post-carousel
 * Domain Path: /languages
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SATORI_PC_VERSION', '1.0.0' );
define( 'SATORI_PC_FILE', __FILE__ );
define( 'SATORI_PC_DIR', plugin_dir_path( __FILE__ ) );
define( 'SATORI_PC_URL', plugin_dir_url( __FILE__ ) );

require_once SATORI_PC_DIR . 'includes/class-autoloader.php';

( new SatoriPostCarousel\Autoloader() )->register();

add_action(
	'plugins_loaded',
	static function () {
		SatoriPostCarousel\Plugin::instance()->boot();
	},
	0
);
