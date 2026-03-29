<?php

if (!class_exists('WGL_Extensions_Core')) {
    return;
}

if (!function_exists('wgl_get_redux_icons')) {
    function wgl_get_redux_icons()
    {
        return WGLAdminIcon()->get_icons_name(true);
    }

    add_filter('redux/font-icons', 'wgl_get_redux_icons',99);
}

add_action('after_setup_theme', function() {

    //* This is theme option name where all the Redux data is stored.
    $theme_slug = 'courto_set';

    /**
     * Set all the possible arguments for Redux
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */
    $theme = wp_get_theme();

    Redux::set_args($theme_slug, [
        'opt_name' => $theme_slug, //* This is where your data is stored in the database and also becomes your global variable name.
        'display_name' => $theme->get('Name'), //* Name that appears at the top of your panel
        'display_version' => $theme->get('Version'), //* Version that appears at the top of your panel
        'menu_type' => 'menu', //* Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu' => true, //* Show the sections below the admin menu item or not
        'menu_title' => esc_html__('Theme Options', 'courto'),
        'page_title' => esc_html__('Theme Options', 'courto'),
        'google_api_key' => '', //* You will need to generate a Google API key to use this feature. Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_update_weekly' => false, //* Set it you want google fonts to update weekly. A google_api_key value is required.
        'async_typography' => true, //* Must be defined to add google fonts to the typography module
        'admin_bar' => true, //* Show the panel pages on the admin bar
        'admin_bar_icon' => 'dashicons-admin-generic', //* Choose an icon for the admin bar menu
        'admin_bar_priority' => 50, //* Choose an priority for the admin bar menu
        'global_variable' => '', //* Set a different name for your global variable other than the opt_name
        'dev_mode' => false,
        'update_notice' => true, //* If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer' => true,
        'page_priority' => 3, //* Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent' => 'wgl-dashboard-panel', //* For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions' => 'manage_options', //* Permissions needed to access the options panel.
        'menu_icon' => 'dashicons-admin-generic', //* Specify a custom URL to an icon
        'last_tab' => '', //* Force your panel to always open to a specific tab (by id)
        'page_icon' => 'icon-themes', //* Icon displayed in the admin panel next to your menu_title
        'page_slug' => 'wgl-theme-options-panel', //* Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults' => true, //* On load save the defaults to DB before user clicks save or not
        'default_show' => false, //* If true, shows the default value next to each field that is not the default value.
        'default_mark' => '', //* What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export' => true, //* Shows the Import/Export panel when not used as a field.
        'transient_time' => 60 * MINUTE_IN_SECONDS, //* Show the time the page took to load, etc
        'output' => true, //* Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag' => true, //* FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database' => '', //* possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn' => true,
    ]);

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'general',
            'title' => esc_html__('General', 'courto'),
            'icon' => 'el el-screen',
            'fields' => [
                [
                    'id' => 'use_minified',
                    'title' => esc_html__('Use minified css/js files', 'courto'),
                    'type' => 'switch',
                    'desc' => esc_html__('Speed up your site load.', 'courto'),
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                ],
                [
                    'id' => 'body_settings',
                    'type' => 'section',
                    'title' => esc_html__('Body', 'courto'),
                    'indent' => true,
                ],
                [
                    'id' => 'body_lines_switch',
                    'title' => esc_html__('Body Lines', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('On', 'courto'),
                    'off' => esc_html__('Off', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'body_lines_color',
                    'title' => esc_html__('Lines Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['body_lines_switch', '=', '1'],
                    'transparent' => false,
                    'default' => [
                        'alpha' => '0.4',
                        'rgba' => 'rgba(185,185,185,0.4)',
                        'color' => '#e3e3e3',
                    ],
                ],
                [
                    'id' => 'body_settings-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'preloader-start',
                    'title' => esc_html__('Preloader', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'preloader',
                    'title' => esc_html__('Preloader', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'preloader_background',
                    'title' => esc_html__('Preloader Background', 'courto'),
                    'type' => 'color',
                    'required' => ['preloader', '=', '1'],
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'preloader_color',
                    'title' => esc_html__('Preloader Color', 'courto'),
                    'type' => 'color',
                    'required' => ['preloader', '=', '1'],
                    'transparent' => false,
                    'default' => '#FF7425',
                ],
                [
                    'id' => 'preloader-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'search_settings',
                    'type' => 'section',
                    'title' => esc_html__('Search', 'courto'),
                    'indent' => true,
                ],
                [
                    'id' => 'search_style',
                    'title' => esc_html__('Choose search style', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'standard' => esc_html__('Standard', 'courto'),
                        'standard_fw' => esc_html__('Full Header Width', 'courto'),
                        'alt' => esc_html__('Full Page Width', 'courto'),
                    ],
                    'default' => 'standard',
                ],
                [
                    'id' => 'search_post_type',
                    'title' => esc_html__('Search Post Types', 'courto'),
                    'type' => 'multi_text',
                    'validate' => 'no_html',
                    'add_text' => esc_html__('Add Post Type', 'courto'),
                    'default' => [],
                ],
                [
                    'id' => 'search_settings-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'cursor_settings',
                    'type' => 'section',
                    'title' => esc_html__('Cursor PoRoboto', 'courto'),
                    'indent' => true,
                ],
                [
                    'id' => 'cursor_switch',
                    'title' => esc_html__('Cursor PoRoboto', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('On', 'courto'),
                    'off' => esc_html__('Off', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'cursor_size',
                    'title' => esc_html__('Cursor Size', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'required' => ['cursor_switch', '=', '1'],
                ],
                [
                    'id' => 'cursor_color',
                    'title' => esc_html__('Cursor Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['cursor_switch', '=', '1'],
                    'transparent' => false,
                    'default' => [
                        'alpha' => '0',
                        'rgba' => 'rgba(60,133,153,0)',
                        'color' => '#FF7425',
                    ],
                ],
                [
                    'id' => 'cursor_duration',
                    'title' => esc_html__('Cursor Duration', 'courto'),
                    'type' => 'text',
                    'required' => ['cursor_switch', '=', '1'],
                    'default' => '0.35',
                ],
                [
                    'id' => 'cursor_follower_size',
                    'title' => esc_html__('Cursor Follower Size', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'required' => ['cursor_switch', '=', '1'],
                ],
                [
                    'id' => 'cursor_follower_color',
                    'title' => esc_html__('Cursor Follower Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['cursor_switch', '=', '1'],
                    'transparent' => false,
                ],
                [
                    'id' => 'cursor_follower_duration',
                    'title' => esc_html__('Cursor Follower Duration', 'courto'),
                    'type' => 'text',
                    'required' => ['cursor_switch', '=', '1'],
                    'default' => '0.9',
                ],
                [
                    'id'       => 'cursor_apply_animation',
                    'title'    => esc_html__('Cursor Link Animation', 'courto'),
                    'type'     => 'select',
                    'required' => ['cursor_switch', '=', '1'],
                    'options'  => [
                        'none'       => esc_html__('None', 'courto'),
                        '1'          => esc_html__('Style 1', 'courto'),
                        '2'          => esc_html__('Style 2', 'courto'),
                        '3'          => esc_html__('Style 3', 'courto'),
                    ],
                    'default'  => '1',
                ],
                [
                    'id'       => 'cursor_blend_mode',
                    'title'    => esc_html__( 'Blend Mode', 'courto' ),
                    'type'     => 'select',
                    'required' => [ 'cursor_switch', '=', '1' ],
                    'options'  => [
                        'normal'      => esc_html__( 'Normal', 'courto' ),
                        'multiply'    => esc_html__( 'Multiply', 'courto' ),
                        'screen'      => esc_html__( 'Screen', 'courto' ),
                        'overlay'     => esc_html__( 'Overlay', 'courto' ),
                        'darken'      => esc_html__( 'Darken', 'courto' ),
                        'lighten'     => esc_html__( 'Lighten', 'courto' ),
                        'color-dodge' => esc_html__( 'Color Dodge', 'courto' ),
                        'saturation'  => esc_html__( 'Saturation', 'courto' ),
                        'color'       => esc_html__( 'Color', 'courto' ),
                        'difference'  => esc_html__( 'Difference', 'courto' ),
                        'exclusion'   => esc_html__( 'Exclusion', 'courto' ),
                        'hue'         => esc_html__( 'Hue', 'courto' ),
                        'luminosity'  => esc_html__( 'Luminosity', 'courto' ),
                    ],
                    'default' => 'normal',
                ],
                [
                    'id' => 'cursor_settings-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'smooth_scroll_settings',
                    'type' => 'section',
                    'title' => esc_html__('Smooth Scroll', 'courto'),
                    'indent' => true,
                ],
                [
                    'id' => 'smooth_scroll_switch',
                    'title' => esc_html__('Enable Smooth Scroll', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('On', 'courto'),
                    'off' => esc_html__('Off', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'smooth_scroll_speed',
                    'title' => esc_html__('Scroll Speed', 'courto'),
                    'type' => 'slider',
                    'required' => ['smooth_scroll_switch', '=', true],
                    'min' => 0.5,
                    'max' => 3,
                    'step' => 0.1,
                    'default' => 1.2,
                    'resolution'    => 0.1,
                    'display_value' => 'text',
                ],
                [
                    'id' => 'smooth_scroll_lag',
                    'title' => esc_html__('Linear Robotopolation (lerp) intensity', 'courto'),
                    'type' => 'slider',
                    'required' => ['smooth_scroll_switch', '=', true],
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.01,
                    'default' => 0.1,
                    'resolution'    => 0.01,
                    'display_value' => 'text',
                ],
                [
                    'id' => 'smooth_scroll_mac_disable',
                    'title' => esc_html__('Use native scroll on mac', 'courto'),
                    'type' => 'switch',
                    'required' => ['smooth_scroll_switch', '=', true],
                    'default' => '',
                ],
                [
                    'id' => 'smooth_scroll_settings-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'scroll_up_settings',
                    'title' => esc_html__('Back to Top', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'scroll_up',
                    'title' => esc_html__('Button', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Disable', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'scroll_up_appearance',
                    'title' => esc_html__('Appearance', 'courto'),
                    'type' => 'switch',
                    'required' => ['scroll_up', '=', true],
                    'on' => esc_html__('Text with Icon', 'courto'),
                    'off' => esc_html__('Only Icon', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'scroll_up_text',
                    'title' => esc_html__('Button Text', 'courto'),
                    'type' => 'text',
                    'required' => ['scroll_up_appearance', '=', true],
                    'default' => esc_html__('Top', 'courto'),
                ],
                [
                    'id' => 'scroll_up_arrow_color',
                    'title' => esc_html__('Text/Icon Color', 'courto'),
                    'type' => 'color',
                    'required' => ['scroll_up', '=', true],
                    'transparent' => false,
                    'default' => '#181818',
                ],
                [
                    'id' => 'scroll_up_arrow_color_bg',
                    'title' => esc_html__('Text/Icon Background Color', 'courto'),
                    'type' => 'color',
                    'required' => ['scroll_up', '=', true],
                    'transparent' => true,
                    'default' => '#D1FF6D',
                ],
                [
                    'id' => 'scroll_up_arrow_color_border',
                    'title' => esc_html__('Icon Border Color', 'courto'),
                    'type' => 'color',
                    'required' => ['scroll_up', '=', true],
                    'transparent' => true,
                    'default' => '#181818',
                ],
                [
                    'id' => 'scroll_up_settings-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'cart_overlay_settings',
                    'title' => esc_html__('Header Cart', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'overlay_full',
                    'title' => esc_html__('Full Page Overlay', 'courto'),
                    'desc' => esc_html__( 'This option is useful if you are using a transparent header background', 'courto' ),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'cart_overlay_color',
                    'title' => esc_html__('Cart Overlay Color', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'customizer' => false,
                ],
                [
                    'id' => 'cart_offset',
                    'title' => esc_html__('Top/Right Offset', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'absolute',
                    'all' => false,
                    'top' => true,
                    'right' => true,
                    'bottom' => false,
                    'left' => false,
                    'default' => [
                        'top' => '30',
                        'right' => '30',
                    ],
                ],
                [
                    'id' => 'cart_offset_m',
                    'title' => esc_html__('Top/Right Offset (Mobile)', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'absolute',
                    'all' => false,
                    'top' => true,
                    'right' => true,
                    'bottom' => false,
                    'left' => false,
                    'default' => [
                        'top' => '10',
                        'right' => '10',
                    ],
                ],
                [
                    'id' => 'cart_overlay_settings-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ],
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'editors-option',
            'title' => esc_html__('Custom JS', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'custom_js',
                    'title' => esc_html__('Custom JS', 'courto'),
                    'type' => 'ace_editor',
                    'subtitle' => esc_html__('Paste your JS code here.', 'courto'),
                    'mode' => 'javascript',
                    'theme' => 'chrome',
                    'default' => ''
                ],
                [
                    'id' => 'header_custom_js',
                    'title' => esc_html__('Custom JS', 'courto'),
                    'type' => 'ace_editor',
                    'subtitle' => esc_html__('Code to be added inside HEAD tag', 'courto'),
                    'mode' => 'html',
                    'theme' => 'chrome',
                    'default' => ''
                ],
            ],
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'editors-option-css',
            'title' => esc_html__('Custom CSS', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'custom_css',
                    'title' => esc_html__('Custom CSS', 'courto'),
                    'type' => 'ace_editor',
                    'subtitle' => esc_html__('Paste your CSS code here.', 'courto'),
                    'mode' => 'css',
                    'theme' => 'chrome',
                    'default' => ''
                ],
            ],
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'header_section',
            'title' => esc_html__('Header', 'courto'),
            'icon' => 'fas fa-window-maximize',
        ]
    );

    $header_builder_items = [
        'default' => [
            'html1' => ['title' => esc_html__('HTML 1', 'courto'), 'settings' => true],
            'html2' => ['title' => esc_html__('HTML 2', 'courto'), 'settings' => true],
            'html3' => ['title' => esc_html__('HTML 3', 'courto'), 'settings' => true],
            'html4' => ['title' => esc_html__('HTML 4', 'courto'), 'settings' => true],
            'html5' => ['title' => esc_html__('HTML 5', 'courto'), 'settings' => true],
            'html6' => ['title' => esc_html__('HTML 6', 'courto'), 'settings' => true],
            'html7' => ['title' => esc_html__('HTML 7', 'courto'), 'settings' => true],
            'html8' => ['title' => esc_html__('HTML 8', 'courto'), 'settings' => true],
            'delimiter1' => ['title' => esc_html__('|', 'courto'), 'settings' => true],
            'delimiter2' => ['title' => esc_html__('|', 'courto'), 'settings' => true],
            'delimiter3' => ['title' => esc_html__('|', 'courto'), 'settings' => true],
            'delimiter4' => ['title' => esc_html__('|', 'courto'), 'settings' => true],
            'delimiter5' => ['title' => esc_html__('|', 'courto'), 'settings' => true],
            'delimiter6' => ['title' => esc_html__('|', 'courto'), 'settings' => true],
            'spacer3' => ['title' => esc_html__('Spacer 3', 'courto'), 'settings' => true],
            'spacer4' => ['title' => esc_html__('Spacer 4', 'courto'), 'settings' => true],
            'spacer5' => ['title' => esc_html__('Spacer 5', 'courto'), 'settings' => true],
            'spacer6' => ['title' => esc_html__('Spacer 6', 'courto'), 'settings' => true],
            'spacer7' => ['title' => esc_html__('Spacer 7', 'courto'), 'settings' => true],
            'spacer8' => ['title' => esc_html__('Spacer 8', 'courto'), 'settings' => true],
            'button1' => ['title' => esc_html__('Button', 'courto'), 'settings' => true],
            'button2' => ['title' => esc_html__('Button', 'courto'), 'settings' => true],
            'wpml' => ['title' => esc_html__('WPML/Polylang', 'courto'), 'settings' => false],
            'cart' => ['title' => esc_html__('Cart', 'courto'), 'settings' => true],
            'item_search' => ['title' => esc_html__('Search', 'courto'), 'settings' => false],
            'login' => ['title' => esc_html__('WC Login', 'courto'), 'settings' => false],
            'side_panel' => ['title' => esc_html__('Side Panel', 'courto'), 'settings' => true],
            'profile' => ['title' => esc_html__('Profile', 'courto'), 'settings' => true],
        ],
        'mobile' => [
            'html1' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html2' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html3' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html4' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html5' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html6' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'spacer1' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer2' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer3' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer4' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer5' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer6' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'side_panel' => ['title' => esc_html__('Side Panel', 'courto'), 'settings' => false],
            'wpml' => ['title' => esc_html__('WPML/Polylang', 'courto'), 'settings' => false],
            'cart' => ['title' => esc_html__('Cart', 'courto'), 'settings' => false],
            'item_search' => ['title' => esc_html__('Search', 'courto'), 'settings' => false],
            'login' => ['title' => esc_html__('WC Login', 'courto'), 'settings' => false],
            'button1' => ['title' => esc_html__('Button', 'courto'), 'settings' => true],
            'button2' => ['title' => esc_html__('Button', 'courto'), 'settings' => true],
            'socials' => ['title' => esc_html__('Socials', 'courto'), 'settings' => true],
            'profile' => ['title' => esc_html__('Profile', 'courto'), 'settings' => true],
        ],
        'mobile_drawer' => [
            'html1' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html2' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html3' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html4' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html5' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'html6' => ['title' => esc_html__('HTML', 'courto'), 'settings' => true],
            'wpml' => ['title' => esc_html__('WPML/Polylang', 'courto'), 'settings' => false],
            'button1' => ['title' => esc_html__('Button', 'courto'), 'settings' => true],
            'button2' => ['title' => esc_html__('Button', 'courto'), 'settings' => true],
            'spacer1' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer2' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer3' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer4' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer5' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'spacer6' => ['title' => esc_html__('Spacer', 'courto'), 'settings' => true],
            'socials' => ['title' => esc_html__('Socials', 'courto'), 'settings' => true],
            'profile' => ['title' => esc_html__('Profile', 'courto'), 'settings' => true],
        ],
    ];

    Redux::set_section(
        $theme_slug,
        [
            'title' => esc_html__('Header Builder', 'courto'),
            'id' => 'header-customize',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'header_switch',
                    'title' => esc_html__('Header', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Disable', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'header_building_tool',
                    'title' => esc_html__('Layout Building Tool', 'courto'),
                    'type' => 'select',
                    'required' => ['header_switch', '=', '1'],
                    'options' => [
                        'default' => esc_html__('Default Builder', 'courto'),
                        'elementor' => esc_html__('Elementor (recommended)', 'courto')
                    ],
                    'default' => 'default',
                ],
                [
                    'id' => 'header_page_select',
                    'type' => 'select',
                    'title' => esc_html__('Header Template', 'courto'),
                    'required' => ['header_building_tool', '=', 'elementor'],
                    'desc' => wp_kses(
                        sprintf(
                            '%s <a href="%s" target="_blank">%s</a> %s<br> %s',
                            __('Selected Template will be used for all pages by default. You can edit/create Header Template in the', 'courto'),
                            admin_url('edit.php?post_type=header'),
                            __('Header Templates', 'courto'),
                            __('dashboard tab.', 'courto'),
                            courto_quick_tip(
                                sprintf(
                                    __('Note: fine tuning is available through the Elementor\'s <code>Post Settings</code> tab, which is located <a href="%s" target="_blank">here</a>', 'courto'),
                                    get_template_directory_uri() . '/core/admin/img/dashboard/quick_tip__header_extra_options.png'
                                )
                            )
                        ),
                        ['a' => ['href' => true, 'target' => true], 'br' => [], 'span' => ['class' => true], 'i' => ['class' => true], 'code' => []]
                    ),
                    'data' => 'posts',
                    'args' => [
                        'post_type' => 'header',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ],
                ],
                [
                    'id' => 'bottom_header_layout',
                    'type' => 'custom_header_builder',
                    'title' => esc_html__('Header Builder', 'courto'),
                    'required' => ['header_building_tool', '=', 'default'],
                    'compiler' => 'true',
                    'full_width' => true,
                    'options' => [
                        'items' => $header_builder_items['default'],
                        'Top Left area' => [],
                        'Top Center area' => [],
                        'Top Right area' => [],
                        'Middle Left area' => [
                            'spacer1' => ['title' => esc_html__('Spacer 1', 'courto'), 'settings' => true],
                            'logo' => ['title' => esc_html__('Logo', 'courto'), 'settings' => false],
                        ],
                        'Middle Center area' => [
                            'menu' => ['title' => esc_html__('Menu', 'courto'), 'settings' => false],
                        ],
                        'Middle Right area' => [
                            'item_search' => ['title' => esc_html__('Search', 'courto'), 'settings' => true],
                            'spacer2' => ['title' => esc_html__('Spacer 2', 'courto'), 'settings' => true],
                        ],
                        'Bottom Left area' => [],
                        'Bottom Center area' => [],
                        'Bottom Right area' => [],
                    ],
                    'default' => [
                        'items' => $header_builder_items['default'],
                        'Top Left area' => [],
                        'Top Center area' => [],
                        'Top Right area' => [],
                        'Middle Left area' => [
                            'spacer1' => ['title' => esc_html__('Spacer 1', 'courto'), 'settings' => true],
                            'logo' => ['title' => esc_html__('Logo', 'courto'), 'settings' => false],
                        ],
                        'Middle Center area' => [
                            'menu' => ['title' => esc_html__('Menu', 'courto'), 'settings' => false],
                        ],
                        'Middle Right area' => [
                            'item_search' => ['title' => esc_html__('Search', 'courto'), 'settings' => true],
                            'spacer2' => ['title' => esc_html__('Spacer 2', 'courto'), 'settings' => true],
                        ],
                        'Bottom Left area' => [],
                        'Bottom Center area' => [],
                        'Bottom Right area' => [],
                    ],
                ],
                [
                    'id' => 'bottom_header_spacer1',
                    'title' => esc_html__('Header Spacer 1 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 40],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_spacer2',
                    'title' => esc_html__('Header Spacer 2 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 40],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_spacer3',
                    'title' => esc_html__('Header Spacer 3 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_spacer4',
                    'title' => esc_html__('Header Spacer 4 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_spacer5',
                    'title' => esc_html__('Header Spacer 5 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_spacer6',
                    'title' => esc_html__('Header Spacer 6 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_spacer7',
                    'title' => esc_html__('Header Spacer 7 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_spacer8',
                    'title' => esc_html__('Header Spacer 8 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_item_search_custom',
                    'title' => esc_html__('Customize Search', 'courto'),
                    'type' => 'switch',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_item_search_color_txt',
                    'title' => esc_html__('Icon Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_item_search_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_item_search_hover_color_txt',
                    'title' => esc_html__('Hover Icon Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_item_search_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_cart_custom',
                    'title' => esc_html__('Customize cart', 'courto'),
                    'type' => 'switch',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_cart_color_txt',
                    'title' => esc_html__('Icon Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_cart_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_cart_hover_color_txt',
                    'title' => esc_html__('Hover Icon Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_cart_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter1_height',
                    'title' => esc_html__('Delimiter Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 50],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter1_width',
                    'title' => esc_html__('Delimiter Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 1],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter1_bg',
                    'title' => esc_html__('Delimiter Background', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#000000',
                        'alpha' => '0.1',
                        'rgba' => 'rgba(0, 0, 0, 0.1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter1_margin',
                    'title' => esc_html__('Delimiter Spacing', 'courto'),
                    'type' => 'spacing',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => false,
                    'top' => false,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'margin-left' => '20',
                        'margin-right' => '30',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter2_height',
                    'title' => esc_html__('Delimiter Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'height' => true,
                    'width' => false,
                    'default' => ['height' => 100],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter2_width',
                    'title' => esc_html__('Delimiter Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 1],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter2_bg',
                    'title' => esc_html__('Delimiter Background', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '.9',
                        'rgba' => 'rgba(255,255,255,0.9)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter2_margin',
                    'title' => esc_html__('Delimiter Spacing', 'courto'),
                    'type' => 'spacing',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => false,
                    'top' => false,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'margin-left' => '30',
                        'margin-right' => '30',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter3_height',
                    'title' => esc_html__('Delimiter Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 100],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter3_width',
                    'title' => esc_html__('Delimiter Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 1],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter3_bg',
                    'title' => esc_html__('Delimiter Background', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '.9',
                        'rgba' => 'rgba(255,255,255,0.9)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter3_margin',
                    'title' => esc_html__('Delimiter Spacing', 'courto'),
                    'type' => 'spacing',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => false,
                    'top' => false,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'margin-left' => '30',
                        'margin-right' => '30',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter4_height',
                    'title' => esc_html__('Delimiter Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 100],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter4_width',
                    'title' => esc_html__('Delimiter Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 1],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter4_bg',
                    'title' => esc_html__('Delimiter Background', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '.9',
                        'rgba' => 'rgba(255,255,255,0.9)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter4_margin',
                    'title' => esc_html__('Delimiter Spacing', 'courto'),
                    'type' => 'spacing',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => false,
                    'top' => false,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'margin-left' => '30',
                        'margin-right' => '30',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter5_height',
                    'title' => esc_html__('Delimiter Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 100],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter5_width',
                    'title' => esc_html__('Delimiter Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 1],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter5_bg',
                    'title' => esc_html__('Delimiter Background', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '.9',
                        'rgba' => 'rgba(255,255,255,0.9)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter5_margin',
                    'title' => esc_html__('Delimiter Spacing', 'courto'),
                    'type' => 'spacing',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => false,
                    'top' => false,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'margin-left' => '30',
                        'margin-right' => '30',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter6_height',
                    'title' => esc_html__('Delimiter Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'height' => true,
                    'width' => false,
                    'default' => ['height' => 100],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter6_width',
                    'title' => esc_html__('Delimiter Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_building_tool', '=', 'default'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 1],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter6_bg',
                    'title' => esc_html__('Delimiter Background', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '.9',
                        'rgba' => 'rgba(255,255,255,0.9)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_delimiter6_margin',
                    'title' => esc_html__('Delimiter Spacing', 'courto'),
                    'type' => 'spacing',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => false,
                    'top' => false,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'margin-left' => '30',
                        'margin-right' => '30',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_title',
                    'title' => esc_html__('Button Text', 'courto'),
                    'type' => 'text',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => esc_html__('Contact Us', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_link',
                    'title' => esc_html__('Link', 'courto'),
                    'type' => 'text',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => '#',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_target',
                    'title' => esc_html__('Open link in a new tab', 'courto'),
                    'type' => 'switch',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_size',
                    'title' => esc_html__('Button Size', 'courto'),
                    'type' => 'select',
                    'required' => ['header_building_tool', '=', 'default'],
                    'options' => [
                        'sm' => esc_html__('Small', 'courto'),
                        'md' => esc_html__('Medium', 'courto'),
                        'lg' => esc_html__('Large', 'courto'),
                        'xl' => esc_html__('Extra Large', 'courto'),
                    ],
                    'default' => 'md',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_radius',
                    'title' => esc_html__('Button Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => ['header_building_tool', '=', 'default'],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_custom',
                    'title' => esc_html__('Customize Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_color_txt',
                    'title' => esc_html__('Text Color Idle', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_hover_color_txt',
                    'title' => esc_html__('Text Color Hover', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_bg',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_hover_bg',
                    'title' => esc_html__('Hover Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_border',
                    'title' => esc_html__('Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button1_hover_border',
                    'title' => esc_html__('Hover Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_title',
                    'title' => esc_html__('Button Text', 'courto'),
                    'type' => 'text',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => esc_html__('Contact Us', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_link',
                    'title' => esc_html__('Link', 'courto'),
                    'type' => 'text',
                    'required' => ['header_building_tool', '=', 'default'],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_target',
                    'title' => esc_html__('Open link in a new tab', 'courto'),
                    'type' => 'switch',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_size',
                    'title' => esc_html__('Button Size', 'courto'),
                    'type' => 'select',
                    'required' => ['header_building_tool', '=', 'default'],
                    'options' => [
                        'sm' => esc_html__('Small', 'courto'),
                        'md' => esc_html__('Medium', 'courto'),
                        'lg' => esc_html__('Large', 'courto'),
                        'xl' => esc_html__('Extra Large', 'courto'),
                    ],
                    'default' => 'md',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_radius',
                    'title' => esc_html__('Button Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => ['header_building_tool', '=', 'default'],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_custom',
                    'title' => esc_html__('Customize Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['header_building_tool', '=', 'default'],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_color_txt',
                    'title' => esc_html__('Text Color Idle', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_hover_color_txt',
                    'title' => esc_html__('Text Color Hover', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_bg',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_hover_bg',
                    'title' => esc_html__('Hover Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_border',
                    'title' => esc_html__('Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_button2_hover_border',
                    'title' => esc_html__('Hover Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['bottom_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_profile_format',
                    'title' => esc_html__('Profile Format', 'courto'),
                    'type' => 'select',
                    'required' => ['header_building_tool', '=', 'default'],
                    'options' => [
                        'none' => esc_html__('None', 'courto'),
                        'def' => esc_html__('Default', 'courto'),
                        'username' => esc_html__('Username', 'courto'),
                        'display_name' => esc_html__('Public', 'courto'),
                    ],
                    'default' => 'def'
                ],
                [
                    'id' => 'bottom_header_bar_html1_editor',
                    'title' => esc_html__('HTML Element 1 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_bar_html2_editor',
                    'title' => esc_html__('HTML Element 2 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_bar_html3_editor',
                    'title' => esc_html__('HTML Element 3 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_bar_html4_editor',
                    'title' => esc_html__('HTML Element 4 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_bar_html5_editor',
                    'title' => esc_html__('HTML Element 5 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_bar_html6_editor',
                    'title' => esc_html__('HTML Element 6 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_bar_html7_editor',
                    'title' => esc_html__('HTML Element 7 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_bar_html8_editor',
                    'title' => esc_html__('HTML Element 8 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'html',
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_side_panel_color',
                    'title' => esc_html__('Icon Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(38,38,38,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'bottom_header_side_panel_background',
                    'title' => esc_html__('Background Icon', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_building_tool', '=', 'default'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '0',
                        'rgba' => 'rgba(255,255,255,0)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top-start',
                    'title' => esc_html__('Header Top Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_full_width',
                    'title' => esc_html__('Full Width Header', 'courto'),
                    'type' => 'switch',
                    'subtitle' => esc_html__('Set header content in full width', 'courto'),
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_max_width_custom',
                    'title' => esc_html__('Limit the Max Width of Container', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_max_width',
                    'title' => esc_html__('Max Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_top_max_width_custom', '=', '1'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 1290],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_height',
                    'title' => esc_html__('Header Top Height', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 49],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_background_image',
                    'title' => esc_html__('Header Top Background Image', 'courto'),
                    'type' => 'media',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_background',
                    'title' => esc_html__('Header Top Background', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,0)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_color',
                    'title' => esc_html__('Header Top Text Color', 'courto'),
                    'type' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_bottom_border',
                    'type' => 'switch',
                    'title' => esc_html__('Set Header Top Bottom Border', 'courto'),
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_border_height',
                    'title' => esc_html__('Header Top Border Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_top_bottom_border', '=', '1'],
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => '1'],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top_bottom_border_color',
                    'title' => esc_html__('Header Top Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_top_bottom_border', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '.2',
                        'rgba' => 'rgba(162,162,162,0.2)',
                        'color' => '#a2a2a2',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_top-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle-start',
                    'title' => esc_html__('Header Middle Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_full_width',
                    'type' => 'switch',
                    'title' => esc_html__('Full Width Middle Header', 'courto'),
                    'subtitle' => esc_html__('Set header content in full width', 'courto'),
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_max_width_custom',
                    'title' => esc_html__('Limit the Max Width of Container', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_max_width',
                    'title' => esc_html__('Max Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_middle_max_width_custom', '=', '1'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 1290],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_height',
                    'title' => esc_html__('Header Middle Height', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 100],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_background_image',
                    'title' => esc_html__('Header Middle Background Image', 'courto'),
                    'type' => 'media',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_background',
                    'title' => esc_html__('Header Middle Background', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,0)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_color',
                    'title' => esc_html__('Header Middle Text Color', 'courto'),
                    'type' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_bottom_border',
                    'title' => esc_html__('Set Header Middle Bottom Border', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_border_height',
                    'title' => esc_html__('Header Middle Border Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_middle_bottom_border', '=', '1'],
                    'height' => true,
                    'width' => false,
                    'default' => ['height' => '1'],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle_bottom_border_color',
                    'title' => esc_html__('Header Middle Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_middle_bottom_border', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(245,245,245,1)',
                        'color' => '#f5f5f5',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_middle-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom-start',
                    'title' => esc_html__('Header Bottom Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_full_width',
                    'title' => esc_html__('Full Width Bottom Header', 'courto'),
                    'type' => 'switch',
                    'subtitle' => esc_html__('Set header content in full width', 'courto'),
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_max_width_custom',
                    'title' => esc_html__('Limit the Max Width of Container', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_max_width',
                    'title' => esc_html__('Max Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_bottom_max_width_custom', '=', '1'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 1290],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_height',
                    'title' => esc_html__('Header Bottom Height', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 100],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_background_image',
                    'title' => esc_html__('Header Bottom Background Image', 'courto'),
                    'type' => 'media',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_background',
                    'title' => esc_html__('Header Bottom Background', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '.9',
                        'rgba' => 'rgba(255,255,255,0.9)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_color',
                    'title' => esc_html__('Header Bottom Text Color', 'courto'),
                    'type' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_bottom_border',
                    'title' => esc_html__('Set Header Bottom Border', 'courto'),
                    'type' => 'switch',
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_border_height',
                    'title' => esc_html__('Header Bottom Border Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['header_bottom_bottom_border', '=', '1'],
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => '1'],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom_bottom_border_color',
                    'title' => esc_html__('Header Bottom Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['header_bottom_bottom_border', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,0.2)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_bottom-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-left-start',
                    'title' => esc_html__('Top Left Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_left_horz',
                    'type' => 'button_set',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_left_vert',
                    'type' => 'button_set',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_left_display',
                    'type' => 'button_set',
                    'title' => esc_html__('Display', 'courto'),
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-left-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-center-start',
                    'title' => esc_html__('Top Center Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_center_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_center_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_center_display',
                    'title' => esc_html__('Display', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-center-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-center-start',
                    'title' => esc_html__('Top Center Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_center_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_center_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_center_display',
                    'title' => esc_html__('Display', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-center-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-right-start',
                    'title' => esc_html__('Top Right Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_right_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'right',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_right_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_top_right_display',
                    'title' => esc_html__('Display', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-top-right-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-middle-left-start',
                    'title' => esc_html__('Middle Left Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_left_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_left_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_left_display',
                    'title' => esc_html__('Display', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-middle-left-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-middle-center-start',
                    'title' => esc_html__('Middle Center Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_center_horz',
                    'type' => 'button_set',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'center',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_center_vert',
                    'type' => 'button_set',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_center_display',
                    'title' => esc_html__('Display', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-middle-center-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-middle-right-start',
                    'title' => esc_html__('Middle Right Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_right_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'right',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_right_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_middle_right_display',
                    'type' => 'button_set',
                    'title' => esc_html__('Display', 'courto'),
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-middle-right-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-bottom-left-start',
                    'title' => esc_html__('Bottom Left Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_left_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_left_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_left_display',
                    'title' => esc_html__('Display', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-bottom-left-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-bottom-center-start',
                    'type' => 'section',
                    'title' => esc_html__('Bottom Center Column Options', 'courto'),
                    'indent' => true,
                    'required' => ['header_building_tool', '=', 'default'],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_center_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_center_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_center_display',
                    'type' => 'button_set',
                    'title' => esc_html__('Display', 'courto'),
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-bottom-center-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-bottom-right-start',
                    'title' => esc_html__('Bottom Right Column Options', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_right_horz',
                    'title' => esc_html__('Horizontal Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'right',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_right_vert',
                    'title' => esc_html__('Vertical Align', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'top' => esc_html__('Top', 'courto'),
                        'middle' => esc_html__('Middle', 'courto'),
                        'bottom' => esc_html__('Bottom', 'courto'),
                    ],
                    'default' => 'middle',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column_bottom_right_display',
                    'title' => esc_html__('Display', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'normal' => esc_html__('Normal', 'courto'),
                        'grow' => esc_html__('Grow', 'courto'),
                    ],
                    'default' => 'normal',
                    'customizer' => false,
                ],
                [
                    'id' => 'header_column-bottom-right-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_row_settings-start',
                    'title' => esc_html__('Header Settings', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'default'],
                    'indent' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_shadow',
                    'title' => esc_html__('Header Bottom Shadow', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_on_bg',
                    'title' => esc_html__('Over content', 'courto'),
                    'type' => 'switch',
                    'subtitle' => esc_html__('Display header template over the content.', 'courto'),
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'lavalamp_active',
                    'type' => 'switch',
                    'title' => esc_html__('Lavalamp Marker', 'courto'),
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'marker_active',
                    'type' => 'switch',
                    'title' => esc_html__('Marker', 'courto'),
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'sub_menu_background',
                    'type' => 'color_rgba',
                    'title' => esc_html__('Sub Menu Background', 'courto'),
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'sub_menu_color',
                    'title' => esc_html__('Sub Menu Text Color', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_mobile_queris',
                    'title' => esc_html__('Mobile Header Switch Breakpoint', 'courto'),
                    'type' => 'slider',
                    'display_value' => 'text',
                    'min' => 400,
                    'max' => 1920,
                    'default' => 1200,
                    'customizer' => false,
                ],
                [
                    'id' => 'header_row_settings-end',
                    'type' => 'section',
                    'indent' => false,
                    'customizer' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'title' => esc_html__('Header Sticky', 'courto'),
            'id' => 'header_builder_sticky',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'header_sticky',
                    'title' => esc_html__('Header Sticky', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                ],
                [
                    'id' => 'header_sticky-start',
                    'title' => esc_html__('Sticky Settings', 'courto'),
                    'type' => 'section',
                    'required' => ['header_sticky', '=', '1'],
                    'indent' => true,
                ],
                [
                    'id' => 'header_sticky_page_select',
                    'title' => esc_html__('Header Sticky Template', 'courto'),
                    'type' => 'select',
                    'required' => ['header_sticky', '=', '1'],
                    'desc' => sprintf(
                        '%s <a href="%s" target="_blank">%s</a> %s',
                        esc_html__('Selected Template will be used for all pages by default. You can edit/create Header Template in the', 'courto'),
                        admin_url('edit.php?post_type=header'),
                        esc_html__('Header Templates', 'courto'),
                        esc_html__('dashboard tab.', 'courto')
                    ),
                    'data' => 'posts',
                    'args' => [
                        'post_type' => 'header',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ],
                ],
                [
                    'id' => 'header_sticky_style',
                    'type' => 'select',
                    'title' => esc_html__('Appearance', 'courto'),
                    'options' => [
                        'standard' => esc_html__('Always Visible', 'courto'),
                        'scroll_up' => esc_html__('Visible while scrolling upwards', 'courto'),
                    ],
                    'default' => 'scroll_up',
                    'required' => ['header_sticky', '=', '1'],
                ],
                [
                    'id' => 'header_sticky-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'title' => esc_html__('Header Mobile', 'courto'),
            'id' => 'header_builder_mobile',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'mobile_header',
                    'title' => esc_html__('Mobile Header', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Custom', 'courto'),
                    'off' => esc_html__('Default', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'mobile_header_building_tool',
                    'title' => esc_html__('Mobile Header Tool', 'courto'),
                    'type' => 'select',
                    'options' => [
                        'default' => esc_html__('WGL Header (Deprecated)', 'courto'),
                        'elementor' => esc_html__('Header Templates (Recommended)', 'courto')
                    ],
                    'desc' => sprintf(
                        wp_kses(
                            __('<a href="%s" target="_blank">Elementor\'s Header Template</a> tool allows you to design a single header for both desktop and mobile screens using its responsive design features', 'courto'),
                            ['a' => ['href' => true, 'target' => true]]
                        ),
                        esc_url(admin_url('edit.php?post_type=header'))
                    ),
                    'default' => 'default',
                    'required' => [
                        ['mobile_header', '=', '1']
                    ],
                ],
                [
                    'id' => 'mobile_drawer_header_building_tool',
                    'title' => esc_html__('Mobile Drawer Tool', 'courto'),
                    'type' => 'select',
                    'options' => [
                        'default' => esc_html__('WGL Builder (Deprecated)', 'courto'),
                        'elementor' => esc_html__('Elementor Templates (Recommended)', 'courto')
                    ],
                    'default' => 'default',
                    'required' => [
                        ['mobile_header', '=', '1']
                    ],
                ],
                [
                    'id' => 'mobile_drawer_header_page_select',
                    'type' => 'select',
                    'title' => esc_html__('Drawer template', 'courto'),
                    'desc' => sprintf(
                        wp_kses(
                            __('Area for placing a mobile menu. You can edit/create it in <a href="%s" target="_blank">Templates->WGL Mobile Drawer</a>', 'courto'),
                            ['a' => ['href' => true, 'target' => true]]
                        ),
                        esc_url(admin_url('edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=wgl-mobile-drawer'))
                    ),
                    'required' => ['mobile_drawer_header_building_tool', '=', 'elementor'],
                    'data' => 'posts',
                    'args' => [
                        'post_type' => 'elementor_library',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                        'meta_query' => [
                            [
                                'key' => '_elementor_template_type',
                                'value' => 'wgl-mobile-drawer',
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'header_mobile_appearance-start',
                    'title' => esc_html__('Appearance', 'courto'),
                    'type' => 'section',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'indent' => true,
                ],
                [
                    'id' => 'header_mobile_height',
                    'title' => esc_html__('Header Height', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => '60'],
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'header_mobile_full_width',
                    'title' => esc_html__('Full Width Header', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_sticky',
                    'title' => esc_html__('Mobile Sticky Header', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_over_content',
                    'title' => esc_html__('Header Over Content', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_background',
                    'title' => esc_html__('Header Background', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_color',
                    'title' => esc_html__('Header Text Color', 'courto'),
                    'type' => 'color',
                    'transparent' => false,
                    'default' => '#ffffff',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_border_color',
                    'title' => esc_html__('Header Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '0.2',
                        'rgba' => 'rgba(131,131,131,0.2)',
                        'color' => '#838383',
                    ],
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'header_mobile_appearance-end',
                    'type' => 'section',
                    'indent' => false,
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'header_mobile_menu-start',
                    'title' => esc_html__('Menu', 'courto'),
                    'type' => 'section',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'indent' => true,
                ],
                [
                    'id' => 'mobile_position',
                    'title' => esc_html__('Menu Occurrence', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                ],
                [
                    'id' => 'custom_mobile_menu',
                    'title' => esc_html__('Custom Mobile Menu', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                ],
                [
                    'id' => 'mobile_menu',
                    'type' => 'select',
                    'title' => esc_html__('Mobile Menu', 'courto'),
                    'required' => ['custom_mobile_menu', '=', '1'],
                    'select2' => ['allowClear' => false],
                    'options' => $menus = wgl_get_custom_menu(),
                    'default' => reset($menus),
                ],
                [
                    'id' => 'mobile_sub_menu_color',
                    'title' => esc_html__('Menu Text Color', 'courto'),
                    'type' => 'color',
                    'transparent' => false,
                    'default' => '#cdcdcd',
                ],
                [
                    'id' => 'mobile_sub_menu_color_active',
                    'title' => esc_html__('Active Menu Text Color', 'courto'),
                    'type' => 'color',
                    'transparent' => false,
                    'default' => '#FB651B',
                ],
                [
                    'id' => 'mobile_sub_menu_background',
                    'title' => esc_html__('Menu Background', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                ],
                [
                    'id' => 'mobile_sub_menu_overlay',
                    'title' => esc_html__('Menu Overlay', 'courto'),
                    'type' => 'color_rgba',
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,0.8)',
                        'color' => '#181818',
                    ],
                ],
                [
                    'id' => 'header_mobile_menu-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'mobile_header_layout',
                    'title' => esc_html__('Mobile Builder', 'courto'),
                    'type' => 'custom_header_mobile_builder',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Organize the layout of the mobile header', 'courto'),
                    'compiler' => 'true',
                    'full_width' => true,
                    'options' => [
                        'items' => $header_builder_items['mobile'],
                        'Left area' => [
                            'menu' => ['title' => esc_html__('Hamburger Menu', 'courto'), 'settings' => false],
                        ],
                        'Center area' => [
                            'logo' => ['title' => esc_html__('Logo', 'courto'), 'settings' => false],
                        ],
                        'Right area' => [
                            'cart' => ['title' => esc_html__('Cart', 'courto'), 'settings' => true],
                        ],
                    ],
                    'default' => [
                        'items' => $header_builder_items['mobile'],
                        'Left area' => [
                            'menu' => ['title' => esc_html__('Hamburger Menu', 'courto'), 'settings' => false],
                        ],
                        'Center area' => [
                            'logo' => ['title' => esc_html__('Logo', 'courto'), 'settings' => false],
                        ],
                        'Right area' => [
                            'cart' => ['title' => esc_html__('Cart', 'courto'), 'settings' => true],
                        ],
                    ],
                ],
                [
                    'id' => 'mobile_header_bar_html1_editor',
                    'title' => esc_html__('HTML Element 1 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_bar_html2_editor',
                    'title' => esc_html__('HTML Element 2 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_bar_html3_editor',
                    'title' => esc_html__('HTML Element 3 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_bar_html4_editor',
                    'title' => esc_html__('HTML Element 4 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_bar_html5_editor',
                    'title' => esc_html__('HTML Element 5 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_bar_html6_editor',
                    'title' => esc_html__('HTML Element 6 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_spacer1',
                    'title' => esc_html__('Spacer 1 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_spacer2',
                    'title' => esc_html__('Spacer 2 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_spacer3',
                    'title' => esc_html__('Spacer 3 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_spacer4',
                    'title' => esc_html__('Spacer 4 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_spacer5',
                    'title' => esc_html__('Spacer 5 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_spacer6',
                    'title' => esc_html__('Spacer 6 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_socials_font_size',
                    'title' => esc_html__('Font Size', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_socials_space',
                    'title' => esc_html__('Space Between', 'courto'),
                    'type' => 'dimensions',
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => '10'],
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id'       => 'mobile_header_socials_target',
                    'type'     => 'checkbox',
                    'title'    => esc_html__('Open in new window', 'courto'),
                    'default'  => '1',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_socials_padding',
                    'title' => esc_html__('Padding', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'all' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_socials_radius',
                    'title' => esc_html__('Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id'         => 'mobile_header_socials',
                    'type'       => 'social_profiles',
                    'title'  => esc_html__('Social Links', 'courto'),
                    'subtitle'   => esc_html__('Click an icon to activate it, drag and drop to change the icon order.', 'courto'),
                    'customizer' => false,
                    'include'    => ['facebook', 'twitter', 'linkedin', 'google-plus', 'dribbble', 'flickr', 'instagram', 'pRobotoest', 'skype', 'tumblr', 'youtube', 'spotify', 'telegram', 'whatsapp'],
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_header_button1_title',
                    'title' => esc_html__('Button Text', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'default' => esc_html__('Contact Us', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_link',
                    'title' => esc_html__('Link', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'default' => '#',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_target',
                    'title' => esc_html__('Open link in a new tab', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_size',
                    'title' => esc_html__('Button Size', 'courto'),
                    'type' => 'select',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'options' => [
                        'sm' => esc_html__('Small', 'courto'),
                        'md' => esc_html__('Medium', 'courto'),
                        'lg' => esc_html__('Large', 'courto'),
                        'xl' => esc_html__('Extra Large', 'courto'),
                    ],
                    'default' => 'md',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_radius',
                    'title' => esc_html__('Button Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_custom',
                    'title' => esc_html__('Customize Button', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_color_txt',
                    'title' => esc_html__('Text Color Idle', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_hover_color_txt',
                    'title' => esc_html__('Text Color Hover', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_bg',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_hover_bg',
                    'title' => esc_html__('Hover Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_border',
                    'title' => esc_html__('Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button1_hover_border',
                    'title' => esc_html__('Hover Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_title',
                    'title' => esc_html__('Button Text', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'default' => esc_html__('Contact Us', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_link',
                    'title' => esc_html__('Link', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_target',
                    'title' => esc_html__('Open link in a new tab', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_size',
                    'title' => esc_html__('Button Size', 'courto'),
                    'type' => 'select',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'options' => [
                        'sm' => esc_html__('Small', 'courto'),
                        'md' => esc_html__('Medium', 'courto'),
                        'lg' => esc_html__('Large', 'courto'),
                        'xl' => esc_html__('Extra Large', 'courto'),
                    ],
                    'default' => 'md',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_radius',
                    'title' => esc_html__('Button Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_custom',
                    'title' => esc_html__('Customize Button', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_color_txt',
                    'title' => esc_html__('Text Color Idle', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_hover_color_txt',
                    'title' => esc_html__('Text Color Hover', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_bg',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_hover_bg',
                    'title' => esc_html__('Hover Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_border',
                    'title' => esc_html__('Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_header_button2_hover_border',
                    'title' => esc_html__('Hover Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'header_mobile_drawer-start',
                    'type' => 'section',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                    'indent' => true,
                ],
                [
                    'id' => 'header_mobile_drawer-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'mobile_content_header_layout',
                    'title' => esc_html__('Mobile Drawer Content', 'courto'),
                    'type' => 'custom_header_mobile_builder',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Organize the layout of the mobile header', 'courto'),
                    'compiler' => 'true',
                    'full_width' => true,
                    'options' => [
                        'items' => $header_builder_items['mobile_drawer'],
                        'Left area' => [
                            'logo' => ['title' => esc_html__('Logo', 'courto'), 'settings' => false],
                            'menu' => ['title' => esc_html__('Menu', 'courto'), 'settings' => false],
                            'item_search' => ['title' => esc_html__('Search', 'courto'), 'settings' => false],
                        ],
                    ],
                    'default' => [
                        'items' => $header_builder_items['mobile_drawer'],
                        'Left area' => [
                            'logo' => ['title' => esc_html__('Logo', 'courto'), 'settings' => false],
                            'menu' => ['title' => esc_html__('Menu', 'courto'), 'settings' => false],
                            'item_search' => ['title' => esc_html__('Search', 'courto'), 'settings' => false],
                        ],
                    ],
                ],
                [
                    'id' => 'mobile_drawer_header_bar_html1_editor',
                    'title' => esc_html__('HTML Element 1 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_bar_html2_editor',
                    'title' => esc_html__('HTML Element 2 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_bar_html3_editor',
                    'title' => esc_html__('HTML Element 3 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_bar_html4_editor',
                    'title' => esc_html__('HTML Element 4 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_bar_html5_editor',
                    'title' => esc_html__('HTML Element 5 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_bar_html6_editor',
                    'title' => esc_html__('HTML Element 6 Editor', 'courto'),
                    'type' => 'ace_editor',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'mode' => 'html',
                    'default' => '',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_spacer1',
                    'title' => esc_html__('Spacer 1 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_spacer2',
                    'title' => esc_html__('Spacer 2 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_spacer3',
                    'title' => esc_html__('Spacer 3 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_spacer4',
                    'title' => esc_html__('Spacer 4 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_spacer5',
                    'title' => esc_html__('Spacer 5 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_spacer6',
                    'title' => esc_html__('Spacer 6 Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 25],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_socials_font_size',
                    'title' => esc_html__('Font Size', 'courto'),
                    'type' => 'dimensions',
                    'width' => false,
                    'height' => true,
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_socials_space',
                    'title' => esc_html__('Space Between', 'courto'),
                    'type' => 'dimensions',
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => '10'],
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id'       => 'mobile_drawer_header_socials_target',
                    'type'     => 'checkbox',
                    'title'    => esc_html__('Open in new window', 'courto'),
                    'default'  => '1',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_socials_padding',
                    'title' => esc_html__('Padding', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'all' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_socials_radius',
                    'title' => esc_html__('Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id'        => 'mobile_drawer_header_socials',
                    'type'      => 'social_profiles',
                    'title'  => esc_html__('Social Links', 'courto'),
                    'subtitle'  => esc_html__('Click an icon to activate it, drag and drop to change the icon order.', 'courto'),
                    'customizer' => false,
                    'include'    => ['facebook', 'twitter', 'linkedin', 'google-plus', 'dribbble', 'flickr', 'instagram', 'pRobotoest', 'skype', 'tumblr', 'youtube', 'spotify', 'telegram', 'whatsapp'],
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_drawer_header_button1_title',
                    'title' => esc_html__('Button Text', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'default' => esc_html__('Contact Us', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_link',
                    'title' => esc_html__('Link', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'default' => '#',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_target',
                    'title' => esc_html__('Open link in a new tab', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_size',
                    'title' => esc_html__('Button Size', 'courto'),
                    'type' => 'select',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'options' => [
                        'sm' => esc_html__('Small', 'courto'),
                        'md' => esc_html__('Medium', 'courto'),
                        'lg' => esc_html__('Large', 'courto'),
                        'xl' => esc_html__('Extra Large', 'courto'),
                    ],
                    'default' => 'md',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_radius',
                    'title' => esc_html__('Button Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_custom',
                    'title' => esc_html__('Customize Button', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_color_txt',
                    'title' => esc_html__('Text Color Idle', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_hover_color_txt',
                    'title' => esc_html__('Text Color Hover', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_bg',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_hover_bg',
                    'title' => esc_html__('Hover Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#ffffff',
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_border',
                    'title' => esc_html__('Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button1_hover_border',
                    'title' => esc_html__('Hover Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button1_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'color' => '#181818',
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)'
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_title',
                    'title' => esc_html__('Button Text', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'default' => esc_html__('Contact Us', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_link',
                    'title' => esc_html__('Link', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_target',
                    'title' => esc_html__('Open link in a new tab', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'default' => true,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_size',
                    'title' => esc_html__('Button Size', 'courto'),
                    'type' => 'select',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'options' => [
                        'sm' => esc_html__('Small', 'courto'),
                        'md' => esc_html__('Medium', 'courto'),
                        'lg' => esc_html__('Large', 'courto'),
                        'xl' => esc_html__('Extra Large', 'courto'),
                    ],
                    'default' => 'md',
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_radius',
                    'title' => esc_html__('Button Border Radius', 'courto'),
                    'type' => 'text',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'desc' => esc_html__('Value in pixels.', 'courto'),
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_custom',
                    'title' => esc_html__('Customize Button', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['mobile_header', '=', '1'],
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                    'default' => false,
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_color_txt',
                    'title' => esc_html__('Text Color Idle', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_hover_color_txt',
                    'title' => esc_html__('Text Color Hover', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_bg',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_hover_bg',
                    'title' => esc_html__('Hover Background Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(255,255,255,1)',
                        'color' => '#ffffff',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_border',
                    'title' => esc_html__('Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
                [
                    'id' => 'mobile_drawer_header_button2_hover_border',
                    'title' => esc_html__('Hover Border Color', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['mobile_drawer_header_button2_custom', '=', '1'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                    'customizer' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'logo',
            'title' => esc_html__('Logo', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'header_logo',
                    'title' => esc_html__('Default Header Logo', 'courto'),
                    'type' => 'media',
                ],
                [
                    'id' => 'logo_height_custom',
                    'title' => esc_html__('Limit Default Logo Height', 'courto'),
                    'type' => 'switch',
                    'required' => ['header_logo', '!=', ''],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'logo_height',
                    'title' => esc_html__('Default Logo Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['logo_height_custom', '=', '1'],
                    'height' => true,
                    'width' => false,
                    'default' => ['height' => 90],
                ],
                [
                    'id' => 'sticky_header_logo',
                    'title' => esc_html__('Sticky Header Logo', 'courto'),
                    'type' => 'media',
                    'required' => [
                        ['header_building_tool', '=', 'default'],
                        ['header_sticky_page_select', '=', '']
                    ],
                ],
                [
                    'id' => 'sticky_logo_height_custom',
                    'title' => esc_html__('Limit Sticky Logo Height', 'courto'),
                    'type' => 'switch',
                    'required' => ['sticky_header_logo', '!=', ''],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'sticky_logo_height',
                    'title' => esc_html__('Sticky Header Logo Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['sticky_logo_height_custom', '=', '1'],
                    'height' => true,
                    'width' => false,
                    'default' => ['height' => 90],
                ],
                [
                    'id' => 'logo_mobile',
                    'title' => esc_html__('Mobile Header Logo', 'courto'),
                    'type' => 'media',
                    'required' => [
                        ['mobile_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_logo_height_custom',
                    'title' => esc_html__('Limit Mobile Logo Height', 'courto'),
                    'type' => 'switch',
                    'required' => ['logo_mobile', '!=', ''],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'mobile_logo_height',
                    'title' => esc_html__('Mobile Logo Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['mobile_logo_height_custom', '=', '1'],
                    'height' => true,
                    'width' => false,
                    'default' => ['height' => 60],
                ],
                [
                    'id' => 'logo_mobile_menu',
                    'title' => esc_html__('Mobile Menu Logo', 'courto'),
                    'type' => 'media',
                    'required' => [
                        ['mobile_drawer_header_building_tool', '=', 'default'],
                    ],
                ],
                [
                    'id' => 'mobile_logo_menu_height_custom',
                    'title' => esc_html__('Limit Mobile Menu Logo Height', 'courto'),
                    'type' => 'switch',
                    'required' => ['logo_mobile_menu', '!=', ''],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'mobile_logo_menu_height',
                    'title' => esc_html__('Mobile Menu Logo Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['mobile_logo_menu_height_custom', '=', '1'],
                    'height' => true,
                    'width' => false,
                    'default' => ['height' => 60],
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'page_title',
            'title' => esc_html__('Page Title', 'courto'),
            'icon' => 'el el-home-alt',
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'page_title_settings',
            'title' => esc_html__('General', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'page_title_switch',
                    'title' => esc_html__('Use Page Titles?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'page_title-start',
                    'title' => esc_html__('Appearance', 'courto'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', '1'],
                    'indent' => true,
                ],
                [
                    'id' => 'page_title_bg_switch',
                    'title' => esc_html__('Use Background Image/Color?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'page_title_bg_image',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'required' => ['page_title_bg_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => true,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center bottom',
                        'background-color' => '#9F683A',
                    ],
                ],
                [
                    'id' => 'page_title_height',
                    'title' => esc_html__('Min Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['page_title_bg_switch', '=', true],
                    'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'courto'),
                    'width' => false,
                    'height' => true,
                    'default' => ['height' => 420],
                ],
                [
                    'id' => 'page_title_border',
                    'title' => esc_html__('Border', 'courto'),
                    'type' => 'border',
                    'required' => ['page_title_switch', '=', true],
                    'default'  => [
                        'border-color'  => '#9F683A',
                        'border-style'  => 'solid',
                        'border-top'    => '0px',
                        'border-right'  => '0px',
                        'border-bottom' => '0px',
                        'border-left'   => '0px'
                    ]
                ],
                [
                    'id' => 'page_title_padding',
                    'title' => esc_html__('Paddings Top/Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => false,
                    'right' => false,
                    'default' => [
                        'padding-top' => '234',
                        'padding-bottom' => '20',
                    ],
                ],
                [
                    'id' => 'page_title_margin',
                    'title' => esc_html__('Margin Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => true,
                    'top' => false,
                    'left' => false,
                    'right' => false,
                    'default' => ['margin-bottom' => '60'],
                ],
                [
                    'id' => 'page_title_full_width_switch',
                    'title' => esc_html__('Page Title Full Width', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'page_title_width',
                    'title' => esc_html__('Max Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['page_title_full_width_switch', '=', true],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 1820],
                ],
                [
                    'id' => 'page_title_align',
                    'title' => esc_html__('Title Alignment', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                ],
                [
                    'id' => 'page_title_breadcrumbs_switch',
                    'title' => esc_html__('Breadcrumbs', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'page_title_breadcrumbs_block_switch',
                    'title' => esc_html__('Breadcrumbs Full Width', 'courto'),
                    'type' => 'switch',
                    'required' => ['page_title_breadcrumbs_switch', '=', true],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'page_title_breadcrumbs_align',
                    'title' => esc_html__('Breadcrumbs Alignment', 'courto'),
                    'type' => 'button_set',
                    'required' => ['page_title_breadcrumbs_block_switch', '=', true],
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                ],
                [
                    'id' => 'page_title_parallax',
                    'title' => esc_html__('Parallax Effect', 'courto'),
                    'type' => 'switch',
                    'default' => false,
                ],
                [
                    'id' => 'page_title_parallax_speed',
                    'title' => esc_html__('Parallax Speed', 'courto'),
                    'type' => 'slider',
                    'required' => ['page_title_parallax', '=', '1'],
                    'min' => -5,
                    'max' => 5,
                    'step' => 0.1,
                    'default' => 0.3,
                    'resolution'    => 0.1,
                    'display_value' => 'text',
                ],
                [
                    'id' => 'page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'page_title_typography',
            'title' => esc_html__('Typography', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'page_title_font',
                    'title' => esc_html__('Page Title Font', 'courto'),
                    'type' => 'custom_typography',
                    'font-size' => true,
                    'google' => false,
                    'font-weight' => true,
                    'font-family' => false,
                    'font-style' => false,
                    'color' => true,
                    'line-height' => true,
                    'letter-spacing' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'all_styles' => false,
                    'default' => [
                        'font-weight' => '800',
                        'font-size' => '96px',
                        'line-height' => '136px',
                        'color' => '#ffffff',
                        'letter-spacing' => '-0.04',
                    ],
                ],
                [
                    'id' => 'page_title_breadcrumbs_font',
                    'title' => esc_html__('Breadcrumbs Font', 'courto'),
                    'type' => 'custom_typography',
                    'font-size' => true,
                    'google' => false,
                    'font-weight' => true,
                    'font-family' => false,
                    'font-style' => false,
                    'color' => true,
                    'line-height' => true,
                    'letter-spacing' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'all_styles' => false,
                    'default' => [
                        'font-weight' => '600',
                        'font-size' => '14px',
                        'color' => '#ffffff',
                        'line-height' => '30px',
                        'letter-spacing' => '-0.06',
                    ],
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'title' => esc_html__('Responsive', 'courto'),
            'id' => 'page_title_responsive',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'page_title_resp_switch',
                    'title' => esc_html__('Responsive Settings', 'courto'),
                    'type' => 'switch',
                    'default' => true,
                ],
                [
                    'id' => 'page_title_resp_resolution',
                    'title' => esc_html__('Screen breakpoint', 'courto'),
                    'type' => 'slider',
                    'required' => ['page_title_resp_switch', '=', '1'],
                    'desc' => esc_html__('Use responsive settings on screens smaller then choosed breakpoint.', 'courto'),
                    'display_value' => 'text',
                    'min' => 1,
                    'max' => 1700,
                    'step' => 1,
                    'default' => 1200,
                ],
                [
                    'id' => 'page_title_resp_padding',
                    'title' => esc_html__('Page Title Paddings', 'courto'),
                    'type' => 'spacing',
                    'required' => ['page_title_resp_switch', '=', '1'],
                    'mode' => 'padding',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => false,
                    'right' => false,
                    'default' => [
                        'padding-top' => '100',
                        'padding-bottom' => '40',
                    ],
                ],
                [
                    'id' => 'page_title_resp_margin',
                    'title' => esc_html__('Margin Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'required' => ['page_title_resp_switch', '=', '1'],
                    'all' => false,
                    'bottom' => true,
                    'top' => false,
                    'left' => false,
                    'right' => false,
                    'default' => ['margin-bottom' => '30'],
                ],
                [
                    'id' => 'page_title_resp_font',
                    'title' => esc_html__('Page Title Font', 'courto'),
                    'type' => 'custom_typography',
                    'required' => ['page_title_resp_switch', '=', '1'],
                    'google' => false,
                    'all_styles' => false,
                    'font-family' => false,
                    'font-style' => false,
                    'font-size' => true,
                    'font-weight' => false,
                    'font-backup' => false,
                    'line-height' => true,
                    'text-align' => false,
                    'color' => true,
                    'default' => [
                        'font-size' => '42px',
                        'line-height' => '44px',
                        'color' => '#ffffff',
                    ],
                ],
                [
                    'id' => 'page_title_resp_breadcrumbs_switch',
                    'title' => esc_html__('Breadcrumbs', 'courto'),
                    'type' => 'switch',
                    'required' => ['page_title_resp_switch', '=', '1'],
                    'default' => true,
                ],
                [
                    'id' => 'page_title_resp_breadcrumbs_font',
                    'title' => esc_html__('Breadcrumbs Font', 'courto'),
                    'type' => 'custom_typography',
                    'required' => ['page_title_resp_breadcrumbs_switch', '=', '1'],
                    'google' => false,
                    'all_styles' => false,
                    'font-family' => false,
                    'font-style' => false,
                    'font-size' => true,
                    'font-weight' => false,
                    'font-backup' => false,
                    'line-height' => true,
                    'text-align' => false,
                    'color' => true,
                    'default' => [
                        'font-size' => '13px',
                        'color' => '#ffffff',
                        'line-height' => '26px',
                    ],
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'footer',
            'title' => esc_html__('Footer', 'courto'),
            'icon' => 'fas fa-window-maximize el-rotate-180',
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'footer-general',
            'title' => esc_html__('General', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'footer_switch',
                    'title' => esc_html__('Footer', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Disable', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'footer-start',
                    'title' => esc_html__('Layout', 'courto'),
                    'type' => 'section',
                    'required' => ['footer_switch', '=', '1'],
                    'indent' => true,
                ],
                [
                    'id' => 'footer_building_tool',
                    'title' => esc_html__('Layout Building Tool', 'courto'),
                    'type' => 'select',
                    'options' => [
                        'widgets' => esc_html__('Wordpress Widgets', 'courto'),
                        'elementor' => esc_html__('Elementor', 'courto'),
                    ],
                    'default' => 'widgets',
                ],
                [
                    'id' => 'footer_page_select',
                    'title' => esc_html__('Footer Template', 'courto'),
                    'type' => 'select',
                    'required' => ['footer_building_tool', '=', 'elementor'],
                    'data' => 'posts',
                    'args' => [
                        'post_type' => 'footer',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ],
                ],
                [
                    'id' => 'widget_columns',
                    'title' => esc_html__('Columns', 'courto'),
                    'type' => 'button_set',
                    'required' => ['footer_building_tool', '=', 'widgets'],
                    'options' => [
                        '1' => esc_html('1'),
                        '2' => esc_html('2'),
                        '3' => esc_html('3'),
                        '4' => esc_html('4'),
                    ],
                    'default' => '4',
                ],
                [
                    'id' => 'widget_columns_2',
                    'title' => esc_html__('Columns Layout', 'courto'),
                    'type' => 'image_select',
                    'required' => ['widget_columns', '=', '2'],
                    'options' => [
                        '6-6' => [
                            'alt' => '50-50',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/50-50.png'
                        ],
                        '3-9' => [
                            'alt' => '25-75',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/25-75.png'
                        ],
                        '9-3' => [
                            'alt' => '75-25',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/75-25.png'
                        ],
                        '4-8' => [
                            'alt' => '33-66',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/33-66.png'
                        ],
                        '8-4' => [
                            'alt' => '66-33',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/66-33.png'
                        ]
                    ],
                    'default' => '6-6',
                ],
                [
                    'id' => 'widget_columns_3',
                    'title' => esc_html__('Columns Layout', 'courto'),
                    'type' => 'image_select',
                    'required' => ['widget_columns', '=', '3'],
                    'options' => [
                        '4-4-4' => [
                            'alt' => '33-33-33',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/33-33-33.png'
                        ],
                        '3-3-6' => [
                            'alt' => '25-25-50',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/25-25-50.png'
                        ],
                        '3-6-3' => [
                            'alt' => '25-50-25',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/25-50-25.png'
                        ],
                        '6-3-3' => [
                            'alt' => '50-25-25',
                            'img' => get_template_directory_uri() . '/core/admin/img/options/50-25-25.png'
                        ],
                    ],
                    'default' => '4-4-4',
                ],
                [
                    'id' => 'footer_spacing',
                    'title' => esc_html__('Paddings', 'courto'),
                    'type' => 'spacing',
                    'required' => ['footer_building_tool', '=', 'widgets'],
                    'output' => ['.wgl-footer'],
                    'all' => false,
                    'mode' => 'padding',
                    'units' => 'px',
                    'default' => [
                        'padding-top' => '50px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px'
                    ],
                ],
                [
                    'id' => 'footer_full_width',
                    'title' => esc_html__('Full Width On/Off', 'courto'),
                    'type' => 'switch',
                    'required' => ['footer_building_tool', '=', 'widgets'],
                    'default' => false,
                ],
                [
                    'id' => 'footer-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'footer-start-styles',
                    'title' => esc_html__('Footer Styling', 'courto'),
                    'type' => 'section',
                    'required' => [
                        ['footer_switch', '=', '1'],
                        ['footer_building_tool', '=', 'widgets'],
                    ],
                    'indent' => true,
                ],
                [
                    'id' => 'footer_bg_image',
                    'title' => esc_html__('Background Image', 'courto'),
                    'type' => 'background',
                    'required' => [
                        ['footer_switch', '=', '1'],
                        ['footer_building_tool', '=', 'widgets'],
                    ],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                    ],
                ],
                [
                    'id' => 'footer_align',
                    'title' => esc_html__('Content Align', 'courto'),
                    'type' => 'button_set',
                    'required' => [
                        ['footer_switch', '=', '1'],
                        ['footer_building_tool', '=', 'widgets'],
                    ],
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'center',
                ],
                [
                    'id' => 'footer_bg_color',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color',
                    'required' => [
                        ['footer_switch', '=', '1'],
                        ['footer_building_tool', '=', 'widgets'],
                    ],
                    'transparent' => false,
                    'default' => '#181818',
                ],
                [
                    'id' => 'footer_heading_color',
                    'title' => esc_html__('Headings color', 'courto'),
                    'type' => 'color',
                    'required' => [
                        ['footer_switch', '=', '1'],
                        ['footer_building_tool', '=', 'widgets'],
                    ],
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'footer_text_color',
                    'title' => esc_html__('Content color', 'courto'),
                    'type' => 'color',
                    'required' => [
                        ['footer_switch', '=', '1'],
                        ['footer_building_tool', '=', 'widgets'],
                    ],
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'footer_add_border',
                    'title' => esc_html__('Add Border Top', 'courto'),
                    'type' => 'switch',
                    'required' => [
                        ['footer_switch', '=', '1'],
                        ['footer_building_tool', '=', 'widgets'],
                    ],
                    'default' => false,
                ],
                [
                    'id' => 'footer_border_color',
                    'title' => esc_html__('Border color', 'courto'),
                    'type' => 'color',
                    'required' => ['footer_add_border', '=', '1'],
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'footer-end-styles',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'footer-copyright',
            'title' => esc_html__('Copyright', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'copyright_switch',
                    'type' => 'switch',
                    'title' => esc_html__('Copyright', 'courto'),
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Disable', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'copyright-start',
                    'type' => 'section',
                    'title' => esc_html__('Copyright Settings', 'courto'),
                    'indent' => true,
                    'required' => ['copyright_switch', '=', '1'],
                ],
                [
                    'id' => 'copyright_editor',
                    'title' => esc_html__('Editor', 'courto'),
                    'type' => 'editor',
                    'required' => ['copyright_switch', '=', '1'],
                    'args' => [
                        'wpautop' => false,
                        'media_buttons' => false,
                        'textarea_rows' => 2,
                        'teeny' => false,
                        'quicktags' => true,
                    ],
                    'default' => '<p>Copyright © 2026 Courto by WebGeniusLab. All Rights Reserved</p>',
                ],
                [
                    'id' => 'copyright_text_color',
                    'title' => esc_html__('Text Color', 'courto'),
                    'type' => 'color',
                    'required' => ['copyright_switch', '=', '1'],
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'copyright_bg_color',
                    'title' => esc_html__('Background Color', 'courto'),
                    'type' => 'color',
                    'required' => ['copyright_switch', '=', '1'],
                    'transparent' => false,
                    'default' => '#000000',
                ],
                [
                    'id' => 'copyright_spacing',
                    'type' => 'spacing',
                    'title' => esc_html__('Paddings', 'courto'),
                    'required' => ['copyright_switch', '=', '1'],
                    'mode' => 'padding',
                    'all' => false,
                    'left' => false,
                    'right' => false,
                    'default' => [
                        'padding-top' => '20',
                        'padding-bottom' => '20',
                    ],
                ],
                [
                    'id' => 'copyright-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'blog-option',
            'title' => esc_html__('Blog', 'courto'),
            'icon' => 'el el-bullhorn',
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'blog-list-option',
            'title' => esc_html__('Archive', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'blog_list_body-start',
                    'title' => esc_html__('Body', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'blog_body_color_bg',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'contain',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center top',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'blog_list_body-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'blog_list_page_title-start',
                    'title' => esc_html__('Page Title', 'courto'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', '1'],
                    'indent' => true,
                ],
                [
                    'id' => 'post_archive__page_title_bg_image',
                    'title' => esc_html__('Background Image', 'courto'),
                    'type' => 'background',
                    'background-color' => true,
                    'preview_media' => true,
                    'preview' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'blog_list_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'blog_list_sidebar-start',
                    'title' => esc_html__('Sidebar', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'blog_list_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'courto'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ]
                    ],
                    'default' => 'none'
                ],
                [
                    'id' => 'blog_list_sidebar_def',
                    'title' => esc_html__('Sidebar Template', 'courto'),
                    'type' => 'select',
                    'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'blog_list_sidebar_def_width',
                    'title' => esc_html__('Sidebar Width', 'courto'),
                    'type' => 'button_set',
                    'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => esc_html( '25%' ),
                        '8' => esc_html( '33%' ),
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'blog_list_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'courto'),
                    'type' => 'switch',
                    'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_sidebar_gap',
                    'title' => esc_html__( 'Sidebar Side Gap', 'courto' ),
                    'type' => 'select',
                    'required' => [ 'blog_list_sidebar_layout', '!=', 'none' ],
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
                    'default' => 'def',
                ],
                [
                    'id' => 'blog_list_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'blog_list_appearance-start',
                    'title' => esc_html__('Appearance', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'blog_list_columns',
                    'title' => esc_html__('Columns in Archive', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        '12' => esc_html__('One', 'courto'),
                        '6' => esc_html__('Two', 'courto'),
                        '4' => esc_html__('Three', 'courto'),
                        '3' => esc_html__('Four', 'courto'),
                    ],
                    'default' => '12',
                ],
                [
                    'id' => 'blog_list_likes',
                    'title' => esc_html__('Likes', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_views',
                    'title' => esc_html__('Views', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_share',
                    'title' => esc_html__('Shares', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_hide_media',
                    'title' => esc_html__('Hide Media?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_hide_title',
                    'title' => esc_html__('Hide Title?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_hide_content',
                    'title' => esc_html__('Hide Content?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_post_listing_content',
                    'title' => esc_html__('Limit the characters amount in Content?', 'courto'),
                    'type' => 'switch',
                    'required' => ['blog_list_hide_content', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_letter_count',
                    'title' => esc_html__('Characters amount to be displayed in Content', 'courto'),
                    'type' => 'text',
                    'required' => ['blog_post_listing_content', '=', true],
                    'default' => '85',
                ],
                [
                    'id' => 'blog_list_read_more',
                    'title' => esc_html__('Hide Read More Button?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_meta',
                    'title' => esc_html__('Hide all post-meta?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_meta_author',
                    'title' => esc_html__('Hide post-meta author?', 'courto'),
                    'type' => 'switch',
                    'required' => ['blog_list_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_meta_comments',
                    'title' => esc_html__('Hide post-meta comments?', 'courto'),
                    'type' => 'switch',
                    'required' => ['blog_list_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_meta_categories',
                    'title' => esc_html__('Hide post-meta categories?', 'courto'),
                    'type' => 'switch',
                    'required' => ['blog_list_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_meta_date',
                    'title' => esc_html__('Hide post-meta date?', 'courto'),
                    'type' => 'switch',
                    'required' => ['blog_list_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_list_appearance-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'blog-single-option',
            'title' => esc_html__('Single', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'blog_single_body-start',
                    'title' => esc_html__('Body', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'blog_single_body_color_bg',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'contain',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center top',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'blog_single_body-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'post_single_type_layout',
                    'title' => esc_html__('Default Post Layout', 'courto'),
                    'type' => 'button_set',
                    'desc' => esc_html__('Note: each Post can be separately customized within its Metaboxes section.', 'courto'),
                    'options' => [
                        '1' => esc_html__('Title First', 'courto'),
                        '2' => esc_html__('Image First', 'courto'),
                        '3' => esc_html__('Overlay Image', 'courto')
                    ],
                    'default' => '3',
                ],
                [
                    'id' => 'blog_single_header-start',
                    'title' => esc_html__('Header', 'courto'),
                    'type' => 'section',
                    'required' => ['header_building_tool', '=', 'elementor'],
                    'indent' => true,
                ],
                [
                    'id' => 'blog_header_conditional',
                    'title' => esc_html__('Header', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Default', 'courto'),
                    'off' => esc_html__('Custom', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'blog_single_header_page_select',
                    'type' => 'select',
                    'title' => esc_html__('Header Template', 'courto'),
                    'required' => ['blog_header_conditional', '=', ''],
                    'desc' => wp_kses(
                        sprintf(
                            '%s <a href="%s" target="_blank">%s</a> %s<br> %s',
                            __('Selected Template will be used for all pages by default. You can edit/create Header Template in the', 'courto'),
                            admin_url('edit.php?post_type=header'),
                            __('Header Templates', 'courto'),
                            __('dashboard tab.', 'courto'),
                            courto_quick_tip(
                                sprintf(
                                    __('Note: fine tuning is available through the Elementor\'s <code>Post Settings</code> tab, which is located <a href="%s" target="_blank">here</a>', 'courto'),
                                    get_template_directory_uri() . '/core/admin/img/dashboard/quick_tip__header_extra_options.png'
                                )
                            )
                        ),
                        ['a' => ['href' => true, 'target' => true], 'br' => [], 'span' => ['class' => true], 'i' => ['class' => true], 'code' => []]
                    ),
                    'data' => 'posts',
                    'args' => [
                        'post_type' => 'header',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ],
                ],
                [
                    'id' => 'blog_single_page_title-start',
                    'title' => esc_html__('Page Title', 'courto'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', '1'],
                    'indent' => true,
                ],
                [
                    'id' => 'blog_title_conditional',
                    'title' => esc_html__('Page Title Text', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Post Type Name', 'courto'),
                    'off' => esc_html__('Post Title', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'blog_single__page_title_breadcrumbs_switch',
                    'title' => esc_html__('Breadcrumbs', 'courto'),
                    'type' => 'switch',
                    'required' => ['post_single_type_layout', '!=', '3'],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'post_single__page_title_bg_switch',
                    'title' => esc_html__('Use Background Image/Color?', 'courto'),
                    'type' => 'switch',
                    'required' => ['post_single_type_layout', '!=', '3'],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'post_single__page_title_bg_image',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'required' => ['post_single_type_layout', '!=', '3'],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'post_single_layout_3_bg_image',
                    'type' => 'background',
                    'title' => esc_html__('Default Background', 'courto'),
                    'required' => ['post_single_type_layout', '=', '3'],
                    'desc' => esc_html__('Note: If Featured Image doesn\'t exist.', 'courto'),
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'background-repeat' => false,
                    'background-size' => false,
                    'background-attachment' => false,
                    'background-position' => false,
                    'default' => [
                        'background-color' => '#9F683A',
                    ],
                ],
                [
                    'id' => 'single_padding_layout_3',
                    'type' => 'spacing',
                    'title' => esc_html__('Padding Top/Bottom', 'courto'),
                    'required' => ['post_single_type_layout', '=', '3'],
                    'mode' => 'padding',
                    'all' => false,
                    'top' => true,
                    'right' => false,
                    'bottom' => true,
                    'left' => false,
                    'default' => [
                        'padding-top' => '463',
                        'padding-bottom' => '69',
                    ],
                ],
                [
                    'id' => 'single_margin_layout_3',
                    'title' => esc_html__('Margin Bottom', 'courto'),
                    'required' => ['post_single_type_layout', '=', '3'],
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => true,
                    'top' => false,
                    'left' => false,
                    'right' => false,
                    'default' => ['margin-bottom' => '60'],
                ],
                [
                    'id' => 'blog_single_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'blog_single_sidebar-start',
                    'type' => 'section',
                    'title' => esc_html__('Sidebar', 'courto'),
                    'indent' => true,
                ],
                [
                    'id' => 'single_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'courto'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ]
                    ],
                    'default' => 'right'
                ],
                [
                    'id' => 'single_sidebar_def',
                    'title' => esc_html__('Sidebar Template', 'courto'),
                    'type' => 'select',
                    'required' => ['single_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                    'default' => 'sidebar_main-sidebar',
                ],
                [
                    'id' => 'single_sidebar_def_width',
                    'title' => esc_html__('Sidebar Width', 'courto'),
                    'type' => 'button_set',
                    'required' => ['single_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => esc_html( '25%' ),
                        '8' => esc_html( '33%' ),
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'single_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'courto'),
                    'type' => 'switch',
                    'required' => ['single_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'single_sidebar_gap',
                    'title' => esc_html__( 'Sidebar Side Gap', 'courto' ),
                    'type' => 'select',
                    'required' => [ 'single_sidebar_layout', '!=', 'none' ],
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
                    'default' => '40',
                ],
                [
                    'id' => 'blog_single_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'blog_single_appearance-start',
                    'title' => esc_html__('Appearance', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'featured_image_type',
                    'title' => esc_html__('Featured Image', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'off' => esc_html__('Off', 'courto'),
                        'replace' => esc_html__('Replace', 'courto')
                    ],
                    'default' => 'default',
                ],
                [
                    'id' => 'featured_image_replace',
                    'title' => esc_html__('Image To Replace On', 'courto'),
                    'type' => 'media',
                    'required' => ['featured_image_type', '=', 'replace'],
                ],
                [
                    'id' => 'single_apply_animation',
                    'title' => esc_html__('Apply Animation?', 'courto'),
                    'type' => 'switch',
                    'required' => ['post_single_type_layout', '=', '3'],
                    'desc' => courto_quick_tip(
                        wp_kses(
                            __('Fade out the Post Title during page scrolling. <br>Note: affects only <code>Overlay Image</code> post layouts', 'courto'),
                            ['br' => [], 'code' => []]
                        )
                    ),
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_scroll_down',
                    'title' => esc_html__('Scroll Down Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['post_single_type_layout', '=', '3'],
                    'desc' => courto_quick_tip(
                        wp_kses(
                            __('Note: affects only <code>Overlay Image</code> post layouts', 'courto'),
                            ['code' => []]
                        )
                    ),
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_likes',
                    'title' => esc_html__('Likes', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_views',
                    'title' => esc_html__('Views', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_share',
                    'title' => esc_html__('Shares', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_meta_tags',
                    'title' => esc_html__('Tags', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'single_author_info',
                    'title' => esc_html__('Author Info', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_meta',
                    'title' => esc_html__('Hide all post-meta?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_meta_author',
                    'title' => esc_html__('Hide post-meta author?', 'courto'),
                    'type' => 'switch',
                    'required' => ['single_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_meta_comments',
                    'title' => esc_html__('Hide post-meta comments?', 'courto'),
                    'type' => 'switch',
                    'required' => ['single_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_meta_categories',
                    'title' => esc_html__('Hide post-meta categories?', 'courto'),
                    'type' => 'switch',
                    'required' => ['single_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'single_meta_date',
                    'title' => esc_html__('Hide post-meta date?', 'courto'),
                    'type' => 'switch',
                    'required' => ['single_meta', '=', false],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'blog_single_appearance-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'blog-single-related-option',
            'title' => esc_html__('Related', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'single_related_posts',
                    'title' => esc_html__('Related Posts', 'courto'),
                    'type' => 'switch',
                    'default' => true,
                ],
                [
                    'id' => 'blog_title_r',
                    'title' => esc_html__('Related Section Title', 'courto'),
                    'type' => 'text',
                    'required' => ['single_related_posts', '=', '1'],
                    'default' => esc_html__('RELATED POSTS', 'courto'),
                ],
                [
                    'id' => 'blog_cat_r',
                    'title' => esc_html__('Select Categories', 'courto'),
                    'type' => 'select',
                    'required' => ['single_related_posts', '=', '1'],
                    'multi' => true,
                    'data' => 'categories',
                    'width' => '20%',
                ],
                [
                    'id' => 'blog_column_r',
                    'title' => esc_html__('Columns', 'courto'),
                    'type' => 'button_set',
                    'required' => ['single_related_posts', '=', '1'],
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4'
                    ],
                    'default' => '2',
                ],
                [
                    'id' => 'blog_number_r',
                    'title' => esc_html__('Number of Related Items', 'courto'),
                    'type' => 'text',
                    'required' => ['single_related_posts', '=', '1'],
                    'default' => '2',
                ],
                [
                    'id' => 'blog_carousel_r',
                    'title' => esc_html__('Display items in the carousel', 'courto'),
                    'type' => 'switch',
                    'required' => ['single_related_posts', '=', '1'],
                    'default' => true,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'portfolio-option',
            'title' => esc_html__('Portfolio', 'courto'),
            'icon' => 'el el-picture',
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'portfolio-list-option',
            'title' => esc_html__('Archive', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'portfolio_slug',
                    'title' => esc_html__('Portfolio Slug', 'courto'),
                    'type' => 'text',
                    'default' => 'portfolio',
                ],
                [
                    'id' => 'portfolio_body-start',
                    'title' => esc_html__('Body', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_body_color_bg',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'contain',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center top',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'portfolio_body-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'portfolio_archive_page_title-start',
                    'title' => esc_html__('Page Title', 'courto'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', '1'],
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_archive__page_title_bg_image',
                    'title' => esc_html__('Page Title Background Image', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'portfolio_archive_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'portfolio_archive_sidebar-start',
                    'title' => esc_html__('Sidebar', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_list_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'courto'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ]
                    ],
                    'default' => 'none'
                ],
                [
                    'id' => 'portfolio_list_sidebar_def',
                    'title' => esc_html__('Sidebar Template', 'courto'),
                    'type' => 'select',
                    'required' => ['portfolio_list_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'portfolio_list_sidebar_def_width',
                    'title' => esc_html__('Sidebar Width', 'courto'),
                    'type' => 'button_set',
                    'required' => ['portfolio_list_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => esc_html__('25%', 'courto'),
                        '8' => esc_html__('33%', 'courto'),
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'portfolio_archive_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'portfolio_list_appearance-start',
                    'title' => esc_html__('Appearance', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_list_columns',
                    'title' => esc_html__('Columns in Archive', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        '1' => esc_html__('One', 'courto'),
                        '2' => esc_html__('Two', 'courto'),
                        '3' => esc_html__('Three', 'courto'),
                        '4' => esc_html__('Four', 'courto'),
                    ],
                    'default' => '3',
                ],
                [
                    'id' => 'portfolio_list_show_title',
                    'title' => esc_html__('Title', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'portfolio_list_show_content',
                    'title' => esc_html__('Content', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_list_show_cat',
                    'title' => esc_html__('Categories', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'portfolio_list_tooltip',
                    'title' => esc_html__('Items cursor tooltip hover animation', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_related_switch', '=', '1'],
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_list_appearance-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'portfolio-single-option',
            'title' => esc_html__('Single', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'portfolio_single_body-start',
                    'title' => esc_html__('Body', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_single_body_color_bg',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'contain',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center top',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'portfolio_single_body-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'portfolio_single_layout-start',
                    'title' => esc_html__('Layout', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_single_type_layout',
                    'title' => esc_html__('Portfolio Single Layout', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        '1' => esc_html__('Title First', 'courto'),
                        '2' => esc_html__('Image First', 'courto'),
                    ],
                    'default' => '2',
                ],
                [
                    'id' => 'portfolio_single_image',
                    'title' => esc_html__('Portfolio Image Wide', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'portfolio_single_image_width',
                    'title' => esc_html__('Max Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['portfolio_single_image', '=', true],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 1400],
                ],
                [
                    'id' => 'portfolio_single__image_margin',
                    'title' => esc_html__('Featured Image Margin Top', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => false,
                    'top' => true,
                    'left' => false,
                    'right' => false,
                    'default' => [
                        'margin-top' => '10',
                    ],
                ],
                [
                    'id' => 'portfolio_single_layout-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'portfolio_single_page_title-start',
                    'title' => esc_html__('Page Title', 'courto'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', true],
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_single__page_title_switch',
                    'title' => esc_html__('Use Page Title?', 'courto'),
                    'type' => 'switch',
                    'required' => ['page_title_switch', '=', true],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'portfolio_title_conditional',
                    'title' => esc_html__('Page Title Text', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Post Type Name', 'courto'),
                    'off' => esc_html__('Post Title', 'courto'),
                    'default' => true,
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single_title_align',
                    'title' => esc_html__('Title Alignment', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single_breadcrumbs_align',
                    'title' => esc_html__('Breadcrumbs Alignment', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single_breadcrumbs_block_switch',
                    'title' => esc_html__('Breadcrumbs Full Width', 'courto'),
                    'type' => 'switch',
                    'default' => true,
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single__page_title_bg_switch',
                    'title' => esc_html__('Use Background Image/Color?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single__page_title_bg_image',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'required' => ['portfolio_single__page_title_bg_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single__page_title_height',
                    'title' => esc_html__('Min Height', 'courto'),
                    'type' => 'dimensions',
                    'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'courto'),
                    'height' => true,
                    'width' => false,
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single__page_title_padding',
                    'title' => esc_html__('Paddings Top/Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => false,
                    'right' => false,
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single__page_title_margin',
                    'title' => esc_html__('Margin Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => true,
                    'top' => false,
                    'left' => false,
                    'right' => false,
                    'required' => [
                        ['portfolio_single__page_title_switch', '=', true],
                    ],
                ],
                [
                    'id' => 'portfolio_single_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'portfolio_single_sidebar-start',
                    'title' => esc_html__('Sidebar', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_single_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'courto'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ]
                    ],
                    'default' => 'none'
                ],
                [
                    'id' => 'portfolio_single_sidebar_def',
                    'title' => esc_html__('Sidebar Template', 'courto'),
                    'type' => 'select',
                    'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'portfolio_single_sidebar_def_width',
                    'title' => esc_html__('Sidebar Width', 'courto'),
                    'type' => 'button_set',
                    'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => esc_html( '25%' ),
                        '8' => esc_html( '33%' ),
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'portfolio_single_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_single_sidebar_gap',
                    'title' => esc_html__( 'Sidebar Side Gap', 'courto' ),
                    'type' => 'select',
                    'required' => [ 'portfolio_single_sidebar_layout', '!=', 'none' ],
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
                    'default' => 'def',
                ],
                [
                    'id' => 'portfolio_single_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'portfolio_single_appearance-start',
                    'title' => esc_html__('Appearance', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'portfolio_above_content_cats',
                    'title' => esc_html__('Tags', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'portfolio_above_content_share',
                    'title' => esc_html__('Shares', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'portfolio_single_meta_likes',
                    'title' => esc_html__('Likes', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_single_meta',
                    'title' => esc_html__('Hide all post-meta?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_single_meta_author',
                    'title' => esc_html__('Post-meta author', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_single_meta', '=', false],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_single_meta_comments',
                    'title' => esc_html__('Post-meta comments', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_single_meta', '=', false],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_single_meta_categories',
                    'title' => esc_html__('Post-meta categories', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_single_meta', '=', false],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_single_meta_date',
                    'title' => esc_html__('Post-meta date', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_single_meta', '=', false],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'portfolio_single_appearance-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'portfolio-related-option',
            'title' => esc_html__('Related Posts', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'portfolio_related_switch',
                    'title' => esc_html__('Related Posts', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'portfolio_related_title',
                    'title' => esc_html__('Title', 'courto'),
                    'type' => 'text',
                    'required' => ['portfolio_related_switch', '=', '1'],
                    'default' => esc_html__('RELATED PROJECTS', 'courto'),
                ],
                [
                    'id' => 'pf_carousel_r',
                    'title' => esc_html__('Display items within carousel for this post', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_related_switch', '=', '1'],
                    'default' => true,
                ],
                [
                    'id' => 'pf_tooltip_r',
                    'title' => esc_html__('Items cursor tooltip hover animation', 'courto'),
                    'type' => 'switch',
                    'required' => ['portfolio_related_switch', '=', '1'],
                    'default' => false,
                ],
                [
                    'id' => 'pf_column_r',
                    'title' => esc_html__('Related Columns', 'courto'),
                    'type' => 'button_set',
                    'required' => ['portfolio_related_switch', '=', '1'],
                    'options' => [
                        '2' => esc_html__('Two', 'courto'),
                        '3' => esc_html__('Three', 'courto'),
                        '4' => esc_html__('Four', 'courto'),
                    ],
                    'default' => '3',
                ],
                [
                    'id' => 'pf_number_r',
                    'title' => esc_html__('Number of Related Items', 'courto'),
                    'type' => 'text',
                    'required' => ['portfolio_related_switch', '=', '1'],
                    'default' => '3',
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'portfolio-advanced',
            'title' => esc_html__('Advanced', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'portfolio_archives',
                    'title' => esc_html__('Portfolio Archives', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Enabled', 'courto'),
                    'off' => esc_html__('Disabled', 'courto'),
                    'default' => true,
                    'desc' => courto_quick_tip(sprintf(
                        wp_kses(
                            __('Archive Page lists all the portfolio posts you have created. <br>This option will disable only the Archive Page, while the post\'s Single Pages will still be displayed. <br>Note: you need to refresh your <a href="%s">permalinks</a> after switching this option.', 'courto'),
                            ['a' => ['href' => true], 'br' => []]
                        ),
                        esc_url(admin_url('options-permalink.php'))
                    )),
                ],
                [
                    'id' => 'portfolio_singular',
                    'title' => esc_html__('Portfolio Single', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Enabled', 'courto'),
                    'off' => esc_html__('Disabled', 'courto'),
                    'default' => true,
                    'desc' => courto_quick_tip(
                        wp_kses(
                            __('By default, all Portfolio posts have their Single Pages. <br>This creates a specific URL on your website for every post. <br>Selecting "Disabled" will prevent the single view post being publicly displayed.', 'courto'),
                            ['br' => []]
                        )
                    ),
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'team-option',
            'title' => esc_html__('Team', 'courto'),
            'icon' => 'el el-user',
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'team-single-option',
            'title' => esc_html__('Single', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'team_body-start',
                    'title' => esc_html__('Body', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'team_body_color_bg',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'contain',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center top',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'team_body-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'team_single_page_title-start',
                    'title' => esc_html__('Page Title', 'courto'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', true],
                    'indent' => true,
                ],
                [
                    'id' => 'team_title_conditional',
                    'title' => esc_html__('Page Title Text', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Post Type Name', 'courto'),
                    'off' => esc_html__('Post Title', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'team_single__page_title_bg_switch',
                    'title' => esc_html__('Use Background Image/Color?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => 'team_single__page_title_bg_image',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'required' => ['team_single__page_title_bg_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'team_single__page_title_height',
                    'title' => esc_html__('Min Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['page_title_bg_switch', '=', true],
                    'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'courto'),
                    'height' => true,
                    'width' => false,
                ],
                [
                    'id' => 'team_single__page_title_padding',
                    'title' => esc_html__('Paddings Top/Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => false,
                    'right' => false,
                ],
                [
                    'id' => 'team_single__page_title_margin',
                    'title' => esc_html__('Margin Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => true,
                    'top' => false,
                    'left' => false,
                    'right' => false,
                ],
                [
                    'id' => 'team_single_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'team_single_render_output',
                    'title' => esc_html__('Elementor Content Position', 'courto'),
                    'desc'       => esc_html__( 'Select where the Elementor content should be displayed on single posts', 'courto' ),
                    'type' => 'select',
                    'options' => [
                        'sidebar' => esc_html__('In Sidebar', 'courto'),
                        'below_meta' => esc_html__('Below Meta Information', 'courto'),
                    ],
                    'default' => 'below_meta',
                ],
                [
                    'id' => 'team_single_sticky_image',
                    'title' => esc_html__('Sticky Image', 'courto'),
                    'type' => 'switch',
                    'default' => true,
                    'required' => ['team_single_render_output', '=', 'sidebar'],
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'team-advanced',
            'title' => esc_html__('Advanced', 'courto'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'team_slug',
                    'title' => esc_html__('Team Slug', 'courto'),
                    'type' => 'text',
                    'default' => 'team',
                ],
                [
                    'id' => 'team_singular',
                    'title' => esc_html__('Team Singles', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Enabled', 'courto'),
                    'off' => esc_html__('Disabled', 'courto'),
                    'default' => true,
                    'desc' => esc_html__('By default, all team posts have single views enabled. This creates a specific URL on your website for that post. Selecting "Disabled" will prevent the single view post being publicly displayed.', 'courto'),
                ],
                [
                    'id' => 'team_archives',
                    'title' => esc_html__('Team Archive', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Enabled', 'courto'),
                    'off' => esc_html__('Disabled', 'courto'),
                    'default' => true,
                    'desc' => sprintf(
                        wp_kses(
                            __('Archive Page lists all the Team Members you have created. This option will disable only the member\'s Archive Page. The member\'s Single Pages will still be displayed. Note: you will need to refresh your <a href="%s">permalinks</a> after switching this option.', 'courto'),
                            ['a' => ['href' => true, 'target' => true]]
                        ),
                        esc_url(admin_url('options-permalink.php'))
                    ),
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'title' => esc_html__('Page 404', 'courto'),
            'id' => '404-option',
            'icon' => 'el el-error',
            'fields' => [
                [
                    'id' => '404_building_tool',
                    'title' => esc_html__('Layout Building Tool', 'courto'),
                    'type' => 'select',
                    'options' => [
                        'default' => esc_html__('Default', 'courto'),
                        'elementor' => esc_html__('Elementor', 'courto'),
                    ],
                    'default' => 'default',
                ],
                [
                    'id' => '404_template_select',
                    'type' => 'select',
                    'title' => esc_html__('Select Template', 'courto'),
                    'required' => ['404_building_tool', '=', 'elementor'],
                    'data' => 'posts',
                    'desc' => sprintf(
                        '%s <br>%s <a href="%s" target="_blank">%s</a> %s',
                        esc_html__('Selected Template will be used for 404 page by default.', 'courto'),
                        esc_html__('You can edit/create Template in the', 'courto'),
                        admin_url('edit.php?post_type=elementor_library&tabs_group=library'),
                        esc_html__('Saved Templates', 'courto'),
                        esc_html__('dashboard tab.', 'courto')
                    ),
                    'args' => [
                        'post_type' => 'elementor_library',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ],
                ],
                [
                    'id' => '404_body_color_bg',
                    'title' => esc_html__('Body Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'contain',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center top',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => '404_show_header',
                    'type' => 'switch',
                    'title' => esc_html__('Header Section', 'courto'),
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => '404_page_title_switcher',
                    'title' => esc_html__('Page Title Section', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => '404_page_title-start',
                    'type' => 'section',
                    'required' => ['404_page_title_switcher', '=', true],
                    'indent' => true,
                ],
                [
                    'id' => '404_custom_title_switch',
                    'title' => esc_html__('Page Title Text', 'courto'),
                    'type' => 'switch',
                    'required' => ['404_page_title_switcher', '=', true],
                    'on' => esc_html__('Custom', 'courto'),
                    'off' => esc_html__('Default', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => '404_page_title_text',
                    'title' => esc_html__('Custom Page Title Text', 'courto'),
                    'type' => 'text',
                    'required' => ['404_custom_title_switch', '=', true],
                ],
                [
                    'id' => '404_page__page_title_bg_switch',
                    'title' => esc_html__('Use Background Image/Color?', 'courto'),
                    'type' => 'switch',
                    'required' => ['404_page_title_switcher', '=', true],
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
                [
                    'id' => '404_page__page_title_bg_image',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'required' => ['404_page__page_title_bg_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => '404_page__page_title_height',
                    'title' => esc_html__('Min Height', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['page_title_bg_switch', '=', true],
                    'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'courto'),
                    'height' => true,
                    'width' => false,
                ],
                [
                    'id' => '404_page__page_title_padding',
                    'title' => esc_html__('Paddings Top/Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'top' => true,
                    'bottom' => true,
                    'left' => false,
                    'right' => false,
                ],
                [
                    'id' => '404_page__page_title_margin',
                    'title' => esc_html__('Margin Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'top' => false,
                    'bottom' => true,
                    'left' => false,
                    'right' => false,
                    'default' => [
                        'margin-bottom' => '0',
                    ],
                ],
                [
                    'id' => '404_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => '404_page_main-start',
                    'type' => 'section',
                    'title' => esc_html__('Section Settings', 'courto'),
                    'indent' => true,
                ],
                [
                    'id' => '404_page_main_bg_image',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'inherit',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                    ],
                ],
                [
                    'id' => '404_use_logo',
                    'title' => esc_html__('Use Logotype?', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => '404_custom_logo_switch',
                    'type' => 'switch',
                    'title' => esc_html__('Use Custom Logotype?', 'courto'),
                    'default' => false,
                    'required' => [ '404_use_logo', '=', true ],
                ],
                [
                    'id' => '404_logotype',
                    'type' => 'media',
                    'title' => esc_html__('Logo 404', 'courto'),
                    'required' => [ '404_custom_logo_switch', '=', true ],
                ],
                [
                    'id' => '404_logo_height_custom',
                    'type' => 'switch',
                    'title' => esc_html__('Enable Logo Height', 'courto'),
                    'default' => false,
                    'required' => [ '404_custom_logo_switch', '=', true ],
                ],
                [
                    'id' => '404_logo_height',
                    'type' => 'dimensions',
                    'units' => 'px',
                    'units_extended' => false,
                    'title' => esc_html__('Set Logo Height', 'courto'),
                    'height' => true,
                    'width' => false,
                    'default' => [ 'height' => 100 ],
                    'required' => [ '404_logo_height_custom', '=', '1' ],
                ],
                [
                    'id' => '404_page_main_padding',
                    'title' => esc_html__('Paddings Top/Bottom', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'top' => true,
                    'bottom' => true,
                    'left' => false,
                    'right' => false,
                ],
                [
                    'id' => '404_page_main_padding_responsive',
                    'title' => esc_html__('Paddings Top/Bottom Tablet', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'top' => true,
                    'bottom' => true,
                    'left' => false,
                    'right' => false,
                ],
                [
                    'id' => '404_page_full_width_switch',
                    'title' => esc_html__('Page Title Full Width', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => '404_page_width',
                    'title' => esc_html__('Max Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['404_page_full_width_switch', '=', true],
                    'height' => false,
                    'width' => true,
                    'default' => ['width' => 1820],
                ],
                [
                    'id' => '404_page_main-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => '404_show_footer',
                    'title' => esc_html__('Footer Section', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => true,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'side_panel',
            'title' => esc_html__('Side Panel', 'courto'),
            'icon' => 'el el-indent-left',
            'fields' => [
                [
                    'id' => 'side_panel_enabled',
                    'title' => esc_html__('Side Panel', 'courto'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Disable', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'side_panel-start',
                    'title' => esc_html__('Layout', 'courto'),
                    'type' => 'section',
                    'required' => ['side_panel_enabled', '=', true],
                    'indent' => true,
                ],
                [
                    'id' => 'side_panel_building_tool',
                    'title' => esc_html__('Layout Building Tool', 'courto'),
                    'type' => 'select',
                    'options' => [
                        'widgets' => esc_html__('Wordpress Widgets', 'courto'),
                        'elementor' => esc_html__('Elementor (recommended)', 'courto'),
                    ],
                    'default' => 'elementor',
                ],
                [
                    'id' => 'side_panel_page_select',
                    'title' => esc_html__('Select Template', 'courto'),
                    'type' => 'select',
                    'required' => ['side_panel_building_tool', '=', 'elementor'],
                    'desc' => wp_kses(
                        sprintf(
                            '%s <a href="%s" target="_blank">%s</a> %s<br> %s',
                            __('You can edit/create Side Panel Template in the', 'courto'),
                            admin_url('edit.php?post_type=side_panel'),
                            __('Side Panel', 'courto'),
                            __('dashboard tab.', 'courto'),
                            courto_quick_tip(
                                sprintf(
                                    __('Note: fine tuning is available through the Elementor\'s <code>Post Settings</code> tab, which is located <a href="%s" target="_blank">here</a>', 'courto'),
                                    get_template_directory_uri() . '/core/admin/img/dashboard/quick_tip__side_panel_extra_options.png'
                                )
                            )
                        ),
                        ['a' => ['href' => true, 'target' => true], 'br' => [], 'span' => ['class' => true], 'i' => ['class' => true], 'code' => []]
                    ),
                    'data' => 'posts',
                    'args' => [
                        'post_type' => 'side_panel',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ],
                ],
                [
                    'id' => 'side_panel_spacing',
                    'title' => esc_html__('Margin', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'required' => ['side_panel_building_tool', '=', 'widgets'],
                    'units' => 'px',
                    'all' => false,
                    'default' => [
                        'margin-top' => '',
                        'margin-right' => '',
                        'margin-bottom' => '',
                        'margin-left' => '',
                    ],
                ],
                [
                    'id' => 'side_panel_title_color',
                    'title' => esc_html__('Title Color', 'courto'),
                    'type' => 'color',
                    'required' => ['side_panel_building_tool', '=', 'widgets'],
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'side_panel_text_color',
                    'title' => esc_html__('Text Color', 'courto'),
                    'type' => 'color',
                    'required' => ['side_panel_building_tool', '=', 'widgets'],
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'side_panel_bg',
                    'title' => esc_html__('Background', 'courto'),
                    'type' => 'color_rgba',
                    'required' => ['side_panel_building_tool', '=', 'widgets'],
                    'mode' => 'background',
                    'default' => [
                        'alpha' => '1',
                        'rgba' => 'rgba(24,24,24,1)',
                        'color' => '#181818',
                    ],
                ],
                [
                    'id' => 'side_panel_text_alignment',
                    'title' => esc_html__('Text Align', 'courto'),
                    'type' => 'button_set',
                    'required' => ['side_panel_building_tool', '=', 'widgets'],
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'center' => esc_html__('Center', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'left',
                ],
                [
                    'id' => 'side_panel_width',
                    'title' => esc_html__('Width', 'courto'),
                    'type' => 'dimensions',
                    'required' => ['side_panel_building_tool', '=', 'widgets'],
                    'width' => true,
                    'height' => false,
                    'default' => ['width' => 370],
                ],
                [
                    'id' => 'side_panel_position',
                    'title' => esc_html__('Position', 'courto'),
                    'type' => 'button_set',
                    'required' => ['side_panel_building_tool', '=', 'widgets'],
                    'options' => [
                        'left' => esc_html__('Left', 'courto'),
                        'right' => esc_html__('Right', 'courto'),
                    ],
                    'default' => 'right'
                ],
                [
                    'id' => 'side_panel-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'layout_options',
            'title' => esc_html__('Sidebars', 'courto'),
            'icon' => 'el el-braille',
            'fields' => [
                [
                    'id' => 'sidebars',
                    'title' => esc_html__('Register Sidebars', 'courto'),
                    'type' => 'multi_text',
                    'validate' => 'no_html',
                    'add_text' => esc_html__('Add Sidebar', 'courto'),
                    'default' => ['Main Sidebar'],
                ],
                [
                    'id' => 'sidebars-start',
                    'title' => esc_html__('Sidebar Settings', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'page_sidebar_layout',
                    'title' => esc_html__('Page Sidebar Layout', 'courto'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'courto'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ]
                    ],
                    'default' => 'none'
                ],
                [
                    'id' => 'page_sidebar_def',
                    'title' => esc_html__('Page Sidebar', 'courto'),
                    'type' => 'select',
                    'required' => ['page_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'page_sidebar_def_width',
                    'title' => esc_html__('Page Sidebar Width', 'courto'),
                    'type' => 'button_set',
                    'required' => ['page_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => esc_html( '25%' ),
                        '8' => esc_html( '33%' ),
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'page_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'courto'),
                    'type' => 'switch',
                    'required' => ['page_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'page_sidebar_gap',
                    'title' => esc_html__( 'Sidebar Side Gap', 'courto' ),
                    'type' => 'select',
                    'required' => [ 'page_sidebar_layout', '!=', 'none' ],
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
                    'default' => 'def',
                ],
                [
                    'id' => 'sidebars-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'soc_shares',
            'title' => esc_html__('Social Shares', 'courto'),
            'icon' => 'el el-share-alt',
            'fields' => [
                [
                    'id' => 'post_shares',
                    'title' => esc_html__('Share List', 'courto'),
                    'type' => 'checkbox',
                    'desc' => esc_html__('Note: used only on Blog Single, Blog List and Portfolio Single pages', 'courto'),
                    'options' => [
                        'telegram' => esc_html__('Telegram', 'courto'),
                        'reddit' => esc_html__('Reddit', 'courto'),
                        'twitter' => esc_html__('Twitter', 'courto'),
                        'whatsapp' => esc_html__('WhatsApp', 'courto'),
                        'facebook' => esc_html__('Facebook', 'courto'),
                        'pRobotoest' => esc_html__('PRobotoest', 'courto'),
                        'linkedin' => esc_html__('Linkedin', 'courto'),
                    ],
                    'default' => [
                        'telegram' => '0',
                        'reddit' => '0',
                        'twitter' => '1',
                        'whatsapp' => '0',
                        'facebook' => '1',
                        'pRobotoest' => '1',
                        'linkedin' => '1',
                    ]
                ],
                [
                    'id' => 'page_socials-start',
                    'title' => esc_html__('Page Socials', 'courto'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'show_soc_icon_page',
                    'title' => esc_html__('Page Social Shares', 'courto'),
                    'type' => 'switch',
                    'desc' => esc_html__('Social buttons are to be rendered on a left side of each page.', 'courto'),
                    'on' => esc_html__('Use', 'courto'),
                    'off' => esc_html__('Hide', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'soc_icon_style',
                    'title' => esc_html__('Socials visibility', 'courto'),
                    'type' => 'button_set',
                    'options' => [
                        'standard' => esc_html__('Always', 'courto'),
                        'hovered' => esc_html__('On Hover', 'courto'),
                    ],
                    'default' => 'standard',
                    'required' => ['show_soc_icon_page', '=', '1'],
                ],
                [
                    'id' => 'soc_icon_offset',
                    'title' => esc_html__('Offset Top', 'courto'),
                    'type' => 'spacing',
                    'required' => ['show_soc_icon_page', '=', '1'],
                    'desc' => esc_html__('If units defined as "%" then socials will be fixed to viewport.', 'courto'),
                    'mode' => 'margin',
                    'units' => ['px', '%'],
                    'all' => false,
                    'top' => true,
                    'bottom' => false,
                    'left' => false,
                    'right' => false,
                    'default' => [
                        'margin-top' => '250',
                        'units' => 'px'
                    ],
                ],
                [
                    'id' => 'soc_icon_facebook',
                    'title' => esc_html__('Facebook Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['show_soc_icon_page', '=', '1'],
                    'default' => false,
                ],
                [
                    'id' => 'soc_icon_twitter',
                    'title' => esc_html__('Twitter Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['show_soc_icon_page', '=', '1'],
                    'default' => false,
                ],
                [
                    'id' => 'soc_icon_linkedin',
                    'title' => esc_html__('Linkedin Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['show_soc_icon_page', '=', '1'],
                    'default' => false,
                ],
                [
                    'id' => 'soc_icon_pRobotoest',
                    'title' => esc_html__('PRobotoest Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['show_soc_icon_page', '=', '1'],
                    'default' => false,
                ],
                [
                    'id' => 'soc_icon_tumblr',
                    'title' => esc_html__('Tumblr Button', 'courto'),
                    'type' => 'switch',
                    'required' => ['show_soc_icon_page', '=', '1'],
                    'default' => false,
                ],
                [
                    'id' => 'add_custom_share',
                    'title' => esc_html__('Need Additional Socials?', 'courto'),
                    'type' => 'switch',
                    'required' => ['show_soc_icon_page', '=', '1'],
                    'on' => esc_html__('Yes', 'courto'),
                    'off' => esc_html__('No', 'courto'),
                    'default' => false,
                ],
                [
                    'id' => 'share_name-1',
                    'title' => esc_html__('Social 1 - Name', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_link-1',
                    'title' => esc_html__('Social 1 - Link', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_icons-1',
                    'title' => esc_html__('Social 1 - Icon', 'courto'),
                    'type' => 'select',
                    'required' => ['add_custom_share', '=', '1'],
                    'data' => 'elusive-icons',
                ],
                [
                    'id' => 'share_name-2',
                    'title' => esc_html__('Social 2 - Name', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_link-2',
                    'title' => esc_html__('Social 2 - Link', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_icons-2',
                    'title' => esc_html__('Social 2 - Icon', 'courto'),
                    'type' => 'select',
                    'required' => ['add_custom_share', '=', '1'],
                    'data' => 'elusive-icons',
                ],
                [
                    'id' => 'share_name-3',
                    'title' => esc_html__('Social 3 - Name', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_link-3',
                    'title' => esc_html__('Social 3 - Link', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_icons-3',
                    'title' => esc_html__('Social 3 - Icon', 'courto'),
                    'type' => 'select',
                    'required' => ['add_custom_share', '=', '1'],
                    'data' => 'elusive-icons',
                ],
                [
                    'id' => 'share_name-4',
                    'type' => 'text',
                    'title' => esc_html__('Social 4 - Name', 'courto'),
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_link-4',
                    'title' => esc_html__('Social 4 - Link', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_icons-4',
                    'type' => 'select',
                    'title' => esc_html__('Social 4 - Icon', 'courto'),
                    'required' => ['add_custom_share', '=', '1'],
                    'data' => 'elusive-icons',
                ],
                [
                    'id' => 'share_name-5',
                    'title' => esc_html__('Social 5 - Name', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_link-5',
                    'title' => esc_html__('Social 5 - Link', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_icons-5',
                    'title' => esc_html__('Social 5 - Icon', 'courto'),
                    'type' => 'select',
                    'required' => ['add_custom_share', '=', '1'],
                    'data' => 'elusive-icons',
                ],
                [
                    'id' => 'share_name-6',
                    'title' => esc_html__('Social 6 - Name', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_link-6',
                    'title' => esc_html__('Social 6 - Link', 'courto'),
                    'type' => 'text',
                    'required' => ['add_custom_share', '=', '1'],
                ],
                [
                    'id' => 'share_icons-6',
                    'title' => esc_html__('Social 6 - Icon', 'courto'),
                    'type' => 'select',
                    'required' => ['add_custom_share', '=', '1'],
                    'data' => 'elusive-icons',
                ],
                [
                    'id' => 'page_socials-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'color_options_color',
            'title' => esc_html__( 'Color Settings', 'courto' ),
            'icon' => 'el-icon-tint',
            'fields' => [
                [
                    'id' => 'theme-primary-color',
                    'title' => esc_html__( 'Primary Theme Color', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#D1FF6D',
                ],
                [
                    'id' => 'theme-secondary-color',
                    'title' => esc_html__( 'Secondary Theme Color', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                ],
                [
                    'id' => 'theme-tertiary-color',
                    'title' => esc_html__( 'Tertiary Theme Color', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'theme-quaternary-color',
                    'title' => esc_html__( 'Quaternary Theme Color', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#E9E6E2',
                ],
                [
                    'id' => 'theme-content-color',
                    'title' => esc_html__( 'Content Color', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#444444',
                ],
                [
                    'id' => 'theme-headings-color',
                    'title' => esc_html__( 'Headings Color', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                ],
                [
                    'id' => 'body_color_bg',
                    'title' => esc_html__('Background Image/Color', 'courto'),
                    'type' => 'background',
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-image' => '',
                        'background-repeat' => 'no-repeat',
                        'background-size' => 'contain',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center top',
                        'background-color' => '#FFFFFF',
                    ],
                ],
                [
                    'id' => 'form-bg-color',
                    'title' => esc_html__( 'Comments Form Background', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'form-border-color',
                    'title' => esc_html__( 'Comments Form Border Color', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                ],
            ]
        ]
    );

    //* ↓ Buttons Config
    Redux::set_section(
        $theme_slug,
        [
            'id' => 'buttons_options',
            'title' => esc_html__('Buttons', 'courto'),
            'icon' => 'fas fa-edit',
            'fields' => [
                [
                    'id' => 'button-font',
                    'title' => esc_html__( 'Button Font', 'courto' ),
                    'type' => 'custom_typography',
                    'font-size' => true,
                    'line-height' => true,
                    'color' => false,
                    'subsets' => false,
                    'all_styles' => true,
                    'font-weight-multi' => false,
                    'letter-spacing' => true,
                    'google' => true,
                    'font-style' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'default' => [
                        'google' => true,
                        'font-family' => 'TikTok Sans',
                        'font-weight' => '700',
                        'font-size' => '14px',
                        'line-height' => '24px',
                        'letter-spacing' => '-0.06',
                    ],
                ],
                [
                    'id' => 'button-font-mobile',
                    'title' => esc_html__( 'Button Font - Mobile', 'courto' ),
                    'type' => 'custom_typography',
                    'all_styles' => false,
                    'font-family' => false,
                    'font-size' => true,
                    'line-height' => true,
                    'color' => false,
                    'subsets' => false,
                    'font-weight' => false,
                    'font-weight-multi' => false,
                    'letter-spacing' => true,
                    'google' => false,
                    'font-style' => false,
                    'font-backup' => false,
                    'text-align' => false,
                    'default' => [
                        'font-size' => '13px',
                        'line-height' => '22px',
                    ],
                ],
                [
                    'id' => 'button-padding',
                    'title' => esc_html__('Padding', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'display_units' => 'false',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'padding-top' => '17px',
                        'padding-right' => '35px',
                        'padding-bottom' => '17px',
                        'padding-left' => '35px',
                    ],
                ],
                [
                    'id' => 'button-padding-mobile',
                    'title' => esc_html__('Padding Mobile', 'courto'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'display_units' => 'false',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => true,
                    'right' => true,
                    'default' => [
                        'padding-top' => '14px',
                        'padding-right' => '30px',
                        'padding-bottom' => '14px',
                        'padding-left' => '30px',
                    ],
                ],
                [
                    'id' => 'button-border-width',
                    'title' => esc_html__('Button Border Width', 'courto'),
                    'type' => 'slider',
                    'display_value' => 'text',
                    'min' => 0,
                    'max' => 10,
                    'customizer' => false,
                    'default' => 1,
                ],
                [
                    'id' => 'button-radius',
                    'title' => esc_html__('Button Border Radius', 'courto'),
                    'type' => 'slider',
                    'display_value' => 'text',
                    'min' => 0,
                    'max' => 100,
                    'customizer' => false,
                    'default' => 40,
                ],
                [
                    'id' => 'button_colors-start',
                    'title' => esc_html__( 'Button Colors', 'courto' ),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'button-color-idle',
                    'title' => esc_html__( 'Button Color Idle', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                ],
                [
                    'id' => 'button-bg-idle',
                    'title' => esc_html__( 'Button Background Idle', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => true,
                    'default' => '#D1FF6D',
                ],
                [
                    'id' => 'button-border-idle',
                    'title' => esc_html__( 'Button Border Color Idle', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => true,
                    'default' => '#D1FF6D',
                ],
                [
                    'id' => 'button-color-hover',
                    'title' => esc_html__( 'Button Color Hover', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => false,
                    'default' => '#181818',
                ],
                [
                    'id' => 'button-bg-hover',
                    'title' => esc_html__( 'Button Background Hover', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => true,
                    'default' => '#ffffff',
                ],
                [
                    'id' => 'button-border-hover',
                    'title' => esc_html__( 'Button Border Color Hover', 'courto' ),
                    'type' => 'color',
                    'validate' => 'color',
                    'transparent' => true,
                    'default' => '#181818',
                ],
            ]
        ]
    );

    //* ↓ Typography Config
    Redux::set_section(
        $theme_slug,
        [
            'id' => 'Typography',
            'title' => esc_html__('Typography', 'courto'),
            'icon' => 'el-icon-font',
        ]
    );

    $main_typography = [
        [
            'id' => 'main-font',
            'title' => esc_html__('Content Font', 'courto'),
            'line-height' => true,
            'font-size' => true,
            'subsets' => false,
            'all_styles' => true,
            'font-weight-multi' => true,
            'letter-spacing' => true,
            'defs' => [
                'font-family' => 'TikTok Sans',
                'font-size' => '16px',
                'line-height' => '30px',
                'font-weight' => '400',
                'font-weight-multi' => '300,400,500,600,700',
            ],
        ],
        [
            'id' => 'header-font',
            'title' => esc_html__('Headings Font', 'courto'),
            'font-size' => false,
            'line-height' => false,
            'subsets' => false,
            'all_styles' => true,
            'font-weight-multi' => true,
            'letter-spacing' => true,
            'defs' => [
                'google' => true,
                'font-family' => 'Roboto',
                'font-weight' => '800',
                'font-style' => 'italic',
                'font-weight-multi' => '200,300,400,500,600,700,800,800italic',
                'letter-spacing' => '-0.04',
            ],
        ],
        [
            'id' => 'additional-font',
            'title' => esc_html__( 'Additional Font', 'courto' ),
            'font-size' => true,
            'line-height' => true,
            'color' => false,
            'subsets' => false,
            'all_styles' => true,
            'font-weight-multi' => true,
            'letter-spacing' => true,
            'defs' => [
                'google' => true,
                'font-family' => 'Playfair Display',
                'font-weight' => '500',
                'font-style' => 'italic',
                'font-weight-multi' => '400italic, 500italic',
            ],
        ],
    ];
    $typography = [];
    foreach ($main_typography as $key => $value) {
        $typography[] = [
            'id' => $value['id'],
            'type' => 'custom_typography',
            'title' => $value['title'],
            'color' => $value['color'] ?? '',
            'line-height' => $value['line-height'],
            'font-size' => $value['font-size'],
            'subsets' => $value['subsets'],
            'all_styles' => $value['all_styles'],
            'font-weight-multi' => $value['font-weight-multi'] ?? '',
            'subtitle' => $value['subtitle'] ?? '',
            'letter-spacing' => $value['letter-spacing'] ?? '',
            'google' => true,
            'font-style' => true,
            'font-backup' => false,
            'text-align' => false,
            'default' => $value['defs'],
        ];
    }

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'main_typography',
            'title' => esc_html__('Main Content', 'courto'),
            'subsection' => true,
            'fields' => $typography,
        ]
    );

    //* ↓ Menu Typography
    $menu_typography = [
        [
            'id' => 'menu-font',
            'title' => esc_html__('Menu Font', 'courto'),
            'color' => false,
            'line-height' => true,
            'font-size' => true,
            'subsets' => true,
            'letter-spacing' => true,
            'defs' => [
                'google' => true,
                'font-family' => 'TikTok Sans',
                'font-size' => '14px',
                'font-weight' => '700',
                'line-height' => '30px',
                'letter-spacing' => '-0.06',
                'text-transform' => 'none',
            ],
        ],
        [
            'id' => 'sub-menu-font',
            'title' => esc_html__('Submenu Font', 'courto'),
            'color' => false,
            'line-height' => true,
            'font-size' => true,
            'subsets' => true,
            'letter-spacing' => true,
            'defs' => [
                'google' => true,
                'font-family' => 'TikTok Sans',
                'font-size' => '15px',
                'font-weight' => '600',
                'line-height' => '30px',
                'letter-spacing' => '-0.02',
                'text-transform' => 'none',
            ],
        ],
    ];
    $menu_typography_array = [];
    foreach ($menu_typography as $key => $value) {
        $menu_typography_array[] = [
            'id' => $value['id'],
            'type' => 'custom_typography',
            'title' => $value['title'],
            'color' => $value['color'],
            'line-height' => $value['line-height'],
            'font-size' => $value['font-size'],
            'subsets' => $value['subsets'],
            'letter-spacing' => $value['letter-spacing'],
            'google' => true,
            'font-style' => true,
            'font-backup' => false,
            'text-align' => false,
            'all_styles' => false,
            'default' => $value['defs'],
        ];
    }

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'main_menu_typography',
            'title' => esc_html__('Menu', 'courto'),
            'subsection' => true,
            'fields' => $menu_typography_array
        ]
    );
    //* ↑ menu typography

    //* ↓ Headings Typography
    $headings = [
        [
            'id' => 'header-h1',
            'title' => esc_html__('‹h1›', 'courto'),
            'defs' => [
                'font-family' => 'Roboto',
                'font-size' => '56px',
                'line-height' => '68px',
                'font-weight' => '800',
                'font-style' => 'italic',
                'letter-spacing' => '-0.04',
                'text-transform' => 'none',
            ],
        ],
        [
            'id' => 'header-h2',
            'title' => esc_html__('‹h2›', 'courto'),
            'defs' => [
                'font-family' => 'Roboto',
                'font-size' => '48px',
                'line-height' => '60px',
                'font-weight' => '800',
                'font-style' => 'italic',
                'letter-spacing' => '-0.04',
                'text-transform' => 'none',
            ],
        ],
        [
            'id' => 'header-h3',
            'title' => esc_html__('‹h3›', 'courto'),
            'defs' => [
                'font-family' => 'Roboto',
                'font-size' => '40px',
                'line-height' => '52px',
                'font-weight' => '800',
                'font-style' => 'italic',
                'letter-spacing' => '-0.04',
                'text-transform' => 'none',
            ],
        ],
        [
            'id' => 'header-h4',
            'title' => esc_html__('‹h4›', 'courto'),
            'defs' => [
                'font-family' => 'Roboto',
                'font-size' => '36px',
                'line-height' => '48px',
                'font-weight' => '800',
                'font-style' => 'italic',
                'letter-spacing' => '-0.04',
                'text-transform' => 'none',
            ],
        ],
        [
            'id' => 'header-h5',
            'title' => esc_html__('‹h5›', 'courto'),
            'defs' => [
                'font-family' => 'Roboto',
                'font-size' => '30px',
                'line-height' => '42px',
                'font-weight' => '800',
                'font-style' => 'italic',
                'letter-spacing' => '-0.04',
                'text-transform' => 'none',
            ],
        ],
        [
            'id' => 'header-h6',
            'title' => esc_html__('‹h6›', 'courto'),
            'defs' => [
                'font-family' => 'Roboto',
                'font-size' => '24px',
                'line-height' => '42px',
                'font-weight' => '800',
                'font-style' => 'italic',
                'letter-spacing' => '-0.04',
                'text-transform' => 'none',
            ],
        ],
    ];
    $headings_array = [];
    foreach ($headings as $key => $heading) {
        $headings_array[] = [
            'id' => $heading['id'],
            'type' => 'custom_typography',
            'title' => $heading['title'],
            'google' => true,
            'font-backup' => false,
            'font-size' => true,
            'line-height' => true,
            'color' => false,
            'word-spacing' => false,
            'letter-spacing' => true,
            'text-align' => false,
            'text-transform' => true,
            'default' => $heading['defs'],
        ];
    }

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'main_headings_typography',
            'title' => esc_html__('Headings', 'courto'),
            'subsection' => true,
            'fields' => $headings_array
        ]
    );

    if (class_exists('WooCommerce')) {
        Redux::set_section(
            $theme_slug,
            [
                'id' => 'shop-option',
                'title' => esc_html__('Shop', 'courto'),
                'icon' => 'el-icon-shopping-cart',
                'fields' => []
            ]
        );

        Redux::set_section(
            $theme_slug,
            [
                'id' => 'shop-catalog-option',
                'title' => esc_html__('Catalog', 'courto'),
                'subsection' => true,
                'fields' => [
                    [
                        'id' => 'shop_body_color_bg',
                        'title' => esc_html__('Body Background Image/Color', 'courto'),
                        'type' => 'background',
                        'preview' => false,
                        'preview_media' => true,
                        'background-color' => true,
                        'transparent' => false,
                        'default' => [
                            'background-image' => '',
                            'background-repeat' => 'no-repeat',
                            'background-size' => 'contain',
                            'background-attachment' => 'scroll',
                            'background-position' => 'center top',
                            'background-color' => '',
                        ],
                    ],
                    [
                        'id' => 'shop_catalog__page_title_switch',
                        'title' => esc_html__('Use Page Title?', 'courto'),
                        'type' => 'switch',
                        'required' => ['page_title_switch', '=', true],
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_catalog__page_title_bg_image',
                        'title' => esc_html__('Page Title Background Image', 'courto'),
                        'type' => 'background',
                        'required' => [
                            ['page_title_switch', '=', true],
                            ['shop_catalog__page_title_switch', '=', true],
                        ],
                        'preview' => false,
                        'preview_media' => true,
                        'background-color' => false,
                        'default' => [
                            'background-repeat' => 'repeat',
                            'background-size' => 'cover',
                            'background-attachment' => 'scroll',
                            'background-position' => 'center center',
                            'background-color' => '',
                        ]
                    ],
                    [
                        'id' => 'shop_catalog_header-start',
                        'title' => esc_html__('Header', 'courto'),
                        'type' => 'section',
                        'required' => ['header_building_tool', '=', 'elementor'],
                        'indent' => true,
                    ],
                    [
                        'id' => 'shop_catalog_header_conditional',
                        'title' => esc_html__('Header', 'courto'),
                        'type' => 'switch',
                        'on' => esc_html__('Default', 'courto'),
                        'off' => esc_html__('Custom', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_catalog_header_page_select',
                        'type' => 'select',
                        'title' => esc_html__('Header Template', 'courto'),
                        'required' => ['shop_catalog_header_conditional', '=', ''],
                        'desc' => wp_kses(
                            sprintf(
                                '%s <a href="%s" target="_blank">%s</a> %s<br> %s',
                                __('Selected Template will be used for all Shop pages by default. You can edit/create Header Template in the', 'courto'),
                                admin_url('edit.php?post_type=header'),
                                __('Header Templates', 'courto'),
                                __('dashboard tab.', 'courto'),
                                courto_quick_tip(
                                    sprintf(
                                        __('Note: fine tuning is available through the Elementor\'s <code>Post Settings</code> tab, which is located <a href="%s" target="_blank">here</a>', 'courto'),
                                        get_template_directory_uri() . '/core/admin/img/dashboard/quick_tip__header_extra_options.png'
                                    )
                                )
                            ),
                            ['a' => ['href' => true, 'target' => true], 'br' => [], 'span' => ['class' => true], 'i' => ['class' => true], 'code' => []]
                        ),
                        'data' => 'posts',
                        'args' => [
                            'post_type' => 'header',
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                        ],
                    ],
                    [
                        'id' => 'shop_catalog_sidebar-start',
                        'title' => esc_html__('Sidebar Settings', 'courto'),
                        'type' => 'section',
                        'indent' => true,
                    ],
                    [
                        'id' => 'shop_catalog_sidebar_layout',
                        'title' => esc_html__('Sidebar Layout', 'courto'),
                        'type' => 'image_select',
                        'options' => [
                            'none' => [
                                'alt' => esc_html__('None', 'courto'),
                                'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                            ],
                            'left' => [
                                'alt' => esc_html__('Left', 'courto'),
                                'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                            ],
                            'right' => [
                                'alt' => esc_html__('Right', 'courto'),
                                'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                            ],
                        ],
                        'default' => 'left',
                    ],
                    [
                        'id' => 'shop_catalog_sidebar_def',
                        'title' => esc_html__('Shop Catalog Sidebar', 'courto'),
                        'type' => 'select',
                        'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                        'data' => 'sidebars',
                        'default' => 'shop_products',
                    ],
                    [
                        'id' => 'shop_catalog_sidebar_def_width',
                        'title' => esc_html__('Shop Sidebar Width', 'courto'),
                        'type' => 'button_set',
                        'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                        'options' => [
                            '9' => esc_html( '25%' ),
                            '8' => esc_html( '33%' ),
                        ],
                        'default' => '9',
                    ],
                    [
                        'id' => 'shop_catalog_sidebar_sticky',
                        'title' => esc_html__('Sticky Sidebar', 'courto'),
                        'type' => 'switch',
                        'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                        'default' => false,
                    ],
                    [
                        'id' => 'shop_catalog_sidebar_gap',
                        'title' => esc_html__( 'Sidebar Side Gap', 'courto' ),
                        'type' => 'select',
                        'required' => [ 'shop_catalog_sidebar_layout', '!=', 'none' ],
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
                        'default' => 'def',
                    ],
                    [
                        'id' => 'shop_catalog_sidebar-end',
                        'type' => 'section',
                        'indent' => false,
                    ],
                    [
                        'id' => 'shop_catalog_filters-start',
                        'title' => esc_html__('Filters Setting', 'courto'),
                        'type' => 'section',
                        'indent' => true,
                    ],
                    [
                        'id' => 'shop_filters_switcher',
                        'title' => esc_html__('Enable Filters', 'courto'),
                        'type' => 'switch',
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => false,
                    ],
                    [
                        'id' => 'shop_filters_sidebar_def',
                        'title' => esc_html__('Product Catalog Filters', 'courto'),
                        'type' => 'select',
                        'required' => ['shop_filters_switcher', '=', true],
                        'data' => 'sidebars',
                        'default' => 'shop_filters',
                    ],
                    [
                        'id' => 'shop_filters_sidebar_columns_width',
                        'title' => esc_html__('Adjust each Column Width', 'courto'),
                        'type' => 'button_set',
                        'required' => ['shop_filters_switcher', '=', true],
                        'options' => [
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                        ],
                    ],
                    [
                        'id' => 'filters_columns_1',
                        'title' => esc_html__('Column 1 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '1'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'filters_columns_2',
                        'title' => esc_html__('Column 2 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '2'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'filters_columns_3',
                        'title' => esc_html__('Column 3 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '3'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'filters_columns_4',
                        'title' => esc_html__('Column 4 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '4'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'filters_columns_5',
                        'title' => esc_html__('Column 5 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '5'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'filters_columns_6',
                        'title' => esc_html__('Column 6 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '6'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'filters_columns_7',
                        'title' => esc_html__('Column 7 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '7'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'filters_columns_8',
                        'title' => esc_html__('Column 8 Width', 'courto'),
                        'type' => 'dimensions',
                        'units' => ['px','%'],
                        'required' => [
                            'shop_filters_sidebar_columns_width', '=', '8'
                        ],
                        'width' => true,
                        'height' => false,
                        'default' => ['width' => 33.3, 'units' => '%'],
                    ],
                    [
                        'id' => 'shop_filters_sidebar_reset_switcher',
                        'title' => esc_html__('Reset Switcher', 'courto'),
                        'desc' => esc_html__('This button only works with the Wordpress Plugin \'Advanced AJAX Product Filters for WooCommerce\'', 'courto'),
                        'type' => 'switch',
                        'required' => ['shop_filters_switcher', '=', true],
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_catalog_filters-end',
                        'type' => 'section',
                        'indent' => false,
                    ],
                    [
                        'id' => 'shop_products_appearance-start',
                        'title' => esc_html__('Appearance', 'courto'),
                        'type' => 'section',
                        'indent' => true,
                    ],
                    [
                        'id' => 'shop_column',
                        'title' => esc_html__('Shop Column', 'courto'),
                        'type' => 'button_set',
                        'options' => [
                            '1' => esc_html('1'),
                            '2' => esc_html('2'),
                            '3' => esc_html('3'),
                            '4' => esc_html('4'),
                        ],
                        'default' => '3',
                    ],
                    [
                        'id' => 'shop_products_per_page',
                        'title' => esc_html__('Products per page', 'courto'),
                        'type' => 'spinner',
                        'min' => '1',
                        'max' => '100',
                        'default' => '12',
                    ],
                    [
                        'id' => 'use_secondary_image',
                        'title' => esc_html__('Use Secondary Image on Hover?', 'courto'),
                        'type' => 'switch',
                    ],
                    [
                        'id' => 'use_animation_shop',
                        'title' => esc_html__('Use Animation Shop?', 'courto'),
                        'type' => 'switch',
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_catalog_animation_style',
                        'title' => esc_html__('Animation Style', 'courto'),
                        'type' => 'select',
                        'required' => ['use_animation_shop', '=', true],
                        'select2' => ['allowClear' => false],
                        'options' => [
                            'fade-in' => esc_html__('Fade In', 'courto'),
                            'slide-top' => esc_html__('Slide Top', 'courto'),
                            'slide-bottom' => esc_html__('Slide Bottom', 'courto'),
                            'slide-left' => esc_html__('Slide Left', 'courto'),
                            'slide-right' => esc_html__('Slide Right', 'courto'),
                            'zoom' => esc_html__('Zoom', 'courto'),
                        ],
                        'default' => 'slide-left',
                    ],
                    [
                        'id' => 'shop_products_stars',
                        'title' => esc_html__('Star Rating', 'courto'),
                        'type' => 'switch',
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_products_overlay',
                        'title' => esc_html__('Overlay Background Color for Products', 'courto'),
                        'type' => 'color_rgba',
                        'mode' => 'background',
                        'default' => [
                            'alpha' => '0',
                            'rgba' => 'rgba(255, 255, 255, 0)',
                            'color' => '#ffffff',
                        ],
                    ],
                ]
            ]
        );

        Redux::set_section(
            $theme_slug,
            [
                'id' => 'shop-single-option',
                'title' => esc_html__('Single', 'courto'),
                'subsection' => true,
                'fields' => [
                    [
                        'id' => 'shop_single_body-start',
                        'title' => esc_html__('Body', 'courto'),
                        'type' => 'section',
                        'indent' => true,
                    ],
                    [
                        'id' => 'shop_single_body_color_bg',
                        'title' => esc_html__('Background Image/Color', 'courto'),
                        'type' => 'background',
                        'preview' => false,
                        'preview_media' => true,
                        'background-color' => true,
                        'transparent' => false,
                        'default' => [
                            'background-image' => '',
                            'background-repeat' => 'no-repeat',
                            'background-size' => 'contain',
                            'background-attachment' => 'scroll',
                            'background-position' => 'center top',
                            'background-color' => '',
                        ],
                    ],
                    [
                        'id' => 'shop_single_body-end',
                        'type' => 'section',
                        'indent' => false,
                    ],
                    [
                        'id' => 'shop_single__page_title_switch',
                        'title' => esc_html__('Use Page Title?', 'courto'),
                        'type' => 'switch',
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_single_page_title-start',
                        'title' => esc_html__('Page Title Settings', 'courto'),
                        'type' => 'section',
                        'required' => ['shop_single__page_title_switch', '=', true],
                        'indent' => true,
                    ],
                    [
                        'id' => 'shop_title_conditional',
                        'title' => esc_html__('Page Title Text', 'courto'),
                        'type' => 'switch',
                        'on' => esc_html__('Post Type Name', 'courto'),
                        'off' => esc_html__('Post Title', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_single_title_align',
                        'title' => esc_html__('Title Alignment', 'courto'),
                        'type' => 'button_set',
                        'options' => [
                            'left' => esc_html__('Left', 'courto'),
                            'center' => esc_html__('Center', 'courto'),
                            'right' => esc_html__('Right', 'courto'),
                        ],
                        'default' => 'left',
                    ],
                    [
                        'id' => 'shop_single_breadcrumbs_block_switch',
                        'title' => esc_html__('Breadcrumbs Display', 'courto'),
                        'type' => 'switch',
                        'required' => ['page_title_breadcrumbs_switch', '=', true],
                        'on' => esc_html__('Block', 'courto'),
                        'off' => esc_html__('Inline', 'courto'),
                        'default' => false,
                    ],
                    [
                        'id' => 'shop_single_breadcrumbs_align',
                        'title' => esc_html__('Breadcrumbs Alignment', 'courto'),
                        'type' => 'button_set',
                        'required' => [
                            ['page_title_breadcrumbs_switch', '=', true],
                            ['shop_single_breadcrumbs_block_switch', '=', true]
                        ],
                        'options' => [
                            'left' => esc_html__('Left', 'courto'),
                            'center' => esc_html__('Center', 'courto'),
                            'right' => esc_html__('Right', 'courto'),
                        ],
                        'default' => 'right',
                    ],
                    [
                        'id' => 'shop_single__page_title_bg_switch',
                        'title' => esc_html__('Use Background Image/Color?', 'courto'),
                        'type' => 'switch',
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_single__page_title_bg_image',
                        'title' => esc_html__('Background Image/Color', 'courto'),
                        'type' => 'background',
                        'required' => ['shop_single__page_title_bg_switch', '=', true],
                        'preview' => false,
                        'preview_media' => true,
                        'background-color' => true,
                        'transparent' => false,
                        'default' => [
                            'background-repeat' => 'repeat',
                            'background-size' => 'cover',
                            'background-attachment' => 'scroll',
                            'background-position' => 'center center',
                            'background-color' => '',
                        ],
                    ],
                    [
                        'id' => 'shop_single__page_title_padding',
                        'title' => esc_html__('Paddings Top/Bottom', 'courto'),
                        'type' => 'spacing',
                        'mode' => 'padding',
                        'all' => false,
                        'bottom' => true,
                        'top' => true,
                        'left' => false,
                        'right' => false,
                    ],
                    [
                        'id' => 'shop_single__page_title_margin',
                        'title' => esc_html__('Margin Bottom', 'courto'),
                        'type' => 'spacing',
                        'mode' => 'margin',
                        'all' => false,
                        'bottom' => true,
                        'top' => false,
                        'left' => false,
                        'right' => false,
                        'default' => ['margin-bottom' => '60'],
                    ],
                    [
                        'id' => 'shop_single_page_title-end',
                        'type' => 'section',
                        'indent' => false,
                    ],
                    [
                        'id' => 'shop_single_sidebar-start',
                        'title' => esc_html__('Sidebar Settings', 'courto'),
                        'type' => 'section',
                        'indent' => true,
                    ],
                    [
                        'id' => 'shop_single_sidebar_layout',
                        'title' => esc_html__('Sidebar Layout', 'courto'),
                        'type' => 'image_select',
                        'options' => [
                            'none' => [
                                'alt' => esc_html__('None', 'courto'),
                                'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                            ],
                            'left' => [
                                'alt' => esc_html__('Left', 'courto'),
                                'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                            ],
                            'right' => [
                                'alt' => esc_html__('Right', 'courto'),
                                'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                            ],
                        ],
                        'default' => 'none',
                    ],
                    [
                        'id' => 'shop_single_sidebar_def',
                        'title' => esc_html__('Sidebar Template', 'courto'),
                        'type' => 'select',
                        'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                        'data' => 'sidebars',
                    ],
                    [
                        'id' => 'shop_single_sidebar_def_width',
                        'title' => esc_html__('Sidebar Width', 'courto'),
                        'type' => 'button_set',
                        'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                        'options' => [
                            '9' => esc_html( '25%' ),
                            '8' => esc_html( '33%' ),
                        ],
                        'default' => '9',
                    ],
                    [
                        'id' => 'shop_single_sidebar_sticky',
                        'title' => esc_html__('Sticky Sidebar', 'courto'),
                        'type' => 'switch',
                        'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                        'default' => false,
                    ],
                    [
                        'id' => 'shop_single_sidebar_gap',
                        'title' => esc_html__( 'Sidebar Side Gap', 'courto' ),
                        'type' => 'select',
                        'required' => [ 'shop_single_sidebar_layout', '!=', 'none' ],
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
                        'default' => 'def',
                    ],
                    [
                        'id' => 'shop_single_sidebar-end',
                        'type' => 'section',
                        'indent' => false,
                    ],
                ]
            ]
        );

        Redux::set_section(
            $theme_slug,
            [
                'title' => esc_html__('Related', 'courto'),
                'id' => 'shop-related-option',
                'subsection' => true,
                'fields' => [
                    [
                        'id' => 'shop_related_columns',
                        'title' => esc_html__('Related products column', 'courto'),
                        'type' => 'button_set',
                        'options' => [
                            '1' => esc_html('1'),
                            '2' => esc_html('2'),
                            '3' => esc_html('3'),
                            '4' => esc_html('4'),
                        ],
                        'default' => '4',
                    ],
                    [
                        'id' => 'shop_r_products_per_page',
                        'title' => esc_html__('Related products per page', 'courto'),
                        'type' => 'spinner',
                        'min' => '1',
                        'max' => '100',
                        'default' => '4',
                    ],
                ]
            ]
        );

        Redux::set_section(
            $theme_slug,
            [
                'title' => esc_html__('Cart', 'courto'),
                'id' => 'shop-cart-option',
                'subsection' => true,
                'fields' => [
                    [
                        'id' => 'shop_cart__page_title_switch',
                        'title' => esc_html__('Use Page Title?', 'courto'),
                        'type' => 'switch',
                        'required' => ['page_title_switch', '=', true],
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_cart__page_title_bg_image',
                        'title' => esc_html__('Page Title Background Image', 'courto'),
                        'type' => 'background',
                        'required' => [
                            ['page_title_switch', '=', true],
                            ['shop_cart__page_title_switch', '=', true],
                        ],
                        'background-color' => false,
                        'preview_media' => true,
                        'preview' => false,
                        'default' => [
                            'background-repeat' => 'repeat',
                            'background-size' => 'cover',
                            'background-attachment' => 'scroll',
                            'background-position' => 'center center',
                            'background-color' => '',
                        ],
                    ],
                ]
            ]
        );

        Redux::set_section(
            $theme_slug,
            [
                'id' => 'shop-checkout-option',
                'title' => esc_html__('Checkout', 'courto'),
                'subsection' => true,
                'fields' => [
                    [
                        'id' => 'shop_checkout__page_title_switch',
                        'title' => esc_html__('Use Page Title?', 'courto'),
                        'type' => 'switch',
                        'required' => ['page_title_switch', '=', true],
                        'on' => esc_html__('Use', 'courto'),
                        'off' => esc_html__('Hide', 'courto'),
                        'default' => true,
                    ],
                    [
                        'id' => 'shop_checkout__page_title_bg_image',
                        'title' => esc_html__('Page Title Background Image', 'courto'),
                        'type' => 'background',
                        'required' => [
                            ['page_title_switch', '=', true],
                            ['shop_checkout__page_title_switch', '=', true],
                        ],
                        'background-color' => false,
                        'preview_media' => true,
                        'preview' => false,
                        'default' => [
                            'background-repeat' => 'repeat',
                            'background-size' => 'cover',
                            'background-attachment' => 'scroll',
                            'background-position' => 'center center',
                            'background-color' => '',
                        ],
                    ],
                ]
            ]
        );
    }

    $advanced_fields = [
        [
            'id' => 'advanced_warning',
            'title' => esc_html__('Attention! This tab stores functionality that can harm site reliability.', 'courto'),
            'type' => 'info',
            'desc' => esc_html__('Site troublefree operation is not ensured, if any of the following options is changed.', 'courto'),
            'style' => 'critical',
            'icon' => 'el el-warning-sign',
        ],
        [
            'id' => 'advanced_divider',
            'type' => 'divide'
        ],
        [
            'id' => 'advanced-wp-start',
            'title' => esc_html__('WordPress', 'courto'),
            'type' => 'section',
            'indent' => true,
        ],
        [
            'id' => 'disable_wp_gutenberg',
            'title' => esc_html__('Gutenberg Stylesheet', 'courto'),
            'type' => 'switch',
            'desc' => esc_html__('Dequeue CSS files.', 'courto') . courto_quick_tip(
                wp_kses(
                    __('Eliminates <code>wp-block-library-css</code> stylesheet. <br>Before disabling ensure that Gutenberg editor is not used anywhere throughout the site.', 'courto'),
                    ['br' => [], 'code' => []]
                )
            ),
            'on' => esc_html__('Dequeue', 'courto'),
            'off' => esc_html__('Default', 'courto'),
        ],
        [
            'id' => 'wordpress_widgets',
            'title' => esc_html__('WordPress Widgets', 'courto'),
            'type' => 'switch',
            'on' => esc_html__('Classic', 'courto'),
            'off' => esc_html__('Gutenberg', 'courto'),
            'default' => true,
        ],
        [
            'id' => 'wgl_input_style',
            'title' => esc_html__('Checkboxes and Radio Buttons Styling', 'courto'),
            'type' => 'switch',
            'on' => esc_html__('Disable', 'courto'),
            'off' => esc_html__('Default', 'courto'),
            'default' => false,
        ],
        [
            'id' => 'advanced-wp-end',
            'type' => 'section',
            'indent' => false,
        ],
    ];

    if (class_exists('Elementor\Plugin')) {
        $advanced_elementor = [
            [
                'id' => 'advanced-elementor-start',
                'title' => esc_html__('Elementor', 'courto'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'disable_elementor_googlefonts',
                'title' => esc_html__('Google Fonts', 'courto'),
                'type' => 'switch',
                'desc' => esc_html__('Dequeue font pack.', 'courto') . courto_quick_tip(sprintf(
                    '%s <a href="%s" target="_blank">%s</a>%s',
                    esc_html__('See: ', 'courto'),
                    esc_url('https://docs.elementor.com/article/286-speed-up-a-slow-site'),
                    esc_html__('Optimizing a Slow Site w/ Elementor', 'courto'),
                    wp_kses(
                        __('<br>Note: breaks all fonts selected within <code>Group_Control_Typography</code> (if any). Has no affect on <code>Theme Options->Typography</code> fonts.', 'courto'),
                        ['br' => [], 'code' => []]
                    )
                )),
                'on' => esc_html__('Disable', 'courto'),
                'off' => esc_html__('Default', 'courto'),
            ],
            [
                'id' => 'disable_elementor_fontawesome',
                'title' => esc_html__('Font Awesome Pack', 'courto'),
                'type' => 'switch',
                'desc' => esc_html__('Dequeue icon pack.', 'courto')
                    . courto_quick_tip(esc_html__('Note: Font Awesome is essential for Courto theme. Disable only if it already enqueued by some other plugin.', 'courto')),
                'on' => esc_html__('Disable', 'courto'),
                'off' => esc_html__('Default', 'courto'),
            ],
            [
                'id'    => 'wgl_elementor_library_install',
                'type'  => 'raw',
                'title' => esc_html__('Import WGL Elementor Default Templates', 'courto'),
                'full_width' => false,
                'content' => '<button id="wgl-import-elementor-templates" class="button button-primary">' . esc_html__('Import', 'courto') . '</button>',
            ],
            [
                'id' => 'advanced-elelemntor-end',
                'type' => 'section',
                'indent' => false,
            ],
        ];
        array_push($advanced_fields, ...$advanced_elementor);
    }

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'advanced',
            'title' => esc_html__('Advanced', 'courto'),
            'icon' => 'el el-warning-sign',
            'fields' => $advanced_fields
        ]
    );

});