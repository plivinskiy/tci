<?php

namespace WGL_Extensions\Includes;

defined('ABSPATH') || exit;

use Elementor\{
    Controls_Manager,
    Control_Media,
    Utils,
    Icons_Manager,
    Group_Control_Image_Size
};

if (!class_exists('WGL_Webgl_Fluid')) {
    /**
     * WGL Elementor Webgl Fluid Settings
     *
     *
     * @package courto-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.0
     */
    class WGL_Webgl_Fluid
    {
        private static $instance;

        /**
         * @var WGL_Webgl_Fluid
         */
        private $canvas_switcher;
        private $color_settings;
        private $color_idle;
        private $resolution;
        private $velocity;
        private $density;
        private $pressure;
        private $splat_radius;
        private $backspace_animation;
        private $dark_mode;
        private $dir_path;
        private $opacity;
        private $fluid_animation;

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
            $this->dir_path = plugin_dir_path(__FILE__);

            add_action('elementor/init', [$this, 'init_addons']);

            // Add WebGL Fluid Animation
            add_action('elementor/element/wp-page/document_settings/after_section_end', [$this, 'inject_options_page'], 10, 1);
        }

        public function init_addons()
        {
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'webgl_fluid_enqueue_scripts'], 10, 3);
            add_action('elementor/frontend/before_enqueue_scripts', [$this, 'webgl_fluid_enqueue_scripts']);

            add_action("wgl/canvas_fluid_animation", [$this, 'webgl_fluid_animation']);
        }

        public function init_variable()
        {
            $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
            $page_settings_model = $page_settings_manager->get_model(get_the_ID());

            $this->fluid_animation = $page_settings_model->get_data('settings')['use_webgl_fluid'] ?? '';

            if ('yes' === $this->fluid_animation) {
                $this->color_settings = $page_settings_model->get_settings('webgl_fluid_color_type');
                $this->opacity = $page_settings_model->get_settings('webgl_fluid_opacity')['size'] ?? '1';
                $this->color_idle = $page_settings_model->get_settings('webgl_fluid_color_idle');
                $this->resolution = $page_settings_model->get_settings('webgl_fluid_resolution');
                $this->velocity = $page_settings_model->get_settings('webgl_fluid_velocity')['size'];
                $this->density = $page_settings_model->get_settings('webgl_fluid_density')['size'];
                $this->pressure = $page_settings_model->get_settings('webgl_fluid_pressure')['size'];
                $this->splat_radius = $page_settings_model->get_settings('webgl_fluid_splat_radius')['size'];
                $this->backspace_animation = $page_settings_model->get_settings('webgl_fluid_backspace');
                $this->canvas_switcher = $page_settings_model->get_settings('webgl_fluid_frontend_switch');
                $this->dark_mode = $page_settings_model->get_settings('webgl_fluid_dark_mode');
            }
        }

        public function webgl_fluid_animation()
        {
            $this->init_variable();

            if ('yes' !== $this->fluid_animation) {
                return;
            }

            echo '<div class="wgl-canvas-outer">';
            echo '<canvas id="wgl-webgl-fluid"></canvas>';
            echo '</div>';

            if ($this->canvas_switcher) {
                echo '<div class="wgl-fluid-switcher',('yes' === $this->dark_mode ? ' dark_mode' : ''),'">';
                echo '<a class="wgl-fluid-switcher__link" href="#"><i class="fa fa-cogs"></i></a>';
                echo '<div class="wgl-fluid-swicher__content">';
                $text_panel = esc_html__( 'Try to create your own Fluid Animation', 'courto-core' );
                echo '<div class="wgl-fluid-switcher__title">'.apply_filters( 'wgl/fluid_panel_text', $text_panel ).'</div>';
                ?>
                <form action="/" class="wgl-fluid-switcher__form">
                    <div class="wgl-control wgl-control-type-select wgl-label-inline">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('WGL Fluid Animation', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <select name="webgl_fluid_color_type">
                                    <option value="random"<?php echo 'random' === $this->color_settings ? ' selected' : '' ;?>><?php echo esc_html__('Random', 'courto-core');?></option>
                                    <option value="simple"<?php echo 'simple' === $this->color_settings ? ' selected' : '' ;?>><?php echo esc_html__('Simple Color', 'courto-core');?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="wgl-control wgl-control-type-color wgl-label-inline<?php echo 'random' === $this->color_settings ? ' hide' : ' show';?>">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Color Animation', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <input type="color" id="fluid_color_idle" name="fluid_color_idle" value="<?php echo esc_attr($this->color_idle);?>">
                            </div>
                        </div>
                    </div>
                    <div class="wgl-control wgl-control-type-slider wgl-label-block">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Opacity', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <input class="wgl-slider" type="range" name="fluid_opacity_range" min="0" max="1" value="<?php echo esc_attr($this->opacity);?>" step="0.01" oninput="this.form.fluid_opacity_input.value=this.value" />
                                <input class="wgl-slider-input" type="number" name="fluid_opacity_input" min="0" max="1" value="<?php echo esc_attr($this->opacity);?>" step="0.01" oninput="this.form.fluid_opacity_range.value=this.value" />
                            </div>
                        </div>
                    </div>

                    <div class="wgl-control wgl-control-type-select wgl-label-inline">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Sim Resolution', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <select name="webgl_fluid_resolution">
                                    <option value="256"<?php echo '256' === $this->resolution ? ' selected' : '' ;?>>256</option>
                                    <option value="128"<?php echo '128' === $this->resolution ? ' selected' : '' ;?>>128</option>
                                    <option value="64"<?php echo '64' === $this->resolution ? ' selected' : '' ;?>>64</option>
                                    <option value="32"<?php echo '32' === $this->resolution ? ' selected' : '' ;?>>32</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="wgl-control wgl-control-type-slider wgl-label-block">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Density Diffusion', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <input class="wgl-slider" type="range" name="density_range" min="0.01" max="4" value="<?php echo esc_attr($this->density);?>" step="0.01" oninput="this.form.density_input.value=this.value" />
                                <input class="wgl-slider-input" type="number" name="density_input" min="0.01" max="4" value="<?php echo esc_attr($this->density);?>" step="0.01" oninput="this.form.density_range.value=this.value" />
                            </div>
                        </div>
                    </div>
                    <div class="wgl-control wgl-control-type-slider wgl-label-block">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Velocity', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <input class="wgl-slider" type="range" name="velocity_range" min="0.01" max="4" value="<?php echo esc_attr($this->velocity);?>" step="0.01" oninput="this.form.velocity_input.value=this.value" />
                                <input class="wgl-slider-input" type="number" name="velocity_input" min="0" max="4" value="<?php echo esc_attr($this->velocity);?>" step="0.01" oninput="this.form.velocity_range.value=this.value" />
                            </div>
                        </div>
                    </div>
                    <div class="wgl-control wgl-control-type-slider wgl-label-block">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Pressure', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <input class="wgl-slider" type="range" name="pressure_range" min="0" max="1" value="<?php echo esc_attr($this->pressure);?>" step="0.01" oninput="this.form.pressure_input.value=this.value" />
                                <input class="wgl-slider-input" type="number" name="pressure_input" min="0" max="1" value="<?php echo esc_attr($this->pressure);?>" step="0.01" oninput="this.form.pressure_range.value=this.value" />
                            </div>
                        </div>
                    </div>
                    <div class="wgl-control wgl-control-type-slider wgl-label-block">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Splat Radius', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper wgl-control-unit-5">
                                <input class="wgl-slider" type="range" name="splat_radius_range" min="0" max="1" value="<?php echo esc_attr($this->splat_radius);?>" step="0.01" oninput="this.form.splat_radius_input.value=this.value" />
                                <input class="wgl-slider-input" type="number" name="splat_radius_input" min="0" max="1" value="<?php echo esc_attr($this->splat_radius);?>" step="0.01" oninput="this.form.splat_radius_range.value=this.value" />
                            </div>
                        </div>
                    </div>
                    <div class="wgl-control wgl-control-type-switcher wgl-label-inline">
                        <div class="wgl-control-field">
                            <label class="wgl-control-title"><?php echo esc_html__('Keys press sensitivity', 'courto-core');?></label>
                            <div class="wgl-control-input-wrapper">
                                <label class="wgl-switch wgl-control-unit-2">
                                    <input type="checkbox" name="webgl_fluid_backspace" class="wgl-switch-input" value="yes" <?php echo 'yes' === $this->backspace_animation ? ' checked' : ''; ?>>
                                    <span class="wgl-switch-label" data-on="Yes" data-off="No"></span>
                                    <span class="wgl-switch-handle"></span>
                                </label>
                            </div>
                        </div>
                        <div class="wgl-control-field-description"><?php echo esc_html__('P - pause. Space - extra diffusion', 'courto-core');?></div>
                    </div>
                </form>
                <?php
                echo '</div>';
                echo '</div>';
            }
        }

        public function webgl_fluid_enqueue_scripts()
        {
            $this->init_variable();

            if ('yes' === $this->fluid_animation) {
                wp_enqueue_script('wgl-webgl-fluid', esc_url(WGL_ELEMENTOR_MODULE_URL . 'assets/js/wgl_webgl_fluid.js'), ['jquery'], false, true);
                wp_localize_script('wgl-webgl-fluid', 'webgl_fluid_settings', [
                    'color_type' => esc_attr($this->color_settings),
                    'color' => esc_attr($this->color_idle),
                    'resolution' => esc_attr($this->resolution),
                    'velocity' => esc_attr($this->velocity),
                    'density' => esc_attr($this->density),
                    'pressure' => esc_attr($this->pressure),
                    'splat_radius' => esc_attr($this->splat_radius),
                    'backspace_animation' => esc_attr($this->backspace_animation),
                ]);
            }
        }

        public function inject_options_page($document)
        {
            $document->start_controls_section(
                'body_webgl_fluid_options',
                [
                    'label' => esc_html__('WGL Fluid Animation', 'courto-core'),
                    'tab' => Controls_Manager::TAB_SETTINGS
                ]
            );

            $document->add_control(
                'use_webgl_fluid',
                [
                    'label' => esc_html__('Use Fluid Animation?', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            // NOTICE
            $document->add_control(
                'webgl_fluid_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'condition' => ['use_webgl_fluid' => 'yes'],
                    'raw' => esc_html__('If the animation is just turned on, click the "Update" button and reload the page to make it visible in the editor, and go to further customization of the one.', 'courto-core'),
                ]
            );

            $document->add_control(
                'webgl_fluid_color_type',
                [
                    'label' => esc_html__('Color', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'condition' => ['use_webgl_fluid' => 'yes'],
                    'options' => [
                        'random' => esc_html__('Random', 'courto-core'),
                        'simple' => esc_html__('Simple Color', 'courto-core'),
                    ],
                    'default' => 'random',
                ]
            );

            $document->add_control(
                'webgl_fluid_color_idle',
                [
                    'label' => esc_html__('Color Animation', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
			        'dynamic' => [  'active' => true],
                    'alpha' => false,
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                        'webgl_fluid_color_type' => 'simple',
                    ],
                ]
            );

            $document->add_control(
                'webgl_fluid_color_invert',
                [
                    'label' => esc_html__('Invert Color?', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                        'webgl_fluid_color_type' => 'simple',
                    ],
                    'selectors' => [
                        '.wgl-canvas-outer' => 'filter: invert(1);',
                    ],
                ]
            );

            $document->add_control(
                'webgl_fluid_opacity',
                [
                    'label' => esc_html__('Opacity', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
			        'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                    ],
                    'range' => [
                        'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                    ],
                    'default' => ['size' => 1, 'unit' => 'px'],
                    'selectors' => [
                        '.wgl-canvas-outer' => 'opacity: {{SIZE}};',
                    ],
                ]
            );

            $document->add_control(
                'webgl_fluid_resolution',
                [
                    'label' => esc_html__('Sim Resolution', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                    ],
                    'options' => [
                        '256' => esc_html__('256', 'courto-core'),
                        '128' => esc_html__('128', 'courto-core'),
                        '64' => esc_html__('64', 'courto-core'),
                        '32' => esc_html__('32', 'courto-core'),
                    ],
                    'default' => '128',
                ]
            );

            $document->add_control(
                'webgl_fluid_density',
                [
                    'label' => esc_html__('Density Diffusion', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
			        'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                    ],
                    'range' => [
                        'px' => ['min' => 0.01, 'max' => 4, 'step' => 0.01],
                    ],
                    'default' => ['size' => .97],
                ]
            );

            $document->add_control(
                'webgl_fluid_velocity',
                [
                    'label' => esc_html__('Velocity Diffusion', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
			        'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                    ],
                    'range' => [
                        'px' => ['min' => 0.01, 'max' => 4, 'step' => 0.01],
                    ],
                    'default' => ['size' => .98],
                ]
            );

            $document->add_control(
                'webgl_fluid_pressure',
                [
                    'label' => esc_html__('Pressure', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
			        'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                    ],
                    'range' => [
                        'px' => ['min' => 0.01, 'max' => 1, 'step' => 0.01],
                    ],
                    'default' => ['size' => .8],
                ]
            );

            $document->add_control(
                'webgl_fluid_splat_radius',
                [
                    'label' => esc_html__('Splat Radius', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
			        'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                    ],
                    'range' => [
                        'px' => ['min' => 0.01, 'max' => 1, 'step' => 0.01],
                    ],
                    'default' => ['size' => .01],
                ]
            );

            $document->add_control(
                'webgl_fluid_backspace',
                [
                    'label' => esc_html__('Keys press sensitivity', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'use_webgl_fluid' => 'yes',
                    ],
                    'default' => 'yes',
                    'description' => esc_html__('P - pause. Space - extra diffusion', 'courto-core'),
                ]
            );

            $document->add_control(
                'webgl_fluid_frontend_switch',
                [
                    'label' => esc_html__('Frontend Fluid Animation Switch', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            $document->add_control(
                'webgl_fluid_dark_mode',
                [
                    'label' => esc_html__('Dark Mode', 'niсo-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'webgl_fluid_frontend_switch' => 'yes',
                    ],
                    'label_on' => esc_html__('On', 'niсo-core'),
                    'label_off' => esc_html__('Off', 'niсo-core'),
                ]
            );

            $document->end_controls_section();
        }
    }
}

if (!function_exists('wgl_elementor_webgl_fluid')) {
    function wgl_elementor_webgl_fluid()
    {
        return WGL_Webgl_Fluid::get_instance();
    }

    wgl_elementor_webgl_fluid();
}
