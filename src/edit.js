import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	ToggleControl,
} from '@wordpress/components';

import { usePostTypes } from './store/selectors';

/*
 * Default inner block template.
 * core/query provides the query loop context that other plugins depend on.
 * core/post-template iterates posts; its children define the card layout.
 * inherit: false prevents the query from following URL pagination context.
 */
const TEMPLATE = [
	[
		'core/query',
		{ query: { postType: 'post', perPage: 3, inherit: false } },
		[
			[
				'core/post-template',
				{},
				[
					[ 'core/post-featured-image', {} ],
					[ 'core/post-title', { level: 3, isLink: true } ],
				],
			],
		],
	],
];

/*
 * Editor component for the Post Carousel block.
 *
 * Parameters:
 *  props               - Block edit props.
 *  props.attributes    - Current block attributes.
 *  props.setAttributes - Attribute update callback.
 *
 * Returns: {Element} The editor UI.
 */
export default function Edit( { attributes, setAttributes } ) {
	const {
		postType,
		postsPerPage,
		autoplayInterval,
		hideDots,
		hideNavButtons,
		hidePauseButton,
	} = attributes;

	const blockProps = useBlockProps( {
		className: 'satori-post-carousel',
		style: { '--spc-slides-per-view': postsPerPage },
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'satori-post-carousel__track' },
		{ template: TEMPLATE, templateLock: false }
	);

	const postTypeOptions = usePostTypes();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Query', 'satori-post-carousel' ) }>
					<SelectControl
						label={ __( 'Post type', 'satori-post-carousel' ) }
						value={ postType }
						options={ postTypeOptions }
						onChange={ ( value ) =>
							setAttributes( { postType: value } )
						}
						__nextHasNoMarginBottom
					/>
					<RangeControl
						label={ __(
							'Slides per view',
							'satori-post-carousel'
						) }
						value={ postsPerPage }
						onChange={ ( value ) =>
							setAttributes( { postsPerPage: value } )
						}
						min={ 1 }
						max={ 20 }
						__nextHasNoMarginBottom
					/>
				</PanelBody>
				<PanelBody title={ __( 'Autoplay', 'satori-post-carousel' ) }>
					<RangeControl
						label={ __(
							'Interval (seconds)',
							'satori-post-carousel'
						) }
						value={ autoplayInterval }
						onChange={ ( value ) =>
							setAttributes( { autoplayInterval: value } )
						}
						min={ 1 }
						max={ 30 }
						__nextHasNoMarginBottom
					/>
				</PanelBody>
				<PanelBody title={ __( 'Controls', 'satori-post-carousel' ) }>
					<ToggleControl
						label={ __(
							'Hide prev / next',
							'satori-post-carousel'
						) }
						checked={ hideNavButtons }
						onChange={ ( value ) =>
							setAttributes( { hideNavButtons: value } )
						}
						__nextHasNoMarginBottom
					/>
					<ToggleControl
						label={ __( 'Hide dots', 'satori-post-carousel' ) }
						checked={ hideDots }
						onChange={ ( value ) =>
							setAttributes( { hideDots: value } )
						}
						__nextHasNoMarginBottom
					/>
					<ToggleControl
						label={ __(
							'Hide pause / play',
							'satori-post-carousel'
						) }
						checked={ hidePauseButton }
						onChange={ ( value ) =>
							setAttributes( { hidePauseButton: value } )
						}
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div { ...innerBlocksProps } />
			</div>
		</>
	);
}
