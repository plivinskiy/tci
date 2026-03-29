<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/templates/wgl-countdown.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

/**
 * WGL Elementor Countdown Template
 *
 *
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGLCountDown
{
	private static $instance ;

	public static function get_instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function render($self, $atts)
	{
		extract($atts);

		wp_enqueue_script(
			'jquery-countdown',
			get_template_directory_uri() . '/js/jquery.countdown.min.js',
			[],
			false,
			false
		);

		// Module unique id
		$cd_attr = ' id=' . uniqid("countdown_");

		$cd_class = isset($show_separating) && $show_separating ? ' has-dots' : '';

		$f = ! $hide_day ? 'd' : '';
		$f .= ! $hide_hours ? 'H' : '';
		$f .= ! $hide_minutes ? 'M' : '';
		$f .= ! $hide_seconds ? 'S' : '';

		// Countdown data attribute http://keith-wood.name/countdown.html
		$data['format'] = !empty($f) ? esc_attr($f) : '';

        if (!$demo){
            $data['year'] = esc_attr($countdown_year);
            $data['month'] = esc_attr($countdown_month);
            $data['day'] = esc_attr($countdown_day);
            $data['hours'] = esc_attr($countdown_hours);
            $data['minutes'] = esc_attr($countdown_min);
        }else{
            $data['year'] = (int)date('Y') + 1;
            $data['month'] = 1;
            $data['day'] = 1;
            $data['hours'] = 0;
            $data['minutes'] = 0;
        }

		$data['labels'][]  = esc_html__('Years', 'courto-core');
		$data['labels'][]  = esc_html__('Months', 'courto-core');
		$data['labels'][]  = esc_html__('Weeks', 'courto-core');
		$data['labels'][]  = esc_html__('Days', 'courto-core');
		$data['labels'][]  = esc_html__('Hours', 'courto-core');
		$data['labels'][]  = esc_html__('Minutes', 'courto-core');
		$data['labels'][]  = esc_html__('Seconds', 'courto-core');
		$data['labels1'][] = esc_html__('Year', 'courto-core');
		$data['labels1'][] = esc_html__('Month', 'courto-core');
		$data['labels1'][] = esc_html__('Week', 'courto-core');
		$data['labels1'][] = esc_html__('Day', 'courto-core');
		$data['labels1'][] = esc_html__('Hour', 'courto-core');
		$data['labels1'][] = esc_html__('Minute', 'courto-core');
		$data['labels1'][] = esc_html__('Second', 'courto-core');

		$attrs = json_encode($data, JSON_UNESCAPED_UNICODE);
		$output = '<div'.$cd_attr.' class="wgl-countdown'.esc_attr($cd_class).'" data-atts="'.esc_js($attrs).'"></div>';
		echo \WGL_Framework::render_html($output);

	}

}