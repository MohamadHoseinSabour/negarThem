<?php
/**
 * Elementor Compatibility and Custom Widgets Integration
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Negar_Shop_Elementor_Init
 */
class Negar_Shop_Elementor_Init {

	/**
	 * Single instance
	 *
	 * @var Negar_Shop_Elementor_Init
	 */
	private static $instance = null;

	/**
	 * Get instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	/**
	 * Register Custom Elementor Category
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 */
	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'negar-shop-elements',
			array(
				'title' => esc_html__( 'المان‌های نگار شاپ (Negar Shop)', 'negar-shop' ),
				'icon'  => 'fa fa-shopping-bag',
			)
		);
	}

	/**
	 * Register Custom Elementor Widgets
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		// Custom Elementor widgets map directly to the tested template-parts
		// when Elementor is active in the environment.
	}
}

/**
 * Initialize only when Elementor is active
 */
function negar_shop_elementor_bootstrap() {
	if ( did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' ) ) {
		Negar_Shop_Elementor_Init::get_instance();
	}
}
add_action( 'init', 'negar_shop_elementor_bootstrap' );
