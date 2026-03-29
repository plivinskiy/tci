<?php
namespace WGL_Extensions\Includes;

defined('ABSPATH') || exit;

use Elementor\Plugin;

if (!class_exists('WGL_Elementor_Helper')) {
    /**
     * WGL Elementor Helper Settings
     *
     *
     * @package wgl-extensions\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.6
     */
    class WGL_Elementor_Helper
    {
        private static $instance;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_wgl_icons()
        {
            $args = [
                'shapes-and-symbols',
                'shapes-and-symbols-1',
                'null',
                'null-1',
                'null-2',
                'null-3',
                'checked',
                'youtube',
                'paper-plane',
                'close',
                'plus',
                'plus-1',
                'add',
                'play',
                'basket',
                'loupe',
                'call',
                'right-arrow',
                'menu',
                'upload-up',
                'upload-right',
                'upload-bottom',
                'upload-left',
                'chat',
                'startup',
                'payment',
                'percent',
                'stopwatch',
                'check',
                'heart',
                'heart-1',
                'quote',
                'email',
                'phone',
                'phone-1',
                'carrot',
                'fruit',
                'fruit-1',
                'fruit-2',
                'broccoli',
                'orange',
                'left-quote',
                'right-quote',
                'link',
                'down-arrow',
                'left-arrow',
                'up-arrow',
                'strawberry',
                'grape',
                'avocado',
                'orange-1',
                'raspberry',
                'cherry',
                'apple',
                'store',
                'package',
                'box',
                'clock',
                'helmet',
                'smartphone',
                'placeholder',
                'arrival',
                'apple-1',
                'android-logo',
            ];

            return apply_filters('wgl_flaticon_icons', $args);
        }

	    public static function enqueue_css($style, $esc_attr = true)
        {
            if (!(bool) Plugin::$instance->editor->is_edit_mode()) {
                if (!empty($style)) {
                    ob_start();
                        echo $style;
                    $css = ob_get_clean();
                    $css = apply_filters('wgl/enqueue_shortcode_css', $css, $style);

                    return $css;
                }
            } else {
	            echo '<style>', ((bool)$esc_attr ? esc_attr($style) : $style ), '</style>';
            }
        }

        public function get_elementor_templates()
        {
            $templates = get_posts([
                'post_type' => 'elementor_library',
                'posts_per_page' => -1,
                'meta_query'     => [
                    'relation' => 'OR',
                    [
                        'key'     => 'wgl_elementor_custom_library_sync_to_library',
                        'compare' => 'NOT EXISTS',
                    ],
                    [
                        'key'     => 'wgl_elementor_custom_library_sync_to_library',
                        'value'   => '0',
                        'compare' => '=',
                    ],
                ],
            ]);

            if (!empty($templates) && !is_wp_error($templates)) {

                foreach ($templates as $template) {
                    $options[$template->ID] = $template->post_title;
                }

                update_option('temp_count', $options);

                return $options ?? [];
            }
        }

        /**
         * Retrieve image dimensions based on passed arguments.
         *
         * @param array|string $desired_dimensions  Required. Desired dimensions. Ex: `700x300`, `[700, 300]`, `['width' => 700, 'height' => 300]`
         * @param string       $aspect_ratio        Required. Desired ratio. Ex: `16:9`
         * @param array        $img_data            Optional. Result of `wp_get_attachment_image_src` function.
         *
         * @version 1.0.6
         */
        public static function get_image_dimensions(
            $desired_dimensions,
            String $aspect_ratio,
            Array $img_data = []
        ) {
            if (
                is_array( $desired_dimensions ) && ! $desired_dimensions[ 'width' ]
                || ! $desired_dimensions
            ) {
                // Bailout, if the required parameters are not provided.
                return;
            }

            if ( $aspect_ratio ) {
                $ratio_arr = explode( ':', $aspect_ratio );
                $ratio = round( $ratio_arr[ 0 ] / $ratio_arr[ 1 ], 4);
            }

            if ( 'full' === $desired_dimensions ) {
                $attachemnt_data = $img_data ?: wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

                if ( ! $attachemnt_data ) {
                    // Bailout, if no featured image
                    return;
                }

                return [
                    'width' => $attachemnt_data[ 1 ],
                    'height' => isset( $ratio ) ? round( (int) $attachemnt_data[1] / $ratio ) : $attachemnt_data[ 2 ]
                ];
            }

            if ( is_array( $desired_dimensions ) ) {
                $desired_width = $desired_dimensions[ 'width' ];
                $desired_height = $desired_dimensions[ 'height' ];
            } else {
                $dims = explode( 'x', $desired_dimensions );
                $desired_width = $dims[ 0 ];
                $desired_height = ! empty( $dims[ 1 ] ) ? $dims[ 1 ] : $dims[ 0 ];
            }

            return [
                'width' => (int) $desired_width,
                'height' => isset( $ratio ) ? round( $desired_width / $ratio ) : (int) $desired_height
            ];
        }
    }

    new WGL_Elementor_Helper;
}
