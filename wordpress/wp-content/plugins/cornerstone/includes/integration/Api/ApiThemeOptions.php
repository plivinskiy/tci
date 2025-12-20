<?php

namespace Cornerstone\API\ThemeOptions;

use const Themeco\Cornerstone\API\BUILTIN_VALUES;

use function Cornerstone\Api\Controls\controls;

const STACK_ENDPOINT_KEY = "cs_api_endpoints";

/**
 * Individual item control for endpoint list editor
 */
function item_group_controls() {
  $controls = [
    //[
      //'type' => 'text',
      //'key' => 'id',
      //'label' => __("ID", "cornerstone"),
    //],

    [
      'type' => 'text',
      'key' => 'name',
      'label' => __("Name", "cornerstone"),
    ],
  ];

  $controls = array_merge($controls, controls());

  return $controls;
}


/**
 * Add API module controls
 */
add_filter("cs_theme_options_modules", function($modules) {

  // API top level group
  $modules[] = [
    'type'  => 'group-sub-module',
    'label' => __( 'API', 'cornerstone' ),
    'options' => [ 'tag' => 'social', 'name' => 'x-theme-options:api' ],
    'controls' => [
      [
        'type' => 'group',
        //'label' => __("Endpoints", "cornerstone"),
        'controls' => [

          // Endpoint group editor
          [
            'label' => __("Global Endpoints", "cornerstone"),
            'key' => STACK_ENDPOINT_KEY,
            'type' => 'list',
            'options' => [
              // Initial object values
              'initial' => array_merge(
                cs_api_global_values(),
                [
                  'name' => __('My Endpoint', "cornerstone"),
                ]
              ),
              'item_label' => '{{index}}. {{name}}',
            ],
            'controls' => item_group_controls(),
          ],

        ],
      ],
    ],
  ];

  return $modules;
});

// Setup on ThemeOptions before init
add_action("cs_theme_options_before_init", function() {
  // Register options
  cs_stack_register_options([
    'cs_api_endpoints' => [],
  ]);
});

// Global endpoints setup
add_filter("cs_api_global_endpoints", function($default = []) {
  // Send stack theme options value
  $val = cs_stack_get_value(STACK_ENDPOINT_KEY);

  return is_array($val)
    ? $val
    : @json_decode($val, true);
}, -10, 1);
