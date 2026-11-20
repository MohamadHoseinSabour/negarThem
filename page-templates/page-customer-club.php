<?php
/**
 * Template Name: Customer Club / باشگاه مشتریان
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$is_enabled = function_exists( 'negar_theme_club_setting' ) ? (bool) negar_theme_club_setting( 'is_enabled', null, true ) : true;
$sections   = function_exists( 'negar_theme_club_setting' ) ? (array) negar_theme_club_setting( 'sections_visibility', null, array() ) : array();
?>

<main id="primary" class="site-main negar-content-page negar-customer-club-page" dir="rtl">
	<div class="container">
		<nav class="negar-page-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر راهنما', 'negar-shop' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></a></li>
				<li class="separator" aria-hidden="true">/</li>
				<li class="current" aria-current="page"><?php esc_html_e( 'باشگاه مشتریان و خرید اقساطی', 'negar-shop' ); ?></li>
			</ol>
		</nav>

		<?php if ( ! $is_enabled ) : ?>
			<?php get_template_part( 'template-parts/customer-club/disabled' ); ?>
		<?php else : ?>
			<?php
			if ( $sections['hero'] ?? true ) {
				get_template_part( 'template-parts/customer-club/hero' );
			}
			if ( $sections['benefits'] ?? true ) {
				get_template_part( 'template-parts/customer-club/benefits' );
			}
			if ( $sections['plans'] ?? true ) {
				get_template_part( 'template-parts/customer-club/plans' );
			}
			if ( $sections['comparison'] ?? true ) {
				get_template_part( 'template-parts/customer-club/comparison' );
			}
			if ( ! empty( $sections['calculator'] ) ) {
				get_template_part( 'template-parts/customer-club/calculator' );
			}
			if ( $sections['timeline'] ?? true ) {
				get_template_part( 'template-parts/customer-club/steps' );
			}
			if ( $sections['faq'] ?? true ) {
				get_template_part( 'template-parts/customer-club/faq' );
			}
			if ( $sections['cta'] ?? true ) {
				get_template_part( 'template-parts/customer-club/cta' );
			}
			?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
