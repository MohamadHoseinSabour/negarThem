<?php
/**
 * Section 33: Latest Blog Articles & Buying Guides (HOME-33)
 * Reference: Demo 43 Section 32 (articlesnkkaveh)
 * Heading: "برترین اخبار - ضمانت قیمت و کیفیت"
 *
 * Displays latest dynamic blog articles with real categories, featured images,
 * titles, authors, and dates.
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_archive_url = get_permalink( get_option( 'page_for_posts' ) );
if ( ! $blog_archive_url ) {
	$blog_archive_url = get_post_type_archive_link( 'post' );
}
if ( ! $blog_archive_url ) {
	$blog_archive_url = home_url( '/blog/' );
}

$articles_query = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 10,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => 1,
) );

$articles = $articles_query->posts;

if ( empty( $articles ) ) {
	return;
}

$prepare_article_data = function( $post_obj ) use ( $blog_archive_url ) {
	if ( ! $post_obj instanceof WP_Post ) {
		return null;
	}
	$p_id  = $post_obj->ID;
	$title = get_the_title( $p_id );
	$link  = get_permalink( $p_id );
	$date  = get_the_date( '', $p_id );

	if ( has_post_thumbnail( $p_id ) ) {
		$thumb = get_the_post_thumbnail_url( $p_id, 'large' );
	} else {
		$thumb = get_template_directory_uri() . '/assets/images/new-demo/1.jpeg';
	}

	$cats        = get_the_category( $p_id );
	$primary_cat = null;
	if ( ! empty( $cats ) ) {
		$default_cat_id = (int) get_option( 'default_category' );
		foreach ( $cats as $cat ) {
			if ( (int) $cat->term_id !== $default_cat_id ) {
				$primary_cat = $cat;
				break;
			}
		}
		if ( ! $primary_cat ) {
			$primary_cat = $cats[0];
		}
	}

	if ( $primary_cat ) {
		$cat_name = $primary_cat->name;
		$cat_link = get_category_link( $primary_cat->term_id );
	} else {
		$cat_name = __( 'اخبار و مقالات', 'negar-shop' );
		$cat_link = $blog_archive_url;
	}

	$author_id = (int) $post_obj->post_author;
	if ( $author_id > 0 ) {
		$author_name = get_the_author_meta( 'display_name', $author_id );
		$author_link = get_author_posts_url( $author_id );
		$avatar_url  = get_avatar_url( $author_id, array( 'size' => 80 ) );
	} else {
		$author_name = '';
		$author_link = '';
		$avatar_url  = '';
	}

	if ( empty( $author_name ) ) {
		$admin_user = get_userdata( 1 );
		if ( $admin_user ) {
			$author_name = $admin_user->display_name;
			$author_link = get_author_posts_url( $admin_user->ID );
			$avatar_url  = get_avatar_url( $admin_user->ID, array( 'size' => 80 ) );
		} else {
			$author_name = __( 'مدیر سایت', 'negar-shop' );
			$author_link = '#';
			$avatar_url  = get_template_directory_uri() . '/assets/images/new-demo/banner-02-150x150.png';
		}
	}

	if ( empty( $avatar_url ) ) {
		$avatar_url = get_template_directory_uri() . '/assets/images/new-demo/banner-02-150x150.png';
	}

	return array(
		'id'          => $p_id,
		'title'       => $title,
		'link'        => $link,
		'date'        => $date,
		'thumb'       => $thumb,
		'cat_name'    => $cat_name,
		'cat_link'    => $cat_link,
		'author_name' => $author_name,
		'author_link' => $author_link,
		'avatar_url'  => $avatar_url,
	);
};

$desktop_slides = array_chunk( $articles, 5 );
?>
<div class="elementor-element elementor-element-6a35d062 e-con-full e-flex e-con e-parent" data-id="6a35d062" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-729e5589 elementor-widget elementor-widget-articlesnkkaveh" data-id="729e5589" data-element_type="widget" data-e-type="widget" data-widget_type="articlesnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="articles-nk articles-nk-729e5589">
				<div class="container">
					<div class="section-heading-one-nk d-flex align-items-center gap-2">
						<div class="title f-iran-yekan">
							<span class="fw-bold">برترین</span> اخبار
						</div>
						<div class="sub-title f-iran-yekan fw-normal d-none d-md-block">ضمانت قیمت و کیفیت</div>
						<div class="line flex-grow-1 d-flex align-items-center gap-1">
							<span class="flex-grow-1"></span>
							<span></span>
						</div>
						<a href="<?php echo esc_url( $blog_archive_url ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal rounded-pill position-relative">
							مشاهده بیشتر
							<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
						</a>
					</div>

					<!-- Desktop Grid / Swiper -->
					<div class="swiper swiper-articles-desktop-nk d-none d-lg-block overflow-hidden" data-swiper-config="{&quot;watchSlidesProgress&quot;:true,&quot;autoHeight&quot;:true,&quot;slidesPerView&quot;:1,&quot;spaceBetween&quot;:12,&quot;observer&quot;:true,&quot;observeParents&quot;:true}">
						<div class="swiper-wrapper">
							<?php foreach ( $desktop_slides as $slide_posts ) :
								$item0 = isset( $slide_posts[0] ) ? $prepare_article_data( $slide_posts[0] ) : null;
								$item1 = isset( $slide_posts[1] ) ? $prepare_article_data( $slide_posts[1] ) : null;
								$item2 = isset( $slide_posts[2] ) ? $prepare_article_data( $slide_posts[2] ) : null;
								$item3 = isset( $slide_posts[3] ) ? $prepare_article_data( $slide_posts[3] ) : null;
								$item4 = isset( $slide_posts[4] ) ? $prepare_article_data( $slide_posts[4] ) : null;
								?>
								<div class="swiper-slide">
									<div class="row">
										<!-- Slot 0: Right Tall Card (col-lg-3 in RTL) -->
										<?php if ( $item0 ) : ?>
											<div class="col-lg-3">
												<div class="articles-nk__item position-relative overflow-hidden">
													<a href="<?php echo esc_url( $item0['link'] ); ?>" class="stretched-link" aria-label="<?php echo esc_attr( $item0['title'] ); ?>"></a>
													<div class="bg position-absolute top-0 end-0 w-100 h-100" style="background-image: url('<?php echo esc_url( $item0['thumb'] ); ?>');"></div>

													<?php if ( ! empty( $item0['cat_name'] ) ) : ?>
														<a href="<?php echo esc_url( $item0['cat_link'] ); ?>" class="category f-iran-yekan text-center text-white position-absolute rounded-pill start-50 translate-middle-x">
															<?php echo esc_html( $item0['cat_name'] ); ?>
														</a>
													<?php endif; ?>

													<div class="content position-absolute bottom-0 p-3 p-md-4 w-100 end-0">
														<h2 class="title f-iran-yekan fw-bold text-white line-clamp-2">
															<?php echo esc_html( $item0['title'] ); ?>
														</h2>
														<div class="author-date d-flex align-items-center gap-4 mt-2" style="position: relative; z-index: 10;">
															<a href="<?php echo esc_url( $item0['author_link'] ); ?>" class="author f-iran-yekan fw-normal text-white"><?php echo esc_html( $item0['author_name'] ); ?></a>
															<span class="date f-iran-yekan fw-light"><?php echo esc_html( $item0['date'] ); ?></span>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>

										<!-- Slots 1, 2, 3: Center Column (col-lg-5 in RTL) -->
										<?php if ( $item1 || $item2 || $item3 ) : ?>
											<div class="col-lg-5">
												<?php if ( $item1 ) : ?>
													<!-- Slot 1: Wide Top Card -->
													<div class="articles-nk__item position-relative overflow-hidden two">
														<a href="<?php echo esc_url( $item1['link'] ); ?>" class="stretched-link" aria-label="<?php echo esc_attr( $item1['title'] ); ?>"></a>
														<div class="bg position-absolute top-0 end-0 w-100 h-100" style="background-image: url('<?php echo esc_url( $item1['thumb'] ); ?>');"></div>

														<?php if ( ! empty( $item1['cat_name'] ) ) : ?>
															<a href="<?php echo esc_url( $item1['cat_link'] ); ?>" class="category f-iran-yekan text-center text-white position-absolute rounded-pill" style="top: 20px; right: 20px; z-index: 10;">
																<?php echo esc_html( $item1['cat_name'] ); ?>
															</a>
														<?php endif; ?>

														<div class="content position-absolute bottom-0 p-3 p-md-4 w-100 end-0">
															<h2 class="title f-iran-yekan fw-bold text-white line-clamp-2">
																<?php echo esc_html( $item1['title'] ); ?>
															</h2>

															<div class="d-flex align-items-center justify-content-between gap-2">
																<div class="author-date d-flex align-items-center gap-4" style="position: relative; z-index: 10;">
																	<a href="<?php echo esc_url( $item1['author_link'] ); ?>" class="author f-iran-yekan fw-normal text-white"><?php echo esc_html( $item1['author_name'] ); ?></a>
																	<span class="date f-iran-yekan fw-light"><?php echo esc_html( $item1['date'] ); ?></span>
																</div>
															</div>
														</div>
													</div>
												<?php endif; ?>

												<!-- Slots 2 & 3: Two Small Cards Side by Side -->
												<?php if ( $item2 || $item3 ) : ?>
													<div class="row">
														<?php if ( $item2 ) : ?>
															<div class="col-sm-6">
																<div class="articles-nk__item position-relative overflow-hidden three">
																	<a href="<?php echo esc_url( $item2['link'] ); ?>" class="stretched-link" aria-label="<?php echo esc_attr( $item2['title'] ); ?>"></a>
																	<div class="bg position-absolute top-0 end-0 w-100 h-100" style="background-image: url('<?php echo esc_url( $item2['thumb'] ); ?>');"></div>

																	<?php if ( ! empty( $item2['cat_name'] ) ) : ?>
																		<a href="<?php echo esc_url( $item2['cat_link'] ); ?>" class="category f-iran-yekan text-center text-white position-absolute rounded-pill">
																			<?php echo esc_html( $item2['cat_name'] ); ?>
																		</a>
																	<?php endif; ?>

																	<div class="content position-absolute bottom-0 p-3 p-md-4 w-100 end-0">
																		<h2 class="title f-iran-yekan fw-bold text-white line-clamp-2">
																			<?php echo esc_html( $item2['title'] ); ?>
																		</h2>
																	</div>
																</div>
															</div>
														<?php endif; ?>

														<?php if ( $item3 ) : ?>
															<div class="col-sm-6">
																<div class="articles-nk__item position-relative overflow-hidden three">
																	<a href="<?php echo esc_url( $item3['link'] ); ?>" class="stretched-link" aria-label="<?php echo esc_attr( $item3['title'] ); ?>"></a>
																	<div class="bg position-absolute top-0 end-0 w-100 h-100" style="background-image: url('<?php echo esc_url( $item3['thumb'] ); ?>');"></div>

																	<?php if ( ! empty( $item3['cat_name'] ) ) : ?>
																		<a href="<?php echo esc_url( $item3['cat_link'] ); ?>" class="category f-iran-yekan text-center text-white position-absolute rounded-pill">
																			<?php echo esc_html( $item3['cat_name'] ); ?>
																		</a>
																	<?php endif; ?>

																	<div class="content position-absolute bottom-0 p-3 p-md-4 w-100 end-0">
																		<h2 class="title f-iran-yekan fw-bold text-white line-clamp-2">
																			<?php echo esc_html( $item3['title'] ); ?>
																		</h2>
																	</div>
																</div>
															</div>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										<?php endif; ?>

										<!-- Slot 4: Left Tall Card with Author Profile and Action Button (col-lg-4 in RTL) -->
										<?php if ( $item4 ) : ?>
											<div class="col-lg-4">
												<div class="articles-nk__item position-relative overflow-hidden four">
													<a href="<?php echo esc_url( $item4['link'] ); ?>" class="stretched-link" aria-label="<?php echo esc_attr( $item4['title'] ); ?>"></a>
													<div class="bg position-absolute top-0 end-0 w-100 h-100" style="background-image: url('<?php echo esc_url( $item4['thumb'] ); ?>');"></div>

													<?php if ( ! empty( $item4['cat_name'] ) ) : ?>
														<a href="<?php echo esc_url( $item4['cat_link'] ); ?>" class="category f-iran-yekan text-center text-white position-absolute rounded-pill start-50 translate-middle-x">
															<?php echo esc_html( $item4['cat_name'] ); ?>
														</a>
													<?php endif; ?>

													<div class="content position-absolute bottom-0 p-3 p-md-4 w-100 end-0">
														<h2 class="title f-iran-yekan fw-bold text-white line-clamp-2">
															<?php echo esc_html( $item4['title'] ); ?>
														</h2>

														<div class="d-flex align-items-center justify-content-between gap-2">
															<div class="author__two d-flex align-items-center text-truncate" style="position: relative; z-index: 10;">
																<img loading="lazy" decoding="async" src="<?php echo esc_url( $item4['avatar_url'] ); ?>" alt="<?php echo esc_attr( $item4['author_name'] ); ?>" width="40" height="40" class="flex-fill flex-grow-0 flex-shrink-0 rounded-circle">
																<div class="detail text-truncate">
																	<a href="<?php echo esc_url( $item4['author_link'] ); ?>" class="name f-iran-yekan fw-normal text-white text-truncate text-decoration-none d-block">
																		<?php echo esc_html( $item4['author_name'] ); ?>
																	</a>
																	<div class="date f-iran-yekan fw-light">
																		<?php echo esc_html( $item4['date'] ); ?>
																	</div>
																</div>
															</div>
															<a href="<?php echo esc_url( $item4['link'] ); ?>" class="btn f-iran-yekan rounded-pill flex-fill flex-grow-0 flex-shrink-0">
																مشاهده بیشتر
															</a>
														</div>
													</div>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- Mobile Swiper -->
					<div class="swiper swiper-articles-nk d-lg-none overflow-hidden" data-swiper-config="{&quot;watchSlidesProgress&quot;:true,&quot;autoHeight&quot;:true,&quot;observer&quot;:true,&quot;observeParents&quot;:true,&quot;breakpoints&quot;:{&quot;320&quot;:{&quot;slidesPerView&quot;:1.2,&quot;spaceBetween&quot;:12},&quot;576&quot;:{&quot;slidesPerView&quot;:2.1,&quot;spaceBetween&quot;:12},&quot;992&quot;:{&quot;slidesPerView&quot;:3,&quot;spaceBetween&quot;:16},&quot;1200&quot;:{&quot;slidesPerView&quot;:3,&quot;spaceBetween&quot;:24}}}">
						<div class="swiper-wrapper">
							<?php
							foreach ( $articles as $post_obj ) :
								$m_item = $prepare_article_data( $post_obj );
								if ( ! $m_item ) {
									continue;
								}
								?>
								<div class="swiper-slide">
									<div class="articles-nk__item position-relative overflow-hidden two">
										<a href="<?php echo esc_url( $m_item['link'] ); ?>" class="stretched-link" aria-label="<?php echo esc_attr( $m_item['title'] ); ?>"></a>
										<div class="bg position-absolute top-0 end-0 w-100 h-100" style="background-image: url('<?php echo esc_url( $m_item['thumb'] ); ?>');"></div>

										<?php if ( ! empty( $m_item['cat_name'] ) ) : ?>
											<a href="<?php echo esc_url( $m_item['cat_link'] ); ?>" class="category f-iran-yekan text-center text-white position-absolute rounded-pill" style="top: 16px; right: 16px; z-index: 10;">
												<?php echo esc_html( $m_item['cat_name'] ); ?>
											</a>
										<?php endif; ?>

										<div class="content position-absolute bottom-0 p-3 p-md-4 w-100 end-0">
											<h2 class="title f-iran-yekan fw-bold text-white line-clamp-2">
												<?php echo esc_html( $m_item['title'] ); ?>
											</h2>

											<div class="d-flex align-items-center justify-content-between gap-2">
												<a href="<?php echo esc_url( $m_item['author_link'] ); ?>" class="author f-iran-yekan fw-normal text-white" style="position: relative; z-index: 10;">
													<?php echo esc_html( $m_item['author_name'] ); ?>
												</a>
												<span class="date f-iran-yekan fw-light">
													<?php echo esc_html( $m_item['date'] ); ?>
												</span>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

				</div>
			</section>
		</div>
	</div>
</div>
<?php
wp_reset_postdata();
