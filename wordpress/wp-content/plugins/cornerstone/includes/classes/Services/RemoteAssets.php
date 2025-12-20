<?php

namespace Themeco\Cornerstone\Services;
use Themeco\Cornerstone\Util\Filesystem;
use Themeco\Cornerstone\Util\Networking;

class RemoteAssets implements Service {

  protected $env;
  protected $filesystem;
  protected $cacheKeyPrefix;

  public function __construct(Env $env, Filesystem $filesystem) {
    $this->env = $env;
    $this->filesystem = $filesystem;
  }

  public function setup() {
    add_action( 'cs_purge_tmp', [$this, 'clearCache'] );
  }

  public function _fetch( $path, $query = [], $args = [] ) {
    $env = $this->env->envData();
    if ( ! isset( $env['templates'] ) || empty( $env['templates']['url'] ) ) {
      throw new \Exception('No base URL available');
    }

    $url = strpos($path, "http") === 0
      ? $path
      : $env['templates']['url'] . $path;

    if ( ! empty( $query ) ) {
      $url = add_query_arg( $query, $url );
    }

    Networking::set_curl_timeout_begin( 30 );

    $request = wp_remote_get( $url, $args );

    if ( is_wp_error( $request ) ) {
      throw new \Exception('Failed to fetch remote assets | ' . $request->get_error_message());
    }

    return $request;
  }

  public function fetchFile( $path, $options = [] ) {

    $filename = get_temp_dir() . 'cs-' . wp_generate_password( 12, false, false ) . '.zip';

    $this->_fetch( $path, isset($options['query']) ? $options['query'] : [], [
      'stream' => true,
      'filename' => $filename
    ]);

    return $filename;

  }

  public function proxyFile( $path ) {
    $this->filesystem->sendFile($this->fetchFile($path));
  }

  public function fetch( $path, $options = [] ) {
    return json_decode( wp_remote_retrieve_body( $this->_fetch( $path, isset($options['query']) ? $options['query'] : []) ), true );
  }

  public function fetchSafe( $path, $options = [] ) {
    try {
      return $this->fetch( $path, $options );
    } catch (\Exception $e) {
      if (defined('WP_DEBUG') && WP_DEBUG) {
        trigger_error($e->getMessage(), E_USER_WARNING);
      }
    }
    return [];
  }

  public function getCacheKey($group) {
    if (! isset($this->cacheKeyPrefix) ) {
      $env = $this->env->envData();
      $this->cacheKeyPrefix = 'cs_remote_asset_data_' . md5($env['product'] . $env['templates']['url']);
    }

    return $this->cacheKeyPrefix . '_' . $group;
  }

  public function clearCache() {
    delete_site_transient( $this->getCacheKey('manifest') );
    delete_site_transient( $this->getCacheKey('legacyManifest') );
  }

  public function cleanManifestItems($groups) {
    $result = [];

    $env = $this->env->envData();

    foreach ($groups as $name => $items) {
      $result[$name] = [];
      foreach ($items as $item) {
        if (isset( $item['minCsVersion'] ) && ! version_compare( $item['minCsVersion'], CS_VERSION, '<=') && CS_VERSION !== 'dev' ) {
          continue;
        }
        if ( isset($item['gate']) && $item['gate'] !== $env['product'] ) {
          continue;
        }
        $item['isRemote'] = true;
        $result[$name][] = $item;
      }
    }

    return $result;
  }

  public function defaultGroups() {
    return [
      'page'   =>  "Pages",
      'post'   =>  "Posts",
      'header' =>  "Headers",
      'footer' =>  "Footers",
      'blog'   =>  "Blogs",
      'shop'   =>  "Shops",
      'misc'   =>  "Misc",
    ];
  }

  public function getAppData() {

    $env = $this->env->envData();
    if ( ! isset( $env['templates'] ) || empty( $env['templates']['url'] ) ) {
      return [
        'groups' => $this->defaultGroups()
      ];
    }

    $key = $this->getCacheKey('manifest');
    $cached = get_site_transient( $key );

    if ($cached === false) {
      $manifest = $this->fetchSafe('/manifest');
      $manifest['templates'] = $this->cleanManifestItems( $manifest['templates'] );
      $cached = $manifest;
      set_site_transient( $key, $cached, HOUR_IN_SECONDS );
    }

    $cached['groups'] = array_merge( $this->defaultGroups(), empty($cached['groups'] ) ? [] : $cached['groups']);

    if ( empty( $cached['templates']['sites'] ) ) {
      $cached['templates']['sites'] = [];
    }
    $cached['templates']['sites'] = array_merge($cached['templates']['sites'], $this->getLegacySitesCached());

    $cached = apply_filters('cs_app_remote_assets', $cached);

    return $cached;

  }

  public function getLegacySites() {
    $env = $this->env->envData();
    if ( ! isset( $env['templates'] ) || empty( $env['templates']['legacyUrl'] ) ) {
      return [];
    }

    $request = wp_remote_get( $env['templates']['legacyUrl'] . '/index' );

    if ( is_wp_error( $request ) ) {
      return [];
    }

    try {
      $items = json_decode( wp_remote_retrieve_body( $request ), true );
      $items = array_filter( $items, function( $item ) {
        if (empty ($item['asset_type'])) return false;
        if ( ! in_array( 'site', $item['asset_type']) ) return false;
        if (empty ($item['status'])) return false;
        if ( $item['status'] !== 'publish' ) return false;
        return true;
      } );

      return array_values( array_map( function($item) use ($env) {
        $remapped = [
          'id' => 'legacy:' . $item['id'],
          'title' => $item['title'],
          'type' => 'site',
          'preview' => $item['thumbnail_url'],
          'demo_url' => $item['demo_url'],
          'isRemote' => true,
          'legacyInstallUrl' => $env['templates']['legacyUrl'] . '/asset/' . $item['id']
        ];

        if ( isset( $item['pro_only'] ) && $item['pro_only'] ) {
          $remapped['gate'] = 'pro';
        }
        return $remapped;
      }, $items ) );
    } catch( \Exception $e) {
      return [];
    }
  }

  public function getLegacySitesCached() {
    $key = $this->getCacheKey('legacyManifest');
    $cached = false;//get_site_transient( $key );

    if ($cached === false) {
      $cached = $this->getLegacySites();
      set_site_transient( $key, $cached, HOUR_IN_SECONDS );
    }

    return $cached;
  }

}
