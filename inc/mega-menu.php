<?php
/**
 * Custom Nav Menu Walker with Mega Menu Support
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Negar_Shop_Mega_Menu_Walker
 */
class Negar_Shop_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		if ( 0 === $depth ) {
			$output .= "\n$indent<div class=\"negar-dropdown-menu\">\n$indent\t<ul class=\"sub-menu\">\n";
		} else {
			$output .= "\n$indent<ul class=\"sub-menu depth-" . esc_attr( $depth ) . "\">\n";
		}
	}

	/**
	 * Ends the list of after the elements are added.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		if ( 0 === $depth ) {
			$output .= "$indent\t</ul>\n$indent</div>\n";
		} else {
			$output .= "$indent</ul>\n";
		}
	}

	/**
	 * Starts the element output.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$menu_item = $data_object;
		$indent    = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
		$classes[] = 'menu-item-' . $menu_item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
		$atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
		if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
			$atts['rel'] = 'noopener';
		} else {
			$atts['rel'] = $menu_item->xfn;
		}
		$atts['href']         = ! empty( $menu_item->url ) ? $menu_item->url : '';
		$atts['aria-current'] = $menu_item->current ? 'page' : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

		// Check for badges in title or classes
		$badge_html = '';
		if ( stripos( $title, '[حراج]' ) !== false || in_array( 'badge-sale', $classes, true ) ) {
			$title      = str_replace( '[حراج]', '', $title );
			$badge_html = ' <span class="nav-badge badge-sale">' . esc_html__( 'حراج', 'negar-shop' ) . '</span>';
		} elseif ( stripos( $title, '[جدید]' ) !== false || in_array( 'badge-new', $classes, true ) ) {
			$title      = str_replace( '[جدید]', '', $title );
			$badge_html = ' <span class="nav-badge badge-new">' . esc_html__( 'جدید', 'negar-shop' ) . '</span>';
		} elseif ( stripos( $title, '[ویژه]' ) !== false || in_array( 'badge-hot', $classes, true ) ) {
			$title      = str_replace( '[ویژه]', '', $title );
			$badge_html = ' <span class="nav-badge badge-hot">' . esc_html__( 'ویژه', 'negar-shop' ) . '</span>';
		}

		$item_output = $args->before ?? '';
		$item_output .= '<a' . $attributes . ' class="nav-link">';
		$item_output .= ( $args->link_before ?? '' ) . $title . ( $args->link_after ?? '' );
		$item_output .= $badge_html;

		if ( $has_children && 0 === $depth ) {
			$item_output .= '<svg class="chevron-down" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>';
		} elseif ( $has_children && $depth > 0 ) {
			$item_output .= '<svg class="chevron-left" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>';
		}

		$item_output .= '</a>';
		$item_output .= $args->after ?? '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}
}
