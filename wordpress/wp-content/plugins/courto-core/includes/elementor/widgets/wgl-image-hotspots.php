<?php
/**
 * Current file can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-countdown.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Repeater,
    Icons_Manager,
    Utils,
    Control_Media,
    Frontend,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Image_Size,
    Group_Control_Css_Filter,
    Group_Control_Background,
    Group_Control_Typography
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Icons,
    Includes\WGL_Elementor_Helper
};

/**
 * Image Hotspots Widget
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Wgl_Image_Hotspots extends Widget_Base
{
    public $item_styles;

    public function get_name()
    {
        return 'wgl-image-hotspots';
    }

    public function get_title()
    {
        return esc_html__( 'WGL Image HotSpots', 'courto-core' );
    }

    public function get_icon()
    {
        return 'wgl-image-hotspots';
    }

    public function get_keywords() {
        return ['hotspots', 'image'];
    }

    public function get_categories()
    {
        return [ 'wgl-modules' ];
    }

    public function get_script_depends()
    {
        return [ 'jquery-appear' ];
    }

    protected function register_controls()
    {
        /** CONTENT -> IMAGE */

        $this->start_controls_section(
            'content_image',
            [ 'label' => esc_html__( 'Image', 'courto-core' ) ]
        );

        $this->add_control(
            'thumbnail',
            [
                'label' => esc_html__( 'Image', 'courto-core' ),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_size',
                'label' => esc_html__( 'Image Size', 'courto-core' ),
                'default' => 'full',
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label' => esc_html__( 'Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'courto-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'courto-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /** CONTENT -> HOTSPOTS */

        $this->start_controls_section(
            'content_hotspots',
            [ 'label' => esc_html__( 'HotSpots', 'courto-core' ) ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs( 'hotspot_tabs' );

        $repeater->start_controls_tab(
            'hotspot_tab_content',
            [ 'label' => esc_html__( 'Content', 'courto-core' ) ]
        );

        $repeater->add_control(
            'hotspot_type',
            [
                'label' => esc_html__( 'Type', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'courto-core' ),
                    'icon' => esc_html__( 'Icon', 'courto-core' ),
                    'image' => esc_html__( 'Image', 'courto-core' ),
                ],
                'default' => 'icon',
            ]
        );

        $repeater->add_control(
            'hotspot_icon',
            [
                'label' => esc_html__( 'Icon', 'courto-core' ),
                'type' => Controls_Manager::ICONS,
                'condition' => [ 'hotspot_type' => 'icon' ],
                'description' => esc_html__( 'Select icon from available libraries.', 'courto-core' ),
                'label_block' => true,
                'default' => [
                    'library' => 'fa-solid',
                    'value' => 'fas fa-arrow-right',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_image',
            [
                'label' => esc_html__( 'Image', 'courto-core' ),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_type' => 'image' ],
                'label_block' => true,
                'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            ]
        );

        $repeater->add_control(
            'hotspot_text',
            [
                'label' => esc_html__( 'Text', 'courto-core' ),
                'condition' => [ 'hotspot_type!' => 'default' ],
                'type' => Controls_Manager::TEXT,

			    'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_media_position',
            [
                'label' => esc_html__( 'Media Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'hotspot_text!' => '',
                    'hotspot_type!' => 'default',
                ],
                'options' => [
                    'left' => esc_html__( 'Left', 'courto-core' ),
                    'top' => esc_html__( 'Top', 'courto-core' ),
                    'right' => esc_html__( 'Right', 'courto-core' ),
                    'bottom' => esc_html__( 'Bottom', 'courto-core' ),
                ],
                'default' => 'left',
            ]
        );

        $repeater->add_control(
            'hotspot_link',
            [
                'label' => esc_html__( 'Link', 'courto-core' ),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => false,
                'placeholder' => esc_attr__( 'https://your-link.com', 'courto-core' ),
                'default' => [ 'is_external' => 'true' ],
            ]
        );

        $repeater->add_control(
            'hotspot_content_type',
            [
                'label' => esc_html__( 'Content Type', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => esc_html__( 'Content', 'courto-core' ),
                    'template' => esc_html__( 'Saved Templates', 'courto-core' ),
                ],
                'default' => 'content',
            ]
        );
        $repeater->add_control(
            'hotspot_content_templates',
            [
                'label' => esc_html__( 'Choose Template', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'hotspot_content_type' => 'template' ],
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
            ]
        );

        $repeater->add_control(
            'hotspot_wysiwyg',
            [
                'label' => esc_html__( 'Content', 'courto-core' ),
                'type' => Controls_Manager::WYSIWYG,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_content_type' => 'content' ],
                'default' => '<img loading="lazy" class="aligncenter" src="'.Utils::get_placeholder_image_src().'" alt="placeholder_image" width="300" height="300" >',
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'hotspot_position',
            [ 'label' => esc_html__( 'Position', 'courto-core' ) ]
        );

        $repeater->add_responsive_control(
            'hotspot_position_horizontal',
            [
                'label' => esc_html__( 'Horizontal position (%)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 50 ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'hotspot_position_vertical',
            [
                'label' => esc_html__( 'Vertical position (%)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 50 ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'tooltips_cur_position',
            [
                'label' => esc_html__( 'ToolTip Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'top_l' => esc_html__( 'Top-Left', 'courto-core' ),
                    'top' => esc_html__( 'Top', 'courto-core' ),
                    'top_r' => esc_html__( 'Top-Right', 'courto-core' ),
                    'right' => esc_html__( 'Right', 'courto-core' ),
                    'bottom_r' => esc_html__( 'Bottom-Right', 'courto-core' ),
                    'bottom' => esc_html__( 'Bottom', 'courto-core' ),
                    'bottom_l' => esc_html__( 'Bottom-Left', 'courto-core' ),
                    'left' => esc_html__( 'Left', 'courto-core' ),
                ],
                'default' => '',
            ]
        );

        $repeater->add_responsive_control(
            'tooltips_cur_margin',
            [
                'label' => esc_html__( 'ToolTip Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'tooltips_cur_position!' => '' ],
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .tooltip__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'hotspot_cur_styles',
            [ 'label' => esc_html__( 'Style', 'courto-core' ) ]
        );

        $repeater->add_responsive_control(
            'hotspot_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_point-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_cur_style',
            [
                'label' => esc_html__( 'Colors', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'courto-core' ),
                    'custom' => esc_html__( 'Custom', 'courto-core' ),
                ],
                'default' => 'default',
            ]
        );

        $repeater->add_control(
            'icon_c_color_idle',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_cur_style' => 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_media-wrap' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'text_c_color_idle',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_cur_style' => 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_point-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'text_c_background_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_cur_style' => 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'current_style_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => [ 'hotspot_cur_style' => 'custom' ],
            ]
        );

        $repeater->add_control(
            'icon_c_color_hover',
            [
                'label' => esc_html__( 'Hover Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_cur_style' => 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.hotspots__item:hover .hotspots_media-wrap' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'text_c_color_hover',
            [
                'label' => esc_html__( 'Hover Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_cur_style' => 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.hotspots__item:hover .hotspots_point-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'text_c_background_hover',
            [
                'label' => esc_html__( 'Hover Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspot_cur_style' => 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.hotspots__item:hover .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'repeater_data',
            [
                'label' => esc_html__( 'Hot Spots', 'courto-core' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'item' => 1 ],
                ],
                'title_field' => '<# var titleField;
                    if (hotspot_text) { titleField = hotspot_text }
                    else if (hotspot_wysiwyg) { titleField = hotspot_wysiwyg.replace(/<[^>]*>?/gm, "").trimLeft().substring(0, 35); }
                    #>{{{ titleField }}}',
            ]
        );

        $this->end_controls_section();

        /** CONTENT -> TOOLTIPS */

        $this->start_controls_section(
            'content_tooltips',
            [ 'label' => esc_html__( 'ToolTips', 'courto-core' ) ]
        );

        $this->add_responsive_control(
            'show_always',
            [
                'label' => esc_html__( 'Show Always', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'tooltips_position',
            [
                'label' => esc_html__( 'Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top_l' => esc_html__( 'Top-Left', 'courto-core' ),
                    'top' => esc_html__( 'Top', 'courto-core' ),
                    'top_r' => esc_html__( 'Top-Right', 'courto-core' ),
                    'right' => esc_html__( 'Right', 'courto-core' ),
                    'bottom_r' => esc_html__( 'Bottom-Right', 'courto-core' ),
                    'bottom' => esc_html__( 'Bottom', 'courto-core' ),
                    'bottom_l' => esc_html__( 'Bottom-Left', 'courto-core' ),
                    'left' => esc_html__( 'Left', 'courto-core' ),
                ],
                'default' => 'top_r',
            ]
        );

        $this->add_responsive_control(
            'tooltips_animation',
            [
                'label' => esc_html__( 'Animation', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'show_always' => '' ],
                'options' => [
                    'fade' => esc_html__( 'Fade', 'courto-core' ),
                    'zoom' => esc_html__( 'Zoom', 'courto-core' ),
                    'to_left' => esc_html__( 'To Left', 'courto-core' ),
                    'to_top' => esc_html__( 'To Top', 'courto-core' ),
                    'to_right' => esc_html__( 'To Right', 'courto-core' ),
                    'to_bottom' => esc_html__( 'To Bottom', 'courto-core' ),
                    'shake' => esc_html__( 'Shake', 'courto-core' ),
                ],
                'default' => 'fade',
            ]
        );

        $this->add_responsive_control(
            'tooltips_align',
            [
                'label' => esc_html__( 'Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'courto-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'courto-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .hotspot__tooltip' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'show_triangle',
            [
                'label' => esc_html__( 'Show Triangle', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [ 'tooltips_position!' => ['bottom_l', 'bottom_r', 'top_r', 'top_l'] ],
                'separator' => 'before',
			    'default' => 'yes',
                'selectors' => [
				    '{{WRAPPER}} .tooltip__wrapper::after' => 'content: "";',
			    ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_width',
            [
                'label' => esc_html__( 'Container Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'default' => [ 'size' => 170 ],
                'selectors' => [
                    '{{WRAPPER}} .hotspot__tooltip' => 'width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'tooltip_icon_enabled',
            [
                'label' => esc_html__( 'Append Icon?', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltip_icon',
            [
                'label' => esc_html__( 'Icon', 'courto-core' ),
                'type' => Controls_Manager::ICONS,
                'condition' => [ 'tooltip_icon_enabled!' => '' ],
                'label_block' => true,
                'description' => esc_html__( 'Select icon from available libraries.', 'courto-core' ),
            ]
        );

        $this->end_controls_section();

        /** CONTENT -> ADDITIONAL */

        $this->start_controls_section(
            'content_additional',
            [ 'label' => esc_html__( 'Animation', 'courto-core' ) ]
        );

        $this->add_control(
            'appear_animation',
            [
                'label' => esc_html__( 'Appear Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'interval',
            [
                'label' => esc_html__( 'HotSpots Appearing Interval (ms)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'appear_animation!' => '' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 2500, 'step' => 10 ],
                ],
                'default' => [ 'size' => 700, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'transition',
            [
                'label' => esc_html__( 'Transition Duration (ms)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'appear_animation!' => '' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 2500, 'step' => 10 ],
                ],
                'default' => [ 'size' => 700, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'loop_animation',
            [
                'label' => esc_html__( 'HotSpots Loop Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'loop_interval',
            [
                'label' => esc_html__( 'HotSpots Loop Interval (ms)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'loop_animation!' => '' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 2500, 'step' => 10 ],
                ],
                'default' => [ 'size' => 800, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'loop_duration',
            [
                'label' => esc_html__( 'HotSpots Loop Duration (seconds)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'loop_animation!' => '' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 200, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 5, 'unit' => 'px' ],
            ]
        );

        $this->add_responsive_control(
            'loop_animation_option',
            [
                'label' => esc_html__( 'Animation', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'loop_animation!' => '' ],
                'options' => [
                    'pulse' => esc_html__( 'Pulse', 'courto-core' ),
                    'shake' => esc_html__( 'Shake', 'courto-core' ),
                    'flash' => esc_html__( 'Flash', 'courto-core' ),
                    'zoom' => esc_html__( 'Zoom', 'courto-core' ),
                    'rubber' => esc_html__( 'Rubber', 'courto-core' ),
                    'swing' => esc_html__( 'Swing', 'courto-core' ),
                ],
                'default' => 'pulse',
            ]
        );

        $this->end_controls_section();

        /** STYLE -> CONTAINER */

        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__( 'Container', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-hotspots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-hotspots' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-hotspots' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /** STYLE -> IMAGE */

        $this->start_controls_section(
            'style_image',
            [
                'label' => esc_html__( 'Image', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hotspots_image_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .hotspots_image',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .hotspots_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filters',
                'selector' => '{{WRAPPER}} .hotspots_image',
            ]
        );

        $this->end_controls_section();

        /** STYLE -> HOTSPOTS */

        $this->start_controls_section(
            'section_style_hotspots',
            [
                'label' => esc_html__( 'HotSpots', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'hotspots_typography',
                'selector' => '{{WRAPPER}} .hotspots_point-text',
            ]
        );

        $this->add_responsive_control(
            'hotspots_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_tag',
            [
                'label' => esc_html__( 'Text Tag', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html( '‹h1›' ),
                    'h2' => esc_html( '‹h2›' ),
                    'h3' => esc_html( '‹h3›' ),
                    'h4' => esc_html( '‹h4›' ),
                    'h5' => esc_html( '‹h5›' ),
                    'h6' => esc_html( '‹h6›' ),
                    'span' => esc_html( '‹span›' ),
                    'div' => esc_html( '‹div›' ),
                ],
                'default' => 'h4',
            ]
        );

        $this->add_responsive_control(
            'hotspots_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 14, 'max' => 200 ],
                ],
                'default' => [ 'size' => 25 ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hotspots_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .hotspots_point-wrap',
            ]
        );

        $this->start_controls_tabs( 'hotspots' );

        $this->start_controls_tab(
            'hotspots_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );

        $this->add_control(
            'hotspots_radius_idle',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspots_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-wrap' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__( 'Dot | Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-icon.default' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .hotspots_media-wrap' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'text_color_idle',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'hotspots_bg_idle',
            [
                'label' => esc_html__( 'Hot Spot Background', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hotspots_shadow_idle',
                'selector' => '{{WRAPPER}} .hotspots_point-wrap',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );

        $this->add_control(
            'hotspots_radius_hover',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots__item:hover .hotspots_point-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'hotspots_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .hotspots__item:hover .hotspots_point-wrap' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Dot | Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hotspots__item:hover .hotspots_point-icon.default' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .hotspots__item:hover .hotspots_media-wrap' => 'color: {{VALUE}};fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hotspots__item:hover .hotspots_point-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'hotspots_bg_hover',
            [
                'label' => esc_html__( 'Hot Spot Background', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hotspots__item:hover .hotspots_point-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hotspots_shadow_hover',
                'fields_options' => [
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 0,
                            'blur' => 0,
                            'spread' => 20,
                            'color' => WGL_Globals::get_secondary_color( 0.15 ),
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .hotspots__item:hover .hotspots_point-wrap',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /** STYLE -> TOOLTIPS */

        $this->start_controls_section(
            'style_tooltips',
            [
                'label' => esc_html__( 'Tooltips', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_typography',
                'selector' => '{{WRAPPER}} .tooltip__wrapper',
            ]
        );

        $this->add_responsive_control(
            'tooltips_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '0',
                    'right' => '-8',
                    'bottom' => '-25',
                    'left' => '8',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .tooltip__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltips_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tooltip__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tooltips_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .tooltip__wrapper',
            ]
        );

        $this->add_responsive_control(
            'tooltip_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit'  => '%',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .tooltip__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .tooltip__wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tooltip_icon_color',
            [
                'label' => esc_html__( 'Appended Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .tooltip__icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tooltip_bg',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tooltip__wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltips_shadow',
                'selector' => '{{WRAPPER}} .tooltip__wrapper',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        echo '<div class="wgl-image-hotspots">';

        $this->render_main_thumbnail();

        echo '<div ', $this->get_wrapper_classes(), '>';

        foreach ( $this->get_settings_for_display( 'repeater_data' ) as $index => $item ) {

            echo '<div ', $this->get_item_wrapper_attributes( $index ), '>';

            $has_link = ! empty( $item[ 'hotspot_link' ][ 'url' ] );
            if ( $has_link ) {
                $hotspot_link = $this->get_repeater_setting_key( 'hotspot_link', 'items', $index );
                $this->add_render_attribute( $hotspot_link, 'class', 'hotspots_link' );
                $this->add_link_attributes( $hotspot_link, $item[ 'hotspot_link' ] );

                echo '<a ', $this->get_render_attribute_string( $hotspot_link ), '>';
            }

            echo '<div class="hotspots_point-wrap">';

                $this->render_item_media( $item );

                if ( ! empty( $item[ 'hotspot_text' ] ) ) {
                    echo '<', $this->get_settings_for_display( 'text_tag' ), ' class="hotspots_point-text">',
                        esc_html( $item[ 'hotspot_text' ] ),
                    '</', $this->get_settings_for_display( 'text_tag' ), '>';
                }

            echo '</div>'; // hotspots_point-wrap

            $this->render_item_content( $item );

            if ( $has_link ) {
                echo '</a>';
            }

            echo '</div>'; // hotspot item
        }

        echo '</div>'; // hotspots items
        echo '</div>'; // wgl-image-hotspots
    }

    protected function render_main_thumbnail()
    {
        $thumbnail_url = $this->get_settings_for_display( 'thumbnail' )[ 'url' ] ?? '';

        if ( ! $thumbnail_url ) {
            // Bailout.
            return;
        }

        $this->add_render_attribute( 'thumbnail', [
            'class' => 'hotspots_image',
            'src' => $thumbnail_url,
            'alt' => Control_Media::get_image_alt( $this->get_settings_for_display( 'thumbnail' ) ),
        ] );

        echo '<div class="hotspots_image-wrap">',
            '<img ', $this->get_render_attribute_string( 'thumbnail' ), '>',
        '</div>';
    }

    protected function get_wrapper_classes()
    {
        $this->add_render_attribute( 'wrapper', 'class', 'hotspots__container' );

        if ( $this->get_settings_for_display( 'appear_animation' ) ) {
            $this->add_render_attribute( 'wrapper', 'class', 'appear_animation' );
        }

        if ( $this->get_settings_for_display( 'loop_animation' ) ) {
            $this->add_render_attribute( 'wrapper', 'class', 'loop_animation loop_animation-' . $this->get_settings_for_display( 'loop_animation_option' ) );
        }

        return $this->get_render_attribute_string( 'wrapper' );
    }

    protected function get_item_wrapper_attributes( $index )
    {
        $current_item = $this->get_settings_for_display( 'repeater_data' )[ $index ];
        $item_wrapper_key = $this->get_repeater_setting_key( 'hotspot_title', 'repeater_data', $index );

        $this->add_render_attribute( $item_wrapper_key, 'class', 'hotspots__item elementor-repeater-item-' . $current_item[ '_id' ] );
        empty( $current_item[ 'hotspot_media_position' ] ) || $this->add_render_attribute( $item_wrapper_key, 'class', 'm-desktop-' . $current_item[ 'hotspot_media_position' ] );
        empty( $current_item[ 'hotspot_media_position_tablet' ] ) || $this->add_render_attribute( $item_wrapper_key, 'class', 'm-tablet-' . $current_item[ 'hotspot_media_position_tablet' ] );
        empty( $current_item[ 'hotspot_media_position_mobile' ] ) || $this->add_render_attribute( $item_wrapper_key, 'class', 'm-mobile-' . $current_item[ 'hotspot_media_position_mobile' ] );

        $this->get_settings_for_display( 'tooltips_position' ) && $this->add_render_attribute( $item_wrapper_key, 'class', 'tt-desktop-' . $this->get_settings_for_display( 'tooltips_position' ) );
        $this->get_settings_for_display( 'tooltips_position_tablet' ) && $this->add_render_attribute( $item_wrapper_key, 'class', 'tt-tablet-' . $this->get_settings_for_display( 'tooltips_position_tablet' ) );
        $this->get_settings_for_display( 'tooltips_position_mobile' ) && $this->add_render_attribute( $item_wrapper_key, 'class', 'tt-mobile-' . $this->get_settings_for_display( 'tooltips_position_mobile' ) );

        $this->add_render_attribute( $item_wrapper_key, [
            'class' => [
                ! empty( $current_item[ 'tooltips_cur_position' ] ) ? 'tt-c-desktop-' . $current_item[ 'tooltips_cur_position' ] : '',
                ! empty( $current_item[ 'tooltips_cur_position_tablet' ] ) ? 'tt-c-tablet-' . $current_item[ 'tooltips_cur_position_tablet' ] : '',
                ! empty( $current_item[ 'tooltips_cur_position_mobile' ] ) ? 'tt-c-mobile-' . $current_item[ 'tooltips_cur_position_mobile' ] : ''
            ],
            'style' => $this->get_item_styles()
        ] );

        return $this->get_render_attribute_string( $item_wrapper_key );
    }

    protected function get_item_styles()
    {
        if ( ! empty( $this->item_styles ) ) {
            return $this->item_styles;
        }

        $this->item_styles = '';

        if ( $transition_properties = $this->get_settings_for_display( 'transition' )[ 'size' ] ?? '' ) {
            $this->item_styles .= 'transition:'
                . ' all ' . $transition_properties . 'ms,'
                . ' top ' . $transition_properties . 'ms,'
                . ' left ' . $transition_properties . 'ms;';
        }

        if ( $transition_delay = $this->get_settings_for_display( 'interval' )[ 'size' ] ?? '' ) {
            $this->item_styles .= ' transition-delay: ' . $transition_delay . 'ms, 0s, 0s;';
        }

        if ( $animation_delay = $this->get_settings_for_display( 'loop_interval' )[ 'size' ] ?? '' ) {
            $this->item_styles .= ' animation-delay: ' . $animation_delay . 'ms;';
        }

        if ( $loop_duration = $this->get_settings_for_display( 'loop_duration' )[ 'size' ] ?? '' ) {
            $this->item_styles .= ' animation-duration: ' . $loop_duration . 's;';
        }

        return $this->item_styles;
    }

    protected function render_item_media( $item )
    {
        if (
            'icon' === $item[ 'hotspot_type' ]
            && ! empty( $item[ 'hotspot_icon' ] )
        ) {
            $is_new = Icons_Manager::is_migration_allowed();
            $migrated = isset( $item[ '__fa4_migrated' ][ $item[ 'hotspot_icon' ] ] );
            if ( $is_new || $migrated ) {
                ob_start();
                    Icons_Manager::render_icon( $item[ 'hotspot_icon' ], [ 'aria-hidden' => 'true' ] );
                $hotspot_icon = ob_get_clean();
            } else {
                $hotspot_icon = '<i class="icon ' . esc_attr( $item[ 'hotspot_icon' ] ) . '"></i>';
            }

            $media_out = '<span class="hotspots_point-icon hotspots_point-icon_font">'
                    . $hotspot_icon
                . '</span>';
        }

        if (
            'image' === $item[ 'hotspot_type' ]
            && ! empty( $item[ 'hotspot_image' ][ 'url' ] )
        ) {
            $this->add_render_attribute( 'thumbnail', 'src', $item[ 'hotspot_image' ][ 'url' ] );
            $this->add_render_attribute( 'thumbnail', 'alt', Control_Media::get_image_alt( $item[ 'hotspot_image' ] ) );
            $this->add_render_attribute( 'thumbnail', 'title', Control_Media::get_image_title( $item[ 'hotspot_image' ] ) );

            $media_out = '<span class="hotspots_point-icon hotspots_point-icon_image">'
                    . Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'hotspot_image' )
                . '</span>';
        }

        $media_out = $media_out ?? '<span class="hotspots_point-icon default"></span>';

        echo '<div class="hotspots_media-wrap">',
            $media_out,
        '</div>';
    }

    protected function render_item_content( $item )
    {
        if ( 'content' === $item[ 'hotspot_content_type' ] ) {
            $tooltip_content = $item[ 'hotspot_wysiwyg' ];
        } elseif ( 'template' === $item[ 'hotspot_content_type' ] ) {
            $id = $item[ 'hotspot_content_templates' ];

            ob_start();
                echo ( new Frontend )->get_builder_content_for_display( $id );
            $tooltip_content = ob_get_clean();
        }

        if ( empty( $tooltip_content ) ) {
            // Bailout.
            return;
        }

        $tooltip_icon_value = $this->get_settings_for_display( 'tooltip_icon' )[ 'value' ] ?? '';
        if ( $tooltip_icon_value ) {
            if ( Icons_Manager::is_migration_allowed() ) {
                ob_start();
                    Icons_Manager::render_icon(
                        $this->get_settings_for_display( 'tooltip_icon' ),
                        [
                            'class' => 'tooltip__icon',
                            'aria-hidden' => 'true',
                        ]
                    );
                $tooltip_icon = ob_get_clean();
            } else {
                $tooltip_icon = '<i class="tooltip__icon ' . esc_attr( $tooltip_icon_value ) . '"></i>';
            }
        }

        $this->add_render_attribute( 'content_wrapper', [
            'class' => [
                'hotspot__tooltip',
                $this->get_settings_for_display( 'show_always' ) ? 'desktop-tooltips-show' : 'desktop-tooltips-hover',
                $this->get_settings_for_display( 'show_always_tablet' ) ? 'tablet-tooltips-show' : 'tablet-tooltips-hover',
                $this->get_settings_for_display( 'show_always_mobile' ) ? 'mobile-tooltips-show' : 'mobile-tooltips-hover',
            ],
        ] );
        $this->get_settings_for_display( 'tooltips_animation' ) && $this->add_render_attribute( 'content_wrapper', 'class', 'animation-' . $this->get_settings_for_display( 'tooltips_animation' ) );

        echo '<div ', $this->get_render_attribute_string( 'content_wrapper' ), '>',
            '<div class="tooltip__wrapper">',
                $tooltip_content,
                $tooltip_icon ?? '',
            '</div>',
        '</div>';
    }

    public function wpml_support_module()
    {
        add_filter( 'wpml_elementor_widgets_to_translate',  [$this, 'wpml_widgets_to_translate_filter' ] );
    }

    public function wpml_widgets_to_translate_filter( $widgets )
    {
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this, $widgets
        );
    }
}
