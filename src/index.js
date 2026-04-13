import './style.scss';
import './editor.scss';

import { registerBlockType } from '@wordpress/blocks';

import blockData from '../block.json';
import Edit from './edit';
import save from './save';

/*
 * Deprecation entry for the original self-closing (no inner blocks) version.
 * Existing block instances in the database saved with save() = null will
 * auto-recover to the current version when opened in the editor.
 */
const deprecated = [
	{
		save() {
			return null;
		},
	},
];

registerBlockType( blockData.name, {
	edit: Edit,
	save,
	deprecated,
} );
