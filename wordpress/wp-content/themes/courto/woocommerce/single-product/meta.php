<?php
/**
 * Single Product Meta
 *
 * This template is overridden by WebGeniusLab team.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( ProductType::VARIABLE ) ) ) : ?>

        <span class="sku_wrapper"><span class="title"><?php esc_html_e( 'SKU', 'courto' ); ?></span><?php echo '<span class="sku">'.( ($sku = $product->get_sku())  ? $sku : esc_html__( 'N/A', 'courto' )); ?></span></span>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in"><span class="title">' . _n( 'Category', 'Categories', count( $product->get_category_ids() ), 'courto' ) . '</span>', '</span>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as"><span class="title">' . _n( 'Tag', 'Tags', count( $product->get_tag_ids() ), 'courto' ) . '</span>', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
