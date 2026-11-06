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
<html <?php language_attributes(); ?> dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
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

	<header id="masthead" class="site-header border-b border-slate-200 bg-white">
		<div class="container py-4 flex items-center justify-between">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					?>
					<h1 class="site-title text-xl font-bold m-0">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-slate-900">
							<?php bloginfo( 'name' ); ?>
						</a>
					</h1>
					<?php
					$negar_description = get_bloginfo( 'description', 'display' );
					if ( $negar_description || is_customize_preview() ) :
						?>
						<p class="site-description text-xs text-slate-500 m-0">
							<?php echo esc_html( $negar_description ); ?>
						</p>
						<?php
					endif;
				endif;
				?>
			</div>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'منوی اصلی', 'negar-shop' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) :
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'nav-menu flex items-center gap-6 list-none m-0 p-0',
						)
					);
				endif;
				?>
			</nav>
		</div>
	</header>
