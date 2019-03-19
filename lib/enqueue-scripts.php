<?php
/**
 * Enqueue all styles and scripts.
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style}
 */
if (!function_exists('bt_scripts')) :
    function bt_scripts()
    {
        // Enqueue the main Stylesheet.
        wp_enqueue_style('main-stylesheet', asset_path('styles/main.css') ,  false, null, 'all');

        // Deregister the jquery version bundled with WordPress.
        wp_deregister_script('jquery');

        // CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
        wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-2.2.4.min.js', array(), '2.2.4', false);

        // Enqueue the main JS file.
        wp_enqueue_script('main-javascript', asset_path('scripts/main.js'), array('jquery'), null, true);

        // Throw variables from back to front end.
        $themeVars = array(
            'home' => get_home_url(),
            'isHome' => is_front_page()
        );
        wp_localize_script('main-javascript', 'themeVars', $themeVars);

        // Comments reply script
        if (is_singular() && comments_open()):
            wp_enqueue_script("comment-reply");
        endif;
    }

    add_action('wp_enqueue_scripts', 'bt_scripts');
endif;

if (file_exists(get_template_directory() . '/feedback/feedback.php')) :
    require_once get_template_directory() . '/feedback/feedback.php';
endif;