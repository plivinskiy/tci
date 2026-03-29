<?php
defined('ABSPATH') || exit;

if (!class_exists( 'WGL_Widgets_Helper')) {
	return;
}

if (!class_exists( 'WGL_Posts_Widget')) {

	class WGL_Posts_Widget extends WGL_Widgets_Helper {

		function create_widget() {
			$args = [
				'label' => esc_html__( 'WGL Posts', 'courto-core' ),
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
			extract($args);

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

			global $wpdb;
			global $post;
			$time_id = rand();

			$title = $instance['title'] ?? '';
			$num_posts = $instance['num_posts'] ?? 4;
			$categories = $instance['categories'] ?? '';
			$show_image = $instance['show_image'] ?? true;
			$show_related = !empty($instance['show_related']) ? true : false;
			$show_content = !empty($instance['show_content']) ? true : false;
			$show_date = !empty($instance['show_date']) ? true : false;

			/* Before widget (defined by themes). */
            if (isset($before_widget)){
	            echo WGL_Framework::render_html($before_widget);
            }

			/* Display the widget title if one was input (before and after defined by themes). */
			if ($title) {
				if (isset($before_title)) {
					echo WGL_Framework::render_html( $before_title );
				}

				echo esc_attr($title);

				if (isset($after_title)) {
					echo WGL_Framework::render_html( $after_title );
				}
			}

			if ($show_related) { // show related category
				$related_category = get_the_category($post->ID);
				if (isset($related_category[0]->cat_name)) {
					$related_category_id = get_cat_ID($related_category[0]->cat_name);
				} else {
					$related_category_id = '';
				}

				$recent_posts = new WP_Query([
					'showposts' => $num_posts,
					'cat' => $related_category_id,
					'post__not_in' => [$post->ID],
					'ignore_sticky_posts' => 1,
				]);
			} else {
				$recent_posts = new WP_Query([
					'showposts' => $num_posts,
					'cat' => $categories,
					'ignore_sticky_posts' => 1,
				]);
			}

			if ($recent_posts->have_posts()) { ?>
				<ul class="recent-posts-widget recent-widget-<?php echo esc_attr( $time_id ); ?>"><?php
				while ( $recent_posts->have_posts() ) {
					$recent_posts->the_post();

					$img_url = false;
					$text = '';
					if ( $show_image && has_post_thumbnail() ) {
						$img_url = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ) );
					}

					if ( $show_content ) {
						if ( has_excerpt() ) {
							$post_excerpt = get_the_excerpt();
						} else {
							$post_excerpt = get_the_content();
						}

						$without_tags = strip_tags( $post_excerpt );
						$text = WGL_Framework::modifier_character( $without_tags, 65, '...' );
					}

					// Render ?>
					<li class="post<?php echo ( $img_url ? ' has_image' : '' ); ?>">
					<a class="post__link" href="<?php echo esc_url( get_permalink() ); ?>"><?php
                        if ( $img_url ) { ?>
                            <div class="recent-posts-image_wrapper">
                            <img src="<?php echo esc_url( aq_resize( $img_url, '134', '134', true, true, true ) ); ?>"
                                 alt="<?php echo the_title_attribute( [ 'echo' => false ] ); ?>" >
                            </div><?php
                        } ?>
                        <div class="recent-posts-content_wrapper"><?php

							if ( $show_date ) { ?>
								<div class="meta-data">
									<span><?php
										echo get_the_time( get_option( 'date_format' ) ); ?>
									</span>
								</div><?php
							}?>

							<h6 class="post__title"><?php
							echo wp_kses( get_the_title(), $allowed_html ); ?>
							</h6><?php

                            if ( $text ) { ?>
                                <div class="recent-post-content"><?php
                                echo wp_kses( $text, $allowed_html ); ?>
                                </div><?php
                            } ?>
                        </div>
                    </a>
                    </li><?php
				} ?>
				</ul><?php

			} else {
				esc_html_e( 'No posts were found.', 'courto-core' );
			}

			/* After widget (defined by themes). */
			if (isset($after_widget)) {
				echo WGL_Framework::render_html( $after_widget );
			}

			// Restore original Query & Post Data
			wp_reset_query();
			wp_reset_postdata();
		}

		/**
		 * Back-end widget form.
		 *
		 * @return array
		 * @see WP_Widget::form()
		 */
		public function form_fields(){

            $categories = get_categories( 'hide_empty=0&depth=1&type=post' );
            $categories_render[] = [
	            'name'  => esc_html__('All categories' , 'courto-core'),
	            'value' => 'all'
            ];
            foreach( $categories as $category) {
	            $categories_render[] = [
		            'name'  => esc_attr($category->cat_name),
		            'value' => esc_attr($category->term_id)
	            ];
            }

			$args = [
				[
					'name' => esc_html__('Title', 'courto-core'),
					'id' => 'title',
					'type' => 'textarea',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_textarea'
				],
				[
					'name' => esc_html__('Number of posts', 'courto-core'),
					'id' => 'num_posts',
					'type' => 'number',
					'class' => 'tiny-text',
					'validate' => 'numeric',
					'std' => 3,
					'filter' => 'strip_tags|esc_attr'
				],
				[
					'name' => esc_html__('Filter by Category', 'courto-core'),
					'id' => 'categories',
					'type' => 'select',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_attr',
					'fields' => $categories_render,
				],
				[
					'name' => esc_html__('Show related category posts', 'courto-core'),
					'id' => 'show_related',
					'type' => 'checkbox',
					'class' => 'widefat wgl_extensions_checkbox',
					'validate' => 'alpha_dash',
					'std' => 0, // 0 or 1
					'filter' => 'strip_tags|esc_attr',
				],
				[
					'name' => esc_html__('Show thumbnail image', 'courto-core'),
					'id' => 'show_image',
					'type' => 'checkbox',
					'class' => 'widefat wgl_extensions_checkbox',
					'validate' => 'alpha_dash',
					'std' => 1, // 0 or 1
					'filter' => 'strip_tags|esc_attr',
				],
				[
					'name' => esc_html__('Show date', 'courto-core'),
					'id' => 'show_date',
					'type' => 'checkbox',
					'class' => 'widefat wgl_extensions_checkbox',
					'validate' => 'alpha_dash',
					'std' => 1, // 0 or 1
					'filter' => 'strip_tags|esc_attr',
				],
				[
					'name' => esc_html__('Show content', 'courto-core'),
					'id' => 'show_content',
					'type' => 'checkbox',
					'class' => 'widefat wgl_extensions_checkbox',
					'validate' => 'alpha_dash',
					'std' => 0, // 0 or 1
					'filter' => 'strip_tags|esc_attr',
				]
            ];

			return $args;
		}
	}

	function wgl_posts_widget_register() {
		register_widget('WGL_Posts_Widget');
	}

	add_action('widgets_init', 'wgl_posts_widget_register');
}
