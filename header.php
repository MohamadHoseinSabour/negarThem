<?php
/**
 * The header for Negar Shop theme
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary">
		<?php esc_html_e( 'پرش به محتوای اصلی', 'negar-shop' ); ?>
	</a>

	<header id="masthead" class="site-header">
		<?php
		/**
		 * 1. Top Bar (HDR-01)
		 */
		get_template_part( 'template-parts/header/top-bar' );

		/**
		 * 2. Main Header with Logo, Ajax Search & Cart (HDR-02)
		 */
		get_template_part( 'template-parts/header/main-header' );

		/**
		 * 3. Primary Navigation & Mega Menu (HDR-03)
		 */
		get_template_part( 'template-parts/header/navigation' );

		/**
		 * 4. Mobile Header (HDR-05)
		 */
		get_template_part( 'template-parts/header/mobile-header' );
		?>
	</header>
