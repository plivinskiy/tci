<?php
if (!class_exists('WGL_Installer_Plugins')) {
	/**
	 * Intaller Plugins
	 *
	 *
	 * @package courto\core\class
	 * @author WebGeniusLab <webgeniuslab@gmail.com>
	 * @version 1.0.0
	 * @since 1.0.0
	 */
	class WGL_Installer_Plugins {

		protected static $instance;

		public static function instance()
		{
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct()
		{
			// do nothing.
		}

		public function construct() {
			add_filter( 'tgmpa_load', [ $this, 'tgmpa_load' ],10, 1);
			add_action( 'wp_ajax_wgl_deactivate_plugin', [ $this, 'deactivate_plugin' ]);
			add_action( 'wp_ajax_nopriv_wgl_deactivate_plugin', [ $this, 'deactivate_plugin' ]);
			add_action( 'wp_ajax_wgl_check_plugins', [ $this, 'verify_plugin' ]);
			add_action( 'wp_ajax_nopriv_wgl_check_plugins', [ $this, 'verify_plugin' ]);
		}

		public function deactivate_plugin() {
			$plugins = $this->get_plugins();

			if ( ! $plugins ) {
				wp_send_json(
					[
						'message' => esc_html__( 'Plugins list is empty.', 'courto' ),
						'status'  => 'error',
					]
				);
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json(
					[
						'message' => esc_html__( 'You not have access.', 'courto' ),
						'status'  => 'error',
					]
				);
			}

			if ( is_multisite() && $this->plugin_activation_for_network_check( $plugins[ $_POST['wgl_plugin'] ]['file_path'] ) ) {
				wp_send_json(
					[
						'message' => esc_html__( 'You cannot deactivate the plugin on a multisite.', 'courto' ),
						'status'  => 'error',
					]
				);
			}

			if ( isset( $_POST['wgl_plugin'] ) && $this->plugin_activation_check( $plugins[ $_POST['wgl_plugin'] ]['file_path'] ) ) {
				deactivate_plugins( $plugins[ $_POST['wgl_plugin'] ]['file_path'] );
			}

			wp_send_json(
				[
					'data'   => $plugins[ $_POST['wgl_plugin'] ]['status'],
					'status' => 'success',
				]
			);
		}

		public function plugin_activation_check( $plugin ) {
			return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || $this->plugin_activation_for_network_check( $plugin );
		}

		function plugin_activation_for_network_check( $plugin ) {
			if ( ! is_multisite() ) {
				return false;
			}

			$plugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $plugins[ $plugin ] ) ) {
				return true;
			}

			return false;
		}

		public function verify_plugin() {
			$plugins = $this->get_plugins();

			if ( ! $plugins ) {
				wp_send_json(
					[
						'message' => esc_html__( 'Plugins list is empty.', 'courto' ),
						'status'  => 'error',
					]
				);
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json(
					[
						'message' => esc_html__( 'You not have access.', 'courto' ),
						'status'  => 'error',
					]
				);
			}

			wp_send_json(
				[
					'data'   => [
						'status'           => $plugins[ $_POST['wgl_plugin'] ]['status'],
						'version'          => $plugins[ $_POST['wgl_plugin'] ]['version'],
						'required_plugins' => count( $this->get_required_plugins_to_activate() ) > 0 ? 'has_required' : 'no',
						'is_all_activated' => $this->is_all_activated() ? 'yes' : 'no',
					],
					'status' => 'success',
				]
			);
		}

		public function tgmpa_load() {
			return is_admin() || current_user_can( 'install_themes' );
		}

		public function get_plugins() {
			$tgmpa             = call_user_func( [ get_class( $GLOBALS['tgmpa'] ), 'get_instance' ] );
			$tgmpa_plugins     = $tgmpa->plugins;
			$installed_plugins = get_plugins();

			$plugins = [];

			foreach ( $tgmpa_plugins as $slug => $plugin ) {
				$plugins[ $slug ]                   = $plugin;
				$plugins[ $slug ]['activate_url']   = $this->get_action_url( $slug, 'activate' );
				$plugins[ $slug ]['update_url']     = $this->get_action_url( $slug, 'update' );
				$plugins[ $slug ]['deactivate_url'] = '';

				if ( isset( $installed_plugins[ $plugin['file_path'] ]['Version'] ) ) {
					$plugins[ $slug ]['version'] = $installed_plugins[ $plugin['file_path'] ]['Version'];
				}

				$status = 'deactivate';

				if ( ! $tgmpa->is_plugin_installed( $slug ) ) {
					$status = 'install';
				} elseif ( $tgmpa->does_plugin_have_update( $slug ) ) {
					$status = 'update';
				} elseif ( $tgmpa->can_plugin_activate( $slug ) ) {
					$status = 'activate';
				} elseif ( $tgmpa->does_plugin_require_update( $slug ) ) {
					$status = 'require_update';
				}

				$plugins[ $slug ]['status'] = $status;
			}

			// Move Dependency plugins to the top if it exists
			$plugins = $this->reinit_position_plugins($plugins, 'woocommerce');
			$plugins = $this->reinit_position_plugins($plugins, 'elementor');
			$plugins = $this->reinit_position_plugins($plugins, 'buddypress');
			$plugins = $this->reinit_position_plugins($plugins, str_replace( '-child', '', wp_get_theme()->get( 'TextDomain' ) ) . '-core');
			$plugins = $this->reinit_position_plugins($plugins, 'wgl-extensions');
			$plugins = $this->reinit_position_plugins_bottom($plugins, 'woocommerce-ajax-filters');

			return $plugins;
		}

		public function reinit_position_plugins($plugins, $name){
			if (isset($plugins[$name])) {
				$plugin = $plugins[$name];
				unset($plugins[$name]);
				$plugins = array_merge([$name => $plugin], $plugins);
			}

			return $plugins;
		}

		public function reinit_position_plugins_bottom($plugins, $name){
			if (isset($plugins[$name])) {
				$plugin = $plugins[$name];
				unset($plugins[$name]);
				$plugins[$name] = $plugin;
			}

			return $plugins;
		}

		public function get_required_plugins_to_activate() {
			$plugins = $this->get_plugins();
			$tgmpa   = call_user_func( [get_class( $GLOBALS['tgmpa'] ), 'get_instance' ] );
			$output  = [];

			foreach ( $plugins as $slug => $plugin ) {
				if ( ! $tgmpa->tgmpa_plugin_active( $slug ) && $plugin['required'] ) {
					$output[] = $plugin;
				}
			}

			return $output;
		}

		public function is_all_activated() {
			$plugins = $this->get_plugins();
			$tgmpa   = call_user_func( [get_class( $GLOBALS['tgmpa'] ), 'get_instance']);
			$output  = [];

			foreach ( $plugins as $slug => $plugin ) {
				if ( ! $tgmpa->tgmpa_plugin_active( $slug ) && $tgmpa->can_plugin_activate( $slug ) ) {
					$output[] = $plugin;
				}
			}

			return count( $output ) === 0;
		}

		public function get_action_url( $slug, $status ) {
			$query_args = [
				'plugin'           => rawurlencode( $slug ),
				'tgmpa-' . $status => $status . '-plugin',
			];

			$url = add_query_arg( $query_args, admin_url( 'themes.php?page=tgmpa-install-plugins' ) );

			return wp_nonce_url( $url, 'tgmpa-' . $status, 'tgmpa-nonce' );
		}

		public function get_action_text( $status ) {
			switch ( $status ) {
				case 'install':
					return esc_html__( 'Install', 'courto' );
				case 'update':
					return esc_html__( 'Update', 'courto' );
				case 'activate':
					return esc_html__( 'Activate', 'courto' );
				default:
					return esc_html__( 'Deactivate', 'courto' );
			}
		}
	}

	function wgl_installer_plugins()
	{
		return WGL_Installer_Plugins::instance();
	}

	wgl_installer_plugins()->construct();
}
?>