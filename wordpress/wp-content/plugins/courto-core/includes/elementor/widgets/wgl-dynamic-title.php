<?php

/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-dynamic-title.php`.
 */

namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
};

class WGL_Dynamic_Title extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-dynamic-title';
    }

    public function get_title()
    {
        return esc_html__('WGL Dynamic Title', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-dynamic-title';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_fields()
    {

        $groups = apply_filters('courto-core/dynamic_title/groups', array(
            array(
                'label'  => esc_html__('Post', 'courto-core'),
                'options' => apply_filters(
                    'courto-core/dynamic_title/field/post',
                    array(
                        'post_id'       => esc_html__('Post ID', 'courto-core'),
                        'post_title'    => esc_html__('Title', 'courto-core'),
                        'post_name'     => esc_html__('Post Slug', 'courto-core'),
                        'post_type'     => esc_html__('Post Type', 'courto-core'),
                        'post_date'     => esc_html__('Date', 'courto-core'),
                        'post_modified' => esc_html__('Date Modified', 'courto-core'),
                        'post_status'   => esc_html__('Post Status', 'courto-core'),
                    )
                )
            ),
            array(
                'label'  => esc_html__('Term', 'courto-core'),
                'options' => apply_filters(
                    'courto-core/dynamic_title/field/term',
                    array(
                        'term_id'     => esc_html__('Term ID', 'courto-core'),
                        'term_title'  => esc_html__('Term Title', 'courto-core'),
                        'term_t_n'    => esc_html__('Term Title & Name', 'courto-core'),
                        'name'        => esc_html__('Term name', 'courto-core'),
                        'slug'        => esc_html__('Term slug', 'courto-core'),
                        'description' => esc_html__('Term description', 'courto-core'),
                        'count'       => esc_html__('Posts count', 'courto-core'),
                        'parent'      => esc_html__('Parent term ID', 'courto-core'),
                    )
                )
            ),
        ));

        return $groups;
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'wgl_dynamic_Title_section',
            ['label' => esc_html__('Dynamic Settings', 'courto-core')]
        );

        $this->add_control(
            'dynamic_field',
            array(
                'label'     => __('Dynamic Field', 'courto-core'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'term_t_n',
                'groups'    => $this->get_fields(),
            )
        );

        $this->add_control(
            'dynamic_title_format',
            array(
                'label'       => __('Title format', 'courto-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     =>  '%s',
                'description' => __('%s will be replaced with field dynamic title.', 'courto-core'),
            )
        );

        $this->add_control(
            'default_text',
            [
                'label' => esc_html__('Default text if value is empty', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_attr__('Portfolio', 'courto-core'),
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

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Dynamic Title Styles', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dynamic_title_type',
                'selector' => '{{WRAPPER}} .wgl-dynamic_title',
            ]
        );

        $this->add_responsive_control(
		    'dynamic_title_display',
		    [
			    'label' => esc_html__( 'Display', 'courto-core' ),
			    'type' => Controls_Manager::SELECT,
			    'options' => [
				    'inline' => esc_html__( 'Inline', 'courto-core' ),
				    'block' => esc_html__( 'Block', 'courto-core' ),
				    'inline-block' => esc_html__( 'Inline Block', 'courto-core' ),
			    ],
			    'default' => 'inline',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-dynamic_title' => 'display: {{VALUE}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'dynamic_title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-dynamic_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('dynamic_title');
        $this->start_controls_tab(
            'dynamic_title_color',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'dynamic_title_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-dynamic_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'dynamic_title_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'dynamic_title_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-dynamic_title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function get_current_object()
    {
        $obj = false;
        global $post;

        if (is_singular()) {
            $obj = $post;
        } elseif (is_tax() || is_category() || is_tag() || is_author()) {
            $obj = get_queried_object();
        } elseif (is_archive() || is_home() || is_post_type_archive()) {
            $obj = $post;
        } elseif ($post) {
            $obj = $post;
        }

        $obj = apply_filters('courto-core/dynamic_title/object', $obj, $this);

        return $obj;
    }

    public function get_item($settings = null)
    {
        $object = $this->get_current_object();

        if (!$object) {
            return false;
        }

        $object_vars = get_object_vars($object);

        if ('post_id' === $settings && 'WP_Post' === get_class($object)) {
            $object_vars['post_id'] = $object_vars['ID'];
        }

        if (isset($object_vars[$settings])) {
            return $object_vars[$settings];
        }

        if (is_object($object) && method_exists($object, $settings)) {
            return call_user_func(array($object, $settings));
        } else if (is_object($object) && 'term_title' === $settings && is_tax()) {
            $tax    = get_taxonomy($object->taxonomy);
            return sprintf(
                _x('%s:', 'taxonomy term archive title prefix'),
                $tax->labels->singular_name,
                'courto-core'
            );
        } else if (is_object($object) && 'term_t_n' === $settings && is_tax()) {
            return get_the_archive_title();
        }
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $dynamic_field = $this->get_item($_s['dynamic_field']);
        $default_text = $_s['default_text'];

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
            'p' => ['class' => true, 'style' => true],
            'small' => ['class' => true, 'style' => true]
        ];


        if (!empty($dynamic_field) || !empty($default_text)) {
            echo '<' . $_s['title_tag'] . ' class="wgl-dynamic_title">';
            if(!empty($dynamic_field)){
                $this->render_filtered_result($dynamic_field, $_s);
            }else if(!empty($default_text)){
                echo wp_kses($default_text, $kses_allowed_html);
            }

            echo '</' . $_s['title_tag'] . '>';
        }
    }

    public function render_filtered_result($field, $settings)
    {

        if (!empty($settings['dynamic_title_format'])) {
            if (false === strpos($settings['dynamic_title_format'], '%s')) {
                echo __('<b>Error:</b> the field format must contains "%s"', 'courto-core');
                return;
            }
            $field = !is_array($field) && !is_object($field) ? $field : '';
            $result = sprintf($settings['dynamic_title_format'], $field);
            echo $result;
        }
    }

    public function wpml_support_module()
    {
        add_filter('wpml_elementor_widgets_to_translate',  [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter($widgets)
    {
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this,
            $widgets
        );
    }
}
