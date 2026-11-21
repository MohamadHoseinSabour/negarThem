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
	const DB_VERSION = '1.3.0';

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

		// 3. Table: wp_negar_club_memberships (Phase 6 VIP Prepayment Membership)
		$table_memberships = self::get_table_name( 'memberships' );
		$sql_memberships   = "CREATE TABLE {$table_memberships} (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  customer_id bigint(20) unsigned NOT NULL,
  plan_id varchar(32) NOT NULL,
  order_id bigint(20) unsigned NOT NULL,
  prepayment_amount bigint(20) unsigned NOT NULL DEFAULT 0,
  applied_deposit bigint(20) unsigned NOT NULL DEFAULT 0,
  unused_deposit bigint(20) unsigned NOT NULL DEFAULT 0,
  purchase_ceiling bigint(20) unsigned NOT NULL DEFAULT 0,
  used_purchase_amount bigint(20) unsigned NOT NULL DEFAULT 0,
  available_purchase_capacity bigint(20) unsigned NOT NULL DEFAULT 0,
  offline_debt_amount bigint(20) unsigned NOT NULL DEFAULT 0,
  term_months int(10) unsigned NOT NULL DEFAULT 3,
  status varchar(32) NOT NULL DEFAULT 'active',
  valid_until datetime DEFAULT NULL,
  notes text DEFAULT NULL,
  last_operator_id bigint(20) unsigned DEFAULT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (id),
  UNIQUE KEY uq_order_id (order_id),
  KEY ix_customer_status (customer_id, status),
  KEY ix_status (status)
) {$charset_collate};";

		dbDelta( $sql_memberships );

		// 4. Table: wp_negar_club_operation_tokens (Durable DB-level Idempotency)
		$table_tokens = self::get_table_name( 'operation_tokens' );
		$sql_tokens   = "CREATE TABLE {$table_tokens} (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  token varchar(64) NOT NULL,
  action varchar(48) NOT NULL,
  membership_id bigint(20) unsigned NOT NULL,
  actor_id bigint(20) unsigned NOT NULL DEFAULT 0,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (id),
  UNIQUE KEY uq_op_token (token),
  KEY ix_mem_action (membership_id, action)
) {$charset_collate};";

		dbDelta( $sql_tokens );

		// Ensure InnoDB storage engine for ACID transactions and row-level locking support
		$all_club_tables = array( $table_applications, $table_audit_log, $table_memberships, $table_tokens );
		foreach ( $all_club_tables as $tbl ) {
			$wpdb->query( "ALTER TABLE `{$tbl}` ENGINE = InnoDB" );
		}

		// Strict verification of table creation before recording success (Fix D)
		$app_exists    = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_applications ) );
		$audit_exists  = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_audit_log ) );
		$mem_exists    = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_memberships ) );
		$tokens_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_tokens ) );

		if ( $app_exists === $table_applications && $audit_exists === $table_audit_log && $mem_exists === $table_memberships && $tokens_exists === $table_tokens ) {
			if ( ! defined( 'NEGAR_CLUB_TEST_PREFIX' ) ) {
				update_option( 'negar_club_db_version', self::DB_VERSION );
			}
			return true;
		}

		return false;
	}

	/**
	 * Start a database transaction.
	 */
	public static function begin_transaction() {
		global $wpdb;
		$wpdb->query( 'START TRANSACTION' );
	}

	/**
	 * Commit active database transaction.
	 */
	public static function commit() {
		global $wpdb;
		$wpdb->query( 'COMMIT' );
	}

	/**
	 * Rollback active database transaction.
	 */
	public static function rollback() {
		global $wpdb;
		$wpdb->query( 'ROLLBACK' );
	}

	/**
	 * Get membership record with row-level lock (FOR UPDATE).
	 *
	 * @param int $membership_id Membership row ID.
	 * @return object|null Row object or null.
	 */
	public static function get_membership_for_update( $membership_id ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE id = %d FOR UPDATE", (int) $membership_id ) );
	}

	/**
	 * Check if a submission token has already been processed (idempotency).
	 *
	 * Checks transient fast cache and durable database unique records.
	 *
	 * @param string $token Unique submission token.
	 * @return bool True if already processed.
	 */
	public static function is_submission_token_used( $token ) {
		global $wpdb;

		$token = sanitize_text_field( $token );
		if ( empty( $token ) ) {
			return false;
		}

		// Fast transient cache check
		$used = get_transient( 'negar_club_subtoken_' . $token );
		if ( ! empty( $used ) ) {
			return true;
		}

		// Persistent DB table check
		$table = self::get_table_name( 'operation_tokens' );
		$found = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM `{$table}` WHERE token = %s LIMIT 1", $token ) );

		return ! empty( $found );
	}

	/**
	 * Atomically claim an operation token within the current database transaction.
	 *
	 * Uses database UNIQUE constraint on `token` to prevent concurrent execution.
	 *
	 * @param string $token         Unique submission token.
	 * @param string $action        Action being performed.
	 * @param int    $membership_id Membership row ID.
	 * @param int    $actor_id      Operator user ID.
	 * @throws Exception If token is missing, already used, or DB duplicate constraint triggers.
	 * @return bool True if successfully claimed.
	 */
	public static function claim_operation_token( $token, $action = '', $membership_id = 0, $actor_id = 0 ) {
		global $wpdb;

		$token = sanitize_text_field( $token );
		if ( empty( $token ) ) {
			throw new Exception( 'توکن یکتای ارسال عملیات الزامی است و نمی‌تواند خالی باشد.' );
		}

		$table = self::get_table_name( 'operation_tokens' );

		// Check fast cache first
		if ( self::is_submission_token_used( $token ) ) {
			throw new Exception( 'این عملیات قبلاً ارسال و پردازش شده است (توکن تکراری).' );
		}

		// Insert token into database inside current transaction
		$suppress = $wpdb->suppress_errors( true );
		$inserted = $wpdb->insert(
			$table,
			array(
				'token'         => $token,
				'action'        => sanitize_key( $action ),
				'membership_id' => (int) $membership_id,
				'actor_id'      => (int) $actor_id,
				'created_at'    => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%d', '%d', '%s' )
		);
		$wpdb->suppress_errors( $suppress );

		if ( false === $inserted ) {
			throw new Exception( 'این عملیات قبلاً ارسال و پردازش شده است (خطای انحصار توکن در پایگاه داده).' );
		}

		// Fast transient cache
		self::record_submission_token( $token, $actor_id );

		return true;
	}

	/**
	 * Record a submission token as processed.
	 *
	 * @param string $token Unique submission token.
	 * @param int    $actor_id Operator user ID.
	 * @param int    $ttl Time to live in seconds (default 2 hours).
	 * @return bool True on success.
	 */
	public static function record_submission_token( $token, $actor_id = 0, $ttl = 7200 ) {
		$token = sanitize_text_field( $token );
		if ( empty( $token ) ) {
			return false;
		}

		return set_transient( 'negar_club_subtoken_' . $token, $actor_id ?: 1, $ttl );
	}

	/**
	 * Get membership record by ID.
	 *
	 * @param int $membership_id Membership row ID.
	 * @return object|null Row object or null.
	 */
	public static function get_membership( $membership_id ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE id = %d", (int) $membership_id ) );
	}

	/**
	 * Get membership record by source WooCommerce order ID.
	 *
	 * @param int $order_id WooCommerce Order ID.
	 * @return object|null Row object or null.
	 */
	public static function get_membership_by_order( $order_id ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE order_id = %d", (int) $order_id ) );
	}

	/**
	 * Get active membership for a customer.
	 *
	 * @param int $customer_id WordPress user ID.
	 * @return object|null Active membership row or null.
	 */
	public static function get_active_membership_by_customer( $customer_id ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE customer_id = %d AND status = 'active' ORDER BY id DESC LIMIT 1", (int) $customer_id ) );
	}

	/**
	 * Get all memberships for a customer.
	 *
	 * @param int $customer_id WordPress user ID.
	 * @return array List of membership row objects.
	 */
	public static function get_memberships_by_customer( $customer_id ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE customer_id = %d ORDER BY id DESC", (int) $customer_id ) );
	}

	/**
	 * Create a new membership record.
	 *
	 * @param array $data Membership data.
	 * @return int|false Inserted ID or false.
	 */
	public static function create_membership( $data ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );

		$defaults = array(
			'customer_id'                 => 0,
			'plan_id'                     => '',
			'order_id'                    => 0,
			'prepayment_amount'           => 0,
			'applied_deposit'             => 0,
			'unused_deposit'              => 0,
			'purchase_ceiling'            => 0,
			'used_purchase_amount'        => 0,
			'available_purchase_capacity' => 0,
			'offline_debt_amount'         => 0,
			'term_months'                 => 3,
			'status'                      => 'active',
			'valid_until'                 => null,
			'notes'                       => null,
			'last_operator_id'            => null,
			'created_at'                  => current_time( 'mysql' ),
			'updated_at'                  => current_time( 'mysql' ),
		);

		$row = wp_parse_args( $data, $defaults );

		// Sanitize integer values
		$row['customer_id']                 = (int) $row['customer_id'];
		$row['order_id']                    = (int) $row['order_id'];
		$row['prepayment_amount']           = max( 0, (int) $row['prepayment_amount'] );
		$row['applied_deposit']             = max( 0, (int) $row['applied_deposit'] );
		$row['unused_deposit']              = max( 0, (int) $row['unused_deposit'] );
		$row['purchase_ceiling']            = max( 0, (int) $row['purchase_ceiling'] );
		$row['used_purchase_amount']        = max( 0, (int) $row['used_purchase_amount'] );
		$row['available_purchase_capacity'] = max( 0, (int) $row['available_purchase_capacity'] );
		$row['offline_debt_amount']         = max( 0, (int) $row['offline_debt_amount'] );
		$row['term_months']                 = max( 1, (int) $row['term_months'] );
		$row['plan_id']                     = sanitize_key( $row['plan_id'] );
		$row['status']                      = sanitize_key( $row['status'] );

		$formats = array(
			'%d', '%s', '%d',
			'%d', '%d', '%d',
			'%d', '%d', '%d', '%d',
			'%d', '%s',
			( null !== $row['valid_until'] ? '%s' : null ),
			( null !== $row['notes'] ? '%s' : null ),
			( null !== $row['last_operator_id'] ? '%d' : null ),
			'%s', '%s'
		);

		// Remove null formats if necessary or let insert handle
		$inserted = $wpdb->insert( $table, $row );
		if ( false === $inserted ) {
			return false;
		}

		return (int) $wpdb->insert_id;
	}

	/**
	 * Update an existing membership record.
	 *
	 * @param int   $membership_id Membership ID.
	 * @param array $data          Columns to update.
	 * @return bool True on success.
	 */
	public static function update_membership( $membership_id, $data ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );

		$data['updated_at'] = current_time( 'mysql' );

		$updated = $wpdb->update(
			$table,
			$data,
			array( 'id' => (int) $membership_id )
		);

		return false !== $updated;
	}

	/**
	 * Query memberships with pagination and filters for operator panel.
	 *
	 * @param array $args Query filters.
	 * @return array List of membership objects.
	 */
	public static function query_memberships( $args = array() ) {
		global $wpdb;

		$table = self::get_table_name( 'memberships' );

		$defaults = array(
			'status'   => '',
			'plan_id'  => '',
			'search'   => '',
			'limit'    => 20,
			'offset'   => 0,
			'orderby'  => 'id',
			'order'    => 'DESC',
		);

		$params = wp_parse_args( $args, $defaults );

		$where = array( '1=1' );
		$values = array();

		if ( ! empty( $params['status'] ) ) {
			$where[]  = 'status = %s';
			$values[] = sanitize_key( $params['status'] );
		}

		if ( ! empty( $params['plan_id'] ) ) {
			$where[]  = 'plan_id = %s';
			$values[] = sanitize_key( $params['plan_id'] );
		}

		if ( ! empty( $params['search'] ) ) {
			$search = '%' . $wpdb->esc_like( sanitize_text_field( $params['search'] ) ) . '%';
			$where[]  = '(order_id LIKE %s OR customer_id LIKE %s OR notes LIKE %s)';
			$values[] = $search;
			$values[] = $search;
			$values[] = $search;
		}

		$where_clause = implode( ' AND ', $where );
		$orderby = in_array( strtolower( $params['orderby'] ), array( 'id', 'created_at', 'updated_at', 'purchase_ceiling', 'prepayment_amount' ), true ) ? strtolower( $params['orderby'] ) : 'id';
		$order = 'ASC' === strtoupper( $params['order'] ) ? 'ASC' : 'DESC';
		$limit = max( 1, (int) $params['limit'] );
		$offset = max( 0, (int) $params['offset'] );

		$sql = "SELECT * FROM `{$table}` WHERE {$where_clause} ORDER BY {$orderby} {$order} LIMIT {$offset}, {$limit}";

		if ( ! empty( $values ) ) {
			return $wpdb->get_results( $wpdb->prepare( $sql, $values ) );
		}

		return $wpdb->get_results( $sql );
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

		if ( function_exists( 'apply_filters' ) ) {
			$mock_result = apply_filters( 'negar_club_log_audit_result', null, $entity_type, $entity_id, $event_action );
			if ( null !== $mock_result ) {
				return $mock_result;
			}
		}

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
