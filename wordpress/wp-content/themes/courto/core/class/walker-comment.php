<?php
defined('ABSPATH') || exit;

if (!class_exists('Courto_Walker_Comment')) {
    /**
     * Courto Theme Helper
     *
     *
     * @category Class
     * @package courto\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Courto_Walker_Comment extends Walker_Comment {
        public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
            $depth++;
            $GLOBALS['comment_depth'] = $depth;
            $GLOBALS['comment']       = $comment;
            if ( ! empty( $args['callback'] ) ) {
                ob_start();
                call_user_func( $args['callback'], $comment, $args, $depth );
                $output .= ob_get_clean();
                return;
            }
            if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
                ob_start();
                $this->ping( $comment, $depth, $args );
                $output .= ob_get_clean();
            } else {
                ob_start();
                $this->comment( $comment, $depth, $args );
                $output .= ob_get_clean();
            }
        }


        protected function ping( $comment, $depth, $args ) {
            $tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
        ?>
            <<?php echo WGL_Framework::render_html($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
                <div class="comment-body stand_comment">
                    <?php esc_html_e( 'Pingback:', 'courto' ); ?> <?php comment_author_link( $comment ); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'courto' ), '<span class="edit-link">', '</span>' ); ?>
                </div>
        <?php
        }

        protected function comment($comment, $depth, $args)
        {
            $max_depth_comment = $args['max_depth'] > 4 ? 4 : $args['max_depth'];

            $GLOBALS['comment'] = $comment; ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>" class="stand_comment">
                <div class="thiscommentbody">
                    <div class="commentava">
                        <?php echo get_avatar($comment->comment_author_email, 160); ?>
                    </div>
                    <div class="comment_info">
                        <div class="comment_author_says"><?php echo esc_html__('By ', 'courto'); printf('%s', get_comment_author_link()) ?></div>
                        <div class="meta-data">
                            <?php edit_comment_link('<span>('.esc_html__('Edit', 'courto').')</span>', '  ', '') ?>
                        </div>
                        <div class="meta-data">
                            <span><?php printf('%1$s', get_comment_date()) ?></span>
                        </div>
                    </div>
                    <div class="comment_content">
                        <?php if ($comment->comment_approved == '0') : ?>
                            <p><?php esc_html_e('Your comment is awaiting moderation.', 'courto'); ?></p>
                        <?php endif; ?>
                        <?php comment_text() ?>
                    </div>
                    <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'before' => '<span class="comment-reply-wrapper">', 'after' => '</span>', 'reply_text' => '<span class="button__text">' . esc_html__('REPLY', 'courto') . '</span><span class="read-more-icon"></span>' , 'max_depth' => $max_depth_comment))) ?>
                </div>
            </div>
            <?php

        }
    }
}