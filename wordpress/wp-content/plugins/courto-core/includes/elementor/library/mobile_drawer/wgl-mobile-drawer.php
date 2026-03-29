<?php
namespace WGL_Extensions\Library;

use Elementor\{
	Group_Control_Background,
    TemplateLibrary\Source_Local,
    Controls_Manager,
	Modules\Library\Documents\Library_Document
};

defined('ABSPATH') || exit;

/**
 * WGL Elementor Mobile Drawer
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.12
 * @version 1.0.0
 */
class WGL_Mobile_Drawer extends Library_Document
{
	/**
	* Elementor template-library post-type slug.
	*/
	const CPT = 'elementor_library';

	/**
	* WGL Library name.
	*/
	public static $name = 'wgl-mobile-drawer';

	public function __construct( array $data = [] ) {
		if ( $data ) {
			$template = get_post_meta( $data['post_id'], '_wp_page_template', true );

			if ( empty( $template ) ) {
				$template = 'default';
			}

			$data['settings']['template'] = $template;
		}

		parent::__construct( $data );
	}

	public static function get_properties(){
		$properties = parent::get_properties();

		$properties['support_kit'] = true;
		return $properties;
	}

	public function get_name(){
		return self::$name;
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return esc_html__( 'WGL Mobile Drawer', 'courto-core' );
	}

	public static function get_plural_title() {
		return esc_html__( 'WGL Mobile Drawer', 'courto-core' );
	}

	/** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template */
	public static function get_single_template($single_template){

		global $post;
		$template_type = Source_Local::get_template_type($post->ID);

		if(self::CPT === $post->post_type && self::$name === $template_type) {
			if ( defined( 'ELEMENTOR_PATH' ) ) {
                $elementor_template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';
				\Elementor\Plugin::$instance->frontend->add_body_class( 'mobile_switch_on' );

				if ( file_exists( $elementor_template ) ) {
                    add_action( 'elementor/page_templates/canvas/before_content', [ __CLASS__, 'wrapper_open' ] );
                    add_action( 'elementor/page_templates/canvas/after_content', [ __CLASS__, 'wrapper_close' ] );

                    return $elementor_template;
                }
            }

			$single_template = plugin_dir_path(  __FILE__  ) . 'templates/single-mobile-drawer.php';
		}

		//\Elementor\Plugin::$instance->files_manager->clear_cache();

		return $single_template;
	}

	public static function inject_options_post($document)
    {
		$template_type = Source_Local::get_template_type($document->get_main_id());

		if(self::$name === $template_type){
			self::get_mobile_drawer_controls($document);
		}else if('header' === get_post_type()){
            self::get_header_controls($document);
        }
    }

