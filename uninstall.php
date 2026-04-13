<?php
/**
 * Uninstall routine for Satori Post Carousel.
 *
 * @category Plugin
 * @package  SatoriPostCarousel
 * @author   Steve Mason <steve@satori.digital>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://satori.digital
 */

declare(strict_types=1);

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// No database cleanup needed for this plugin.
