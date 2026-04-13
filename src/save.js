import { InnerBlocks } from '@wordpress/block-editor';

/*
 * Save component — persists the card template to the database so the PHP
 * render callback can access it via $block->inner_blocks.
 *
 * Returns: {Element} Inner blocks content.
 */
export default function save() {
	return <InnerBlocks.Content />;
}
