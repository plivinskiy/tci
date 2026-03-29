<?php
namespace WGL_Extensions\Includes;

defined('ABSPATH') || exit;

if (!did_action('elementor/loaded')) {
	return;
}

use Elementor\{
    Controls_Manager,
    Group_Control_Background,
    Core\Kits\Documents\Tabs\Tab_Base
};

if (class_exists('Elementor\Core\Kits\Documents\Tabs\Tab_Base')) {
	class WGL_Page_Transitions_Controls extends Tab_Base {

		public function get_id() {
			return 'wgl-page-transitions';
		}

		public function get_title() {
			return esc_html__( 'WGL Page Transitions', 'courto-core' );
		}

		public function get_icon() {
			return 'eicon-header';
		}

		public function get_help_url() {
			return '';
		}

		public function get_group() {
			return 'theme-style';
		}

		protected function register_tab_controls() {
			$this->start_controls_section(
				'wgl_page_transitions_section',
				[
					'tab' => 'wgl-page-transitions',
					'label' => esc_html__( 'WGL Page Transitions', 'courto-core' ),
				]
			);

			$this->add_control(
				$this->get_control_id( 'use_page_transition' ),
				[
					'label' => esc_html__('Use Page Transition?', 'courto-core'),
					'type' => Controls_Manager::SWITCHER,
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'  => $this->get_control_id( 'background' ),
					'exclude' => [ 'image', 'video' ],
					'fields_options' => [
						'background' => [
							'label' => esc_html__( 'Background', 'courto-core' ),
							'default' => 'classic',
							'description' => esc_html__( 'This is the page color behind your loading animation', 'courto-core' ),
						],
						'color' => [
							'default' => '#FFBC7D',
						],
					],
					'condition' => [
						$this->get_control_id( 'use_page_transition' ) => 'yes',
					],
					'selector' => '{{WRAPPER}} [wgl-page-transition]',
				]
			);

			$this->add_responsive_control(
				$this->get_control_id( 'entrance_animation' ),
				[
					'label' => esc_html__( 'Entrance Animation', 'courto-core' ),
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => [
						'' => esc_html__( 'None', 'courto-core' ),
						'fade-out' => esc_html__( 'Fade In', 'courto-core' ),
						'fade-out-down' => esc_html__( 'Fade In Down', 'courto-core' ),
						'fade-out-right' => esc_html__( 'Fade In Right', 'courto-core' ),
						'fade-out-up' => esc_html__( 'Fade In Up', 'courto-core' ),
						'fade-out-left' => esc_html__( 'Fade In Left', 'courto-core' ),
						'zoom-out' => esc_html__( 'Zoom In', 'courto-core' ),
						'slide-out-down' => esc_html__( 'Slide In Down', 'courto-core' ),
						'slide-out-right' => esc_html__( 'Slide In Right', 'courto-core' ),
						'slide-out-up' => esc_html__( 'Slide In Up', 'courto-core' ),
						'slide-out-left' => esc_html__( 'Slide In Left', 'courto-core' ),
					],
					'condition' => [
						$this->get_control_id( 'use_page_transition' ) => 'yes',
					],
					'selectors' => [
						'{{WRAPPER}}' => '--wgl-page-transition-entrance-animation: wgl-page-transition-{{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				$this->get_control_id( 'exit_animation' ),
				[
					'label' => esc_html__( 'Exit Animation', 'courto-core' ),
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => [
						'' => esc_html__( 'None', 'courto-core' ),
						'fade-in' => esc_html__( 'Fade Out', 'courto-core' ),
						'fade-in-down' => esc_html__( 'Fade Out Down', 'courto-core' ),
						'fade-in-right' => esc_html__( 'Fade Out Right', 'courto-core' ),
						'fade-in-up' => esc_html__( 'Fade Out Up', 'courto-core' ),
						'fade-in-left' => esc_html__( 'Fade Out Left', 'courto-core' ),
						'zoom-in' => esc_html__( 'Zoom Out', 'courto-core' ),
						'slide-in-down' => esc_html__( 'Slide Out Down', 'courto-core' ),
						'slide-in-right' => esc_html__( 'Slide Out Right', 'courto-core' ),
						'slide-in-up' => esc_html__( 'Slide Out Up', 'courto-core' ),
						'slide-in-left' => esc_html__( 'Slide Out Left', 'courto-core' ),
					],
					'selectors' => [
						'{{WRAPPER}}' => '--wgl-page-transition-exit-animation: wgl-page-transition-{{VALUE}}',
					],
					'condition' => [
						$this->get_control_id( 'use_page_transition' ) => 'yes',
						$this->get_control_id( 'entrance_animation' ) . '!' => '',
					],
				]
			);

			$this->add_control(
				$this->get_control_id( 'animation_duration' ),
				[
					'label' => esc_html__( 'Animation Duration', 'courto-core' ) . ' (ms)',
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'ms' ],
					'default' => [
						'unit' => 'ms',
						'size' => 1500,
					],
					'range' => [
						'ms' => [
							'min' => 0,
							'max' => 5000,
							'step' => 50,
						],
					],
					'condition' => [
						$this->get_control_id( 'use_page_transition' ) => 'yes',
						$this->get_control_id( 'entrance_animation' ) . '!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}' => '--wgl-page-transition-animation-duration: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->end_controls_section();
		}
	}
}

if (!class_exists('WGL_Page_Transitions')) {
    /**
     * WGL Elementor Page Transitions Settings
     *
     *
     * @package courto-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.0
     */
    class WGL_Page_Transitions
    {
        private static $instance;

        /**
         * Creates and returns an instance of the class
         *
         * @return object
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct()
        {
           $this->init_addons();

            // Add WGL Page Transitions Animation
            add_action( 'elementor/kit/register_tabs', function( \Elementor\Core\Kits\Documents\Kit $kit ) {
                $kit->register_tab( 'wgl-page-transitions', WGL_Page_Transitions_Controls::class );
            }, 1, 40 );

            add_action( 'wp_enqueue_scripts', [ $this, 'get_elementor_page_transition_css' ] );
        }

        public function init_addons()
        {
            add_action( 'wp_body_open', function () {
                if ( $this->should_render() ) {
                    $this->render();
                }
            }, 10, 2 );
        }

        public function get_elementor_page_transition_css()
        {
            $kit_id = get_option( 'elementor_active_kit' );

            if ( $this->should_render() && $kit_id ) {
                // Get the Kit CSS file
                $css_file = new \Elementor\Core\Files\CSS\Post($kit_id);
                $css_file->enqueue();
            }
        }

        public function should_render()
        {
            // Get the active kit ID
            $kit_id = get_option( 'elementor_active_kit' );

            // Access the global color settings using the kit ID
            $use_page_transition = \Elementor\Plugin::$instance->kits_manager->get_kit( $kit_id )->get_settings( 'use_page_transition' );

            if($use_page_transition){
                return true;
            }

        }
        public function render()
        {
            echo '<div wgl-page-transition></div>';
        }
    }

	if (!function_exists('wgl_page_transitions')) {
		function wgl_page_transitions()
		{
			return WGL_Page_Transitions::get_instance();
		}

		wgl_page_transitions();
	}
}
