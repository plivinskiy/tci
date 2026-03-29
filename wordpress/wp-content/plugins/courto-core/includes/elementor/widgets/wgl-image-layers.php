<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-image-layers.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Border, Group_Control_Box_Shadow, Widget_Base, Controls_Manager, Control_Media, Repeater};

class WGL_Image_Layers extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-image-layers';
    }

    public function get_title()
    {
        return esc_html__('WGL Image Layers', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-image-layers';
    }

    public function get_keywords()
    {
        return [ 'image', 'layers' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return [ 'jquery-appear' ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_responsive_control(
            'wrapper_height',
            [
                'label' => esc_html__('Wrapper Min-Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'vw', 'vh', 'custom'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 1200],
                    'vw' => ['max' => 150],
                ],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_image-wrapper:first-child' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_width',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'vh', 'custom'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 1200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-layers' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_min_width',
            [
                'label' => esc_html__('Wrapper Min-Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'vh', 'custom'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 1200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-layers' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [ 'wrapper_width[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'interval',
            [
                'label' => esc_html__('Images Appearing Interval (ms)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 0,
				'step' => 50,
				'default' => 600,
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 0,
				'step' => 50,
				'default' => 800,
                'selectors' => [
                    '{{WRAPPER}} .img-layer_image' => 'transition-duration: {{VALUE}}ms;'
                ],
            ]
        );

        $this->add_control(
            'transition_timing',
            [
                'label' => esc_html__( 'Transition Timing Function', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'ease' => esc_html__( 'Ease', 'courto-core' ),
                    'ease-in' => esc_html__( 'Ease-In', 'courto-core' ),
                    'ease-out' => esc_html__( 'Ease-Out', 'courto-core' ),
                    'ease-in-out' => esc_html__( 'Ease-In-Out', 'courto-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_image' => 'transition-timing-function: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'image_link',
            [
                'label' => esc_html__('Add Module Link', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            [ 'label' => esc_html__('Content', 'courto-core') ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'default' => [ 'url' => '' ],
                'label_block' => true,
            ]
        );

        $repeater->add_responsive_control(
            'image_size',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2560 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                    'vw' => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'image_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top center' => esc_html__('Top Center', 'courto-core'),
                    'top left' => esc_html__('Top Left', 'courto-core'),
                    'top right' => esc_html__('Top Right', 'courto-core'),
                    'center center' => esc_html__('Center Center', 'courto-core'),
                    'center left' => esc_html__('Center Left', 'courto-core'),
                    'center right' => esc_html__('Center Right', 'courto-core'),
                    'bottom center' => esc_html__('Bottom Center', 'courto-core'),
                    'bottom left' => esc_html__('Bottom Left', 'courto-core'),
                    'bottom right' => esc_html__('Bottom Right', 'courto-core'),
                ],
                'selectors_dictionary' => [
                    'top center' =>    '0 auto auto auto',
                    'top left' =>      '0 auto auto 0',
                    'top right' =>     '0 0 auto auto',
                    'center center' => 'auto auto auto auto',
                    'center left' =>   'auto auto auto 0',
                    'center right' =>  'auto 0 auto auto',
                    'bottom center' => 'auto auto 0 auto',
                    'bottom left' =>   'auto auto 0 0',
                    'bottom right' =>  'auto 0 0 auto',
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_item' => 'margin: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'top_offset',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_item' => '--pos-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'left_offset',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_item' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image',
            ]
        );

        $repeater->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image',
            ]
        );

        $repeater->add_responsive_control(
            'image_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'item_interval',
            [
                'label' => esc_html__('Image Appearing Interval (ms)', 'courto-core'),
                'description' => esc_html__('Delay animation relative to the previous element. Leave empty to default.', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => 0,
                'step' => 50,
            ]
        );

        $repeater->add_control(
            'item_transition_duration',
            [
                'label' => esc_html__('Transition Duration (ms)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => 0,
                'step' => 50,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => 'transition-duration: {{VALUE}}ms;'
                ],
            ]
        );

        $repeater->add_control(
            'image_order',
            [
                'label' => esc_html__('Image z-index', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
				'step' => 1,
                'default' => '1',
            ]
        );

        $repeater->start_controls_tabs( 'image_tabs' );
        $repeater->start_controls_tab(
            'image_start',
            ['label' => esc_html__('Start', 'courto-core')]
        );
        $repeater->add_control(
            'scale_from',
            [
                'label' => esc_html__( 'Scale From', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 20, 'step' => 0.1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => '--wgl-scale: {{SIZE}};',
                ],
            ]
        );
        $repeater->add_control(
            'opacity_from',
            [
                'label' => esc_html__( 'Opacity From', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => '--wgl-opacity: {{SIZE}};',
                ],
            ]
        );
        $repeater->add_control(
            'blur_from',
            [
                'label' => esc_html__( 'Blur From', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50, 'step' => 0.1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => '--wgl-blur: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'vert_pos_from',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => '--wgl-v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'hor_pos_from',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => '--wgl-h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'rotate_from',
            [
                'label' => esc_html__('Rotate From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -2, 'max' => 2, 'step' => 1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => '--wgl-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'image_end',
            ['label' => esc_html__('End', 'courto-core')]
        );
        $repeater->add_control(
            'scale_to',
            [
                'label' => esc_html__( 'Scale To', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 20, 'step' => 0.1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_animate {{CURRENT_ITEM}} .img-layer_image' => '--wgl-scale: {{SIZE}};',
                ],
            ]
        );
        $repeater->add_control(
            'opacity_to',
            [
                'label' => esc_html__( 'Opacity To', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 1, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_animate {{CURRENT_ITEM}} .img-layer_image' => '--wgl-opacity: {{SIZE}};',
                ],
            ]
        );
        $repeater->add_control(
            'blur_to',
            [
                'label' => esc_html__( 'Blur To', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50, 'step' => 0.1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_animate {{CURRENT_ITEM}} .img-layer_image' => '--wgl-blur: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'vert_pos_to',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_animate {{CURRENT_ITEM}} .img-layer_image' => '--wgl-v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'hor_pos_to',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_animate {{CURRENT_ITEM}} .img-layer_image' => '--wgl-h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_control(
            'rotate_to',
            [
                'label' => esc_html__('Rotate To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -2, 'max' => 2, 'step' => 1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_animate {{CURRENT_ITEM}} .img-layer_image' => '--wgl-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $content = '';
        $animation_delay = 0;
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('image-layers', 'class', 'wgl-image-layers');

        if (!empty($settings['image_link']['url'])) {
            $this->add_render_attribute('image_link', 'class', 'image_link');
            $this->add_link_attributes('image_link', $settings['image_link']);
        }

        foreach ($settings[ 'items' ] as $index => $item) {
            $animation_delay = $animation_delay + (int)(is_int($item['item_interval']) ? $item['item_interval'] : $settings['interval']);

            $image_layer = $this->get_repeater_setting_key('image_layer', 'items' , $index);
            $this->add_render_attribute($image_layer, [
                'src' => isset($item['thumbnail']['url']) ? esc_url($item['thumbnail']['url']) : '',
                'alt' => Control_Media::get_image_alt($item['thumbnail']),
            ]);

            $image_wrapper = $this->get_repeater_setting_key('image_wrapper', 'items' , $index);
            $this->add_render_attribute($image_wrapper, [
                'class' => [
                    'img-layer_image-wrapper',
                    'elementor-repeater-item-'. $item['_id'],
                ],
                'style' => 'z-index: '.esc_attr((int)$item['image_order']),
            ]);

            $layer_item = $this->get_repeater_setting_key('layer_item', 'items' , $index);
            $this->add_render_attribute($layer_item, [
                'class' => 'img-layer_item'
            ]);

            $layer_image = $this->get_repeater_setting_key('layer_image', 'items' , $index);
            $this->add_render_attribute($layer_image, [
                'class' => 'img-layer_image',
                'style' => 'transition-delay: '.$animation_delay.'ms;'
            ]);

            ob_start();

            ?><div <?php echo $this->get_render_attribute_string( $image_wrapper ); ?>>
                <div <?php echo $this->get_render_attribute_string( $layer_item ); ?>>
                    <div <?php echo $this->get_render_attribute_string( $layer_image ); ?>>
                        <img <?php echo $this->get_render_attribute_string( $image_layer ); ?> />
                    </div>
                </div>
            </div> <?php

            $content .= ob_get_clean();
        }

        ?><div <?php echo $this->get_render_attribute_string('image-layers'); ?>><?php
            if (!empty($settings['image_link']['url'])) : ?><a <?php echo $this->get_render_attribute_string('image_link'); ?>><?php endif;
                echo $content;
            if (!empty($settings['image_link']['url'])) : ?></a><?php endif;
        ?></div><?php

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