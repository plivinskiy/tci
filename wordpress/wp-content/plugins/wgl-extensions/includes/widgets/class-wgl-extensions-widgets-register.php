<?php
if (!class_exists('WGL_Widgets')) {
    /**
     * WGL_Widgets
     *
     *
     * @package wgl-extensions\includes\widgets
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Widgets
    {
        private $widgets = [];

        public function __construct()
        {
            do_action('wgl/widgets_require');
            add_action( 'widgets_init', [$this, 'init']);
        }

        public function add_widget($widget)
        {
            if ($widget->id_base) {
                return $this->widgets[$widget->id_base] = $widget;
            }

            return false;
        }

        public function init()
        {
            $this->action_register();

            foreach ($this->widgets as $widget) {
                $widget->register();
            }
        }

        public function action_register()
        {
            do_action('wgl/widgets_register');
        }
    }
}
