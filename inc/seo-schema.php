<?php
/**
 * SEO, OpenGraph and JSON-LD Structured Data Schema Markup
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output OpenGraph and Twitter Card Meta Tags in wp_head.
 */
function negar_shop_opengraph_meta_tags() {
	$site_name   = get_bloginfo( 'name' );
	$title       = wp_get_document_title();
	$url         = is_front_page() ? home_url( '/' ) : get_permalink();
	$description = get_bloginfo( 'description' );
	$type        = 'website';
	$image       = '';

	if ( is_singular() ) {
		$post_id = get_the_ID();
		if ( has_post_thumbnail( $post_id ) ) {
			$image = get_the_post_thumbnail_url( $post_id, 'large' );
		}
		if ( is_singular( 'product' ) ) {
			$type = 'og:product';
		} elseif ( is_singular( 'post' ) ) {
			$type = 'article';
		}
		$excerpt = get_the_excerpt( $post_id );
		if ( ! empty( $excerpt ) ) {
			$description = wp_strip_all_tags( $excerpt );
		}
	}

	echo "\n<!-- Negar Shop OpenGraph & Social Meta -->\n";
	echo '<meta property="og:locale" content="fa_IR" />' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' . "\n";
	if ( ! empty( $image ) ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
}
add_action( 'wp_head', 'negar_shop_opengraph_meta_tags', 2 );

/**
 * Output Rich JSON-LD Structured Data Schema in wp_head.
 */
function negar_shop_json_ld_schema() {
	$home_url   = home_url( '/' );
	$site_name  = get_bloginfo( 'name' );
	$site_desc  = get_bloginfo( 'description' );
	$phone      = get_theme_mod( 'negar_shop_phone_hotline', '۰۲۱-۱۲۳۴۵۶۷۸' );
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$logo_url   = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : '';

	$graph = array();

	// 1. WebSite Schema with SearchAction
	$website_schema = array(
		'@type'           => 'WebSite',
		'@id'             => esc_url( $home_url ) . '#website',
		'url'             => esc_url( $home_url ),
		'name'            => esc_html( $site_name ),
		'description'     => esc_html( $site_desc ),
		'inLanguage'      => 'fa-IR',
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => esc_url( $home_url ) . '?s={search_term_string}&post_type=product',
			'query-input' => 'required name=search_term_string',
		),
	);
	$graph[] = $website_schema;

	// 2. OnlineStore / Organization Schema
	$store_schema = array(
		'@type'       => 'OnlineStore',
		'@id'         => esc_url( $home_url ) . '#organization',
		'name'        => esc_html( $site_name ),
		'url'         => esc_url( $home_url ),
		'description' => esc_html( $site_desc ),
	);

	if ( ! empty( $logo_url ) ) {
		$store_schema['logo']  = esc_url( $logo_url );
		$store_schema['image'] = esc_url( $logo_url );
	}

	if ( ! empty( $phone ) ) {
		$store_schema['telephone'] = esc_html( $phone );
	}

	$graph[] = $store_schema;

	// 3. Article Schema on Single Blog Posts
	if ( is_singular( 'post' ) ) {
		$post_id   = get_the_ID();
		$author_id = get_post_field( 'post_author', $post_id );
		$article_schema = array(
			'@type'            => 'Article',
			'@id'              => esc_url( get_permalink( $post_id ) ) . '#article',
			'headline'         => esc_html( get_the_title( $post_id ) ),
			'datePublished'    => esc_html( get_the_date( 'c', $post_id ) ),
			'dateModified'     => esc_html( get_the_modified_date( 'c', $post_id ) ),
			'mainEntityOfPage' => esc_url( get_permalink( $post_id ) ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => esc_html( get_the_author_meta( 'display_name', $author_id ) ),
			),
			'publisher'        => array(
				'@id' => esc_url( $home_url ) . '#organization',
			),
		);

		if ( has_post_thumbnail( $post_id ) ) {
			$thumb_url = get_the_post_thumbnail_url( $post_id, 'full' );
			if ( $thumb_url ) {
				$article_schema['image'] = esc_url( $thumb_url );
			}
		}

		$graph[] = $article_schema;
	}

	$schema_data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo "\n<!-- Negar Shop JSON-LD Structured Data Schema -->\n";
	echo '<script type="application/ld+json">' . wp_json_encode( $schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . "</script>\n";
}
add_action( 'wp_head', 'negar_shop_json_ld_schema', 3 );
