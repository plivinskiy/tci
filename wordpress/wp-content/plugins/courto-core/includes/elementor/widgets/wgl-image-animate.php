<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-infobox.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.


use Elementor\{Group_Control_Box_Shadow, Repeater, Widget_Base, Controls_Manager, Control_Media};


class WGL_Image_Animate extends Widget_Base {

    public function get_name() {
        return 'wgl-image-animate';
    }

    public function get_title() {
        return esc_html__('WGL Image Animate', 'courto-core');
    }

    public function get_icon() {
        return 'wgl-image-animate';
    }

    public function get_keywords() {
        return ['image', 'animate'];
    }

    public function get_categories() {
        return ['wgl-modules'];
    }

    public function get_script_depends() {
        return [ 'jquery-appear',  'gsap-inertia-plugin' ];
    }


    protected function register_controls() {

        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'section_content_general',
            [
                'label' => esc_html__('General', 'courto-core'),
            ]
        );

        $this->add_control(
            'image_display',
            [
                'label' => esc_html__( 'Display', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'absolute' => esc_html__( 'Absolute', 'courto-core' ),
                    'flex' => esc_html__( 'Flex', 'courto-core' ),
                    'grid' => esc_html__( 'Grid', 'courto-core' ),
                ],
                'default' => 'absolute',
            ]
        );