    public static function get_mobile_drawer_templates()
    {
        $templates = get_posts([
            'post_type' => 'elementor_library',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_elementor_template_type',
                    'value' => self::$name,
                ],
            ],
        ]);

        $options['wgl_default_template_drawer'] = esc_html__('Inherit From Theme Options', 'courto-core');

        if (!empty($templates) && !is_wp_error($templates)) {

            foreach ($templates as $template) {
                $options[$template->ID] = $template->post_title;
            }

            update_option('temp_count', $options);

            return $options ?? [];
        }
    }

    public static function get_header_controls($document)
    {
        $document->start_controls_section(
            'header_drawer_options',
            [
                'label' => esc_html__('WGL Header Drawer Template', 'courto-core'),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );

        $document->add_control(
            'mobile_drawer_template',
            [
                'label' => esc_html__('Mobile Drawer Template', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => self::get_mobile_drawer_templates(),
                'default' => 'wgl_default_template_drawer',
            ]
        );

        $document->end_controls_section();
    }

	public static function get_mobile_drawer_controls($document)
    {
        $document->start_controls_section(
            'settings_mobile_drawer_options',
            [
                'label' => esc_html__('WGL Mobile Drawer Options', 'courto-core'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $document->add_control(
			'mobile_drawer_container_heading',
			[
				'label' => esc_html__('Mobile Drawer Container', 'courto-core'),
				'type' => Controls_Manager::HEADING,
			]
        );

        $document->add_control(
            'mobile_drawer_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
            ]
        );

		$document->add_control(
            'mobile_drawer_full_width',
            [
                'label' => esc_html__('Full Width', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
				'selectors' => [
                    '{{WRAPPER}} .mobile_nav_wrapper .wgl-menu_outer' => 'width: 100%;',
                ],
            ]
        );

        $document->add_responsive_control(
		    'mobile_drawer_container_width',
		    [
			    'label' => esc_html__('Width', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => ['px', '%', 'custom'],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 2000 ],
				    '%' => ['min' => 10, 'max' => 100 ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .mobile_nav_wrapper .wgl-menu_outer' => 'width: {{SIZE}}{{UNIT}};',
			    ],
				'condition' => [
                    'mobile_drawer_full_width' => '',
                ],
		    ]
	    );

        $document->add_responsive_control(
            'mobile_drawer_container_inner_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => 50,
                    'left' => 50,
                    'right' => 50,
                    'bottom' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mobile_nav_wrapper .wgl-menu_outer .wgl-menu-outer_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$document->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'mobile_drawer_container_bg',
                'selector' => '{{WRAPPER}} .mobile_nav_wrapper .wgl-menu_outer',
            ]
        );

        $document->add_responsive_control(
			'mobile_drawer_container_animation',
			[
				'label' => esc_html__( 'Container Animation', 'courto-core' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
                'options' => [
					'translate' => esc_html__( 'Translate', 'courto-core' ),
					'fade_down' => esc_html__( 'Fade In Down', 'courto-core' ),
				],
                'default' => 'translate',
                'prefix_class' => 'container-animated-',
			]
		);

        $document->add_responsive_control(
			'mobile_drawer_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'courto-core' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
			]
		);

		$document->add_control(
			'mobile_drawer_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'courto-core' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 0,
				'step' => 100,
				'condition' => [
					'mobile_drawer_animation!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$document->add_control(
			'mobile_drawer_close_icon_heading',
			[
				'label' => esc_html__('Mobile Drawer Close Icon', 'courto-core'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );

		$document->add_responsive_control(
		    'mobile_drawer_close_padding',
		    [
			    'label' => esc_html__('Padding', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-menu-outer_header .hamburger-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $document->add_responsive_control(
            'mobile_drawer_close_position_top',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mobile_nav_wrapper .wgl-menu_outer .wgl-menu-outer_header' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $document->add_responsive_control(
            'mobile_drawer_close_position_left',
            [
                'label' => esc_html__('Left Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -2000, 'max' => 2000, 'step' => 1],
                ],
				'condition' => ['mobile_drawer_position' => 'left'],
                'selectors' => [
                    '{{WRAPPER}} .mobile_nav_wrapper .wgl-menu_outer .wgl-menu-outer_header' => 'left: auto; right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$document->add_responsive_control(
            'mobile_drawer_close_position_right',
            [
                'label' => esc_html__('Right Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -2000, 'max' => 2000, 'step' => 1],
                ],
				'condition' => ['mobile_drawer_position' => 'right'],
				'default' => ['size' => -45, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mobile_nav_wrapper .wgl-menu_outer .wgl-menu-outer_header' => 'right: auto; left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


		$document->start_controls_tabs( 'mobile_drawer_close_icon' );

        $document->start_controls_tab(
            'mobile_drawer_close_idle_tab',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );

		$document->add_control(
            'mobile_drawer_close_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
				'selectors' => [
                    '{{WRAPPER}} .mobile_nav_wrapper .mobile-hamburger-close' => 'color: {{VALUE}};',
                ],
            ]
        );

		$document->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'mobile_drawer_close_bg',
                'selector' => '{{WRAPPER}} .mobile_nav_wrapper .mobile-hamburger-close',
            ]
        );

        $document->end_controls_tab();

        $document->start_controls_tab(
            'mobile_drawer_close_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );

		$document->add_control(
            'mobile_drawer_close_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
				'selectors' => [
                    '{{WRAPPER}} .mobile_nav_wrapper .mobile-hamburger-close:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

		$document->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'mobile_drawer_close_bg_hover',
                'selector' => '{{WRAPPER}} .mobile_nav_wrapper .mobile-hamburger-close:hover',
            ]
        );

        $document->end_controls_tab();
        $document->end_controls_tabs();

        $document->end_controls_section();
    }

	public static function get_class_full_name() {
		return get_called_class();
	}

	public static function wrapper_open()
    {
		echo '<div class="mobile_nav_wrapper">';
		echo '<div class="container-wrapper">';
		echo '<div class="wgl-menu_outer">';
		echo '<div class="wgl-menu-outer_header mobile-hamburger-module">';
		echo '<div class="mobile-hamburger-close">';
		echo '<div class="hamburger-box">';
		echo '<div class="hamburger-inner"><span></span><span></span><span></span></div>';
		echo '</div>'; // close hamburger-box
		echo '</div>'; // close mobile-hamburger-close
		echo '</div>'; // close wgl-menu-outer_header
		echo '<div class="wgl-menu-outer_content">';
    }

    public static function wrapper_close()
    {
		echo '</div>'; // close wgl-menu-outer_content
		echo '</div>'; // close wgl-menu_outer
		echo '</div>'; // close container-wrapper
		echo '</div>'; // close mobile_nav_wrapper
    }
}