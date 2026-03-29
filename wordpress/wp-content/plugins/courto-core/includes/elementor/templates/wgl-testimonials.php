<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/templates/wgl-testimonials.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit;
use Elementor\{
    Control_Media,
    Group_Control_Image_Size,
};
use WGL_Extensions\{Includes\WGL_Carousel_Settings, Includes\WGL_Cursor, Includes\WGL_Elementor_Helper};

if (!class_exists('WGL_Testimonials')) {
    /**
     * WGL Elementor Testimonials Template
     *
     *
     * @package courto-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Testimonials
    {
        private static $instance;
        private $attributes;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function render($self, $attributes)
        {
            $this->attributes = $attributes;
            extract($this->attributes);

            switch ($posts_per_row) {
                case '1':
                    $col = 12;
                    break;
                case '2':
                    $col = 6;
                    break;
                case '3':
                    $col = 4;
                    break;
                case '4':
                    $col = 3;
                    break;
                case '5':
                    $col = '1/5';
                    break;
            }

            // Wrapper attributes
            $self->add_render_attribute('wrapper', 'class', [
                'wgl-testimonials',
                'type-' . $layout,
            ]);
            if ($hover_animation) {
                $self->add_render_attribute('wrapper', 'class', 'hover_animation');
            }

            if ( 'left_block' === $layout && $image_responsive ) {
                $styles = '@media all and (max-width: '.esc_attr($image_responsive).'px) {';
                    $styles .= '.elementor-widget-wgl-testimonials.elementor-element-' . esc_attr( $self->get_id() ) . ' .item__image_wrapper { display: flex; flex-direction: column; }';
                    $styles .= '.elementor-widget-wgl-testimonials.elementor-element-' . esc_attr( $self->get_id() ) . ' .item__image_wrapper .item__image { display: none !important; }';
                    $styles .= '.elementor-widget-wgl-testimonials.elementor-element-' . esc_attr( $self->get_id() ) . ' .item__image_wrapper .item__author { position: relative; width: 100% !important; aspect-ratio: 1; height: auto !important; }';
                $styles .= '}';
                WGL_Elementor_Helper::enqueue_css( $styles, false );
            }

            // Image styles
            $image_size = $image_size['size'] ?? '';
            $image_url_size = (int)$image_size ? $image_size * 2 : 254;

            // Build structure
            $items_html = $image_global = '';

            if(!$use_carousel){
                $items_html .= '<div class="row">';
            }

            // Image
            if (!empty($image) && is_array($image) && !empty($image['url'])) {
                $self->add_render_attribute('image', 'src', $image['url']);
                $self->add_render_attribute('image', 'alt', Control_Media::get_image_alt($image));
                $self->add_render_attribute('image', 'title', Control_Media::get_image_title($image));
                $image_global = '<div class="item__image">' . Group_Control_Image_Size::get_attachment_image_html($attributes, 'full', 'image') . '</div>';
            }

            foreach ($items as $index=>$item) {
                $image_html = '';
                if (!empty($item['image_items']) && is_array($item['image_items']) && !empty($item['image_items']['url'])) {
                    $image_html = '<div class="item__image">'.'<img src="'.esc_url($item['image_items']['url']).'" alt="'.Control_Media::get_image_alt($item['image_items']).'">'.'</div>';
                } elseif ($image_global){
                    $image_html = $image_global;
                }


                // Fields validation
                $thumbnail = $item['thumbnail'] ?? '';
                $quote = $item['quote'] ?? '';
                $title = $item['title'] ?? '';
                $subtitle = $item['subtitle'] ?? '';
                $author_name = $item['author_name'] ?? '';
                $author_position = $item['author_position'] ?? '';
                $link_author = $item['link_author'] ?? '';
                $rating = $item['quote_rating'] ?? '';
                $name_html = $title_html = $subtitle_html = $icon_html__block = $icon_html__title = '';
                $cursor_data = '';

                $has_link = !empty($link_author['url']);

                if ($has_link) {
                    $self->add_link_attributes('link-author'.$index, $link_author);
                }

                $rating && $rating = $this->get_rating_html( $rating );

                if (isset($item['cursor_tooltip']) && '' != $item['cursor_tooltip']) {
                    add_filter( 'wgl/courto_module_cursor', function () { return true; });
                    $cursor = new WGL_Cursor;
                    $cursor_data = $cursor->build($self, array_merge($this->attributes, $item), $item['_id']);
                }

                $self->add_render_attribute('item_wrapper'.$index, 'class', [
                    'testimonials__wrapper',
                    !$use_carousel ? ' wgl_col-' . $col : ' swiper-slide',
                    isset($item['cursor_tooltip']) && '' != $item['cursor_tooltip'] ? 'wgl-cursor-text' : ''
                ]);

                if (!!$author_name) {
                    $name_html = '<' . esc_attr($name_tag) . ' class="author__name">'
                        . ($has_link ? '<a ' . $self->get_render_attribute_string('link-author' . $index) . '>' : '')
                        . esc_html($author_name)
                        . ($has_link ? '</a>' : '')
                        . '</' . esc_attr($name_tag) . '>';
                }

                if (isset($title_icon_enabled) && !!$title_icon_enabled){
                    if ('block' === $title_icon_display){
                        $icon_html__block = '<span class="item__icon"></span>';
                    } else if ('title' === $title_icon_display){
                        $icon_html__title = '<span class="item__icon"></span>';
                    }
                }

                if (!!$title){
                    $title_html = '<' . esc_attr($title_tag) . ' class="item__title">';
                        $title_html .= $icon_html__title . '<span>'.wp_kses($title, self::get_kses_allowed_html()).'</span>';
                    $title_html .= '</' . esc_attr($title_tag ?? '') . '>';
                }

                if (!!$subtitle){
                    $subtitle_html = '<span class="item__subtitle">';
                        $subtitle_html .= '<span>'.wp_kses($subtitle, self::get_kses_allowed_html()).'</span>';
                    $subtitle_html .= '</span>';
                }

                $quote_html = !empty($quote) ? '<' . esc_attr($quote_tag) . ' class="item__quote">' . wp_kses($quote, self::get_kses_allowed_html()) . '</' . esc_attr($quote_tag) . '>' : '';

                $position_html = $author_position ? '<div class="author__position_wrapper"><' . esc_attr($position_tag) . ' class="author__position">' . esc_html($author_position) . '</' . esc_attr($position_tag) . '></div>' : '';

                $thumbnail_html = '';
                $testimonials_thumbnail_src = aq_resize($thumbnail['url'], $image_url_size, $image_url_size, true, true, true);
                if (!empty($testimonials_thumbnail_src)) {
                    $thumbnail_html = '<div class="item__thumbnail"><div class="author__thumbnail">'
                        . ($has_link ? '<a ' . $self->get_render_attribute_string('link-author'.$index) . '>' : '')
                        . '<img src="' . esc_url($testimonials_thumbnail_src) . '" alt="' . esc_attr($author_name) . ' photo">'
                        . ($has_link ? '</a>' : '')
                        . '</div></div>';
                }else{
                    $self->add_render_attribute('item_wrapper'.$index, 'class', 'no_image');
                }

                $items_html .= '<div ' . $self->get_render_attribute_string('item_wrapper'.$index) . ' ' . $cursor_data .'>';

                switch ($layout) {
                    case 'top_block':
                        $items_html .= '<div class="testimonial__item">'
                            . '<div class="testimonial__item-inner">'
                                . $icon_html__block
                                . $thumbnail_html
                                . '<div class="item__content">'
                                    . $title_html
                                    . $quote_html
                                    . $rating
                                . '</div>'
                                . '<div class="item__author">'
                                    . '<div class="author__meta">'
                                        . $subtitle_html
                                        . $name_html
                                        . $position_html
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>';
                        break;

                    case 'bottom_block':
                        $items_html .= '<div class="testimonial__item">'
                            . '<div class="testimonial__item-inner">'
                                . $icon_html__block
                                . '<div class="item__content">'
                                    . $title_html
                                    . $quote_html
                                    . $rating
                                . '</div>'
                                . '<div class="item__author">'
                                    . '<div class="author__meta">'
                                        . $subtitle_html
                                        . $name_html
                                        . $position_html
                                    . '</div>'
                                    . $thumbnail_html
                                . '</div>'
                            . '</div>'
                        . '</div>';
                        break;

                    case 'bottom_inline':
                        $items_html .= '<div class="testimonial__item">'
                            . '<div class="testimonial__item-inner">'
                                . $icon_html__block
                                . '<div class="item__content">'
                                    . $title_html
                                    . $quote_html
                                    . $rating
                                . '</div>'
                                . '<div class="item__author">'
                                    . $thumbnail_html
                                    . '<div class="author__meta">'
                                        . $subtitle_html
                                        . $name_html
                                        . $position_html
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                        . '</div>';
                        break;

                    case 'top_inline':
                        $items_html .= '<div class="testimonial__item">'
                            . '<div class="testimonial__item-inner">'
                                . $icon_html__block
                                . '<div class="item__author">'
                                    . $thumbnail_html
                                    . '<div class="author__meta">'
                                        . $subtitle_html
                                        . $name_html
                                        . $position_html
                                    . '</div>'
                                . '</div>'
                                . '<div class="item__content">'
                                    . $title_html
                                    . $quote_html
                                    . $rating
                                . '</div>'
                            . '</div>'
                        . '</div>';
                        break;

                    case 'left_inline':
                        $items_html .= $thumbnail_html
                            . '<div class="testimonial__item">'
                                . '<div class="testimonial__item-inner">'
                                    . '<div class="item__content">'
                                        . $icon_html__block
                                        . $title_html
                                        . $quote_html
                                        . $rating
                                    . '</div>'
                                    . '<div class="item__author">'
                                        . '<div class="author__meta">'
                                            . $subtitle_html
                                            . $name_html
                                            . $position_html
                                        . '</div>'
                                    . '</div>'
                                . '</div>'
                            . '</div>';
                        break;

                    case 'left_block':
                        $items_html .=
                            '<div class="item__image_wrapper">'
                                . $image_html
                                . '<div class="item__author">'
                                    . $thumbnail_html
                                    . '<div class="author__meta">'
                                        . $subtitle_html
                                        . $name_html
                                        . $position_html
                                    . '</div>'
                                . '</div>'
                            . '</div>'
                            . '<div class="testimonial__item">'
                                . '<div class="testimonial__item-inner">'
                                    . '<div class="item__content">'
                                        . $icon_html__block
                                        . $title_html
                                        . $quote_html
                                        . $rating
                                    . '</div>'
                                . '</div>'
                            . '</div>';
                        break;
                }
                $items_html .= '</div>';
            }

            if(!$use_carousel){
                $items_html .= '</div>';
            }

            echo '<div  ', $self->get_render_attribute_string('wrapper'), '>',
                (!$use_carousel ? $items_html : $this->apply_carousel_settings($items_html)),
            '</div>';
        }

        protected function apply_carousel_settings($testimonials_html)
        {
            $this->attributes['slides_per_row'] = $this->attributes['posts_per_row'];

            return WGL_Carousel_Settings::init($this->attributes, $testimonials_html);
        }

        protected function get_rating_html(Int $value)
        {
            $max_rating = 5;
            $width = $value / $max_rating * 100;

            return '<div class="item__rating" role="img" aria-label="' . esc_attr__('Rated', 'courto-core') . ' ' . $value . ' out of ' . $max_rating . '">'
                . '<span style="width: ' . $width . '%"></span>'
                . '</div>';
        }

        protected static function get_kses_allowed_html()
        {
            return [
                'a' => [
                    'id' => true, 'class' => true, 'style' => true,
                    'href' => true, 'title' => true,
                    'rel' => true, 'target' => true
                ],
                'br' => ['id' => true, 'class' => true, 'style' => true],
                'em' => ['id' => true, 'class' => true, 'style' => true],
                'strong' => ['id' => true, 'class' => true, 'style' => true],
                'span' => ['id' => true, 'class' => true, 'style' => true],
                'p' => ['id' => true, 'class' => true, 'style' => true],
                'ul' => ['id' => true, 'class' => true, 'style' => true],
                'ol' => ['id' => true, 'class' => true, 'style' => true],
            ];
        }
    }
}
