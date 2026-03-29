<?php

if (!class_exists('RWMB_Loader')) return;

use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class Courto_Metaboxes
{
    public function __construct()
    {
        // General
        add_filter( 'rwmb_meta_boxes', [ $this, 'general_meta_boxes' ] );

        // Team
        add_filter( 'rwmb_meta_boxes', [ $this, 'team_meta_boxes' ] );

        // Portfolio
        add_filter( 'rwmb_meta_boxes', [ $this, 'portfolio_meta_boxes' ] );
        add_filter( 'rwmb_meta_boxes', [ $this, 'portfolio_post_settings_meta_boxes' ] );
        add_filter( 'rwmb_meta_boxes', [ $this, 'portfolio_related_meta_boxes' ] );

        // Blog
        add_filter( 'rwmb_meta_boxes', [ $this, 'blog_settings_meta_boxes' ] );
        add_filter( 'rwmb_meta_boxes', [ $this, 'blog_meta_boxes' ] );
        add_filter( 'rwmb_meta_boxes', [ $this, 'blog_related_meta_boxes' ] );

        // Page
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_layout_meta_boxes' ] );

        // Colors
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_color_meta_boxes' ] );

        // Header Builder
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_header_meta_boxes' ] );

        // Title
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_title_meta_boxes' ] );

        // Side Panel
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_side_panel_meta_boxes' ] );

        // Social Shares
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_soc_icons_meta_boxes' ] );

        // Footer
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_footer_meta_boxes' ] );

        // Copyright
        add_filter( 'rwmb_meta_boxes', [ $this, 'page_copyright_meta_boxes' ] );
    }

    public function general_meta_boxes( $meta_boxes )
    {
        $meta_boxes[] = [
            'title' => esc_html__('General', 'courto'),
            'post_types' => ['page' , 'post', 'team', 'portfolio', 'product'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_body_switch',
                    'name' => esc_html__( 'Body Styles', 'courto' ),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_body_color_bg',
                    'name' => esc_html__('Body Background', 'courto'),
                    'type' => 'wgl_background',
                    'image' => '',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_body_switch', '=', 'on']
                        ]],
                    ],
                    'repeat' => esc_attr(WGL_Framework::get_option('body_color_bg')['background-repeat'] ?? ''),
                    'size' => esc_attr(WGL_Framework::get_option('body_color_bg')['background-size'] ?? ''),
                    'attachment' => esc_attr(WGL_Framework::get_option('body_color_bg')['background-attachment'] ?? ''),
                    'position' => esc_attr(WGL_Framework::get_option('body_color_bg')['background-position'] ?? ''),
                    'color' => esc_attr(WGL_Framework::get_option('body_color_bg')['background-color'] ?? ''),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_body_lines_switch',
                    'name' => esc_html__( 'Body Lines', 'courto' ),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_body_lines_color',
                    'name' => esc_html__( 'Lines Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_body_lines_switch', '=', 'on'],
                        ]],
                    ],
                    'validate' => 'color',
                    'alpha_channel' => true,
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('body_lines_color')['rgba'] ?? '')],
                    'std' => esc_attr(WGL_Framework::get_option('body_lines_color')['rgba'] ?? ''),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_overlay_full',
                    'name' => esc_html__( 'Cart Full Page Overlay', 'courto' ),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_cart_overlay_color',
                    'name' => esc_html__( 'Cart Overlay Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_overlay_full', '=', 'on'],
                        ]],
                    ],
                    'validate' => 'color',
                    'alpha_channel' => true,
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('cart_overlay_color')['rgba'] ?? '')],
                    'std' => esc_attr(WGL_Framework::get_option('cart_overlay_color')['rgba'] ?? ''),
                    'hide_from_rest' => true
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function team_meta_boxes( $meta_boxes )
    {
        $meta_boxes[] = [
            'title' => esc_html__('Team Options', 'courto'),
            'post_types' => ['team'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'highlighted_info',
                    'name' => esc_html__('Highlighted Info', 'courto'),
                    'type' => 'text',
                    'class' => 'field-inputs'
                ],
                [
                    'id' => 'info_items',
                    'name' => esc_html__('Member Info', 'courto'),
                    'type' => 'social',
                    'clone' => true,
                    'sort_clone' => true,
                    'options' => [
                        'name' => [
                            'name' => esc_html__('Name', 'courto'),
                            'type_input' => 'text'
                        ],
                        'description' => [
                            'name' => esc_html__('Description', 'courto'),
                            'type_input' => 'text'
                        ],
                        'link' => [
                            'name' => esc_html__('Link', 'courto'),
                            'type_input' => 'text'
                        ],
                    ],
                ],
                [
                    'id' => 'soc_icon',
                    'name' => esc_html__('Member Socials', 'courto'),
                    'type' => 'select_icon',
                    'placeholder' => esc_attr__('Select an icon', 'courto'),
                    'clone' => true,
                    'sort_clone' => true,
                    'multiple' => false,
                    'options' => WGLAdminIcon()->get_icons_name(),
                    'std' => 'default',
                ],
                [
                    'id' => 'soc_icon_target',
                    'name' => esc_html__('Open social link in a new tab', 'courto'),
                    'type' => 'switch',
                ],
                [
                    'id' => 'info_bg_color',
                    'name' => esc_html__('Info Background Color', 'courto'),
                    'type' => 'color',
                    'validate' => 'color',
                    'std' => '',
                ],
                [
                    'id' => 'mb_info_bg',
                    'name' => esc_html__('Info Background Image', 'courto'),
                    'type' => 'file_advanced',
                    'mime_type' => 'image',
                    'max_file_uploads' => 1,
                ],
                [
                    'id'      => 'mb_team_single_render_output',
                    'name'    => esc_html__( 'Elementor Content Position', 'courto' ),
                    'type'    => 'select',
                    'desc'    => esc_html__( 'Select where the Elementor content should be displayed', 'courto' ),
                    'options' => [
                        'default' => esc_html__('Inherit from Theme options', 'courto'),
                        'sidebar' => esc_html__('In Sidebar', 'courto'),
                        'below_meta' => esc_html__('Below Meta Information', 'courto'),
                    ],
                    'std'      => 'default',
                ],
                [
                    'id' => 'mb_team_single_sticky_image',
                    'name' => esc_html__('Sticky Image', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'yes' => esc_html__( 'Enable', 'courto' ),
                        'no' => esc_html__( 'Disable', 'courto' ),
                    ],
                     'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_team_single_render_output', '=', 'sidebar']
                        ]],
                    ],
                    'std' => 'default',
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function portfolio_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Portfolio Options', 'courto'),
            'post_types' => ['portfolio'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_portfolio_featured_image_conditional',
                    'name' => esc_html__('Featured Image', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_featured_image_type',
                    'name' => esc_html__('Featured Image Settings', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                                ['mb_portfolio_featured_image_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'off' => esc_html__('Off', 'courto'),
                        'replace' => esc_html__('Replace', 'courto'),
                    ],
                    'std' => 'off',
                ],
                [
                    'id' => 'mb_portfolio_featured_image_replace',
                    'name' => esc_html__('Featured Image Replace', 'courto'),
                    'type' => 'image_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_featured_image_conditional', '=', 'custom'],
                            ['mb_portfolio_featured_image_type', '=', 'replace'],
                        ]],
                    ],
                    'max_file_uploads' => 1,
                ],
                [
                    'id' => 'mb_portfolio_title',
                    'name' => esc_html__('Show Title on single', 'courto'),
                    'type' => 'switch',
                    'std' => 'true',
                ],
                [
                    'id' => 'mb_portfolio_link',
                    'name' => esc_html__('Add Custom Link for Portfolio Grid', 'courto'),
                    'type' => 'switch',
                ],
                [
                    'id' => 'portfolio_custom_url',
                    'name' => esc_html__('Custom Url for Portfolio Grid', 'courto'),
                    'type' => 'text',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_link', '=', '1']
                        ]],
                    ],
                    'class' => 'field-inputs',
                ],
                [
                    'id' => 'mb_portfolio_video',
                    'name' => esc_html__('Add Video for Portfolio Grid', 'courto'),
                    'type' => 'switch',
                ],
                [
                    'id'      => 'video_source',
                    'name'    => esc_html__( 'Video Source', 'courto' ),
                    'type'    => 'select',
                    'options' => [
                        'vimeo'       => esc_html__( 'Vimeo', 'courto' ),
                        'self_hosted' => esc_html__( 'Self Hosted', 'courto' ),
                    ],
                    'std'      => 'vimeo',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_video', '=', '1']
                        ]],
                    ],
                ],
                [
                    'id'       => 'vimeo_video_url',
                    'name'     => esc_html__( 'Vimeo Video URL', 'courto' ),
                    'type'     => 'url',
                    'desc'     => esc_html__( 'Enter the Vimeo video URL (e.g. https://vimeo.com/12345678)', 'courto' ),
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_video', '=', '1'],
                            [ 'video_source', '=', 'vimeo' ],
                        ]],
                    ],
                ],
                [
                    'id'       => 'self_hosted_video',
                    'name'     => esc_html__( 'Self Hosted Video', 'courto' ),
                    'type'     => 'file_input', 
                    'desc'     => esc_html__( 'Upload or select a self-hosted video (MP4, MOV, etc.)', 'courto' ),
                    'visible'  => [ 'video_source', '=', 'self_hosted' ],
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_video', '=', '1'],
                            [ 'video_source', '=', 'self_hosted' ],
                        ]],
                    ],
                ],             
                [
                    'id' => 'mb_portfolio_single_meta_categories',
                    'name' => esc_html__('Categories', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'yes' => esc_html__( 'Enable', 'courto' ),
                        'no' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_single_meta_date',
                    'name' => esc_html__('Date', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'yes' => esc_html__( 'Enable', 'courto' ),
                        'no' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_above_content_cats',
                    'name' => esc_html__('Tags', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'yes' => esc_html__( 'Enable', 'courto' ),
                        'no' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_above_content_share',
                    'name' => esc_html__('Share Links', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'yes' => esc_html__( 'Enable', 'courto' ),
                        'no' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function portfolio_post_settings_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Portfolio Post Settings', 'courto'),
            'post_types' => ['portfolio'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_portfolio_post_conditional',
                    'name' => esc_html__('Post Layout', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Post Layout Settings', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_post_conditional', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_portfolio_single_type_layout',
                    'name' => esc_html__('Layout', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_post_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '1' => esc_html__('Title First', 'courto'),
                        '2' => esc_html__('Image First', 'courto'),
                    ],
                    'std' => '2',
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function portfolio_related_meta_boxes( $meta_boxes )
    {
        $meta_boxes[] = [
            'title' => esc_html__( 'Related Portfolio', 'courto' ),
            'post_types' => [ 'portfolio' ],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_portfolio_related_switch',
                    'name' => esc_html__( 'Portfolio Related', 'courto' ),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default'
                ],
                [
                    'name' => esc_html__( 'Portfolio Related Settings', 'courto' ),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_portfolio_related_switch', '=', 'on' ]
                        ] ],
                    ],
                ],
                [
                    'id' => 'mb_pf_carousel_r',
                    'name' => esc_html__( 'Display items withiin carousel for this post', 'courto' ),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_portfolio_related_switch', '=', 'on' ]
                        ] ],
                    ],
                    'std' => 1,
                ],
                [
                    'id' => 'mb_portfolio_related_title',
                    'name' => esc_html__( 'Title', 'courto' ),
                    'type' => 'text',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_portfolio_related_switch', '=', 'on' ]
                        ] ],
                    ],
                    'std' => esc_html( WGL_Framework::get_option( 'portfolio_related_title' ) ),
                ],
                [
                    'id' => 'mb_pf_cat_r',
                    'name' => esc_html__( 'Categories', 'courto' ),
                    'type' => 'taxonomy_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_portfolio_related_switch', '=', 'on' ]
                        ] ],
                    ],
                    'multiple' => true,
                    'taxonomy' => 'portfolio-category',
                ],
                [
                    'id' => 'mb_pf_column_r',
                    'name' => esc_html__( 'Columns', 'courto' ),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_portfolio_related_switch', '=', 'on' ]
                        ] ],
                    ],
                    'multiple' => false,
                    'options' => [
                        '2' => esc_html__( '2', 'courto' ),
                        '3' => esc_html__( '3', 'courto' ),
                        '4' => esc_html__( '4', 'courto' ),
                    ],
                    'std' => '3',
                ],
                [
                    'id' => 'mb_pf_number_r',
                    'name' => esc_html__( 'Number of Related Items', 'courto' ),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            ['mb_portfolio_related_switch', '=', 'on']
                        ] ],
                    ],
                    'min' => 0,
                    'step' => 1,
                    'std' => 3,
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function blog_settings_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Post Settings', 'courto'),
            'post_types' => ['post'],
            'context' => 'advanced',
            'fields' => [
                [
                    'name' => esc_html__('Post Layout Settings', 'courto'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'mb_post_layout_conditional',
                    'name' => esc_html__('Post Layout', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_post_single_type_layout',
                    'name' => esc_html__('Post Layout Type', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_post_layout_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '1' => esc_html__('Title First', 'courto'),
                        '2' => esc_html__('Image First', 'courto'),
                        '3' => esc_html__('Overlay Image', 'courto'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('post_single_type_layout')),
                ],
                [
                    'id' => 'mb_single_padding_layout_3',
                    'name' => esc_html__('Padding Top/Bottom', 'courto'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_post_layout_conditional', '=', 'custom'],
                            ['mb_post_single_type_layout', '=', '3'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr(WGL_Framework::get_option('single_padding_layout_3')['padding-top']),
                        'padding-bottom' => esc_attr(WGL_Framework::get_option('single_padding_layout_3')['padding-bottom']),
                    ],
                ],
                [
                    'id' => 'mb_single_apply_animation',
                    'name' => esc_html__('Apply Animation', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_post_layout_conditional', '=', 'custom'],
                            ['mb_post_single_type_layout', '=', '3'],
                        ]],
                    ],
                    'std' => 1,
                ],
                [
                    'name' => esc_html__('Featured Image Settings', 'courto'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'mb_featured_image_conditional',
                    'name' => esc_html__('Featured Image', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_featured_image_type',
                    'name' => esc_html__('Featured Image Settings', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_featured_image_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'off' => esc_html__('Off', 'courto'),
                        'replace' => esc_html__('Replace', 'courto'),
                    ],
                    'std' => 'off',
                ],
                [
                    'id' => 'mb_featured_image_replace',
                    'name' => esc_html__('Featured Image Replace', 'courto'),
                    'type' => 'image_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_featured_image_conditional', '=', 'custom'],
                            ['mb_featured_image_type', '=', 'replace'],
                        ]],
                    ],
                    'max_file_uploads' => 1,
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function blog_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Post Format Layout', 'courto'),
            'post_types' => ['post'],
            'context' => 'advanced',
            'fields' => [
                // Standard Post Format
                [
                    'id' => 'post_format_standard',
                    'name' => esc_html__('Standard Post( Enabled only Featured Image for this post format)', 'courto'),
                    'type' => 'static-text',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['formatdiv', '=', '0']
                        ]],
                    ],
                ],
                // Gallery Post Format
                [
                    'name' => esc_html__('Gallery Settings', 'courto'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_gallery',
                    'name' => esc_html__('Add Images', 'courto'),
                    'type' => 'image_advanced',
                    'max_file_uploads' => '',
                ],
                // Video Post Format
                [
                    'name' => esc_html__('Video Settings', 'courto'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_video_style',
                    'name' => esc_html__('Video Style', 'courto'),
                    'type' => 'select',
                    'multiple' => false,
                    'options' => [
                        'bg_video' => esc_html__('Background Video', 'courto'),
                        'popup' => esc_html__('Popup', 'courto'),
                    ],
                    'std' => 'bg_video',
                ],
                [
                    'id' => 'start_video',
                    'name' => esc_html__('Start Video', 'courto'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['post_format_video_style', '=', 'bg_video'],
                        ]],
                    ],
                    'std' => '0',
                ],
                [
                    'id' => 'end_video',
                    'name' => esc_html__('End Video', 'courto'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['post_format_video_style', '=', 'bg_video'],
                        ]],
                    ],
                ],
                [
                    'id' => 'post_format_video_url',
                    'name' => esc_html__('oEmbed URL', 'courto'),
                    'type' => 'oembed',
                ],
                // Quote Post Format
                [
                    'name' => esc_html__('Quote Settings', 'courto'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_qoute_text',
                    'name' => esc_html__('Quote Text', 'courto'),
                    'type' => 'textarea',
                ],
                [
                    'id' => 'post_format_qoute_name',
                    'name' => esc_html__('Author Name', 'courto'),
                    'type' => 'text',
                ],
                [
                    'id' => 'post_format_qoute_position',
                    'name' => esc_html__('Author Position', 'courto'),
                    'type' => 'text',
                ],
                [
                    'id' => 'post_format_qoute_avatar',
                    'name' => esc_html__('Author Avatar', 'courto'),
                    'type' => 'image_advanced',
                    'max_file_uploads' => 1,
                ],
                // Audio Post Format
                [
                    'name' => esc_html__('Audio Settings', 'courto'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_audio_url',
                    'name' => esc_html__('oEmbed URL', 'courto'),
                    'type' => 'oembed',
                ],
                // Link Post Format
                [
                    'name' => esc_html__('Link Settings', 'courto'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_link_url',
                    'name' => esc_html__('URL', 'courto'),
                    'type' => 'url',
                ],
                [
                    'id' => 'post_format_link_text',
                    'name' => esc_html__('Text', 'courto'),
                    'type' => 'text',
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function blog_related_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Related Blog Post', 'courto'),
            'post_types' => ['post'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_blog_show_r',
                    'name' => esc_html__( 'Related Posts', 'courto' ),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'custom' => esc_html__( 'Custom', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Related Settings', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_blog_title_r',
                    'name' => esc_html__('Title', 'courto'),
                    'type' => 'text',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'std' => esc_html__('Related Posts', 'courto'),
                ],
                [
                    'id' => 'mb_blog_cat_r',
                    'name' => esc_html__('Categories', 'courto'),
                    'type' => 'taxonomy_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'multiple' => true,
                    'taxonomy' => 'category',
                ],
                [
                    'id' => 'mb_blog_column_r',
                    'name' => esc_html__('Columns', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '12' => esc_html__('1', 'courto'),
                        '6' => esc_html__('2', 'courto'),
                        '4' => esc_html__('3', 'courto'),
                        '3' => esc_html__('4', 'courto'),
                    ],
                    'std' => '6',
                ],
                [
                    'name' => esc_html__('Number of Related Items', 'courto'),
                    'id' => 'mb_blog_number_r',
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'min' => 0,
                    'std' => 2,
                ],
                [
                    'id' => 'mb_blog_carousel_r',
                    'name' => esc_html__('Display items carousel for this blog post', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'std' => 1,
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function page_layout_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Page Sidebar Layout', 'courto'),
            'post_types' => ['page', 'post', 'team', 'portfolio', 'product'],
            'context' => 'advanced',
            'fields' => [
                [
                    'name' => esc_html__('Page Sidebar Layout', 'courto'),
                    'id' => 'mb_page_sidebar_layout',
                    'type' => 'wgl_image_select',
                    'options' => [
                        'default' => get_template_directory_uri() . '/core/admin/img/options/1c.png',
                        'none' => get_template_directory_uri() . '/core/admin/img/options/none.png',
                        'left' => get_template_directory_uri() . '/core/admin/img/options/2cl.png',
                        'right' => get_template_directory_uri() . '/core/admin/img/options/2cr.png',
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Sidebar Settings', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_sidebar_def',
                    'name' => esc_html__('Page Sidebar', 'courto'),
                    'type' => 'select',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                    'placeholder' => esc_html__('Select a Sidebar', 'courto'),
                    'multiple' => false,
                    'options' => courto_get_all_sidebars(),
                ],
                [
                    'id' => 'mb_page_sidebar_def_width',
                    'name' => esc_html__('Page Sidebar Width', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '9' => esc_html( '25%' ),
                        '8' => esc_html( '33%' ),
                    ],
                    'std' => '9',
                ],
                [
                    'id' => 'mb_sticky_sidebar',
                    'name' => esc_html__( 'Sticky Sidebar?', 'courto' ),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_sidebar_gap',
                    'name' => esc_html__( 'Sidebar Side Gap', 'courto' ),
                    'type' => 'select',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_sidebar_layout', '!=', 'default' ],
                            [ 'mb_page_sidebar_layout', '!=', 'none' ],
                        ] ],
                    ],
                    'multiple' => false,
                    'options' => [
                        'def' => esc_html__( 'Default', 'courto' ),
                        '0' => esc_html( '15' ),
	                    '15' => esc_html( '30' ),
	                    '20' => esc_html( '35' ),
	                    '25' => esc_html( '40' ),
	                    '30' => esc_html( '45' ),
	                    '35' => esc_html( '50' ),
	                    '40' => esc_html( '55' ),
	                    '45' => esc_html( '60' ),
                    ],
                    'std' => '40',
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_color_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Page Colors', 'courto'),
            'post_types' => ['page' , 'post', 'team', 'portfolio'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_page_colors_switch',
                    'name' => esc_html__('Page Colors', 'courto'),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_theme-primary-color',
                    'name' => esc_html__('Primary Theme Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Globals::get_primary_color() ) ],
                    'std' => esc_attr( WGL_Globals::get_primary_color() ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_theme-secondary-color',
                    'name' => esc_html__('Secondary Theme Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Globals::get_secondary_color() ) ],
                    'std' => esc_attr( WGL_Globals::get_secondary_color() ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_theme-tertiary-color',
                    'name' => esc_html__('Tertiary Theme Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Globals::get_tertiary_color() ) ],
                    'std' => esc_attr( WGL_Globals::get_tertiary_color() ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_theme-quaternary-color',
                    'name' => esc_html__('Quaternary Theme Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Globals::get_quaternary_color() ) ],
                    'std' => esc_attr( WGL_Globals::get_quaternary_color() ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_theme-content-color',
                    'name' => esc_html__( 'Content Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Globals::get_main_font_color() ) ],
                    'std' => esc_attr( WGL_Globals::get_main_font_color() ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_theme-headings-color',
                    'name' => esc_html__( 'Headings Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            [ 'mb_page_colors_switch', '=', 'custom' ],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Globals::get_h_font_color() ) ],
                    'std' => esc_attr( WGL_Globals::get_h_font_color() ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_form-bg-color',
                    'name' => esc_html__('Comments Form Background', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'form-bg-color' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'form-bg-color' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_form-border-color',
                    'name' => esc_html__('Comments Form Border Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'form-border-color' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'form-border-color' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_button-color-idle',
                    'name' => esc_html__('Button Color Idle', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'button-color-idle' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'button-color-idle' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_button-bg-idle',
                    'name' => esc_html__( 'Button Background Idle', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'button-bg-idle' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'button-bg-idle' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_button-border-idle',
                    'name' => esc_html__( 'Button Border Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'button-border-idle' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'button-border-idle' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_button-color-hover',
                    'name' => esc_html__('Button Color Hover', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'button-color-hover' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'button-color-hover' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_button-bg-hover',
                    'name' => esc_html__( 'Button Background Hover', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_colors_switch', '=', 'custom' ],
                        ] ],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'button-bg-hover' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'button-bg-hover' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_button-border-hover',
                    'name' => esc_html__( 'Button Border Color Hover', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_colors_switch', '=', 'custom' ],
                        ] ],
                    ],
                    'validate' => 'color',
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'button-border-hover' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'button-border-hover' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'name' => esc_html__( 'Back to Top', 'courto' ),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_colors_switch', '=', 'custom' ]
                        ] ],
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_scroll_up_arrow_color',
                    'name' => esc_html__('Button Arrow Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color'))],
                    'std' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color')),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_scroll_up_arrow_color_bg',
                    'name' => esc_html__('Button Arrow Background Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color_bg'))],
                    'std' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color_bg')),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_scroll_up_arrow_color_border',
                    'name' => esc_html__('Button Arrow Background Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color_border'))],
                    'std' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color_border')),
                    'hide_from_rest' => true
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_header_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Header', 'courto'),
            'post_types' => ['page', 'post', 'portfolio', 'product'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_customize_header_layout',
                    'name' => esc_html__( 'Header Settings', 'courto' ),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto'),
                        'custom' => esc_html__( 'Custom', 'courto' ),
                        'hide' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_header_content_type',
                    'name' => esc_html__('Header Template', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto')
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_customize_header',
                    'name' => esc_html__('Template', 'courto'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_header_content_type', '=', 'custom'],
                        ]],
                    ],
                    'post_type' => 'header',
                    'multiple' => false,
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_header_sticky',
                    'name' => esc_html__('Sticky Header', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom']
                        ]],
                    ],
                    'std' => 1,
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_sticky_header_content_type',
                    'name' => esc_html__('Sticky Header Template', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_header_sticky', '=', '1'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto')
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_customize_sticky_header',
                    'name' => esc_html__('Template', 'courto'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_sticky_header_content_type', '=', 'custom'],
                            ['mb_header_sticky', '=', '1'],
                        ]],
                    ],
                    'multiple' => false,
                    'post_type' => 'header',
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_mobile_menu_custom',
                    'name' => esc_html__('Mobile Menu Template', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto')
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_mobile_menu_header',
                    'name' => esc_html__('Mobile Menu ', 'courto'),
                    'type' => 'select',
                    'attributes' => [
                        'data-conditional-logic'  =>  [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_mobile_menu_custom', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => $menus = wgl_get_custom_menu(),
                    'default' => reset($menus),
                    'hide_from_rest' => true
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_title_meta_boxes( $meta_boxes )
    {
        $meta_boxes[] = [
            'title' => esc_html__( 'Page Title', 'courto' ),
            'post_types' => [ 'page', 'post', 'team', 'portfolio', 'product' ],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_page_title_switch',
                    'name' => esc_html__( 'Page Title', 'courto' ),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'name' => esc_html__('Page Title Settings', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_bg_switch',
                    'name' => esc_html__('Use Background Image/Color?', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'std' => true,
                ],
                [
                    'id' => 'mb_page_title_tag',
                    'name' => esc_html__('Title HTML tag', 'courto'),
                    'type' => 'select',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'def' => 'Theme Default',
                        'div' => '‹div›',
                        'h1' => '‹h1›',
                        'h2' => '‹h2›',
                        'h3' => '‹h3›',
                        'h4' => '‹h4›',
                        'h5' => '‹h5›',
                        'h6' => '‹h6›',
                    ],
                    'default' => 'def'
                ],
                [
                    'id' => 'mb_page_title_bg',
                    'name' => esc_html__('Background', 'courto'),
                    'type' => 'wgl_background',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_title_switch', '=', 'on' ],
                            [ 'mb_page_title_bg_switch', '=', true ],
                        ] ],
                    ],
                    'image' => '',
                    'repeat' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-repeat'] ?? ''),
                    'size' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-size'] ?? ''),
                    'attachment' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-attachment'] ?? ''),
                    'position' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-position'] ?? ''),
                    'color' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-color'] ?? ''),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_border',
                    'name' => esc_html__('Border', 'courto'),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_title_switch', '=', 'on' ],
                            [ 'mb_page_title_bg_switch', '=', true ],
                        ] ],
                    ],
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_height',
                    'name' => esc_html__('Min Height', 'courto'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_bg_switch', '=', true],
                        ]],
                    ],
                    'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'courto'),
                    'min' => 0,
                    'std' => esc_attr((int) WGL_Framework::get_option('page_title_height')['height']),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_align',
                    'name' => esc_html__('Title Alignment', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('left', 'courto'),
                        'center' => esc_html__('center', 'courto'),
                        'right' => esc_html__('right', 'courto'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('page_title_align')),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_padding',
                    'name' => esc_html__('Paddings Top/Bottom', 'courto'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr((int) WGL_Framework::get_option('page_title_padding')['padding-top'] ?? ''),
                        'padding-bottom' => esc_attr((int) WGL_Framework::get_option('page_title_padding')['padding-bottom'] ?? ''),
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_margin',
                    'name' => esc_html__('Margin Bottom', 'courto'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'mode' => 'margin',
                        'top' => false,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => ['margin-bottom' => esc_attr((int) WGL_Framework::get_option('page_title_margin')['margin-bottom'] ?? '')],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_parallax',
                    'name' => esc_html__('Parallax Switch', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_parallax_speed',
                    'name' => esc_html__('Parallax Speed', 'courto'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_parallax', '=', true],
                            ['mb_page_title_switch', '=', 'on'],
                        ]],
                    ],
                    'step' => 0.1,
                    'std' => 0.3,
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_switch',
                    'name' => esc_html__('Show Breadcrumbs', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'std' => esc_attr( WGL_Framework::get_option( 'page_title_breadcrumbs_switch' ) ),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_align',
                    'name' => esc_html__('Breadcrumbs Alignment', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_title_switch', '=', 'on' ],
                            [ 'mb_page_title_breadcrumbs_switch', '=', true ]
                        ] ],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('left', 'courto'),
                        'center' => esc_html__('center', 'courto'),
                        'right' => esc_html__('right', 'courto'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_align')),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_block_switch',
                    'name' => esc_html__('Breadcrumbs Full Width', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_page_title_switch', '=', 'on' ],
                            [ 'mb_page_title_breadcrumbs_switch', '=', true ]
                        ] ],
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_block_switch')),
                    'hide_from_rest' => true
                ],
                [
                    'name' => esc_html__('Page Title Typography', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_font',
                    'name' => esc_html__('Page Title Font', 'courto'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                        'letter-spacing' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_font')['font-size'] ?? ''),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_font')['line-height'] ?? ''),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_font')['color'] ?? ''),
                        'letter-spacing' => esc_attr(WGL_Framework::get_option('page_title_font')['letter-spacing'] ?? ''),
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_font',
                    'name' => esc_html__('Page Title Breadcrumbs Font', 'courto'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                        'letter-spacing' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['font-size']),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['line-height']),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_font')['color']),
                        'letter-spacing' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_font')['letter-spacing'] ?? ''),
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'name' => esc_html__('Responsive Layout', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_resp_switch',
                    'name' => esc_html__('Responsive Layout On/Off', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_resp_resolution',
                    'name' => esc_html__('Screen breakpoint', 'courto'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'min' => 1,
                    'std' => esc_attr(WGL_Framework::get_option('page_title_resp_resolution')),
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_resp_padding',
                    'name' => esc_html__('Padding Top/Bottom', 'courto'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr( (int) WGL_Framework::get_option( 'page_title_resp_padding' )[ 'padding-top' ] ?? '' ),
                        'padding-bottom' => esc_attr( (int) WGL_Framework::get_option( 'page_title_resp_padding' )[ 'padding-bottom' ] ?? '' ),
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_resp_margin',
                    'name' => esc_html__('Margin Bottom', 'courto'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'margin',
                        'top' => false,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr( (int) WGL_Framework::get_option( 'page_title_resp_margin' )[ 'margin-bottom' ] ?? '' ),
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_resp_font',
                    'name' => esc_html__('Page Title Font', 'courto'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_resp_font')['font-size']),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_resp_font')['line-height']),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_resp_font')['color']),
                    ],
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_resp_breadcrumbs_switch',
                    'name' => esc_html__('Show Breadcrumbs', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'std' => 1,
                    'hide_from_rest' => true
                ],
                [
                    'id' => 'mb_page_title_resp_breadcrumbs_font',
                    'name' => esc_html__('Page Title Breadcrumbs Font', 'courto'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                            ['mb_page_title_resp_breadcrumbs_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['font-size']),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['line-height']),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_font')['color']),
                    ],
                    'hide_from_rest' => true
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function page_side_panel_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Side Panel', 'courto'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_customize_side_panel',
                    'name' => esc_html__('Side Panel', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'inline' => true,
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'custom' => esc_html__('Custom', 'courto'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Side Panel Settings', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_side_panel_building_tool',
                    'name' => esc_html__('Content Type', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'widgets' => esc_html__('Wordpress Widgets', 'courto'),
                        'elementor' => esc_html__('Elementor', 'courto')
                    ],
                    'std' => 'widgets',
                ],
                [
                    'id' => 'mb_side_panel_page_select',
                    'name' => esc_html__('Select a page', 'courto'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'elementor'],
                        ]],
                    ],
                    'post_type' => 'side_panel',
                    'field_type' => 'select_advanced',
                    'placeholder' => esc_html__('Select a page', 'courto'),
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                ],
                [
                    'id' => 'mb_side_panel_spacing',
                    'name' => esc_html__( 'Margins', 'courto' ),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'margin',
                        'top' => true,
                        'right' => true,
                        'bottom' => true,
                        'left' => true,
                    ],
                    'std' => [
                        'margin-top' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-top'] ?? ''),
                        'margin-right' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-right'] ?? ''),
                        'margin-bottom' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-bottom'] ?? ''),
                        'margin-left' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-left'] ?? ''),
                    ],
                ],
                [
                    'id' => 'mb_side_panel_title_color',
                    'name' => esc_html__('Title Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('side_panel_title_color'))],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_title_color')),
                ],
                [
                    'id' => 'mb_side_panel_text_color',
                    'name' => esc_html__( 'Text Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_customize_side_panel', '=', 'custom' ],
                            [ 'mb_side_panel_building_tool', '=', 'widgets' ],
                        ] ],
                    ],
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Globals::get_h_font_color() ) ],
                    'std' => esc_attr( WGL_Globals::get_h_font_color() ),
                ],
                [
                    'id' => 'mb_side_panel_bg',
                    'name' => esc_html__('Background Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'alpha_channel' => true,
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('side_panel_bg')['rgba'] ?? '')],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_bg')['rgba'] ?? ''),
                ],
                [
                    'id' => 'mb_side_panel_text_alignment',
                    'name' => esc_html__('Text Align', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_text_alignment')),
                ],
                [
                    'id' => 'mb_side_panel_width',
                    'name' => esc_html__('Width', 'courto'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'min' => 50,
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_width')['width'] ?? ''),
                ],
                [
                    'id' => 'mb_side_panel_position',
                    'name' => esc_html__('Position', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                                ['mb_customize_side_panel', '=', 'custom'],
                                ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_position')),
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_soc_icons_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Social Shares', 'courto'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_customize_soc_shares',
                    'name' => esc_html__('Social Shares', 'courto'),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_soc_icon_style',
                    'name' => esc_html__('Socials visibility', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'standard' => esc_html__('Always', 'courto'),
                        'hovered' => esc_html__('On Hover', 'courto'),
                    ],
                    'std' => 'standard',
                ],
                [
                    'id' => 'mb_soc_icon_offset',
                    'name' => esc_html__('Offset Top', 'courto'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                    'min' => 0,
                    'std' => 250,
                ],
                [
                    'id' => 'mb_soc_icon_offset_units',
                    'name' => esc_html__('Offset Top Units', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                    'desc' => esc_html__('If measurement units defined as "%" then social buttons will be fixed relative to viewport.', 'courto'),
                    'multiple' => false,
                    'options' => [
                        'pixel' => esc_html__('pixels (px)', 'courto'),
                        'percent' => esc_html__('percents (%)', 'courto'),
                    ],
                    'std' => 'pixel',
                ],
                [
                    'id' => 'mb_soc_icon_facebook',
                    'name' => esc_html__('Facebook Button', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_twitter',
                    'name' => esc_html__('Twitter Button', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_linkedin',
                    'name' => esc_html__('Linkedin Button', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_pinterest',
                    'name' => esc_html__('Pinterest Button', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_tumblr',
                    'name' => esc_html__('Tumblr Button', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_footer_meta_boxes( $meta_boxes )
    {
        $meta_boxes[] = [
            'title' => esc_html__('Footer', 'courto'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_footer_switch',
                    'name' => esc_html__('Footer', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Footer Settings', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_footer_building_tool',
                    'name' => esc_html__('Layout Building Tool', 'courto'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'widgets' => esc_html__('Wordpress Widgets', 'courto'),
                        'elementor' => esc_html__('Elementor', 'courto')
                    ],
                    'std' => 'elementor',
                ],
                [
                    'id' => 'mb_footer_page_select',
                    'name' => esc_html__('Select a page', 'courto'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'elementor']
                        ]],
                    ],
                    'post_type' => 'footer',
                    'field_type' => 'select_advanced',
                    'placeholder' => esc_html__('Select a page', 'courto'),
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                ],
                [
                    'id' => 'mb_footer_spacing',
                    'name' => esc_html__('Paddings', 'courto'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => true,
                        'bottom' => true,
                        'left' => true,
                    ],
                    'std' => [
                        'padding-top' => '0',
                        'padding-right' => '0',
                        'padding-bottom' => '0',
                        'padding-left' => '0'
                    ],
                ],
                [
                    'id' => 'mb_footer_bg',
                    'name' => esc_html__('Background', 'courto'),
                    'type' => 'wgl_background',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'image' => '',
                    'position' => 'center center',
                    'attachment' => 'scroll',
                    'size' => 'cover',
                    'repeat' => 'no-repeat',
                    'color' => '#ffffff',
                ],
                [
                    'id' => 'mb_footer_add_border',
                    'name' => esc_html__('Add Border Top', 'courto'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'widgets'],
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_footer_border_color',
                    'name' => esc_html__('Border Color', 'courto'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_add_border', '=', '1'],
                        ]],
                    ],
                    'alpha_channel' => true,
                    'js_options' => ['defaultColor' => '#e5e5e5'],
                    'std' => '#e5e5e5',
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function page_copyright_meta_boxes( $meta_boxes )
    {
        $meta_boxes[] = [
            'title' => esc_html__('Copyright', 'courto'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_copyright_switch',
                    'name' => esc_html__('Copyright', 'courto'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__( 'Default', 'courto' ),
                        'on' => esc_html__( 'Enable', 'courto' ),
                        'off' => esc_html__( 'Disable', 'courto' ),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Copyright Settings', 'courto'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_copyright_editor',
                    'name' => esc_html__('Editor', 'courto'),
                    'type' => 'textarea',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                    'cols' => 20,
                    'rows' => 3,
                    'std' => esc_html__('Copyright © 2026 Courto by WebGeniusLab. All Rights Reserved', 'courto'),
                ],
                [
                    'id' => 'mb_copyright_text_color',
                    'name' => esc_html__( 'Text Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_copyright_switch', '=', 'on' ]
                        ] ],
                    ],
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'copyright_text_color' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'copyright_text_color' ) ),
                ],
                [
                    'id' => 'mb_copyright_bg_color',
                    'name' => esc_html__( 'Background Color', 'courto' ),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [ [
                            [ 'mb_copyright_switch', '=', 'on' ]
                        ] ],
                    ],
                    'js_options' => [ 'defaultColor' => esc_attr( WGL_Framework::get_option( 'copyright_bg_color' ) ) ],
                    'std' => esc_attr( WGL_Framework::get_option( 'copyright_bg_color' ) ),
                ],
                [
                    'id' => 'mb_copyright_spacing',
                    'name' => esc_html__('Paddings', 'courto'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr(WGL_Framework::get_option('copyright_spacing')['padding-top'] ?? ''),
                        'padding-bottom' => esc_attr(WGL_Framework::get_option('copyright_spacing')['padding-bottom'] ?? ''),
                    ],
                ],
            ],
        ];

        return $meta_boxes;
    }
}

new Courto_Metaboxes();
