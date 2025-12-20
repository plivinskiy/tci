<?php

// =============================================================================
// FRAMEWORK/FUNCTIONS/PRO/BARS/DEFINITIONS/BAR.PHP
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
    'bar_base_font_size'          => cs_value( '16px', 'style' ),
    'bar_z_index'                 => cs_value( '9999', 'style' ),
    'bar_position_top'            => cs_value( 'relative', 'all' ),
    'bar_margin_top'              => cs_value( '0px', 'style' ),
    'bar_margin_sides'            => cs_value( '0px', 'style' ),
    'bar_scroll'                  => cs_value( false, 'markup' ),
    'bar_bg_color'                => cs_value( '#ffffff', 'style:color' ),
    'bar_bg_advanced'             => cs_value( false, 'all' ),

    'bar_sticky'                  => cs_value( false, 'markup' ),
    'bar_sticky_keep_margin'      => cs_value( false, 'markup' ),
    'bar_sticky_hide_initially'   => cs_value( false, 'markup' ),
    'bar_sticky_z_stack'          => cs_value( false, 'markup' ),
    'bar_sticky_trigger_selector' => cs_value( '', 'markup' ),
    'bar_sticky_trigger_offset'   => cs_value( '0', 'markup' ),
    'bar_sticky_shrink'           => cs_value( '1', 'markup' ),
  ),
  'bg',
  array(
    'bar_width'                 => cs_value( '15em', 'style' ),
    'bar_height'                => cs_value( '6em', 'style' ),
    'bar_outer_spacing'         => cs_value( '2em', 'style' ),
    'bar_content_length'        => cs_value( '100%', 'style' ),
    'bar_content_max_length'    => cs_value( 'none', 'style' ),

    'bar_row_flex_direction'    => cs_value( 'row', 'style' ),
    'bar_row_flex_wrap'         => cs_value( false, 'style' ),
    'bar_row_flex_justify'      => cs_value( 'space-between', 'style' ),
    'bar_row_flex_align'        => cs_value( 'center', 'style' ),

    'bar_col_flex_direction'    => cs_value( 'column', 'style' ),
    'bar_col_flex_wrap'         => cs_value( false, 'style' ),
    'bar_col_flex_justify'      => cs_value( 'space-between', 'style' ),
    'bar_col_flex_align'        => cs_value( 'center', 'style' ),

    'bar_padding'               => cs_value( '0em', 'style' ),
    'bar_border_width'          => cs_value( '0px', 'style' ),
    'bar_border_style'          => cs_value( 'none', 'style' ),
    'bar_border_color'          => cs_value( 'transparent', 'style:color' ),
    'bar_box_shadow_dimensions' => cs_value( '0em 0.15em 2em', 'style' ),
    'bar_box_shadow_color'      => cs_value( 'rgba(0, 0, 0, 0.15)', 'style:color' ),
  ),
  'omega'
);


// Style
// =============================================================================

function x_element_style_bar() {
  return x_get_view( 'styles/elements', 'bar', 'css', array(), false );
}

// Render
// =============================================================================

function x_element_render_bar( $data ) {
  return x_get_view( 'elements', 'bar', '', $data, false );
}


// Define Element
// =============================================================================

$data = array(
  'title'  => __( 'Bar', '__x__' ),
  'values' => $values,
  'builder' => 'x_element_builder_setup_bar',
  'style' => 'x_element_style_bar',
  'render' => 'x_element_render_bar',
  'options' => array(
    'valid_children'    => array( 'container' ),
    'index_labels'      => true,
    'library'           => false,
    'empty_placeholder' => false,
    'default_children'  => array(
      array( '_type' => 'container', 'container_flex' => '1 0 auto' )
    ),
    'add_new_element'   => array( '_type' => 'container' ),
    'contrast_keys' => array(
      'bg:bar_bg_advanced',
      'bar_bg_color'
    )
  )
);



// Builder Setup
// =============================================================================

