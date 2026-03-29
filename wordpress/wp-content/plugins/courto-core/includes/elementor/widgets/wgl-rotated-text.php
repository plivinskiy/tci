<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-rotated-text.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Controls_Manager,
    Group_Control_Typography,
    Widget_Base
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;
use WGL_Extensions\Includes\WGL_Icons;

class WGL_Rotated_Text extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-rotated-text';
    }

    public function get_title()
    {
        return esc_html__('WGL Rotated Text', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-rotated-text';
    }

    public function get_keywords() {
        return ['rotated', 'text'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_rotated_text_section',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'rt_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
                'rows' => 1,
                'default' => esc_html_x('Rotated Title', 'WGL Rotated Text', 'courto-core'),
            ]
        );

	    $this->add_control(
		    'subtitle',
		    [
			    'label' => esc_html__('Subtitle', 'courto-core'),
			    'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
			    'label_block' => true,
                'placeholder' => esc_attr__('Rotated Sub-Title', 'courto-core'),
            ]
	    );

	    $this->add_responsive_control(
		    'height',
		    [
			    'label' => esc_html__('Height', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
			    'range' => [
				    'px' => ['min' => 20, 'max' => 1000],
			    ],
			    'default' => ['size' => 400],
			    'selectors' => [
				    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'v_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Justify', 'courto-core'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}}' => 'display: flex; align-items: {{VALUE}}'
                ],
            ]
        );
        $this->add_responsive_control(
            'h_alignment',
            [
                'label' => esc_html__('Horizontal Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'courto-core'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'disable_rotation',
		    [
			    'label' => esc_html__('Disable Rotation', 'courto-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'devices' => [ 'desktop', 'tablet', 'mobile' ],
			    'mobile_default' => 'yes',
			    'return_value' => 'yes',
			    'prefix_class' => 'disable-rotation%s-',
		    ]
	    );

	    $this->add_control(
		    'link',
		    [
			    'label' => esc_html__('Module Link', 'courto-core'),
			    'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
			    'placeholder' => esc_attr__('https://your-link.com', 'courto-core'),
		    ]
	    );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> ICON \ IMAGE
        /*-----------------------------------------------------------------------------------*/

	    WGL_Icons::init(
		    $this,
		    [
			    'section' => true,
		    ]
	    );

	    /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> ITEM
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('Item', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_responsive_control(
		    'item_margin',
		    [
			    'label' => esc_html__('Padding', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-rotated_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> TITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['rt_title!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .rt__title-wrapper',
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
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'h3',
            ]
        );
        $this->add_responsive_control(
            'title_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                'selectors' => [
                    '{{WRAPPER}} .rt__title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

	    $this->start_controls_tabs( 'tabs_title' );
	    $this->start_controls_tab(
		    'tab_title_idle',
		    ['label' => esc_html__('Idle' , 'courto-core')]
	    );
	    $this->add_control(
		    'title_color',
		    [
			    'label' => esc_html__('Title Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .rt__title' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
        $this->add_control(
            'title_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'title_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .rt__title' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
	    $this->end_controls_tab();
	    $this->start_controls_tab(
		    'title_hover',
		    ['label' => esc_html__('Hover' , 'courto-core')]
	    );
	    $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['rt_title!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-rotated_text:hover .rt__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_color_hover',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'title_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-rotated_text:hover .rt__title' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
	    $this->end_controls_tab();
	    $this->end_controls_tabs();
        $this->end_controls_section();

	    /*-----------------------------------------------------------------------------------*/
	    /*  STYLES -> SUBTITLE
		/*-----------------------------------------------------------------------------------*/

	    $this->start_controls_section(
		    'section_style_subtitle',
		    [
			    'label' => esc_html__('Subtitle', 'courto-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
			    'condition' => ['subtitle!' => ''],
		    ]
	    );

	    $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typo',
                'selector' => '{{WRAPPER}} .rt__subtitle',
            ]
        );

	    $this->add_responsive_control(
		    'subtitle_gap',
		    [
			    'label' => esc_html__('Gap', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'condition' => ['rt_title!' => ''],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 100],
			    ],
			    'default' => ['size' => 11],
			    'render_type' => 'template',
			    'selectors' => [
				    '{{WRAPPER}} .rt__subtitle' => 'margin-right: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs(
		    'tabs_subtitle'
	    );

	    $this->start_controls_tab(
		    'tab_subtitle_idle',
		    ['label' => esc_html__('Idle' , 'courto-core')]
	    );

	    $this->add_control(
		    'subtitle_color',
		    [
			    'label' => esc_html__('Subtitle Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .rt__subtitle' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'subtitle_hover',
		    ['label' => esc_html__('Hover' , 'courto-core')]
	    );

	    $this->add_control(
		    'subtitle_color_hover',
		    [
			    'label' => esc_html__('Subtitle Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-rotated_text:hover .rt__subtitle' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->end_controls_tab();
	    $this->end_controls_tabs();

	    $this->end_controls_section();

	    /*-----------------------------------------------------------------------------------*/
	    /*  STYLES -> ICON / IMAGE
		/*-----------------------------------------------------------------------------------*/

	    $this->start_controls_section(
		    'section_style_icon_image',
		    [
			    'label' => esc_html__('Icon / Image', 'courto-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
			    'condition' => ['icon_type!' => ''],
		    ]
	    );

	    $this->add_responsive_control(
		    'icon_size',
		    [
			    'label' => esc_html__('Icon Size', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'condition' => [
				    'icon_type' => 'font',
				    'icon_fontawesome!' => [
					    'value' => '',
					    'library' => '',
				    ]
			    ],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 200 ],
			    ],
			    'default' => ['size' => 20 ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'image_max_width',
		    [
			    'label' => esc_html__( 'Max Width', 'courto-core' ),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'condition' => ['icon_type' => 'image'],
			    'range' => [
				    'px' => [ 'min' => 0, 'max' => 200 ],
				    'em' => [ 'min' => 0, 'max' => 10 ],
				    'vw' => [ 'min' => 0, 'max' => 20 ],
			    ],
			    'default' => [ 'size' => 40, 'unit' => 'px' ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img img' => 'max-width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'media_margin',
		    [
			    'label' => esc_html__( 'Margin', 'courto-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'devices' => [ 'desktop', 'tablet', 'mobile' ],
			    'condition' => [ 'icon_type!' => '' ],
			    'render_type' => 'template',
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
			    'default' => [
				    'top' => '0',
				    'right' => '10',
				    'bottom' => '0',
				    'left' => '0',
				    'unit'  => 'px',
				    'isLinked' => false
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper' => 'margin: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
				    'body[data-elementor-device-mode="desktop"] {{WRAPPER}}.disable-rotation-yes .media-wrapper,
				     body[data-elementor-device-mode="tablet"] {{WRAPPER}}.disable-rotation-tablet-yes .media-wrapper,
				     body[data-elementor-device-mode="mobile"] {{WRAPPER}}.disable-rotation-mobile-yes .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'media_padding',
		    [
			    'label' => esc_html__( 'Padding', 'courto-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'devices' => [ 'desktop', 'tablet', 'mobile' ],
			    'condition' => [ 'icon_type!' => '' ],
			    'render_type' => 'template',
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
			    'default' => [
				    'top' => '10',
				    'right' => '10',
				    'bottom' => '10',
				    'left' => '10',
				    'unit'  => 'px',
				    'isLinked' => false
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper' => 'padding: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
				    'body[data-elementor-device-mode="desktop"] {{WRAPPER}}.disable-rotation-yes .media-wrapper,
				     body[data-elementor-device-mode="tablet"] {{WRAPPER}}.disable-rotation-tablet-yes .media-wrapper,
				     body[data-elementor-device-mode="mobile"] {{WRAPPER}}.disable-rotation-mobile-yes .media-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'media_border_radius',
		    [
			    'label' => esc_html__( 'Border Radius', 'courto-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'devices' => [ 'desktop', 'tablet', 'mobile' ],
			    'condition' => [ 'icon_type!' => '' ],
			    'render_type' => 'template',
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
			    'default' => [
				    'top' => '30',
				    'right' => '30',
				    'bottom' => '30',
				    'left' => '30',
				    'unit'  => 'px',
				    'isLinked' => false
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper' => 'border-radius: {{LEFT}}{{UNIT}} {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
				    'body[data-elementor-device-mode="desktop"] {{WRAPPER}}.disable-rotation-yes .media-wrapper,
				     body[data-elementor-device-mode="tablet"] {{WRAPPER}}.disable-rotation-tablet-yes .media-wrapper,
				     body[data-elementor-device-mode="mobile"] {{WRAPPER}}.disable-rotation-mobile-yes .media-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'icon_rotation',
		    [
			    'label' => esc_html__( 'Rotation', 'courto-core' ),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'condition' => [ 'icon_type!' => '' ],
			    'size_units' => [ 'deg' ],
			    'range' => [
				    'deg' => [ 'max' => 360 ],
			    ],
			    'default' => [ 'size' => 90, 'unit' => 'deg' ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img img,
				     {{WRAPPER}} .wgl-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
			    ],
		    ]
	    );

	    $this->start_controls_tabs(
		    'tabs_icon'
	    );

	    $this->start_controls_tab(
		    'icon_idle',
		    [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
	    );

	    $this->add_control(
		    'icon_color_idle',
		    [
			    'label' => esc_html__( 'Icon Color', 'courto-core' ),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => [ 'icon_type' => 'font' ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-icon' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->add_control(
		    'icon_bg_color_idle',
		    [
			    'label' => esc_html__( 'Icon Background Color', 'courto-core' ),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => WGL_Globals::get_secondary_color(),
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->end_controls_tab();
	    $this->start_controls_tab(
		    'icon_hover',
		    [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
	    );

	    $this->add_control(
		    'icon_color_hover',
		    [
			    'label' => esc_html__( 'Icon Color', 'courto-core' ),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => [ 'icon_type' => 'font' ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-rotated_text:hover .wgl-icon,
                     {{WRAPPER}} .wgl-rotated_text:focus .wgl-icon' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->add_control(
		    'icon_bg_color_hover',
		    [
			    'label' => esc_html__( 'Icon Background Color', 'courto-core' ),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => WGL_Globals::get_secondary_color('0.5'),
			    'selectors' => [
				    '{{WRAPPER}} .wgl-rotated_text:hover .media-wrapper,
                     {{WRAPPER}} .wgl-rotated_text:focus .media-wrapper' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->end_controls_tab();
	    $this->end_controls_tabs();

	    $this->end_controls_section();

    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

	    if (isset($_s['link']['url']) && !empty($_s['link']['url'])) {
		    $this->add_render_attribute('link', 'class', 'rt__link');
		    $this->add_link_attributes('link', $_s['link']);
	    }

	    // Icon/Image
	    ob_start();
	    if (!empty($_s['icon_type'])) {
		    $icons = new WGL_Icons;
		    echo $icons->build($this, $_s);
	    }
	    $media = ob_get_clean();

	    $this->add_render_attribute( 'heading_wrapper', [ 'class' => [ 'wgl-rotated_text' ] ] );
        ?>
        <div <?php echo $this->get_render_attribute_string('heading_wrapper'); ?>><?php
	        if (isset($_s['link']['url']) && !empty($_s['link']['url'])) echo '<a ', $this->get_render_attribute_string('link'), '></a>';

            if ($_s['icon_type']) {
                echo $media;
            }

            if ($_s['rt_title']) {
                echo '<', $_s['title_tag'], ' class="rt__title-wrapper">';
                    if ($_s['rt_title']) ?><span class="rt__title"><?php echo $_s['rt_title']; ?></span><?php
                echo '</', $_s['title_tag'], '>';
            }

            if ($_s['subtitle']) {
	            ?><div class="rt__subtitle"><?php
                    echo $_s['subtitle'];
	            ?></div><?php
            }?>
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
