<?php
namespace WGL_Elementor_Templates\Custom_Library\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-elementor-template-library-save-template">
	<div class="elementor-template-library-blank-icon">
		<#
			const templateIcon = typeof icon === 'undefined' ? '<i class="eicon-library-upload" aria-hidden="true"></i>' : icon;
			print( templateIcon );
		#>
		<span class="elementor-screen-only"><?php echo esc_html__( 'Save', 'wgl-extensions' ); ?></span>
	</div>
	<div class="elementor-template-library-blank-title">{{{ title }}}</div>
	<div class="elementor-template-library-blank-message">{{{ description }}}</div>
	<form id="elementor-template-library-save-template-form">
		<input type="hidden" name="post_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
		<# if ( typeof canSaveToCloud === 'undefined' || ! canSaveToCloud ) { #>
		<input id="elementor-template-library-save-template-name" name="title" placeholder="<?php echo esc_attr__( 'Enter Template Name', 'wgl-extensions' ); ?>" required>
		<button id="elementor-template-library-save-template-submit" class="elementor-button e-primary">
			<span class="elementor-state-icon">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</span>
			<?php echo esc_html__( 'Save', 'wgl-extensions' ); ?>
		</button>
		<div class="wgl-elementor-custom-library-template-syncer">
			<input type="checkbox" id="elementor-template-library-wgl-elementor-custom-library-syncer" name="wgl_elementor_custom_library_elementor_sync_on_save">
			<label for="elementor-template-library-wgl-elementor-custom-library-syncer"><?php echo esc_html__( 'Add to WGL Library', 'elementor' ); ?></label>
		</div>
		<# } else { #>
		<div class="cloud-library-form-inputs">
			<input id="elementor-template-library-save-template-name" name="title" placeholder="<?php echo esc_attr__( 'Give your template a name', 'wgl-extensions' ); ?>" required>
			<div class="source-selections">
				<div class="cloud-folder-selection-dropdown">
					<div class="cloud-folder-selection-dropdown-list"></div>
				</div>
				<div class="source-selections-input cloud">
					<input type="checkbox" id="cloud" name="cloud" value="cloud">
					<label for="cloud"> <?php echo esc_html__( 'Cloud Templates', 'wgl-extensions' ); ?></label> <span class="divider">/</span>  <div class="ellipsis-container"><i class="eicon-ellipsis-h"></i></div>
					<span class="selected-folder">
						<span class="selected-folder-text"></span>
						<i class="eicon-editor-close" aria-hidden="true"></i>
					</span>
					<# if ( elementor.config.library_connect.is_connected ) { #>
						<#
							const goLink = elementor.templates.hasCloudLibraryQuota()
								? 'https://go.elementor.com/go-pro-cloud-templates-save-to-100-usage-badge'
								: 'https://go.elementor.com/go-pro-cloud-templates-save-to-free-badge/';
						#>
					<span class="upgrade-badge">
						<a href="{{{ goLink }}}" target="_blank">
							<i class="eicon-upgrade-crown"></i><?php echo esc_html__( 'Upgrade', 'wgl-extensions' ); ?>
						</a>
					</span>
					<i class="eicon-info upgrade-tooltip" aria-hidden="true"></i>
					<# } else { #>
					<span class="connect-badge">
						<span class="connect-divider">|</span>
						<a id="elementor-template-library-connect__badge" href="{{{ elementorAppConfig?.[ 'cloud-library' ]?.library_connect_url }}}">
							<?php echo esc_html__( 'Connect', 'wgl-extensions' ); ?>
						</a>
					</span>
					<# } #>
				</div>
				<div class="source-selections-input local">
					<input type="checkbox" id="local" name="local" value="local">
					<label for="local"> <?php echo esc_html__( 'Site Templates', 'wgl-extensions' ); ?></label><br>
				</div>
				<input type="hidden" name="parentId" id="parentId" />
			</div>
			<div class="wgl-elementor-custom-library-template-syncer with-sources">
				<div class="wgl-elementor-custom-library-template-checkbox">
					<input type="checkbox" id="elementor-template-library-wgl-elementor-custom-library-syncer" name="wgl_elementor_custom_library_elementor_sync_on_save">
					<label for="elementor-template-library-wgl-elementor-custom-library-syncer"><?php echo esc_html__( 'Add to WGL Library', 'elementor' ); ?></label>
				</div>
				<p class="wgl-elementor-custom-library-template-description"><?php echo esc_html__( 'Note: WGL Library sync requires "Site Templates" to be checked as well.', 'wgl-extensions' ); ?></p>
			</div>
			<div class="quota-cta">
				<p>
					<?php echo esc_html__( 'You’ve saved 100% of the templates in your plan.', 'wgl-extensions' ); ?>
					<br>
					<?php printf(
					/* translators: %s is the "Upgrade now" link */
						esc_html__( 'To get more space %s', 'wgl-extensions' ),
						'<a href="https://go.elementor.com/go-pro-cloud-templates-save-to-100-usage-notice">' . esc_html__( 'Upgrade now', 'wgl-extensions' ) . '</a>'
					); ?>
				</p>
			</div>
			<button id="elementor-template-library-save-template-submit" class="elementor-button e-primary">
				<span class="elementor-state-icon">
					<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
				</span>
				{{{ saveBtnText }}}
			</button>
		</div>
		<# } #>
	</form>
	<div class="elementor-template-library-blank-footer">
		<?php echo esc_html__( 'Learn more about the', 'wgl-extensions' ); ?>
		<a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php echo esc_html__( 'Template Library', 'wgl-extensions' ); ?></a>
	</div>
</script>