( function () {
	'use strict';

	if ( ! window.wp || ! window.wp.blocks || ! window.wp.blocks.registerBlockBindingsSource ) {
		return;
	}

	const labels = window.novaflowHeadingLabels || {};
	const hasLabel = ( key ) => Object.prototype.hasOwnProperty.call( labels, key );

	window.wp.blocks.registerBlockBindingsSource( {
		name: 'novaflow/section-heading',
		getValues( { bindings } ) {
			const binding = bindings && bindings.content;
			const key = binding && binding.args && binding.args.key;
			return typeof key === 'string' && hasLabel( key ) ? { content: labels[ key ] } : {};
		},
		getFieldsList() {
			return Object.keys( labels ).map( ( key ) => ( {
				label: labels[ key ],
				type: 'string',
				args: { key },
			} ) );
		},
		canUserEditValue() {
			return false;
		},
	} );
}() );
