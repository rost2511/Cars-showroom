<?php

if( ! class_exists('acf_field_groups_type') ) :

class acf_field_groups_type
{
    var $taxonomy = 'acf-fg-type',
        $post_type = 'acf-field-group',
        $default_terms = ['Template', 'Option', 'Post Type'];

    function __construct() {
        add_action('init',                                       [$this, 'field_group_taxonomy']);
        add_action('init',                                       [$this, 'field_group_terms']);
        add_filter('manage_edit-acf-field-group_columns',        [$this, 'field_group_columns'], 20, 1);
        add_action('manage_acf-field-group_posts_custom_column', [$this, 'field_group_columns_html'], 20, 2);
        add_action('restrict_manage_posts',                      [$this, 'field_group_filter'], 10, 2);
    }

    function field_group_taxonomy() {
        register_taxonomy(
            $this->taxonomy,
            $this->post_type,
            [
                'label' => __('Field Group Type'),
                'rewrite' => false,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
            ]
        );
    }

    function field_group_terms() {
        foreach ($this->default_terms as $default_term) {
            wp_insert_term($default_term, $this->taxonomy);
        }
    }

    function field_group_columns($columns) {
        unset($columns['taxonomy-acf-fg-type']);
        $new = [];
        foreach($columns as $key => $title) {
            if ($key=='acf-fg-status')
                $new[$this->taxonomy] = __('Type');
            $new[$key] = $title;
        }
        return $new;
    }

    function field_group_columns_html($column, $post_id) {
        switch ($column) {
            case $this->taxonomy :
                $terms = get_the_terms($post_id, $this->taxonomy);
                if ($terms) {
                    $term_names = wp_list_pluck($terms, 'name');
                    $terms_str = implode(', ', $term_names);
                    echo $terms_str;
                }
                break;
        }
    }

    function field_group_filter($post_type, $which) {
        if ($this->post_type !== $post_type)
            return;

        $taxonomies = [$this->taxonomy];
        foreach ($taxonomies as $taxonomy_slug) {
            $taxonomy_obj = get_taxonomy($taxonomy_slug);
            $taxonomy_name = $taxonomy_obj->labels->name;
            $terms = get_terms($taxonomy_slug);

            echo '<style>#acf-field-group-wrap .tablenav { display: block; } #acf-field-group-wrap .tablenav #filter-by-date, #acf-field-group-wrap .tablenav .bulkactions { display: none; }</style>';
            echo '<select name="' . $taxonomy_slug . '" id="' . $taxonomy_slug . '" class="postform">';
            echo '<option value="">' . $taxonomy_name . '</option>';
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    $term->slug,
                    isset($_GET[$taxonomy_slug]) && ($_GET[$taxonomy_slug] == $term->slug) ? ' selected="selected"' : '',
                    $term->name,
                    $term->count
                );
            }
            echo '</select>';
        }
    }
}

new acf_field_groups_type();

endif;