        // ---------------- FLEX OPTIONS ----------------
        $this->add_responsive_control(
            'flex_direction',
            [
                'label'     => esc_html__( 'Flex Direction', 'courto-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'row'            => esc_html__( 'Row', 'courto-core' ),
                    'row-reverse'    => esc_html__( 'Row Reverse', 'courto-core' ),
                    'column'         => esc_html__( 'Column', 'courto-core' ),
                    'column-reverse' => esc_html__( 'Column Reverse', 'courto-core' ),
                ],
                'default'   => 'row',
                'condition' => [
                    'image_display' => 'flex',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-animate' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'flex_align',
            [
                'label'     => esc_html__( 'Align Items', 'courto-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'flex-start' => esc_html__( 'Start', 'courto-core' ),
                    'center'     => esc_html__( 'Center', 'courto-core' ),
                    'flex-end'   => esc_html__( 'End', 'courto-core' ),
                    'stretch'    => esc_html__( 'Stretch', 'courto-core' ),
                    'baseline'   => esc_html__( 'Baseline', 'courto-core' ),
                ],
                'default'   => 'center',
                'condition' => [
                    'image_display' => 'flex',
                    'image_display' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-animate' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'flex_justify',
            [
                'label'     => esc_html__( 'Justify Content', 'courto-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'flex-start'    => esc_html__( 'Start', 'courto-core' ),
                    'center'        => esc_html__( 'Center', 'courto-core' ),
                    'flex-end'      => esc_html__( 'End', 'courto-core' ),
                    'space-between' => esc_html__( 'Space Between', 'courto-core' ),
                    'space-around'  => esc_html__( 'Space Around', 'courto-core' ),
                    'space-evenly'  => esc_html__( 'Space Evenly', 'courto-core' ),
                ],
                'default'   => 'flex-start',
                'condition' => [
                    'image_display' => 'flex',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-animate' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_justify_items',
            [
                'label'     => esc_html__( 'Justify Items', 'courto-core' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'start'   => esc_html__( 'Start', 'courto-core' ),
                    'center'  => esc_html__( 'Center', 'courto-core' ),
                    'end'     => esc_html__( 'End', 'courto-core' ),
                    'stretch' => esc_html__( 'Stretch', 'courto-core' ),
                ],
                'default'   => 'center',
                'condition' => [
                    'image_display' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-animate' => 'justify-items: {{VALUE}};',
                ],
            ]
        );

        // ---------------- GRID OPTIONS ----------------
        $this->add_responsive_control(
            'grid_columns',
            [
                'label'     => esc_html__( 'Columns', 'courto-core' ),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 12,
                'default'   => 3,
                'condition' => [
                    'image_display' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-animate' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label'     => esc_html__( 'Gap', 'courto-core' ),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'size_units'=> [ 'px', 'em', '%' ],
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'condition' => [
                    'image_display' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-animate' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'apply_cursor_animation',
            [
                'label' => esc_html__('Apply Cursor Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'motion_cursor_animation',
            [
                'label' => esc_html__('Motion Effect', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'default' => 230,
                'condition' => ['apply_cursor_animation' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}}' => '--motion-cursor-animation: {{VALUE}};',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Thumbnail', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => '',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2560 ],
                ],
                'condition' => [ 'thumbnail[url]!' => '' ],
                'size_units' => ['px', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .display-absolute div{{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}}; max-width: unset',
                    '{{WRAPPER}} .display-flex {{CURRENT_ITEM}} .img-layer_item' => 'width: {{SIZE}}{{UNIT}}; max-width: unset',
                    '{{WRAPPER}} .display-grid {{CURRENT_ITEM}} .img-layer_item' => 'width: {{SIZE}}{{UNIT}}; max-width: unset',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'image_max_width',
            [
                'label' => esc_html__('Image Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2560 ],
                ],
                'condition' => [
                    'thumbnail[url]!' => '',
                    'image_width[size]!' => [0, ''],
                ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .display-absolute div{{CURRENT_ITEM}}' => 'max-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .display-flex {{CURRENT_ITEM}} .img-layer_item' => 'max-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .display-grid {{CURRENT_ITEM}} .img-layer_item' => 'max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_control(
            'image_link',
            [
                'label' => esc_html__('Add Image Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'top_offset',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => -1000,
                'max' => 1000,
				'step' => 1,
				'default' => '0',
                'description' => esc_html__('Enter offset in %, for example -100% or 100%', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_item' => '--offset-top: {{VALUE}}%',
                ],
            ]
        );

        $repeater->add_control(
            'left_offset',
            [
                'label' => esc_html__('Left Offset', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => -1000,
                'max' => 1000,
				'step' => 1,
				'default' => '0',
                'description' => esc_html__('Enter offset in %, for example -100% or 100%', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_item' => '--offset-left: {{VALUE}}%',
                ],
            ]
        );

        $repeater->add_control(
            'image_animation',
            [
                'label' => esc_html__('Layer Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'courto-core'),
                    'up_down1' => esc_html__('Up Down 1', 'courto-core'),
                    'up_down2' => esc_html__('Up Down 2', 'courto-core'),
                    'up_down3' => esc_html__('Up Down 3', 'courto-core'),
                    'left_right1' => esc_html__('Left Right 1', 'courto-core'),
                    'left_right2' => esc_html__('Left Right 2', 'courto-core'),
                    'left_right3' => esc_html__('Left Right 3', 'courto-core'),
                    'move1' => esc_html__('Move 1', 'courto-core'),
                    'move2' => esc_html__('Move 2', 'courto-core'),
                    'move3' => esc_html__('Move 3', 'courto-core'),
                    'move4' => esc_html__('Move 4', 'courto-core'),
                    'move-rotate1' => esc_html__('Move with Rotate 1', 'courto-core'),
                    'move-rotate2' => esc_html__('Move with Rotate 2', 'courto-core'),
                    'move-rotate3' => esc_html__('Move with Rotate 3', 'courto-core'),
                    'move-rotate4' => esc_html__('Move with Rotate 4', 'courto-core'),
                    'scale1' => esc_html__('Scale 1', 'courto-core'),
                    'scale2' => esc_html__('Scale 2', 'courto-core'),
                    'scale3' => esc_html__('Scale 3', 'courto-core'),
                    'modern1' => esc_html__('Modern 1', 'courto-core'),
                    'modern2' => esc_html__('Modern 2', 'courto-core'),
                    'modern3' => esc_html__('Modern 3', 'courto-core'),
                ],
                'default' => 'none',
            ]
        );

        $repeater->add_control(
            'anim_duration',
            [
                'label' => esc_html__('Animation Duration (in sec)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'image_animation!' => ['none'] ],
				'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => 'animation-duration: {{VALUE}}s',
                ],
            ]
        );

        $repeater->add_control(
            'anim_duration_rotate',
            [
                'label' => esc_html__('Animation Duration Rotate (in sec)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'image_animation' => ['modern1', 'modern2', 'modern3'] ],
				'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_item__inner' => 'animation-duration: {{VALUE}}s',
                ],
            ]
        );


        $avaliable_timing_function = [
            'linear' => esc_html__( 'Default | Linear', 'courto-core' ),
            'ease' => esc_html__( 'Ease', 'courto-core' ),
            'ease-in' => esc_html__( 'Ease-In', 'courto-core' ),
            'ease-out' => esc_html__( 'Ease-Out', 'courto-core' ),
            'ease-in-out' => esc_html__( 'Ease-In-Out', 'courto-core' ),
            'cubic-bezier(0.94, 0.87, 0.87, 1)' => esc_html__( 'WGL Easy Linear', 'courto-core' ),
        ];
        $repeater->add_control(
            'anim_timing_func',
            [
                'label' => esc_html__('Animation Timing Func', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $avaliable_timing_function,
                'default' => 'cubic-bezier(0.94, 0.87, 0.87, 1)',
                'condition' => [ 'image_animation!' => 'none' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .img-layer_image' => 'animation-timing-function: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'image_rotate',
            [
                'label' => esc_html__('Rotate', 'courto-core'),
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
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--wgl-rotate-image: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} img',
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
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $repeater->add_control(
            'image_blend',
            [
                'label' => esc_html__('Blend Mode', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    '' => esc_html__('Disabled', 'courto-core'),
                    'multiply' => esc_html__('Multiply', 'courto-core'),
                    'screen' => esc_html__('Screen', 'courto-core'),
                    'overlay' => esc_html__('Overlay', 'courto-core'),
                    'darken' => esc_html__('Darken', 'courto-core'),
                    'lighten' => esc_html__('Lighten', 'courto-core'),
                    'color-dodge' => esc_html__('Color Dodge', 'courto-core'),
                    'saturation' => esc_html__('Saturation', 'courto-core'),
                    'color' => esc_html__('Color', 'courto-core'),
                    'difference' => esc_html__('Difference', 'courto-core'),
                    'exclusion' => esc_html__('Exclusion', 'courto-core'),
                    'hue' => esc_html__('Hue', 'courto-core'),
                    'luminosity' => esc_html__('Luminosity', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_general',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'images_width',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2560 ],
                ],
                'condition' => [ 'thumbnail[url]!' => '' ],
                'size_units' => ['px', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .display-absolute .img-layer_image-wrapper' => 'width: {{SIZE}}{{UNIT}}; max-width: unset',
                    '{{WRAPPER}} .display-flex .img-layer_item' => 'width: {{SIZE}}{{UNIT}}; max-width: unset',
                    '{{WRAPPER}} .display-grid .img-layer_item' => 'width: {{SIZE}}{{UNIT}}; max-width: unset',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_max_width',
            [
                'label' => esc_html__('Image Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2560 ],
                ],
                'condition' => [
                    'thumbnail[url]!' => '',
                    'image_width[size]!' => [0, ''],
                ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .display-absolute .img-layer_image-wrapper' => 'max-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .display-flex .img-layer_item' => 'max-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .display-grid .img-layer_item' => 'max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_margin',
            [
                'label' => esc_html__('Margin for Each Element', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .img-layer_image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'images_radius',
            [
                'label' => esc_html__('Image Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'images_shadow',
                'selector' => '{{WRAPPER}} img',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {

        wp_enqueue_script('jquery-appear', get_template_directory_uri() . '/js/jquery.appear.js', [], false, false);

        $content = '';
        $_s = $this->get_settings_for_display();

        $this->add_render_attribute('image-animate', 'class',
            [
                'wgl-image-animate',
                'display-' . esc_attr($_s['image_display']),
                !!$_s['apply_cursor_animation'] ? 'cursor-image-animation' : '',
            ]
        );

        foreach ( $_s[ 'items' ] as $index => $item ) {

            $image_link = $item['image_link'] ?? '';
            $has_link = !empty($image_link['url']);
            if ($has_link) {
                $this->add_link_attributes('image-link'.$index, $image_link);
            }


            $image_wrapper = $this->get_repeater_setting_key( 'image_wrapper', 'items' , $index );
            $this->add_render_attribute( $image_wrapper, [
                'class' => [
                    'img-layer_image-wrapper',
                    'elementor-repeater-item-'. $item['_id'],
                    esc_attr($item[ 'image_animation' ])
                ],
            ] );

            ob_start();

            ?><div <?php echo $this->get_render_attribute_string( $image_wrapper ); ?>>
                <div class="img-layer_item">
                    <div class="img-layer_item__inner">
                        <div class="img-layer_image"><?php
                            echo $has_link ? '<a ' . $this->get_render_attribute_string('image-link' . $index) . '>' : '';
                                ?><img src="<?php echo esc_url($item[ 'thumbnail' ][ 'url' ]); ?>" alt="<?php echo Control_Media::get_image_alt( $item[ 'thumbnail' ] ); ?>"/><?php
                            echo $has_link ? '</a>' : '';
                        ?></div>
                    </div>
                </div>
            </div><?php

            $content .= ob_get_clean();
        }

        ?><div <?php echo $this->get_render_attribute_string( 'image-animate' ); ?>><?php
            echo $content;
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