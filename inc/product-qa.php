<?php
/**
 * Product Questions & Answers (Q&A) Persistence and Moderation
 * Uses native WordPress comment architecture with comment_type = 'product_qa'.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch Persisted Questions for a Product
 *
 * @param int $product_id Product post ID.
 * @return array Array of approved question comments with replies.
 */
function negar_get_product_questions( $product_id ) {
	$product_id = (int) $product_id;
	if ( ! $product_id ) {
		return array();
	}

	$questions = get_comments( array(
		'post_id' => $product_id,
		'type'    => 'product_qa',
		'status'  => 'approve',
		'parent'  => 0,
		'orderby' => 'comment_date_gmt',
		'order'   => 'DESC',
	) );

	if ( empty( $questions ) ) {
		return array();
	}

	$result = array();
	foreach ( $questions as $q ) {
		// Fetch replies / answers by admin or support
		$answers = get_comments( array(
			'post_id' => $product_id,
			'type'    => 'product_qa',
			'status'  => 'approve',
			'parent'  => $q->comment_ID,
			'orderby' => 'comment_date_gmt',
			'order'   => 'ASC',
		) );

		$likes    = (int) get_comment_meta( $q->comment_ID, '_kav_likes', true );
		$dislikes = (int) get_comment_meta( $q->comment_ID, '_kav_dislikes', true );

		$is_buyer = false;
		if ( $q->user_id ) {
			$is_buyer = wc_customer_bought_product( '', $q->user_id, $product_id );
		}

		$result[] = array(
			'id'       => $q->comment_ID,
			'author'   => $q->comment_author,
			'date'     => get_comment_date( 'j F Y', $q ),
			'question' => $q->comment_content,
			'status'   => $is_buyer ? 'buyer' : 'user',
			'likes'    => $likes,
			'dislikes' => $dislikes,
			'answers'  => array_map( function( $a ) {
				return array(
					'id'     => $a->comment_ID,
					'author' => $a->comment_author ? $a->comment_author : esc_html__( 'پشتیبانی فنی', 'negar-shop' ),
					'date'   => get_comment_date( 'j F Y', $a ),
					'text'   => $a->comment_content,
					'status' => 'support',
				);
			}, $answers ),
		);
	}

	return $result;
}

/**
 * AJAX Handler: Submit a New Product Question
 */
function negar_ajax_submit_product_question() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? (int) $_POST['product_id'] : 0;
	$author     = isset( $_POST['author'] ) ? sanitize_text_field( wp_unslash( $_POST['author'] ) ) : '';
	$question   = isset( $_POST['question'] ) ? sanitize_textarea_field( wp_unslash( $_POST['question'] ) ) : '';

	if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
		wp_send_json_error( array( 'message' => esc_html__( 'محصول مورد نظر یافت نشد.', 'negar-shop' ) ) );
	}

	if ( mb_strlen( $question, 'UTF-8' ) < 5 ) {
		wp_send_json_error( array( 'message' => esc_html__( 'متن پرسش باید حداقل ۵ کاراکتر باشد.', 'negar-shop' ) ) );
	}

	$user_id = get_current_user_id();
	if ( $user_id && empty( $author ) ) {
		$current_user = wp_get_current_user();
		$author       = $current_user->display_name;
	}

	if ( empty( $author ) ) {
		$author = esc_html__( 'کاربر مهمان', 'negar-shop' );
	}

	$comment_data = array(
		'comment_post_ID'      => $product_id,
		'comment_author'       => $author,
		'comment_content'      => $question,
		'comment_type'         => 'product_qa',
		'user_id'              => $user_id,
		'comment_approved'     => 1, // Approved by default for instant interactivity, manageable via WP Comments
		'comment_author_IP'    => preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] ?? '' ),
		'comment_agent'        => substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ?? '' ) ), 0, 254 ),
		'comment_date'         => current_time( 'mysql' ),
		'comment_date_gmt'     => current_time( 'mysql', 1 ),
	);

	$comment_id = wp_insert_comment( $comment_data );

	if ( ! $comment_id ) {
		wp_send_json_error( array( 'message' => esc_html__( 'خطا در ثبت پرسش. لطفاً دوباره تلاش کنید.', 'negar-shop' ) ) );
	}

	update_comment_meta( $comment_id, '_kav_likes', 0 );
	update_comment_meta( $comment_id, '_kav_dislikes', 0 );

	wp_send_json_success( array(
		'message'    => esc_html__( 'پرسش شما با موفقیت ثبت شد و نمایش داده شد.', 'negar-shop' ),
		'comment_id' => $comment_id,
		'author'     => $author,
		'question'   => $question,
		'date'       => current_time( 'j F Y' ),
	) );
}
add_action( 'wp_ajax_negar_submit_product_question', 'negar_ajax_submit_product_question' );
add_action( 'wp_ajax_nopriv_negar_submit_product_question', 'negar_ajax_submit_product_question' );

/**
 * AJAX Handler: Like / Dislike Vote for Reviews and Q&A
 */
function negar_ajax_vote_comment() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$comment_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
	$vote_type  = isset( $_POST['vote_type'] ) && 'dislike' === $_POST['vote_type'] ? 'dislike' : 'like';

	if ( ! $comment_id ) {
		wp_send_json_error( array( 'message' => esc_html__( 'دیدگاه یا پرسش نامعتبر است.', 'negar-shop' ) ) );
	}

	$meta_key = ( 'dislike' === $vote_type ) ? '_kav_dislikes' : '_kav_likes';
	$current  = (int) get_comment_meta( $comment_id, $meta_key, true );
	$new_val  = $current + 1;

	update_comment_meta( $comment_id, $meta_key, $new_val );

	wp_send_json_success( array(
		'new_count' => $new_val,
		'vote_type' => $vote_type,
	) );
}
add_action( 'wp_ajax_negar_vote_comment', 'negar_ajax_vote_comment' );
add_action( 'wp_ajax_nopriv_negar_vote_comment', 'negar_ajax_vote_comment' );
