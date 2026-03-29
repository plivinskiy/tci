<?php
/**
 * Product quantity inputs
 *
 * This template is overridden by WebGeniusLab team.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

defined('ABSPATH') || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'courto' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'courto' );

?>
<div class="quantity number-input<?php if ('hidden' === $type) echo ' wgl-number-input-hidden'; ?>">
    <?php
    /**
     * Hook to output something before the quantity input field.
     *
     * @since 7.2.0
     */
    do_action( 'woocommerce_before_quantity_input_field' );
    ?>
    <label class="label-qty" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $label ); ?></label>
    <div class="quantity-wrapper">
        <span class="minus"></span>
        <input
            type="<?php echo esc_attr( $type ); ?>"
            <?php echo !!$readonly ? 'readonly="readonly"' : ''; ?>
            id="<?php echo esc_attr( $input_id ); ?>"
            class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
            name="<?php echo esc_attr( $input_name ); ?>"
            value="<?php echo esc_attr( $input_value ); ?>"
            aria-label="<?php esc_attr_e( 'Product quantity','courto' ); ?>"
            <?php if ( in_array( $type, array( 'text', 'search', 'tel', 'url', 'email', 'password' ), true ) ) : ?>
                size="4"
            <?php endif; ?>
            min="<?php echo esc_attr( $min_value ); ?>"
            <?php if ( 0 < $max_value ) : ?>
                max="<?php echo esc_attr( $max_value ); ?>"
            <?php endif; ?>
            <?php if ( ! $readonly ): ?>
                step="<?php echo esc_attr( $step ); ?>"
                placeholder="<?php echo esc_attr( $placeholder ); ?>"
                inputmode="<?php echo esc_attr( $inputmode ); ?>"
                autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
            <?php endif; ?>
        />
        <span class="plus"></span>
    </div>
    <?php
    /**
     * Hook to output something after quantity input field
     *
     * @since 3.6.0
     */
    do_action( 'woocommerce_after_quantity_input_field' );
    ?>
</div><?php
