<?php

function wgl_elementor_library_default_option()
{
    $default_theme_options = '
    [
        {
            "meta": {
                "thumbnail": "wgl_header_1.webp",
                "tags": ["Header"]
            },
            "elementor_template": "header_def_1.json"
        },
        {
            "meta": {
                "thumbnail": "wgl_header_overlay_1.webp",
                "tags": ["Header"]
            },
            "elementor_template": "header_overlay_full_width_1.json"
        },
        {
            "meta": {
                "thumbnail": "wgl_header_overlay_full_width_2.webp",
                "tags": ["Header"]
            },
            "elementor_template": "header_overlay_full_width_2.json"
        },
        {
            "meta": {
                "thumbnail": "wgl_footer_1.webp",
                "tags": ["Footer"]
            },
            "elementor_template": "footer_def_1.json"
        },
        {
            "meta": {
                "thumbnail": "wgl_block_1.webp",
                "tags": ["Block"]
            },
            "elementor_template": "showcase_1.json"
        },
        {
            "meta": {
                "thumbnail": "wgl_block_2.webp",
                "tags": ["Block"]
            },
            "elementor_template": "double_headings_animation_1.json"
        },
        {
            "meta": {
                "thumbnail": "wgl_block_3.webp",
                "tags": ["Block"]
            },
            "elementor_template": "wgl_block_1.json"
        },
        {
            "meta": {
                "thumbnail": "Mobile-Menu.webp",
                "tags": ["Mobile Menu"]
            },
            "elementor_template": "mobile_menu.json"
        },
        {
            "meta": {
                "thumbnail": "clients_text_path.webp",
                "tags": ["Block"]
            },
            "elementor_template": "clients_text_path.json"
        },
        {
            "meta": {
                "thumbnail": "infobox_who_we_are.webp",
                "tags": ["Block"]
            },
            "elementor_template": "infobox_who_we_are.json"
        },
        {
            "meta": {
                "thumbnail": "pricing_table_services.webp",
                "tags": ["Block"]
            },
            "elementor_template": "pricing_table_services.json"
        },
        {
            "meta": {
                "thumbnail": "infobox_services.webp",
                "tags": ["Block"]
            },
            "elementor_template": "infobox_services.json"
        },
        {
            "meta": {
                "thumbnail": "infobox_sticky.webp",
                "tags": ["Block"]
            },
            "elementor_template": "infobox_sticky.json"
        },
        {
            "meta": {
                "thumbnail": "doble_headings_with_circle.webp",
                "tags": ["Block"]
            },
            "elementor_template": "double_headings_with_circle.json"
        },
        {
            "meta": {
                "thumbnail": "construction_contact_1_block.webp",
                "tags": ["Block"]
            },
            "elementor_template": "construction_contact_1_block.json"
        },
        {
            "meta": {
                "thumbnail": "construction_our_history_time_line.webp",
                "tags": ["Block"]
            },
            "elementor_template": "construction_our_history_time_line.json"
        },
        {
            "meta": {
                "thumbnail": "construction_faqs_accordion.webp",
                "tags": ["Block"]
            },
            "elementor_template": "construction_faqs_accordion.json"
        },
        {
            "meta": {
                "thumbnail": "ai_homepage_text_editor.webp",
                "tags": ["Block"]
            },
            "elementor_template": "ai_homepage_text_editor.json"
        },
        {
            "meta": {
                "thumbnail": "solar_energy_homepage_pie_chart.webp",
                "tags": ["Block"]
            },
            "elementor_template": "solar_energy_homepage_pie_chart.json"
        },
        {
            "meta": {
                "thumbnail": "app_homepage_scrolltrigger.webp",
                "tags": ["Block"]
            },
            "elementor_template": "app_homepage_scrolltrigger.json"
        },
        {
            "meta": {
                "thumbnail": "construction_homepage_striped_services.webp",
                "tags": ["Block"]
            },
            "elementor_template": "construction_homepage_striped_services.json"
        },
        {
            "meta": {
                "thumbnail": "app_how_it_works_physics_buttons.webp",
                "tags": ["Block"]
            },
            "elementor_template": "app_how_it_works_physics_buttons.json"
        },
        {
            "meta": {
                "thumbnail": "handmade_ceramic_homepage_testimonials_block_1.webp",
                "tags": ["Block"]
            },
            "elementor_template": "handmade_ceramic_homepage_testimonials_block_1.json"
        },
        {
            "meta": {
                "thumbnail": "memecoin_homepage_footer.webp",
                "tags": ["Footer"]
            },
            "elementor_template": "memecoin_homepage_footer.json"
        },
        {
            "meta": {
                "thumbnail": "interior_design_agency_homepage_footer.webp",
                "tags": ["Footer"]
            },
            "elementor_template": "interior_design_agency_homepage_footer.json"
        },
        {
            "meta": {
                "thumbnail": "creative_agency_homepage_splittext.webp",
                "tags": ["Block"]
            },
            "elementor_template": "creative_agency_homepage_splittext.json"
        },
        {
            "meta": {
                "thumbnail": "interior_design_agency_our_philosophy_text.webp",
                "tags": ["Block"]
            },
            "elementor_template": "interior_design_agency_our_philosophy_text.json"
        },
        {
            "meta": {
                "thumbnail": "solar_energy_explore_infobox_1.webp",
                "tags": ["Block"]
            },
            "elementor_template": "solar_energy_explore_infobox_1.json"
        },
        {
            "meta": {
                "thumbnail": "solar_energy_what_we_offer_infobox_2.webp",
                "tags": ["Block"]
            },
            "elementor_template": "solar_energy_what_we_offer_infobox_2.json"
        },
        {
            "meta": {
                "thumbnail": "app_how_it_works_steps.webp",
                "tags": ["Block"]
            },
            "elementor_template": "app_how_it_works_steps.json"
        },
        {
            "meta": {
                "thumbnail": "handmade_ceramic_homepage_videobox.webp",
                "tags": ["Block"]
            },
            "elementor_template": "handmade_ceramic_homepage_videobox.json"
        }
    ]
    ';

    $default_theme_options = json_decode($default_theme_options, true);

    update_option('wgl_elementor_custom_library_default', $default_theme_options);
}

$version = '1.0.0';
if (get_option('wgl_elementor_library_option_version') !== $version 
    || !get_option('wgl_elementor_custom_library_default')
) {
	wgl_elementor_library_default_option();
	update_option('wgl_elementor_library_option_version', $version);
}