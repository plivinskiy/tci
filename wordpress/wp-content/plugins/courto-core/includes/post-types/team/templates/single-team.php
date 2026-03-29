<?php
/**
 * Single Page Template for Team CPT
 *
 * @package wgl-extensions\includes\post-types\team
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

use WGL_Extensions\Templates\WGL_Team;

$sb = WGL_Framework::get_sidebar_data();
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';

$attributes = [
    'single_page' => true,
    'posts_per_row' => '1',
    'thumbnail_dimensions' => ['width' => '1140', 'height' => '1140'],
    'socials_official_colors' => ['idle' => false, 'hover' => false],
    // Defaults
    'thumbnail_linked' => '',
    'hide_title' => '',
    'hide_content' => '',
    'hide_highlited_info' => '',
    'hide_socials' => '',
];

// Render
get_header();
$team_single_render_output = WGL_Framework::get_mb_option('team_single_render_output');
if('default' === $team_single_render_output || '' === $team_single_render_output){
    $team_single_render_output = WGL_Framework::get_option('team_single_render_output');
}
$sticky_class = '';

if('sidebar' === $team_single_render_output){
    $sticky_image = WGL_Framework::get_option('team_single_sticky_image');
    if (class_exists('RWMB_Loader')) {
        $mb_sticky_image = rwmb_meta('mb_team_single_sticky_image');
        if ('yes' === $mb_sticky_image) {
            $sticky_image = true;
        } elseif ('no' === $mb_sticky_image) {
            $sticky_image = false;
        }
    }
    if ($sticky_image) {
        wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js');
        $sticky_class = ' sticky-sidebar';
    }
}


echo '<div class="wgl-container', apply_filters('wgl/container/class', $container_class), '">';
echo '<div class="row', apply_filters('wgl/row/class', $row_class), '">';

    echo '<div id="main-content" class="wgl_col-', apply_filters('wgl/column/class', $column), '">';

        while (have_posts()) :
            the_post();

            ?><div class="row single_team_page">

                <?php echo '<div class="wgl_col-12">';
                    echo '<div class="team__member-wrap"><div class="row">';
                        ?>
                        <?php echo '<div class="wgl_col-6', $sticky_class, '">';
                            (new WGL_Team())->render_member_single_image($attributes);
                        ?></div>
                        <div class="wgl_col-6"><?php
                            (new WGL_Team())->render_member_single($attributes);
                            if('sidebar' === $team_single_render_output){
                                the_content( esc_html__('Read more!', 'courto-core') );
                            }
                        ?></div>

                    <?php echo '</div></div>';
                ?></div>
                <?php 
                if('sidebar' !== $team_single_render_output){?>
                    <div class="wgl_col-12"><?php
                        the_content( esc_html__('Read more!', 'verdaagro-core') );
                    ?></div>
                <?php 
                }
                ?>
            </div><?php
        endwhile;
        wp_reset_postdata();

    echo '</div>';

    $sb && WGL_Framework::render_sidebar($sb);

echo '</div>';
echo '</div>';

get_footer();
