<?php
/**
 * Scoped Roles & Permissions Manager for Negar Customer Club.
 *
 * Implements the scoped `negar_club_operator` capability and assigns it
 * to Administrator and Shop Manager roles without granting broad admin privileges.
 *
 * @package NegarCustomerClub
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Roles {

	/**
	 * Scoped operator capability name.
	 */
	const CAPABILITY_OPERATOR = 'negar_club_operator';

	/**
	 * Roles that receive the operator capability.
	 *
	 * @var array<string>
	 */
	protected static $operator_roles = array( 'administrator', 'shop_manager' );

	/**
	 * Register the operator capability to allowed roles.
	 *
	 * @return void
	 */
	public static function register_capabilities() {
		if ( ! function_exists( 'get_role' ) ) {
			return;
		}

		foreach ( self::$operator_roles as $role_name ) {
			$role = get_role( $role_name );
			if ( $role && ! $role->has_cap( self::CAPABILITY_OPERATOR ) ) {
				$role->add_cap( self::CAPABILITY_OPERATOR );
			}
		}
	}

	/**
	 * Remove the operator capability from roles upon plugin uninstallation.
	 *
	 * @return void
	 */
	public static function remove_capabilities() {
		if ( ! function_exists( 'get_role' ) ) {
			return;
		}

		foreach ( self::$operator_roles as $role_name ) {
			$role = get_role( $role_name );
			if ( $role && $role->has_cap( self::CAPABILITY_OPERATOR ) ) {
				$role->remove_cap( self::CAPABILITY_OPERATOR );
			}
		}
	}

	/**
	 * Check whether the current user has permission to review customer club applications.
	 *
	 * Grants access if user has `negar_club_operator` capability or WordPress `manage_options`.
	 *
	 * @param int|null $user_id Optional user ID. Defaults to current user.
	 * @return bool
	 */
	public static function current_user_can_operate( $user_id = null ) {
		if ( null !== $user_id && function_exists( 'user_can' ) ) {
			return user_can( $user_id, self::CAPABILITY_OPERATOR ) || user_can( $user_id, 'manage_options' );
		}

		if ( ! function_exists( 'current_user_can' ) ) {
			return false;
		}

		return current_user_can( self::CAPABILITY_OPERATOR ) || current_user_can( 'manage_options' );
	}
}
