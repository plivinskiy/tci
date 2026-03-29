<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-link-overlay.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager
};

class WGL_Link_Overlay extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-link-overlay';
    }

    public function get_title()
    {
        return esc_html__( 'WGL Link Overlay', 'courto-core' );
    }

    public function get_icon()
    {
        return 'wgl-link-overlay';
    }

    public function get_keywords()
    {
        return [ 'link' ];
    }

    public function get_categories()
    {
        return [ 'wgl-modules' ];
    }

    protected function register_controls()
    {
        /** CONTENT -> GENERAL */

        $this->start_controls_section(
            'content_general',
            [ 'label' => esc_html__( 'General', 'courto-core' ) ]
        );

        $this->add_control(
            'overlay_layout',
            [
                'label' => esc_html__('Overlay', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1st' => esc_html__('First Dom Element', 'courto-core'),
                    '2nd' => esc_html__('Second Dom Element', 'courto-core'),
                    '3rd' => esc_html__('Third Dom Element', 'courto-core'),
                    'column' => esc_html__('Column', 'courto-core'),
                    'row' => esc_html__('Row', 'courto-core'),
                ],
                'description' => esc_html__('Which element should be overlay?', 'courto-core'),
                'default' => 'row',
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /** CONTENT -> CONTENT */
    }

    protected function render()
    {
        if (!empty($this->get_settings_for_display('link')['url'])) {
            $this->add_link_attributes('link', $this->get_settings_for_display('link'));
            echo '<a class="wgl-link-overlay" ' , $this->get_render_attribute_string('link') , ' data-link-position="', $this->get_settings_for_display('overlay_layout') ,'"></a>';
        }
    }

}
