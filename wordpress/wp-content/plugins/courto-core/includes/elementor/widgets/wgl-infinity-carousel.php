<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-carousel.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use WGL_Extensions\Includes\WGL_Elementor_Helper;
use Elementor\{Group_Control_Background,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Css_Filter,
    Widget_Base,
    Controls_Manager,
    Plugin};

class WGL_Infinity_Carousel extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-infinity-carousel';
    }

    public function get_title()
    {
        return esc_html__('WGL Infinity Carousel', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-infinity-carousel';
    }

    public function get_keywords()
    {
        return [ 'carousel', 'infinity', 'splide', 'images' ];
    }

    public function get_script_depends()
    {
        return [
            'splide',
            'splide-extension-auto-scroll',
            'wgl-widgets',
        ];
    }

    public function get_style_depends()
    {
        return [ 'splide' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'content_general',
            ['label' => esc_html__('General' , 'courto-core')]
        );

        $this->add_control(
            'gallery',
            [
                'type' => Controls_Manager::GALLERY,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'auto_width_switcher',
            [
                'label' => esc_html__('Auto Width', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $this->add_responsive_control(
            'auto_width',
            [
                'label' => esc_html__('Set Width for Images', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [ 'auto_width_switcher!' => '' ],
                'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 1000] ],
                'size_units' => ['px', 'custom'],
                'default' => [ 'size' => 370, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .splide__slide' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'per_page_default',
            [
                'label' => esc_html__('Slides per Page (default)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'condition' => [ 'auto_width_switcher' => '' ],
                'default' => 3,
            ]
        );

        $this->add_responsive_control(
            'items_gap_responsive',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'auto_width_switcher!' => '' ],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px', 'custom'],
                'default' => [ 'size' => 30, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .splide__track--ltr .splide__slide' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .splide--ttb .splide__slide' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'items_gap',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'auto_width_switcher' => '' ],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px'],
                'default' => [ 'size' => 30, 'unit' => 'px' ],
            ]
        );

        $this->add_responsive_control(
            'infinity_speed',
            [
                'label' => esc_html__('Speed', 'courto-core'),
                'description' => esc_html__('You can scroll the carousel in the opposite direction by setting a negative value', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [ 'px' => ['min' => -5, 'max' => 5, 'step' => 0.1] ],
                'size_units' => ['px'],
                'default' => [ 'size' => 0, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'carousel_direction',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Direction', 'courto-core'),
                'separator' => 'before',
                'options' => [
                    'ltr' => esc_html__('Horizontal', 'courto-core'),
                    'ttb' => esc_html__('Vertical', 'courto-core'),
                ],
                'default' => 'ltr',
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__('Carousel Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => ['min' => 100, 'max' => 1200],
                ],
                'default' => [ 'size' => 500, 'unit' => 'px' ],
                'condition' => ['carousel_direction' => 'ttb'],
            ]
        );

        $this->end_controls_section();


        /**
         * CONTENT -> Additional Options
         */
        $this->start_controls_section(
            'additional_options', [
            'label' => esc_html__('Additional Options', 'courto-core')
        ]);
        $this->add_responsive_control(
            'carousel_padding',
            [
                'label' => esc_html__('Carousel Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'style_transfer' => true,
                'selectors' => [
                    '{{WRAPPER}} .splide__track' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Size', 'courto-core'),
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('150x150 - Thumbnail', 'courto-core'),
                    '300' => esc_html__('300x300 - Medium', 'courto-core'),
                    '768' => esc_html__('768x768 - Medium Large', 'courto-core'),
                    '1024' => esc_html__('1024x1024 - Large', 'courto-core'),
                    'full' => esc_html__('Full', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => 'full',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'courto-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [ 'img_size_string' => 'custom' ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'courto-core'),
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('No Crop', 'courto-core'),
                    '1:1' => esc_html__('1:1', 'courto-core'),
                    '3:2' => esc_html__('3:2', 'courto-core'),
                    '4:3' => esc_html__('4:3', 'courto-core'),
                    '6:5' => esc_html__('6:5', 'courto-core'),
                    '9:16' => esc_html__('9:16', 'courto-core'),
                    '16:9' => esc_html__('16:9', 'courto-core'),
                    '21:9' => esc_html__('21:9', 'courto-core'),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'link_destination',
            [
                'label' => esc_html__('Link Target', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    'none' => esc_html__('None', 'courto-core'),
                    'file' => esc_html__('Media File', 'courto-core'),
                    'custom' => esc_html__('Custom URL', 'courto-core'),
                ],
                'default' => 'file',
            ]
        );

        $this->add_control(
            'link_custom__notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['link_destination' => 'custom'],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Specify the link in the attachment details of each corresponding image.', 'courto-core'),
            ]
        );

        $this->add_control(
            'link_target_blank',
            [
                'label' => esc_html__('Open in New Tab', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['link_destination' => 'custom'],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'file_popup',
            [
                'label' => esc_html__('Open in Popup', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['link_destination' => 'file'],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'popup_hide_title_description',
            [
                'label' => esc_html__('Hide Title and Description on Popup', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['file_popup' => 'yes'],
                'default' => 'yes',
                'selectors' => [
                    '#elementor-lightbox-slideshow-all-{{ID}} .elementor-slideshow__title,
                     #elementor-lightbox-slideshow-all-{{ID}} .elementor-slideshow__description' => 'display: none;',
                ],
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__('Order By', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'random' => esc_html__('Random', 'courto-core'),
                    'asc' => esc_html__('ASC', 'courto-core'),
                    'desc' => esc_html__('DESC', 'courto-core'),
                ],
                'default' => '',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> Responsive
         */
        $this->start_controls_section(
            'responsive', [
            'label' => esc_html__('Responsive', 'courto-core'),
            'condition' => [ 'auto_width_switcher' => '' ],
        ]);
        $this->add_control(
            'breakpoint_desktop',
            [
                'label' => esc_html__('Width for Desktop and less', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'before',
                'placeholder' => 1200,
                'default' => 1200,
            ]
        );
        $this->add_control(
            'per_page_desktop',
            [
                'label' => esc_html__('Slides', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [ 'breakpoint_desktop!' => '' ],
                'placeholder' => 3,
                'default' => 3,
            ]
        );
        $this->add_control(
            'items_gap_desktop',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'breakpoint_desktop!' => '',
                    'per_page_desktop!' => ''
                ],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px'],
                'default' => [ 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'breakpoint_tablet',
            [
                'label' => esc_html__('Width for Tablet and less', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'before',
                'placeholder' => 768,
                'default' => 768,
            ]
        );
        $this->add_control(
            'per_page_tablet',
            [
                'label' => esc_html__('Slides', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [ 'breakpoint_tablet!' => '' ],
                'placeholder' => 2,
                'default' => 2,
            ]
        );
        $this->add_control(
            'items_gap_tablet',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'breakpoint_tablet!' => '',
                    'per_page_tablet!' => ''
                ],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px'],
                'default' => [ 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'breakpoint_mobile',
            [
                'label' => esc_html__('Width for Mobile and less', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'before',
                'placeholder' => 480,
                'default' => 480,
            ]
        );
        $this->add_control(
            'per_page_mobile',
            [
                'label' => esc_html__('Slides', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [ 'breakpoint_mobile!' => '' ],
                'placeholder' => 1,
                'default' => 1,
            ]
        );
        $this->add_control(
            'items_gap_mobile',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'breakpoint_mobile!' => '',
                    'per_page_mobile!' => ''
                ],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px'],
                'default' => [ 'unit' => 'px' ],
            ]
        );

        $this->end_controls_section();


        /** STYLE -> GENERAl */

        $this->start_controls_section(
            'style_general',
            [
                'label' => esc_html__( 'General', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs('image');
        $this->start_controls_tab(
            'image_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_responsive_control(
            'image_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-splide__slide-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_idle',
                'selector' => '{{WRAPPER}} .wgl-splide__slide-wrapper',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-splide__slide-wrapper',
            ]
        );
        $this->add_responsive_control(
            'image_opacity_idle',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-splide__slide-wrapper' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'item_css_filters',
                'selector' => '{{WRAPPER}} .wgl-splide__slide-wrapper img',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_responsive_control(
            'image_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .splide__slide:hover .wgl-splide__slide-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'selector' => '{{WRAPPER}} .splide__slide:hover .wgl-splide__slide-wrapper',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .splide__slide:hover .wgl-splide__slide-wrapper',
            ]
        );
        $this->add_responsive_control(
            'image_opacity_hover',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .splide__slide:hover .wgl-splide__slide-wrapper' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'item_css_filters_hover',
                'selector' => '{{WRAPPER}} .splide__slide:hover .wgl-splide__slide-wrapper img',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_responsive_control(
            'image_radius_active',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .splide__slide.is-active .wgl-splide__slide-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_active',
                'selector' => '{{WRAPPER}} .splide__slide.is-active .wgl-splide__slide-wrapper',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_active',
                'selector' => '{{WRAPPER}} .splide__slide.is-active .wgl-splide__slide-wrapper',
            ]
        );
        $this->add_responsive_control(
            'image_opacity_active',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .splide__slide.is-active .wgl-splide__slide-wrapper' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'item_css_filters_active',
                'selector' => '{{WRAPPER}} .splide__slide.is-active .wgl-splide__slide-wrapper img',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        // Variables validation
        $img_size_string = $_s['img_size_string'] ?? '';
        $img_size_array = $_s['img_size_array'] ?? [];
        $img_aspect_ratio = $_s['img_aspect_ratio'] ?? '';
        $open_in_popup = $_s['file_popup'] ? 'yes' : 'no';
        $item_tag = 'none' === $_s['link_destination'] ? 'div' : 'a';

        //* Carousel order
        if ('random' === $_s['order_by']) {
            shuffle($_s['gallery']);
        } elseif ('desc' === $_s['order_by']) {
            krsort($_s['gallery']);
        }

        wp_enqueue_script('splide', get_template_directory_uri() . '/js/splide/js/splide.min.js', array(), '4.1.4', false);
        wp_enqueue_script('splide-extension-auto-scroll', get_template_directory_uri() . '/js/splide/js/splide-extension-auto-scroll.min.js', ['splide'], '0.5.3', false);
        wp_enqueue_style('splide', get_template_directory_uri() . '/js/splide/css/splide.min.css', array(), '4.1.4');

        $this->add_render_attribute('splide-wrapper', [
            'class' => ['splide', 'wgl_splide__wrapper'],
            'data-perpage' => $_s['per_page_default'],
            'data-infinity-speed' => (!empty($_s['infinity_speed']['size'])) ? $_s['infinity_speed']['size'] : 0.6,
            'data-infinity-speed-tablet' => (!empty($_s['infinity_speed_tablet']['size'])) ? $_s['infinity_speed_tablet']['size'] : '',
            'data-infinity-speed-mobile' => (!empty($_s['infinity_speed_mobile']['size'])) ? $_s['infinity_speed_mobile']['size'] : '',
            'data-carousel-direction' => $_s['carousel_direction'],
        ]);

        if($_s['carousel_direction'] == 'ttb'){
            $this->add_render_attribute('splide-wrapper', [
                'data-height-desktop' => (!empty($_s['height']['size']) ? $_s['height']['size'] : 500),
                'data-height-tablet' => (!empty($_s['height_tablet']['size']) ? $_s['height_tablet']['size'] : ''),
                'data-height-mobile' => (!empty($_s['height_mobile']['size']) ? $_s['height_mobile']['size'] : ''),
            ]);
        }

        if( isset($_s['auto_width_switcher']) && !$_s['auto_width_switcher']){
            $gap = $_s['items_gap'] && $_s['items_gap']['size'] ? $_s['items_gap']['size'] : 30;
            $gap_desktop = $_s['items_gap_desktop'] && $_s['items_gap_desktop']['size'] ? $_s['items_gap_desktop']['size'] : $gap;
            $gap_tablet = $_s['items_gap_tablet'] && $_s['items_gap_tablet']['size'] ? $_s['items_gap_tablet']['size'] : $gap_desktop;
            $gap_mobile = $_s['items_gap_mobile'] && $_s['items_gap_mobile']['size'] ? $_s['items_gap_mobile']['size'] : $gap_tablet;
            $this->add_render_attribute('splide-wrapper', [
                'data-gap' => $gap,
                'data-breakpoint-desktop' => $_s['breakpoint_desktop'] ?? 1200,
                'data-perpage-desktop' => $_s['per_page_desktop'] ?? 3,
                'data-gap-desktop' => $gap_desktop,
                'data-breakpoint-tablet' => $_s['breakpoint_tablet'] ?? 768,
                'data-perpage-tablet' => $_s['per_page_tablet'] ?? 2,
                'data-gap-tablet' => $gap_tablet,
                'data-breakpoint-mobile' => $_s['breakpoint_mobile'] ?? 480,
                'data-perpage-mobile' => $_s['per_page_mobile'] ?? 1,
                'data-gap-mobile' => $gap_mobile,
            ]);
        }else{
            $this->add_render_attribute('splide-wrapper', [
                'data-auto-width-switcher' => !isset($_s['auto_width_switcher']) && $_s['auto_width_switcher'] || false,
            ]);
        }

        ob_start();
        foreach ($_s['gallery'] as $index => $item) {
            $id = $item[ 'id' ];
            $attachment = get_post( $id );
            $image_data = wp_get_attachment_image_src( $id, 'full' );

            if ( empty( $image_data[ 0 ] ) ) {
                continue;
            }

            if($img_size_string !== 'full'){
                $dimensions = WGL_Elementor_Helper::get_image_dimensions(
                    $img_size_array ?: $img_size_string,
                    $img_aspect_ratio,
                    $image_data
                );
                $dimensions[ 'width' ] = $dimensions[ 'width' ] ?? $image_data[ 1 ] ?? null;
                $dimensions[ 'height' ] = $dimensions[ 'height' ] ?? $image_data[ 2 ] ?? null;

                $image_full_url = $image_data[ 0 ];
                $image_resized_url = aq_resize( $image_full_url, $dimensions[ 'width' ], $dimensions[ 'height' ], true, true, true ) ?: $image_full_url;
            }else{
                $image_resized_url = $image_full_url = $image_data[ 0 ];
            }

            // Image Attachment
            $image_arr = [
                'src_full' => $image_full_url,
                'src_resized' => $image_resized_url,
                'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
                'title' => $attachment->post_title,
                'caption' => $attachment->post_excerpt,
                'description' => $attachment->post_content
            ];

            $this->add_render_attribute( 'splide__slide-wrapper_' . $index, 'class', 'wgl-splide__slide-wrapper' );

            //* Link
            switch ($_s['link_destination']) {
                case 'file':
                    $this->add_lightbox_data_attributes('splide__slide-wrapper_' . $index, $id, $open_in_popup, 'all-' . $this->get_id());
                    $this->add_render_attribute('splide__slide-wrapper_' . $index, [
                        'href' => $image_arr['src_full'],
                    ]);
                    break;

                case 'custom':
                    $custom_link = get_post_meta($id, 'custom_image_link', true);
                    if (!empty($custom_link)) {
                        $this->add_render_attribute('splide__slide-wrapper_' . $index, [
                            'href' => $custom_link,
                            'target' => $_s['link_target_blank'] ? '_blank' : '_self',
                        ]);
                        $item_tag = 'a';
                    } else {
                        $item_tag = 'div';
                    }
                    break;
            }

            $this->add_render_attribute( 'splide__image_' . $index, [
                'class' => 'wgl-splide__image',
                'src' => $image_arr[ 'src_resized' ],
                'alt' => $image_arr[ 'alt' ],
            ] );

            echo '<div class="splide__slide">';
                echo '<', $item_tag, ' ', $this->get_render_attribute_string('splide__slide-wrapper_' . $index), '>';
                    echo '<img ', $this->get_render_attribute_string('splide__image_' . $index), '>'; // carousel image
                    echo !empty($this->attachment_info($image_arr, $_s))
                        ? '<div ' . $this->get_render_attribute_string('splide__image__info') . '>' . $this->attachment_info($image_arr, $_s) . '</div>'
                        : ''; //* attachment info
                echo '</', $item_tag, '>'; //* carousel item
            echo '</div>';
        }
        $carousel_items = ob_get_clean();



        if ( ! empty($_s['gallery']) && is_array($_s['gallery']) ) {
            echo '<div ' . $this->get_render_attribute_string('splide-wrapper') . '>';
                echo '<div class="splide__track"><div class="splide__list">';
                    echo $carousel_items;
                echo '</div></div>';
            echo '</div>';
        }
    }

    protected function attachment_info($image_arr, $_s)
    {
        $image_title = $this->get_settings_for_display('image_title');
        $image_descr = $this->get_settings_for_display('image_descr');

        ob_start();

        if ($image_title && !empty($image_arr[$image_title])) {
            echo '<div class="wgl-splide__image-title">',
            $image_arr[$image_title],
            '</div>';
        }

        if ($image_descr && !empty($image_arr[$image_descr])) {
            echo '<div class="wgl-splide__image-descr">',
            $image_arr[$image_descr],
            '</div>';
        }

        return ob_get_clean();
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
