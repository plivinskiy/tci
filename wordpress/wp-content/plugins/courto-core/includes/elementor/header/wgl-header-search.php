<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use WGL_Extensions\Includes\WGL_Cursor;
use Elementor\{Widget_Base, Controls_Manager, Group_Control_Border, Group_Control_Typography};

/**
 * Search widget for Header CPT
 *
 *
 * @category Class
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Search extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-header-search';
    }

    public function get_title()
    {
        return esc_html__('WGL Search', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-header-search';
    }

    public function get_keywords() {
        return ['search'];
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    public function get_script_depends()
    {
        return [ 'wgl-widgets' ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-search' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_style',
            [
                'label' => esc_html__('Choose Search Style', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'standard' => esc_html__( 'Standard', 'courto-core' ),
                    'simple' => esc_html__( 'Simple', 'courto-core' ),
                    'standard_fw' => esc_html__( 'Full Header Width', 'courto-core' ),
                    'alt' => esc_html__( 'Full Page Width', 'courto-core' ),
                ],
                'default' => 'standard',
            ]
        );

        $this->add_control(
            'search_text_add',
            [
                'label' => esc_html__('Add Text', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'condition' => ['search_style' => 'standard'],
            ]
        );

        $this->add_control(
            'search_text',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_attr__('ex: Search', 'courto-core'),
                'condition' => [
                    'search_text_add!' => '',
                    'search_style' => 'standard'
                ],
            ]
        );

        $this->add_control(
            'icon_alignment',
            [
                'label' => esc_html__('Icon Position', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['search_style' => 'simple'],
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
                'selectors_dictionary' => [
                    'left' => 'left: 0;',
                    'right' => 'left: auto; right: 0;',
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .header_search-field .search-button' => '{{VALUE}}',
                    '{{WRAPPER}} .header_search-field .search__icon' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'height_custom',
            [
                'label' => esc_html__('Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'vw', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .header_search' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'width_custom',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'vw', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .search-field' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_post_type',
            [
                'label' => esc_html__('Search Post Types', 'courto-core'),
                'type' => Controls_Manager::SELECT2,
                'options' => self::post_type_options(),
                'multiple' => true,
                'default' => '',
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

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typo',
                'selector' => '{{WRAPPER}} .header_search .header_search-field .search-field',
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label' => esc_html__('Input Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['search_style!' => 'alt'],
                'selectors' => [
                    '{{WRAPPER}} .header_search .header_search-field .search-field' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'input_icon_color',
            [
                'label' => esc_html__('Input Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['search_style!' => 'alt'],
                'selectors' => [
                    '{{WRAPPER}} .header_search .header_search-field .search__icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'input_bg_color',
            [
                'label' => esc_html__('Input Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['search_style!' => 'alt'],
                'selectors' => [
                    '{{WRAPPER}} .header_search .header_search-field .search-field' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'search_bg_color',
            [
                'label' => esc_html__('Search Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['search_style' => 'standard'],
                'selectors' => [
                    '{{WRAPPER}} .header_search .header_search-field' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'search_border',
                'selector' => '{{WRAPPER}} .header_search .header_search-field .search-field',
            ]
        );

        $this->add_control(
            'search_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'condition' => ['search_style' => 'standard'],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'search_field_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => ['search_style' => 'simple'],
                'selectors' => [
                    '{{WRAPPER}} .search_simple .search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> SEARCH ICON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_search',
            [
                'label' => esc_html__('Search Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search',
                'selector' => '{{WRAPPER}} .header_search-button, {{WRAPPER}} .header_search-close',
                'exclude' => ['font_family', 'text_transform', 'font_style', 'text_decoration', 'letter_spacing'],
            ]
        );

        $this->add_control(
            'search_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'icon' );
        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle' , 'courto-core')]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover' , 'courto-core')]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CLOSE ICON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_close',
            [
                'label' => esc_html__('Close Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'close_typo',
                'exclude' => ['font_family', 'text_transform', 'font_style', 'text_decoration', 'letter_spacing'],
                'selector' => '{{WRAPPER}} .search_standard .header_search-close',
            ]
        );

        $this->add_control(
            'icon_close_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .header_search-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_close_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'icon_close' );
        $this->start_controls_tab(
            'tab_icon_close_idle',
            ['label' => esc_html__('Idle' , 'courto-core')]
        );
        $this->add_control(
            'icon_close_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close' => 'color: {{VALUE}}'
                ],
            ]
        );
        $this->add_control(
            'icon_close_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_close_hover',
            ['label' => esc_html__('Hover' , 'courto-core')]
        );
        $this->add_control(
            'icon_close_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_close_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLES -> SEARCH TEXT
         */

        $this->start_controls_section(
            'style_search_text',
            [
                'label' => esc_html__('Search Text', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['search_text!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search_text_typo',
                'selector' => '{{WRAPPER}} .header_search-text',
            ]
        );

        $this->add_control(
            'search_text_font',
            [
                'label' => esc_html__('Theme Font Family', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'header' => esc_html__('Headings Font', 'courto-core'),
                    'content' => esc_html__('Content Font', 'courto-core'),
                    'additional' => esc_html__('Additional Font', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .header_search-text' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'search_text_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .header_search-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'search_text_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .header_search-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'search_text_border',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
                'selector' => '{{WRAPPER}} .header_search-text',
            ]
        );

        $this->add_control(
            'search_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .header_search-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_text_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'search_text_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-text' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    public static function post_type_options()
    {
        $args = array(
            'public'   => true,
            '_builtin' => false,
        );
        $output = 'names';
        $operator = 'and';
        $content = [
            '' => esc_html__('Default', 'courto-core'),
            'post' => 'post',
            'page' => 'page',
        ];
        $post_types = get_post_types($args, $output, $operator);
        foreach ($post_types  as $post_type) {
            $content[$post_type] = $post_type;
        }

        return $content ?? [];
    }

    public function render()
    {
        $_s = $this->get_settings_for_display();

        if (isset($_s['cursor_tooltip']) && '' != $_s['cursor_tooltip']) {
            add_filter( 'wgl/courto_module_cursor', function () { return true; });
        }

        $description = esc_html__('Type To Search', 'courto-core');
        $search_style = $_s['search_style'] ?? 'standard';
        $search_counter = null;
        $unique_id = uniqid('search-form-');

        if (class_exists('\Courto_Get_Header')) {
            $search_counter = \Courto_Get_Header::$search_form_counter ?? null;
        }

        $search_class = ' search_' . $_s['search_style'];

        $render_search = true;
        if ($search_style === 'alt') {
            // the only search form in Default and Sticky headers is allowed
            $render_search = $search_counter > 0 ? false : true;

            if (isset($search_counter)) \Courto_Get_Header::$search_form_counter++;
        }

        $inputs = '';
        if (!empty($_s['search_post_type'])) {
            if (count($_s['search_post_type']) === 1) {
                $inputs .= '<input type="hidden" name="post_type" value="'.$_s['search_post_type'][0].'" />';
            } else{
                foreach ($_s['search_post_type'] as $key => $value) {
                    $inputs .= '<input type="hidden" name="post_type[]" value="'.$value.'" />';
                }
            }
        }

        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($this, $_s);

        $this->add_render_attribute('search', 'class', 'wgl-search elementor-search header_search-button-wrapper');
        $this->add_render_attribute('search', 'role', 'button');
        $this->add_render_attribute('search-button', [
            'class' => [
                'header_search-button',
                ( isset($_s['cursor_tooltip']) && !empty($_s['cursor_tooltip']) ? ' wgl-cursor-text' : '' )
            ]
        ]);
        ?>
        <div class="header_search<?php echo esc_attr($search_class); ?>"><?php
            if ($search_style != 'simple') {?>
                <div <?php echo $this->get_render_attribute_string('search'); ?>>
                    <?php if ($_s['search_text']) {
                        echo '<div class="header_search-text">', $_s['search_text'], '</div>';
                    }?>
                    <div <?php echo $this->get_render_attribute_string('search-button'), $cursor_data; ?>>
                        <?php echo wgl_dynamic_styles()->wgl_theme_svg()['search']; ?>
                    </div>
                    <div class="header_search-close"><?php echo wgl_dynamic_styles()->wgl_theme_svg()['close']; ?></div>
                </div><?php
            }

            if ($render_search) { ?>
                <div class="header_search-field"><?php
                if ($search_style != 'simple') {
                    if ($search_style === 'alt') { ?>
                        <div class="header_search-wrap">
                            <div class="wgl_theme_module_double_headings aleft">
                            <h3 class="header_search-heading_description heading_title"><?php
                                echo apply_filters('wgl_theme/search/description', $description); ?>
                            </h3>
                            </div>
                            <div class="header_search-close"><?php echo wgl_dynamic_styles()->wgl_theme_svg()['close']; ?></div>
                        </div><?php
                    } else {
                        echo '<div class="header_search-close">' . wgl_dynamic_styles()->wgl_theme_svg()['close'] . '</div>';
                    }
                }
                // search form
                echo '<form role="search" method="get" action="', esc_url(home_url('/')), '" class="search-form">',
                    '<input',
                        ' required',
                        ' type="text"',
                        ' id="', esc_attr($unique_id), '"',
                        ' class="search-field"',
                        ' placeholder="', esc_attr_x('Search &hellip;', 'placeholder', 'courto-core'), '"',
                        ' value="', get_search_query(), '"',
                        ' name="s"',
                        '>',
                    '<input class="search-button" type="submit" value="', esc_attr__('Search', 'courto-core'), '">',
                    $inputs;
                    echo '<i class="search__icon">'. wgl_dynamic_styles()->wgl_theme_svg()['search']. '</i>',
                '</form>'; ?>
                </div><?php
            }?>
        </div><?php
    }
}
