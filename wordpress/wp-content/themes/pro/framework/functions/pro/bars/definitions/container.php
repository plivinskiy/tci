<?php

// =============================================================================
// FRAMEWORK/FUNCTIONS/PRO/BARS/DEFINITIONS/CONTAINER.PHP
// -----------------------------------------------------------------------------
// V2 element definitions.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Controls
//   02. Control Groups
//   03. Values
//   04. Define Element
//   05. Builder Setup
//   06. Register Element
// =============================================================================

// Values
// =============================================================================

$values = cs_compose_values(
  array(
    'container_max_width'   => cs_value( 'none', 'style' ),
    'container_max_height'  => cs_value( 'none', 'style' ),
    'container_bg_color'    => cs_value( 'transparent', 'style:color' ),
    'container_bg_advanced' => cs_value( false, 'all' ),
  ),
  'bg',
  array(
    'container_flex'                  => cs_value( '0 1 auto', 'style' ),
    'container_row_flex_direction'    => cs_value( 'row', 'style' ),
    'container_row_flex_wrap'         => cs_value( false, 'style' ),
    'container_row_flex_justify'      => cs_value( 'space-between', 'style' ),
    'container_row_flex_align'        => cs_value( 'center', 'style' ),
    'container_col_flex_direction'    => cs_value( 'column', 'style' ),
    'container_col_flex_wrap'         => cs_value( false, 'style' ),
    'container_col_flex_justify'      => cs_value( 'space-between', 'style' ),
    'container_col_flex_align'        => cs_value( 'center', 'style' ),
    'container_margin'                => cs_value( '0px', 'style' ),
    'container_padding'               => cs_value( '0px', 'style' ),
    'container_border_width'          => cs_value( '0px', 'style' ),
    'container_border_style'          => cs_value( 'none', 'style' ),
    'container_border_color'          => cs_value( 'transparent', 'style:color' ),
    'container_border_radius'         => cs_value( '0px', 'style' ),
    'container_box_shadow_dimensions' => cs_value( '0em 0em 0em 0em', 'style' ),
    'container_box_shadow_color'      => cs_value( 'transparent', 'style:color' ),
  ),
  'omega'
);


// Style
// =============================================================================

function x_element_style_container() {
  return x_get_view( 'styles/elements', 'container', 'css', array(), false );
}


// Render
// =============================================================================

function x_element_render_container( $data ) {
  return x_get_view( 'elements', 'container', '', $data, false );
}


// Define Element
// =============================================================================

$data = array(
  'title'  => __( 'Container', '__x__' ),
  'values' => $values,
  'builder' => 'x_element_builder_setup_container',
  'style' => 'x_element_style_container',
  'render' => 'x_element_render_container',
  'options' => array(
    'valid_children'    => array( '*' ),
    'index_labels'      => true,
    'library'           => false,
    'empty_placeholder' => false,
    'contrast_keys' => array(
      'bg:container_bg_advanced',
      'container_bg_color'
    )
  )
);



// Builder Setup
// =============================================================================

