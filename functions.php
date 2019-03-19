<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

/**
 * Text domain definition
 */
defined('THEME_TD') ? THEME_TD : define('THEME_TD', 'bt');

// Load modules

$theme_includes = [
    '/lib/helpers.php',
    '/lib/cleanup.php',                        // Clean up default theme includes
    '/lib/enqueue-scripts.php',                // Enqueue styles and scripts
    '/lib/protocol-relative-theme-assets.php', // Protocol (http/https) relative assets path
    '/lib/framework.php',                      // Css framework related stuff (content width, nav walker class, comments, pagination, etc.)
    '/lib/theme-support.php',                  // Theme support options
    '/lib/template-tags.php',                  // Custom template tags
    '/lib/menu-areas.php',                     // Menu areas
    '/lib/widget-areas.php',                   // Widget areas
    '/lib/customizer.php',                     // Theme customizer
    '/lib/vc_shortcodes.php',                  // Visual Composer shortcodes
    '/lib/jetpack.php',                        // Jetpack compatibility file
    '/lib/acf_field_groups_type.php',          // ACF Field Groups Organizer
];

foreach ($theme_includes as $file) {
    if (!$filepath = locate_template($file)) {
        continue;
        trigger_error(sprintf(__('Error locating %s for inclusion', THEME_TD), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);


// Theme the TinyMCE editor (Copy post/page text styles in this file)

add_editor_style('assets/dist/css/custom-editor-style.css');


// Custom CSS for the login page

function loginCSS()
{
    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri(THEME_TD) . 'assets/dist/css/wp-login.css"/>';
}

add_action('login_head', 'loginCSS');


// Add body class for active sidebar
function wp_has_sidebar($classes)
{
    if (is_active_sidebar('sidebar')) {
        // add 'class-name' to the $classes array
        $classes[] = 'has_sidebar';
    }
    // return the $classes array
    return $classes;
}

add_filter('body_class', 'wp_has_sidebar');

// Remove the version number of WP
// Warning - this info is also available in the readme.html file in your root directory - delete this file!
remove_action('wp_head', 'wp_generator');


// Obscure login screen error messages
function wp_login_obscure()
{
    return '<strong>Error</strong>: wrong username or password';
}

add_filter('login_errors', 'wp_login_obscure');


// Disable the theme / plugin text editor in Admin
define('DISALLOW_FILE_EDIT', true);

if (function_exists('acf_add_options_page')) {
	acf_add_options_page();
}
