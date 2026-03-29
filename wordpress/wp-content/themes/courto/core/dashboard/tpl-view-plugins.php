<?php
/**
 * Template Welcome
 *
 *
 * @package courto\core\dashboard
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

if (!class_exists('TGMPA_List_Table')) {
    return;
}

wp_clean_plugins_cache(false);
$install_plugins = wgl_installer_plugins();
$plugins_list    = $install_plugins->get_plugins();
?>
<div class="wgl-tgmpa_dashboard tgmpa wrap<?php echo !!$install_plugins->is_all_activated() ? ' wgl-all-active' : ''; ?><?php echo count( $install_plugins->get_required_plugins_to_activate() ) > 0 ? ' ' : ' wgl-all-required'; ?>">
	<div class="wgl-plugins">
		<div class="wgl-plugin-response"></div>
		<div class="wgl-plugins-title">
			<h3>
				<?php esc_html_e( 'Theme Plugins', 'courto' ); ?>
			</h3>
			<button class="wgl-button wgl-wizard-all-plugins">
                <?php esc_html_e( 'install & activate all plugins', 'courto' ); ?>
        	</button>
		</div>

		<ul class="wgl-plugins-list">
			<?php foreach ( $plugins_list as $slug => $plugin_data ) : ?>
				<?php
					$slug_image = strpos( $slug, 'wgl-' ) !== false || $slug === str_replace( '-child', '', wp_get_theme()->get( 'TextDomain' ) ) . '-core' ? 'wgl-extensions' : $slug;
				?>
				<li class="wgl-plugins-list_item item-wrapper">
					<div class="wgl-plugins-list_item_image">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/core/admin/img/dashboard/plugins/' . $slug_image . '.png' ); ?>" alt="<?php echo esc_html( $plugin_data['name'] );?>">
					</div>

					<div class="wgl-plugins-list_item_title">
						<h4 class="title">
							<?php echo esc_html( $plugin_data['name'] ); ?>
						</h4>
					</div>

					<div class="wgl-plugins-list_item-version">
						<?php
							if(!empty($plugin_data['version'])):
						?>
							<span class="version">
								<?php echo esc_html( $plugin_data['version'] ); ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="wgl-plugins-list_item_type type-<?php $plugin_data['required'] ? esc_attr_e( 'required', 'courto' ) : esc_attr_e( 'recommended', 'courto' )?>">
						<span class="type">
							<?php echo !!$plugin_data['required'] ? esc_html__( 'Required', 'courto' ) : esc_html__( 'Recommended', 'courto' ); ?>
						</span>
						<?php echo apply_filters('wgl/plugins_install/additional_info', '', $slug); ?>
					</div>
					<div class="wgl-plugins-list_item-btn_wrapper">
						<?php if ( is_multisite() && is_plugin_active_for_network( $plugin_data['file_path'] ) ) : ?>
							<span class="wgl-plugins-btn-text">
								<?php esc_html_e( 'Plugin activated globally.', 'courto' ); ?>
							</span>
						<?php elseif ( 'require_update' !== $plugin_data['status'] ) : ?>
							<a class="wgl-button wgl-ajax-installer-plugins wgl-<?php echo esc_html( $plugin_data['status'] ); ?>-now"
								href="<?php echo esc_url( $install_plugins->get_action_url( $slug, $plugin_data['status'] ) ); ?>"
								data-plugin="<?php echo esc_attr( $slug ); ?>"
								data-action="<?php echo esc_attr( $plugin_data['status'] ); ?>">
								<span><?php echo esc_html( $install_plugins->get_action_text( $plugin_data['status'] ) ); ?></span>
							</a>
						<?php else : ?>
							<span class="wgl-plugins-btn-text">
								<?php esc_html_e( 'Required update not available', 'courto' ); ?>
							</span>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<form class="wgl-plugins-list-form">
			<input type="hidden" class="wgl-installer-plugins" name="wgl_installer_plugins" value="<?php echo esc_attr( wp_json_encode( $plugins_list ) ); ?>" />
		</form>
	</div>
</div>