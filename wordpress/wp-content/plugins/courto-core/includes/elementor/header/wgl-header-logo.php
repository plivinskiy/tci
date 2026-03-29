<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Utils
};

/**
 * Logo widget for Header CPT
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Logo extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-header-logo';
    }

    public function get_title()
    {
        return esc_html__('WGL Logo', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-header-logo';
    }

    public function get_keywords() {
        return ['logotype'];
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'use_custom_logo',
            [
                'label' => esc_html__('Use Custom Logo?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'custom_logo',
            [
                'label' => esc_html__('Custom Logo', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'condition' => ['use_custom_logo!' => ''],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_control(
            'flex_grow',
            [
                'label' => esc_html__('Flex Grow', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => -1,
                'max' => 20,
                'selectors' => [
                    '{{WRAPPER}}' => 'flex-grow: {{VALUE}}; display: inline-flex;',
                ],
            ]
        );

        $this->add_control(
            'enable_logo_height',
            [
                'label' => esc_html__('Enable Logo Height?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['use_custom_logo!' => ''],
            ]
        );

        $this->add_control(
            'logo_height',
            [
                'label' => esc_html__('Logo Height', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'use_custom_logo!' => '',
                    'enable_logo_height!' => '',
                ],
                'min' => 1,
                'default' => 90,
            ]
        );

        $this->add_control(
            'logo_align',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'toggle' => true,
                'options' => [
                    'left;justify-content: lex-start;' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center;justify-content:center;' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right;justify-content:flex-end;' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left;justify-content: lex-start;',
                'selectors' => [
                    '{{WRAPPER}} .wgl-logotype-container,
                     {{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'mobile_header_animation',
			[
				'label' => esc_html__( 'Mobile Entrance Animation', 'courto-core' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
                'prefix_class' => 'animated ',
			]
		);

		$this->add_control(
			'mobile_header_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'courto-core' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 0,
				'step' => 100,
				'condition' => [
					'mobile_header_animation!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'animation-delay: {{VALUE}}ms;',
                ],
			]
		);

        $this->end_controls_section();
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();
        extract($settings);

        $custom_size = false;

        $logo = !empty($custom_logo) ? $custom_logo : false;

        if (
            $logo
            && !empty($enable_logo_height)
            && !empty($logo_height)
        ) {
            $custom_size = $logo_height;
        }

        require_once (get_theme_file_path('/templates/header/components/logo.php'));

        new \Courto_Get_Logo('bottom', false, $logo, $custom_size);
    }
}
