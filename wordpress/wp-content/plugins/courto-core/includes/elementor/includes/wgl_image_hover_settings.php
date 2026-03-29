<?php

namespace WGL_Extensions\Includes;

defined('ABSPATH') || exit;

use Elementor\{
    Controls_Manager
};

if (!class_exists('WGL_Webgl_Image_Hover')) {
    /**
     * WGL Elementor Webgl Image Hover Settings
     *
     *
     * @package courto-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.0
     */
    class WGL_Webgl_Image_Hover
    {
        private static $instance;

        /**
         * @var WGL_Webgl_Image_Hover
         */
        private $dir_path;

        /**
         * Creates and returns an instance of the class
         *
         * @return object
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct()
        {
            $this->dir_path = plugin_dir_path(__FILE__);
        }

        public function init()
        {
            add_action('elementor/init', [$this, 'init_addons']);
        }

        public function init_addons()
        {
            // Add WebGL Image_Hover Animation
            add_action('elementor/frontend/after_register_scripts', [$this, 'frontend_scripts_registration']);
            add_action('elementor/element/before_section_end', [$this, 'inject_options_in_elementor_widgets'], 10, 3);
            add_action('elementor/widget/render_content', [$this, 'extended_image_render_content'], 10, 2);
        }

        public function extended_image_render_content($content, $widget)
        {
            if ('image' === $widget->get_name()) {
                $settings = $widget->get_settings_for_display();
                if (!empty($settings['wgl_webgl_image_hover_effects'])) {
                    return $this->build($content, $widget);
                }
            }
            return $content;
        }

        public function build($content, $widget)
        {
            $settings = $widget->get_settings_for_display();
            if (!empty($settings['wgl_webgl_image_hover_effects'])) {
                $this->enable_scripts($settings);
                $effect_attribute = 'data-image-effect="' . esc_attr($settings['wgl_webgl_image_hover_effects']) . '"';
                if('ripple' === $settings['wgl_webgl_image_hover_effects']){
                     $effect_attribute .= ' data-trigger="' . esc_attr($settings['wgl_webgl_image_hover_trigger'] ?? 'hover') . '"';
                }

                if('yes' === $settings['wgl_webgl_image_premultiplied_alpha']){
                    $effect_attribute .= ' data-premultiplied="yes"';
                }

                $wrapper_style = '';
                if (isset($settings['image_border_radius']) && !empty($settings['image_border_radius'])) {
                    $br = $settings['image_border_radius'];

                    $top    = $br['top'] ?? 0;
                    $right  = $br['right'] ?? 0;
                    $bottom = $br['bottom'] ?? 0;
                    $left   = $br['left'] ?? 0;
                    $unit   = $br['unit'] ?? 'px';

                    $wrapper_style .= '--wgl-br: '
                        . $top . $unit . ' '
                        . $right . $unit . ' '
                        . $bottom . $unit . ' '
                        . $left . $unit . ';';
                }

                $style_attr = $wrapper_style ? ' style="' . esc_attr($wrapper_style) . '"' : '';

                $wrapped_content = '<div class="wgl-webgl-plane_wrapper" ' . $effect_attribute . $style_attr . '>';
                $wrapped_content .= $content;
                $wrapped_content .= '</div>';

                return $wrapped_content;
            }
        }

        public function force_script( $handle ) {
            global $wp_scripts;

            if ( $wp_scripts instanceof \WP_Scripts ) {
                if ( ! in_array( $handle, $wp_scripts->queue, true ) ) {
                    $wp_scripts->queue[] = $handle;
                }
            }

            wp_enqueue_script( $handle );
        }

        public function enable_scripts($settings)
        {
            $this->force_script( 'curtains' );

            if ( $settings['wgl_webgl_image_hover_effects'] === 'waterRipples' ) {
                $this->force_script( 'jquery-ripples' );
            }

            $this->force_script( 'wgl-image-hover-effect' );
        }

        public function frontend_scripts_registration()
        {
            wp_register_script(
                'curtains',
                WGL_ELEMENTOR_MODULE_URL . 'assets/js/curtains.umd.min.js',
                ['jquery'],
                '8.1.6',
                true
            );

            wp_register_script(
                'jquery-ripples',
                WGL_ELEMENTOR_MODULE_URL . 'assets/js/jquery.ripples-min.js',
                ['jquery'],
                '0.6.3',
                true
            );

            wp_register_script(
                'wgl-image-hover-effect',
                WGL_ELEMENTOR_MODULE_URL . 'assets/js/wgl_webgl_image_hover.js',
                ['jquery', 'jquery-ripples', 'curtains'],
                '1.0.0',
                true
            );

            wp_localize_script('wgl-image-hover-effect', 'WGLHoverVars', [
                'img' => esc_url(WGL_ELEMENTOR_MODULE_URL . 'assets/img/'),
            ]);
        }

        public function inject_options_in_elementor_widgets($element, $section_id, $args)
        {
            if ('image' == $element->get_name() && 'section_image' === $section_id) {
                $element->add_control(
                    'wgl_webgl_image_hover_heading',
                    [
                        'label' => esc_html__('WGL Webgl Image Hover Effects', 'courto-core'),
                        'type' => Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );

                $element->add_control(
                    'wgl_webgl_image_hover_effects',
                    [
                        'label' => esc_html__('Image Hover Interaction Effects', 'courto-core'),
                        'type' => Controls_Manager::SELECT,
                        'options' =>  $this->image_hover_effects_list(),
                        'default' => '',
                    ]
                );

                $element->add_control(
                    'wgl_webgl_image_hover_trigger',
                    [
                        'label' => esc_html__('Trigger', 'courto-core'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            'visible' => esc_html__( 'Always Visible', 'courto-core' ),
                            'hover' => esc_html__( 'On Hover', 'courto-core' ),
                        ],
                        'condition' => [
                            'wgl_webgl_image_hover_effects' => 'ripple',
                        ],
                        'default' => 'hover',
                    ]
                );

                $element->add_control(
                    'wgl_webgl_image_premultiplied_alpha',
                    [
                        'label' => esc_html__( 'Transparent Alpha', 'courto-core' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Yes', 'courto-core' ),
                        'label_off' => esc_html__( 'No', 'courto-core' ),
                        'return_value' => 'yes',
                        'default' => '',
                        'condition' => [
                            'wgl_webgl_image_hover_effects!' => ['', 'flowmap'],
                        ],
                        'description' => esc_html__( 'Enable if your image uses premultiplied alpha (useful for WebGL transparency correction).', 'courto-core' ),
                    ]
                );
            }
        }

        public function image_hover_effects_list()
        {
            $additional_animations = [];

            $get_default_animations = [
                '' => esc_html__('None', 'courto-core'),
                'ripple' => esc_html__('Ripple', 'courto-core'),
                'rain' => esc_html__('Rain', 'courto-core'),
                'aberration' => esc_html__('Aberration', 'courto-core'),
                'liquid' => esc_html__('Liquid', 'courto-core'),
                'rgba' => esc_html__('RGBA Hover', 'courto-core'),
                'wave3d' => esc_html__('Wave 3d', 'courto-core'),
                'flowmap' => esc_html__('Flowmap', 'courto-core'),
                'waterRipples' => esc_html__('Water', 'courto-core'),
                'scrollWave' => esc_html__('Scrolling Wave', 'courto-core'),
            ];

            $additional_animations = apply_filters('wgl/controls/wgl_webgl_image_hover/list', $additional_animations);

            return array_merge($get_default_animations, $additional_animations);
        }
    }
}

if (!function_exists('wgl_elementor_webgl_image_hover')) {
    function wgl_elementor_webgl_image_hover()
    {
        return WGL_Webgl_Image_Hover::get_instance();
    }

    wgl_elementor_webgl_image_hover()->init();
}
