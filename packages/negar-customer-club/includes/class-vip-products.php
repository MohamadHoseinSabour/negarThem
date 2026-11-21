<?php
/**
 * WooCommerce VIP Products Mapping & Cart Safeguards for Negar Customer Club.
 *
 * Implements standard WooCommerce simple virtual product associations for VIP plans,
 * ensuring clean separation from regular merchandise and enforcing authentication.
 *
 * @package NegarCustomerClub
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_VIP_Products {

	/**
	 * Meta key linking WooCommerce product to Customer Club plan.
	 */
	const META_KEY_PLAN_ID = '_negar_club_plan_id';

	/**
	 * Fixed plan identifiers.
	 */
	const PLAN_AGHAZ = 'negar_aghaz';
	const PLAN_PLUS  = 'negar_plus';
	const PLAN_VIZHE = 'negar_vizhe';

	/**
	 * Initialize cart and checkout hooks.
	 */
	public static function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Cart item validation (anti-guest, single VIP purchase, no mixed checkout)
		add_action( 'woocommerce_check_cart_items', array( __CLASS__, 'validate_cart_items' ) );

		// Simplified VIP checkout fields
		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'filter_checkout_fields' ), 20 );
		add_filter( 'woocommerce_billing_fields', array( __CLASS__, 'filter_billing_fields' ), 20 );

		// Disable order notes for VIP checkout
		add_filter( 'woocommerce_enable_order_notes_field', array( __CLASS__, 'filter_enable_order_notes' ) );

		// Prefill default checkout values from authenticated profile / OTP
		add_filter( 'default_checkout_billing_first_name', array( __CLASS__, 'filter_default_first_name' ) );
		add_filter( 'default_checkout_billing_last_name', array( __CLASS__, 'filter_default_last_name' ) );
		add_filter( 'default_checkout_billing_phone', array( __CLASS__, 'filter_default_phone' ) );

		// Inject customer email from profile without asking on checkout
		add_filter( 'woocommerce_checkout_posted_data', array( __CLASS__, 'filter_checkout_posted_data' ) );

		// Server-side validation of VIP checkout fields
		add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'validate_vip_checkout_fields' ), 10, 2 );
	}

	/**
	 * Get configuration for a specific plan or all plans.
	 *
	 * @param string|null $plan_id Specific plan ID or null for all.
	 * @return array Plan configuration.
	 */
	public static function get_plan_configs( $plan_id = null ) {
		$theme_options = function_exists( 'get_option' ) ? get_option( 'negar_theme_club_options', array() ) : array();
		$plans_opt     = isset( $theme_options['plans'] ) && is_array( $theme_options['plans'] ) ? $theme_options['plans'] : array();

		$defaults = array(
			self::PLAN_AGHAZ => array(
				'id'              => self::PLAN_AGHAZ,
				'title'           => 'عضویت VIP نگار آغاز',
				'subtitle'        => 'شروعی آسان برای خرید اقساطی لوازم خانگی',
				'prepayment'      => 3000000,
				'ceiling'         => 6000000,
				'duration_months' => 3,
				'badge'           => 'اقتصادی',
			),
			self::PLAN_PLUS  => array(
				'id'              => self::PLAN_PLUS,
				'title'           => 'عضویت VIP نگار پلاس',
				'subtitle'        => 'تعادل مناسب بین پیش‌پرداخت و سقف خرید کالا',
				'prepayment'      => 5000000,
				'ceiling'         => 10000000,
				'duration_months' => 6,
				'badge'           => 'انتخاب محبوب',
			),
			self::PLAN_VIZHE => array(
				'id'              => self::PLAN_VIZHE,
				'title'           => 'عضویت VIP نگار ویژه',
				'subtitle'        => 'قدرت انتخاب بیشتر برای خریدهای بزرگ‌تر',
				'prepayment'      => 10000000,
				'ceiling'         => 20000000,
				'duration_months' => 6,
				'badge'           => 'حرفه‌ای',
			),
		);

		// Merge theme settings if available
		foreach ( $defaults as $id => $conf ) {
			if ( ! empty( $plans_opt[ $id ]['title'] ) ) {
				$defaults[ $id ]['title'] = 'اشتراک VIP ' . sanitize_text_field( $plans_opt[ $id ]['title'] );
			}
			if ( ! empty( $plans_opt[ $id ]['initial_payment'] ) && (int) $plans_opt[ $id ]['initial_payment'] > 0 ) {
				$defaults[ $id ]['prepayment'] = (int) $plans_opt[ $id ]['initial_payment'];
			}
			if ( ! empty( $plans_opt[ $id ]['ceiling'] ) && (int) $plans_opt[ $id ]['ceiling'] > 0 ) {
				$defaults[ $id ]['ceiling'] = (int) $plans_opt[ $id ]['ceiling'];
			}
			if ( ! empty( $plans_opt[ $id ]['duration_months'] ) && (int) $plans_opt[ $id ]['duration_months'] > 0 ) {
				$defaults[ $id ]['duration_months'] = (int) $plans_opt[ $id ]['duration_months'];
			}
		}

		if ( null !== $plan_id ) {
			return $defaults[ $plan_id ] ?? null;
		}

		return $defaults;
	}

	/**
	 * Find mapped WooCommerce product ID for a given plan ID.
	 *
	 * @param string $plan_id Plan identifier.
	 * @return int Product ID or 0 if not found.
	 */
	public static function get_plan_product_id( $plan_id ) {
		global $wpdb;

		$plan_id = sanitize_key( $plan_id );
		if ( empty( $plan_id ) ) {
			return 0;
		}

		// Fast query via postmeta
		$sql = "SELECT post_id FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = %s AND pm.meta_value = %s
			AND p.post_status != 'trash'
			LIMIT 1";

		$product_id = (int) $wpdb->get_var( $wpdb->prepare( $sql, self::META_KEY_PLAN_ID, $plan_id ) );

		return $product_id > 0 ? $product_id : 0;
	}

	/**
	 * Get plan ID mapped to a product ID.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return string Plan ID or empty string.
	 */
	public static function get_product_plan_id( $product_id ) {
		$product_id = (int) $product_id;
		if ( $product_id <= 0 ) {
			return '';
		}

		if ( function_exists( 'get_post_meta' ) ) {
			$plan_id = get_post_meta( $product_id, self::META_KEY_PLAN_ID, true );
			if ( ! empty( $plan_id ) ) {
				return sanitize_key( $plan_id );
			}
		}

		return '';
	}

	/**
	 * Check if a product is a Customer Club VIP plan product.
	 *
	 * @param int $product_id Product ID.
	 * @return bool True if VIP product.
	 */
	public static function is_vip_product( $product_id ) {
		return '' !== self::get_product_plan_id( $product_id );
	}

	/**
	 * Validate that a product is a suitable simple virtual VIP product.
	 *
	 * Must be:
	 * 1. Valid WooCommerce product object.
	 * 2. Simple product.
	 * 3. Virtual.
	 * 4. Not downloadable.
	 * 5. Published status.
	 * 6. Mapped to an authorized plan ('negar_aghaz', 'negar_plus', 'negar_vizhe').
	 * 7. Price matches the plan's defined prepayment amount.
	 *
	 * Production validation fails closed (returns false) if any check fails.
	 * Unit and integration tests may override via 'negar_club_is_valid_vip_product' filter.
	 *
	 * @param int $product_id WooCommerce product ID.
	 * @return bool True if valid VIP product.
	 */
	public static function is_valid_vip_product( $product_id ) {
		$product_id = (int) $product_id;
		if ( $product_id <= 0 ) {
			return false;
		}

		$is_valid = false;
		if ( function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $product_id );
			if ( $product ) {
				$plan_id            = self::get_product_plan_id( $product_id );
				$valid_plans        = array( 'negar_aghaz', 'negar_plus', 'negar_vizhe' );
				$is_plan_authorized = in_array( $plan_id, $valid_plans, true );

				$configs        = self::get_plan_configs( $plan_id );
				$expected_price = isset( $configs['prepayment'] ) ? (int) $configs['prepayment'] : 0;
				$actual_price   = method_exists( $product, 'get_price' ) ? (int) $product->get_price() : 0;

				$is_simple       = method_exists( $product, 'is_type' ) ? $product->is_type( 'simple' ) : false;
				$is_virtual      = method_exists( $product, 'is_virtual' ) && $product->is_virtual();
				$is_downloadable = method_exists( $product, 'is_downloadable' ) && $product->is_downloadable();
				$is_published    = method_exists( $product, 'get_status' ) ? ( 'publish' === $product->get_status() ) : ( 'publish' === get_post_status( $product_id ) );
				$price_matches   = ( $expected_price > 0 && $actual_price === $expected_price );

				$is_valid = $is_plan_authorized
					&& $is_simple
					&& $is_virtual
					&& ! $is_downloadable
					&& $is_published
					&& $price_matches;
			}
		}

		if ( function_exists( 'apply_filters' ) ) {
			$is_valid = apply_filters( 'negar_club_is_valid_vip_product', $is_valid, $product_id );
		}

		return (bool) $is_valid;
	}

	/**
	 * Ensure VIP products exist in WooCommerce for all three tiers.
	 *
	 * Strictly protected: Can only be executed from an authorized admin action,
	 * WP-CLI or test context. Never callable from frontend page requests.
	 * Idempotent: checks for existing valid products and reuses them without duplicating.
	 *
	 * @return array Mapped product IDs [ plan_id => product_id ].
	 */
	public static function ensure_vip_products() {
		// Guard: Allow only authorized operators/admins, CLI, or test execution
		$can_setup = ( is_admin() && Negar_Club_Roles::current_user_can_operate() )
			|| ( defined( 'WP_CLI' ) && WP_CLI )
			|| defined( 'NEGAR_CLUB_TEST_PREFIX' );

		if ( ! $can_setup ) {
			return array();
		}

		if ( ! class_exists( 'WC_Product_Simple' ) ) {
			return array();
		}

		$configs = self::get_plan_configs();
		$mapping = array();

		foreach ( $configs as $plan_id => $conf ) {
			$existing_id = self::get_plan_product_id( $plan_id );
			if ( $existing_id > 0 && self::is_valid_vip_product( $existing_id ) ) {
				$mapping[ $plan_id ] = $existing_id;
				continue;
			}

			// Create standard WooCommerce simple virtual product
			$product = new WC_Product_Simple();
			$product->set_name( $conf['title'] );
			$product->set_status( 'publish' );
			$product->set_catalog_visibility( 'hidden' ); // Hidden from general shop catalog
			$product->set_virtual( true );
			$product->set_downloadable( false );
			$product->set_sold_individually( true );
			$product->set_regular_price( (string) $conf['prepayment'] );
			$product->set_price( (string) $conf['prepayment'] );
			$product->set_description( sprintf(
				'پیش‌پرداخت عضویت VIP در باشگاه مشتریان نگار. سقف خرید مجاز: %s تومان. مهلت بازپرداخت اقساط حضوری: %d ماه.',
				number_format( (int) $conf['ceiling'] ),
				(int) $conf['duration_months']
			) );

			$product->update_meta_data( self::META_KEY_PLAN_ID, $plan_id );
			$new_id = $product->save();

			if ( $new_id > 0 ) {
				$mapping[ $plan_id ] = $new_id;
			}
		}

		return $mapping;
	}

	/**
	 * Get checkout URL for purchasing a VIP plan.
	 *
	 * Note: Never creates products during ordinary frontend requests!
	 * If the product is not mapped or invalid, returns empty string.
	 *
	 * @param string $plan_id Plan identifier.
	 * @return string Checkout or cart URL, or empty string.
	 */
	public static function get_checkout_url_for_plan( $plan_id ) {
		$product_id = self::get_plan_product_id( $plan_id );

		if ( $product_id <= 0 || ! self::is_valid_vip_product( $product_id ) ) {
			return '';
		}

		if ( function_exists( 'wc_get_checkout_url' ) ) {
			return add_query_arg( 'add-to-cart', $product_id, wc_get_checkout_url() );
		}

		return '?add-to-cart=' . $product_id;
	}

	/**
	 * Validate cart items during checkout:
	 * 1. Require user authentication for VIP plans (no guest VIP).
	 * 2. Forbid combining VIP plan with regular merchandise.
	 * 3. Enforce maximum of 1 VIP plan per checkout order.
	 * 4. Warn if customer already has an active VIP membership.
	 */
	public static function validate_cart_items() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}

		$vip_count     = 0;
		$regular_count = 0;
		$vip_plan_id   = '';

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product_id = $cart_item['product_id'] ?? 0;
			$plan_id    = self::get_product_plan_id( $product_id );

			if ( ! empty( $plan_id ) ) {
				$vip_count     += (int) ( $cart_item['quantity'] ?? 1 );
				$vip_plan_id    = $plan_id;
			} else {
				$regular_count += (int) ( $cart_item['quantity'] ?? 1 );
			}
		}

		if ( $vip_count === 0 ) {
			return; // No VIP products in cart
		}

		// 1. Authentication check
		if ( ! is_user_logged_in() ) {
			wc_add_notice(
				__( 'برای خرید اشتراک VIP باشگاه مشتریان، لطفاً ابتدا وارد حساب کاربری خود شوید یا ثبت‌نام فرمایید.', 'negar-club' ),
				'error'
			);
		}

		// 2. Separation of VIP and ordinary merchandise
		if ( $regular_count > 0 ) {
			wc_add_notice(
				__( 'اشتراک VIP باشگاه مشتریان باید به صورت یک سفارش مجزا خریداری شود. لطفاً کالاهای دیگر را از سبد خرید حذف کنید.', 'negar-club' ),
				'error'
			);
		}

		// 3. Single VIP plan per order
		if ( $vip_count > 1 ) {
			wc_add_notice(
				__( 'در هر سفارش تنها یک اشتراک VIP قابل خریداری است.', 'negar-club' ),
				'error'
			);
		}

		// 4. Duplicate active membership check
		if ( is_user_logged_in() ) {
			$customer_id = get_current_user_id();
			$active_membership = Negar_Club_Database::get_active_membership_by_customer( $customer_id );
			if ( $active_membership ) {
				wc_add_notice(
					__( 'شما در حال حاضر دارای یک اشتراک فعال VIP در باشگاه مشتریان هستید. امکان خرید همزمان اشتراک جدید وجود ندارد.', 'negar-club' ),
					'error'
				);
			}
		}
	}

	/**
	 * Check whether the current WooCommerce cart contains an authorized VIP product
	 * and zero physical merchandise.
	 *
	 * Strictly fails closed if cart is empty or contains non-VIP merchandise.
	 *
	 * @return bool True if exclusively VIP digital cart.
	 */
	public static function is_vip_cart() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
			return false;
		}

		$vip_count     = 0;
		$regular_count = 0;

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product_id = $cart_item['product_id'] ?? 0;
			$plan_id    = self::get_product_plan_id( $product_id );

			if ( ! empty( $plan_id ) ) {
				$vip_count += (int) ( $cart_item['quantity'] ?? 1 );
			} else {
				$regular_count += (int) ( $cart_item['quantity'] ?? 1 );
			}
		}

		return ( $vip_count > 0 && 0 === $regular_count );
	}

	/**
	 * Retrieve prefilled customer info for VIP checkout.
	 *
	 * Reuses first name, last name, and OTP-verified phone number from authenticated profile.
	 *
	 * @param int|null $user_id Optional user ID. Defaults to current user.
	 * @return array{
	 *     first_name: string,
	 *     last_name: string,
	 *     phone: string,
	 *     has_first_name: bool,
	 *     has_last_name: bool,
	 *     has_phone: bool,
	 *     is_complete: bool
	 * }
	 */
	public static function get_customer_vip_checkout_data( $user_id = null ) {
		if ( null === $user_id && function_exists( 'get_current_user_id' ) ) {
			$user_id = get_current_user_id();
		}

		$user_id = (int) $user_id;
		$empty_data = array(
			'first_name'     => '',
			'last_name'      => '',
			'phone'          => '',
			'has_first_name' => false,
			'has_last_name'  => false,
			'has_phone'      => false,
			'is_complete'    => false,
		);

		if ( $user_id <= 0 || ! function_exists( 'get_userdata' ) ) {
			return $empty_data;
		}

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return $empty_data;
		}

		// First name: check billing_first_name, then user first_name
		$first_name = trim( (string) get_user_meta( $user_id, 'billing_first_name', true ) );
		if ( empty( $first_name ) && ! empty( $user->first_name ) ) {
			$first_name = trim( (string) $user->first_name );
		}

		// Last name: check billing_last_name, then user last_name
		$last_name = trim( (string) get_user_meta( $user_id, 'billing_last_name', true ) );
		if ( empty( $last_name ) && ! empty( $user->last_name ) ) {
			$last_name = trim( (string) $user->last_name );
		}

		// Phone: check billing_phone, then OTP-verified username if mobile
		$phone = trim( (string) get_user_meta( $user_id, 'billing_phone', true ) );
		if ( empty( $phone ) && preg_match( '/^09\d{9}$/', $user->user_login ) ) {
			$phone = $user->user_login;
		}

		$valid_phone = class_exists( 'Negar_Club_Validator' )
			? Negar_Club_Validator::validate_phone( $phone )
			: ( preg_match( '/^09\d{9}$/', $phone ) ? $phone : false );

		$has_fn    = ! empty( $first_name );
		$has_ln    = ! empty( $last_name );
		$has_phone = false !== $valid_phone && ! empty( $valid_phone );

		return array(
			'first_name'     => $first_name,
			'last_name'      => $last_name,
			'phone'          => false !== $valid_phone ? $valid_phone : '',
			'has_first_name' => $has_fn,
			'has_last_name'  => $has_ln,
			'has_phone'      => $has_phone,
			'is_complete'    => ( $has_fn && $has_ln && $has_phone ),
		);
	}

	/**
	 * Filter checkout fields for VIP membership digital checkout.
	 *
	 * When cart contains a VIP plan:
	 * - Unsets all address fields (country, state, city, address_1, address_2, postcode).
	 * - Unsets company.
	 * - Unsets shipping fields entirely.
	 * - Unsets order comments / notes.
	 * - Keeps only billing_first_name, billing_last_name, and billing_phone.
	 * - Reuses existing customer profile data if already set.
	 *
	 * @param array $fields All checkout fields.
	 * @return array Filtered checkout fields.
	 */
	public static function filter_checkout_fields( $fields ) {
		if ( ! self::is_vip_cart() ) {
			return $fields;
		}

		$customer_data = self::get_customer_vip_checkout_data();
		$allowed       = array( 'billing_first_name', 'billing_last_name', 'billing_phone' );

		if ( isset( $fields['billing'] ) && is_array( $fields['billing'] ) ) {
			foreach ( $fields['billing'] as $key => $conf ) {
				if ( ! in_array( $key, $allowed, true ) ) {
					unset( $fields['billing'][ $key ] );
				}
			}

			if ( isset( $fields['billing']['billing_first_name'] ) ) {
				$fields['billing']['billing_first_name']['required'] = true;
				$fields['billing']['billing_first_name']['label']    = __( 'نام', 'negar-shop' );
				$fields['billing']['billing_first_name']['class']    = array( 'form-row-first' );
				if ( ! empty( $customer_data['first_name'] ) ) {
					$fields['billing']['billing_first_name']['default'] = $customer_data['first_name'];
				}
			}

			if ( isset( $fields['billing']['billing_last_name'] ) ) {
				$fields['billing']['billing_last_name']['required'] = true;
				$fields['billing']['billing_last_name']['label']    = __( 'نام خانوادگی', 'negar-shop' );
				$fields['billing']['billing_last_name']['class']    = array( 'form-row-last' );
				if ( ! empty( $customer_data['last_name'] ) ) {
					$fields['billing']['billing_last_name']['default'] = $customer_data['last_name'];
				}
			}

			if ( isset( $fields['billing']['billing_phone'] ) ) {
				$fields['billing']['billing_phone']['required'] = true;
				$fields['billing']['billing_phone']['label']    = __( 'شماره همراه', 'negar-shop' );
				$fields['billing']['billing_phone']['class']    = array( 'form-row-wide' );
				$fields['billing']['billing_phone']['validate'] = array( 'phone' );
				if ( ! empty( $customer_data['phone'] ) ) {
					$fields['billing']['billing_phone']['default'] = $customer_data['phone'];
				}
			}
		}

		if ( isset( $fields['shipping'] ) ) {
			unset( $fields['shipping'] );
		}

		if ( isset( $fields['order']['order_comments'] ) ) {
			unset( $fields['order']['order_comments'] );
		}

		return $fields;
	}

	/**
	 * Filter billing fields directly.
	 *
	 * @param array $fields Billing fields.
	 * @return array Filtered billing fields.
	 */
	public static function filter_billing_fields( $fields ) {
		if ( ! self::is_vip_cart() ) {
			return $fields;
		}

		$allowed = array( 'billing_first_name', 'billing_last_name', 'billing_phone' );
		foreach ( $fields as $key => $conf ) {
			if ( ! in_array( $key, $allowed, true ) ) {
				unset( $fields[ $key ] );
			}
		}

		if ( isset( $fields['billing_first_name'] ) ) {
			$fields['billing_first_name']['required'] = true;
		}
		if ( isset( $fields['billing_last_name'] ) ) {
			$fields['billing_last_name']['required'] = true;
		}
		if ( isset( $fields['billing_phone'] ) ) {
			$fields['billing_phone']['required'] = true;
		}

		return $fields;
	}

	/**
	 * Disable order comments field on VIP checkout.
	 *
	 * @param bool $enabled Order notes enabled state.
	 * @return bool False for VIP, original value otherwise.
	 */
	public static function filter_enable_order_notes( $enabled ) {
		if ( self::is_vip_cart() ) {
			return false;
		}
		return $enabled;
	}

	/**
	 * Default billing first name filter for VIP checkout.
	 *
	 * @param mixed $value Default value.
	 * @return mixed
	 */
	public static function filter_default_first_name( $value ) {
		if ( empty( $value ) && self::is_vip_cart() ) {
			$data = self::get_customer_vip_checkout_data();
			return ! empty( $data['first_name'] ) ? $data['first_name'] : $value;
		}
		return $value;
	}

	/**
	 * Default billing last name filter for VIP checkout.
	 *
	 * @param mixed $value Default value.
	 * @return mixed
	 */
	public static function filter_default_last_name( $value ) {
		if ( empty( $value ) && self::is_vip_cart() ) {
			$data = self::get_customer_vip_checkout_data();
			return ! empty( $data['last_name'] ) ? $data['last_name'] : $value;
		}
		return $value;
	}

	/**
	 * Default billing phone filter for VIP checkout.
	 *
	 * @param mixed $value Default value.
	 * @return mixed
	 */
	public static function filter_default_phone( $value ) {
		if ( empty( $value ) && self::is_vip_cart() ) {
			$data = self::get_customer_vip_checkout_data();
			return ! empty( $data['phone'] ) ? $data['phone'] : $value;
		}
		return $value;
	}

	/**
	 * Filter posted checkout data: carry user real email and normalize phone without manual entry.
	 *
	 * @param array $data Posted checkout data.
	 * @return array Sanitized posted data.
	 */
	public static function filter_checkout_posted_data( $data ) {
		if ( ! self::is_vip_cart() ) {
			return $data;
		}

		// Ensure customer's existing authentic email from profile is used on the order
		if ( empty( $data['billing_email'] ) && is_user_logged_in() ) {
			$user = wp_get_current_user();
			if ( $user && ! empty( $user->user_email ) ) {
				$data['billing_email'] = $user->user_email;
			}
		}

		// Normalize phone
		if ( ! empty( $data['billing_phone'] ) && class_exists( 'Negar_Club_Validator' ) ) {
			$norm = Negar_Club_Validator::validate_phone( $data['billing_phone'] );
			if ( false !== $norm ) {
				$data['billing_phone'] = $norm;
			}
		}

		return $data;
	}

	/**
	 * Server-side validation callback for VIP checkout submissions.
	 *
	 * @param array    $data   Submitted checkout data.
	 * @param WP_Error $errors Validation errors object.
	 * @return void
	 */
	public static function validate_vip_checkout_fields( $data, $errors ) {
		if ( ! self::is_vip_cart() ) {
			return;
		}

		$first_name = trim( (string) ( $data['billing_first_name'] ?? '' ) );
		$last_name  = trim( (string) ( $data['billing_last_name'] ?? '' ) );
		$phone      = trim( (string) ( $data['billing_phone'] ?? '' ) );

		if ( empty( $first_name ) ) {
			$errors->add( 'validation', __( 'لطفاً نام خریدار اشتراک VIP را وارد فرمایید.', 'negar-shop' ) );
		}

		if ( empty( $last_name ) ) {
			$errors->add( 'validation', __( 'لطفاً نام خانوادگی خریدار اشتراک VIP را وارد فرمایید.', 'negar-shop' ) );
		}

		if ( empty( $phone ) ) {
			$errors->add( 'validation', __( 'لطفاً شماره همراه خریدار اشتراک VIP را وارد فرمایید.', 'negar-shop' ) );
		} elseif ( class_exists( 'Negar_Club_Validator' ) && false === Negar_Club_Validator::validate_phone( $phone ) ) {
			$errors->add( 'validation', __( 'شماره همراه وارد شده نامعتبر است. شماره همراه باید ۱۱ رقم بوده و با ۰۹ آغاز شود.', 'negar-shop' ) );
		}
	}
}
