<?php

defined('ABSPATH') || exit;

/**
 * The template for displaying product search form
 *
 * This template is overridden by WebGeniusLab team.
 *
 * @see        https://woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version    7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'courto' ); ?></label>
	<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'courto' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button class="search-button <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'courto' ); ?>"><i class="search__icon"><?php echo wgl_dynamic_styles()->wgl_theme_svg()['search']; ?></i></button>
	<input type="hidden" name="post_type" value="product" />
</form>
