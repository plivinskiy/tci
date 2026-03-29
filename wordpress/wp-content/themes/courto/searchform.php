<?php
defined('ABSPATH') || exit;

/**
 * Template for displaying search forms
 *
 * @package courto
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

$unique_id = uniqid('search-form-');
$search_post_type = WGL_Framework::get_option('search_post_type') ?: [];

$inputs = '';
if (!empty($search_post_type)) {
    if (count($search_post_type) === 1) {
        $inputs .= '<input type="hidden" name="post_type" value="'.$search_post_type[0].'" />';
    } else{
        foreach ($search_post_type as $key => $value) {
            $inputs .= '<input type="hidden" name="post_type[]" value="'.$value.'" />';
        }
    }
}

echo '<form role="search" method="get" action="', esc_url(home_url('/')), '" class="search-form">',
    '<input',
        ' required',
        ' type="text"',
        ' id="', esc_attr($unique_id), '"',
        ' class="search-field"',
        ' placeholder="', esc_attr_x('Search...', 'placeholder', 'courto'), '"',
        ' value="', get_search_query(), '"',
        ' name="s"',
        '>',
    '<button class="search-button" type="submit" value="',esc_attr__('Search', 'courto'),'"><i class="search__icon">',wgl_dynamic_styles()->wgl_theme_svg()['search'],'</i></button>',
    $inputs,
'</form>';
