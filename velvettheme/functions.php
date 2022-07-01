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
add_theme_support( 'automatic-feed-links' );
load_theme_textdomain( 'myfirsttheme', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );


function velvet_custom_post_type() {
  register_post_type('books',
      array(
          'labels'      => array(
              'name'          => __( 'Books', 'velvettheme' ),
              'singular_name' => __( 'Book', 'velvettheme' ),
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



function velvet_shortcode( $atts = [] ) {
  $output = '';
  extract( shortcode_atts( array(
    'limit' => '10',
    'orderby' => 'date',
), $atts ) );
// Creating custom query to fetch the project type custom post.
$loop = new WP_Query(array('post_type' => 'books', 'posts_per_page' => $limit, 'orderby' => $orderby));
// Looping through the posts and building the HTML structure.
if($loop){
    while ($loop->have_posts()){
         $loop->the_post();
         $output .= '<div class="type-post hentry"><h2 class="entry-title"><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
         $output .= '<div class="entry-content">'.get_the_excerpt().'</div></div>';
    }
}
else
    $output = 'Sorry, No projects yet. Come back Soon.';        
// Now we are returning the HTML code back to the place from where the shortcode was called.        
return $output;
}

function velvet_shortcodes_init() {
  add_shortcode( 'redvelvet', 'velvet_shortcode' );
}

add_action( 'init', 'velvet_shortcodes_init' );


function wporg_register_taxonomy_course() {
  $labels = array(
      'name'              => _x( 'Courses', 'taxonomy general name' ),
      'singular_name'     => _x( 'Course', 'taxonomy singular name' ),
      'search_items'      => __( 'Search Courses' ),
      'all_items'         => __( 'All Courses' ),
      'parent_item'       => __( 'Parent Course' ),
      'parent_item_colon' => __( 'Parent Course:' ),
      'edit_item'         => __( 'Edit Course' ),
      'update_item'       => __( 'Update Course' ),
      'add_new_item'      => __( 'Add New Course' ),
      'new_item_name'     => __( 'New Course Name' ),
      'menu_name'         => __( 'Course' ),
  );
  $args   = array(
      'hierarchical'      => true, // make it hierarchical (like categories)
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => [ 'slug' => 'course' ],
  );
  register_taxonomy( 'course', [ 'post','books' ], $args );
}
add_action( 'init', 'wporg_register_taxonomy_course' );