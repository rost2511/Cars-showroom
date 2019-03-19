<?php
/**
 * Register navigation menus
 *
 * @link https://codex.wordpress.org/Function_Reference/register_nav_menus
 */
add_action( 'after_setup_theme', 'register_theme_menus' );
function register_theme_menus() {
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', THEME_TD ),
        'footer_menu' => __( 'Footer Menu', THEME_TD )
    ) );
}
