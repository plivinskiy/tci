<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-gallery.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Background,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography,
    Group_Control_Css_Filter
};
use WGL_Extensions\{
    Includes\WGL_Icons,
    Includes\WGL_Webgl_Image_Hover,
    Includes\WGL_Carousel_Settings,
    Includes\WGL_Elementor_Helper
};

class WGL_Gallery extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-gallery';
    }

    public function get_title()
    {
        return esc_html__('WGL Gallery', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-gallery';
    }

    public function get_keywords()
    {
        return [ 'gallery', 'carousel', 'image' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return [
            'swiper',
            'imagesloaded',
            'isotope',
            'gsap-inertia-plugin',
            'jquery-justifiedGallery',
            'wgl-widgets',
        ];
    }

    public function get_style_depends()
    {
        return [ 'swiper' ];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'gallery',
            [
                'type' => Controls_Manager::GALLERY,
			    'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'gallery_layout',
            [
                'label' => esc_html__('Gallery Layout', 'courto-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'justified' => [
                        'title' => esc_html__('Justified', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_justified.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['gallery_layout!' => 'justified'],
                'render_type' => 'template',
                'options' => [
                    '1' => esc_html__('1 (one)', 'courto-core'),
                    '2' => esc_html__('2 (two)', 'courto-core'),
                    '3' => esc_html__('3 (three)', 'courto-core'),
                    '4' => esc_html__('4 (four)', 'courto-core'),
                    '5' => esc_html__('5 (five)', 'courto-core'),
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'prefix_class' => 'col%s-',
            ]
        );

        $this->add_responsive_control(
            'justified_height',
            [
                'label' => esc_html__('Row Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => ['gallery_layout' => 'justified'],
                'render_type' => 'template',
                'range' => [
                    'px' => ['min' => 20, 'max' => 600],
                ],
                'default' => ['size' => 200],
                'tablet_default' => ['size' => 150],
                'mobile_default' => ['size' => 100],
            ]
        );
        $this->add_responsive_control(
            'justified_max_height',
            [
                'label' => esc_html__('Max Row Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => ['gallery_layout' => 'justified'],
                'render_type' => 'template',
                'range' => [
                    'px' => ['min' => 20, 'max' => 600],
                ],
                'default' => ['size' => 200],
                'tablet_default' => ['size' => 150],
                'mobile_default' => ['size' => 100],
            ]
        );

        $this->add_control(
            'justified_last_row',
            [
                'label' => esc_html__('Last Row', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['gallery_layout' => 'justified'],
                'options' => [
                    'nojustify' => esc_html__('No Justify', 'courto-core'),
                    'justify' => esc_html__('Justify', 'courto-core'),
                    'hide' => esc_html__('Hide', 'courto-core'),
                    'left' => esc_html__('Left', 'courto-core'),
                    'center' => esc_html__('Center', 'courto-core'),
                    'right' => esc_html__('Right', 'courto-core'),
                ],
                'default' => 'nojustify',
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'default' => ['size' => 30],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_items:not(.gallery-justified, .gallery-carousel) .wgl-gallery_item-wrapper' => 'padding: calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .wgl-gallery_items:not(.gallery-justified, .gallery-carousel)' => 'margin: calc(-{{SIZE}}px / 2);',

                    '{{WRAPPER}} .wgl-gallery_items.gallery-carousel .wgl-gallery_item-wrapper' => 'padding: 0 calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .wgl-gallery_items.gallery-carousel' => 'margin: 0 calc(-{{SIZE}}px / 2);',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Size', 'courto-core'),
                'condition' => [
                    'gallery_layout' => ['grid', 'carousel']
                ],
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
                'condition' => [
                    'img_size_string' => 'custom',
                    'gallery_layout' => ['grid', 'carousel']
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'courto-core'),
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'gallery_layout' => ['grid', 'carousel']
                ],
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
                'default' => '1:1',
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

        $this->add_control(
            'add_animation',
            [
                'label' => esc_html__('Add Appear Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => ['gallery_layout!' => 'carousel'],
            ]
        );

        $this->add_control(
            'apply_cursor_animation',
            [
                'label' => esc_html__('Add Cursor Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'motion_cursor_animation',
            [
                'label' => esc_html__('Motion Effect', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'condition' => ['apply_cursor_animation' => 'yes'],
                'default' => 230,
                'selectors' => [
                    '{{WRAPPER}}' => '--motion-cursor-animation: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'appear_animation',
            [
                'label' => esc_html__('Animation Style', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'add_animation' => 'yes',
                    'gallery_layout!' => 'carousel'
                ],
                'options' => [
                    'fade-in' => esc_html__('Fade In', 'courto-core'),
                    'slide-top' => esc_html__('Slide Top', 'courto-core'),
                    'slide-bottom' => esc_html__('Slide Bottom', 'courto-core'),
                    'slide-left' => esc_html__('Slide Left', 'courto-core'),
                    'slide-right' => esc_html__('Slide Right', 'courto-core'),
                    'zoom' => esc_html__('Zoom', 'courto-core'),
                ],
                'default' => 'fade-in',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> IMAGE ATTACHMENT
         */

        $this->start_controls_section(
            'content_image_attachment',
            ['label' => esc_html__('Image Attachment', 'courto-core')]
        );

        $this->add_control(
            'info_animation',
            [
                'label' => esc_html__('Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'until_hover' => esc_html__('Visible Until Hover', 'courto-core'),
                    'always' => esc_html__('Always Visible', 'courto-core'),
                    'disable' => esc_html__('Disable', 'courto-core'),
                ],
                'render_type' => 'template',
                'default' => 'disable',
            ]
        );

        /**
         * CONTENT -> ICON/IMAGE
         */

        $output = [];

        WGL_Icons::init(
            $this,
            [
                'output' => $output,
                'section' => false,
                'default' => [
                    'media_type' => '',
                    'icon' => [
                        'library' => 'solid',
                        'value' => 'fas fa-icons'
                    ],
                ],
                'condition' => ['info_animation!' => 'disable'],
                'media_types_options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'courto-core'),
                        'icon' => 'far fa-image',
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['info_animation!' => 'disable'],
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'alt' => esc_html__('Alt', 'courto-core'),
                    'title' => esc_html__('Title', 'courto-core'),
                    'caption' => esc_html__('Caption', 'courto-core'),
                    'description' => esc_html__('Description', 'courto-core'),
                ],
            ]
        );

        $this->add_control(
            'image_descr',
            [
                'label' => esc_html__('Description', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['info_animation!' => 'disable'],
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'alt' => esc_html__('Alt', 'courto-core'),
                    'title' => esc_html__('Title', 'courto-core'),
                    'caption' => esc_html__('Caption', 'courto-core'),
                    'description' => esc_html__('Description', 'courto-core'),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Image Effects
         */
        $this->start_controls_section(
            'wgl_webgl_image_hover_start_section',
            [
                'label' => esc_html__('Webgl Image Hover', 'courto-core'),
            ]
        );
        
        $this->add_control(
            'wgl_webgl_image_hover_heading',
            [
                'label' => esc_html__('WGL Webgl Image Hover Effects', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
                
        $this->add_control(
            'wgl_webgl_image_hover_effects',
            [
                'label' => esc_html__('Image Hover Interaction Effects', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' =>  WGL_Webgl_Image_Hover::get_instance()->image_hover_effects_list(),
                'default' => '',
            ]
        );

        $this->add_control(
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
        
        $this->add_control(
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
        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL SETTINGS
         */

        $this->start_controls_section(
            'content_carousel',
            [
                'label' => esc_html__('Carousel Settings', 'courto-core'),
                'condition' => ['gallery_layout' => 'carousel'],
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this, [
            'chess_layout_enable' => true,
            'variable_width_height' => [ 'wrapper' => '.wgl-gallery_item-wrapper img' ],
        ]);

        $this->add_control(
            'multi_sized_layout',
            [
                'label' => esc_html__( 'Multi-sized Layout', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'scale_odd' => [
                        'title' => esc_html__('Odd', 'courto-core'),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'scale_even' => [
                        'title' => esc_html__('Even', 'courto-core'),
                        'icon' => 'eicon-align-center-v wgl-icon-revert',
                    ],
                    'scale_active' => [
                        'title' => esc_html__('Active/Center', 'courto-core'),
                        'icon' => 'eicon-star-o',
                    ],
                ],
                'prefix_class' => 'layout-',
            ]
        );

        $this->add_responsive_control(
            'scale_size',
            [
                'label' => esc_html__( 'Scale Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'multi_sized_layout!' => '' ],
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 0.7 ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => '--wgl-gallery-scale-size: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['use_pagination' => 'yes'],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this, [
            'pagination_margin' => [
                'default' => [
                    'top' => '20',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
            ]
        ]);

        $this->add_control(
            'pagination_navigation_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_pagination',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_navigation_controls($this);

        $this->add_control(
            'navigation_responsive_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'customize_responsive',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_responsive_controls($this);

        $this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'style_image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .wgl-gallery_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_idle',
                'condition' => ['gallery_layout!' => 'justified'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-gallery_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item::before',
            ]
        );

        $this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'item_css_filters',
			    'selector' => '{{WRAPPER}} .wgl-gallery_item img',
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
                    '{{WRAPPER}} .wgl-gallery_item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'condition' => ['gallery_layout!' => 'justified'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-gallery_item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item::after',
            ]
        );

        $this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'item_css_filters_hover',
			    'selector' => '{{WRAPPER}} .wgl-gallery_item:hover img',
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
                    '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_active',
                'condition' => ['gallery_layout!' => 'justified'],
                'selector' => '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_active',
                'selector' => '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_active',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item::after',
            ]
        );

        $this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'item_css_filters_active',
			    'selector' => '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item img',
		    ]
	    );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> INFO
         */

        $this->start_controls_section(
            'style_info',
            [
                'label' => esc_html__('Info', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['info_animation!' => 'disable'],
            ]
        );

        $this->add_control(
            'info_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'info_vertical',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'default' => 'middle',
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_padding',
            [
                'label' => esc_html__('Info Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'divider_1',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__('Title Typography', 'courto-core'),
                'condition' => ['image_title!' => ''],
                'selector' => '{{WRAPPER}} .wgl-gallery_image-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'condition' => ['image_title!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'descr_typo',
                'label' => esc_html__('Description Typography', 'courto-core'),
                'condition' => ['image_descr!' => ''],
                'selector' => '{{WRAPPER}} .wgl-gallery_image-descr',
            ]
        );

        $this->add_responsive_control(
            'descr_margin',
            [
                'label' => esc_html__('Description Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'condition' => ['image_descr!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-descr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 6, 'max' => 300],
                ],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}} .wgl-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_image_size',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 6, 'max' => 200],
                ],
                'default' => ['size' => 50],
                'condition' => ['icon_type' => 'image'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_rotate',
            [
                'label' => esc_html__('Icon Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon::before,
                     {{WRAPPER}} .wgl-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}}); display: inline-block;',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Icon Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Icon/Image Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'condition' => ['icon_type' => ['font','image']],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Icon Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'condition' => ['icon_type' => 'font'],
                'selector' => '{{WRAPPER}} .wgl-icon',
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Icon Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('title');
        $this->start_controls_tab(
            'title_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['image_title!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'description_color_idle',
            [
                'label' => esc_html__('Description Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['image_descr!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-descr' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_idle',
            [
                'label' => esc_html__('Icon Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'icon_type' => 'font',
                    'icon_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['image_title!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-gallery_image-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'description_color_hover',
            [
                'label' => esc_html__('Description Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['image_descr!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-gallery_image-descr' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__('Icon Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'icon_type' => 'font',
                    'icon_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['image_title!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item .wgl-gallery_image-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'description_color_active',
            [
                'label' => esc_html__('Description Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['image_descr!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item .wgl-gallery_image-descr' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_active',
            [
                'label' => esc_html__('Icon Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item .wgl-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'icon_type' => 'font',
                    'icon_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide-active .wgl-gallery_item .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();
        extract($atts);

        if (isset($wgl_webgl_image_hover_effects) && '' !== $wgl_webgl_image_hover_effects) {
            WGL_Webgl_Image_Hover::get_instance()->enable_scripts($atts);
        }

        // Variables validation
        $gallery = $gallery ?? [];
        $img_size_string = $img_size_string ?? '';
        $img_size_array = $img_size_array ?? [];
        $img_aspect_ratio = $img_aspect_ratio ?? '';
        $open_in_popup = $file_popup ? 'yes' : 'no';
        $item_tag = 'none' === $link_destination ? 'div' : 'a';

        switch ($gallery_layout) {
            case 'masonry':
                $layout_class = 'gallery-masonry';
                break;
            case 'justified':
                $layout_class = 'gallery-justified';
                $this->add_render_attribute('gallery_items', [
                    'data-height' => !empty($justified_height['size']) ? $justified_height['size'] : '200',
                    'data-tablet-height' => !empty($justified_height_tablet['size']) ? $justified_height_tablet['size'] : '150',
                    'data-mobile-height' => !empty($justified_height_mobile['size']) ? $justified_height_mobile['size'] : '100',
                    'data-max-height' => !empty($justified_max_height['size']) ? $justified_max_height['size'] : '200',
                    'data-tablet-max-height' => !empty($justified_max_height_tablet['size']) ? $justified_max_height_tablet['size'] : '150',
                    'data-mobile-max-height' => !empty($justified_max_height_mobile['size']) ? $justified_max_height_mobile['size'] : '100',
                    'data-gap' => !empty($gap['size']) ? $gap['size'] : '10',
                    'data-tablet-gap' => !empty($gap_tablet['size']) ? $gap_tablet['size'] : '10',
                    'data-mobile-gap' => !empty($gap_mobile['size']) ? $gap_mobile['size'] : '10',
                    'data-last-row' => $justified_last_row,
                ]);
                break;
            case 'carousel':
                $layout_class = 'gallery-carousel';
                break;
            default:
                $layout_class = '';
                break;
        }

        //* Gallery order
        if ('random' === $order_by) {
            shuffle($gallery);
        } elseif ('desc' === $order_by) {
            krsort($gallery);
        }

        $this->add_render_attribute('gallery', 'class', 'wgl-gallery');

        $this->add_render_attribute('gallery_items', [
            'class' => [
                'wgl-gallery_items',
                $layout_class,
                !!$apply_cursor_animation ? 'cursor-image-animation' : '',
            ],
        ]);

        $this->add_render_attribute(
            'gallery_item_wrap',
            [
                'class' => [
                    'wgl-gallery_item-wrapper',
                    ( 'carousel' === $gallery_layout ? 'swiper-slide' : '' )
                ],
            ]
        );

        $this->add_render_attribute('gallery_image_info', [
            'class' => [
                'wgl-gallery_image-info',
                !empty($info_animation) ? 'show_' . $info_animation : '',
            ],
        ]);

        //* Appear Animation
        if (
            'carousel' !== $gallery_layout
            && $add_animation
        ) {
            $this->add_render_attribute('gallery_items', [
                'class' => [
                    'appear-animation',
                    $appear_animation,
                ],
            ]);
        }

        ob_start();
        foreach ($gallery as $index => $item) {
            $id = $item[ 'id' ];
            $attachment = get_post( $id );
            $image_data = wp_get_attachment_image_src( $id, 'full' );

            if ( empty( $image_data[ 0 ] ) ) {
                continue;
            }

            $dimensions = WGL_Elementor_Helper::get_image_dimensions(
                $img_size_array ?: $img_size_string,
                $img_aspect_ratio,
                $image_data
            );
            $dimensions[ 'width' ] = $dimensions[ 'width' ] ?? $image_data[ 1 ] ?? null;
            $dimensions[ 'height' ] = $dimensions[ 'height' ] ?? $image_data[ 2 ] ?? null;

            $image_full_url = $image_data[ 0 ];
            $image_resized_url = aq_resize( $image_full_url, $dimensions[ 'width' ], $dimensions[ 'height' ], true, true, true ) ?: $image_full_url;

            // Image Attachment
            $image_arr = [
                'src_full' => $image_full_url,
                'src_resized' => $image_resized_url,
                'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
                'title' => $attachment->post_title,
                'caption' => $attachment->post_excerpt,
                'description' => $attachment->post_content
            ];

            $this->add_render_attribute(
                'gallery_item_' . $index,
                [
                    'class' => [
                        'wgl-gallery_item',
                        isset($wgl_webgl_image_hover_effects) && '' !== $wgl_webgl_image_hover_effects ? 'wgl-webgl-plane_wrapper' : '',
                    ],
                ]
            );

            if (isset($wgl_webgl_image_hover_effects) && '' !== $wgl_webgl_image_hover_effects) {
                $this->add_render_attribute(
                    'gallery_item_' . $index,
                    'data-image-effect',
                    esc_attr($wgl_webgl_image_hover_effects)
                );

                if('ripple' === $wgl_webgl_image_hover_effects){
                    $this->add_render_attribute(
                        'gallery_item_' . $index,
                        'data-trigger',
                        esc_attr($wgl_webgl_image_hover_trigger ?? 'hover')
                    );
                }

                if(isset($wgl_webgl_image_premultiplied_alpha) && 'yes' === $wgl_webgl_image_premultiplied_alpha){
                    $this->add_render_attribute(
                        'gallery_item_' . $index,
                        'data-premultiplied',
                        'yes'
                    );
                }
            }

            //* Link
            switch ($link_destination) {
                case 'file':
                    $this->add_lightbox_data_attributes('gallery_item_' . $index, $id, $open_in_popup, 'all-' . $this->get_id());
                    $this->add_render_attribute('gallery_item_' . $index, [
                        'href' => $image_arr['src_full'],
                    ]);
                    break;

                case 'custom':
                    $custom_link = get_post_meta($id, 'custom_image_link', true);
                    if (!empty($custom_link)) {
                        $this->add_render_attribute('gallery_item_' . $index, [
                            'href' => $custom_link,
                            'target' => $link_target_blank ? '_blank' : '_self',
                        ]);
                        $item_tag = 'a';
                    } else {
                        $item_tag = 'div';
                    }
                    break;
            }

            $this->add_render_attribute( 'gallery_image' . $index, [
                'class' => 'wgl-gallery_image',
                'src' => $image_arr[ 'src_resized' ],
                'alt' => $image_arr[ 'alt' ],
                'loading' => 'lazy'
            ] );

            echo '<div ', $this->get_render_attribute_string('gallery_item_wrap'), '>';
                echo '<', $item_tag, ' ', $this->get_render_attribute_string('gallery_item_' . $index), '>';
                echo '<img ', $this->get_render_attribute_string('gallery_image' . $index), '>'; // gallery image
                echo !empty($this->attachment_info($image_arr, $atts))
                    ? '<div ' . $this->get_render_attribute_string('gallery_image_info') . '>' . $this->attachment_info($image_arr, $atts) . '</div>'
                    : ''; //* attachment info
                echo '</', $item_tag, '>'; //* gallery item
            echo '</div>';
        }
        $gallery_items = ob_get_clean();

        echo '<div ', $this->get_render_attribute_string('gallery'), '>',
            '<div ', $this->get_render_attribute_string('gallery_items'), '>',
                'carousel' === $gallery_layout ? $this->apply_carousel_options($gallery_items) : $gallery_items,
            '</div>',
        '</div>';
    }

    protected function attachment_info($image_arr, $atts)
    {
        $image_title = $this->get_settings_for_display('image_title');
        $image_descr = $this->get_settings_for_display('image_descr');

        ob_start();
        // Media
        if (!empty($atts['icon_type'])) {
            $media = new WGL_Icons;
            echo $media->build($this, $atts, []);
        }

        if ($image_title && !empty($image_arr[$image_title])) {
            echo '<div class="wgl-gallery_image-title">',
                $image_arr[$image_title],
            '</div>';
        }

        if ($image_descr && !empty($image_arr[$image_descr])) {
            echo '<div class="wgl-gallery_image-descr">',
                $image_arr[$image_descr],
            '</div>';
        }

        return ob_get_clean();
    }

    protected function apply_carousel_options($items_html)
    {
        $_s = $this->get_settings_for_display();
        $_s['slides_per_row'] = $_s['columns'];

        return WGL_Carousel_Settings::init($_s, $items_html);
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
