<?php
defined('ABSPATH') || exit;

if (!class_exists( 'WGL_Widgets_Helper')){
	return;
}

if (!class_exists( 'WGL_Author_Widget')) {

	class WGL_Author_Widget extends WGL_Widgets_Helper {

		function create_widget() {
			$args = [
				'label' => esc_html__( 'WGL Blog Author', 'courto-core' ),
				'description' => esc_html__( 'WGL Widget', 'courto-core' ),
			];
			$args['fields'] = $this->form_fields();

			$this->create( $args );
		}
		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget($args, $instance) {
			$title = $instance['title'] ?? false;
			$author_name = $instance['name'] ?? false;
			$text = $instance['text'] ?? '';
			$author_image_url = $instance['image'] ?? '';
			$image_signature = $instance['signature'] ?? '';
			$bg_image = $instance['bg_image'] ?? '';

			// KSES Allowed HTML
			$allowed_html = [
                'a' => [
                    'id' => true, 'class' => true, 'style' => true,
                    'href' => true, 'title' => true,
                    'rel' => true, 'target' => true,
                ],
                'br' => ['id' => true, 'class' => true, 'style' => true],
                'em' => ['id' => true, 'class' => true, 'style' => true],
                'strong' => ['id' => true, 'class' => true, 'style' => true],
                'span' => ['id' => true, 'class' => true, 'style' => true],
                'p' => ['id' => true, 'class' => true, 'style' => true],
                'ul' => ['id' => true, 'class' => true, 'style' => true],
                'ol' => ['id' => true, 'class' => true, 'style' => true],
                'li' => ['id' => true, 'class' => true, 'style' => true],
			];

			$alt = $alt_s = '';

			// if no alt attribute is filled out then echo "Featured Image of article: Article Name"
			if ($author_image_url) {
				$attachment_id = attachment_url_to_postid( $author_image_url );
				if ( '' === get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) {
					$alt = the_title_attribute( [
						'before' => esc_html__( 'Featured author image: ', 'courto-core' ),
						'echo' => false
					] );
				} else {
					$alt = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
				}
			}

			// Get Image Signature
			if ($image_signature) {
				$attachment_id_s = attachment_url_to_postid( $image_signature );
				// if no alt attribute is filled out then echo "Featured Image of article: Article Name"
				if ( '' === get_post_meta( $attachment_id_s, '_wp_attachment_image_alt', true ) ) {
					$alt_s = the_title_attribute( [
						'before' => esc_html__( 'Featured author signature: ', 'courto-core' ),
						'echo' => false
					] );
				} else {
					$alt_s = trim( strip_tags( get_post_meta( $attachment_id_s, '_wp_attachment_image_alt', true ) ) );
				}
			}

			$socials = [];
			foreach (wgl_user_social_medias_arr() as $soc_name => $value) {
				$socials[$soc_name] = !empty($instance[$soc_name]) ? $instance[$soc_name] : '';
			}

			$wrapper_style = $bg_image ? ' style="background-image: url('.esc_url($bg_image).');"' : '';

			// Render ?>
			<div class="widget courto_widget widget_author"><?php

			if ($title) { ?>
				<div class="title-wrapper">
					<span class="title"><?php
						echo esc_html($title); ?>
					</span>
				</div><?php
			}

			?><div class="author-widget_wrapper"<?php echo $wrapper_style; ?>><?php

			if ($author_image_url) {
				?><img class="author-widget_img" src="<?php echo esc_url(aq_resize($author_image_url, '420', '420', true, true, true)); ?>" alt="<?php echo esc_attr($alt); ?>"><?php
			}

			?><div class="author-widget_content"><?php

			if ($image_signature) {
				?><div class="author-widget_img_sign-wrapper">
					<img class="author-widget_sign" src="<?php echo esc_url($image_signature); ?>" alt="<?php echo esc_attr($alt_s); ?>">
				</div><?php
			}

			if ($author_name) { ?>
				<h4 class="author-widget_title">
					<?php echo wp_kses($author_name, $allowed_html); ?>
				</h4><?php
			}

			if ($text) { ?>
				<p class="author-widget_text">
					<?php echo wp_kses($text, $allowed_html); ?>
				</p><?php
			}

			if (!empty($socials)) { ?>
				<div class="author-widget_social"><?php
				foreach ($socials as $name => $link) if ($link) {
					$icon_pref = 'fab fa-';
					if ($name == 'twitter') $icon_pref = 'fab fa-x-';
					if ($name == 'facebook-f') $name = 'facebook';
					echo '<a',
					' class="author-widget_social-link ', esc_attr($icon_pref), esc_attr($name), '"',
					' href="', esc_url($link), '"',
					'></a>';
				} ?>
				</div><?php
			} ?>
			</div>
			</div>
			</div><?php
		}

		/**
		 * Back-end widget form.
		 *
		 * @return array
		 * @see WP_Widget::form()
		 */
		public function form_fields(){
			$args = [
				[
					'name' => esc_html__('Title', 'courto-core'),
					'desc' => esc_html__('Enter the widget title.', 'courto-core'),
					'id' => 'title',
					'type' => 'text',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_attr'
				],
				[
					'name' => esc_html__('Author Name', 'courto-core'),
					'id' => 'name',
					'type' => 'text',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_attr'
				],
				[
					'name' => esc_html__('Text', 'courto-core'),
					'id' => 'text',
					'type' => 'textarea',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_textarea'
				],
				[
					'name' => esc_html__('Author Image', 'courto-core'),
					'id' => 'image',
					'type' => 'media_image',
					'class' => 'widefat wgl_extensions_media_url',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags'
				],
				[
					'name' => esc_html__('Background Image', 'courto-core'),
					'id' => 'bg_image',
					'type' => 'media_image',
					'class' => 'widefat wgl_extensions_media_url',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_url'
				],
				[
					'name' => esc_html__('Thumbnail', 'courto-core'),
					'id' => 'signature',
					'type' => 'media_image',
					'class' => 'widefat wgl_extensions_media_url',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_url'
				]
			]; // fields array

			foreach (wgl_user_social_medias_arr() as $soc_name => $value) {
				$args[] = [
					'name' => esc_html($value.' link'),
					'id' => $soc_name,
					'type' => 'text',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_attr'
				];
			}

			return $args;
		}
	}

	function wgl_author_widget_register() {
		register_widget('WGL_Author_Widget');
	}

	add_action('widgets_init', 'wgl_author_widget_register');
}