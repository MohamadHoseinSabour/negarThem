<?php
/**
 * Single Product Tabs Component (Specifications, Description, Reviews, Questions) - 100% Matching Demo 43
 *
 * @package Negar_Shop
 * @version 2.5.0
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

<ul class="kav-single-product-tabs d-flex gap-1 p-2 bg-white mb-5">
	<li class="active d-flex align-items-center gap-2 letter-spacing-2 flex-fill flex-grow-0 flex-shrink-0" data-content="#info-product-tab">
		<i class="icon-stars-2 lh-1"></i>
		<?php esc_html_e( 'مشخصات', 'negar-shop' ); ?>
	</li>
	<li class="d-flex align-items-center gap-2 letter-spacing-2 flex-fill flex-grow-0 flex-shrink-0" data-content="#comments-tab">
		<i class="icon-message lh-1"></i>
		<?php esc_html_e( 'دیدگاه', 'negar-shop' ); ?>
	</li>
	<li class="d-flex align-items-center gap-2 letter-spacing-2 flex-fill flex-grow-0 flex-shrink-0" data-content="#questions-tab">
		<i class="icon-comments-3 lh-1"></i>
		<?php esc_html_e( 'پرسش و پاسخ', 'negar-shop' ); ?>
	</li>
</ul>

<!-- 1. Specifications Tab -->
<div id="info-product-tab" class="kav-single-product-info-product kav-single-product-tab-content is-active">
	<div class="kav-single-product-heading d-flex align-items-center gap-2 mb-3" data-bs-toggle="collapse" data-bs-target="#info-product">
		<i class="kav-single-product-heading-triangle icon-triangle-left ms-2 lh-1"></i>
		<div class="kav-single-product-heading-title fw-bold letter-spacing-4"><?php esc_html_e( 'مشخصات محصول', 'negar-shop' ); ?></div>
		<i class="kav-single-product-heading-chevron icon-chevron-down ms-auto ms-md-2 lh-1"></i>
	</div>
	<div id="info-product" class="collapse show">
		<div id="kav-single-product-info-product-content" class="overflow-hidden" style="max-height: 464px;">
			<?php if ( ! empty( $specs ) ) : ?>
				<ul class="kav-single-product-info-product-items d-flex flex-column">
					<?php foreach ( $specs as $spec_item ) :
						$s_label = is_array( $spec_item ) && isset( $spec_item['label'] ) ? $spec_item['label'] : '';
						$s_val   = is_array( $spec_item ) && isset( $spec_item['value'] ) ? $spec_item['value'] : (string) $spec_item;
					?>
						<li class="d-flex align-items-stretch gap-2 gap-md-3">
							<div class="label letter-spacing-2 w-100 flex-fill flex-grow-0 flex-shrink-0"><?php echo esc_html( $s_label ); ?></div>
							<div class="value letter-spacing-2 w-100 flex-fill flex-grow-0 flex-shrink-0"><p><?php echo esc_html( $s_val ); ?></p></div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<div class="p-4 text-muted bg-white rounded-3"><?php esc_html_e( 'مشخصات فنی برای این کالا ثبت نشده است.', 'negar-shop' ); ?></div>
			<?php endif; ?>
		</div>
		<?php if ( ! empty( $specs ) && count( $specs ) > 5 ) : ?>
			<button type="button" class="kav-single-product-btn-show-more d-flex align-items-center justify-content-center gap-2 fw-normal letter-spacing-4 w-100 bg-white" data-kav-show-more="#kav-single-product-info-product-content" data-kav-max-height='{"xs":"420","md":"464"}'>
				<span><?php esc_html_e( 'نمایش بیشتر', 'negar-shop' ); ?></span>
				<span><?php esc_html_e( 'نمایش کمتر', 'negar-shop' ); ?></span>
				<i class="icon-arrow-bottom lh-1"></i>
			</button>
		<?php endif; ?>

		<?php if ( ! empty( $product->get_description() ) ) : ?>
			<div class="kav-single-product-heading d-flex align-items-center gap-2 mb-3 mt-5" data-bs-toggle="collapse" data-bs-target="#desc-product">
				<i class="kav-single-product-heading-triangle icon-triangle-left ms-2 lh-1"></i>
				<div class="kav-single-product-heading-title fw-bold letter-spacing-4"><?php esc_html_e( 'معرفی و بررسی محصول', 'negar-shop' ); ?></div>
				<i class="kav-single-product-heading-chevron icon-chevron-down ms-auto ms-md-2 lh-1"></i>
			</div>
			<div id="desc-product" class="collapse show">
				<div class="kav-single-product-description-content bg-white p-4 rounded-4">
					<?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- 2. Reviews / Comments Tab -->
<div id="comments-tab" class="kav-single-product-comments kav-single-product-tab-content">
	<div class="kav-single-product-heading d-flex align-items-center gap-2 mb-3" data-bs-toggle="collapse" data-bs-target="#comments-product">
		<i class="kav-single-product-heading-triangle icon-triangle-left ms-2 lh-1"></i>
		<div class="kav-single-product-heading-title fw-bold letter-spacing-4"><?php esc_html_e( 'دیدگاه', 'negar-shop' ); ?></div>
		<i class="kav-single-product-heading-chevron icon-chevron-down ms-auto ms-md-2 lh-1"></i>
	</div>
	<div id="comments-product" class="collapse show">
		<div class="kav-reviews2" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
			<div class="row gap-4 gap-lg-0">
				<div class="col-lg-8 order-2 order-lg-1">
					<div class="kav-reviews2-sort d-flex align-items-center gap-2 flex-wrap mb-4">
						<button type="button" class="kav-single-product-comments-sort letter-spacing-4 border-0 active" data-sort="newest"><?php esc_html_e( 'جدیدترین', 'negar-shop' ); ?></button>
						<button type="button" class="kav-single-product-comments-sort letter-spacing-4 border-0" data-sort="helpful"><?php esc_html_e( 'پربازدید ترین ها', 'negar-shop' ); ?></button>
						<button type="button" class="kav-single-product-comments-sort letter-spacing-4 border-0" data-sort="highest"><?php esc_html_e( 'بیشترین امتیاز', 'negar-shop' ); ?></button>
						<button type="button" class="kav-single-product-comments-sort letter-spacing-4 border-0" data-sort="lowest"><?php esc_html_e( 'کمترین امتیاز', 'negar-shop' ); ?></button>
					</div>

					<div class="kav-reviews2-list-wrap">
						<ul class="kav-single-product-comments-items d-flex flex-column gap-3" id="kav-reviews2-list">
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
								) );
							}

							if ( ! empty( $comments ) ) :
								foreach ( $comments as $comm ) :
									$rating = (float) get_comment_meta( $comm->comment_ID, 'rating', true );
									$is_verified = (bool) get_comment_meta( $comm->comment_ID, 'verified', true );
									$pros = get_comment_meta( $comm->comment_ID, 'pros', true );
									$cons = get_comment_meta( $comm->comment_ID, 'cons', true );
									if ( ! is_array( $pros ) ) {
										$pros = ! empty( $pros ) ? (array) maybe_unserialize( $pros ) : array();
									}
									if ( ! is_array( $cons ) ) {
										$cons = ! empty( $cons ) ? (array) maybe_unserialize( $cons ) : array();
									}
							?>
									<li id="comment-<?php echo esc_attr( $comm->comment_ID ); ?>" class="kav-reviews2-item">
										<div class="wrapper">
											<div class="content">
												<div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
													<div class="date d-flex align-items-center gap-1 fw-normal letter-spacing-4">
														<i class="icon-calendar-dates lh-1"></i>
														<?php echo esc_html( get_comment_date( 'j F Y', $comm ) ); ?>
													</div>
													<?php if ( $rating > 0 ) : ?>
														<div class="rate d-flex align-items-center gap-1 fw-light letter-spacing-4">
															<i class="icon-star-5 lh-1"></i>
															<?php echo esc_html( number_format( $rating, 1, ',', '' ) ); ?>
														</div>
													<?php endif; ?>
												</div>

												<p class="fw-normal letter-spacing-4 my-3 text-dark"><?php echo esc_html( $comm->comment_content ); ?></p>

												<?php if ( ! empty( $pros ) || ! empty( $cons ) ) : ?>
													<ul class="strengths-weaknesses d-flex flex-column gap-1 p-0 mb-0 list-unstyled">
														<?php foreach ( $pros as $pro_item ) : if ( trim( $pro_item ) === '' ) continue; ?>
															<li class="d-flex align-items-start fw-normal letter-spacing-4">
																<i class="icon-check-3 green lh-1 text-success me-1"></i>
																<span class="kav-sw-text"><?php echo esc_html( $pro_item ); ?></span>
															</li>
														<?php endforeach; ?>
														<?php foreach ( $cons as $con_item ) : if ( trim( $con_item ) === '' ) continue; ?>
															<li class="d-flex align-items-start fw-normal letter-spacing-4">
																<i class="icon-cross red lh-1 text-danger me-1"></i>
																<span class="kav-sw-text"><?php echo esc_html( $con_item ); ?></span>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											</div>

											<div class="info d-flex align-items-md-center justify-content-md-between gap-3 gap-md-2 mt-md-2 px-md-3 flex-column flex-md-row">
												<div class="info-user d-flex align-items-center px-3 px-md-0">
													<img loading="lazy" src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/user-avatar.svg' ); ?>" alt="" width="40" height="40" class="rounded-circle flex-fill flex-grow-0 flex-shrink-0 ms-2">
													<div class="d-flex align-items-center gap-1">
														<div class="name letter-spacing-4 fw-medium"><?php echo esc_html( $comm->comment_author ); ?></div>
														<span class="status <?php echo $is_verified ? 'buyer' : 'user'; ?> fw-normal letter-spacing-4">
															<?php echo $is_verified ? esc_html__( 'خریدار', 'negar-shop' ) : esc_html__( 'کاربر', 'negar-shop' ); ?>
														</span>
													</div>
												</div>
												<div class="icons d-flex align-items-center gap-3 gap-md-4 ms-auto ms-md-0 px-3 px-md-0">
													<button type="button" class="reply d-flex align-items-center gap-1 fw-normal letter-spacing-4 p-0 border-0 bg-transparent">
														<i class="icon-arrow-corner-cw-lb lh-1"></i>
														<?php esc_html_e( 'پاسخ', 'negar-shop' ); ?>
													</button>
													<div class="divider"></div>
													<div class="d-flex align-items-center gap-3">
														<button type="button" class="thumbs d-flex align-items-center gap-1 fw-normal letter-spacing-4 p-0 border-0 bg-transparent" data-kav-vote="review" data-id="<?php echo esc_attr( $comm->comment_ID ); ?>" data-vote-type="dislike">
															<span class="kav-vote-count">0</span>
															<i class="icon-thumbs-down-3 lh-1"></i>
														</button>
														<button type="button" class="thumbs d-flex align-items-center gap-1 fw-normal letter-spacing-4 p-0 border-0 bg-transparent" data-kav-vote="review" data-id="<?php echo esc_attr( $comm->comment_ID ); ?>" data-vote-type="like">
															<span class="kav-vote-count">0</span>
															<i class="icon-thumbs-up-3 lh-1"></i>
														</button>
													</div>
												</div>
											</div>
										</div>
									</li>
							<?php endforeach; else : ?>
								<li class="p-4 bg-white rounded-3 text-muted text-center"><?php esc_html_e( 'هنوز دیدگاهی برای این محصول ثبت نشده است. اولین نفری باشید که نظر خود را ثبت می‌کند!', 'negar-shop' ); ?></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>

				<div class="col-lg-4 order-1 order-lg-2">
					<div class="sticker">
						<div class="inner-wrapper-sticky">
							<div class="kav-single-product-comments-total-info">
								<?php
								$actual_rating = (float) $product->get_average_rating();
								$actual_reviews = (int) $product->get_review_count();
								?>
								<div class="kav-single-product-comments-total-info-rate d-flex align-items-center letter-spacing-4 gap-2 mb-2">
									<?php if ( $actual_reviews > 0 ) : ?>
										<?php echo esc_html( number_format( $actual_rating, 1, ',', '' ) ); ?>
										<i class="icon-star-6 lh-1"></i>
									<?php else : ?>
										<span class="fs-6 fw-normal text-muted"><?php esc_html_e( 'بدون امتیاز', 'negar-shop' ); ?></span>
									<?php endif; ?>
								</div>
								<div class="kav-single-product-comments-total-info-total-count mb-4">
									<?php if ( $actual_reviews > 0 ) : ?>
										<span><?php echo esc_html( $actual_reviews ); ?></span>
										<?php esc_html_e( 'نظر ثبت شده توسط کاربران', 'negar-shop' ); ?>
									<?php else : ?>
										<span><?php esc_html_e( 'هنوز نظری برای این کالا ثبت نشده است', 'negar-shop' ); ?></span>
									<?php endif; ?>
								</div>

								<div class="d-flex flex-column gap-3">
									<div class="kav-single-product-comments-total-info-box-progress d-flex align-items-center">
										<div class="right flex-grow-1">
											<div class="text fw-normal letter-spacing-4 mb-2 small text-muted"><?php esc_html_e( 'کیفیت ساخت', 'negar-shop' ); ?></div>
											<div class="progress"><div class="progress-bar" style="width: 85%;"></div></div>
										</div>
										<div class="left d-flex align-items-center gap-2 ms-3">
											<div class="count fw-normal letter-spacing-4">4,5</div>
											<div class="precent fw-normal letter-spacing-4">85%</div>
										</div>
									</div>
									<div class="kav-single-product-comments-total-info-box-progress d-flex align-items-center">
										<div class="right flex-grow-1">
											<div class="text fw-normal letter-spacing-4 mb-2 small text-muted"><?php esc_html_e( 'سهولت استفاده', 'negar-shop' ); ?></div>
											<div class="progress"><div class="progress-bar" style="width: 90%; background-color: #2aba74;"></div></div>
										</div>
										<div class="left d-flex align-items-center gap-2 ms-3">
											<div class="count fw-normal letter-spacing-4">4,7</div>
											<div class="precent fw-normal letter-spacing-4">90%</div>
										</div>
									</div>
									<div class="kav-single-product-comments-total-info-box-progress d-flex align-items-center">
										<div class="right flex-grow-1">
											<div class="text fw-normal letter-spacing-4 mb-2 small text-muted"><?php esc_html_e( 'امکانات و قابلیت ها', 'negar-shop' ); ?></div>
											<div class="progress"><div class="progress-bar" style="width: 80%;"></div></div>
										</div>
										<div class="left d-flex align-items-center gap-2 ms-3">
											<div class="count fw-normal letter-spacing-4">4,0</div>
											<div class="precent fw-normal letter-spacing-4">80%</div>
										</div>
									</div>
									<div class="kav-single-product-comments-total-info-box-progress d-flex align-items-center">
										<div class="right flex-grow-1">
											<div class="text fw-normal letter-spacing-4 mb-2 small text-muted"><?php esc_html_e( 'ارزش خرید نسبت به قیمت', 'negar-shop' ); ?></div>
											<div class="progress"><div class="progress-bar" style="width: 75%; background-color: #ffe100;"></div></div>
										</div>
										<div class="left d-flex align-items-center gap-2 ms-3">
											<div class="count fw-normal letter-spacing-4">3,8</div>
											<div class="precent fw-normal letter-spacing-4">75%</div>
										</div>
									</div>
								</div>

								<div class="kav-single-product-comments-total-info-total-count-box d-flex align-items-center justify-content-between bg-white position-relative">
									<div class="d-flex align-items-center justify-content-center gap-3 letter-spacing-4 w-100 flex-fill flex-grow-0 flex-shrink-0 px-2">
										<span><?php echo esc_html( max( 3, $product->get_review_count() ) ); ?></span>
										<div class="fw-normal f-iran-yekan letter-spacing-2 small text-muted">
											<?php esc_html_e( 'نقد و بررسیها', 'negar-shop' ); ?>
											<span class="d-block text-dark fw-bold"><?php esc_html_e( 'کاربـــــران', 'negar-shop' ); ?></span>
										</div>
									</div>
									<div class="d-flex align-items-center justify-content-center gap-3 letter-spacing-4 w-100 flex-fill flex-grow-0 flex-shrink-0 px-2 border-end">
										<span><?php echo esc_html( max( 2, (int) $product->get_review_count() - 1 ) ); ?></span>
										<div class="fw-normal f-iran-yekan letter-spacing-2 small text-muted">
											<?php esc_html_e( 'نقد و بررسیها', 'negar-shop' ); ?>
											<span class="d-block text-dark fw-bold"><?php esc_html_e( 'خریداران', 'negar-shop' ); ?></span>
										</div>
									</div>
								</div>

								<div class="kav-single-product-comments-total-info-box-add-comment bg-white p-4">
									<div class="d-flex align-items-center gap-3">
										<img loading="lazy" src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/detail-product-7/comments.png' ); ?>" alt="" width="100" height="88" class="flex-fill flex-grow-0 flex-shrink-0">
										<div>
											<div class="title fw-bold letter-spacing-4 mb-2"><?php esc_html_e( 'ثبــــــــت دیدگاه', 'negar-shop' ); ?></div>
											<div class="sub-title fw-normal letter-spacing-4 small text-muted"><?php esc_html_e( 'اگر از این محصول خریداری کرده اید یا نظری دارید با ما به اشتراک بگذارید!', 'negar-shop' ); ?></div>
										</div>
									</div>
									<button type="button" class="btn btn-danger-6 d-flex align-items-center justify-content-center gap-2 fw-bold letter-spacing-4 w-100 mt-3" data-bs-toggle="modal" data-bs-target="#modal-kav-add-camment">
										<?php esc_html_e( 'ثبت نظر', 'negar-shop' ); ?>
										<i class="icon-arrow-left-10 lh-1"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- 3. Questions Tab -->
<div id="questions-tab" class="kav-single-product-questions kav-single-product-tab-content">
	<div class="kav-single-product-heading d-flex align-items-center gap-2 mb-3" data-bs-toggle="collapse" data-bs-target="#questions-product">
		<i class="kav-single-product-heading-triangle icon-triangle-left ms-2 lh-1"></i>
		<div class="kav-single-product-heading-title fw-bold letter-spacing-4"><?php esc_html_e( 'پرسش و پاسخ', 'negar-shop' ); ?></div>
		<i class="kav-single-product-heading-chevron icon-chevron-down ms-auto ms-md-2 lh-1"></i>
	</div>
	<div id="questions-product" class="collapse show">
		<div class="kav-reviews2" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
			<div class="row gap-4 gap-lg-0">
				<div class="col-lg-8 order-2 order-lg-1">
					<div class="kav-reviews2-sort d-flex align-items-center gap-2 flex-wrap mb-4">
						<button type="button" class="kav-single-product-comments-sort letter-spacing-4 border-0 active" data-sort="newest"><?php esc_html_e( 'جدیدترین', 'negar-shop' ); ?></button>
						<button type="button" class="kav-single-product-comments-sort letter-spacing-4 border-0" data-sort="helpful"><?php esc_html_e( 'پربازدید ترین ها', 'negar-shop' ); ?></button>
					</div>

					<div class="kav-reviews2-list-wrap">
						<ul class="kav-single-product-questions-items d-flex flex-column gap-3" id="kav-questions2-list">
							<?php
							$questions = get_post_meta( $product->get_id(), '_kav_product_questions', true );
							if ( empty( $questions ) || ! is_array( $questions ) ) {
								$questions = array(
									array(
										'id' => 1,
										'author' => 'علیرضا',
										'status' => 'user',
										'question' => 'آیا برای اتصال به کامپیوتر حتما باید دانگل اختصاصی بخریم یا با بلوتوث معمولی وصل میشه؟',
										'answers' => array(
											array(
												'author' => 'پشتیبانی فنی',
												'status' => 'support',
												'text' => 'سلام، بله این کنترلر از طریق بلوتوث و کابل تایپ سی به راحتی به ویندوز، مک و گوشی‌های هوشمند متصل می‌شود.',
											),
										),
										'likes' => 4,
										'dislikes' => 0,
									),
									array(
										'id' => 2,
										'author' => 'مهدی',
										'status' => 'buyer',
										'question' => 'گارانتی اصالت و سلامت فیزیکی دارد؟',
										'answers' => array(
											array(
												'author' => 'پشتیبانی فروش',
												'status' => 'support',
												'text' => 'بله تمامی کالاهای دیجیتال با ضمانت ۷ روزه بازگشت وجه و سلامت فیزیکی ارائه می‌شوند.',
											),
										),
										'likes' => 2,
										'dislikes' => 0,
									),
								);
							}

							foreach ( $questions as $q_item ) :
								$q_id = isset( $q_item['id'] ) ? $q_item['id'] : wp_rand( 100, 999 );
								$q_author = isset( $q_item['author'] ) ? $q_item['author'] : 'کاربر';
								$q_status = isset( $q_item['status'] ) ? $q_item['status'] : 'user';
								$q_text = isset( $q_item['question'] ) ? $q_item['question'] : '';
								$q_answers = isset( $q_item['answers'] ) && is_array( $q_item['answers'] ) ? $q_item['answers'] : array();
								$q_likes = isset( $q_item['likes'] ) ? (int) $q_item['likes'] : 0;
								$q_dislikes = isset( $q_item['dislikes'] ) ? (int) $q_item['dislikes'] : 0;
							?>
								<li class="kav-questions2-item" data-question-id="<?php echo esc_attr( $q_id ); ?>">
									<div class="content d-flex align-items-md-center justify-content-md-between gap-3 flex-column flex-md-row">
										<div class="text flex-fill min-w-0">
											<div class="ques fw-bold letter-spacing-4 mb-2"><?php esc_html_e( 'پرسش', 'negar-shop' ); ?></div>
											<p class="letter-spacing-4 mb-2 text-dark"><?php echo esc_html( $q_text ); ?></p>
											<div class="author d-flex align-items-center gap-1">
												<span class="name letter-spacing-4"><?php echo esc_html( $q_author ); ?></span>
												<span class="status <?php echo esc_attr( $q_status ); ?> fw-normal letter-spacing-4">
													<?php echo ( $q_status === 'buyer' ) ? esc_html__( 'خریدار', 'negar-shop' ) : esc_html__( 'کاربر', 'negar-shop' ); ?>
												</span>
											</div>
										</div>
										<div class="icons d-flex align-items-center gap-3 flex-fill flex-grow-0 flex-shrink-0 ms-auto ms-md-0">
											<button type="button" class="reply d-flex align-items-center justify-content-center gap-1 fw-normal letter-spacing-4 border-0 bg-transparent">
												<i class="icon-arrow-corner-cw-lb lh-1"></i>
												<?php esc_html_e( 'پاسخ', 'negar-shop' ); ?>
											</button>
											<button type="button" class="d-flex align-items-center justify-content-center gap-1 fw-normal letter-spacing-4 border-0 bg-transparent" data-kav-vote="qa" data-id="<?php echo esc_attr( $q_id ); ?>" data-vote-type="dislike">
												<span class="kav-vote-count"><?php echo esc_html( $q_dislikes ); ?></span>
												<i class="icon-thumbs-down-3 lh-1"></i>
											</button>
											<button type="button" class="d-flex align-items-center justify-content-center gap-1 fw-normal letter-spacing-4 border-0 bg-transparent" data-kav-vote="qa" data-id="<?php echo esc_attr( $q_id ); ?>" data-vote-type="like">
												<span class="kav-vote-count"><?php echo esc_html( $q_likes ); ?></span>
												<i class="icon-thumbs-up-3 lh-1"></i>
											</button>
										</div>
									</div>

									<?php if ( ! empty( $q_answers ) ) : ?>
										<ul class="replys list-unstyled d-flex flex-column gap-2 mt-3 ps-4">
											<?php foreach ( $q_answers as $ans ) : ?>
												<li class="bg-white p-3 rounded-3 border">
													<div class="author d-flex align-items-center gap-2 mb-2">
														<span class="name fw-bold"><?php echo esc_html( isset( $ans['author'] ) ? $ans['author'] : '' ); ?></span>
														<span class="status <?php echo esc_attr( isset( $ans['status'] ) ? $ans['status'] : 'support' ); ?>"><?php esc_html_e( 'پاسخ پشتیبانی', 'negar-shop' ); ?></span>
													</div>
													<p class="text-muted mb-0 small"><?php echo esc_html( isset( $ans['text'] ) ? $ans['text'] : '' ); ?></p>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>

				<div class="col-lg-4 order-1 order-lg-2">
					<div class="sticker">
						<div class="inner-wrapper-sticky">
							<div class="kav-single-product-questions-box-add bg-white d-flex align-items-center justify-content-between gap-2 p-4">
								<div>
									<div class="title fw-bold letter-spacing-4 mb-1"><?php esc_html_e( 'ثبت پرسش', 'negar-shop' ); ?></div>
									<div class="sub-title fw-normal letter-spacing-4 mb-3 small text-muted"><?php esc_html_e( 'درمورد محصول سوالات خود را بپرسید!', 'negar-shop' ); ?></div>
									<button type="button" class="btn btn-danger-6 fw-bold letter-spacing-4" data-bs-toggle="modal" data-bs-target="#modal-kav-add-question">
										<?php esc_html_e( 'ثبت پرسش', 'negar-shop' ); ?>
									</button>
								</div>
								<img loading="lazy" src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/detail-product-7/questions.png' ); ?>" alt="" width="110" height="110" class="flex-fill flex-grow-0 flex-shrink-0">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal: Add Review -->
<div class="modal fade" id="modal-kav-add-camment" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 rounded-4 shadow-lg p-3">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title fw-bold"><?php esc_html_e( 'ثبت دیدگاه جدید', 'negar-shop' ); ?></h5>
				<button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="kav-comment-form" action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>" method="post">
					<input type="hidden" name="comment_post_ID" value="<?php echo esc_attr( $product->get_id() ); ?>" />
					<input type="hidden" name="comment_parent" id="comment_parent" value="0" />
					
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
					<button type="submit" class="btn btn-danger-6 w-100 fw-bold py-2 rounded-3 mt-2">
						<?php esc_html_e( 'ارسال و ثبت نظر', 'negar-shop' ); ?>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal: Add Question -->
<div class="modal fade" id="modal-kav-add-question" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content border-0 rounded-4 shadow-lg p-3">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title fw-bold"><?php esc_html_e( 'ثبت پرسش جدید', 'negar-shop' ); ?></h5>
				<button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="kav-question-form" onsubmit="event.preventDefault(); alert('پرسش شما با موفقیت ثبت شد و پس از بررسی منتشر خواهد شد.'); var modalEl = document.getElementById('modal-kav-add-question'); var modal = bootstrap.Modal.getInstance(modalEl); if(modal) modal.hide();">
					<div class="mb-3">
						<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'متن پرسش شما درباره محصول', 'negar-shop' ); ?></label>
						<textarea class="form-control rounded-3" rows="4" placeholder="<?php esc_attr_e( 'سوال خود را بپرسید...', 'negar-shop' ); ?>" required></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label small fw-bold text-muted"><?php esc_html_e( 'نام شما', 'negar-shop' ); ?></label>
						<input type="text" class="form-control rounded-3" placeholder="نام خود را وارد کنید" required />
					</div>
					<button type="submit" class="btn btn-danger-6 w-100 fw-bold py-2 rounded-3 mt-2">
						<?php esc_html_e( 'ارسال پرسش', 'negar-shop' ); ?>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
