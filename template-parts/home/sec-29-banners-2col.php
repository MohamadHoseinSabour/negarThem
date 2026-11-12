<?php
/**
 * Section 29: 2-Column Banner Grid #6 (HOME-29)
 * Reference: Demo 43 Section 28 (Top: 14243px, Height: ~274px)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

negar_theme_render_editorial_banner_grid(
	'elementor-element elementor-element-sec29-banners e-flex e-con-boxed e-con e-parent e-lazyloaded my-6',
	'',
	array(
		array(
			'record_id' => 'banners-29-one',
			'widget_id' => '572e2709',
			'is_lazy'   => true,
		),
		array(
			'record_id' => 'banners-29-two',
			'widget_id' => '1d9f3c87',
			'is_lazy'   => true,
		),
	)
);
