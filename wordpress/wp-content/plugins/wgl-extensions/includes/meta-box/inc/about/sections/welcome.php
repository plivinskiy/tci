<?php defined( 'ABSPATH' ) || die ?>

<h1>
	<?php
	$plugin_data = get_plugin_data( RWMB_DIR . 'meta-box.php', false, false );

	// Translators: %s - Plugin name.
	echo esc_html( sprintf( __( 'Welcome to %s', 'wgl-extensions' ), $plugin_data['Name'] ) );
	?>
</h1>
<div class="about-text"><?php esc_html_e( 'Meta Box is a free Gutenberg and GDPR-compatible WordPress custom fields plugin and framework that makes quick work of customizing a website with—you guessed it—meta boxes and custom fields in WordPress. Follow the instruction below to get started!', 'wgl-extensions' ); ?></div>
<a target="_blank" class="wp-badge" href="https://metabox.io/?utm_source=dashboard&utm_medium=link&utm_campaign=meta_box"><?php echo esc_html( $plugin_data['Name'] ); ?></a>
<p class="about-buttons">
	<a target="_blank" class="button" href="https://docs.metabox.io?utm_source=dashboard&utm_medium=link&utm_campaign=meta_box"><?php esc_html_e( 'Documentation', 'wgl-extensions' ); ?></a>
	<a target="_blank" class="button" href="https://support.metabox.io/?utm_source=dashboard&utm_medium=link&utm_campaign=meta_box"><?php esc_html_e( 'Support', 'wgl-extensions' ); ?></a>
	<a target="_blank" class="button" href="http://facebook.com/groups/metaboxusers"><?php esc_html_e( 'Facebook Group', 'wgl-extensions' ); ?></a>
	<a target="_blank" class="button" href="https://www.youtube.com/c/MetaBoxWP"><?php esc_html_e( 'Youtube Channel', 'wgl-extensions' ); ?></a>
</p>
