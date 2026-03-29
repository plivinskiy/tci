<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-image-comparison.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Utils
};
use WGL_Extensions\Includes\WGL_Elementor_Helper;
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Image_Comparison extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-image-comparison';
    }

    public function get_title()
    {
        return esc_html__('WGL Image Comparison', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-image-comparison';
    }

    public function get_keywords()
    {
        return [ 'image', 'comparison' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['wgl-widgets', 'cocoen'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'wgl_image_comparison_section',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'before_image',
            [
                'label' => esc_html__('Before Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label' => esc_html__('After Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> SLIDER BAR STYLES
         */

        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__('Slider Bar Styles', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
			'slider',
			[
				'label' => esc_html__('Slider Bar', 'courto-core'),
				'type' => Controls_Manager::HEADING,
			]
        );

        $this->add_control(
			'slider_color',
			[
				'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'default' => '#04011C',
				'selectors' => [
					'{{WRAPPER}} .cocoen-drag::before,
					 {{WRAPPER}} .cocoen-drag::after' => 'color: {{VALUE}};',
				],
			]
        );
        $this->add_control(
			'slider_bg',
			[
				'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .cocoen-drag,
					 {{WRAPPER}} .cocoen-drag::before' => 'background-color: {{VALUE}};',
				],
			]
        );
        $this->add_responsive_control(
            'slider_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image_comparison.cocoen .cocoen-drag::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $styles = '';
        if (!!$_s['slider_color']){
            $styles = '.elementor-element-' . esc_attr( $this->get_id() ) . ' .wgl-image_comparison.cocoen .cocoen-drag::before,
                       .elementor-element-' . esc_attr( $this->get_id() ) . ' .wgl-image_comparison.cocoen .cocoen-drag::after{
                        background-image: url(\'data:image/svg+xml; utf8, '.wgl_dynamic_styles()->bg_caret($_s['slider_color'] ? esc_attr($_s['slider_color']) : WGL_Globals::get_secondary_color()).'\'); }';
        }

        WGL_Elementor_Helper::enqueue_css( $styles, false );

        $this->add_render_attribute('image_comp_wrapper', 'class', [
            'wgl-image_comparison',
            'cocoen'
        ]);

        $this->add_render_attribute('before_image', [
            'class' => [
                'comp-image_before',
                'comp-image'
            ],
            'src' => isset($_s['before_image']['url']) ? esc_url($_s['before_image']['url']) : '',
            'alt' => Control_Media::get_image_alt($_s['before_image']),
        ]);

        $this->add_render_attribute('after_image', [
            'class' => [
                'comp-image_after',
                'comp-image'
            ],
            'src' => isset($_s['after_image']['url']) ? esc_url($_s['after_image']['url']) : '',
            'alt' => Control_Media::get_image_alt($_s['after_image']),
        ]);

        ?><div <?php echo $this->get_render_attribute_string('image_comp_wrapper'); ?>>
            <img <?php echo $this->get_render_attribute_string('before_image'); ?> />
            <img <?php echo $this->get_render_attribute_string('after_image'); ?> />
        </div><?php
    }

    public function wpml_support_module() {
        add_filter( 'wpml_elementor_widgets_to_translate',  [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter( $widgets ){
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this, $widgets
        );
    }
}