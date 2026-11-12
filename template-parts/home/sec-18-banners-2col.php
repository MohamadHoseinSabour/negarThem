<?php
/**
 * Section 18: 2-Column Banner Grid #4 (HOME-18)
 * Reference: Demo 43 Section 17 (Top: 8510px, Height: ~274px)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

negar_theme_render_editorial_banner_grid(
	'elementor-element elementor-element-sec18-banners e-flex e-con-boxed e-con e-parent e-lazyloaded my-6',
	'',
	array(
		array(
			'record_id' => 'banners-18-one',
			'widget_id' => '2ec67f7',
			'is_lazy'   => true,
		),
		array(
			'record_id' => 'banners-18-two',
			'widget_id' => '78e286e7',
			'is_lazy'   => true,
		),
	)
);