function x_element_builder_setup_bar() {

  // Conditions
  // ----------

  $condition_bar_is_t               = array( '_region' => 'top' );
  $condition_bar_is_t_and_sticky    = array( array( '_region' => 'top' ), array( 'bar_sticky' => true ) );
  $condition_bar_is_t_and_absolute  = array( array( '_region' => 'top' ), array( 'bar_position_top' => 'absolute' ) );
  $condition_bar_is_lr              = array( 'key' => '_region', 'op' => 'IN', 'value' => array( 'left', 'right' ) );
  $condition_bar_is_tbf             = array( 'key' => '_region', 'op' => 'IN', 'value' => array( 'top', 'bottom', 'footer' ) );
  $condition_bar_height_is_auto     = array( 'bar_height' => 'auto' );
  $condition_bar_height_is_not_auto = array( 'key' => 'bar_height', 'op' => '!=', 'value' => 'auto' );


  // Options
  // -------

  $options_bar_offset = array(
    'available_units' => array( 'px' ),
    'valid_keywords'  => array( 'calc' ),
    'fallback_value'  => '0px',
  );


  // Individual Controls
  // -------------------

  $control_bar_base_font_size = array(
    'key'     => 'bar_base_font_size',
    'type'    => 'unit',
    'options' => array(
      'available_units' => array( 'px', 'em', 'rem' ),
      'valid_keywords'  => array( 'calc' ),
      'fallback_value'  => '16px',
      'ranges'          => array(
        'px'  => array( 'min' => 10,  'max' => 24,  'step' => 1    ),
        'em'  => array( 'min' => 0.5, 'max' => 1.5, 'step' => 0.01 ),
        'rem' => array( 'min' => 0.5, 'max' => 1.5, 'step' => 0.01 ),
      ),
    ),
  );

  $control_bar_z_index = array(
    'key'     => 'bar_z_index',
    'type'    => 'unit',
    'options' => array(
      'unit_mode'      => 'unitless',
      'fallback_value' => '9999',
    ),
  );

  $control_bar_base_font_size_and_z_index = array(
    'type'     => 'group',
    'title'    => __( 'Font Size &amp; Z-Index', '__x__' ),
    'controls' => array(
      $control_bar_base_font_size,
      $control_bar_z_index,
    ),
  );

  $control_bar_position_top = array(
    'key'       => 'bar_position_top',
    'type'      => 'choose',
    'title'     => __( 'Initial Position', '__x__' ),
    'condition' => $condition_bar_is_t,
    'options'   => array(
      'choices' => array(
        array( 'value' => 'relative', 'label' => __( 'Relative', '__x__' ) ),
        array( 'value' => 'absolute', 'label' => __( 'Absolute', '__x__' ) ),
      ),
    ),
  );

  $control_bar_scroll = array(
    'key'       => 'bar_scroll',
    'type'      => 'choose',
    'label'     => __( 'Content Scrolling', '__x__' ),
    'condition' => $condition_bar_height_is_not_auto,
    'options'   => cs_recall( 'options_choices_off_on_bool' ),
  );

  $control_bar_sticky = array(
    'key'       => 'bar_sticky',
    'type'      => 'choose',
    'label'     => __( 'Sticky Bar', '__x__' ),
    'condition' => array( '_region' => 'top' ),
    'options'   => cs_recall( 'options_choices_off_on_bool' ),
  );

  $control_bar_bg_color = array(
    'keys'    => array( 'value' => 'bar_bg_color' ),
    'type'    => 'color',
    'label'   => __( 'Background', '__x__' ),
    'options' => array( 'label' => __( 'Select', '__x__' ) ),
  );

  $control_bar_bg_advanced = array(
    'keys' => array(
      'bg_advanced' => 'bar_bg_advanced',
    ),
    'type'    => 'checkbox-list',
    'options' => array(
      'list' => array(
        array( 'key' => 'bg_advanced', 'label' => __( 'Advanced', '__x__' ) ),
      ),
    ),
  );

  $control_bar_background = array(
    'type'     => 'group',
    'title'    => __( 'Background', '__x__' ),
    'controls' => array(
      $control_bar_bg_color,
      $control_bar_bg_advanced,
    ),
  );

  $control_bar_sticky_options = array(
    'keys' => array(
      'sticky_keep_margins'   => 'bar_sticky_keep_margin',
      'sticky_hide_initially' => 'bar_sticky_hide_initially',
      'sticky_z_stack'        => 'bar_sticky_z_stack',
    ),
    'type'       => 'checkbox-list',
    'label'      => __( 'Options', '__x__' ),
    'options'    => array(
      'list' => array(
        array( 'key' => 'sticky_keep_margins',   'label' => __( 'Keep Margin', '__x__' ) ),
        array( 'key' => 'sticky_hide_initially', 'label' => __( 'Hide Initially', '__x__' ) ),
        array( 'key' => 'sticky_z_stack',        'label' => __( 'Z-Index Stack', '__x__' ) ),
      ),
    ),
  );

  $control_bar_sticky_trigger_selector = array(
    'key'     => 'bar_sticky_trigger_selector',
    'type'    => 'text',
    'label'   => __( 'Trigger Selector', '__x__' ),
    'options' => array( 'placeholder' => __( '#target-element (optional)', '__x__' ) ),
  );

  $control_bar_sticky_trigger_offset = array(
    'key'     => 'bar_sticky_trigger_offset',
    'type'    => 'unit-slider',
    'label'   => __( 'Trigger Offset', '__x__' ),
    'options' => array(
      'unit_mode'      => 'unitless',
      'fallback_value' => '0',
      'min'            => '0',
      'max'            => '150',
      'step'           => '1',
    ),
  );

  $control_bar_sticky_shrink = array(
    'key'        => 'bar_sticky_shrink',
    'type'       => 'unit-slider',
    'label'      => __( 'Shrink Amount', '__x__' ),
    'condition'  => array( 'bar_sticky' => true ),
    'options'    => array(
      'unit_mode'      => 'unitless',
      'fallback_value' => 1,
      'min'            => 0.33,
      'max'            => 1,
      'step'           => 0.001,
    ),
  );

  $control_bar_width = array(
    'key'       => 'bar_width',
    'type'      => 'unit-slider',
    'title'     => __( 'Width', '__x__' ),
    'condition' => $condition_bar_is_lr,
    'options'   => array(
      'available_units' => array( 'px', 'em', 'rem', 'vw', 'vh' ),
      'valid_keywords'  => array( 'calc' ),
      'fallback_value'  => '210px',
      'ranges'          => array(
        'px'  => array( 'min' => '30',  'max' => '300', 'step' => '1'    ),
        'em'  => array( 'min' => '1.5', 'max' => '15',  'step' => '0.01' ),
        'rem' => array( 'min' => '1.5', 'max' => '15',  'step' => '0.01' ),
        'vw'  => array( 'min' => '1',   'max' => '100', 'step' => '1'    ),
        'vh'  => array( 'min' => '1',   'max' => '100', 'step' => '1'    ),
      ),
    ),
  );

  $control_bar_height = array(
    'key'       => 'bar_height',
    'type'      => 'unit-slider',
    'title'     => __( 'Height', '__x__' ),
    'condition' => $condition_bar_is_tbf,
    'options'   => array(
      'available_units' => array( 'px', 'em', 'rem', 'vw', 'vh' ),
      'valid_keywords'  => array( 'calc', 'auto' ),
      'fallback_value'  => '100px',
      'ranges'          => array(
        'px'  => array( 'min' => '30',  'max' => '150', 'step' => '1'    ),
        'em'  => array( 'min' => '1.5', 'max' => '7.5', 'step' => '0.01' ),
        'rem' => array( 'min' => '1.5', 'max' => '7.5', 'step' => '0.01' ),
        'vw'  => array( 'min' => '1',   'max' => '100', 'step' => '1'    ),
        'vh'  => array( 'min' => '1',   'max' => '100', 'step' => '1'    ),
      ),
    ),
  );

  $control_bar_outer_spacing = array(
    'key'     => 'bar_outer_spacing',
    'type'    => 'unit-slider',
    'title'   => __( 'Outer Spacing', '__x__' ),
    'options' => array(
      'available_units' => array( 'px', 'em', 'rem' ),
      'valid_keywords'  => array( 'calc' ),
      'fallback_value'  => '35px',
      'ranges'          => array(
        'px'  => array( 'min' => '0', 'max' => '50',  'step' => '1'    ),
        'em'  => array( 'min' => '0', 'max' => '2.5', 'step' => '0.01' ),
        'rem' => array( 'min' => '0', 'max' => '2.5', 'step' => '0.01' ),
      ),
    ),
  );

  $control_bar_content_length = array(
    'key'       => 'bar_content_length',
    'type'      => 'unit-slider',
    'title'     => __( 'Content Length', '__x__' ),
    'options'   => array(
      'available_units' => array( '%' ),
      'valid_keywords'  => array( 'calc', 'auto' ),
      'fallback_value'  => '100%',
      'ranges'          => array( '%' => array( 'min' => '60', 'max' => '100', 'step' => '1' ) ),
    ),
  );

  $control_bar_content_max_length = array(
    'key'     => 'bar_content_max_length',
    'type'    => 'unit-slider',
    'title'   => __( 'Content Max Length', '__x__' ),
    'options' => array(
      'available_units' => array( 'px', 'em', 'rem' ),
      'valid_keywords'  => array( 'calc', 'none' ),
      'fallback_value'  => 'none',
      'ranges'          => array(
        'px'  => array( 'min' => '500', 'max' => '1200', 'step' => '1'   ),
        'em'  => array( 'min' => '25',  'max' => '60',   'step' => '0.1' ),
        'rem' => array( 'min' => '25',  'max' => '60',   'step' => '0.1' ),
      ),
    ),
  );

  $control_bar_margin_top = array(
    'key'     => 'bar_margin_top',
    'type'    => 'unit',
    'options' => $options_bar_offset,
  );

  $control_bar_margin_sides = array(
    'key'     => 'bar_margin_sides',
    'type'    => 'unit',
    'options' => $options_bar_offset,
  );

  $control_bar_margin_top_and_sides = array(
    'type'       => 'group',
    'title'      => __( 'Margin Top &amp; Sides', '__x__' ),
    'conditions' => $condition_bar_is_t_and_absolute,
    'controls'   => array(
      $control_bar_margin_top,
      $control_bar_margin_sides,
    ),
  );

  return cs_compose_controls(
    array(
      'controls' => array(
        array(
          'type'     => 'group',
          'title'    => __( 'Setup', '__x__' ),
          'group'    => 'bar:setup',
          'controls' => array(
            $control_bar_base_font_size_and_z_index,
            $control_bar_position_top,
            $control_bar_scroll,
            $control_bar_sticky,
            $control_bar_background,
          ),
        ),
        array(
          'type'       => 'group',
          'title'      => __( 'Sticky Setup', '__x__' ),
          'group'      => 'bar:design',
          'conditions' => $condition_bar_is_t_and_sticky,
          'controls'   => array(
            $control_bar_sticky_options,
            $control_bar_sticky_trigger_selector,
            $control_bar_sticky_trigger_offset,
            $control_bar_sticky_shrink,
          ),
        ),
      ),
      'control_nav' => array(
        'bar'        => __( 'Bar', '__x__' ),
        'bar:setup'  => __( 'Setup', '__x__' ),
        'bar:design' => __( 'Design', '__x__' ),
      ),
      'controls_std_design_setup' => array(
        array(
          'type'     => 'group',
          'title'    => __( 'Design Setup', '__x__' ),
          'controls' => array(
            $control_bar_base_font_size_and_z_index,
            $control_bar_width,
            $control_bar_height,
            $control_bar_outer_spacing,
            $control_bar_content_length,
            $control_bar_content_max_length,
            // $control_bar_margin_top_and_sides,
          ),
        ),
      ),
      'controls_std_design_colors' => array(
        array(
          'type'     => 'group',
          'title'    => __( 'Base Colors', '__x__' ),
          'controls' => array(
            array(
              'keys'      => array( 'value' => 'bar_box_shadow_color' ),
              'type'      => 'color',
              'label'     => __( 'Box<br>Shadow', '__x__' ),
              'condition' => array( 'key' => 'bar_box_shadow_dimensions', 'op' => 'NOT EMPTY' ),
              'options'   => array( 'label' => __( 'Select', '__x__' ) ),
            ),
            $control_bar_bg_color,
          ),
        ),
        cs_control( 'border', 'bar', array(
          'options'   => array( 'color_only' => true ),
          'conditions' => array(
            array( 'key' => 'bar_border_width', 'op' => 'NOT EMPTY' ),
            array( 'key' => 'bar_border_style', 'op' => '!=', 'value' => 'none' )
          ),
        ) )
      )
    ),
    cs_partial_controls( 'bg', array(
      'group'     => 'bar:design',
      'condition' => array( 'bar_bg_advanced' => true ),
    ) ),
    array(
      'controls' => array(
        array(
          'type'     => 'group',
          'title'    => __( 'Dimensions', '__x__' ),
          'group'    => 'bar:design',
          'controls' => array(
            $control_bar_width,
            $control_bar_height,
            $control_bar_outer_spacing,
            $control_bar_content_length,
            $control_bar_content_max_length,
            $control_bar_margin_top_and_sides,
          ),
        ),
        cs_control( 'flexbox', 'bar', array(
          'group'   => 'bar:design',
          'no_self' => true,
          'layout_pre' => 'row',
          'conditions' => array( $condition_bar_is_tbf ),
        ) ),
        cs_control( 'flexbox', 'bar', array(
          'group'   => 'bar:design',
          'no_self' => true,
          'layout_pre' => 'col',
          'conditions' => array( $condition_bar_is_lr ),
        ) ),
        cs_control( 'padding', 'bar', array( 'group' => 'bar:design', 'condition' => $condition_bar_height_is_auto ) ),
        cs_control( 'border', 'bar', array( 'group' => 'bar:design' ) ),
        cs_control( 'box-shadow', 'bar', array( 'group' => 'bar:design' ) )
      )
    ),
    cs_partial_controls( 'omega' )
  );

}



// Register Element
// =============================================================================

cs_register_element( 'bar', $data );
