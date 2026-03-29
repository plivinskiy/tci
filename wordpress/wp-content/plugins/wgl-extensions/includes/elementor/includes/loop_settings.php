<?php
namespace WGL_Extensions\Includes;

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;

if (!class_exists('WGL_Loop_Settings')) {
    /**
     * WGL Elementor Loop Settings
     *
     *
     * @package wgl-extensions\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Loop_Settings
    {
        private static $instance;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function buildQuery($query)
        {
            return (new WGL_Query_Builder($query))->build();
        }

        /**
         * Cache Query
         */
        public static function cache_query($args = [])
        {
            $cache_key = http_build_query($args);
            $custom_query = wp_cache_get($cache_key, 'wgl_theme_cache');
            if(class_exists( 'Jetpack' )){
                $custom_query = false;
            }
            
            if (false === $custom_query) {
                $custom_query = new \WP_Query($args);
                if (!is_wp_error($custom_query) && $custom_query->have_posts() && !class_exists( 'Jetpack' )) {
                    wp_cache_set($cache_key, $custom_query, 'wgl_theme_cache');
                }
            }

            return $custom_query;
        }

        public static function add_controls($self, $atts = [])
        {
            if (!$self) {
                // Bailout.
                return;
            }

            $self->start_controls_section(
                'query_section',
                [
                    'label' => esc_html__('Query', 'wgl-extensions'),
                    'tab' => Controls_Manager::TAB_SETTINGS
                ]
            );

            $self->add_control(
                'number_of_posts',
                [
                    'label' => esc_html__('Posts amount', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'min' => 1,
                    'default' => 12,
                ]
            );

            if(!isset($atts['event_order']) && !isset($atts['give_campaigns_order'])){
                $self->add_control(
                    'order_by',
                    [
                        'label' => esc_html__('Order by', 'wgl-extensions'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            'date' => esc_html__('Date', 'wgl-extensions'),
                            'title' => esc_html__('Title', 'wgl-extensions'),
                            'author' => esc_html__('Author', 'wgl-extensions'),
                            'modified' => esc_html__('Modified', 'wgl-extensions'),
                            'rand' => esc_html__('Random', 'wgl-extensions'),
                            'comment_count' => esc_html__('Comments', 'wgl-extensions'),
                            'menu_order' => esc_html__('Menu Order', 'wgl-extensions'),
                        ],
                        'default' => 'date',
                    ]
                );                
            }

            if(isset($atts['give_campaigns_order'])){
                $self->add_control(
                    'give_campaigns_order_by',
                    array(
                        'label'       => esc_html__('Order by', 'wgl-extensions'),
                        'type'        => Controls_Manager::SELECT,
                        'default'     => 'date',
                        'options'     => array(
                            'date'      => esc_html__('Date','wgl-extensions'),
                            'title'     => esc_html__('Title','wgl-extensions'),
                        ),
                    )
                );
            }

            if(isset($atts['event_order'])){
                $self->add_control(
                    'events_order_by',
                    array(
                        'label'       => esc_html__('Events Order by', 'wgl-extensions'),
                        'type'        => Controls_Manager::SELECT,
                        'default'     => 'date',
                        'options'     => array(
                            '_event_start' => esc_html__('Date','wgl-extensions'),
                            'title' => esc_html__('Title','wgl-extensions')
                        ),
                    )
                );
                $self->add_control(
                    'scope',
                    [
                        'label' => esc_html__( 'Scope:', 'wgl-extensions' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => '',
                        'options' => array(
                            '' => esc_html__('All events','wgl-extensions'),
                            'future' => esc_html__('Future events','wgl-extensions'),
                            'past' => esc_html__('Past events','wgl-extensions'),
                            'today' => esc_html__('Today\'s events','wgl-extensions'),
                            'tomorrow' => esc_html__('Tomorrow\'s events','wgl-extensions'),
                            'month' => esc_html__('Events this month','wgl-extensions'),
                            'next-month' => esc_html__('Events next month','wgl-extensions'),
                            '1-months'  => esc_html__('Events current and next month','wgl-extensions'),
                            '2-months'  => esc_html__('Events within 2 months','wgl-extensions'),
                            '3-months'  => esc_html__('Events within 3 months','wgl-extensions'),
                            '6-months'  => esc_html__('Events within 6 months','wgl-extensions'),
                            '12-months' => esc_html__('Events within 12 months','wgl-extensions')
                        ),
                    ]
                );
            }


            $self->add_control(
                'order',
                [
                    'label' => esc_html__('Order', 'wgl-extensions'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'DESC' => esc_html__('Descending', 'wgl-extensions'),
                        'ASC' => esc_html__('Ascending', 'wgl-extensions'),
                    ],
                    'default' => 'DESC',
                ]
            );

            if (!isset($atts['hide_cats'])) {
                $self->add_control(
                    'categories',
                    [
                        'label' => esc_html__('Categories', 'wgl-extensions'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by category slug', 'wgl-extensions'),
                        'separator' => 'before',
                        'label_block' => true,
                        'multiple' => true,
                        'options' => self::categories_suggester(),
                    ]
                );

                $self->add_control(
                    'exclude_categories',
                    [
                        'label' => esc_html__('Exclude Selected Categories', 'wgl-extensions'),
                        'type' => Controls_Manager::SWITCHER,
                        'description' => esc_html__('Leave empty for all', 'wgl-extensions'),
                    ]
                );
            }

            if (!isset($atts['hide_tags'])) {
                $self->add_control(
                    'tags',
                    [
                        'label' => esc_html__('Tags', 'wgl-extensions'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by tags slug', 'wgl-extensions'),
                        'separator' => 'before',
                        'multiple' => true,
                        'label_block' => true,
                        'options' => self::tags_suggester(),
                    ]
                );

                $self->add_control(
                    'exclude_tags',
                    [
                        'label' => esc_html__('Exclude Selected Tags', 'wgl-extensions'),
                        'type' => Controls_Manager::SWITCHER,
                        'description' => esc_html__('Leave empty for all', 'wgl-extensions'),
                    ]
                );
            }

            if (!isset($atts['hide_tax'])) {
                $self->add_control(
                    'taxonomies',
                    [
                        'label' => esc_html__('Taxonomies', 'wgl-extensions'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by custom taxonomies.', 'wgl-extensions'),
                        'separator' => 'before',
                        'multiple' => true,
                        'label_block' => true,
                        'options' => self::taxonomies_suggester($atts),
                    ]
                );

                $self->add_control(
                    'exclude_taxonomies',
                    [
                        'label' => esc_html__('Exclude Selected Taxonomies', 'wgl-extensions'),
                        'type' => Controls_Manager::SWITCHER,
                    ]
                );
            }

            if (!isset($atts['hide_individual_posts'])) {
                $self->add_control(
                    'hr_posts',
                    ['type' => Controls_Manager::DIVIDER]
                );

                $self->add_control(
                    'by_posts',
                    [
                        'label' => esc_html__('Individual Post/Page/Post Types', 'wgl-extensions'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by individual posts, pages or custom post type', 'wgl-extensions'),
                        'multiple' => true,
                        'label_block' => true,
                        'options' => self::by_posts_suggester($atts),
                    ]
                );

                $self->add_control(
                    'exclude_any',
                    [
                        'label' => esc_html__('Exclude Selected', 'wgl-extensions'),
                        'type' => Controls_Manager::SWITCHER,
                        'description' => esc_html__('Leave empty for all', 'wgl-extensions'),
                    ]
                );
            }

            if (!isset($atts['hide_author'])) {
                $self->add_control(
                    'author',
                    [
                        'label' => esc_html__('Author', 'wgl-extensions'),
                        'type' => Controls_Manager::SELECT2,
                        'description' => esc_html__('Filter by author names', 'wgl-extensions'),
                        'separator' => 'before',
                        'multiple' => true,
                        'label_block' => true,
                        'options' => self::by_author_suggester(),
                    ]
                );

                $self->add_control(
                    'exclude_author',
                    [
                        'label' => esc_html__('Exclude Selected Authors', 'wgl-extensions'),
                        'type' => Controls_Manager::SWITCHER,
                        'description' => esc_html__('Leave empty for all', 'wgl-extensions'),
                    ]
                );
            }

            $self->end_controls_section();
        }

        public static function get_term_parents_list($term_id, $taxonomy, $args = [])
        {
            $term = get_term($term_id, $taxonomy);

            if (
                !$term
                || is_wp_error($term)
            ) {
                return '';
            }

            $term_id = $term->term_id;

            $defaults = [
                'format' => 'name',
                'separator' => '/',
                'inclusive' => true,
            ];

            $args = wp_parse_args($args, $defaults);

            foreach (['inclusive'] as $bool) {
                $args[$bool] = wp_validate_boolean($args[$bool]);
            }

            $parents = get_ancestors($term_id, $taxonomy, 'taxonomy');

            if ($args['inclusive']) {
                array_unshift($parents, $term_id);
            }

            $a = count($parents) - 1;
            $list = '';
            foreach (array_reverse($parents) as $index => $term_id) {
                $parent = get_term($term_id, $taxonomy);
                $temp_sep = $args['separator'];
                $lastElement = reset($parents);

                if ($index == $a - 1) {
                    $temp_sep = '';
                }

                if ($term_id != $lastElement) {
                    $name = $parent->name;
                    $list .= $name . $temp_sep;
                }
            }

            return $list ?? '';
        }

        public static function categories_suggester()
        {
            foreach (get_categories() as $cat) {
                $parent = self::get_term_parents_list($cat->cat_ID, 'category');

                $content[(string) $cat->slug] = $cat->cat_name
                    . (!empty($parent) ? esc_html__(' (Parent categories: (', 'wgl-extensions') . $parent . '))' : '');
            }

            return $content ?? [];
        }

        public static function tags_suggester()
        {
            foreach (get_tags() as $tag) {
                $content[(string) $tag->slug] = $tag->name;
            }

            return $content ?? [];
        }

        public static function getTaxonomies()
        {
            $taxonomy_exclude = (array) apply_filters('get_categories_taxonomy', 'category');
            $taxonomy_exclude[] = 'post_tag';
            $taxonomies = [];

            foreach (get_taxonomies() as $taxonomy) {
                if (!in_array($taxonomy, $taxonomy_exclude)) {
                    $taxonomies[] = $taxonomy;
                }
            }

            return $taxonomies;
        }

        public static function taxonomies_suggester($atts)
        {
            $taxonomies = self::getTaxonomies();

            if (isset($atts['post_type'])) {
                $type = $atts['post_type'];

                $taxonomies = array_reduce($taxonomies, function ($arr, $item) use ($type) {
                    if (
                        strpos($item, $type) !== false
                        || strpos($item, 'pa_') !== false && 'product' === $type
                    ) {
                        $arr[] = $item;
                    }

                    return $arr;
                });
            }

            $terms_arr = get_terms(['taxonomy' => $taxonomies]);

            if ($terms_arr) foreach ($terms_arr as $tag) {
                $args = [
                    'separator' => ' > ',
                    'format' => 'name',
                ];
                $parent = self::get_term_parents_list($tag->term_id, $tag->taxonomy, $args);

                $content[$tag->taxonomy . ":" . $tag->slug] = $tag->name
                    . ' (' . $tag->taxonomy . ')'
                    . (!empty($parent) ? esc_html__(' (Parent categories: (', 'wgl-extensions') . $parent . '))' : '');
            }

            return $content ?? [];
        }

        public static function by_posts_suggester($atts)
        {
            $arguments['post_type'] = $atts['post_type'] ?? 'any';
            $arguments['numberposts'] = -1;

            if('give_campaigns' === $arguments['post_type']){
                return self::give_campaigns_posts($arguments);
            }

            foreach (get_posts($arguments) as $post) {
                $content[$post->post_name] = $post->post_title;
            }

            return $content ?? [];
        }

        public static function give_campaigns_posts($arguments)
        {
            global $wpdb;
            $sql = "SELECT * FROM {$wpdb->prefix}give_campaigns";
            $campaigns = $wpdb->get_results($sql);
            $content = [];
        
            foreach ($campaigns as $campaign) {
                $content[$campaign->campaign_title] = $campaign->campaign_title;
            }
        
            return $content;
        }

        public static function by_author_suggester()
        {
            foreach (get_users() as $user) {
                $content[(string) $user->ID] = (isset($user->data) && isset($user->data->user_nicename)) ? (string) $user->data->user_nicename : (string) $user->user_nicename;
            }

            return $content ?? [];
        }
    }
    new WGL_Loop_Settings();
}

if (!class_exists('WGL_Query_Builder')) {
    /**
     * WGL Query Builder
     *
     *
     * @category Class
     * @package wgl-extensions\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Query_Builder
    {
        /**
         * @since   1.0.0
         * @var     array
         */
        private $args = [
            'post_status' => 'publish', // show only published posts #1098
        ];

        private $data_attr = [];
        private static $instance = null;
        private $custom_order_type;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        function __construct($data)
        {
            // Include Item
            foreach ($data as $key => $value) {
                $method = 'parse_' . $key;
                if (
                    stripos($key, 'exclude_') === false
                    && method_exists($this, $method)
                    && !empty($value)
                ) {
                    if('post_meta' === $key){
                        $this->$method($value, isset($data['meta_query']) ? $data['meta_query'] : []);
                    }else{
                        $this->$method($value);
                    }                
                }
            }

            // Exclude Item
            foreach ($data as $k => $v) {
                $method = 'parse_' . $k;
                if (
                    stripos($k, 'exclude_') !== false
                    && method_exists($this, $method)
                    && !empty($v)
                ) {
                    $this->$method($v);
                }
            }
        }

        /**
         * Pages count
         */
        protected function parse_number_of_posts($value)
        {
            $this->args['posts_per_page'] = 'All' === $value ? -1 : (int) $value;
        }

        /**
         * Sorting field
         */
        protected function parse_order_by($value)
        {
            $this->args['orderby'] = $value;
        }

        /**
         * Events order by
         */
        protected function parse_events_order_by( $value ) {
            $this->custom_order_type = 'event';

            if( $value == 'title'){
                $this->args['orderby'] = 'title';
            }else{

                $this->args['orderby'] = 'meta_value';
                $this->args['meta_key'] = '_event_start_local';
                $this->args['meta_type'] = 'DATETIME';
            }
        }

        /**
         * Give Campaings order by
         */
        protected function parse_give_campaigns_order_by( $value ) {
            $this->custom_order_type = 'page';
        }

        /**
         * Sorting events
         */
        protected function parse_scope( $value ) {

            $conditions = array();
            $status_type = 'event_status';

            //Publish Status = 1
            $conditions['status'] = "(`{$status_type}`=1)";

            if ( preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{1,2}$/", $value ) ) {
                //Scope can also be a specific date. However, if 'day', 'month', or 'year' are set, that will take precedence
                if( get_option('dbem_events_current_are_past') ){
                    $conditions['scope'] = "event_start_date = CAST('$value' AS DATE)";
                }else{
                    $conditions['scope'] = " ( event_start_date = CAST('$value' AS DATE) OR ( event_start_date <= CAST('$value' AS DATE) AND event_end_date >= CAST('$value' AS DATE) ) )";
                }
            } else {
                if(class_exists('\EM_DateTime')){
                    $EM_DateTime = new \EM_DateTime(); //the time, now, in blog/site timezone
                    if ($value == "past"){
                        if( get_option('dbem_events_current_are_past') ){
                            $conditions['scope'] = " event_start < '".$EM_DateTime->getDateTime(true)."'";
                        }else{
                            $conditions['scope'] = " event_end < '".$EM_DateTime->getDateTime(true)."'";
                        }
                    }elseif ($value == "today"){
                        $conditions['scope'] = " (event_start_date = CAST('".$EM_DateTime->getDate()."' AS DATE))";
                        if( !get_option('dbem_events_current_are_past') ){
                            $conditions['scope'] .= " OR (event_start_date <= CAST('".$EM_DateTime->getDate()."' AS DATE) AND event_end_date >= CAST('$EM_DateTime' AS DATE))";
                        }
                    }elseif ($value == "tomorrow"){
                        $EM_DateTime->modify('+1 day');
                        $conditions['scope'] = "(event_start_date = CAST('".$EM_DateTime->getDate()."' AS DATE))";
                        if( !get_option('dbem_events_current_are_past') ){
                            $conditions['scope'] .= " OR (event_start_date <= CAST('".$EM_DateTime->getDate()."' AS DATE) AND event_end_date >= CAST('".$EM_DateTime->getDate()."' AS DATE))";
                        }
                    }elseif ($value == "month" || $value == "next-month"){
                        if( $value == 'next-month' ) $EM_DateTime->add('P1M');
                        $start_month = $EM_DateTime->modify('first day of this month')->getDate();
                        $end_month = $EM_DateTime->modify('last day of this month')->getDate();
                        $conditions['scope'] = " (event_start_date BETWEEN CAST('$start_month' AS DATE) AND CAST('$end_month' AS DATE))";
                        if( !get_option('dbem_events_current_are_past') ){
                            $conditions['scope'] .= " OR (event_start_date < CAST('$start_month' AS DATE) AND event_end_date >= CAST('$start_month' AS DATE))";
                        }
                    }elseif( preg_match('/([0-9]+)\-months/',$value,$matches) ){ // next x months means this month (what's left of it), plus the following x months until the end of that month.
                        $months_to_add = $matches[1];
                        $start_month = $EM_DateTime->getDate();
                        $end_month = $EM_DateTime->add('P'.$months_to_add.'M')->format('Y-m-t');
                        $conditions['scope'] = " (event_start_date BETWEEN CAST('$start_month' AS DATE) AND CAST('$end_month' AS DATE))";
                        if( !get_option('dbem_events_current_are_past') ){
                            $conditions['scope'] .= " OR (event_start_date < CAST('$start_month' AS DATE) AND event_end_date >= CAST('$start_month' AS DATE))";
                        }
                    }elseif ($value == "future"){
                        $conditions['scope'] = " event_start >= '".$EM_DateTime->getDateTime(true)."'";
                        if( !get_option('dbem_events_current_are_past') ){
                            $conditions['scope'] .= " OR (event_end >= '".$EM_DateTime->getDateTime(true)."')";
                        }
                    }
                }
            }
            if( !empty($conditions['scope']) ){
                $conditions['scope'] = '('.$conditions['scope'].')';
            }

            $this->args['where'] =  implode( " AND ", $conditions );
        }

        /**
         * Sorting order
         */
        protected function parse_order($value)
        {
            $this->args['order'] = $value;
        }

        /**
         * By author
         */
        protected function parse_author($value)
        {
            $value = implode(',', $value);
            $this->data_attr['author_id'] = $value;
            $this->args['author'] = $value;
        }

        /**
         * Exclude author
         */
        protected function parse_exclude_author($value)
        {
            if (!isset($this->data_attr['author_id'])) {
                return;
            }
            if (isset($this->args['author'])) {
                unset($this->args['author']);
            }
            $author_id = [];
            $author_id[] = $this->data_attr['author_id'];
            $this->args['author__not_in'] = $author_id;
        }

        /**
         * By categories
         */
        protected function parse_categories($value)
        {
            if (empty($value)) {
                return;
            }

            $this->args['category_name'] = implode(', ', (array) $value);
        }

        /**
         * Exclude categories
         */
        protected function parse_exclude_categories($value)
        {
            if (!isset($this->args['category_name'])) {
                return;
            }

            $list = explode(', ', $this->args['category_name']);

            $id_list = [];
            foreach ($list as $key => $value) {
                $idObj = get_category_by_slug($value);
                $id_list[] = '-' . $idObj->term_id;
            }
            $id_list = implode(',', $id_list);
            $this->args['cat'] = $id_list;
            unset($this->args['category_name']);
        }

        /**
         * Get Post Meta
         */
        private function parse_post_meta($value, $query)
        {
            $terms = (array) $query;
            $this->args['meta_query'] = ['relation' => 'OR'];
            foreach($terms as $t){
                $this->args['meta_query'][] = $t;
            }
        }

        /**
         * Get Taxonomies
         */
        private function get_tax($value)
        {
            $terms = (array) $value;
            $this->args['tax_query'] = ['relation' => 'AND'];

            $item = $id_list = [];

            $taxonomies = get_terms(WGL_Loop_Settings::getTaxonomies());
            foreach ($terms as $key => $value) {
                $item_t = explode(":", $value);
                if (isset($item_t[1])) {
                    $idObj = get_term_by('slug', $item_t[1], $item_t[0]);

                    if(function_exists('pll_get_term')){
                        global $wpdb;
                        $idObj = $wpdb->get_row( "SELECT term_id FROM $wpdb->terms WHERE slug = '".$item_t[1]."'" );
                    }

                    if(isset($idObj->term_id)){
                        $id_list[] = $idObj->term_id;
                    }
                }
            }

            $terms = get_terms(
                WGL_Loop_Settings::getTaxonomies(),
                ['include' => array_map('abs', $id_list)]
            );
            foreach ($terms as $t) {
                $item[$t->taxonomy][] = $t->slug;
            }

            return $item;
        }

        /**
         * By taxonomies
         */
        protected function parse_taxonomies($value)
        {
            if (empty($value)) {
                return;
            }

            $this->data_attr['taxonomies'] = $value;

            $item = $this->get_tax($value);

            foreach ($item as $taxonomy => $terms) {
                $this->args['tax_query'][] = [
                    'field' => 'slug',
                    'taxonomy' => $taxonomy,
                    'terms' => $terms,
                    'operator' => 'IN',
                ];
            }
        }

        /**
         * Exclude tax slugs
         */
        protected function parse_exclude_taxonomies()
        {
            if (!isset($this->data_attr['taxonomies'])) {
                return;
            }

            if (isset($this->args['tax_query'])) {
                unset($this->args['tax_query']);
            }

            $value = $this->data_attr['taxonomies'];

            $item = $this->get_tax($value);

            foreach ($item as $taxonomy => $terms) {
                $this->args['tax_query'][] = [
                    'field' => 'slug',
                    'taxonomy' => $taxonomy,
                    'terms' => $terms,
                    'operator' => 'NOT IN',
                ];
            }
        }

        /**
         * By tags slugs
         */
        protected function parse_tags($value)
        {
            if (empty($value)) {
                return;
            }

            $this->data_attr['tags'] = $value;
            $in = $not_in = [];
            $tags_slugs = $value;
            foreach ($tags_slugs as $tag) {
                $in[] = $tag;
            }
            $this->args['tag_slug__in'] = $in;
        }

        /**
         * Exclude tags slugs
         *
         * @since 1.0
         *
         * @param $value
         */
        protected function parse_exclude_tags($value)
        {
            if (!isset($this->data_attr['tags'])) {
                return;
            }

            $list = $this->data_attr['tags'];
            $id_list = [];
            foreach ($list as $value) {
                $idObj = get_term_by('slug', $value, 'post_tag');
                $id_list[] = (int) $idObj->term_id;
            }

            $this->args['tag__not_in'] = $id_list;

            unset($this->args['tag_slug__in']);
        }

        /**
         * By posts slugs
         */
        protected function parse_by_posts($value)
        {
            $in = [];
            $this->data_attr['posts_in'] = $value;
            $slugs = $value;
            if (!empty($slugs)) {
                foreach ($slugs as $slug) {
                    $in[] = $slug;
                }
                $this->args['post_name__in'] = $in;
            }
        }

        /**
         * Exclude posts slugs
         */
        protected function parse_exclude_any($value)
        {
            if (!isset($this->data_attr['posts_in'])) {
                return;
            }

            if (isset($this->args['post_name__in'])) {
                unset($this->args['post_name__in']);
            }

            $options = [];
            $value = $this->data_attr['posts_in'];

            $type = isset($this->custom_order_type) ? $this->custom_order_type : 'any';

            $list = new \WP_Query([
                'post_type' => $type,
                'post_name__in' => $value,
            ]);
            foreach ($list->posts as $obj) {
                $options[] = $obj->ID;
            }
            $this->args['post__not_in'] = $options;
        }

        public function excludeId($id)
        {
            if (!isset($this->args['post__not_in'])) {
                $this->args['post__not_in'] = [];
            }
            if (is_array($id)) {
                $this->args['post__not_in'] = array_merge($this->args['post__not_in'], $id);
            } else {
                $this->args['post__not_in'][] = $id;
            }
        }

        public function filter_where($where = '')
        {
            return $where . ' AND ' . $this->args['where'];
        }

        public function add_table( $join, $wp_query )
        {
            if( defined('EM_EVENTS_TABLE') ){
                global $wpdb;
                $events_table = EM_EVENTS_TABLE;
                $join .= " JOIN {$events_table} on {$wpdb->posts}.ID = {$wpdb->prefix}em_events.post_id ";
                return $join;
            }
        }

        public function build()
        {
            if(isset($this->args['where'])){
                add_filter( 'posts_join', [$this, 'add_table'], 10, 2);
                add_filter( 'posts_where', [$this, 'filter_where']);
            }

            $output = [$this->args, new \WP_Query($this->args)];

            if (isset($this->args['where'])) {
                remove_filter( 'posts_join', [$this, 'add_table']);
                remove_filter( 'posts_where', [$this, 'filter_where']);
            }

            return $output;
        }
    }
}
