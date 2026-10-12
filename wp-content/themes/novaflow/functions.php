<?php
/**
 * Novaflow functions and definitions.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

define( 'NOVAFLOW_VERSION', '1.0.98' );
define( 'NOVAFLOW_DIR', get_template_directory() );

require_once NOVAFLOW_DIR . '/inc/options.php';
require_once NOVAFLOW_DIR . '/inc/setup.php';
require_once NOVAFLOW_DIR . '/inc/customizer.php';
require_once NOVAFLOW_DIR . '/inc/block-editor.php';
require_once NOVAFLOW_DIR . '/inc/template-tags.php';
require_once NOVAFLOW_DIR . '/inc/dynamic-css.php';
require_once NOVAFLOW_DIR . '/inc/theme-info.php';
