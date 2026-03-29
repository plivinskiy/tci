<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{
    Frontend,
    Group_Control_Border,
    Widget_Base,
    Controls_Manager
};

use WGL_Extensions\{
    Includes\WGL_Cursor,
    Includes\WGL_Elementor_Helper
};

/**
 * Animated side menu button widget for Header CPT
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Animated_Side_Menu extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-header-animated_side_menu';
    }

    public function get_title()
    {
        return esc_html__('WGL Animated Side Menu', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-header-mobile_menu_button';
    }

    public function get_keywords() {
        return ['link', 'button', 'mobile', 'menu', 'panel', 'side', 'animated'];
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    public function get_script_depends() {
        return [ 'wgl-widgets' ];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_side_panel_settings',
            ['label' => esc_html__('Mobile Button', 'courto-core')]
        );

        $this->add_control(
            'content_templates',
            [
                'label' => esc_html__('Choose Template', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
            ]
        );

        $this->add_control(
            'hide_on_scroll',
            [
                'label' => esc_html__( 'Hide on Scroll', 'courto-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'courto-core' ),
                'label_off' => esc_html__( 'No', 'courto-core' ),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $this->add_control(
            'hide_amount',
            [
                'label' => esc_html__( 'Hide Scroll Amount (px)', 'courto-core' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 100,
                'condition' => [
                    'hide_on_scroll' => 'true',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-side-menu-area' => '--hide-on-scroll-amount: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sp_line_width',
            [
                'label' => esc_html__('Line Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 200],
                    '%' => ['min' => 1, 'max' => 100],
                ],
                'selectors' => [
				    '{{WRAPPER}} .hamburger-box-animated .hamburger-inner-animated,
                     {{WRAPPER}} .hamburger-box-animated .hamburger-inner-animated span' => 'width: {{SIZE}}{{UNIT}};',
			    ],
            ]
        );

        $this->add_responsive_control(
            'sp_width',
            [
                'label' => esc_html__('Button Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 30, 'max' => 200],
                    '%' => ['min' => 5, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hamburger-box-animated' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sp_height',
            [
                'label' => esc_html__('Button Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 30, 'max' => 250],
                    '%' => ['min' => 5, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hamburger-box-animated' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['sp_width!' => 0],
                'options' => [
                    'margin-right' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'margin' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'margin-left' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}: auto;',
                ],

            ]
        );

        $this->add_control(
            'alignment_v',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'flex-start;' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center;' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end;' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center;',
                'selectors' => [
                    '{{WRAPPER}} .hamburger-box-animated' => 'align-items: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_button_animation',
            [
                'label' => esc_html__( 'Button Style', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .hamburger-box-animated',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .hamburger-box-animated' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'sp_color_tabs',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_color_idle',
            ['label' => esc_html__('Idle' , 'courto-core')]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hamburger-box-animated' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'item_bg_idle',
            [
                'label' => esc_html__('Item Background', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hamburger-box-animated' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_color_hover',
            ['label' => esc_html__('Hover' , 'courto-core')]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .hamburger-box-animated' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'item_bg_hover',
            [
                'label' => esc_html__('Item Background', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .hamburger-box-animated' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__( 'Content Style', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'vw', 'custom'],
                'default' => [ 'unit' => 'px', 'size' => 560 ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-side-menu-area .wgl-side-menu' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_position_offset_x',
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
                'default' => [ 'unit' => 'px', 'size' => -85 ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-side-menu-area .wgl-side-menu' => '--right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'content_position_offset_y',
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
                'default' => [ 'unit' => 'px', 'size' => 65 ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-side-menu-area .wgl-side-menu' => '--top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * GENERAL -> CURSOR
         */

        WGL_Cursor::init(
            $this,
            [
                'section' => true,
            ]
        );
    }

    public function render()
    {
        $_s = $this->get_settings_for_display();

        if (isset($_s['cursor_tooltip']) && '' != $_s['cursor_tooltip']) {
            add_filter('wgl/courto_module_cursor', function () { return true; });
        }

        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($this, $_s);

        $hide_on_scroll = $_s['hide_on_scroll'];

        $this->add_render_attribute('area', [
            'class' => [
                'wgl-side-menu-area',
                ( isset($hide_on_scroll) && !empty($hide_on_scroll) ? ' wgl-hide-on-scroll' : '' )
            ],
        ]);

        $this->add_render_attribute('button', [
            'class' => [
                'hamburger-box-animated',
                ( isset($_s['cursor_tooltip']) && !empty($_s['cursor_tooltip']) ? ' wgl-cursor-text' : '' )
            ],
            'title' => esc_attr__('Open', 'synta-core')
        ]);

        ?>
        <div <?php echo $this->get_render_attribute_string('area'); ?>>
            <div class="wgl-side-menu-button mobile-hamburger-module">
                <div <?php echo $this->get_render_attribute_string('button') . $cursor_data; ?>>
                    <span class="hamburger-inner-animated">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
            </div>
            <div class="wgl-side-menu e-con">
                <?php
                $id = $_s['content_templates'];
                $wgl_frontend = new \Elementor\Frontend;
                echo $wgl_frontend->get_builder_content_for_display($id);
                ?>
            </div>
        </div>
        <?php
    }
}