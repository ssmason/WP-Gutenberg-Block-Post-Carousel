import { useMemo } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

/* Excluded post type slugs — must mirror Sanitizer::EXCLUDED_POST_TYPES */
const EXCLUDED_TYPES = new Set( [
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
] );

/*
 * Returns posts for the editor preview panel.
 *
 * Parameters:
 *  postType - The post type slug to query.
 *  perPage  - Number of posts to fetch.
 *
 * Returns: {Array|null} Array of post objects with embeds, or null while loading.
 */
export function usePostPreview( postType, perPage ) {
	return useSelect(
		( select ) => {
			return select( coreStore ).getEntityRecords( 'postType', postType, {
				per_page: perPage,
				_embed: true,
			} );
		},
		[ postType, perPage ]
	);
}

/*
 * Returns post type options suitable for a SelectControl.
 *
 * Returns: {Array} Array of { label, value } objects, or empty array while loading.
 */
export function usePostTypes() {
	const types = useSelect( ( select ) => {
		return select( coreStore ).getPostTypes( { per_page: -1 } );
	}, [] );

	return useMemo( () => {
		if ( ! types ) {
			return [];
		}
		return types
			.filter(
				( type ) => type.viewable && ! EXCLUDED_TYPES.has( type.slug )
			)
			.map( ( type ) => ( { label: type.name, value: type.slug } ) );
	}, [ types ] );
}
