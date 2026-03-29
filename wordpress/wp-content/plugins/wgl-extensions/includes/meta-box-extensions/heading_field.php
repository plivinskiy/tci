<?php
if ( class_exists( 'RWMB_Field' ) ){
    /**
     * The heading field which displays a simple heading text.
     *
     * @package Meta Box
     */

    /**
     * Heading field class.
     */
    class RWMB_WGL_Heading_Field extends RWMB_Field {

        /**
         * Show begin HTML markup for fields.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        protected static function begin_html( array $field ) : string {
            // $attributes = empty( $field['id'] ) ? '' : " id='{$field['id']}'";

            $attributes = self::get_attributes( $field );
            $return = '<div class="rwmb-input">';
            $return .= sprintf( '<h3%s>%s</h3>', self::render_attributes( $attributes ), $field['name'] );
            return $return;
        }

        /**
         * Show end HTML markup for fields.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        protected static function end_html(array $field ) : string {
            $return = self::input_description( $field );
            $return .= '</div>';
            return $return;
        }

        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The attribute value.
         * @return array
         */
        public static function get_attributes( $field, $value = null ) {
            $attributes           = parent::get_attributes( $field, $value );
            $attributes = wp_parse_args( $attributes, array() );

            return $attributes;
        }
    }
}

?>