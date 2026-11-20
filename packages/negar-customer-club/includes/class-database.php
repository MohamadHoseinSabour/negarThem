<?php
/**
 * Database Migration & Schema Manager for Negar Customer Club.
 *
 * Implements idempotent table creation and migrations using dbDelta
 * for Customer Club applications and audit log tables with atomic concurrency guards.
 *
 * @package NegarCustomerClub
 * @version 1.1.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Database {

	/**
	 * Database schema version.
	 */
	const DB_VERSION = '1.1.0';

	/**
	 * Get dynamic table name with test prefix support for isolated execution.
	 *
	 * @param string $table Table identifier ('applications' or 'audit_log').
	 * @return string Full prefixed table name.
	 */
	public static function get_table_name( $table = 'applications' ) {
		global $wpdb;

		$test_prefix = defined( 'NEGAR_CLUB_TEST_PREFIX' ) ? constant( 'NEGAR_CLUB_TEST_PREFIX' ) : '';

		return $wpdb->prefix . $test_prefix . 'negar_club_' . sanitize_key( $table );
	}

	/**
	 * Create or upgrade custom database tables using dbDelta.
	 *
	 * Idempotent: checks existing schema and updates without data loss.
	 * Verifies physical table creation before declaring migration success.
	 *
	 * @return bool True on verified success, false on failure.
	 */
	public static function create_tables() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		// 1. Table: wp_negar_club_applications
		$table_applications = self::get_table_name( 'applications' );
		$sql_applications   = "CREATE TABLE {$table_applications} (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  application_number varchar(32) NOT NULL,
  user_id bigint(20) unsigned NOT NULL,
  plan_id varchar(32) NOT NULL,
  status varchar(24) NOT NULL DEFAULT 'pending_review',
  requested_ceiling bigint(20) unsigned NOT NULL DEFAULT 0,
  approved_ceiling bigint(20) unsigned NOT NULL DEFAULT 0,
  national_id_hash varchar(64) DEFAULT NULL,
  active_lock_user varchar(32) DEFAULT NULL,
  active_lock_nid varchar(64) DEFAULT NULL,
  income_bracket varchar(32) DEFAULT NULL,
  contact_phone varchar(20) NOT NULL,
  applicant_notes text DEFAULT NULL,
  operator_notes text DEFAULT NULL,
  customer_message text DEFAULT NULL,
  reviewed_by bigint(20) unsigned DEFAULT NULL,
  reviewed_at datetime DEFAULT NULL,
  expires_at datetime DEFAULT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (id),
  UNIQUE KEY uq_application_number (application_number),
  UNIQUE KEY uq_active_user (active_lock_user),
  UNIQUE KEY uq_active_nid (active_lock_nid),
  KEY ix_user_status (user_id, status),
  KEY ix_status_created (status, created_at),
  KEY ix_national_hash (national_id_hash)
) {$charset_collate};";

		dbDelta( $sql_applications );

		// 2. Table: wp_negar_club_audit_log
		$table_audit_log = self::get_table_name( 'audit_log' );
		$sql_audit_log   = "CREATE TABLE {$table_audit_log} (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  entity_type varchar(32) NOT NULL,
  entity_id bigint(20) unsigned NOT NULL,
  event_action varchar(48) NOT NULL,
  old_state longtext DEFAULT NULL,
  new_state longtext DEFAULT NULL,
  actor_id bigint(20) unsigned NOT NULL DEFAULT 0,
  actor_role varchar(32) NOT NULL DEFAULT '',
  actor_ip varchar(45) NOT NULL DEFAULT '',
  user_agent varchar(255) DEFAULT NULL,
  context_note varchar(255) DEFAULT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (id),
  KEY ix_entity (entity_type, entity_id),
  KEY ix_actor (actor_id, created_at)
) {$charset_collate};";

		dbDelta( $sql_audit_log );

		// Strict verification of table creation before recording success (Fix D)
		$app_exists   = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_applications ) );
		$audit_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_audit_log ) );

		if ( $app_exists === $table_applications && $audit_exists === $table_audit_log ) {
			if ( ! defined( 'NEGAR_CLUB_TEST_PREFIX' ) ) {
				update_option( 'negar_club_db_version', self::DB_VERSION );
			}
			return true;
		}

		return false;
	}

	/**
	 * Append an entry to the audit log table.
	 *
	 * @param string               $entity_type  Entity type ('club_application', etc.).
	 * @param int                  $entity_id    Entity ID.
	 * @param string               $event_action Action name ('application:submit', 'application:approve', etc.).
	 * @param mixed                $old_state    Previous state (array, object or null).
	 * @param mixed                $new_state    New state (array, object or null).
	 * @param int|null             $actor_id     User ID executing the action.
	 * @param string               $context_note Optional note/reason.
	 * @return int|false Inserted row ID or false on failure.
	 */
	public static function log_audit( $entity_type, $entity_id, $event_action, $old_state = null, $new_state = null, $actor_id = null, $context_note = '' ) {
		global $wpdb;

		$table_audit_log = self::get_table_name( 'audit_log' );

		if ( null === $actor_id ) {
			$actor_id = function_exists( 'get_current_user_id' ) ? (int) get_current_user_id() : 0;
		}

		$actor_role = 'guest';
		if ( $actor_id > 0 && function_exists( 'get_userdata' ) ) {
			$user = get_userdata( $actor_id );
			if ( $user && ! empty( $user->roles ) ) {
				$actor_role = (string) reset( $user->roles );
			}
		}

		$actor_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
		if ( strlen( $actor_ip ) > 45 ) {
			$actor_ip = substr( $actor_ip, 0, 45 );
		}

		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
		if ( strlen( $user_agent ) > 255 ) {
			$user_agent = substr( $user_agent, 0, 255 );
		}

		$context_note = sanitize_text_field( (string) $context_note );
		if ( strlen( $context_note ) > 255 ) {
			$context_note = substr( $context_note, 0, 255 );
		}

		$data = array(
			'entity_type'  => sanitize_key( $entity_type ),
			'entity_id'    => (int) $entity_id,
			'event_action' => sanitize_text_field( $event_action ),
			'old_state'    => null !== $old_state ? wp_json_encode( $old_state ) : null,
			'new_state'    => null !== $new_state ? wp_json_encode( $new_state ) : null,
			'actor_id'     => (int) $actor_id,
			'actor_role'   => sanitize_key( $actor_role ),
			'actor_ip'     => $actor_ip,
			'user_agent'   => $user_agent,
			'context_note' => $context_note,
			'created_at'   => current_time( 'mysql' ),
		);

		$format = array( '%s', '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s' );

		$inserted = $wpdb->insert( $table_audit_log, $data, $format );
		if ( false === $inserted ) {
			return false;
		}

		return (int) $wpdb->insert_id;
	}
}
