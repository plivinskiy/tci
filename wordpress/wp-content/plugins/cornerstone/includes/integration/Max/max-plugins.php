<?php

/**
 * Update max plugins
 */
add_action("themeco_update_api_response", function($data) {
  if (empty($data['max']) || !apply_filters("cs_max_enabled", true)) {
    update_option("x_max_plugins", []);
    return;
  }

  update_option("x_max_plugins", $data['max']);
});

// Update theme packages cache with
// max plugins
add_filter("themeco_update_cache", function($cache) {

  $maxPlugins = apply_filters("cs_max_get_plugins", []);

  // loop and add to cache
  // which is keyed by plugin file
  foreach ($maxPlugins as $plugin) {
    $cache['plugins'][$plugin['plugin']] = $plugin;
  }

  return $cache;
}, 1000);

/**
 * Get plugins and get their status
 */
add_filter("cs_max_get_plugins", function() {
  $plugins = get_option("x_max_plugins", []);

  if (empty($plugins)) {
    return [];
  }

  $tgmpa = apply_filters("cs_tgma_get_instance", null);

  // Register and get status' of plugins
  foreach ($plugins as &$plugin) {
    $plugin = array_merge($plugin, $plugin['x-extension']);

    // Purchased
    if (!empty($plugin['purchased'])) {
      // Register with TGMA
      $tgmpa->register([
        'slug' => $plugin['slug'],
        'name' => $plugin['title'],
        'file_path' => $plugin['plugin'],
        'source' => $plugin['package'],
        'version' => $plugin['new_version']
      ]);

      // TGM file path detection doesn't always work so we need to set the known path here
      $tgmpa->plugins[ $plugin['slug'] ]['file_path'] = $plugin['plugin'];

      $plugin['installed'] = $tgmpa->is_plugin_installed( $plugin['slug'] );
      $plugin['activated'] = $tgmpa->is_plugin_active( $plugin['slug'] );
    } else {
      $plugin['installed'] = false;
      $plugin['activated'] = false;
    }
  }

  return $plugins;
});


/**
 * This adds max plugins to TGMA registry
 */
add_action( 'wp_ajax_cs_extensions_install', function() {
  // We just need the registry, and not the actual plugins
  apply_filters("cs_max_get_plugins", []);
}, 0);

add_action( 'tgmpa_register', function() {
  apply_filters("cs_max_get_plugins", []);
}, 0);
