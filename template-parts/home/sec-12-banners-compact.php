<?php
/**
 * Section 12: Dual Compact Promo Banners #3 (HOME-12)
 * Exact 1:1 Implementation matching Kaveh Demo 43 Widget (elementor-element-26635d31)
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
	'26635d31',
	array(
		array(
			'record_id' => 'compact-12-one',
			'widget_id' => '57013a2f',
			'width'     => 773,
			'height'    => 228,
		),
		array(
			'record_id' => 'compact-12-two',
			'widget_id' => '2bf8a385',
			'width'     => 538,
			'height'    => 227,
		),
	)
);
