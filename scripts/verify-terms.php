<?php
require dirname( __DIR__, 4 ) . '/wp-load.php';

$terms = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
) );

echo 'Total product_cat terms: ' . count( $terms ) . PHP_EOL;
$parents = 0;
$children = 0;
foreach ( $terms as $t ) {
	if ( 0 === (int) $t->parent ) {
		$parents++;
		echo "[ROOT] ID: {$t->term_id} | Slug: {$t->slug} | Name: {$t->name}\n";
	} else {
		$children++;
		$p = get_term( $t->parent, 'product_cat' );
		echo "  [CHILD] ID: {$t->term_id} | Slug: {$t->slug} | Name: {$t->name} | Parent: {$p->name} ({$p->slug})\n";
	}
}

echo "\nSummary: {$parents} Root categories, {$children} Subcategories. Total: " . ( $parents + $children ) . PHP_EOL;
