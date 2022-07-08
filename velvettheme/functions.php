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
//load_theme_textdomain( 'myfirsttheme', get_template_directory() . '/languages' );
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


/**
 * Add a sidebar.
 */
function wpdocs_theme_slug_widgets_init() {
  register_sidebar( array(
      'name'          => __( 'Main Sidebar', 'textdomain' ),
      'id'            => 'sidebar-1',
      'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
      'before_widget' => '<li id="%1$s" class="widget %2$s">',
      'after_widget'  => '</li>',
      'before_title'  => '<h2 class="widgettitle">',
      'after_title'   => '</h2>',
  ) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );

class My_Widget extends WP_Widget {
 
  function __construct() {

      parent::__construct(
          'my-text',  // Base ID
          'My Text'   // Name
      );

      add_action( 'widgets_init', function() {
          register_widget( 'My_Widget' );
      });

  }

  public $args = array(
      'before_title'  => '<h4 class="widgettitle">',
      'after_title'   => '</h4>',
      'before_widget' => '<div class="widget-wrap">',
      'after_widget'  => '</div></div>'
  );

  public function widget( $args, $instance ) {

      echo $args['before_widget'];

      if ( ! empty( $instance['title'] ) ) {
          echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
      }

      echo '<div class="textwidget">';

      echo esc_html__( $instance['text'], 'text_domain' );

      echo '</div>';

      echo $args['after_widget'];

  }

  public function form( $instance ) {

      $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
      $text = ! empty( $instance['text'] ) ? $instance['text'] : esc_html__( '', 'text_domain' );
      ?>
      <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'text_domain' ); ?></label>
          <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
      </p>
      <p>
          <label for="<?php echo esc_attr( $this->get_field_id( 'Text' ) ); ?>"><?php echo esc_html__( 'Text:', 'text_domain' ); ?></label>
          <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" cols="30" rows="10"><?php echo esc_attr( $text ); ?></textarea>
      </p>
      <?php

  }

  public function update( $new_instance, $old_instance ) {

      $instance = array();

      $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      $instance['text'] = ( !empty( $new_instance['text'] ) ) ? $new_instance['text'] : '';

      return $instance;
  }

}
$my_widget = new My_Widget();



class Foo_Widget extends WP_Widget {
 
  /**
   * Register widget with WordPress.
   */
  public function __construct() {
      parent::__construct(
          'foo_widget', // Base ID
          'Foo_Widget', // Name
          array( 'description' => __( 'A Foo Widget', 'text_domain' ), ) // Args
      );
      add_action( 'widgets_init', function() {
        register_widget( 'Foo_Widget' );
    });
  }

  public function widget( $args, $instance ) {
      extract( $args );
      $title = apply_filters( 'widget_title', $instance['title'] );

      echo $before_widget;
      if ( ! empty( $title ) ) {
          echo $before_title . $title . $after_title;
      }
      echo __( 'Hello, World!', 'text_domain' );
      echo $after_widget;
  }


  public function form( $instance ) {
      if ( isset( $instance[ 'title' ] ) ) {
          $title = $instance[ 'title' ];
      }
      else {
          $title = __( 'New title', 'text_domain' );
      }
      ?>
      <p>
          <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
       </p>
  <?php
  }

  public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

      return $instance;
  }

} 

$my_widget1 = new Foo_Widget();