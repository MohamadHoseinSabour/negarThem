<?php
/**
 * Custom template tags for Negar Shop
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'negar_shop_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function negar_shop_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated screen-reader-text" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		echo '<span class="posted-on">' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'negar_shop_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function negar_shop_posted_by() {
		echo '<span class="byline"> ' . esc_html__( 'توسط', 'negar-shop' ) . ' <span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span></span>';
	}
endif;

if ( ! function_exists( 'negar_shop_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * @param string $size Image size.
	 */
	function negar_shop_post_thumbnail( $size = 'post-thumbnail' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<div class="post-thumbnail mb-6 rounded-lg overflow-hidden">
				<?php the_post_thumbnail( $size, array( 'class' => 'w-full h-auto object-cover' ) ); ?>
			</div>
		<?php else : ?>
			<a class="post-thumbnail block mb-4 rounded-lg overflow-hidden group" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php the_post_thumbnail( $size, array( 'class' => 'w-full h-auto object-cover transition duration-300 group-hover:scale-105' ) ); ?>
			</a>
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'negar_shop_product_discount_percentage' ) ) :
	/**
	 * Calculate discount percentage for a WooCommerce product.
	 *
	 * @param WC_Product $product Product instance.
	 * @return int Discount percentage.
	 */
	function negar_shop_product_discount_percentage( $product ) {
		if ( ! $product || ! $product->is_on_sale() ) {
			return 0;
		}

		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();

		if ( $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price ) {
			return (int) round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
		}

		return 0;
	}
endif;

if ( ! function_exists( 'negar_shop_get_discount_badge' ) ) :
	/**
	 * Output discount badge HTML for single product and cards.
	 *
	 * @param WC_Product $product Product instance.
	 * @return string Badge HTML.
	 */
	function negar_shop_get_discount_badge( $product ) {
		$discount = negar_shop_product_discount_percentage( $product );
		if ( $discount <= 0 ) {
			return '';
		}
		return '<span class="badge badge-discount bg-rose-600 text-white text-xs font-black py-1 px-2.5 rounded-full shadow-xs">٪' . esc_html( $discount ) . ' تخفیف</span>';
	}
endif;

if ( ! function_exists( 'negar_shop_phone_href' ) ) :
	/**
	 * Normalize Persian and Arabic digits for a telephone URI.
	 *
	 * @param string $phone Phone number entered in the Customizer.
	 * @return string
	 */
	function negar_shop_phone_href( $phone ) {
		$phone = strtr(
			(string) $phone,
			array(
				'۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
				'۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
				'٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
				'٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
			)
		);

		return preg_replace( '/[^0-9+]/', '', $phone );
	}
endif;
