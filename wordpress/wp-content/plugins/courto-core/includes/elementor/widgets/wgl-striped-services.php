<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-striped-services.php`.
 */

namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Image_Size,
    Icons_Manager,
    Plugin,
    Utils,
    Embed,
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Group_Control_Background,
    Group_Control_Typography,
    Repeater};

use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals
};

class Wgl_Striped_Services extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-striped-services';
    }

    public function get_title()
    {
        return esc_html__('WGL Striped Services', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-striped-services';
    }

    public function get_keywords()
    {
        return [ 'striped', 'services', 'images', 'text', 'animation' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['jquery-appear'];
    }

    protected function register_controls()
    {
        /** CONTENT -> GENERAL */

        $this->start_controls_section(
            'wgl_striped_services_section',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_responsive_control(
            'interval',
            [
                'label' => esc_html__('Widget Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', 'custom'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 1000],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'default' => ['size' => 'min(50vw, 830px)', 'unit' => 'custom'],
                'tablet_default' => ['size' => 500, 'unit' => 'px'],
                'mobile_default' => ['size' => 700, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped-services' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'serv_proportion_active',
            [
                'label' => esc_html__('Proportions for Active Sections', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 1, 'max' => 20, 'step' => 0.1] ],
                'default' => ['size' => 2.76],
                'tablet_default' => ['size' => 3.5],
                'mobile_default' => ['size' => 3],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped-services .wgl-striped.active' => 'flex: {{SIZE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'serv_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '50',
                    'right' => '90',
                    'bottom' => '52',
                    'left' => '50',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '30',
                    'right' => '80',
                    'bottom' => '30',
                    'left' => '30',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '30',
                    'right' => '20',
                    'bottom' => '30',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'stretch_to_right_edge',
            [
                'label' => esc_html__('Stretch Widget to the Right Edge of Window', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped-services' => 'margin-right: calc(50% - 50vw); transition: .4s;'
                ],
            ]
        );

        $this->add_mobile_breakpoint();

        $this->end_controls_section();

        /** CONTENT -> CONTENT */

        $this->start_controls_section(
            'wgl_content_section',
            ['label' => esc_html__('Content', 'courto-core')]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'serv_link',
            [
                'label' => esc_html__('Add Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [ 'url' => '#' ],
            ]
        );

        $repeater->add_control(
            'striped_icon_type',
            [
                'label' => esc_html__('Add Icon/Image', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
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
                    ]
                ],
                'default' => '',
            ]
        );
        $repeater->add_control(
            'striped_icon_fontawesome',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'description' => esc_html__('Select icon from Fontawesome library.', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [ 'striped_icon_type'  => 'font' ],
                'default' => [
                    'library' => 'solid',
                    'value' => 'fas fa-icons'
                ],
            ]
        );
        $repeater->add_control(
            'striped_icon_thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => [ 'striped_icon_type' => 'image' ],
                'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            ]
        );

        $repeater->add_control(
            'serv_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Service Title', 'courto-core'),
                'placeholder' => esc_html__('Service Title', 'courto-core'),
            ]
        );
        $repeater->add_control(
            'serv_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_html__('Service Subtitle', 'courto-core'),
            ]
        );
        $repeater->add_control(
            'serv_bg_text',
            [
                'label' => esc_html__('Background Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_html__('01', 'courto-core'),
                'default' => esc_html__('01', 'courto-core'),
            ]
        );
        $repeater->add_control(
            'serv_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'courto-core'),
            ]
        );
        $repeater->add_control(
            'serv_def_active',
            [
                'label' => esc_html__('Active as Default', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'serv_background',
                'label' => esc_html__('Background', 'courto-core'),
                'separator' => 'before',
                'types' => ['classic'],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => 'classic',
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'image' => [
                        'selectors' => [ '{{SELECTOR}} .image' => 'background-image: url("{{URL}}");' ],
                    ],
                    'position' => [
                        'default' => 'center center',
                        'selectors' => [ '{{SELECTOR}} .image' => 'background-position: {{VALUE}};' ],
                    ],
                    'xpos' => [
                        'selectors' => [ '{{SELECTOR}} .image' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}};' ],
                    ],
                    'ypos' => [
                        'selectors' => [ '{{SELECTOR}} .image' => 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}};' ],
                    ],
                    'attachment' => [
                        'selectors' => [ '(desktop+){{SELECTOR}} .image' => 'background-attachment: {{VALUE}};' ],
                    ],
                    'repeat' => [
                        'default' => 'no-repeat',
                        'selectors' => [ '{{SELECTOR}} .image' => 'background-repeat: {{VALUE}};' ],
                    ],
                    'size' => [
                        'default' => 'cover',
                        'selectors' => [ '{{SELECTOR}} .image' => 'background-size: {{VALUE}};' ],
                    ],
                    'bg_width' => [
                        'selectors' => [ '{{SELECTOR}} .image' => 'background-size: {{SIZE}}{{UNIT}} auto;' ],
                    ],
                ],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .service-image',
            ]
        );

        $repeater->add_control(
            'add_video_background',
            [
                'label' => esc_html__('Add Background Video', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => '',
            ]
        );

        $repeater->add_control(
            'video_source',
            [
                'label' => esc_html__('Video Source', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'vimeo' => esc_html__('Vimeo', 'courto-core'),
                    'self_hosted' => esc_html__('Self-Hosted', 'courto-core'),
                ],
                'condition' => [
                    'add_video_background' => 'yes',
                ],
                'separator' => 'before',
                'default' => 'vimeo',
            ]
        );

        $repeater->add_control(
            'background_video_link',
            [
                'label' => esc_html__('Video Link', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'description' => esc_html__('Enter Vimeo or Self Hosted video.', 'courto-core'),
                'placeholder' => esc_attr__('https://vimeo.com/259976635', 'courto-core'),
                'default' => 'https://vimeo.com/259976635',
                'condition' => [
                    'add_video_background' => 'yes',
                    'video_source' => 'vimeo',
                ],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $repeater->add_control(
            'self_hosted_video',
            [
                'label' => esc_html__('Self-Hosted Video', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => ['video'],
                'dynamic' => ['active' => true],
                'condition' => [
                    'add_video_background' => 'yes',
                    'video_source' => 'self_hosted',
                ],
                'description' => esc_html__('Select a video file (e.g., MP4) from your media library.', 'courto-core'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{serv_title}}',
                'default' => [
                    [
                        'serv_title' => esc_html__('Service Title 1', 'courto-core'),
                        'serv_bg_text' => esc_html__('1', 'courto-core'),
                        'serv_background_color' => '#000000', // ← тут
                        'serv_def_active' => 'yes'
                    ],
                    [
                        'serv_title' => esc_html__('Service Title 2', 'courto-core'),
                        'serv_bg_text' => esc_html__('2', 'courto-core'),
                        'serv_background_color' => WGL_Globals::get_secondary_color()
                    ],
                    [
                        'serv_title' => esc_html__('Service Title 3', 'courto-core'),
                        'serv_bg_text' => esc_html__('3', 'courto-core'),
                        'serv_background_color' => '#868686'
                    ],
                    [
                        'serv_title' => esc_html__('Service Title 4', 'courto-core'),
                        'serv_bg_text' => esc_html__('4', 'courto-core'),
                        'serv_background_color' => '#8f8f8f'
                    ],
                ],
            ]
        );

        $this->add_control(
            'deprecated_notice',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Two or more items are expected for correct rendering', 'courto-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> LINK
         */

        $this->start_controls_section(
            'section_style_link',
            ['label' => esc_html__('Link', 'courto-core')]
        );

        $this->add_control(
            'link_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: For the link to be applied, make sure you enter a link to each item.', 'courto-core'),
            ]
        );

        $this->add_control(
            'module_link',
            [
                'label' => esc_html__('Whole Module Link', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );
        $this->add_control(
            'add_read_more',
            [
                'label' => esc_html__('\'Read More\' Button', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Use', 'courto-core'),
                'label_off' => esc_html__('Hide', 'courto-core'),
                'default' => 'yes'
            ]
        );
        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Button Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'condition' => [ 'add_read_more' => 'yes' ],
                'label_block' => true,
                'placeholder' => esc_html__('Read More', 'courto-core'),
                'default' => esc_html__('READ MORE', 'courto-core'),
            ]
        );
        $this->add_control(
            'button_type',
            [
                'label' => esc_html__( 'Button Type', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'style_transfer' => true,
                'options' => [
                    'wgl-button' => esc_html__( 'Default Button Style', 'courto-core' ),
                    'button-read-more' => esc_html__( 'Read More Style', 'courto-core' ),
                ],
                'condition' => [ 'add_read_more' => 'yes' ],
                'default' => 'button-read-more',
            ]
        );
        $this->add_control(
            'read_more_anim',
            [
                'label' => esc_html__('Read More Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'style_transfer' => true,
                'render_type' => 'template',
                'options' => [
                    'default' => esc_html__( 'Default', 'courto-core' ),
                    'disable' => esc_html__( 'Disable', 'courto-core' ),
                ],
                'condition' => [
                    'add_read_more' => 'yes',
                    'button_type' => 'button-read-more',
                ],
                'prefix_class' => 'button_animation-',
                'default' => 'default',
            ]
        );
        $this->add_control(
            'read_more_icon_type',
            [
                'label' => esc_html__('Icon Type', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'label_block' => false,
                'condition' => [
                    'add_read_more' => 'yes',
                    'button_type' => 'wgl-button',
                ],
                'toggle' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
                    ],
                ],
                'default' => '',
            ]
        );
        $this->add_control(
            'read_more_icon_fontawesome',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_icon_type' => 'font',
                    'button_type' => 'wgl-button',
                ],
                'label_block' => true,
                'description' => esc_html__('Select icon from available libraries.', 'courto-core'),
                'default' => [
                    'library' => 'fa-solid',
                    'value' => 'fas fa-icons',
                ],
            ]
        );
        $this->add_control(
            'read_more_icon_align',
            [
                'label' => esc_html__( 'Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_icon_type!' => '',
                    'button_type' => 'wgl-button',
                ],
                'options' => [
                    'top' => esc_html__( 'Top', 'courto-core' ),
                    'left' => esc_html__( 'Before', 'courto-core' ),
                    'right' => esc_html__( 'After', 'courto-core' ),
                ],
                'default' => 'right',
            ]
        );
        $this->add_responsive_control(
            'button_icon_top',
            [
                'label' => esc_html__('Icon Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => -50, 'max' => 50],
                ],
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_text!' => '',
                    'read_more_icon_type' => 'font',
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-translate-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_stroke_size',
            [
                'label' => esc_html__('SVG Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 300],
                ],
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_icon_fontawesome[library]' => 'svg',
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon path' => 'stroke-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> MEDIA
         */

        $this->start_controls_section(
            'section_style_thumbnail',
            [
                'label' => esc_html__('Background Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'media_overlay_blend',
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
                    '{{WRAPPER}} .service-image .image' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );
        $this->start_controls_tabs('media_overlay_tabs');
        $this->start_controls_tab(
            'media_overlay_tab_before',
            ['label' => esc_html__('First Layer', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'media_overlay_before',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [ 'default' => '' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => WGL_Globals::get_primary_color(0),
                    ],
                    'color_b' => [
                        'label' => esc_html__( 'Second Background Color', 'courto-core' ),
                        'default' => WGL_Globals::get_primary_color(0.6),
                    ],
                    'color_stop' => [
                        'default' => [
                            'unit' => 'custom',
                            'size' => 'calc(100% - 167px)',
                        ],
                    ],
                    'gradient_angle' => [
                        'default' => [
                            'unit' => 'deg',
                            'size' => 180,
                        ],
                    ],
                    'gradient_position' => [
                        'options' => [
                            'center center' => esc_html__( 'Center Center', 'courto-core' ),
                            'center left' => esc_html__( 'Center Left', 'courto-core' ),
                            'center right' => esc_html__( 'Center Right', 'courto-core' ),
                            'top center' => esc_html__( 'Top Center', 'courto-core' ),
                            'top left' => esc_html__( 'Top Left', 'courto-core' ),
                            'top right' => esc_html__( 'Top Right', 'courto-core' ),
                            'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                            'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                            'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                            'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                        ],
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],

                ],
                'selector' => '{{WRAPPER}} .wgl-striped .service-image::before',
            ]
        );
        $this->add_responsive_control(
            'media_overlay_before_gradient_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'media_overlay_before_background' => 'gradient',
                    'media_overlay_before_gradient_type' => 'radial',
                    'media_overlay_before_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::before' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_overlay_before_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'media_overlay_before_background' => 'gradient',
                    'media_overlay_before_gradient_type' => 'radial',
                    'media_overlay_before_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::before' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'media_overlay_before_z_index',
            [
                'label' => esc_html__( 'Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'step' => 1,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::before' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_overlay_before_idle',
            [
                'label' => esc_html__('Opacity From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'media_overlay_before_background!' => '' ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_overlay_before_active',
            [
                'label' => esc_html__('Opacity To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'media_overlay_before_background!' => '' ],
                'default' => ['size' => 1, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .service-image::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'media_overlay_tab_after',
            ['label' => esc_html__('Second Layer', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'media_overlay_after',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [ 'default' => '' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                    ],
                    'color_b' => [
                        'label' => esc_html__( 'Second Background Color', 'courto-core' ),
                    ],
                    'gradient_position' => [
                        'options' => [
                            'center center' => esc_html__( 'Center Center', 'courto-core' ),
                            'center left' => esc_html__( 'Center Left', 'courto-core' ),
                            'center right' => esc_html__( 'Center Right', 'courto-core' ),
                            'top center' => esc_html__( 'Top Center', 'courto-core' ),
                            'top left' => esc_html__( 'Top Left', 'courto-core' ),
                            'top right' => esc_html__( 'Top Right', 'courto-core' ),
                            'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                            'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                            'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                            'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                        ],
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                        ],
                    ],

                ],
                'selector' => '{{WRAPPER}} .wgl-striped .service-image::after',
            ]
        );
        $this->add_responsive_control(
            'media_overlay_after_gradient_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'media_overlay_after_background' => 'gradient',
                    'media_overlay_after_gradient_type' => 'radial',
                    'media_overlay_after_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::after' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_overlay_after_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'media_overlay_after_background' => 'gradient',
                    'media_overlay_after_gradient_type' => 'radial',
                    'media_overlay_after_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::after' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'media_overlay_after_z_index',
            [
                'label' => esc_html__( 'Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'step' => 1,
                'default' => 2,
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::after' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_overlay_after_idle',
            [
                'label' => esc_html__('Opacity From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'media_overlay_after_background!' => '' ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .service-image::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_overlay_after_active',
            [
                'label' => esc_html__('Opacity To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'media_overlay_after_background!' => '' ],
                'default' => ['size' => 1, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .service-image::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> ICON/IMAGE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon/Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'striped_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200, 'step' => 1 ],
                ],
                'default' => ['size' => 50, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon.wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 300 ],
                    '%' => ['min' => 5, 'max' => 80 ],
                ],
                'default' => ['size' => 100, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon.wgl-image' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'striped_icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '15',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'striped_icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'striped_icon_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'striped_icon_border',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .wgl-striped_icon',
            ]
        );

        $this->start_controls_tabs( 'striped_icon_tabs' );
        $this->start_controls_tab(
            'striped_icon_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'striped_icon_color',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'striped_icon_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'striped_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'striped_icon_opacity_idle',
            [
                'label' => esc_html__('Opacity From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_icon' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'striped_icon_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'striped_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'striped_icon_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'striped_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'striped_icon_opacity_hover',
            [
                'label' => esc_html__('Opacity To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_icon' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [
                        'default' => ['size' => 'clamp(22px, 2.083vw, 40px)', 'unit' => 'custom'],
                    ],
                    'line_height' => ['default' => ['size' => 1, 'unit' => 'em']],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .wgl-striped_title',
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_max_width',
            [
                'label' => esc_html__('Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 850],
                ],
                'size_units' => ['px', 'vw', 'custom'],
                'default' => ['size' => 'min(32vw, 520px)', 'unit' => 'custom'],
                'tablet_default' => ['size' => 40, 'unit' => 'vw'],
                'mobile_default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_title' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('title_colors');
        $this->start_controls_tab(
            'title_colors_normal',
            ['label' => esc_html__('Normal', 'courto-core')]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_opacity_idle',
            [
                'label' => esc_html__('Opacity From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_title' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_colors_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_opacity_hover',
            [
                'label' => esc_html__('Opacity To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_title' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> SUBTITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'subtitle_style_section',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typo',
                'selector' => '{{WRAPPER}} .wgl-striped_subtitle',
            ]
        );
        $this->add_control(
            'subtitle_tag',
            [
                'label' => esc_html__('Subtitle HTML Tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                ],
                'default' => 'div',
            ]
        );
        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_max_width',
            [
                'label' => esc_html__('Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 850],
                ],
                'default' => ['size' => 'min(35vw, 460px)', 'unit' => 'custom'],
                'tablet_default' => ['size' => 40, 'unit' => 'vw'],
                'mobile_default' => ['size' => 320, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_subtitle' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('subtitle_colors');
        $this->start_controls_tab(
            'subtitle_colors_normal',
            ['label' => esc_html__('Normal', 'courto-core')]
        );
        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_opacity_idle',
            [
                'label' => esc_html__('Opacity From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_subtitle' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'subtitle_colors_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'subtitle_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_opacity_hover',
            [
                'label' => esc_html__('Opacity To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_subtitle' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [ 'default' => ['size' => 18, 'unit' => 'px'] ],
                    'line_height' => ['default' => ['size' => 1.667, 'unit' => 'em']],
                ],
                'selector' => '{{WRAPPER}} .wgl-striped_content',
            ]
        );
        $this->add_responsive_control(
            'content_max_width',
            [
                'label' => esc_html__('Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 850],
                ],
                'size_units' => ['px', 'vw', 'custom'],
                'default' => ['size' => 'min(35vw, 480px)', 'unit' => 'custom'],
                'tablet_default' => ['size' => 40, 'unit' => 'vw'],
                'mobile_default' => ['size' => 320, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'custom' ],
                'default' => [
                    'top' => '14',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'content_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .wgl-striped_content',
            ]
        );
        $this->add_responsive_control(
            'content_p_bottom_margin',
            [
                'label' => esc_html__('Margin Bottom for "p" selector', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content p:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_li_bottom_margin',
            [
                'label' => esc_html__('Margin Bottom for "li" selector', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('content_tabs');
        $this->start_controls_tab(
            'content_tab_idle',
            ['label' => esc_html__('Normal', 'courto-core')]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( 'Content Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__( 'Content Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_opacity_idle',
            [
                'label' => esc_html__('Opacity From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_content' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'content_tab_hover',
            ['label' => esc_html__('Item Hover', 'courto-core')]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__( 'Content Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_bg_color_hover',
            [
                'label' => esc_html__( 'Content Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_opacity_hover',
            [
                'label' => esc_html__('Opacity To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_content' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> BACKGROUND TEXT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'bg_text_style_section',
            [
                'label' => esc_html__('Background Text', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bg_text_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [
                        'default' => ['size' => 'clamp(86px, 13vw, 200px)', 'unit' => 'custom'],
                    ],
                ],
                'selector' => '{{WRAPPER}} .wgl-striped_bg_text',
            ]
        );

        $this->add_responsive_control(
            'bg_text_position',
            [
                'label' => esc_html__('Text Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'static' => esc_html__('Static', 'courto-core'),
                    'absolute' => esc_html__('Absolute', 'courto-core'),
                ],
                'default' => 'absolute',
                'prefix_class' => 'bg_text_position%s-',
            ]
        );

        $this->add_responsive_control(
            'bg_text_alignment_h',
            [
                'label' => esc_html__('Horizontal Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => true,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [ 'bg_text_position' => 'absolute' ],
                'default' => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_text_alignment_v',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => true,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [ 'bg_text_position' => 'absolute' ],
                'default' => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_rotate',
            [
                'label' => esc_html__('Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                ],
                'default' => ['size' => 0, 'unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'condition' => [ 'bg_text_position' => 'absolute' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text span' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_wrapper_size',
            [
                'label' => esc_html__('Wrapper Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                    'em' => ['max' => 10, 'step' => 0.05],
                ],
                'condition' => [ 'bg_text_position' => 'absolute' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '25',
                    'right' => '37',
                    'bottom' => '0',
                    'left' => '43',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '18',
                    'right' => '25',
                    'bottom' => '0',
                    'left' => '32',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '10',
                    'right' => '20',
                    'bottom' => '0',
                    'left' => '25',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('bg_text_colors');
        $this->start_controls_tab(
            'bg_text_colors_normal',
            ['label' => esc_html__('Normal', 'courto-core')]
        );
        $this->add_control(
            'bg_text_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_opacity_idle',
            [
                'label' => esc_html__('Opacity From', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_h_pos_idle',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped_bg_text' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'bg_text_colors_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'bg_text_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_bg_text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_opacity_hover',
            [
                'label' => esc_html__('Opacity To', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_bg_text' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_h_pos_hover',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped.active .wgl-striped_bg_text' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> BUTTON
         */

        $this->start_controls_section(
            'button_style_section',
            [
                'label' => esc_html__('Button', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['add_read_more!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_custom_fonts',
                'selector' => '{{WRAPPER}} .wgl-widget__button .button__text',
            ]
        );
        $this->add_responsive_control(
            'button_decoration_line_size',
            [
                'label' => esc_html__('Decoration Line Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 20, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .button__text' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_underline_offset',
            [
                'label' => esc_html__('Underline Offset Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => 'underline' ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => -20, 'max' => 20, 'step' => 1],
                    'em' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .button__text' => 'text-underline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '20',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_wrapper_padding',
            [
                'label' => esc_html__('Wrapper Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
		    'button_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%', 'custom'],
			    'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_fontawesome[value]!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'render_type' => 'template',
			    'dynamic' => ['active' => true],
                'fields_options' => [
                    'border' => [ 'default' => '' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 0,
                            'right' => 0,
                            'bottom' => 1,
                            'left' => 0,
                        ],
                    ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ]
                ],
                'selector' => '{{WRAPPER}} .wgl-widget__button',
            ]
        );


        $this->add_responsive_control(
            'read_more_button_width',
            [
                'label' => esc_html__( 'Button Min-Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_type' => 'wgl-button' ],
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'range' => [
                    'px' => ['max' => 500],
                    '%' => ['max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_position',
            [
                'label' => esc_html__('Button Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'relative' => esc_html__('Default', 'courto-core'),
                    'absolute' => esc_html__('Absolute', 'courto-core'),
                ],
                'default' => 'relative',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'position: {{VALUE}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'button_position_offset_x',
			[
				'label' => esc_html__( 'Offset X', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vw', 'vh', 'custom' ],
                'condition' => [
                    'button_position!' => 'relative',
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-button-wrapper' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'button_position_offset_y',
			[
				'label' => esc_html__( 'Offset Y', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vh', 'vw', 'custom' ],
                'condition' => [
                    'button_position!' => 'relative',
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-button-wrapper' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button' );
        $this->start_controls_tab(
            'tab_button_idle',
            ['label' => esc_html__('Idle' , 'courto-core') ]
        );
        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => WGL_Globals::get_tertiary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_idle',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .button__text' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
	            'condition' => [
                    'button_border_border!' => ['', 'none']
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_idle',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => '{{WRAPPER}} .wgl-widget__button',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_idle',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
        'tab_button_item_hover',
            ['label' => esc_html__('Item Hover', 'courto-core')]
        );
        $this->add_control(
            'button_color_item_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_item_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_item_hover',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button .button__text' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_item_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_item_hover',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_item_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_hover',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover span' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_hover',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_responsive',
            ['label' => esc_html__('Responsive', 'courto-core')]
        );
        $this->add_control(
            'button_color_responsive',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_responsive',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_responsive',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active span' => 'text-decoration-color: {{VALUE}};',
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button span,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button span,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button span,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button span,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button span,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button span' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_responsive',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_responsive',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => 'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_responsive',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> BUTTON ICON
         */

        $this->start_controls_section(
            'style_button_icon',
            [
                'label' => esc_html__('Button Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_type' => 'font'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'button_animation_style!' => 'icon_size_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-widget__button .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-widget__button .wgl-icon::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wgl-widget__button .wgl-icon',
            ]
        );
        $this->start_controls_tabs( 'tabs_icon_button' );
        $this->start_controls_tab(
            'tab_icon_button_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'button_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wgl-widget__button' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_idle',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .wgl-widget__button .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_idle',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_idle',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_button_item_hover',
            ['label' => esc_html__('Item Hover', 'courto-core')]
        );

        $this->add_control(
            'button_icon_color_item_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_item_hover',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_fontawesome[value]!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_item_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_item_hover',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_item_hover',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_button_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'button_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_hover',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .wgl-striped .wgl-widget__button:hover .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .wgl-striped .wgl-widget__button:hover .wgl-icon::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_hover',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_hover',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:hover .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_button_responsive',
            ['label' => esc_html__('Responsive', 'courto-core')]
        );

        $this->add_control(
            'button_icon_color_responsive',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_responsive',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_fontawesome[value]!' => '' ],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button .wgl-icon,

                     body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_responsive',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button .wgl-icon,

                     body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button .wgl-icon::before,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button .wgl-icon::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_responsive',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button .wgl-icon' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_responsive',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-widescreen .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-desktop .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-tablet .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile_extra .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .wgl-striped-services.breakpoint_on-mobile .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /** STYLE -> BUTTON ANIMATION */

        $this->start_controls_section(
            'style_button_animation',
            [
                'label' => esc_html__( 'Button Animation', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_type' => 'font'
                ],
            ]
        );

        $this->add_control(
            'button_animation_style',
            [
                'label' => esc_html__('Animation Style', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'moving_icon' => esc_html__('Moving Icon', 'courto-core'),
                    'letter_animation' => esc_html__('Letter Animation', 'courto-core'),
                    'background_gradient' => esc_html__('Background Gradient', 'courto-core'),
                    'border_gradient' => esc_html__('Border Gradient', 'courto-core'),
                    'separated' => esc_html__('Separated Button', 'courto-core'),
                    'highlight_animation' => esc_html__('Highlight Animation', 'courto-core'),
                    'bg_animation' => esc_html__('Background Animation ', 'courto-core'),
                    'border_animation' => esc_html__('Border Animation', 'courto-core'),
                    'magnetic' => esc_html__('Magnetic', 'courto-core'),
                    'icon_size_animation' => esc_html__('Icon Size Animation', 'courto-core'),
                    'icon_visibility' => esc_html__('Icon Visibility', 'courto-core'),
                ],
                'prefix_class' => 'has-',
            ]
        );

        /** Moving Icon Animation */
        $this->add_responsive_control(
            'moving_icon_wrapper_size',
            [
                'label' => esc_html__('Icon BG Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'moving_icon', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        /** Background Gradient Animation */
        $this->add_responsive_control(
            'background_gradient_location_1',
            [
                'label' => esc_html__('Primary Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--bg-gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_location_2',
            [
                'label' => esc_html__('Secondary Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--bg-gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'background_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 90 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'linear',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'courto-core' ),
                    'center left' => esc_html__( 'Center Left', 'courto-core' ),
                    'center right' => esc_html__( 'Center Right', 'courto-core' ),
                    'top center' => esc_html__( 'Top Center', 'courto-core' ),
                    'top left' => esc_html__( 'Top Left', 'courto-core' ),
                    'top right' => esc_html__( 'Top Right', 'courto-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                    'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                ],
                'default' => 'center center',
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                    'background_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                    'background_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'gradient_background_tabs', [
            'condition' => [ 'button_animation_style' => 'background_gradient' ]
        ]);
        $this->start_controls_tab(
            'gradient_background_tab_idle', [
            'label' => esc_html__( 'Idle', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_idle',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#5A76F7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_idle',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#A96FF3',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_item_hover', [
            'label' => esc_html__( 'Hover', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_item_hover',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_item_hover',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_hover', [
            'label' => esc_html__( 'Hover', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_hover',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#A96FF3',
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_hover',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#5A76F7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_active', [
            'label' => esc_html__( 'Active', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_active',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_active',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Border Gradient Animation */
        $this->add_responsive_control(
            'gradient_border_width',
            [
                'label' => esc_html__( 'Border Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'border_gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-mask-border: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'gradient_border_tabs',
            [ 'condition' => [ 'button_animation_style' => 'border_gradient' ] ]
        );
        $this->start_controls_tab(
            'gradient_border_tab_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_idle',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_idle',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_border_tab_item_hover',
            [ 'label' => esc_html__( 'Item Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_item_hover',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_item_hover',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_border_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_hover',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_hover',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Separated Button Animation */
        $this->add_responsive_control(
            'icon_wrapper_size',
            [
                'label' => esc_html__( 'Icon Wrapper Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'separated' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-icon-wrapper: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /** Highlight Animation */
        $this->add_control(
            'stroke_highlight_color_normal',
            [
                'label' => esc_html__( 'Stroke Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'highlight_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .highlight_svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'stroke_highlight_width_normal',
            [
                'label' => esc_html__( 'Stroke Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'highlight_animation' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 10 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .highlight_svg path' => 'stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /** Background Animation */

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_bg_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'selector' => '{{WRAPPER}} .wgl-widget__button::after',
            ]
        );
        $this->add_control(
            'button_bg_z_index',
            [
                'label' => esc_html__( 'Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $this->start_controls_tabs( 'button_animation', [
            'condition' => [ 'button_animation_style' => 'bg_animation' ],
        ] );
        $this->start_controls_tab(
            'button_animation_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_idle',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_idle',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_size_w',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'button_bg_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_item_hover',
            [ 'label' => esc_html__( 'Item Hover', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_item_hover',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_item_hover',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_w_item_hover',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_size_h_item_hover',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_border_radius_item_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_item_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_item_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_hover',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_hover',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_w_hover',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h_hover',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus)::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_active',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_y_pos_active',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_w_active',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h_active',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active::after' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'button_bg_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'button_bg_bg_active',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Border Animation */
        $this->add_control(
            'button_border_animation_revers',
            [
                'label' => esc_html__( 'Revers This Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'revers',
                'prefix_class' => '',
                'condition' => [ 'button_animation_style' => 'border_animation' ],
            ]
        );

        $this->add_control(
            'button_border_animation_color',
            [
                'label' => esc_html__( 'Animation Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button' => '--ab-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_offset',
            [
                'label' => esc_html__('Animation Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 20, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 6],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button' => '--ab-offset: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_width',
            [
                'label' => esc_html__('Animation Border Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 10, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 1],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button' => '--ab-width: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_extend',
            [
                'label' => esc_html__('Mow Much to Extend the Border', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 50, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 4],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button' => '--ab-extend: {{SIZE}};',
                ],
            ]
        );

        /** Magnetic Threshold */

        $this->add_control(
            'button_magnetic_threshold',
            [
                'label' => esc_html__('Magnetic Threshold', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'style_transfer' => true,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 1920, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 500],
            ]
        );

        $this->add_control(
            'button_magnetic_strong',
            [
                'label' => esc_html__('Magnetic Strong', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'style_transfer' => true,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0.05, 'max' => 1, 'step' => 0.05],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 0.2],
            ]
        );

        /** Icon Size Animation */
        $this->add_control(
            'button_icon_bg_animation',
            [
                'label' => esc_html__( 'Background Size(px)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'icon_size_animation' ],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ] ],
                'default' => ['size' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-size: {{SIZE}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'button_icon_size_animation_tabs', [
            'condition' => [ 'button_animation_style' => 'icon_size_animation' ],
        ] );
        $this->start_controls_tab(
            'button_icon_size_animation_tab_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_idle',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_idle',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_item_hover',
            [ 'label' => esc_html__( 'Item Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_item_hover',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_item_hover',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped:hover .wgl-widget__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_hover',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_hover',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_active',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_active',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-striped .wgl-widget__button:active .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Icon Visibility */
        $this->add_control(
            'icon_visibility',
            [
                'label' => esc_html__( 'Icon Visibility', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'button_animation_style' => 'icon_visibility' ],
                'options' => [
                    'default' => esc_html__( 'Default', 'courto-core' ),
                    'revert' => esc_html__( 'Revert', 'courto-core' ),
                ],
                'default' => 'default',
                'prefix_class' => 'icon-visibility-'
            ]
        );

        $this->end_controls_section();
    }

    protected function add_mobile_breakpoint() {
        // The 'Hide On X' controls are displayed from largest to smallest, while the method returns smallest to largest.
        $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
        $active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

        $avaliable_breakpoints = [];
        foreach ( $active_devices as $breakpoint_key ) {
            $label = 'desktop' === $breakpoint_key ? esc_html__( 'Desktop', 'courto-core' ) : $active_breakpoints[ $breakpoint_key ]->get_label();
            $avaliable_breakpoints[$breakpoint_key] = $label;
        }
        $this->add_control(
            'wgl_mobile_breakpoint',
            [
                /* translators: %s: Device Name. */
                'label' => esc_html__( 'Set Mobile Template On', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => $avaliable_breakpoints,
                'default' => 'mobile',
            ]
        );
    }

    protected function render()
    {
        $kses_allowed_html = [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'i' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
            'small' => ['id' => true, 'class' => true, 'style' => true],
            'ul' => ['id' => true, 'class' => true, 'style' => true],
            'ol' => ['id' => true, 'class' => true, 'style' => true],
            'li' => ['id' => true, 'class' => true, 'style' => true],
        ];

        $_s = $this->get_settings_for_display();

        $this->add_render_attribute('striped-services', 'class', [
            'wgl-striped-services'
        ]);

        // The 'Hide On X' controls are displayed from largest to smallest, while the method returns smallest to largest.
        $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
        $key = array_search($_s['wgl_mobile_breakpoint'], $active_devices);
        $all_breakpoints = array_slice($active_devices, $key);
        foreach($all_breakpoints as $breakpoint){
            $this->add_render_attribute( 'striped-services', [ 'class' => [ 'breakpoint_on-' . $breakpoint ] ] );
        }

        // Read more button
        if ($_s['add_read_more']) {
            $this->add_render_attribute('btn', 'class',
                [
                    'wgl-striped_button',
                    'wgl-widget__button',
                    $_s['button_type'] ?? '',
                    !$_s['read_more_text'] ? 'no_text' : '',
                    ! empty($_s['read_more_icon_align']) ? 'align-icon-' . ($_s['read_more_icon_align']) : '',
                ]
            );

            $btn_icon = '';
            // ↓ Icon
            if ('font' === $_s['read_more_icon_type'] && isset($_s['read_more_icon_fontawesome']['value'])) {
                $migrated = isset( $_s['__fa4_migrated']['read_more_icon_fontawesome'] );
                $is_new = Icons_Manager::is_migration_allowed();
                if ( $is_new || $migrated ) {
                    ob_start();
                    Icons_Manager::render_icon($_s['read_more_icon_fontawesome'], ['class' => 'read-more-icon', 'aria-hidden' => 'true']);
                    $btn_icon = ob_get_clean();
                }
                if ('svg' === $_s['read_more_icon_fontawesome']['library']) {
                    $wrapper_icon = '<span class="read-more-icon read-more-svg">';
                    $wrapper_icon .= $btn_icon;
                    $wrapper_icon .= '</span>';
                    $btn_icon = $wrapper_icon;
                }
                if ( 'moving_icon' === $_s['button_animation_style']) {
                    $btn_icon .= $btn_icon;
                }

                $btn_icon = !!$btn_icon ? '<span class="wgl-icon"> ' . $btn_icon . '</span>' : '';
                $btn_icon = 'wgl-button' === $_s['button_type'] ? '<div class="icon-wrapper">' . $btn_icon . '</div>' : $btn_icon;
            }elseif('button-read-more' === $_s['button_type']){
                $btn_icon = '<span class="read-more-icon"></span>';
            }else{
                $this->add_render_attribute(['btn' => ['class' => [ 'no_media']]]);
            }
            // ↑ icon
        }

        // Read more button
        if (!empty($_s['read_more_text']) && ( 'letter_animation' === $_s[ 'button_animation_style' ] )) {
            $letters = '';
            $len = mb_strlen($_s['read_more_text'], 'UTF-8');
            for ($i = 0; $i < $len; $i++) {
                $value = mb_substr(esc_html($_s['read_more_text']), $i, 1, 'UTF-8');
                $letters .= $value == ' ' ? ' ' : '<span class="letter">' . $value . '</span>';
            }
            $_s['read_more_text'] = $letters;
        }else{
            $_s['read_more_text'] = esc_html($_s['read_more_text']);
        }

        echo '<div ', $this->get_render_attribute_string('striped-services'), '>';

        foreach ($_s['items'] as $index => $item) {

            $striped_icon = '';
            $has_link = !empty($item['serv_link']['url']);
            if ($has_link) {
                $serv_link = $this->get_repeater_setting_key('serv_link', 'items', $index);
                $this->add_link_attributes($serv_link, $item['serv_link']);

                $striped_button = '<div class="wgl-button-wrapper">';
                    $striped_button .= sprintf(
                        '<%s %s %s>',
                        $_s['module_link'] ? 'div' : 'a',
                        $_s['module_link'] ? '' : $this->get_render_attribute_string($serv_link),
                        $this->get_render_attribute_string('btn')
                    );

                        $striped_button .= 'wgl-button' === $_s['button_type'] ? '<div class="button__content">' : '';
                            $striped_button .= $btn_icon ?: '';
                            $striped_button .= $_s['read_more_text'] ? '<span class="button__text">' . $_s['read_more_text'] . '</span>' : '';
                        $striped_button .= 'wgl-button' === $_s['button_type'] ? '</div>' : '';
                    $striped_button .= $_s['module_link'] ? '</div>' : '</a>';
                $striped_button .= '</div>';

                if ($_s['module_link']) {
                    $module_link_html = '<a class="wgl-striped__link" ' . $this->get_render_attribute_string($serv_link) . '></a>';
                }
            }

            // Icon/image
            if ( $item[ 'striped_icon_type' ] !== '' ) {
                if ( $item[ 'striped_icon_type' ] === 'font' && ( !empty( $item[ 'striped_icon_fontawesome' ] ) ) ) {
                    $icon_font = $item[ 'striped_icon_fontawesome' ];
                    $icon_out = '';
                    $migrated = isset( $item['__fa4_migrated'][$item[ 'striped_icon_fontawesome' ]] );
                    $is_new = Icons_Manager::is_migration_allowed();
                    if ( $is_new || $migrated ) {
                        ob_start();
                        Icons_Manager::render_icon( $item[ 'striped_icon_fontawesome' ], [ 'aria-hidden' => 'true' ] );
                        $icon_out .= ob_get_clean();
                    } else {
                        $icon_out .= '<i class="icon '.esc_attr($icon_font).'"></i>';
                    }

                    $striped_icon = '<span class="wgl-striped_icon wgl-icon">' . $icon_out .'</span>';

                }else if ( $item['striped_icon_type'] === 'image' && !empty($item['striped_icon_thumbnail']) && !empty($item['striped_icon_thumbnail'][ 'url' ] ) ) {
                    $this->add_render_attribute( 'thumbnail', 'src', $item[ 'striped_icon_thumbnail' ][ 'url' ] );
                    $this->add_render_attribute( 'thumbnail', 'alt', Control_Media::get_image_alt( $item[ 'striped_icon_thumbnail' ] ) );
                    $this->add_render_attribute( 'thumbnail', 'title', Control_Media::get_image_title( $item[ 'striped_icon_thumbnail' ] ) );

                    $striped_icon = '<span class="wgl-striped_icon wgl-image">' . Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'striped_icon_thumbnail' ) .'</span>';

                }
            }
            // End Tab Icon/image

            // Background Video
            $this->add_render_attribute('striped-service-item-' . $item['_id'], 'class', [
                'wgl-striped',
                'elementor-repeater-item-'. $item['_id'],
                !empty($item['serv_def_active']) ? ' active def-active' : '',
                $item['add_video_background'] === 'yes' ? 'wgl-video-hero stripped-video plays-hover' : '',
            ]);

            $background_video_html = '';
            if('yes' === $item['add_video_background']){

                $source_type = 'self';
                if ($item['video_source'] === 'vimeo') {
                    $source_type = 'vimeo';
                }

                $video_url = '';
                if ($item['video_source'] === 'self_hosted') {
                    $video_url = $item['self_hosted_video']['url'] ?? '';
                } else {
                    $video_url = Embed::get_embed_url($item['background_video_link']);
                }
                $data_attrs = [
                    'data-source-type' => $source_type,
                    'data-autoplay' => 0,
                    'data-mute' => 1,
                    'data-loop' => 1,
                    'data-link' => esc_url($video_url),
                ];

                $video_id = '';
                if ($item['video_source'] === 'youtube') {
                    preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $item['background_video_link'], $matches);
                    $video_id = $matches[1] ?? '';
                } elseif ($item['video_source'] === 'vimeo') {
                    preg_match('/vimeo\.com\/([0-9]+)/', $item['background_video_link'], $matches);
                    $video_id = $matches[1] ?? '';
                }

                if (!empty($video_id)) {
                    $data_attrs['data-video-id'] = $video_id;
                }

                foreach ($data_attrs as $k => $v) {
                    $this->add_render_attribute('striped-service-item-' . $item['_id'], $k, esc_attr($v));
                }

                $widget_id = $this->get_id() . '-' . $item['_id'];
                if ($item['video_source'] === 'self_hosted' && !empty($item['self_hosted_video']['url'])) {
                    $background_video_html = sprintf(
                        '<video id="video-%1$s" class="wgl-video elementor-background-video-hosted" playsinline preload="auto">' .
                        '<source src="%2$s" type="video/mp4">' .
                        '</video>',
                        esc_attr($widget_id),
                        esc_url($item['self_hosted_video']['url'])
                    );
                } elseif ($item['video_source'] === 'youtube' || $item['video_source'] === 'vimeo') {
                    $background_video_html = sprintf(
                        '<div id="video-%1$s" class="wgl-video-iframe iframe elementor-background-video-embed" aria-hidden="true"></div>',
                        esc_attr($widget_id)
                    );
                }                
            }

            echo '<div ' , $this->get_render_attribute_string('striped-service-item-' . $item['_id']) , '>';
                echo '<div class="service-image"><div class="image"></div></div>';
                    echo $has_link && isset($module_link_html) ? $module_link_html : '';

                    echo '<div class="wgl-striped_wrapper">';

                        if (!empty($item['serv_bg_text'])) {
                            echo '<div class="wgl-striped_bg_text"><span>', esc_html($item['serv_bg_text']), '</span></div>';
                        }

                        if (!empty($item['serv_title'])) {
                            echo '<' . $_s['title_tag'] . ' class="wgl-striped_title">' .
                                esc_html($item['serv_title']) .
                            '</' . $_s['title_tag'] . '>';
                        }

                        if (!empty($striped_icon)) {
                            echo $striped_icon;
                        }

                        if (!empty($item['serv_subtitle'])) {
                            echo '<'. $_s['subtitle_tag']. ' class="wgl-striped_subtitle">'.
                                esc_html($item['serv_subtitle']).
                            '</' . $_s['subtitle_tag'] . '>';
                        }

                        if (!empty($item['serv_content'])) {
                            echo '<div class="wgl-striped_content">'.
                                wp_kses($item['serv_content'], $kses_allowed_html).
                            '</div>';
                        }

                        echo $has_link && isset($striped_button) ? $striped_button : '';

                    echo '</div>'; // wgl-striped_content
                    if ($background_video_html){
                        echo '<div class="wgl-video-hero__video elementor-background-video-container">';
                        echo $background_video_html;
                        echo '</div>';
                    }
                echo '</div>'; // wgl-striped
        }

        echo '</div>';
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