function x_element_builder_setup_container() {

  // Individual Controls
  // -------------------

  $control_container_max_width = array(
    'key'     => 'container_max_width',
    'type'    => 'unit-slider',
    'label'   => __( 'Max Width', '__x__' ),
    'options' => array(
      'available_units' => array( 'px', 'em', 'rem', '%' ),
      'valid_keywords'  => array( 'none' ),
      'fallback_value'  => 'none',
      'ranges'          => array(
        'px'  => array( 'min' => '0', 'max' => '300', 'step' => '1'    ),
        'em'  => array( 'min' => '0', 'max' => '30',  'step' => '0.25' ),
        'rem' => array( 'min' => '0', 'max' => '30',  'step' => '0.25' ),
        '%'   => array( 'min' => '0', 'max' => '100', 'step' => '1'    ),
      ),
    ),
  );

  $control_container_max_height = array(
    'key'     => 'container_max_height',
    'type'    => 'unit-slider',
    'label'   => __( 'Max Height', '__x__' ),
    'options' => array(
      'available_units' => array( 'px', 'em', 'rem', '%' ),
      'valid_keywords'  => array( 'none' ),
      'fallback_value'  => 'none',
      'ranges'          => array(
        'px'  => array( 'min' => '0', 'max' => '300', 'step' => '1'    ),
        'em'  => array( 'min' => '0', 'max' => '30',  'step' => '0.25' ),
        'rem' => array( 'min' => '0', 'max' => '30',  'step' => '0.25' ),
        '%'   => array( 'min' => '0', 'max' => '100', 'step' => '1'    ),
      ),
    ),
  );

  $control_container_bg_color = array(
    'key'     => 'container_bg_color',
    'type'    => 'color',
    'label'   => __( 'Background', '__x__' ),
    'options' => array( 'label' => __( 'Select', '__x__' ) ),
  );

  $control_container_bg_advanced = array(
    'keys' => array(
      'bg_advanced' => 'container_bg_advanced',
    ),
    'type'    => 'checkbox-list',
    'options' => array(
      'list' => array(
        array( 'key' => 'bg_advanced', 'label' => __( 'Advanced', '__x__' ) ),
      ),
    ),
  );

  $control_container_background = array(
    'type'     => 'group',
    'title'    => __( 'Background', '__x__' ),
    'controls' => array(
      $control_container_bg_color,
      $control_container_bg_advanced,
    ),
  );


  // Compose Controls
  // ----------------

  return cs_compose_controls(
    array(
      'controls' => array(
        array(
          'type'     => 'group',
          'title'    => __( 'Setup', '__x__' ),
          'group'    => 'container:setup',
          'controls' => array(
            $control_container_max_width,
            $control_container_max_height,
            $control_container_background,
          ),
        )
      ),
      'controls_std_design_setup' => array(
        array(
          'type'     => 'group',
          'title'    => __( 'Design Setup', '__x__' ),
          'controls' => array(
            $control_container_max_width,
            $control_container_max_height,
          ),
        ),
        cs_control( 'margin', 'container' )
      ),
      'controls_std_design_colors' => array(
        array(
          'type'     => 'group',
          'title'    => __( 'Base Colors', '__x__' ),
          'controls' => array(
            array(
              'keys'      => array( 'value' => 'container_box_shadow_color' ),
              'type'      => 'color',
              'label'     => __( 'Box<br>Shadow', '__x__' ),
              'condition' => array( 'key' => 'container_box_shadow_dimensions', 'op' => 'NOT EMPTY' ),
              'options'   => array( 'label' => __( 'Select', '__x__' ) ),
            ),
            $control_container_bg_color,
          ),
        ),
        cs_control( 'border', 'container', array(
          'options'   => array( 'color_only' => true ),
          'conditions' => array(
            array( 'key' => 'container_border_width', 'op' => 'NOT EMPTY' ),
            array( 'key' => 'container_border_style', 'op' => '!=', 'value' => 'none' )
          ),
        ) )
      ),
      'control_nav' => array(
        'container'        => __( 'Container', '__x__' ),
        'container:setup'  => __( 'Setup', '__x__' ),
        'container:design' => __( 'Design', '__x__' ),
      )
    ),
    cs_partial_controls( 'bg', array(
      'group'     => 'container:design',
      'condition' => array( 'container_bg_advanced' => true )
    ) ),
    array(
      'controls' => array(
        cs_control( 'flexbox', 'container', array(
          'group' => 'container:design',
          'layout_pre' => 'row',
          'conditions' => array( array( 'key' => '_region', 'op' => 'IN', 'value' => array( 'top', 'bottom', 'footer' ) ) ),
        ) ),
        cs_control( 'flexbox', 'container', array(
          'group' => 'container:design',
          'layout_pre' => 'col',
          'conditions'  => array( array( 'key' => '_region', 'op' => 'IN', 'value' => array( 'left', 'right' ) ) ),
        ) ),
        cs_control( 'margin', 'container', array( 'group' => 'container:design' ) ),
        cs_control( 'padding', 'container', array( 'group' => 'container:design' ) ),
        cs_control( 'border', 'container', array( 'group' => 'container:design' ) ),
        cs_control( 'border-radius', 'container', array( 'group' => 'container:design' ) ),
        cs_control( 'box-shadow', 'container', array( 'group' => 'container:design' ) )
      )
    ),
    cs_partial_controls( 'omega' )
  );
}



// Register Module
// =============================================================================

cs_register_element( 'container', $data );
