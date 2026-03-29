<?php
namespace WGL_Extensions\Modules;

defined('ABSPATH') || exit;

use Elementor\{Controls_Manager,
    Group_Control_Background,
    Group_Control_Css_Filter,
    Group_Control_Typography,
    Repeater,
    Plugin,
    Utils};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * WGL Elementor Section
 *
 *
 * @package wgl-extensions\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Section
{
    public $sections = [];
    const CACHE_META_KEY = '_elementor_element_cache';

    public function __construct()
    {
        add_action('elementor/init', [$this, 'add_hooks']);
    }

    public function add_hooks()
    {
        if(!class_exists('WGL_Framework')){
            return;
        }

        // Add WGL extension control section to Section panel
        add_action('elementor/element/section/section_typo/after_section_end', [$this, 'extended_blur_options'], 9, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_blur_options'], 9, 2);

        add_action('elementor/element/column/section_typo/after_section_end', [$this, 'extended_blur_options'], 9, 2);

        add_action('elementor/element/section/section_typo/after_section_end', [$this, 'extended_animation_options'], 10, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_animation_options'], 10, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_container_sticky_options'], 10, 2);

        add_action('elementor/element/column/layout/after_section_end', [$this, 'extends_column_params'], 10, 2);

        add_action('elementor/frontend/section/before_render', [$this, 'extended_row_render'], 10, 1);
        add_action('elementor/frontend/container/before_render', [$this, 'extended_row_render'], 10, 1);

        add_action('elementor/frontend/column/before_render', [$this, 'extended_column_render'], 10, 1);
        add_action('elementor/frontend/container/before_render', [$this, 'extended_column_render'], 10, 1);

        add_action('elementor/element/wp-page/document_settings/after_section_end', [$this, 'inject_options_page'], 10, 1);
        add_action('elementor/element/wp-post/document_settings/after_section_end', [$this, 'inject_options_post'], 10, 1);

        // Add WGL Editor Style
        if(!class_exists('\ElementorPro\Plugin')){
            add_action('elementor/element/after_section_end', [$this, 'add_controls_section'], 10, 3);
            add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);
            add_action('elementor/css-file/post/parse', [ $this, 'add_page_settings_css']);
        }
        // Add Compatible for new feature Element Caching   
        if('disable' !== get_option( 'elementor_element_cache_ttl', '' )){
            add_filter('elementor/frontend/builder_content_data', [ $this, 'cache_page_elementor'], 10, 2);
        }
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    function extractElements($array, $types = ['container', 'section', 'column']) {
        $result = [];
    
        foreach ($array as $element) {
            if (is_array($element)) {
                if (isset($element['elType']) && in_array($element['elType'], $types)) {
                    $result[] = $element;
                }
    
                if (isset($element['elements']) && is_array($element['elements'])) {
                    $result = array_merge($result, $this->extractElements($element['elements'], $types));
                }
            }
        }
    
        return $result;
    }

    public function get_json_meta( $key, $ID ) {
		$meta = get_post_meta( $ID, $key, true );

		if ( is_string( $meta ) && ! empty( $meta ) ) {
			$meta = json_decode( $meta, true );
		}

		if ( empty( $meta ) ) {
			$meta = [];
		}

		return $meta;
	}

    private function get_document_cache( $post_id ) {
		$cache = $this->get_json_meta( static::CACHE_META_KEY, $post_id );

		if ( empty( $cache['timeout'] ) ) {
			return false;
		}

		if ( current_time( 'timestamp' ) > $cache['timeout'] ) {
			return false;
		}

		if ( ! is_array( $cache['value'] ) ) {
			return false;
		}

		return $cache['value'];
	}

    public function cache_page_elementor($data, $post_id) {

        $cached_data = $this->get_document_cache($post_id);
        if ( false === $cached_data ) {
            return $data;
        }
        $extract_settings = $this->extractElements($data);
        foreach($extract_settings as $extract_setting){
            $setting_id = $extract_setting['id'] ?? '';
            if($setting_id){
                $settings = $this->ext_section_settings_elementor($extract_setting['settings']);
    
                // Background Text Extensions
                if (!empty($settings['add_background_text'])) {
                    $settings['background_text'] = $settings['background_text'] ?? esc_html__('Text', 'wgl-extensions');
                    $settings['bg_text_color_1'] = $settings['bg_text_color_1'] ?? WGL_Globals::get_primary_color(1);
                    $settings['bg_text_color_2'] = $settings['bg_text_color_2'] ?? WGL_Globals::get_primary_color(0);
                    $settings['bg_text_location_1'] = $settings['bg_text_location_1'] ?? [ 'unit' => '%', 'size' => 0 ];
                    $settings['bg_text_location_2'] = $settings['bg_text_location_2'] ?? [ 'unit' => '%', 'size' => 100 ];
                    $settings['bg_text_gradient_type'] = $settings['bg_text_gradient_type'] ?? 'linear';
                    $settings['bg_text_gradient_angle'] = $settings['bg_text_gradient_angle'] ?? [ 'unit' => 'deg', 'size' => 180 ];
                    $settings['bg_text_gradient_position'] = $settings['bg_text_gradient_position'] ?? 'center center';
                    $settings['background_text_index'] = $settings['background_text_index'] ?? 0;
                    wp_enqueue_script('jquery-appear', esc_url(get_template_directory_uri() . '/js/jquery.appear.js'));
                    wp_enqueue_script('anime', esc_url(get_template_directory_uri() . '/js/anime.min.js'));
                }
        
                // 3D Wave Extensions
                if (isset($settings['add_wave'])
                    && !empty($settings['add_wave'])) {
                    $settings['wave_dots_color'] = $settings['wave_dots_color'] ?? '#8d07d5';
                    $settings['wave_bg_color'] = $settings['wave_bg_color'] ?? '#000000';
                    $settings['wave_dots_max_x'] = $settings['wave_dots_max_x'] ?? ['size' => 0];
                    $settings['wave_dots_max_y'] = $settings['wave_dots_max_y'] ?? ['size' => 150];

                    wp_enqueue_script('three', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/three.min.js'), [],wp_get_theme()->get('Version') ?? false);
                    wp_enqueue_script('projector', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/projector.js'), [],wp_get_theme()->get('Version') ?? false);
                    wp_enqueue_script('canvas-renderer', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/canvas.renderer.js'), [],wp_get_theme()->get('Version') ?? false);
                    wp_enqueue_script('stats', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/stats.min.js'), [],wp_get_theme()->get('Version') ?? false);
                }
        
                // Parallax Extensions
                if (
                    isset($settings['add_background_animation'])
                    && !empty($settings['add_background_animation'])
                    && !(bool) Plugin::$instance->editor->is_edit_mode()
                ) {

                    $scroll_animation = $mouse_animation = $css_animation = false;
                    foreach ($settings['items_parallax'] as $k => $v) {
                        $settings['items_parallax'][$k]['image_effect'] = $settings['items_parallax'][$k]['image_effect'] ?? 'scroll';
                        $settings['items_parallax'][$k]['animation_name'] = $settings['items_parallax'][$k]['animation_name'] ?? 'fadeIn';
                        $settings['items_parallax'][$k]['animation_name_iteration_count'] = $settings['items_parallax'][$k]['animation_name_iteration_count'] ?? '1';
                        $settings['items_parallax'][$k]['animation_name_speed'] = $settings['items_parallax'][$k]['animation_name_speed'] ?? '1';
                        $settings['items_parallax'][$k]['animation_name_direction'] = $settings['items_parallax'][$k]['animation_name_direction'] ?? 'normal';
                        $settings['items_parallax'][$k]['image_bg'] = $settings['items_parallax'][$k]['image_bg'] ?? ['url' => ''];
                        $settings['items_parallax'][$k]['parallax_dir'] = $settings['items_parallax'][$k]['parallax_dir'] ?? 'vertical';
                        $settings['items_parallax'][$k]['parallax_factor'] = $settings['items_parallax'][$k]['parallax_factor'] ??  0.03;
                        $settings['items_parallax'][$k]['position_top'] = $settings['items_parallax'][$k]['position_top'] ??  ['size' => 0, 'unit' => '%'];
                        $settings['items_parallax'][$k]['position_left'] = $settings['items_parallax'][$k]['position_left'] ?? ['size' => 0, 'unit' => '%'];
                        $settings['items_parallax'][$k]['position_rotate'] = $settings['items_parallax'][$k]['position_rotate'] ??  ['unit' => 'deg'];
                        $settings['items_parallax'][$k]['image_index'] = $settings['items_parallax'][$k]['image_index'] ??  -1;
                        $settings['items_parallax'][$k]['hide_mobile_resolution'] = $settings['items_parallax'][$k]['hide_mobile_resolution'] ??  768;
                        
                        if('css_animation' === $settings['items_parallax'][$k]['image_effect']){
                            $css_animation = true;
                        }elseif('mouse' === $settings['items_parallax'][$k]['image_effect']){
                            $mouse_animation = true;
                        }elseif('scroll' === $settings['items_parallax'][$k]['image_effect']){
                            $scroll_animation = true;
                        }
                    }
        
                    if($css_animation){
                        wp_enqueue_style('animate', esc_url(get_template_directory_uri() . '/css/animate.css'), ['e-animations']);
                    }
        
                    if($mouse_animation){
                        wp_enqueue_script('parallax', esc_url(get_template_directory_uri() . '/js/parallax.min.js'));
                    }
        
                    if($scroll_animation){
                        wp_enqueue_script('jquery-paroller', esc_url(get_template_directory_uri() . '/js/jquery.paroller.min.js'));
                    }
                }
        
                // Section Parallax
                if (
                    isset($settings['add_section_parallax'])
                    && !empty($settings['add_section_parallax'])
                    && !(bool) Plugin::$instance->editor->is_edit_mode()
                ) {
                    $settings['section_parallax_dir'] = $settings['section_parallax_dir'] ?? 'vertical';
                    $settings['section_parallax_factor'] = $settings['section_parallax_factor'] ?? 0.1;
                    wp_enqueue_script('jquery-paroller', esc_url(get_template_directory_uri() . '/js/jquery.paroller.min.js'));
                }
        
                // Particles Extensions
                if (
                    !empty($settings['add_particles_animation'])
                    && !(bool) Plugin::$instance->editor->is_edit_mode()
                ) {
                    foreach ($settings['items_particles'] as $k => $v) {
                        $settings['items_particles'][$k]['particles_effect'] = $settings['items_particles'][$k]['particles_effect'] ?? 'one_color';
                        $settings['items_particles'][$k]['particles_color_one'] = $settings['items_particles'][$k]['particles_color_one'] ?? WGL_Globals::get_primary_color();
                        $settings['items_particles'][$k]['particles_count'] = $settings['items_particles'][$k]['particles_count'] ?? 50;
                        $settings['items_particles'][$k]['particles_max_size'] = $settings['items_particles'][$k]['particles_max_size'] ?? 10;
                        $settings['items_particles'][$k]['particles_speed'] = $settings['items_particles'][$k]['particles_speed'] ?? 2;
                        $settings['items_particles'][$k]['particles_hover_animation'] = $settings['items_particles'][$k]['particles_hover_animation'] ?? 'grab';
                        $settings['items_particles'][$k]['position_particles_top'] = $settings['items_particles'][$k]['position_particles_top'] ?? ['size' => 0, 'unit' => '%'];
                        $settings['items_particles'][$k]['position_particles_left'] = $settings['items_particles'][$k]['position_particles_left'] ?? ['size' => 0, 'unit' => '%'];
                        $settings['items_particles'][$k]['particles_width'] = $settings['items_particles'][$k]['particles_width'] ?? 100;
                        $settings['items_particles'][$k]['particles_height'] = $settings['items_particles'][$k]['particles_height'] ?? 100;
                        $settings['items_particles'][$k]['hide_particles_mobile_resolution'] = $settings['items_particles'][$k]['hide_particles_mobile_resolution'] ?? 768;
                    }
                    wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', ['jquery'], false, true);
                }
        
                // Particles Img Extensions
                if (
                    !empty($settings['add_particles_img_animation'])
                    && !(bool) Plugin::$instance->editor->is_edit_mode()
                ) {
                    foreach ($settings['items_particles_img'] as $k => $v) {
                        $settings['items_particles_img'][$k]['particles_image'] = $settings['items_particles_img'][$k]['particles_image'] ?? ['url' => Utils::get_placeholder_image_src()];
                        $settings['items_particles_img'][$k]['particles_img_width'] = $settings['items_particles_img'][$k]['particles_img_width'] ?? 100;
                        $settings['items_particles_img'][$k]['particles_img_height'] = $settings['items_particles_img'][$k]['particles_img_height'] ?? 100;
                    }
                    $settings['particles_img_color'] = $settings['particles_img_color'] ?? '';
                    $settings['particles_img_max_size'] = $settings['particles_img_max_size'] ?? 60;
                    $settings['particles_img_count'] = $settings['particles_img_count'] ?? 50;
                    $settings['particles_img_speed'] = $settings['particles_img_speed'] ?? 2;
                    $settings['particles_img_rotate'] = $settings['particles_img_rotate'] ?? 'yes';
                    $settings['particles_img_rotate_speed'] = $settings['particles_img_rotate_speed'] ?? 5;
                    $settings['particles_img_hover_animation'] = $settings['particles_img_hover_animation'] ?? 'grab';
                    $settings['position_particles_img_top'] = $settings['position_particles_img_top'] ?? ['size' => 0, 'unit' => '%'];
                    $settings['position_particles_img_left'] = $settings['position_particles_img_left'] ?? ['size' => 0, 'unit' => '%'];
                    $settings['particles_img_container_width'] = $settings['particles_img_container_width'] ?? 100;
                    $settings['particles_img_container_height'] = $settings['particles_img_container_height'] ?? 100;
                    $settings['hide_particles_img_mobile_resolution'] = $settings['hide_particles_img_mobile_resolution'] ?? 768;
                    wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', ['jquery'], false, true);
                }

                if (isset($settings['enable_layered'])
                    && !empty($settings['enable_layered'])
                    && !(bool) Plugin::$instance->editor->is_edit_mode()) {
                    $settings['layered_start'] = $settings['layered_start'] ?? '';
                    $settings['custom_layered_class'] = $settings['custom_layered_class'] ?? '';
                    $settings['layered_end'] = $settings['layered_end'] ?? '';
                    $settings['layered_start_position'] = $settings['layered_start_position'] ?? 'top top';
                    $settings['layered_custom_start_position'] = $settings['layered_custom_start_position'] ?? 'top top';
                    $settings['layered_end_position'] = $settings['layered_end_position'] ?? 'bottom top';
                    $settings['layered_custom_end_position'] = $settings['layered_custom_end_position'] ?? 'bottom top';
                    $settings['layered_pin_element'] = $settings['layered_pin_element'] ?? 'yes';
                    $settings['hide_layered_on_mobile'] = $settings['hide_layered_on_mobile'] ?? 'yes';
                    $settings['hide_layered_mobile_resolution'] = $settings['hide_layered_mobile_resolution'] ?? 768;
                    $settings['add_layered_animate_scroll'] = $settings['add_layered_animate_scroll'] ?? 'none';
                    $settings['layered_fade_start_opacity'] = $settings['layered_fade_start_opacity'] ?? ['size' => 0];
                    $settings['layered_fade_end_opacity'] = $settings['layered_fade_end_opacity'] ?? ['size' => 1];
                    $settings['layered_zoom_start_scale'] = $settings['layered_zoom_start_scale'] ?? ['size' => 1];
                    $settings['layered_zoom_end_scale'] = $settings['layered_zoom_end_scale'] ?? ['size' => 0.9];
                    $settings['layered_move_rotate_x'] = $settings['layered_move_rotate_x'] ?? ['size' => -45];
                    $settings['layered_move_rotate_y'] = $settings['layered_move_rotate_y'] ?? ['size' => 0];
                    $settings['layered_move_perspective'] = $settings['layered_move_perspective'] ?? 1000;
                    $settings['layered_css_filter_start_blur'] = $settings['layered_css_filter_start_blur'] ?? '';
                    $settings['layered_css_filter_start_brightness'] = $settings['layered_css_filter_start_brightness'] ?? '';
                    $settings['layered_css_filter_start_contrast'] = $settings['layered_css_filter_start_contrast'] ?? '';
                    $settings['layered_css_filter_start_saturate'] = $settings['layered_css_filter_start_saturate'] ?? '';
                    $settings['layered_css_filter_start_hue'] = $settings['layered_css_filter_start_hue'] ?? '';
                    $settings['layered_css_filter_end_blur'] = $settings['layered_css_filter_end_blur'] ?? '';
                    $settings['layered_css_filter_end_brightness'] = $settings['layered_css_filter_end_brightness'] ?? '';
                    $settings['layered_css_filter_end_contrast'] = $settings['layered_css_filter_end_contrast'] ?? '';
                    $settings['layered_css_filter_end_saturate'] = $settings['layered_css_filter_end_saturate'] ?? '';
                    $settings['layered_css_filter_end_hue'] = $settings['layered_css_filter_end_hue'] ?? '';
                    wp_enqueue_script('gsap', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/gsap/gsap-core.min.js', ['jquery'], '3.13.0', true);
                    wp_enqueue_script( 'gsap-scroll-trigger', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/gsap/ScrollTrigger.min.js', ['jquery','gsap'], '3.13.0', true);
                }

                if (isset($settings['apply_sticky_column']) && !empty($settings['apply_sticky_column'])) {
                    wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js', ['jquery']);
                }
        
                if (!empty($settings)) {
                    $this->sections[$setting_id] = $settings;
                }
            }
        }
		return $data;
	}

	public static function add_controls_section($element, $section_id, $args) {
		if ($section_id == 'section_custom_css_pro') {

			$element->remove_control('section_custom_css_pro');
			$element->start_controls_section(
				'section_custom_css',
				[
					'label' => esc_html__( 'WGL Custom CSS', 'wgl-extensions' ),
					'tab' => Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_control(
				'custom_css_title',
				[
					'raw' => esc_html__( 'Add your own custom CSS here', 'wgl-extensions' ),
					'type' => Controls_Manager::RAW_HTML,
				]
			);

			$element->add_control(
				'custom_css',
				[
					'type' => Controls_Manager::CODE,
					'label' => esc_html__( 'Custom CSS', 'wgl-extensions' ),
					'language' => 'css',
					'render_type' => 'ui',
					'show_label' => false,
					'separator' => 'none',
				]
			);

			$element->add_control(
				'custom_css_description',
				[
					'raw' => 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wgl-elementor-descriptor',
				]
			);

			$element->end_controls_section();
		}
	}

	public function add_post_css($post_css, $element) {
		if ($post_css instanceof Dynamic_CSS) {
			return;
		}

		$element_settings = $element->get_settings();

		if (empty($element_settings['custom_css'])) {
			return;
		}

		$css = trim($element_settings['custom_css']);

		if (empty($css)) {
			return;
		}
		$css = str_replace('selector', $post_css->get_element_unique_selector($element), $css);

		// Add a css comment
		$css = sprintf('/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector()) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css($css);
	}

	public function add_page_settings_css( $post_css ) {
		$document = \Elementor\Plugin::$instance->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'custom_css' );
		$custom_css = !empty($custom_css) ? trim( $custom_css ) : '';

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

    public function inject_options_post($document)
    {
        if ('header' === get_post_type()) {
            $this->get_header_controls($document);
        } elseif ('side_panel' === get_post_type()){
            $this->get_side_panel_controls($document);
        }
    }

    public function inject_options_page( $document )
    {
        $document->start_controls_section(
            'body_options',
            [
                'label' => esc_html__( 'WGL Main Content Options', 'wgl-extensions' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );

        $document->add_responsive_control(
            'main_content_margin',
            [
                'label' => esc_html__( 'Margin', 'wgl-extensions' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} #main.site-main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_responsive_control(
            'main_content_padding',
            [
                'label' => esc_html__( 'Padding', 'wgl-extensions' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} #main.site-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_responsive_control(
            'z_index',
            [
                'label' => esc_html__( 'Z-Index', 'wgl-extensions' ),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                'default' => '0',
                'selectors' => [
                    '{{WRAPPER}} #main.site-main' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $document->end_controls_section();
    }

    public function get_header_controls($document)
    {
        $document->start_controls_section(
            'header_options',
            [
                'label' => esc_html__('WGL Header Options', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );

        if('elementor' !== \WGL_Framework::get_option('mobile_header_building_tool')){
            $document->add_control(
                'use_custom_logo',
                [
                    'label' => esc_html__('Use Custom Mobile Logo?', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );
    
            $document->add_control(
                'custom_logo',
                [
                    'label' => esc_html__('Custom Logo', 'wgl-extensions'),
                    'type' => Controls_Manager::MEDIA,
                    'dynamic' => [  'active' => true],
                    'condition' => ['use_custom_logo' => 'yes'],
                    'label_block' => true,
                    'default' => ['url' => Utils::get_placeholder_image_src()],
                ]
            );
    
            $document->add_control(
                'enable_logo_height',
                [
                    'label' => esc_html__('Enable Logo Height?', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_custom_logo' => 'yes'],
                ]
            );
    
            $document->add_control(
                'logo_height',
                [
                    'label' => esc_html__('Logo Height', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_custom_logo' => 'yes',
                        'enable_logo_height' => 'yes',
                    ],
                    'min' => 1,
                ]
            );
        }

        if('elementor' !== \WGL_Framework::get_option('mobile_drawer_header_building_tool')){
            $document->add_control(
                'hr_mobile_logo',
                ['type' => Controls_Manager::DIVIDER ]
            );
    
            $document->add_control(
                'use_custom_menu_logo',
                [
                    'label' => esc_html__('Use Custom Mobile Menu Logo?', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );
    
            $document->add_control(
                'custom_menu_logo',
                [
                    'label' => esc_html__('Custom Logo', 'wgl-extensions'),
                    'type' => Controls_Manager::MEDIA,
                    'dynamic' => [  'active' => true],
                    'condition' => ['use_custom_menu_logo' => 'yes'],
                    'label_block' => true,
                    'default' => ['url' => Utils::get_placeholder_image_src()],
                ]
            );
    
            $document->add_control(
                'enable_menu_logo_height',
                [
                    'label' => esc_html__('Enable Logo Height?', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_custom_menu_logo' => 'yes'],
                ]
            );
    
            $document->add_control(
                'logo_menu_height',
                [
                    'label' => esc_html__('Logo Height', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_custom_menu_logo' => 'yes',
                        'enable_menu_logo_height' => 'yes',
                    ],
                    'min' => 1,
                ]
            );
        }

        if('elementor' !== \WGL_Framework::get_option('mobile_header_building_tool')){
            $document->add_control(
                'hr_mobile_menu_logo',
                ['type' => Controls_Manager::DIVIDER]
            );

            $document->add_control(
                'mobile_breakpoint',
                [
                    'label' => esc_html__('Mobile Header resolution breakpoint', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => [  'active' => true],
                    'min' => 5,
                    'max' => 1920,
                    'default' => 1200,
                ]
            );
        }

        $document->add_control(
            'header_on_bg',
            [
                'label' => esc_html__('Over content', 'wgl-extensions'),
                'description' => esc_html__('Set Header to display over content.', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $document->end_controls_section();

        if('elementor' === \WGL_Framework::get_option('mobile_header_building_tool')){
            $document->start_controls_section(
                'header_mobile_options',
                [
                    'label' => esc_html__('WGL Header Mobile Options', 'wgl-extensions'),
                    'tab' => Controls_Manager::TAB_SETTINGS
                ]
            );

            $document->add_control(
                'mobile_sticky',
                [
                    'label' => esc_html__('Mobile Sticky', 'wgl-extensions'),
                    'description' => esc_html__('Enable to make the header stick to the top on mobile devices.', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            $document->end_controls_section();
        }
    }

    public function get_side_panel_controls($document)
    {
        $document->start_controls_section(
            'settings_side_panel_options',
            [
                'label' => esc_html__('WGL Side Panel Options', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $document->add_control(
			'sp_container_heading',
			[
				'label' => esc_html__('Side Panel Container', 'wgl-extensions'),
				'type' => Controls_Manager::HEADING,
			]
        );

        $document->add_control(
            'sp_position',
            [
                'label' => esc_html__('Position', 'wgl-extensions'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wgl-extensions'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wgl-extensions'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'left: 0; right: auto;',
                    'right' => 'left: auto; right: 0;',
                ],
                'default' => 'right',
                'selectors' => [
                    '#side-panel.side-panel' => '{{VALUE}}',
                ],
            ]
        );

        $document->add_control(
            'sp_container_width',
            [
                'label' => esc_html__('Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                'min' => 100,
                'max' => 500,
                'selectors' => [
                    '#side-panel.side-panel' => 'width: {{VALUE}}px;',
                ],
            ]
        );

        $document->add_responsive_control(
            'sp_container_padding',
            [
                'label' => esc_html__('Padding', 'wgl-extensions'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => 50,
                    'left' => 50,
                    'right' => 50,
                    'bottom' => 50,
                ],
                'selectors' => [
                    '#side-panel.side-panel .side-panel_sidebar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_responsive_control(
            'sp_container_margin',
            [
                'label' => esc_html__('Margin', 'wgl-extensions'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '#side-panel.side-panel .side-panel_sidebar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_control(
            'sp_container_bg',
            [
                'label' => esc_html__('Background Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                'selectors' => [
                    '#side-panel.side-panel .side-panel_sidebar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $document->end_controls_section();
    }

    function ext_section_settings_elementor($settings) {

        $new_settings = $section_keys = [];

        // Blur
        if (!empty($settings['add_backdrop_filter'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_backdrop_filter',
                    'section_backdrop_filter',
                ]
            );
        }
        // Bg Text
        if (!empty($settings['add_background_text'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_background_text',
                    'background_text',
                    'background_text_typo',
                    'background_text_indent',
                    'background_text_spacing',
                    'background_text_stroke_size',
                    'background_text_color',
                    'background_text_stroke_color',
                    'apply_animation_background_text',
                    'background_text_gradient',
                    'bg_text_color_1',
                    'bg_text_color_2',
                    'bg_text_location_1',
                    'bg_text_location_2',
                    'bg_text_gradient_type',
                    'bg_text_gradient_angle',
                    'bg_text_gradient_position',
                    'background_text_index',
                ]
            );
        }
        // Bg Animations
        if (!empty($settings['add_background_animation'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_background_animation',
                    'image_effect',
                    'animation_name',
                    'animation_name_iteration_count',
                    'animation_name_speed',
                    'animation_name_direction',
                    'image_bg',
                    'parallax_dir',
                    'parallax_factor',
                    'position_top',
                    'position_left',
                    'position_rotate',
                    'parallax_opacity',
                    'parallax_h_alignment',
                    'v_alignment_back',
                    'parallax_image_radius',
                    'parallax_image_width',
                    'parallax_image_maxwidth',
                    'parallax_image_mask_color',
                    'image_index',
                    'hide_on_mobile',
                    'hide_mobile_resolution',
                    'items_parallax',
                ]
            );
        }
        // Section Parallax
        if (!empty($settings['add_section_parallax'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_section_parallax',
                    'section_parallax_dir',
                    'section_parallax_factor',
                    'section_parallax_factor_tablet',
                    'section_parallax_factor_mobile',
                ]
            );
        }
        // Shape Divider Top
        if (!empty($settings['wgl_shape_divider_top'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'wgl_shape_divider_top',
                    'wgl_shape_divider_top_color',
                    'wgl_shape_divider_top_offset',
                    'wgl_shape_divider_top_height',
                    'wgl_shape_divider_top_flip',
                    'wgl_shape_divider_top_invert',
                    'wgl_shape_divider_top_above_content',
                ]
            );
        }
        // Shape Divider Bottom
        if (!empty($settings['wgl_shape_divider_bottom'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'wgl_shape_divider_bottom',
                    'wgl_shape_divider_bottom_color',
                    'wgl_shape_divider_bottom_offset',
                    'wgl_shape_divider_bottom_height',
                    'wgl_shape_divider_bottom_flip',
                    'wgl_shape_divider_bottom_invert',
                    'wgl_shape_divider_bottom_above_content',
                ]
            );
        }
        // Particles
        if (!empty($settings['add_particles_animation'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_particles_animation',
                    'particles_effect',
                    'particles_color_one',
                    'particles_color_second',
                    'particles_color_third',
                    'particles_count',
                    'particles_max_size',
                    'particles_speed',
                    'particles_line',
                    'particles_hover_animation',
                    'position_particles_top',
                    'position_particles_left',
                    'particles_width',
                    'particles_height',
                    'hide_particles_on_mobile',
                    'hide_particles_mobile_resolution',
                    'items_particles',
                ]
            );
        }
        // Particles Image
        if (!empty($settings['add_particles_img_animation'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_particles_img_animation',
                    'particles_image',
                    'particles_img_width',
                    'particles_img_height',
                    'items_particles_img',
                    'particles_img_color',
                    'particles_img_max_size',
                    'particles_img_count',
                    'particles_img_speed',
                    'particles_img_line',
                    'particles_img_rotate',
                    'particles_img_rotate_speed',
                    'particles_img_hover_animation',
                    'position_particles_img_top',
                    'position_particles_img_left',
                    'particles_img_container_width',
                    'particles_img_container_height',
                    'hide_particles_img_on_mobile',
                    'hide_particles_img_mobile_resolution',
                ]
            );
        }
        // Dynamic Highlights
        if (!empty($settings['add_dynamic_highlights_animation'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_dynamic_highlights_animation',
                    'dynamic_highlights_color_first',
                    'dynamic_highlights_color_second',
                    'position_dynamic_highlights_top',
                    'position_dynamic_highlights_left',
                    'dynamic_highlights_width',
                    'hide_dynamic_highlights_on_mobile',
                    'hide_dynamic_highlights_mobile_resolution',
                    'dynamic_highlights_index',
                    'items_dynamic_highlights',
                ]
            );
        }
        // Hover Highlights
        if (!empty($settings['add_hover_highlights_animation'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_hover_highlights_animation',
                    'hover_highlights_color_first',
                    'hover_highlights_color_second',
                    'position_hover_highlights_top',
                    'position_hover_highlights_left',
                    'hover_highlights_width',
                    'hide_hover_highlights_on_mobile',
                    'hide_hover_highlights_mobile_resolution',
                    'hover_highlights_index',
                    'items_hover_highlights',
                ]
            );
        }
        // Morph Animation
        if (!empty($settings['add_morph_animation'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_morph_animation',
                    'morph_style',
                    'morph_transform',
                    'morph_color',
                    'morph_animation_speed',
                    'position_morph_top',
                    'position_morph_left',
                    'morph_size',
                    'hide_morph_on_mobile',
                    'hide_morph_mobile_resolution',
                    'morph_index',
                    'items_morph',
                ]
            );
        }
        // Wave
        if (!empty($settings['add_wave'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_wave',
                    'wave_dots_color',
                    'wave_bg_color',
                    'wave_opacity',
                    'wave_dots_max_x',
                    'wave_dots_max_y',
                    'wave_mouse_manipulation',
                    'wave_hide_on_mobile',
                    'wave_hide_mobile_resolution',
                    'wave_index',
                ]
            );
        }
        // Fade
        if (!empty($settings['wgl_sibling_fade_elements'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'wgl_sibling_fade_elements',
                    'wgl_sibling_elements_fade',
                    'wgl_sibling_elements_fade_transition',
                ]
            );
        }
        // Opacity
        $mod = (bool)apply_filters('wgl_opacity_by_gradient_newest', false);

        if (!empty($settings['extended_opacity_by_gradient_init']) && $mod) {
            $section_keys[] = 'extended_opacity_by_gradient_init';
        }
        if (!empty($settings['use_extended_mod_opacity_by_gradient_init']) && !$mod) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'use_extended_mod_opacity_by_gradient_init',
                    'extended_mod_gradient_opacity_1',
                    'extended_mod_gradient_location_1',
                    'extended_mod_gradient_opacity_2',
                    'extended_mod_gradient_location_2',
                    'extended_mod_gradient_type',
                    'extended_mod_gradient_angle',
                    'extended_mod_gradient_position',
                ]
            );
        }

        // WGL Animate on Scroll
        if (!empty($settings['add_extended_animate_scroll'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'add_extended_animate_scroll',
                    'extended_animate_scroll_opacity',
                    'extended_animate_scroll_rotate',
                    'extended_animate_scroll_perspective',
                    'extended_animate_scroll_transition',
                ]
            );
        }
        // Sticky
        if (!empty($settings['apply_sticky_row'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'apply_sticky_row'
                ]
            );
        }
        // Sticky
        if (!empty($settings['apply_sticky_column'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'apply_sticky_column'
                ]
            );
        }
        // Order
        if (!empty($settings['extend_column_order'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'extend_column_order'
                ]
            );
        }
        // Order
        if (!empty($settings['wgl_sibling_widgets_fade'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'wgl_sibling_widgets_fade'
                ]
            );
        }
        // Layered
        if (!empty($settings['enable_layered'])) {
            $section_keys = array_merge(
                $section_keys,
                [
                    'enable_layered',
                    'layered_start',
                    'custom_layered_class',
                    'layered_end',
                    'layered_start_position',
                    'layered_custom_start_position',
                    'layered_end_position',
                    'layered_custom_end_position',
                    'layered_pin_element',
                    'hide_layered_on_mobile',
                    'hide_layered_mobile_resolution',
                    'add_layered_animate_scroll',
                    'layered_css_filter_start_blur',
                    'layered_css_filter_start_brightness',
                    'layered_css_filter_start_contrast',
                    'layered_css_filter_start_saturate',
                    'layered_css_filter_start_hue',
                    'layered_css_filter_end_blur',
                    'layered_css_filter_end_brightness',
                    'layered_css_filter_end_contrast',
                    'layered_css_filter_end_saturate',
                    'layered_css_filter_end_hue',
                    'layered_fade_start_opacity',
                    'layered_fade_end_opacity',
                    'layered_zoom_start_scale',
                    'layered_zoom_end_scale',
                    'layered_move_rotate_x',
                    'layered_move_rotate_y',
                    'layered_move_perspective'
                ]
            );
        }


        $section_keys = array_merge(
            $section_keys,
            [
                'column_overflow',
            ]
        );

        foreach ($section_keys as $key) {
            if (array_key_exists($key, $settings)) {
                $new_settings[$key] = $settings[$key];
            }
        }

        return $new_settings;
    }

    public function extended_row_render(\Elementor\Element_Base $element)
    {
        if (
            'section' !== $element->get_name()
            && 'container' !== $element->get_name()
        ) {
            return;
        }

        $settings = $element->get_settings();
        $data = $element->get_data();

        $settings = $this->ext_section_settings_elementor($settings);

        // Background Text Extensions
        if (!empty($settings['add_background_text'])) {
            wp_enqueue_script('jquery-appear', esc_url(get_template_directory_uri() . '/js/jquery.appear.js'));
            wp_enqueue_script('anime', esc_url(get_template_directory_uri() . '/js/anime.min.js'));
        }

        // 3D Wave Extensions
        if (isset($settings['add_wave'])
            && !empty($settings['add_wave'])) {
            wp_enqueue_script('three', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/three.min.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('projector', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/projector.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('canvas-renderer', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/canvas.renderer.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('stats', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/stats.min.js'), [],wp_get_theme()->get('Version') ?? false);
        }

        // Parallax Extensions
        if (
            isset($settings['add_background_animation'])
            && !empty($settings['add_background_animation'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()
        ) {
            $scroll_animation = $mouse_animation = $css_animation = false;

            foreach ($settings['items_parallax'] as $k => $v) {
                if('css_animation' === $v['image_effect']){
                    $css_animation = true;
                }elseif('mouse' === $v['image_effect']){
                    $mouse_animation = true;
                }elseif('scroll' === $v['image_effect']){
                    $scroll_animation = true;
                }
            }

            if($css_animation){
                wp_enqueue_style('animate', esc_url(get_template_directory_uri() . '/css/animate.css'), array('e-animations'));
            }

            if($mouse_animation){
                wp_enqueue_script('parallax', esc_url(get_template_directory_uri() . '/js/parallax.min.js'));
            }

            if($scroll_animation){
                wp_enqueue_script('jquery-paroller', esc_url(get_template_directory_uri() . '/js/jquery.paroller.min.js'));
            }
        }

        // Section Parallax
        if (
            isset($settings['add_section_parallax'])
            && !empty($settings['add_section_parallax'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()
        ) {
            wp_enqueue_script('jquery-paroller', esc_url(get_template_directory_uri() . '/js/jquery.paroller.min.js'));
        }

        // Particles Extensions
        if (
            !empty($settings['add_particles_animation'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()
        ) {
            wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', array('jquery'), false, true);
        }

        // Particles Img Extensions
        if (
            !empty($settings['add_particles_img_animation'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()
        ) {
            wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', array('jquery'), false, true);
        }

        if (isset($settings['enable_layered'])
            && !empty($settings['enable_layered'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()) {
            wp_enqueue_script('gsap', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/gsap/gsap-core.min.js', ['jquery'], '3.13.0', true);
            wp_enqueue_script( 'gsap-scroll-trigger', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/gsap/ScrollTrigger.min.js', ['jquery','gsap'], '3.13.0', true);
        }

        if (!empty($settings)) {
            $this->sections[$data['id']] = $settings;
        }
    }

    public function extended_column_render(\Elementor\Element_Base $element)
    {
        if (
            'column' !== $element->get_name()
            && 'container' !== $element->get_name())
        {
            return;
        }

        $settings = $element->get_settings();
        $data     = $element->get_data();

        if (isset($settings['apply_sticky_column']) && !empty($settings['apply_sticky_column'])) {

            wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js', ['jquery']);
        }
    }

    public function enqueue_scripts()
    {
        if (Plugin::$instance->preview->is_preview_mode()) {
            wp_enqueue_style('animate', esc_url(get_template_directory_uri() . '/css/animate.css'), array('e-animations'));

            wp_enqueue_script('parallax', esc_url(get_template_directory_uri() . '/js/parallax.min.js'));
            wp_enqueue_script('jquery-paroller', esc_url(get_template_directory_uri() . '/js/jquery.paroller.min.js'));

            wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js', ['jquery']);

            wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', ['jquery'], false, true);

            // 3D Wave Extensions
            wp_enqueue_script('three', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/three.min.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('projector', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/projector.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('canvas-renderer', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/canvas.renderer.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('stats', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/stats.min.js'), [],wp_get_theme()->get('Version') ?? false);
        }

        // Add options in the section
        wp_enqueue_script('wgl-parallax', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/wgl_elementor_sections.js'), ['jquery'], false, true);

        wp_localize_script('wgl-parallax', 'wgl_parallax_settings', [
            $this->sections,
            'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
            'svgURL' => esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/shapes/'),
            'elementorPro' => class_exists('\ElementorPro\Plugin'),
        ]);
    }

    public function extended_blur_options($widget, $args)
    {
        /**
         * WGL Blur
         */

        $widget->start_controls_section(
            'extended_filter',
            [
                'label' => esc_html__('WGL Blur', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_backdrop_filter',
            [
                'label' => esc_html__('Add Backdrop Blur?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'add-backdrop-filter',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->add_control(
            'section_backdrop_filter',
            [
                'label' => esc_html__('Backdrop Blur', 'wgl-extensions'),
                'description' => esc_html__('Backdrop Blur may not work in the Elementor editor. Check the effect on the live site.', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-backdrop-filter::after' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
                'condition' => ['add_backdrop_filter!' => ''],
            ]
        );

        $widget->end_controls_section();
    }

    public function extended_animation_options($widget, $args)
    {
        $widget->start_controls_section(
            'extended_layered',
            [
                'label' => esc_html__('WGL Layered Element', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'enable_layered',
            [
                'label'              => esc_html__('Add Layered', 'wgl-extensions'),
                'type'               => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'render_type'        => 'none',
                'return_value'       => 'yes',
            ]
        );

        $widget->add_control(
            'layered_start',
            [
                'label'       => esc_html__('Layered Wrapper', 'wgl-extensions'),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => [
                    ''       => esc_html__('Default', 'wgl-extensions'),
                    'custom' => esc_html__('Custom', 'wgl-extensions'),
                ],
                'condition'   => [ 'enable_layered!' => '' ],
                'render_type' => 'none',
            ]
        );

        $widget->add_control(
            'custom_layered_class',
            [
                'label'              => esc_html__('Custom Layered Class', 'wgl-extensions'),
                'type'               => Controls_Manager::TEXT,
                'ai'                 => false,
                'placeholder'        => esc_html__('.custom_layered_class', 'wgl-extensions'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'condition'          => [
                    'layered_start' => 'custom',
                    'enable_layered!' => '',
                ],
                'prefix_class' => 'wgl-layered-',
            ]
        );

        $widget->add_control(
            'layered_end',
            [
                'label'              => esc_html__('End Trigger', 'wgl-extensions'),
                'type'               => Controls_Manager::TEXT,
                'ai'                 => false,
                'placeholder'        => esc_html__('.end_layered', 'wgl-extensions'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'condition'          => [
                    'enable_layered!' => '',
                ]
            ]
        );

        $widget->add_control(
            'add_layered_animate_scroll',
            [
                'label'              => esc_html__( 'Scroll Animation', 'wgl-extensions' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'none',
                'separator'          => 'before',
                'options'            => [
                    'none' => esc_html__( 'None', 'wgl-extensions' ),
                    'fade' => esc_html__( 'Fade Effect', 'wgl-extensions' ),
                    'move' => esc_html__( '3D Move Effect', 'wgl-extensions' ),
                    'zoom' => esc_html__( 'Zoom', 'wgl-extensions' ),
                ],
                'render_type'        => 'none',
                'frontend_available' => true,
                'condition'          => [
                    'enable_layered!' => '',
                ]
            ]
        );

        $widget->add_control(
            'layered_css_filter_start_label',
            [
                'label' => __( 'Start Scroll CSS Filter', 'wgl-extensions' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'condition' => [
                    'add_layered_animate_scroll!' => 'none',
                    'enable_layered!' => '',
                ],
                'separator' => 'before'
            ]
        );

        $widget->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'layered_css_filter_start',
                'frontend_available' => true,
                'render_type'        => 'none',
                'condition' => [
                    'add_layered_animate_scroll!' => 'none',
                    'enable_layered!' => '',
                ],   
                'selector' => 'body:not(.elementor-editor-active) {{WRAPPER}}.wgl-layered-{{custom_layered_class}}_layered-defaut-filter, body:not(.elementor-editor-active) {{WRAPPER}}_layered-default-filter:not([class*="wgl-layered-"])',
            ]
        );

        $widget->add_control(
            'layered_css_filter_end_label',
            [
                'label' => __( 'End Scroll CSS Filter', 'wgl-extensions' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'condition' => [
                    'add_layered_animate_scroll!' => 'none',
                    'enable_layered!' => '',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'layered_css_filter_end',
                'frontend_available' => true,
                'render_type'        => 'none',
                'condition' => [
                    'add_layered_animate_scroll!' => 'none',
                    'enable_layered!' => '',
                ],
                'selector' => 'body:not(.elementor-editor-active) {{WRAPPER}}.wgl-layered-{{custom_layered_class}}_layered-defaut-filter, body:not(.elementor-editor-active) {{WRAPPER}}_layered-default-filter:not([class*="wgl-layered-"])',
            ]
        );

        $widget->add_control(
            'layered_fade_start_opacity',
            [
                'label'     => esc_html__( 'Start Opacity', 'wgl-extensions' ),
                'type'      => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step'=> 0.1,
                    ],
                ],
                'default'   => [
                    'size' => 0,
                ],
                'condition' => [
                    'add_layered_animate_scroll!' => 'none',
                    'enable_layered!' => '',
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
                'selectors' => [
                    'body:not(.elementor-editor-active) {{WRAPPER}}.wgl-layered-{{custom_layered_class}}' => '--layered-default-opacity: {{SIZE}};',
                    'body:not(.elementor-editor-active) {{WRAPPER}}:not([class*="wgl-layered-"])' => '--layered-default-opacity: {{SIZE}};',
                ],
                 'separator' => 'before'
            ]
        );

        $widget->add_control(
            'layered_fade_end_opacity',
            [
                'label'     => esc_html__( 'End Opacity', 'wgl-extensions' ),
                'type'      => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step'=> 0.1,
                    ],
                ],
                'default'   => [
                    'size' => 1,
                ],
                'condition' => [
                    'add_layered_animate_scroll!' => 'none',
                    'enable_layered!' => '',
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
            ]
        );

        $widget->add_control(
            'layered_zoom_start_scale',
            [
                'label'     => esc_html__( 'Zoom Start', 'wgl-extensions' ),
                'type'      => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step'=> 0.01,
                    ],
                ],
                'default'   => [
                    'size' => 1,
                ],
                'condition' => [
                    'add_layered_animate_scroll' => 'zoom',
                    'enable_layered!' => '',
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
                 'separator' => 'before'
            ]
        );

        $widget->add_control(
            'layered_zoom_end_scale',
            [
                'label'     => esc_html__( 'Zoom End', 'wgl-extensions' ),
                'type'      => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step'=> 0.01,
                    ],
                ],
                'default'   => [
                    'size' => 0.9,
                ],
                'condition' => [
                    'add_layered_animate_scroll' => 'zoom',
                    'enable_layered!' => '',
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
            ]
        );


        $widget->add_control(
            'layered_move_rotate_x',
            [
                'label'     => esc_html__( 'Rotate X (deg)', 'wgl-extensions' ),
                'type'      => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range'     => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                        'step'=> 5,
                    ],
                ],
                'default'   => [
                    'size' => -45,
                ],
                'condition' => [
                    'add_layered_animate_scroll' => 'move',
                    'enable_layered!' => '',
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
                 'separator' => 'before'
            ]
        );

        $widget->add_control(
            'layered_move_rotate_y',
            [
                'label'     => esc_html__( 'Rotate Y (deg)', 'wgl-extensions' ),
                'type'      => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range'     => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                        'step'=> 5,
                    ],
                ],
                'default'   => [
                    'size' => 0,
                ],
                'condition' => [
                    'add_layered_animate_scroll' => 'move',
                    'enable_layered!' => '',
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
            ]
        );

        $widget->add_control(
            'layered_move_perspective',
            [
                'label'     => esc_html__( 'Perspective', 'wgl-extensions' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 1000,
                'condition' => [
                    'add_layered_animate_scroll' => 'move',
                    'enable_layered!' => '',
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
            ]
        );

        $widget->add_control(
            'layered_start_position',
            [
                'label'              => esc_html__('Start', 'wgl-extensions'),
                'description'        => esc_html__('The first value sets the element’s original placement in the document. The second value controls how and where that element is displayed on screen during scrolling.', 'wgl-extensions'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'default'            => 'top top',
                'frontend_available' => true,
                'options'            => [
                    'top top'       => esc_html__('Top on Top', 'wgl-extensions'),
                    'top center'    => esc_html__('Top on Center', 'wgl-extensions'),
                    'top bottom'    => esc_html__('Top on Bottom', 'wgl-extensions'),
                    'center top'    => esc_html__('Center on Top', 'wgl-extensions'),
                    'center center' => esc_html__('Center on Center', 'wgl-extensions'),
                    'center bottom' => esc_html__('Center on Bottom', 'wgl-extensions'),
                    'bottom top'    => esc_html__('Bottom on Top', 'wgl-extensions'),
                    'bottom center' => esc_html__('Bottom on Center', 'wgl-extensions'),
                    'bottom bottom' => esc_html__('Bottom on Bottom', 'wgl-extensions'),
                    'custom'        => esc_html__('custom', 'wgl-extensions'),
                ],
                'render_type'        => 'none',
                'condition'          => [ 'enable_layered!' => '' ],

            ]
        );

        $widget->add_control(
            'layered_custom_start_position',
            [
                'label'              => esc_html__('Custom', 'wgl-extensions'),
                'type'               => Controls_Manager::TEXT,
                'default'            => esc_html__('top top', 'wgl-extensions'),
                'description'        => esc_html__('You can write like: top top+=100', 'wgl-extensions'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'ai'                 => false,
                'condition'          => [
                    'enable_layered!' => '',
                    'layered_start_position'   => 'custom',
                ],
            ]
        );

        $widget->add_control(
            'layered_end_position',
            [
                'label'              => esc_html__('End', 'wgl-extensions'),
                'description'        => esc_html__('The first value sets the element’s original placement in the document. The second value controls how and where that element is displayed on screen during scrolling.', 'wgl-extensions'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'default'            => 'bottom top',
                'frontend_available' => true,
                'render_type'        => 'none',
                'options'            => [
                    'top top'       => esc_html__('Top on Top', 'wgl-extensions'),
                    'top center'    => esc_html__('Top on Center', 'wgl-extensions'),
                    'top bottom'    => esc_html__('Top on Bottom', 'wgl-extensions'),
                    'center top'    => esc_html__('Center on Top', 'wgl-extensions'),
                    'center center' => esc_html__('Center on Center', 'wgl-extensions'),
                    'center bottom' => esc_html__('Center on Bottom', 'wgl-extensions'),
                    'bottom top'    => esc_html__('Bottom on Top', 'wgl-extensions'),
                    'bottom center' => esc_html__('Bottom on Center', 'wgl-extensions'),
                    'bottom bottom' => esc_html__('Bottom on Bottom', 'wgl-extensions'),
                    'custom'        => esc_html__('custom', 'wgl-extensions'),
                ],
                'condition'          => [ 'enable_layered!' => '' ],
            ]
        );

        $widget->add_control(
            'layered_custom_end_position',
            [
                'label'              => esc_html__('Custom', 'wgl-extensions'),
                'type'               => Controls_Manager::TEXT,
                'frontend_available' => true,
                'render_type'        => 'none',
                'ai'                 => false,
                'default'            => esc_html__('bottom top', 'wgl-extensions'),
                'description'        => esc_html__('You can write like: bottom top+=100', 'wgl-extensions'),
                'condition'          => [
                    'enable_layered!' => '',
                    'layered_end_position'     => 'custom',
                ],
            ]
        );

        $widget->add_control(
            'layered_pin_element',
            [
                'label' => esc_html__('Pin Element?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
                'default'   => 'yes',
                'separator'          => 'before',
                'condition'          => [ 'enable_layered!' => '' ],
            ]
        );

        $widget->add_control(
            'hide_layered_on_mobile',
            [
                'label' => esc_html__('Disable On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
                'default'   => 'yes',
                'separator'          => 'before',
                'condition'          => [ 'enable_layered!' => '' ],
            ]
        );

        $widget->add_control(
            'hide_layered_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['hide_layered_on_mobile' => 'yes' , 'enable_layered!' => '' ],
                'default' => 768,
            ]
        );


        $widget->end_controls_section();
        /**
         * BACKGROUND TEXT
         */

        $widget->start_controls_section(
            'extended_animation',
            [
                'label' => esc_html__('WGL Background Text', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_background_text',
            [
                'label' => esc_html__('Add Background Text?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'add-background-text',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->add_control(
            'background_text',
            [
                'label' => esc_html__('Background Text', 'wgl-extensions'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [  'active' => true],
                'condition' => ['add_background_text!' => ''],
                'label_block' => true,
                'default' => esc_html__('Text', 'wgl-extensions'),
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'content: "{{VALUE}}"',
                    '{{WRAPPER}} .wgl-background-text' => 'content: "{{VALUE}}"',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'background_text_typo',
                'condition' => ['add_background_text' => 'add-background-text'],
                'selector' => '{{WRAPPER}}.wgl-add-background-text:before, {{WRAPPER}} .wgl-background-text',
            ]
        );

        $widget->add_responsive_control(
            'background_text_indent',
            [
                'label' => esc_html__('Text Indent', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => ['add_background_text!' => ''],
                'size_units' => ['px', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => -1000, 'max' => 1000],
                    'vw' => ['min' => -100, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .wgl-background-text' => 'transform: translateX(calc({{SIZE}}{{UNIT}} / 2));',
                ],
            ]
        );

        $widget->add_responsive_control(
            'background_text_spacing',
            [
                'label' => esc_html__('Top Spacing', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => ['add_background_text!' => ''],
                'range' => [
                    'px' => ['min' => -100, 'max' => 400],
                    'em' => ['min' => -10, 'max' => 20],
                    '%' => ['min' => -100, 'max' => 200],
                    'vw' => ['min' => -100, 'max' => 200],
                ],
                'size_units' => ['px', 'em', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-background-text' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_responsive_control(
            'background_text_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.1]],
                'condition' => ['add_background_text!' => ''],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before,
				     {{WRAPPER}} .wgl-background-text' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_control(
            'background_text_color',
            [
                'label' => esc_html__('Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'condition' => ['add_background_text!' => ''],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-background-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'background_text_stroke_color',
            [
                'label' => esc_html__('Stroke Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'condition' => [
                    'add_background_text!' => '',
                    'background_text_stroke_size[size]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before,
				     {{WRAPPER}} .wgl-background-text' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );

        $widget->add_control(
            'apply_animation_background_text',
            [
                'label' => esc_html__('Apply Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => ['add_background_text!' => ''],
                'return_value' => 'animation-background-text',
                'default' => 'animation-background-text',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->add_control(
            'background_text_gradient',
            [
                'label' => esc_html__('Gradient Text', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                ],
                'label_on' => esc_html__('Yes', 'wgl-extensions'),
                'label_off' => esc_html__('No', 'wgl-extensions'),
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before,
				     {{WRAPPER}} .wgl-background-text' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );

        $widget->add_control(
            'bg_text_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'default' => WGL_Globals::get_primary_color(1),
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-color-1: {{VALUE}}',
                ],
            ]
        );

        $widget->add_control(
            'bg_text_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'default' => WGL_Globals::get_primary_color(0),
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-color-2: {{VALUE}}',
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_location_1',
            [
                'label' => esc_html__('First Color Location', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => [ '%' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-location-1: {{SIZE}}%',
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_location_2',
            [
                'label' => esc_html__('Second Color Location', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => [ '%' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-location-2: {{SIZE}}%',
                ],
            ]
        );

        $widget->add_control(
            'bg_text_gradient_type',
            [
                'label' => esc_html__('Type', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'wgl-extensions' ),
                    'radial' => esc_html__( 'Radial', 'wgl-extensions' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_gradient_angle',
            [
                'label' => esc_html__('Angle', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 180 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => '',
                    'bg_text_gradient_type' => 'linear',
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--bg-text-color-1) var(--bg-text-location-1), var(--bg-text-color-2) var(--bg-text-location-2));',
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_gradient_position',
            [
                'label' => esc_html__('Position', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'wgl-extensions' ),
                    'center left' => esc_html__( 'Center Left', 'wgl-extensions' ),
                    'center right' => esc_html__( 'Center Right', 'wgl-extensions' ),
                    'top center' => esc_html__( 'Top Center', 'wgl-extensions' ),
                    'top left' => esc_html__( 'Top Left', 'wgl-extensions' ),
                    'top right' => esc_html__( 'Top Right', 'wgl-extensions' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'wgl-extensions' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'wgl-extensions' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'wgl-extensions' ),
                ],
                'default' => 'center center',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => '',
                    'bg_text_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--bg-text-color-1) var(--bg-text-location-1), var(--bg-text-color-2) var(--bg-text-location-2));',
                ],
            ]
        );

        $widget->add_control(
            'background_text_index',
            [
                'label' => esc_html__('Z-Index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => 0,
                'separator' => 'before',
                'condition' => ['add_background_text!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-background-text,
                     {{WRAPPER}}.wgl-add-background-text:before' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $widget->end_controls_section();

        /**
         * PARALLAX
         */

        $widget->start_controls_section(
            'extended_parallax',
            [
                'label' => esc_html__('WGL Parallax', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_background_animation',
            [
                'label' => esc_html__('Add Extended Background Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image_effect',
            [
                'label' => esc_html__('Parallax Effect', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'scroll' => esc_html__('Scroll', 'wgl-extensions'),
                    'mouse' => esc_html__('Mouse', 'wgl-extensions'),
                    'css_animation' => esc_html__('CSS Animation', 'wgl-extensions'),
                ],
                'default' => 'scroll',
            ]
        );

        $repeater->add_responsive_control(
            'animation_name',
            [
                'label' => esc_html__( 'Animation', 'wgl-extensions'),
                'type' => Controls_Manager::ANIMATION,
                'render_type' => 'template',
                'frontend_available' => true,
                'condition' => ['image_effect' => 'css_animation'],
                'default' => 'fadeIn',
            ]
        );

        $repeater->add_control(
            'animation_name_iteration_count',
            [
                'label' => esc_html__('Animation Iteration Count', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['image_effect' => 'css_animation'],
                'options' => [
                    'infinite' => esc_html__('Infinite', 'wgl-extensions'),
                    '1' => esc_html__('1', 'wgl-extensions'),
                ],
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'animation-iteration-count: {{VALUE}};'
                ],
            ]
        );

        $repeater->add_control(
            'animation_name_speed',
            [
                'label' => esc_html__('Animation speed', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['image_effect' => 'css_animation'],
                'min' => 0.1,
                'step' => 0.1,
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'animation-duration: {{VALUE}}s;'
                ],
            ]
        );

        $repeater->add_control(
            'animation_name_direction',
            [
                'label' => esc_html__('Animation Direction', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['image_effect' => 'css_animation'],
                'options' => [
                    'normal' => esc_html__('Normal', 'wgl-extensions'),
                    'reverse' => esc_html__('Reverse', 'wgl-extensions'),
                    'alternate' => esc_html__('Alternate', 'wgl-extensions'),
                ],
                'default' => 'normal',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'animation-direction: {{VALUE}};'
                ],
            ]
        );

        $repeater->add_control(
            'image_bg',
            [
                'label' => esc_html__('Parallax Image', 'wgl-extensions'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [  'active' => true],
                'label_block' => true,
                'default' => ['url' => ''],
            ]
        );

        $repeater->add_control(
            'parallax_dir',
            [
                'label' => esc_html__('Parallax Direction', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['image_effect' => 'scroll'],
                'options' => [
                    'vertical' => esc_html__('Vertical', 'wgl-extensions'),
                    'horizontal' => esc_html__('Horizontal', 'wgl-extensions'),
                ],
                'default' => 'vertical',
            ]
        );

        $repeater->add_control(
            'parallax_factor',
            [
                'label' => esc_html__('Parallax Factor', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set elements offset and speed. It can be positive (0.3) or negative (-0.3). Less means slower.', 'wgl-extensions'),
                'min' => -3,
                'max' => 3,
                'step' => 0.01,
                'default' => 0.03,
            ]
        );

        $repeater->add_responsive_control(
            'position_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set figure vertical offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -1000, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set figure horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -1000, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',

                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_rotate',
            [
                'label' => esc_html__('Rotate Image', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['max' => 360],
                    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $repeater->add_control(
            'parallax_opacity',
            [
                'label' => esc_html__( 'Opacity', 'wgl-extensions' ),
                'description' => esc_html__('Set figure opacity from 0 to 1.', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'range' => [
                    'px' => [ 'min' => 0.10, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'filter: opacity({{SIZE}})',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'parallax_h_alignment',
            [
                'label' => esc_html__('Horizontal Alignment', 'wgl-extensions'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'wgl-extensions'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wgl-extensions'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'wgl-extensions'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'display: flex; justify-content: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'v_alignment_back',
            [
                'label' => esc_html__( 'Vertical Alignment', 'wgl-extensions' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'wgl-extensions'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wgl-extensions' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'wgl-extensions'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'min-height: 100%; display: flex; align-items: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'parallax_image_radius',
            [
                'label' => esc_html__( 'Border Radius', 'wgl-extensions' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'parallax_image_width',
            [
                'label' => esc_html__( 'Image Width', 'wgl-extensions' ),
                'description' => esc_html__('Set figure width.', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => ['%', 'vw', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => 1, 'max' => 100],
                    'px' => ['min' => 1, 'max' => 1920, 'step' => 1],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_control(
            'parallax_image_maxwidth',
            [
                'label' => esc_html__('Disable Max-Width 100% on Image?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'max-width: none',
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'parallax_image_filters',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} img',
            ]
        );

        $repeater->add_control(
            'parallax_image_mask_color',
            [
                'label' => esc_html__('Change Color for Image', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl_mask_image' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'visibility: hidden !important',
                ],
            ]
        );

        $repeater->add_control(
            'image_index',
            [
                'label' => esc_html__('Image z-index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => -1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'hide_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $repeater->add_control(
            'hide_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['hide_on_mobile' => 'yes'],
                'default' => 768,
            ]
        );

        $widget->add_control(
            'items_parallax',
            [
                'label' => esc_html__('Layers', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_background_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->end_controls_section();

        /**
         * SECTION PARALLAX
         */

        $widget->start_controls_section(
            'extended_section_parallax',
            [
                'label' => esc_html__('WGL Section Parallax', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_section_parallax',
            [
                'label' => esc_html__('Add Section Parallax?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $widget->add_control(
            'section_parallax_dir',
            [
                'label' => esc_html__('Parallax Direction', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['add_section_parallax' => 'yes'],
                'options' => [
                    'vertical' => esc_html__('Vertical', 'wgl-extensions'),
                    'horizontal' => esc_html__('Horizontal', 'wgl-extensions'),
                ],
                'default' => 'vertical',
            ]
        );

        $widget->add_responsive_control(
            'section_parallax_factor',
            [
                'label' => esc_html__('Parallax Factor', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [ 'active' => true ],
                'description' => esc_html__('Set elements offset and speed. It can be positive (0.3) or negative (-0.3). Less means slower.', 'wgl-extensions'),
                'min' => -3,
                'max' => 3,
                'step' => 0.01,
                'default' => 0.1,
                'condition' => ['add_section_parallax' => 'yes'],
            ]
        );
        $widget->end_controls_section();

        // Shape Divider
        $widget->start_controls_section(
            'extended_shape',
            [
                'label' => esc_html__('WGL Shape Divider', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->start_controls_tabs('tabs_wgl_shape_dividers');

        $shapes_options = [
            '' => esc_html__('None', 'wgl-extensions'),
            'torn_line' => esc_html__('Torn Line', 'wgl-extensions'),
            'gamer_line' => esc_html__('Gamer Line', 'wgl-extensions'),
            'gamer_line_inverted' => esc_html__('Gamer Line - Inverted', 'wgl-extensions'),
        ];

        foreach ([
                     'top' => esc_html__('Top', 'wgl-extensions'),
                     'bottom' => esc_html__('Bottom', 'wgl-extensions'),
                 ] as $side => $side_label) {
            $base_control_key = "wgl_shape_divider_$side";

            $widget->start_controls_tab(
                "tab_$base_control_key",
                [
                    'label' => $side_label,
                ]
            );

            $widget->add_control(
                $base_control_key,
                [
                    'label' => esc_html__('Type', 'wgl-extensions'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $shapes_options,
                ]
            );

            $widget->add_control(
                $base_control_key . '_color',
                [
                    'label' => esc_html__('Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => [  'active' => true],
                    'condition' => ["wgl_shape_divider_$side!" => ''],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side svg" => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $widget->add_responsive_control(
                $base_control_key . '_offset',
                [
                    'label' => esc_html__('Offset', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => [  'active' => true],
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'range' => [
                        'px' => ['min' => -50, 'max' => 50],
                    ],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side" => $side.': {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $widget->add_responsive_control(
                $base_control_key . '_height',
                [
                    'label' => esc_html__('Height', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => [  'active' => true],
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'range' => [
                        'px' => ['max' => 500],
                    ],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side svg" => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $widget->add_control(
                $base_control_key . '_flip',
                [
                    'label' => __('Flip', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side svg" => 'transform: translateX(-50%) rotateY(180deg)',
                    ],
                ]
            );

            $widget->add_control(
                $base_control_key . '_invert',
                [
                    'label' => __('Invert', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side" => 'transform: rotate(180deg);',
                    ],
                ]
            );

            $widget->add_control(
                $base_control_key . '_above_content',
                [
                    'label' => esc_html__('Z-index', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => [  'active' => true],
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'default' => 0,
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side" => 'z-index: {{UNIT}}',
                    ],
                ]
            );

            $widget->end_controls_tab();
        }

        $widget->end_controls_tabs();
        $widget->end_controls_section();

        $widget->start_controls_section(
            'extended_particles',
            [
                'label' => esc_html__('WGL Particles', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_particles_animation',
            [
                'label' => esc_html__('Add Particles Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'particles_effect',
            [
                'label' => esc_html__('Style: ', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'one_color' => esc_html__('One Color', 'wgl-extensions'),
                    'random_colors' => esc_html__('Random Colors', 'wgl-extensions'),
                ],
                'default' => 'one_color',
            ]
        );

        $repeater->add_control(
            'particles_color_one',
            [
                'label' => esc_html__('Color 1', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'default' => WGL_Globals::get_primary_color(),
            ]
        );

        $repeater->add_control(
            'particles_color_second',
            [
                'label' => esc_html__('Color 2', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'condition' => ['particles_effect' => 'random_colors'],
            ]
        );

        $repeater->add_control(
            'particles_color_third',
            [
                'label' => esc_html__('Color 3', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'condition' => ['particles_effect' => 'random_colors'],
            ]
        );

        $repeater->add_control(
            'particles_count',
            [
                'label' => esc_html__('Count Of Particles', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => 50,
            ]
        );

        $repeater->add_control(
            'particles_max_size',
            [
                'label' => esc_html__('Particles Max Size', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => 10,
            ]
        );

        $repeater->add_control(
            'particles_speed',
            [
                'label' => esc_html__('Particles Speed', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'step' => .1,
                'default' => 2,
            ]
        );

        $repeater->add_control(
            'particles_line',
            [
                'label' => esc_html__('Add Linked Line?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'particles_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grab' => esc_html__('Grab', 'wgl-extensions'),
                    'bubble' => esc_html__('Bubble', 'wgl-extensions'),
                    'repulse' => esc_html__('Repulse', 'wgl-extensions'),
                    'none' => esc_html__('None', 'wgl-extensions'),
                ],
                'default' => 'grab',
            ]
        );

        $repeater->add_responsive_control(
            'position_particles_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set particles vertical offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_particles_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set particles horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_control(
            'particles_width',
            [
                'label' => esc_html__('Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set particles container width in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $repeater->add_control(
            'particles_height',
            [
                'label' => esc_html__('Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set particles container height in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $repeater->add_control(
            'hide_particles_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $repeater->add_control(
            'hide_particles_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['hide_particles_on_mobile' => 'yes'],
                'default' => 768,
            ]
        );

        $widget->add_control(
            'items_particles',
            [
                'label' => esc_html__('Particles', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_particles_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->end_controls_section();


        $widget->start_controls_section(
            'extended_particles_img',
            [
                'label' => esc_html__('WGL Particles Image', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_particles_img_animation',
            [
                'label' => esc_html__('Add Particles Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'particles_image',
            [
                'label' => esc_html__('Image', 'wgl-extensions'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [  'active' => true],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'particles_img_width',
            [
                'label' => esc_html__('Image Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set particles img width in px.', 'wgl-extensions'),
                'min' => 0,
                'max' => 1000,
                'default' => 100,
            ]
        );

        $repeater->add_control(
            'particles_img_height',
            [
                'label' => esc_html__('Image Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set particles img height in px.', 'wgl-extensions'),
                'min' => 0,
                'max' => 1000,
                'default' => 100,
            ]
        );

        $widget->add_control(
            'items_particles_img',
            [
                'label' => esc_html__('Particles Image', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_particles_img_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->add_control(
            'particles_img_color',
            [
                'label' => esc_html__('Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_max_size',
            [
                'label' => esc_html__('Particles Max Size', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => 60,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_count',
            [
                'label' => esc_html__('Count Of Particles', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => 50,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_speed',
            [
                'label' => esc_html__('Particles Speed', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'step' => .1,
                'default' => 2,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_line',
            [
                'label' => esc_html__('Add Linked Line?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_rotate',
            [
                'label' => esc_html__('Add Rotate Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_rotate_speed',
            [
                'label' => esc_html__('Rotate Speed Animation', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'step' => .1,
                'default' => 5,
                'condition' => [
                    'particles_img_rotate' => 'yes',
                    'add_particles_img_animation' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'particles_img_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grab' => esc_html__('Grab', 'wgl-extensions'),
                    'bubble' => esc_html__('Bubble', 'wgl-extensions'),
                    'repulse' => esc_html__('Repulse', 'wgl-extensions'),
                    'none' => esc_html__('None', 'wgl-extensions'),
                ],
                'default' => 'grab',
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_responsive_control(
            'position_particles_img_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set particles vertical offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-particles-img-js' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_responsive_control(
            'position_particles_img_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => ['add_particles_img_animation' => 'yes'],
                'description' => esc_html__('Set particles horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                    '%' => ['min' => -100, 'max' => 100],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-particles-img-js' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $widget->add_control(
            'particles_img_container_width',
            [
                'label' => esc_html__('Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['add_particles_img_animation' => 'yes'],
                'description' => esc_html__('Set particles container width in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $widget->add_control(
            'particles_img_container_height',
            [
                'label' => esc_html__('Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['add_particles_img_animation' => 'yes'],
                'description' => esc_html__('Set particles container height in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $widget->add_control(
            'hide_particles_img_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_particles_img_animation' => 'yes'],
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $widget->add_control(
            'hide_particles_img_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['hide_particles_img_on_mobile' => 'yes' , 'add_particles_img_animation' => 'yes'],
                'default' => 768,
            ]
        );

        $widget->end_controls_section();

        /**
         * WGL Dynamic Highlights
         */
        $widget->start_controls_section(
            'extended_dynamic_highlights',
            [
                'label' => esc_html__('WGL Dynamic Highlights', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_dynamic_highlights_animation',
            [
                'label' => esc_html__('Add Highlights Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'dynamic_highlights_color_first',
            [
                'label' => esc_html__('First Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'default' => '#8d07d526',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'dynamic_highlights_color_second',
            [
                'label' => esc_html__('Secondary Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'default' => '#ff6c5233',
            ]
        );

        $repeater->add_responsive_control(
            'position_dynamic_highlights_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Dynamic Highlights offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_dynamic_highlights_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Dynamic Highlights horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'dynamic_highlights_width',
            [
                'label' => esc_html__('Size', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Dynamic Highlights container Size', 'wgl-extensions'),
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2000, 'step' => 5],
                ],
                'default' => ['size' => 500, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'dynamic_highlights_scale',
            [
                'label' => esc_html__('Scale by Horizontal', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--wgl-highlights-scalex: {{SIZE}}',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'dynamic_highlights_rotate',
            [
                'label' => esc_html__('Rotate (deg)', 'synta-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg', 'custom'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--wgl-highlights-rotate: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_control(
            'hide_dynamic_highlights_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
                'default' => 'yes',
            ]
        );

        $repeater->add_control(
            'hide_dynamic_highlights_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['hide_dynamic_highlights_on_mobile' => 'yes'],
                'default' => 768,
            ]
        );

        $repeater->add_control(
            'dynamic_highlights_index',
            [
                'label' => esc_html__('Highlights z-index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => -2,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'items_dynamic_highlights',
            [
                'label' => esc_html__('Dynamic Highlights', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_dynamic_highlights_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->end_controls_section();

        /**
         * WGL Highlights on Hover
         */
        $widget->start_controls_section(
            'extended_hover_highlights',
            [
                'label' => esc_html__('WGL Highlights on Hover', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_hover_highlights_animation',
            [
                'label' => esc_html__('Add Hover Highlights Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'hover_highlights_color_first',
            [
                'label' => esc_html__('Idle Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#90ACFF',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'hover_highlights_color_second',
            [
                'label' => esc_html__('Color on Hover', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#6411C7',
                'selectors' => [
                    '{{WRAPPER}}:is(:hover, :focus) {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_hover_highlights_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Highlights offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_hover_highlights_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Highlights horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'hover_highlights_width',
            [
                'label' => esc_html__('Size', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Highlights container Size', 'wgl-extensions'),
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2000, 'step' => 5],
                ],
                'default' => ['size' => 500, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'hover_highlights_scale',
            [
                'label' => esc_html__('Scale by Horizontal', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--wgl-highlights-scalex: {{SIZE}}',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'hover_highlights_rotate',
            [
                'label' => esc_html__('Rotate (deg)', 'synta-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg', 'custom'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--wgl-highlights-rotate: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_control(
            'hide_hover_highlights_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $repeater->add_control(
            'hide_hover_highlights_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['hide_hover_highlights_on_mobile' => 'yes'],
                'default' => 768,
            ]
        );

        $repeater->add_control(
            'hover_highlights_index',
            [
                'label' => esc_html__('Highlights z-index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => -2,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'item_transition',
            [
                'label' => esc_html__('Transition Duration', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['s'],
                'range' => [
                    's' => ['min' => 0, 'max' => 2, 'step' => 0.1 ],
                ],
                'default' => ['size' => 0.4, 'unit' => 's'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'items_hover_highlights',
            [
                'label' => esc_html__('Highlights', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_hover_highlights_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->end_controls_section();


        /**
         * WGL Morph
         */
        $widget->start_controls_section(
            'extended_morph',
            [
                'label' => esc_html__('WGL Morph', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_morph_animation',
            [
                'label' => esc_html__('Add Morph Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'morph_style',
            [
                'label' => esc_html__( 'Morph Style', 'wgl-extensions' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style_1' => esc_html__( 'Style 1', 'wgl-extensions' ),
                    'style_2' => esc_html__( 'Style 2', 'wgl-extensions' ),
                    'style_3' => esc_html__( 'Style 3', 'wgl-extensions' ),
                ],
                'default' => 'style_1',
            ]
        );

        $repeater->add_control(
            'morph_transform',
            [
                'label' => esc_html__( 'Morph Rotation', 'wgl-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => -360, 'max' => 360 ],
                ],
                'default' => [ 'size' => 0 ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );

        $repeater->add_control(
            'morph_color',
            [
                'label' => esc_html__('Morph Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'default' => '#8d07d526',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'morph_animation_speed',
            [
                'label' => esc_html__('Animation Speed', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'min' => 0,
                'step' => 1,
                'default' => 10,
            ]
        );

        $repeater->add_responsive_control(
            'position_morph_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Morph offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_morph_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Morph horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'morph_size',
            [
                'label' => esc_html__('Size', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'description' => esc_html__('Set Morph Size', 'wgl-extensions'),
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    '%' => ['min' => 0, 'max' => 100, 'step' => 1],
                    'px' => ['min' => 0, 'max' => 2000, 'step' => 1],
                ],
                'default' => ['size' => 450, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'hide_morph_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $repeater->add_control(
            'hide_morph_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['hide_morph_on_mobile' => 'yes'],
                'default' => 768,
            ]
        );

        $repeater->add_control(
            'morph_index',
            [
                'label' => esc_html__('Morph z-index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'default' => -2,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'items_morph',
            [
                'label' => esc_html__('Morph', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_morph_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->end_controls_section();

        /**
         * WGL 3D Wave
         */
        $widget->start_controls_section(
            'extended_wave',
            [
                'label' => esc_html__('WGL 3D Wave', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_wave',
            [
                'label' => esc_html__('Add 3D Wave Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $widget->add_control(
            'wave_dots_color',
            [
                'label' => esc_html__('Dots Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'alpha' => false,
                'condition' => ['add_wave!' => ''],
                'default' => '#8d07d5',
            ]
        );

        $widget->add_control(
            'wave_bg_color',
            [
                'label' => esc_html__('Background Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [  'active' => true],
                'alpha' => false,
                'condition' => ['add_wave!' => ''],
                'default' => '#000',
            ]
        );

        $widget->add_control(
            'wave_opacity',
            [
                'label' => esc_html__('Wave Opacity', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'size_units' => ['px'],
                'condition' => ['add_wave!' => ''],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wave' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $widget->add_control(
            'wave_dots_max_x',
            [
                'label' => esc_html__('Scene Position by X', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'range' => [
                    'px' => ['min' => -500, 'max' => 500],
                ],
                'condition' => ['add_wave!' => ''],
                'default' => ['size' => 0],
            ]
        );

        $widget->add_control(
            'wave_dots_max_y',
            [
                'label' => esc_html__('Scene Position by Y', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'range' => [
                    'px' => ['min' => -200, 'max' => 500],
                ],
                'condition' => ['add_wave!' => ''],
                'default' => ['size' => 150],
            ]
        );

        $widget->add_control(
            'wave_mouse_manipulation',
            [
                'label' => esc_html__('Mouse Manipulation', 'wgl-extensions'),
                'description' => esc_html__('For best performance, we recommend disabling animation', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_wave!' => ''],
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $widget->add_control(
            'wave_hide_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_wave!' => ''],
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $widget->add_control(
            'wave_hide_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => [
                    'add_wave!' => '',
                    'wave_hide_on_mobile!' => ''
                ],
                'default' => 768,
            ]
        );

        $widget->add_control(
            'wave_index',
            [
                'label' => esc_html__('Wave z-index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => [  'active' => true],
                'condition' => ['add_wave!' => ''],
                'default' => -2,
                'selectors' => [
                    '{{WRAPPER}} .wgl-wave' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $widget->end_controls_section();

        /**
         * WGL Sibling Columns Fade
         */
        $widget->start_controls_section(
            'extended_sibling_columns',
            [
                'label' => esc_html__('WGL Sibling Columns Fade', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $widget->add_control(
            'wgl_sibling_fade_elements',
            [
                'label' => esc_html__('Elements', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Disable', 'wgl-extensions'),
                    'animate-columns' => esc_html__('Columns', 'wgl-extensions'),
                    'animate-widgets' => esc_html__('Widgets of Columns', 'wgl-extensions'),
                ],
                'description' => esc_html__('Which Elements will be animated?', 'wgl-extensions'),
                'default' => '',
                'prefix_class' => '',
            ]
        );

        $widget->add_responsive_control(
            'wgl_sibling_elements_fade',
            [
                'label' => esc_html__( 'Sibling Elements Fade', 'wgl-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 1, 'step' => 0.01] ],
                'condition' => ['wgl_sibling_fade_elements!' => ''],
                'selectors' => [
                    '{{WRAPPER}}.animate-columns:hover > .elementor-container > .elementor-column:not(:hover),
                     {{WRAPPER}}.animate-widgets:hover > .elementor-container > .elementor-column > .elementor-widget-wrap > .elementor-widget:not(:hover),
                     {{WRAPPER}}.animate-widgets:hover > .elementor-container > .elementor-column > .elementor-widget-wrap > .elementor-inner-section:not(:hover),
                     {{WRAPPER}}.animate-columns:hover > .e-con-inner > .elementor-element:not(:hover),
                     {{WRAPPER}}.animate-widgets:hover > .e-con-inner > .elementor-element .elementor-widget:not(:hover)' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $widget->add_control(
            'wgl_sibling_elements_fade_transition',
            [
                'label' => esc_html__( 'Transition', 'wgl-extensions' ) . ' (ms)',
                'type' => Controls_Manager::SLIDER,
                'condition' => ['wgl_sibling_fade_elements!' => ''],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 3000, 'step' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.animate-columns:hover > .elementor-container > .elementor-column,
                     {{WRAPPER}}.animate-widgets:hover > .elementor-container > .elementor-column > .elementor-widget-wrap > .elementor-widget,
                     {{WRAPPER}}.animate-widgets:hover > .elementor-container > .elementor-column > .elementor-widget-wrap > .elementor-inner-section,
                     {{WRAPPER}}.animate-columns:hover > .e-con-inner > .elementor-element,
                     {{WRAPPER}}.animate-widgets:hover > .e-con-inner > .elementor-element .elementor-widget' => 'transition: {{SIZE}}ms',
                ],
            ]
        );
        $widget->end_controls_section();

        /**
         * WGL Opacity by Gradient
         */
        $widget->start_controls_section(
            'extended_opacity_by_gradient',
            [
                'label' => esc_html__('WGL Opacity by Gradient', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $mod = apply_filters('wgl_opacity_by_gradient_newest', false);
        if (!$mod) {
            $widget->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'extended_opacity_by_gradient_init',
                    'types' => ['gradient'],
                    'fields_options' => [
                        'background' => ['default' => ''],
                        'color' => [
                            'label' => esc_html__('Gradient Color 1', 'wgl-extensions'),
                            'default' => '#ffffff'
                        ],
                        'color_stop' => ['default' => ['unit' => '%', 'size' => 0]],
                        'color_b' => [
                            'label' => esc_html__('Gradient Color 2', 'wgl-extensions'),
                            'default' => 'transparent'
                        ],
                        'color_b_stop' => ['default' => ['unit' => '%', 'size' => 75]],
                        'gradient_type' => ['default' => 'radial'],
                        'gradient_angle' => [
                            'default' => ['unit' => 'deg', 'size' => 90],
                            'selectors' => [
                                '{{SELECTOR}}' => 'background-color: transparent; -webkit-mask-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                                '{{SELECTOR}} .elementor-editor-section-settings' => 'transform: translateX(-50%) translateY(0) scaleY(-1);',
                            ],
                        ],
                        'gradient_position' => [
                            'selectors' => [
                                '{{SELECTOR}}' => 'background-color: transparent; -webkit-mask-image: radial-gradient( circle at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                                '{{SELECTOR}} .elementor-editor-section-settings' => 'transform: translateX(-50%) translateY(0) scaleY(-1);',
                            ],
                        ],
                    ],
                    'selector' => '{{WRAPPER}}',
                ]
            );
        } else {
            $widget->add_control(
                'use_extended_mod_opacity_by_gradient_init',
                [
                    'label' => esc_html__('Use Opacity by Gradient?', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );
            $widget->add_responsive_control(
                'extended_mod_gradient_opacity_1',
                [
                    'label' => esc_html__('Opacity for 1st part', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'condition' => ['use_extended_mod_opacity_by_gradient_init!' => ''],
                    'range' => [
                        'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                    ],
                    'render_type' => 'ui',
                    'default' => ['size' => 1, 'unit' => 'px'],
                ]
            );
            $widget->add_responsive_control(
                'extended_mod_gradient_location_1',
                [
                    'label' => esc_html__('Location for 1st part', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'condition' => ['use_extended_mod_opacity_by_gradient_init!' => ''],
                    'size_units' => ['px', '%', 'custom'],
                    'range' => [
                        'px' => ['min' => 0, 'max' => 1920],
                        '%' => ['min' => 0, 'max' => 100],
                    ],
                    'render_type' => 'ui',
                    'default' => ['size' => 0, 'unit' => '%'],
                ]
            );
            $widget->add_responsive_control(
                'extended_mod_gradient_opacity_2',
                [
                    'label' => esc_html__('Opacity for 2nd part', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'condition' => ['use_extended_mod_opacity_by_gradient_init!' => ''],
                    'range' => [
                        'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                    ],
                    'render_type' => 'ui',
                    'default' => ['size' => 0, 'unit' => 'px'],
                ]
            );
            $widget->add_responsive_control(
                'extended_mod_gradient_location_2',
                [
                    'label' => esc_html__('Location for 2nd part', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'condition' => ['use_extended_mod_opacity_by_gradient_init!' => ''],
                    'size_units' => ['px', '%', 'custom'],
                    'range' => [
                        'px' => ['min' => 0, 'max' => 1920],
                        '%' => ['min' => 0, 'max' => 100],
                    ],
                    'render_type' => 'ui',
                    'default' => ['size' => 75, 'unit' => '%'],
                ]
            );
            $widget->add_control(
                'extended_mod_gradient_type',
                [
                    'label' => esc_html__('Type', 'wgl-extensions'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'linear' => esc_html__('Linear', 'wgl-extensions'),
                        'radial' => esc_html__('Radial', 'wgl-extensions'),
                    ],
                    'condition' => ['use_extended_mod_opacity_by_gradient_init!' => ''],
                    'render_type' => 'ui',
                    'default' => 'radial',
                ]
            );
            $widget->add_responsive_control(
                'extended_mod_gradient_angle',
                [
                    'label' => esc_html__('Angle', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['deg', 'grad', 'rad', 'turn', 'custom'],
                    'default' => ['unit' => 'deg', 'size' => 90],
                    'condition' => [
                        'use_extended_mod_opacity_by_gradient_init!' => '',
                        'extended_mod_gradient_type' => 'linear',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-color: transparent; -webkit-mask-image: linear-gradient({{SIZE}}{{UNIT}}, rgba(255,255,255,{{extended_mod_gradient_opacity_1.SIZE}}) {{extended_mod_gradient_location_1.SIZE}}{{extended_mod_gradient_location_1.UNIT}}, rgba(255,255,255,{{extended_mod_gradient_opacity_2.SIZE}}) {{extended_mod_gradient_location_2.SIZE}}{{extended_mod_gradient_location_2.UNIT}})',
                        '{{WRAPPER}} .elementor-editor-section-settings' => 'transform: translateX(-50%) translateY(0) scaleY(-1);',
                    ],
                ]
            );
            $widget->add_responsive_control(
                'extended_mod_gradient_position',
                [
                    'label' => esc_html__('Position', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'center center' => esc_html__('Center Center', 'elementor'),
                        'center left' => esc_html__('Center Left', 'elementor'),
                        'center right' => esc_html__('Center Right', 'elementor'),
                        'top center' => esc_html__('Top Center', 'elementor'),
                        'top left' => esc_html__('Top Left', 'elementor'),
                        'top right' => esc_html__('Top Right', 'elementor'),
                        'bottom center' => esc_html__('Bottom Center', 'elementor'),
                        'bottom left' => esc_html__('Bottom Left', 'elementor'),
                        'bottom right' => esc_html__('Bottom Right', 'elementor'),
                    ],
                    'default' => 'center center',
                    'condition' => [
                        'use_extended_mod_opacity_by_gradient_init!' => '',
                        'extended_mod_gradient_type' => 'radial',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-color: transparent; -webkit-mask-image: radial-gradient( circle at {{VALUE}}, rgba(255,255,255,{{extended_mod_gradient_opacity_1.SIZE}}) {{extended_mod_gradient_location_1.SIZE}}{{extended_mod_gradient_location_1.UNIT}}, rgba(255,255,255,{{extended_mod_gradient_opacity_2.SIZE}}) {{extended_mod_gradient_location_2.SIZE}}{{extended_mod_gradient_location_2.UNIT}})',
                        '{{WRAPPER}} .elementor-editor-section-settings' => 'transform: translateX(-50%) translateY(0) scaleY(-1);',
                    ],
                ]
            );
        }

        $widget->end_controls_section();

        /**
         * WGL Animate on Scroll
         */
        $widget->start_controls_section(
            'extended_animate_scroll',
            [
                'label' => esc_html__('WGL Animate on Scroll', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $widget->add_control(
            'add_extended_animate_scroll',
            [
                'label' => esc_html__('Add Animate on Scroll?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'prefix_class' => 'wgl-animate-scroll-',
            ]
        );

        $widget->add_control(
            'extended_animate_scroll_opacity',
            [
                'label' => esc_html__('Opacity', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => [ 'add_extended_animate_scroll' => 'yes' ],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'default' => ['size' => 0.5, 'unit' => 'px'],
            ]
        );
        $widget->add_control(
            'extended_animate_scroll_rotate',
            [
                'label' => esc_html__('Rotate', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => [ 'add_extended_animate_scroll' => 'yes' ],
                'range' => [
                    'px' => ['min' => 0, 'max' => 90, 'step' => 1],
                ],
                'default' => ['size' => 25, 'unit' => 'px'],
            ]
        );
        $widget->add_control(
            'extended_animate_scroll_perspective',
            [
                'label' => esc_html__('Perspective', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => [ 'add_extended_animate_scroll' => 'yes' ],
                'range' => [
                    'px' => ['min' => 0, 'max' => 5000, 'step' => 1],
                ],
                'default' => ['size' => 1200, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}' => '--wgl-anim-scroll-perspective: {{SIZE}}px;',
                ],
            ]
        );
        $widget->add_control(
            'extended_animate_scroll_transition',
            [
                'label' => esc_html__( 'Transition Duration', 'wgl-extensions' ) . ' (ms)',
                'type' => Controls_Manager::SLIDER,
                'condition' => [ 'add_extended_animate_scroll' => 'yes' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 3000, 'step' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--wgl-anim-scroll-transition: {{SIZE}}ms',
                ],
            ]
        );
        $widget->end_controls_section();
    }

    public function extended_container_sticky_options($widget, $args)
    {
        /**
         * Sticky column for container
         */

        $widget->start_controls_section(
            'extended_sticky',
            [
                'label' => esc_html__('WGL Sticky Column', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $widget->add_control(
            'apply_sticky_column',
            [
                'label' => esc_html__('Enable Sticky?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
                'return_value' => 'sidebar',
                'prefix_class' => 'sticky-',
                'selectors' => [
                    '{{WRAPPER}}.sticky-sidebar' => 'display: block;',
                ],
            ]
        );
        $widget->add_responsive_control(
            'sticky_column_offset_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => ['apply_sticky_column!' => ''],
                'render_type' => 'template',
                'size_units' => ['px'],
                'range' => [ 'px' => [ 'min' => -100, 'max' => 1000 ] ],
                'selectors' => [
                    '{{WRAPPER}}.sticky-sidebar' => '--wgl-sticky-offset-t: {{SIZE}};',
                ],
            ]
        );

        $widget->end_controls_section();
    }

    public function extends_header_params($widget, $args)
    {
        $widget->start_controls_section(
            'extended_header',
            [
                'label' => esc_html__('WGL Header Layout', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $widget->add_control(
            'apply_sticky_row',
            [
                'label' => esc_html__('Apply Sticky?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'sticky-on',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->end_controls_section();
    }

    public function extends_column_params( $widget, $args )
    {
        $widget->start_controls_section(
            'extended_header',
            [
                'label' => esc_html__('WGL Column Options', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $widget->add_responsive_control(
            'column_overflow',
            [
                'label' => esc_html__( 'Column Overflow', 'wgl-extensions' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'visible' => esc_html__( 'Visible', 'wgl-extensions' ),
                    'hidden' => esc_html__( 'Hidden', 'wgl-extensions' ),
                    'clip' => esc_html__( 'Clip', 'wgl-extensions' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'overflow: {{VALUE}}',
                ],
            ]
        );

        $widget->add_control(
            'apply_sticky_column',
            [
                'label' => esc_html__('Enable Sticky?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
                'return_value' => 'sidebar',
                'prefix_class' => 'sticky-',
                'selectors' => [
                    '{{WRAPPER}}.sticky-sidebar' => 'display: block;',
                ],
            ]
        );
        $widget->add_responsive_control(
            'sticky_column_offset_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => [  'active' => true],
                'condition' => ['apply_sticky_column!' => ''],
                'render_type' => 'template',
                'size_units' => ['px'],
                'range' => [ 'px' => [ 'min' => -100, 'max' => 1000 ] ],
                'selectors' => [
                    '{{WRAPPER}}.sticky-sidebar' => '--wgl-sticky-offset-t: {{SIZE}};',
                ],
            ]
        );

	    $widget->add_responsive_control(
		    'extend_column_order',
		    [
			    'label' => esc_html__( 'Column Order', 'wgl-extensions' ),
			    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
			    'min' => -5,
			    'max' => 5,
			    'selectors' => [
				    '{{WRAPPER}}' => 'order: {{VALUE}}',
			    ],
		    ]
	    );

        $widget->add_responsive_control(
            'wgl_sibling_widgets_fade',
            [
                'label' => esc_html__( 'Sibling Widgets Fade', 'wgl-extensions' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-widget-wrap > .elementor-widget:not(:hover),
                     {{WRAPPER}}:hover > .elementor-widget-wrap > .elementor-inner-section:not(:hover)' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $widget->end_controls_section();
    }
}
