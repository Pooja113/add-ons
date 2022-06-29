<?php
/**
 *
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
add_theme_support( 'post-thumbnails' );


function velvet_custom_post_type() {
  register_post_type('books',
      array(
          'labels'      => array(
              'name'          => __( 'Books', 'velvettheme' ),
              'singular_name' => __( 'Books', 'velvettheme' ),
          ),
          'public'      => true,
          'has_archive' => true,
          'rewrite'     => array( 'slug' => 'books' ),
          'supports'  => array('title','editor','excerpt','author', 'thumbnail','comments')
      )
  );
}
add_action('init', 'velvet_custom_post_type');

