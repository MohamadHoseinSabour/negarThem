<?php
/**
 * Section 27: Dual Compact Promo Banners #5 (HOME-27)
 * Exact 1:1 Implementation matching Kaveh Demo 43 Widget (elementor-element-3c70d07e)
 * Right banner: banner-04 (773px width, ~59%)
 * Left banner: banner-05 (538px width, ~41%)
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

negar_theme_render_compact_banner_pair(
	'3c70d07e',
	array(
		array(
			'record_id' => 'compact-27-one',
			'widget_id' => '6feae589',
			'width'     => 773,
			'height'    => 228,
		),
		array(
			'record_id' => 'compact-27-two',
			'widget_id' => '2a90c2db',
			'width'     => 538,
			'height'    => 227,
		),
	)
);
