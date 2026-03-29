<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{Group_Control_Border,
    Plugin,
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Box_Shadow};

/**
 * Login/Register widget for Header CPT
 *
 *
 * @category Class
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_login extends Widget_Base
{
    public function get_name() {
        return 'wgl-header-login';
    }

    public function get_title() {
        return esc_html__('WGL Login Button', 'courto-core' );
    }

    public function get_icon() {
        return 'wgl-header-login';
    }

    public function get_keywords() {
        return [ 'login', 'register', 'logout', 'button', 'woocommerce' ];
    }

    public function get_categories() {
        return [ 'wgl-header-modules' ];
    }

    public function get_script_depends() {
        return [ 'wgl-widgets' ];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_wc-login_settings',
            [
                'label' => esc_html__( 'WooCommerce Login', 'courto-core' ),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'def' => esc_html__('Default', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => 'def',
            ]
        );

        $this->add_control(
            'login_text',
            [
                'label' => esc_html__('Login Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'default' => esc_html__('Login', 'courto-core'),
                'condition' => [
                    'button_text' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'logout_text',
            [
                'label' => esc_html__('Logout Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'default' => esc_html__('Logout', 'courto-core'),
                'condition' => [
                    'button_text' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

        /**
        * STYLE
        */

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typo',
                'selector' => '{{WRAPPER}} .login-in .login-in_wrapper a',
            ]
        );
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .login-in .login-in_wrapper a',
            ]
        );
        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'sp_color_tabs' );
        $this->start_controls_tab(
            'tab_color_idle',
            [ 'label' => esc_html__('Idle' , 'courto-core') ]
        );
        $this->add_control(
            'color_idle',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'bg_idle',
            [
                'label' => esc_html__( 'Background', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_shadow_idle',
                'selector' => '{{WRAPPER}} .login-in .login-in_wrapper a',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_color_hover',
            [ 'label' => esc_html__('Hover' , 'courto-core') ]
        );
        $this->add_control(
            'color_hover',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'bg_hover',
            [
                'label' => esc_html__( 'Background', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a:hover' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .login-in .login-in_wrapper a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_shadow_hover',
                'selector' => '{{WRAPPER}} .login-in .login-in_wrapper a:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }


    public function render()
    {
        if (!class_exists('\WooCommerce')) {
            return;
        }
        $_ = $this->get_settings_for_display();

        $logout_text = esc_html__('Logout', 'courto-core');
        $login_text = esc_html__('Login', 'courto-core');
        if ($_['button_text'] == 'custom') {
            $logout_text = !empty($_['logout_text']) ? $_['logout_text'] : $logout_text;
            $login_text = !empty($_['login_text']) ? $_['login_text'] : $login_text;
        }
        $link = get_permalink( get_option('woocommerce_myaccount_page_id') );
        $query_args = [
            'action' => urlencode('signup_form'),
        ];
        $url = add_query_arg($query_args, $link);

        $link_logout = wp_logout_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>
        <div class="login-in woocommerce">
            <span class="login-in_wrapper"><?php
            if (is_user_logged_in()) {
                echo "<a class='login-in_link-logout button' href='", esc_url($link_logout), "'>", $logout_text, "</a>";
            } else {
                echo "<a class='login-in_link button' href='", esc_url_raw($url), "'>", $login_text, '</a>';
            }?>
            </span><?php
	        if (!(bool) Plugin::$instance->editor->is_edit_mode()) : ?>
                <div class="login-modal wgl_modal-window">
                    <div class="overlay"></div>
                    <div class="modal-dialog modal_window-login">
                        <div class="modal_header"></div>
                        <div class="modal_content"><?php
	                        wc_get_template('myaccount/form-login.php'); ?>
                        </div>
                    </div>
                </div><?php
            endif; ?>
        </div><?php
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