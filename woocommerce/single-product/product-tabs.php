<?php
/**
 * Single Product Tabs Component (Specifications, Description, Reviews, Questions)
 * Clean architecture, zero fake mock data, real Q&A persistence.
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post;

if ( ! $product instanceof WC_Product ) {
	return;
}

$specs = negar_shop_get_product_specs( $product );
?>

<ul class="kav-single-product-tabs d-flex gap-1 p-2 bg-white mb-5 rounded-4 shadow-sm list-unstyled">
	<li class="active d-flex align-items-center gap-2 letter-spacing-2 flex-fill flex-grow-0 flex-shrink-0 cursor-pointer" data-content="#info-product-tab" role="tab" aria-selected="true" tabindex="0">
		<i class="icon-stars-2 lh-1"></i>
		<?php esc_html_e( 'مشخصات', 'negar-shop' ); ?>
	</li>
	<li class="d-flex align-items-center gap-2 letter-spacing-2 flex-fill flex-grow-0 flex-shrink-0 cursor-pointer" data-content="#comments-tab" role="tab" aria-selected="false" tabindex="0">
		<i class="icon-message lh-1"></i>
		<?php esc_html_e( 'دیدگاه‌ها', 'negar-shop' ); ?>
	</li>
	<li class="d-flex align-items-center gap-2 letter-spacing-2 flex-fill flex-grow-0 flex-shrink-0 cursor-pointer" data-content="#questions-tab" role="tab" aria-selected="false" tabindex="0">
		<i class="icon-comments-3 lh-1"></i>
		<?php esc_html_e( 'پرسش و پاسخ', 'negar-shop' ); ?>
	</li>
</ul>

<!-- 1. Specifications Tab Pane -->
<div id="info-product-tab" class="kav-single-product-info-product kav-single-product-tab-content is-active mb-5" role="tabpanel">
	<div class="bg-white p-4 p-md-5 rounded-4 shadow-sm mb-4">
		<h3 class="f-iran-yekan fw-bold fs-5 text-dark mb-4 d-flex align-items-center gap-2">
			<i class="icon-stars-2 text-danger lh-1"></i>
			<?php esc_html_e( 'مشخصات فنی کالا', 'negar-shop' ); ?>
		</h3>

		<div id="kav-single-product-info-product-content" class="overflow-hidden" style="max-height: 464px;">
			<?php if ( ! empty( $specs ) ) : ?>
				<ul class="kav-single-product-info-product-items d-flex flex-column list-unstyled mb-0">
					<?php foreach ( $specs as $spec_item ) :
						$s_label = is_array( $spec_item ) && isset( $spec_item['label'] ) ? $spec_item['label'] : '';
						$s_val   = is_array( $spec_item ) && isset( $spec_item['value'] ) ? $spec_item['value'] : (string) $spec_item;
					?>
						<li class="d-flex align-items-stretch gap-2 gap-md-3 py-2.5 border-bottom border-light">
							<div class="label letter-spacing-2 text-muted small w-25 flex-shrink-0"><?php echo esc_html( $s_label ); ?></div>
							<div class="value letter-spacing-2 text-dark small fw-medium flex-grow-1"><p class="mb-0"><?php echo esc_html( $s_val ); ?></p></div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<div class="p-4 text-muted bg-light rounded-3 text-center"><?php esc_html_e( 'مشخصات فنی برای این کالا ثبت نشده است.', 'negar-shop' ); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $specs ) && count( $specs ) > 5 ) : ?>
			<button type="button" class="kav-single-product-btn-show-more d-flex align-items-center justify-content-center gap-2 fw-normal letter-spacing-4 w-100 bg-white mt-3 pt-2 text-danger border-0" data-kav-show-more="#kav-single-product-info-product-content" data-kav-max-height='{"xs":"420","md":"464"}'>
				<span><?php esc_html_e( 'نمایش بیشتر', 'negar-shop' ); ?></span>
				<span class="d-none"><?php esc_html_e( 'نمایش کمتر', 'negar-shop' ); ?></span>
				<i class="icon-arrow-bottom lh-1"></i>
			</button>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $product->get_description() ) ) : ?>
		<div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
			<h3 class="f-iran-yekan fw-bold fs-5 text-dark mb-4 d-flex align-items-center gap-2">
				<i class="icon-document-text text-danger lh-1"></i>
				<?php esc_html_e( 'معرفی و نقد تخصصی محصول', 'negar-shop' ); ?>
			</h3>
			<div class="kav-single-product-description-content text-secondary lh-lg">
				<?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<!-- 2. Reviews / Comments Tab Pane -->
<div id="comments-tab" class="kav-single-product-comments kav-single-product-tab-content hidden mb-5" role="tabpanel">
	<div class="kav-reviews2" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
		<div class="row g-4">
			
			<!-- Left/Primary: Reviews List -->
			<div class="col-lg-8 order-2 order-lg-1">
				<div class="kav-reviews2-list-wrap">
					<ul class="kav-single-product-comments-items d-flex flex-column gap-3 list-unstyled mb-0" id="kav-reviews2-list">
						<?php
						$comments = get_comments( array(
							'post_id' => $product->get_id(),
							'status'  => 'approve',
							'type'    => 'review',
						) );
						if ( empty( $comments ) ) {
							$comments = get_comments( array(
								'post_id' => $product->get_id(),
								'status'  => 'approve',
								'type'    => 'comment',
							) );
						}

						if ( ! empty( $comments ) ) :
							foreach ( $comments as $comm ) :
								$rating      = (float) get_comment_meta( $comm->comment_ID, 'rating', true );
								$is_verified = (bool) get_comment_meta( $comm->comment_ID, 'verified', true );
								$pros        = get_comment_meta( $comm->comment_ID, 'pros', true );
								$cons        = get_comment_meta( $comm->comment_ID, 'cons', true );
								if ( ! is_array( $pros ) ) {
									$pros = ! empty( $pros ) ? (array) maybe_unserialize( $pros ) : array();
								}
								if ( ! is_array( $cons ) ) {
									$cons = ! empty( $cons ) ? (array) maybe_unserialize( $cons ) : array();
								}
						?>
								<li id="comment-<?php echo esc_attr( $comm->comment_ID ); ?>" class="kav-reviews2-item bg-white p-4 rounded-4 shadow-sm border border-slate-100">
									<div class="wrapper">
										<div class="content">
											<div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-2">
												<div class="date d-flex align-items-center gap-1 text-muted small">
													<i class="icon-calendar-dates lh-1"></i>
													<?php echo esc_html( get_comment_date( 'j F Y', $comm ) ); ?>
												</div>
												<?php if ( $rating > 0 ) : ?>
													<div class="rate d-flex align-items-center gap-1 text-warning fw-bold small bg-amber-50 px-2 py-0.5 rounded-full">
														<i class="icon-star-5 lh-1"></i>
														<?php echo esc_html( number_format( $rating, 1, ',', '' ) ); ?>
													</div>
												<?php endif; ?>
											</div>

											<p class="fw-normal letter-spacing-4 my-3 text-dark"><?php echo esc_html( $comm->comment_content ); ?></p>

											<?php if ( ! empty( $pros ) || ! empty( $cons ) ) : ?>
												<ul class="strengths-weaknesses d-flex flex-column gap-1 p-0 mb-3 list-unstyled">
													<?php foreach ( $pros as $pro_item ) : if ( trim( $pro_item ) === '' ) continue; ?>
														<li class="d-flex align-items-start small text-success">
															<i class="icon-check-3 lh-1 me-1 mt-1"></i>
															<span><?php echo esc_html( $pro_item ); ?></span>
														</li>
													<?php endforeach; ?>
													<?php foreach ( $cons as $con_item ) : if ( trim( $con_item ) === '' ) continue; ?>
														<li class="d-flex align-items-start small text-danger">
															<i class="icon-cross lh-1 me-1 mt-1"></i>
															<span><?php echo esc_html( $con_item ); ?></span>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</div>

										<div class="info d-flex align-items-center justify-content-between gap-2 pt-2 border-top border-light">
											<div class="info-user d-flex align-items-center gap-2">
												<div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">
													<?php echo esc_html( mb_substr( $comm->comment_author, 0, 1, 'UTF-8' ) ); ?>
												</div>
												<div class="name small fw-medium text-slate-800"><?php echo esc_html( $comm->comment_author ); ?></div>
												<span class="badge <?php echo $is_verified ? 'bg-success-subtle text-success' : 'bg-slate-100 text-slate-600'; ?> text-3xs">
													<?php echo $is_verified ? esc_html__( 'خریدار محصول', 'negar-shop' ) : esc_html__( 'کاربر', 'negar-shop' ); ?>
												</span>
											</div>

											<div class="icons d-flex align-items-center gap-2">
												<button type="button" class="btn btn-sm btn-light py-1 px-2 text-slate-600 text-xs d-flex align-items-center gap-1" data-kav-vote="review" data-id="<?php echo esc_attr( $comm->comment_ID ); ?>" data-vote-type="like">
													<i class="icon-thumbs-up-3 lh-1"></i>
													<span class="kav-vote-count"><?php echo esc_html( (int) get_comment_meta( $comm->comment_ID, '_kav_likes', true ) ); ?></span>
												</button>
												<button type="button" class="btn btn-sm btn-light py-1 px-2 text-slate-600 text-xs d-flex align-items-center gap-1" data-kav-vote="review" data-id="<?php echo esc_attr( $comm->comment_ID ); ?>" data-vote-type="dislike">
													<i class="icon-thumbs-down-3 lh-1"></i>
													<span class="kav-vote-count"><?php echo esc_html( (int) get_comment_meta( $comm->comment_ID, '_kav_dislikes', true ) ); ?></span>
												</button>
											</div>
										</div>
									</div>
								</li>
						<?php endforeach; else : ?>
							<li class="p-5 bg-white rounded-4 shadow-sm text-center text-muted">
								<div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
									<i class="icon-message fs-4"></i>
								</div>
								<p class="mb-0 fw-medium"><?php esc_html_e( 'هنوز دیدگاهی برای این کالا ثبت نشده است. اولین نفری باشید که تجربه خود را به اشتراک می‌گذارد!', 'negar-shop' ); ?></p>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>

			<!-- Right Sidebar: Rating Stats & Add Review Trigger -->
			<div class="col-lg-4 order-1 order-lg-2">
				<div class="bg-white p-4 rounded-4 shadow-sm sticky-top" style="top: 100px;">
					<?php
					$actual_rating  = (float) $product->get_average_rating();
					$actual_reviews = (int) $product->get_review_count();
					?>
					<div class="text-center pb-4 border-bottom border-light mb-4">
						<div class="d-flex align-items-center justify-content-center gap-2 mb-1">
							<span class="fs-1 font-mono fw-black text-slate-900"><?php echo esc_html( number_format( $actual_rating, 1, ',', '' ) ); ?></span>
							<span class="text-muted fs-6">/ ۵</span>
							<div class="text-warning fs-5">
								<i class="icon-star-6 lh-1"></i>
							</div>
						</div>
						<div class="text-muted small">
							<?php if ( $actual_reviews > 0 ) : ?>
								<span><?php echo esc_html( $actual_reviews ); ?></span> <?php esc_html_e( 'دیدگاه ثبت شده کاربران', 'negar-shop' ); ?>
							<?php else : ?>
								<?php esc_html_e( 'هنوز امتیازی ثبت نشده است', 'negar-shop' ); ?>
							<?php endif; ?>
						</div>
					</div>

					<button type="button" class="btn btn-danger-6 w-100 py-2.5 rounded-3 fw-bold f-payda" data-bs-toggle="modal" data-bs-target="#modal-kav-add-camment">
						<i class="icon-edit-3 me-1"></i>
						<?php esc_html_e( 'ثبت دیدگاه جدید', 'negar-shop' ); ?>
					</button>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- 3. Questions Tab Pane -->
<div id="questions-tab" class="kav-single-product-questions kav-single-product-tab-content hidden mb-5" role="tabpanel">
	<div class="kav-reviews2" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
		<div class="row g-4">
			
			<!-- Left: Questions List -->
			<div class="col-lg-8 order-2 order-lg-1">
				<div class="kav-reviews2-list-wrap">
					<ul class="kav-single-product-questions-items d-flex flex-column gap-3 list-unstyled mb-0" id="kav-questions2-list">
						<?php
						$persisted_questions = function_exists( 'negar_get_product_questions' ) ? negar_get_product_questions( $product->get_id() ) : array();

						if ( ! empty( $persisted_questions ) ) :
							foreach ( $persisted_questions as $q_item ) :
								$q_id       = $q_item['id'];
								$q_author   = $q_item['author'];
								$q_status   = $q_item['status'];
								$q_text     = $q_item['question'];
								$q_answers  = $q_item['answers'];
								$q_likes    = (int) $q_item['likes'];
								$q_dislikes = (int) $q_item['dislikes'];
								$q_date     = $q_item['date'];
							?>
								<li class="kav-questions2-item bg-white p-4 rounded-4 shadow-sm border border-slate-100" data-question-id="<?php echo esc_attr( $q_id ); ?>">
									<div class="content">
										<div class="d-flex align-items-center justify-content-between gap-2 mb-2">
											<div class="author d-flex align-items-center gap-2">
												<span class="name fw-bold text-slate-800 small"><?php echo esc_html( $q_author ); ?></span>
												<span class="badge <?php echo ( 'buyer' === $q_status ) ? 'bg-success-subtle text-success' : 'bg-slate-100 text-slate-600'; ?> text-3xs">
													<?php echo ( 'buyer' === $q_status ) ? esc_html__( 'خریدار', 'negar-shop' ) : esc_html__( 'کاربر', 'negar-shop' ); ?>
												</span>
											</div>
											<span class="text-muted text-xs"><?php echo esc_html( $q_date ); ?></span>
										</div>

										<p class="text-slate-800 my-2 fw-medium"><?php echo esc_html( $q_text ); ?></p>

										<div class="d-flex align-items-center justify-content-end gap-2 pt-2">
											<button type="button" class="btn btn-sm btn-light py-1 px-2 text-slate-600 text-xs d-flex align-items-center gap-1" data-kav-vote="qa" data-id="<?php echo esc_attr( $q_id ); ?>" data-vote-type="like">
												<i class="icon-thumbs-up-3 lh-1"></i>
												<span class="kav-vote-count"><?php echo esc_html( $q_likes ); ?></span>
											</button>
											<button type="button" class="btn btn-sm btn-light py-1 px-2 text-slate-600 text-xs d-flex align-items-center gap-1" data-kav-vote="qa" data-id="<?php echo esc_attr( $q_id ); ?>" data-vote-type="dislike">
												<i class="icon-thumbs-down-3 lh-1"></i>
												<span class="kav-vote-count"><?php echo esc_html( $q_dislikes ); ?></span>
											</button>
										</div>
									</div>

									<?php if ( ! empty( $q_answers ) ) : ?>
										<ul class="replys list-unstyled d-flex flex-column gap-2 mt-3 pt-3 border-top border-light">
											<?php foreach ( $q_answers as $ans ) : ?>
												<li class="bg-slate-50 p-3 rounded-3 border border-slate-100">
													<div class="author d-flex align-items-center gap-2 mb-1">
														<span class="name fw-bold small text-rose-600"><?php echo esc_html( $ans['author'] ); ?></span>
														<span class="badge bg-rose-100 text-rose-700 text-3xs"><?php esc_html_e( 'پاسخ کارشناس', 'negar-shop' ); ?></span>
													</div>
													<p class="text-secondary mb-0 small"><?php echo esc_html( $ans['text'] ); ?></p>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</li>
							<?php endforeach; else : ?>
							<li class="p-5 bg-white rounded-4 shadow-sm text-center text-muted" id="kav-no-questions-notice">
								<div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
									<i class="icon-comments-3 fs-4"></i>
								</div>
								<p class="mb-0 fw-medium"><?php esc_html_e( 'هنوز پرسشی برای این کالا ثبت نشده است. اگر سوالی در مورد مشخصات، اصالت یا نحوه استفاده دارید، اولین نفری باشید که سوال می‌پرسد!', 'negar-shop' ); ?></p>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>

			<!-- Right: Add Question Box -->
			<div class="col-lg-4 order-1 order-lg-2">
				<div class="bg-white p-4 rounded-4 shadow-sm sticky-top" style="top: 100px;">
					<div class="title fw-bold text-slate-900 fs-5 mb-2"><?php esc_html_e( 'پرسش درباره این محصول', 'negar-shop' ); ?></div>
					<p class="text-muted small mb-4"><?php esc_html_e( 'پرسش خود را مطرح کنید تا کارشناسان فروشگاه یا خریداران دیگر در کوتاه‌ترین زمان به آن پاسخ دهند.', 'negar-shop' ); ?></p>
					<button type="button" class="btn btn-danger-6 w-100 py-2.5 rounded-3 fw-bold f-payda" data-bs-toggle="modal" data-bs-target="#modal-kav-add-question">
						<i class="icon-edit-3 me-1"></i>
						<?php esc_html_e( 'ثبت پرسش جدید', 'negar-shop' ); ?>
					</button>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Modal: Add Review -->
<div class="modal fade" id="modal-kav-add-camment" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 rounded-4 shadow-lg p-3 text-right">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title fw-bold"><?php esc_html_e( 'ثبت دیدگاه جدید', 'negar-shop' ); ?></h5>
				<button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="kav-comment-form" action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>" method="post">
					<input type="hidden" name="comment_post_ID" value="<?php echo esc_attr( $product->get_id() ); ?>" />
					<input type="hidden" name="comment_parent" value="0" />
					
					<div class="mb-3">
						<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'امتیاز شما به محصول', 'negar-shop' ); ?></label>
						<select name="rating" class="form-select rounded-3">
							<option value="5">۵ ستاره (عالی)</option>
							<option value="4">۴ ستاره (خیلی خوب)</option>
							<option value="3">۳ ستاره (متوسط)</option>
							<option value="2">۲ ستاره (ضعیف)</option>
							<option value="1">۱ ستاره (خیلی ضعیف)</option>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'متن نظر شما', 'negar-shop' ); ?></label>
						<textarea name="comment" class="form-control rounded-3" rows="4" placeholder="<?php esc_attr_e( 'تجربه خود را درباره استفاده از این کالا بنویسید...', 'negar-shop' ); ?>" required></textarea>
					</div>
					<?php if ( ! is_user_logged_in() ) : ?>
						<div class="row g-2 mb-3">
							<div class="col-6">
								<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'نام شما', 'negar-shop' ); ?></label>
								<input type="text" name="author" class="form-control rounded-3" required />
							</div>
							<div class="col-6">
								<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'ایمیل شما', 'negar-shop' ); ?></label>
								<input type="email" name="email" class="form-control rounded-3" required />
							</div>
						</div>
					<?php endif; ?>
					<button type="submit" class="btn btn-danger-6 w-100 fw-bold py-2.5 rounded-3 mt-2">
						<?php esc_html_e( 'ارسال و ثبت نظر', 'negar-shop' ); ?>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal: Add Question (With Real AJAX Persistence) -->
<div class="modal fade" id="modal-kav-add-question" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 rounded-4 shadow-lg p-3 text-right">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title fw-bold"><?php esc_html_e( 'ثبت پرسش جدید', 'negar-shop' ); ?></h5>
				<button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="kav-question-form" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
					<div class="mb-3">
						<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'متن پرسش شما درباره محصول', 'negar-shop' ); ?></label>
						<textarea id="kav-qa-text" class="form-control rounded-3" rows="4" placeholder="<?php esc_attr_e( 'سوال خود را دقیق مطرح کنید...', 'negar-shop' ); ?>" required></textarea>
					</div>
					<?php if ( ! is_user_logged_in() ) : ?>
						<div class="mb-3">
							<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'نام شما', 'negar-shop' ); ?></label>
							<input type="text" id="kav-qa-author" class="form-control rounded-3" placeholder="<?php esc_attr_e( 'مثال: علیرضا', 'negar-shop' ); ?>" required />
						</div>
					<?php endif; ?>
					<div id="kav-qa-msg" class="hidden mb-3 text-xs p-2.5 rounded-3 font-medium"></div>
					<button type="submit" id="kav-qa-submit" class="btn btn-danger-6 w-100 fw-bold py-2.5 rounded-3 mt-2">
						<span><?php esc_html_e( 'ارسال و ثبت پرسش', 'negar-shop' ); ?></span>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
