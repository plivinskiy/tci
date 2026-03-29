<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-video-hero.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit;

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Embed,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography,
    Group_Control_Background
};

/**
 * Video Hero Widget
 *
 * @package wgl-extensions\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Video_Hero extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-video-hero';
    }

    public function get_title()
    {
        return esc_html__('WGL Video Hero', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-video-popup';
    }

    public function get_keywords()
    {
        return ['video', 'hero', 'youtube', 'vimeo', 'self-hosted', 'lightbox'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return [];
    }

    protected function register_controls()
    {
        /** CONTENT -> GENERAL */
        $this->start_controls_section(
            'content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'video_source',
            [
                'label' => esc_html__('Video Source', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'vimeo' => esc_html__('Vimeo', 'courto-core'),
                    'self_hosted' => esc_html__('Self-Hosted', 'courto-core'),
                ],
                'default' => 'vimeo',
            ]
        );

        $this->add_control(
            'background_video_link',
            [
                'label' => esc_html__('Video Link', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'description' => esc_html__('Enter Vimeo or Self Hosted video.', 'courto-core'),
                'placeholder' => esc_attr__('https://vimeo.com/259976635', 'courto-core'),
                'default' => 'https://vimeo.com/259976635',
                'condition' => ['video_source' => ['vimeo']],
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'background_background',
            [
                'label' => esc_html__('Video', 'courto-core'),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'video',
                'frontend_available' => true,
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'self_hosted_video',
            [
                'label' => esc_html__('Self-Hosted Video', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => ['video'],
                'dynamic' => ['active' => true],
                'condition' => ['video_source' => 'self_hosted'],
                'description' => esc_html__('Select a video file (e.g., MP4) from your media library.', 'courto-core'),
            ]
        );

        $this->add_control(
            'featured_image',
            [
                'label' => esc_html__('Featured Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => ['image'],
                'dynamic' => ['active' => true],
                'description' => esc_html__('Select an image to display when the video is not playing.', 'courto-core'),
            ]
        );

        $this->add_responsive_control(
            'featured_image_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['featured_image[url]!' => ''],
                'options' => [
                    'center center' => esc_html__('Center Center', 'courto-core'),
                    'center left' => esc_html__('Center Left', 'courto-core'),
                    'center right' => esc_html__('Center Right', 'courto-core'),
                    'top center' => esc_html__('Top Center', 'courto-core'),
                    'top left' => esc_html__('Top Left', 'courto-core'),
                    'top right' => esc_html__('Top Right', 'courto-core'),
                    'bottom center' => esc_html__('Bottom Center', 'courto-core'),
                    'bottom left' => esc_html__('Bottom Left', 'courto-core'),
                    'bottom right' => esc_html__('Bottom Right', 'courto-core'),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero.has-featured-image' => 'background-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'featured_image_repeat',
            [
                'label' => esc_html__('Repeat', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['featured_image[url]!' => ''],
                'options' => [
                    'no-repeat' => esc_html__('No-repeat', 'courto-core'),
                    'repeat' => esc_html__('Repeat', 'courto-core'),
                    'repeat-x' => esc_html__('Repeat X', 'courto-core'),
                    'repeat-y' => esc_html__('Repeat Y', 'courto-core'),
                ],
                'default' => 'no-repeat',
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero.has-featured-image' => 'background-repeat: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'featured_image_size',
            [
                'label' => esc_html__('Size', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['featured_image[url]!' => ''],
                'options' => [
                    'cover' => esc_html__('Cover', 'courto-core'),
                    'contain' => esc_html__('Contain', 'courto-core'),
                    'auto' => esc_html__('Auto', 'courto-core'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero.has-featured-image' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_html__('Video Hero Title', 'courto-core'),
                'default' => esc_html__('Watch Us', 'courto-core'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Title Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('https://your-link.com', 'courto-core'),
            ]
        );

        $this->add_control(
            'title_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Description Text', 'courto-core'),
                'label_block' => true,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'courto-core'),
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
                ],
                'default' => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'h_alignment',
            [
                'label' => esc_html__('Horizontal Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .wgl-video-hero' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Text Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'style_transfer' => true,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'plays_by_hover',
            [
                'label' => esc_html__('Works By Hover', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'back_image',
            [
                'label' => esc_html__('Fallback to featured Image', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['plays_by_hover' => 'yes'],
            ]
        );

        $this->add_control(
            'module_link',
            [
                'label' => esc_html__('Show Lightbox on Click', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        /** CONTENT -> BACKGROUND VIDEO */
        $this->start_controls_section(
            'content_background',
            ['label' => esc_html__('Settings', 'courto-core')]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
            ]
        );

        $this->end_controls_section();

        /** STYLE -> CONTAINER */
        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__('Container', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_height',
            [
                'label' => esc_html__('Container Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 1500],
                    'vh' => ['min' => 10, 'max' => 100],
                    '%' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['unit' => 'px', 'size' => 640],
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .wgl-video-hero',
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .wgl-video-hero',
            ]
        );

        $this->add_control(
            'container_video_z_index',
            [
                'label' => esc_html__( 'Video Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero__video' => 'z-index: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'content_z_index',
            [
                'label' => esc_html__( 'Content Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero__content' => 'z-index: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /** CONTENT -> OVERLAY */
        $this->start_controls_section(
            'content_overlay',
            [
                'label' => esc_html__('Overlay', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'enable_overlay',
            [
                'label' => esc_html__('Enable Overlay', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'label' => esc_html__('Overlay Background', 'courto-core'),
                'types' => ['classic', 'gradient'],
                'condition' => ['enable_overlay' => 'yes'],
                'selector' => '{{WRAPPER}} .wgl-video-hero__overlay',
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => esc_html__('Overlay Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['enable_overlay' => 'yes'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'default' => ['size' => 0.8],
                'selectors' => [
                    '{{WRAPPER}} .wgl-video-hero__overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /** STYLE -> TITLE */
        $this->start_controls_section(
            'style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['title_text!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => ['default' => ['size' => 64, 'unit' => 'px']],
                    'font_weight' => ['default' => 800],
                    'letter_spacing' => ['default' => ['size' => -0.04, 'unit' => 'em']],
                ],
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML Tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('h1', 'courto-core'),
                    'h2' => esc_html__('h2', 'courto-core'),
                    'h3' => esc_html__('h3', 'courto-core'),
                    'h4' => esc_html__('h4', 'courto-core'),
                    'h5' => esc_html__('h5', 'courto-core'),
                    'h6' => esc_html__('h6', 'courto-core'),
                    'span' => esc_html__('span', 'courto-core'),
                    'div' => esc_html__('div', 'courto-core'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('title_color_tab');
        $this->start_controls_tab(
            'custom_title_color_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'custom_title_color_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .title' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /** STYLE -> CONTENT */
        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['title_content!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'selector' => '{{WRAPPER}} .content',
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '6',
                    'right' => '0',
                    'bottom' => '30',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('content_color_tab');
        $this->start_controls_tab(
            'custom_content_color_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'custom_content_color_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .content' => 'color: {{VALUE}};'
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
        $widget_id = $this->get_id();

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
            'p' => ['class' => true, 'style' => true]
        ];

        // Prepare video URL for lightbox
        $video_url = '';
        if ($_s['video_source'] === 'self_hosted') {
            $video_url = $_s['self_hosted_video']['url'] ?? '';
        } else {
            $video_url = Embed::get_embed_url($_s['background_video_link']);
        }

        if ($_s['module_link']) {
            $lightbox_options = [
                'type' => 'video',
                'videoType' => $_s['video_source'] === 'self_hosted' ? 'hosted' : $_s['video_source'],
                'url' => $video_url,
                'modalOptions' => [
                    'id' => 'elementor-lightbox-' . $widget_id,
                ],
            ];

            $this->add_render_attribute('lightbox', [
                'class' => 'wgl-video-hero__lightbox lightbox__link',
                'data-elementor-open-lightbox' => 'yes',
                'data-elementor-lightbox' => wp_json_encode($lightbox_options),
            ]);
        }

        $source_type = 'self';
        if ($_s['video_source'] === 'youtube') {
            $source_type = 'youtube';
        } elseif ($_s['video_source'] === 'vimeo') {
            $source_type = 'vimeo';
        }

        $this->add_render_attribute('video-wrap', 'class', [
            'wgl-video-hero',
            $_s['enable_overlay'] === 'yes' ? 'has-overlay' : '',
            $_s['plays_by_hover'] === 'yes' ? 'plays-hover' : '',
            $_s['plays_by_hover'] === 'yes' && $_s['back_image'] === 'yes' ? 'back-image' : '',
            !empty($_s['featured_image']['url']) ? 'has-featured-image' : '',
        ]);

        $this->add_render_attribute('video-wrap', 'id', 'wgl-video-hero-' . $widget_id);

        // Add custom CSS for the featured image
        if (!empty($_s['featured_image']['url'])) {
            $this->add_render_attribute('video-wrap', 'style', sprintf(
                'background-image: url(%s);',
                esc_url($_s['featured_image']['url'])
            ));
        }

        $data_attrs = [
            'data-source-type' => $source_type,
            'data-autoplay' => 0,
            'data-mute' => 1,
            'data-loop' => $_s['loop'] === 'yes' ? 1 : 0,
            'data-link' => esc_url($video_url),
        ];

        $video_id = '';
        if ($_s['video_source'] === 'youtube') {
            preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $_s['background_video_link'], $matches);
            $video_id = $matches[1] ?? '';
        } elseif ($_s['video_source'] === 'vimeo') {
            preg_match('/vimeo\.com\/([0-9]+)/', $_s['background_video_link'], $matches);
            $video_id = $matches[1] ?? '';
        }

        if (!empty($video_id)) {
            $data_attrs['data-video-id'] = $video_id;
        }

        foreach ($data_attrs as $k => $v) {
            $this->add_render_attribute('video-wrap', $k, esc_attr($v));
        }

        $this->add_render_attribute('video-content', [
            'class' => 'wgl-video-hero__content videobox_content',
        ]);

        $background_video_html = '';

        if ($_s['video_source'] === 'self_hosted' && !empty($_s['self_hosted_video']['url'])) {
            $background_video_html = sprintf(
                '<video id="video-%1$s" class="wgl-video elementor-background-video-hosted" playsinline preload="auto">' .
                '<source src="%2$s" type="video/mp4">' .
                '</video>',
                esc_attr($widget_id),
                esc_url($_s['self_hosted_video']['url'])
            );
        } elseif ($_s['video_source'] === 'youtube' || $_s['video_source'] === 'vimeo') {
            $background_video_html = sprintf(
                '<div id="video-%1$s" class="wgl-video-iframe elementor-background-video-embed" aria-hidden="true"></div>',
                esc_attr($widget_id)
            );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('video-wrap'); ?>>
            <?php if ($background_video_html) : ?>
                <div class="wgl-video-hero__video elementor-background-video-container">
                    <?php echo $background_video_html; ?>
                </div>
            <?php endif; ?>
            <?php if ($_s['enable_overlay'] === 'yes') : ?>
                <div class="wgl-video-hero__overlay"></div>
            <?php endif; ?>

            <div <?php echo $this->get_render_attribute_string('video-content'); ?>>
                <?php if ($_s['title_text']) : ?>
                    <?php
                    if (!empty($_s['link']['url'])) {
                        $this->add_render_attribute('link', 'class', 'title_link');
                        $this->add_link_attributes('link', $_s['link']);
                        echo '<a ', $this->get_render_attribute_string('link'), '>';
                    }
                    ?>
                    <<?php echo esc_attr($_s['title_tag']); ?> class="title">
                        <?php echo esc_html($_s['title_text']); ?>
                    </<?php echo esc_attr($_s['title_tag']); ?>>
                    <?php
                    if (!empty($_s['link']['url'])) {
                        echo '</a>';
                    }
                    ?>
                <?php endif; ?>
                <?php if ($_s['title_content']) : ?>
                    <div class="content">
                        <?php
                        echo wp_kses($_s['title_content'], $kses_allowed_html);
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($_s['module_link']) : ?>
                <div <?php echo $this->get_render_attribute_string('lightbox'); ?>></div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function wpml_support_module()
    {
        add_filter('wpml_elementor_widgets_to_translate', [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter($widgets)
    {
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate($this, $widgets);
    }
}