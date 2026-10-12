<?php
/**
 * WordPress Customizer integration.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Show advertisement controls only while the header advertisement is enabled.
 *
 * @param WP_Customize_Control $control Customizer control instance.
 * @return bool
 */
function novaflow_customizer_header_ad_enabled( $control ) {
	$setting = $control->manager->get_setting( 'novaflow_options[show_header_ad]' );

	return $setting ? (bool) $setting->value() : true;
}

/**
 * Register all theme presentation settings in the native WordPress Customizer.
 *
 * Novaflow keeps its existing single prefixed option array so upgrades from
 * earlier versions retain every saved value while the configuration UI lives
 * entirely in WordPress core's Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function novaflow_customize_register( $wp_customize ) {
	$defaults = novaflow_get_defaults();

	$wp_customize->add_section(
		'novaflow_header_options',
		array(
			'title'       => __( 'Novaflow Header', 'novaflow' ),
			'description' => __( 'Control the visible header areas and the optional header advertisement.', 'novaflow' ),
			'priority'    => 35,
		)
	);

	$header_toggles = array(
		'show_topbar' => array(
			'label'       => __( 'Top information bar', 'novaflow' ),
			'description' => __( 'Display the date, utility menu, and icon links above the logo.', 'novaflow' ),
		),
		'show_ticker' => array(
			'label'       => __( 'Breaking-news ticker', 'novaflow' ),
			'description' => __( 'Display recent posts in the ticker below the primary menu.', 'novaflow' ),
		),
	);

	foreach ( $header_toggles as $key => $labels ) {
		$wp_customize->add_setting(
			"novaflow_options[{$key}]",
			array(
				'type'              => 'option',
				'default'           => $defaults[ $key ],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'novaflow_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			"novaflow_options[{$key}]",
			array(
				'type'        => 'checkbox',
				'section'     => 'novaflow_header_options',
				'label'       => $labels['label'],
				'description' => $labels['description'],
			)
		);
	}

	$shortcut_choices    = array( 0 => __( 'Automatic destination', 'novaflow' ) );
	$shortcut_categories = get_categories( array( 'hide_empty' => false ) );
	if ( ! is_wp_error( $shortcut_categories ) ) {
		foreach ( $shortcut_categories as $category ) {
			$shortcut_choices[ $category->term_id ] = $category->name;
		}
	}
	$shortcut_labels = array(
		'header_inspiration_category' => __( 'Inspiring Ideas category', 'novaflow' ),
		'header_editor_category'      => __( "Editor's selection category", 'novaflow' ),
		'header_trends_category'      => __( 'Trends category', 'novaflow' ),
	);
	foreach ( $shortcut_labels as $key => $label ) {
		$wp_customize->add_setting(
			"novaflow_options[{$key}]",
			array(
				'type'              => 'option',
				'default'           => $defaults[ $key ],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			"novaflow_options[{$key}]",
			array(
				'type'        => 'select',
				'section'     => 'novaflow_header_options',
				'label'       => $label,
				'description' => __( 'Select a category. Automatic uses the posts page configured under Settings > Reading, or the latest-posts section when no separate posts page is configured.', 'novaflow' ),
				'choices'     => $shortcut_choices,
			)
		);
	}

	$wp_customize->add_section(
		'novaflow_header_ad_options',
		array(
			'title'       => __( 'Novaflow Header Advertisement', 'novaflow' ),
			'description' => __( 'Upload a banner here, or leave the image empty to use the Header Banner widget area. Nothing is displayed until the site owner enables and configures this area.', 'novaflow' ),
			'priority'    => 36,
		)
	);

	$wp_customize->add_setting(
		'novaflow_options[show_header_ad]',
		array(
			'type'              => 'option',
			'default'           => $defaults['show_header_ad'],
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'novaflow_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'novaflow_options[show_header_ad]',
		array(
			'type'        => 'checkbox',
			'section'     => 'novaflow_header_ad_options',
			'label'       => __( 'Display header advertisement', 'novaflow' ),
			'description' => __( 'Show the advertisement between the site logo and search form.', 'novaflow' ),
			'priority'    => 10,
		)
	);

	$wp_customize->add_setting(
		'novaflow_options[header_ad_image_id]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_ad_image_id'],
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'novaflow_sanitize_image_id',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'novaflow_options[header_ad_image_id]',
			array(
				'section'         => 'novaflow_header_ad_options',
				'label'           => __( 'Header advertisement image', 'novaflow' ),
				'description'     => __( 'Select, change, or remove the advertisement with the WordPress Media Library. Recommended size: 728 × 90 pixels. Leave it empty to use the Header Banner widget area.', 'novaflow' ),
				'mime_type'       => 'image',
				'priority'        => 20,
				'active_callback' => 'novaflow_customizer_header_ad_enabled',
			)
		)
	);

	$wp_customize->add_setting(
		'novaflow_options[header_ad_url]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_ad_url'],
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'novaflow_sanitize_external_url',
		)
	);
	$wp_customize->add_control(
		'novaflow_options[header_ad_url]',
		array(
			'type'            => 'url',
			'section'         => 'novaflow_header_ad_options',
			'label'           => __( 'Advertisement destination URL', 'novaflow' ),
			'description'     => __( 'Optional. Add a link only when you want the advertisement to open another page.', 'novaflow' ),
			'priority'        => 30,
			'active_callback' => 'novaflow_customizer_header_ad_enabled',
		)
	);

	$wp_customize->add_setting(
		'novaflow_options[header_ad_alt]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_ad_alt'],
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'novaflow_options[header_ad_alt]',
		array(
			'type'            => 'text',
			'section'         => 'novaflow_header_ad_options',
			'label'           => __( 'Advertisement image alternative text', 'novaflow' ),
			'priority'        => 40,
			'active_callback' => 'novaflow_customizer_header_ad_enabled',
		)
	);

	$wp_customize->add_setting(
		'novaflow_options[header_ad_new_tab]',
		array(
			'type'              => 'option',
			'default'           => $defaults['header_ad_new_tab'],
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'novaflow_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'novaflow_options[header_ad_new_tab]',
		array(
			'type'            => 'checkbox',
			'section'         => 'novaflow_header_ad_options',
			'label'           => __( 'Open advertisement link in a new tab', 'novaflow' ),
			'priority'        => 50,
			'active_callback' => 'novaflow_customizer_header_ad_enabled',
		)
	);

	$wp_customize->add_section(
		'novaflow_social_options',
		array(
			'title'       => __( 'Novaflow Social Links', 'novaflow' ),
			'description' => __( 'The default icons link to the official social network homepages. Replace each address with your profile URL, or clear it to hide that icon. External social links use nofollow.', 'novaflow' ),
			'priority'    => 37,
		)
	);

	$social_settings = array(
		'topbar_facebook_url'  => __( 'Facebook URL', 'novaflow' ),
		'topbar_x_url'         => __( 'X URL', 'novaflow' ),
		'topbar_instagram_url' => __( 'Instagram URL', 'novaflow' ),
		'topbar_youtube_url'   => __( 'YouTube URL', 'novaflow' ),
		'topbar_tiktok_url'    => __( 'TikTok URL', 'novaflow' ),
		'topbar_linkedin_url'  => __( 'LinkedIn URL', 'novaflow' ),
		'topbar_pinterest_url' => __( 'Pinterest URL', 'novaflow' ),
	);

	foreach ( $social_settings as $key => $label ) {
		$wp_customize->add_setting(
			"novaflow_options[{$key}]",
			array(
				'type'              => 'option',
				'default'           => $defaults[ $key ],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'novaflow_sanitize_external_url',
			)
		);
		$wp_customize->add_control(
			"novaflow_options[{$key}]",
			array(
				'type'    => 'url',
				'section' => 'novaflow_social_options',
				'label'   => $label,
			)
		);
	}

	$wp_customize->add_section(
		'novaflow_home_options',
		array(
			'title'       => __( 'Novaflow Homepage', 'novaflow' ),
			'description' => __( 'Choose which editorial sections appear when the front page displays the latest posts.', 'novaflow' ),
			'priority'    => 37,
		)
	);

	$home_toggles = array(
		'show_trending' => __( 'Trending-posts section', 'novaflow' ),
		'show_editor'   => __( "Editor's selection section", 'novaflow' ),
	);

	foreach ( $home_toggles as $key => $label ) {
		$wp_customize->add_setting(
			"novaflow_options[{$key}]",
			array(
				'type'              => 'option',
				'default'           => $defaults[ $key ],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'novaflow_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			"novaflow_options[{$key}]",
			array(
				'type'    => 'checkbox',
				'section' => 'novaflow_home_options',
				'label'   => $label,
			)
		);
	}

	$wp_customize->add_section(
		'novaflow_design_options',
		array(
			'title'       => __( 'Novaflow Design', 'novaflow' ),
			'description' => __( 'Adjust layout, typography, and the theme color system.', 'novaflow' ),
			'priority'    => 38,
		)
	);

	$range_settings = array(
		'container_width' => array(
			'label'    => __( 'Maximum content width', 'novaflow' ),
			'min'      => 1080,
			'max'      => 1600,
			'step'     => 10,
			'sanitize' => 'novaflow_sanitize_container_width',
		),
		'card_radius'     => array(
			'label'    => __( 'Card corner radius', 'novaflow' ),
			'min'      => 0,
			'max'      => 20,
			'step'     => 1,
			'sanitize' => 'novaflow_sanitize_card_radius',
		),
		'base_font_size'  => array(
			'label'    => __( 'Base font size', 'novaflow' ),
			'min'      => 14,
			'max'      => 20,
			'step'     => 1,
			'sanitize' => 'novaflow_sanitize_base_font_size',
		),
	);

	foreach ( $range_settings as $key => $args ) {
		$wp_customize->add_setting(
			"novaflow_options[{$key}]",
			array(
				'type'              => 'option',
				'default'           => $defaults[ $key ],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => $args['sanitize'],
			)
		);
		$wp_customize->add_control(
			"novaflow_options[{$key}]",
			array(
				'type'        => 'range',
				'section'     => 'novaflow_design_options',
				'label'       => $args['label'],
				'input_attrs' => array(
					'min'  => $args['min'],
					'max'  => $args['max'],
					'step' => $args['step'],
				),
			)
		);
	}

	$wp_customize->add_setting(
		'novaflow_options[heading_weight]',
		array(
			'type'              => 'option',
			'default'           => $defaults['heading_weight'],
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'novaflow_sanitize_heading_weight',
		)
	);
	$wp_customize->add_control(
		'novaflow_options[heading_weight]',
		array(
			'type'    => 'select',
			'section' => 'novaflow_design_options',
			'label'   => __( 'Heading font weight', 'novaflow' ),
			'choices' => array(
				300 => __( 'Light', 'novaflow' ),
				400 => __( 'Normal', 'novaflow' ),
				500 => __( 'Medium', 'novaflow' ),
			),
		)
	);

	$color_settings = array(
		'accent_color'    => __( 'Primary accent color', 'novaflow' ),
		'secondary_color' => __( 'Secondary accent color', 'novaflow' ),
		'tertiary_color'  => __( 'Tertiary accent color', 'novaflow' ),
		'warning_color'   => __( 'Warning color', 'novaflow' ),
		'ink_color'       => __( 'Text color', 'novaflow' ),
		'surface_color'   => __( 'Card color', 'novaflow' ),
		'page_color'      => __( 'Page background color', 'novaflow' ),
		'dark_color'      => __( 'Dark-area color', 'novaflow' ),
	);

	foreach ( $color_settings as $key => $label ) {
		$wp_customize->add_setting(
			"novaflow_options[{$key}]",
			array(
				'type'              => 'option',
				'default'           => $defaults[ $key ],
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				"novaflow_options[{$key}]",
				array(
					'section' => 'novaflow_design_options',
					'label'   => $label,
				)
			)
		);
	}
}
add_action( 'customize_register', 'novaflow_customize_register' );
