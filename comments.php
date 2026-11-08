<?php
/**
 * The template for displaying comments
 * Standardized to Kaveh Demo 43 design
 *
 * @package Negar_Shop
 * @version 2.5.0
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area bg-white rounded-4 p-4 p-md-5 shadow-sm mt-5">

	<?php if ( have_comments() ) : ?>
		<div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom border-light flex-wrap gap-2">
			<h3 class="comments-title f-iran-yekan fw-bold fs-5 text-dark m-0 d-flex align-items-center gap-2">
				<i class="icon-message text-danger lh-1"></i>
				<?php
				$comment_count = get_comments_number();
				printf(
					/* translators: 1: number of comments */
					esc_html( _nx( '%1$s دیدگاه ثبت شده', '%1$s دیدگاه برای این مطلب', $comment_count, 'comments title', 'negar-shop' ) ),
					number_format_i18n( $comment_count )
				);
				?>
			</h3>
		</div>

		<ul class="comment-list list-unstyled m-0 p-0 d-flex flex-column gap-3">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ul',
					'short_ping'  => true,
					'avatar_size' => 48,
					'callback'    => function( $comment, $args, $depth ) {
						$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
						?>
						<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item p-3 p-md-4 rounded-3 bg-light border border-light' ); ?>>
							<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
								<footer class="comment-meta d-flex align-items-center justify-content-between mb-3">
									<div class="comment-author vcard d-flex align-items-center gap-2">
										<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-circle flex-shrink-0' ) ); ?>
										<div>
											<b class="fn text-dark f-payda fs-6"><?php echo get_comment_author_link(); ?></b>
											<div class="comment-metadata text-muted small mt-0.5">
												<time datetime="<?php comment_time( 'c' ); ?>">
													<?php printf( esc_html__( '%1$s در %2$s', 'negar-shop' ), get_comment_date(), get_comment_time() ); ?>
												</time>
											</div>
										</div>
									</div>

									<div class="reply">
										<?php
										comment_reply_link(
											array_merge(
												$args,
												array(
													'depth'     => $depth,
													'max_depth' => $args['max_depth'],
													'reply_text' => '<i class="icon-arrow-corner-cw-lb lh-1 me-1"></i> ' . esc_html__( 'پاسخ', 'negar-shop' ),
													'add_below' => 'div-comment',
												)
											)
										);
										?>
									</div>
								</footer>

								<div class="comment-content text-secondary f-payda leading-relaxed">
									<?php comment_text(); ?>
								</div>

								<?php if ( '0' == $comment->comment_approved ) : ?>
									<em class="comment-awaiting-moderation badge bg-warning text-dark mt-2"><?php esc_html_e( 'دیدگاه شما پس از تایید مدیریت نمایش داده خواهد شد.', 'negar-shop' ); ?></em>
								<?php endif; ?>
							</article>
						</<?php echo $tag; ?>>
						<?php
					},
				)
			);
			?>
		</ul>

		<?php the_comments_navigation(); ?>

	<?php else : ?>
		<div class="text-center py-4 text-muted">
			<i class="icon-message fs-1 d-block mb-2 text-secondary opacity-50"></i>
			<p class="mb-0 f-payda"><?php esc_html_e( 'هنوز دیدگاهی برای این مطلب ثبت نشده است. اولین نفری باشید که نظر می‌دهد!', 'negar-shop' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments alert alert-secondary small mt-3"><?php esc_html_e( 'ارسال دیدگاه برای این نوشته بسته شده است.', 'negar-shop' ); ?></p>
	<?php endif; ?>

	<div class="comment-form-wrapper mt-5 pt-4 border-top border-light">
		<?php
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$html_req  = ( $req ? " required='required'" : '' );

		comment_form(
			array(
				'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title f-iran-yekan fw-bold fs-5 text-dark mb-3">',
				'title_reply_after'  => '</h4>',
				'class_form'         => 'comment-form',
				'class_submit'       => 'btn btn-danger rounded-pill px-5 py-2.5 fw-bold f-payda mt-3',
				'label_submit'       => esc_html__( 'ارسال دیدگاه', 'negar-shop' ),
				'comment_field'      => '<div class="comment-form-comment mb-3"><label for="comment" class="form-label small text-muted">' . esc_html__( 'متن دیدگاه شما:', 'negar-shop' ) . '</label><textarea id="comment" name="comment" class="form-control rounded-3 p-3" rows="5" required="required" placeholder="' . esc_attr__( 'نظر یا تجربه خود را در مورد این مطلب بنویسید...', 'negar-shop' ) . '"></textarea></div>',
				'fields'             => array(
					'author' => '<div class="row g-3 mb-3"><div class="col-12 col-md-6"><label for="author" class="form-label small text-muted">' . esc_html__( 'نام شما:', 'negar-shop' ) . ( $req ? ' <span class="required text-danger">*</span>' : '' ) . '</label><input id="author" name="author" type="text" class="form-control rounded-3 py-2" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $html_req . ' placeholder="' . esc_attr__( 'مثال: رضا کریمی', 'negar-shop' ) . '"></div>',
					'email'  => '<div class="col-12 col-md-6"><label for="email" class="form-label small text-muted">' . esc_html__( 'ایمیل شما (نمایش داده نمی‌شود):', 'negar-shop' ) . ( $req ? ' <span class="required text-danger">*</span>' : '' ) . '</label><input id="email" name="email" type="email" class="form-control rounded-3 py-2 text-start font-monospace" value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . $html_req . ' placeholder="name@example.com"></div></div>',
				),
			)
		);
		?>
	</div>

</div><!-- #comments -->
