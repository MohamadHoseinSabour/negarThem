<?php
/**
 * Section 32: 2-Column Banner Grid #7 (HOME-32)
 * Reference: Demo 43 Section 31 (Top: 15713px, Height: ~274px)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

negar_theme_render_editorial_banner_grid(
	'elementor-element elementor-element-sec32-banners e-flex e-con-boxed e-con e-parent e-lazyloaded my-6',
	'',
	array(
		array(
			'record_id' => 'banners-32-one',
			'widget_id' => '14f3363d',
			'is_lazy'   => true,
		),
		array(
			'record_id' => 'banners-32-two',
			'widget_id' => '3d836679',
			'is_lazy'   => true,
		),
	)
);
