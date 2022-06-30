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


function velvet_add_custom_box() {
  $screens = [ 'post', 'books' ];
  foreach ( $screens as $screen ) {
      add_meta_box(
          'author_box_id',                 // Unique ID
          'Author',      // Box title
          'author_custom_box_html',  // Content callback, must be of type callable
          $screen                            // Post type
      );
  }
}
add_action( 'add_meta_boxes', 'velvet_add_custom_box' );

function author_custom_box_html( $post ) {
  $value = get_post_meta( $post->ID, '_velvet_meta_key', true );
  ?>
    <label for="author_field">Description for this field</label>
    <input type="text" id="author_field" name="author_field"  value="<?php _e($value , 'velvettheme' ); ?>" >
  <?php
}


function velvet_save_postdata( $post_id ) {
  $title = sanitize_text_field( $_POST['author_field'] );

  if ( array_key_exists( 'author_field', $_POST ) ) {
      update_post_meta(
          $post_id,
          '_velvet_meta_key',
          $_POST['author_field']
      );
  }
}
add_action( 'save_post', 'velvet_save_postdata' );