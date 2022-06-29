<?php
/**
 *
 * This is the template contains header section
 *
 * @package velvettheme
 */

/**
 * Proper way to enqueue scripts and styles.
 */
function wpdocs_velvet_scripts() {
    wp_enqueue_style( 'stylesheet', get_stylesheet_uri() );
    //wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_velvet_scripts' );


add_action( 'after_setup_theme', 'register_my_menu' );
function register_my_menu() {
  register_nav_menu( 'primary', __( 'Primary Menu', 'velvettheme' ) );
}

add_theme_support( 'custom-header' );

add_theme_support( 'custom-logo' );