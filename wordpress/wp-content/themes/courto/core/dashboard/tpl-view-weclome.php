<?php
/**
 * Template Welcome
 *
 *
 * @package courto\core\dashboard
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

$theme = wp_get_theme();

$allowed_html = [
    'a' => [
        'href' => true,
        'target' => true,
    ],
];

?>
<div class="wgl-welcome_page">
    <div class="wgl-welcome_title">
        <h1><?php esc_html_e('Welcome to', 'courto');?>
            <?php echo esc_html(wp_get_theme()->get('Name')); ?>
        </h1>
    </div>
    <div class="wgl-version_theme">
        <?php esc_html_e('Version - ', 'courto');?>
        <?php echo esc_html(wp_get_theme()->get('Version')); ?>
    </div>
    <div class="wgl-welcome_subtitle">
            <?php
                echo sprintf(esc_html__('%s is already installed and ready to use! Let\'s build something impressive.', 'courto'), esc_html(wp_get_theme()->get('Name'))) ;
            ?>
    </div>

    <div class="wgl-welcome-step_wrap">
        <div class="wgl-welcome_sidebar left_sidebar">
            <div class="theme-screenshot">
                <img src="<?php echo esc_url(get_template_directory_uri() . "/screenshot.png"); ?>">

            </div>
        </div>
        <div class="wgl-welcome_content">
            <div class="step-subtitle">
                <?php
                    echo sprintf(esc_html__('Сomplete the steps and you will be able to use all functionalities of %s theme by WebGeniusLab', 'courto'), esc_html(wp_get_theme()->get('Name')));
                ?>
            </div>
        </div>
    </div>
</div>
