<?php
/**
 * Section 10: 2-Column Banner Grid #2 (HOME-10)
 * Reference: Demo 43 Section 9 (Top: 4361px, Height: ~274px)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

negar_theme_render_editorial_banner_grid(
	'elementor-element elementor-element-sec10-banners e-flex e-con-boxed e-con e-parent e-lazyloaded my-6',
	'',
	array(
		array(
			'record_id' => 'banners-10-one',
			'widget_id' => '419afcb5',
			'is_lazy'   => true,
		),
		array(
			'record_id' => 'banners-10-two',
			'widget_id' => '54c73a37',
			'is_lazy'   => true,
		),
	)
);
