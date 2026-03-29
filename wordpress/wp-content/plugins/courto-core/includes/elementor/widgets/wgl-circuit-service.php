<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-circuit-service.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Group_Control_Box_Shadow,
    Group_Control_Typography,
    Group_Control_Image_Size,
    Repeater,
    Utils,
    Icons_Manager
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Circuit_Service extends Widget_Base
{
    public function get_name() {
        return 'wgl-circuit-service';
    }

    public function get_title() {
        return esc_html__('WGL Circuit Service', 'courto-core');
    }

    public function get_icon() {
        return 'wgl-circuit-service';
    }

    public function get_keywords() {
        return ['circuit', 'service'];
    }

    public function get_categories() {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_ib_content',
            [
                'label' => esc_html__('General', 'courto-core'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'service_icon_type',
            [
                'label' => esc_html__('Add Icon/Image', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
            'service_icon_fontawesome',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'service_icon_type' => 'font',
                ],
                'label_block' => true,
                'description' => esc_html__('Select icon from Fontawesome library.', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'service_icon_thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'service_icon_type' => 'image',
                ],
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'service_title',
            [
                'label' => esc_html__('Service Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'service_text',
            [
                'label' => esc_html__('Service Text', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'service_link',
            [
                'label' => esc_html__('Add Link', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Service', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{service_title}}',
                'default' => [
                    [
                        'service_title' => esc_html__('Circuit Title 1', 'courto-core'),
                        'service_icon_type' => 'font',
                        'service_icon_fontawesome' => [
                            'value' => 'fas fa-plus',
	                        'library' => 'fa-solid',
                        ]
                    ],
                    [
                        'service_title' => esc_html__('Circuit Title 2', 'courto-core'),
                        'service_icon_type' => 'font',
                        'service_icon_fontawesome' => [
                            'value' => 'fas fa-car',
	                        'library' => 'fa-solid',
                        ]
                    ],
                    [
                        'service_title' => esc_html__('Circuit Title 3', 'courto-core'),
                        'service_icon_type' => 'font',
                        'service_icon_fontawesome' => [
                            'value' => 'fas fa-user-astronaut',
	                        'library' => 'fa-solid',
                        ]
                    ],
                ],
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> ITEM
         */

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('Item', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_space',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => '%',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> TITLE
         */

        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML Tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'div' => esc_html('‹div›'),
                    'span' => esc_html('‹span›'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'title_offset',
            [
                'label' => esc_html__('Title Offset', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-services_title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_tag',
            [
                'label' => esc_html__('HTML Tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'div' => esc_html('‹div›'),
                    'span' => esc_html('‹span›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'content_offset',
            [
                'label' => esc_html__('Content Offset', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-services_text',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Icon
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'icon_style_section',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_icon_style' );

        $this->start_controls_tab(
            'tab_icon_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-services_item .wgl-services_icon-wrap',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_item.active .wgl-services_icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_item.active .wgl-services_icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-services_item.active .wgl-services_icon-wrap',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => 38,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_bg_h',
            [
                'label' => esc_html__('Icon Background Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'size' => 90,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 60,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-services_icon-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-circuit-service::before' => 'left: calc({{SIZE}}{{UNIT}} / 2); top: calc({{SIZE}}{{UNIT}} / 2); width: calc(100% - {{SIZE}}{{UNIT}}); height: calc(100% - {{SIZE}}{{UNIT}});',
                ],
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();

    }

    public function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('services', 'class', 'wgl-circuit-service');

        $kses_allowed_html = [
            'a' => [
                'href' => true, 'title' => true,
                'class' => true, 'style' => true,
                'rel' => true, 'target' => true
            ],
            'br' => ['class' => true, 'style' => true],
            'em' => ['class' => true, 'style' => true],
            'strong' => ['class' => true, 'style' => true],
            'span' => ['class' => true, 'style' => true],
            'p' => ['class' => true, 'style' => true]
        ];

        ?>

        <div <?php echo $this->get_render_attribute_string( 'services' ); ?>><?php
            foreach ($settings[ 'items' ] as $index => $item) {

                if (!empty($item['service_link']['url'])) {
                    $service_link = $this->get_repeater_setting_key('service_link', 'items', $index);
                    $this->add_render_attribute($service_link, 'class', 'wgl-services_link');
                    $this->add_link_attributes($service_link, $item['service_link']);
                }

                ?>
                <div class="wgl-services_item"><?php
                    // Icon/Image service
                    if($item[ 'service_icon_type' ] != '') {?>
                        <div class="wgl-services_icon-wrap"><?php
                        if ($item[ 'service_icon_type' ] == 'font' && (!empty($item['service_icon_fontawesome']))) {

                            $icon_font = $item[ 'service_icon_fontawesome' ];
                            $icon_out = '';
                            // add icon migration
                            $migrated = isset( $item['__fa4_migrated'][$item['service_icon_fontawesome']] );
                            $is_new = Icons_Manager::is_migration_allowed();
                            if ( $is_new || $migrated ) {
                                ob_start();
                                Icons_Manager::render_icon($item['service_icon_fontawesome'], [ 'aria-hidden' => 'true' ]);
                                $icon_out .= ob_get_clean();
                            } else {
                                $icon_out .= '<i class="icon '.esc_attr($icon_font).'"></i>';
                            }

                            ?>
                            <span class="wgl-services_icon">
                                <?php
                                    echo $icon_out;
                                ?>
                            </span>
                            <?php
                        }
                        if ($item[ 'service_icon_type' ] == 'image' && ! empty($item[ 'service_icon_thumbnail' ])) {
                            if (!empty($item['service_icon_thumbnail']['url'])) {
                                $this->add_render_attribute('thumbnail', 'src', $item['service_icon_thumbnail']['url']);
                                $this->add_render_attribute('thumbnail', 'alt', Control_Media::get_image_alt($item['service_icon_thumbnail']));
                                $this->add_render_attribute('thumbnail', 'title', Control_Media::get_image_title($item['service_icon_thumbnail']));
                                ?>
                                <span class="wgl-services_icon wgl-services_icon-image">
                                <?php
                                    echo Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'service_icon_thumbnail');
                                ?>
                                </span>
                                <?php
                            }
                        }?>
                        </div><?php
                    }?>
                    <div class="wgl-services_content-wrap"><?php
                    // End Icon/Image service
                    if (!empty($item['service_title'])) { ?>
                        <<?php echo $settings[ 'title_tag' ]; ?> class="wgl-services_title"><?php echo wp_kses($item['service_title'], $kses_allowed_html);?></<?php echo $settings['title_tag']; ?>><?php
                    }
                    if (!empty($item['service_text'])) { ?>
                        <<?php echo $settings[ 'content_tag' ]; ?> class="wgl-services_text"><?php echo wp_kses($item['service_text'], $kses_allowed_html);?></<?php echo $settings['content_tag']; ?>><?php
                    }
                    if (!empty($item['service_link']['url'])) {
                        echo '<a ', $this->get_render_attribute_string($service_link), '></a>';
                    }
                    ?>
                    </div>
                </div><?php
            }?>
        </div>

        <?php

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
