<?php

// Versions
// =============================================================================

if ( ! defined( 'CS_VERSION' ) ) {
  define( 'CS_VERSION', '7.4.16' );
}

add_filter( '_cornerstone_app_env', function() {
  return [
    'product'     => 'cornerstone', // cornerstone-site-builder
    'title'       => csi18n('common.title.cornerstone'),
    'version'     => CS_VERSION,
    'siteBuilder' => true
  ];
});

add_filter( 'body_class', function( $output ) {
  $output[] = 'cornerstone-v' . str_replace( '.', '_', CS_VERSION );
  return $output;
}, 10000 );
