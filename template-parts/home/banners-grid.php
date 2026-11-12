<?php
/**
 * Promotional Middle Banners Grid Component #1 (HOME-06)
 * Reference: Demo 43 Section 5 (.elementor-element-4e969361)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

negar_theme_render_editorial_banner_grid(
	'elementor-element elementor-element-4e969361 e-flex e-con-boxed e-con e-parent e-lazyloaded my-6',
	'data-id="4e969361" data-element_type="container" data-e-type="container"',
	array(
		array(
			'record_id' => 'banners-grid-one',
			'widget_id' => '60d1115b',
			'col_id'    => '1e016233',
			'col_attrs' => 'data-id="1e016233" data-element_type="container" data-e-type="container"',
			'is_lazy'   => true,
		),
		array(
			'record_id' => 'banners-grid-two',
			'widget_id' => '447c3bc1',
			'col_id'    => '71ced218',
			'col_attrs' => 'data-id="71ced218" data-element_type="container" data-e-type="container"',
			'is_lazy'   => true,
		),
	)
);
