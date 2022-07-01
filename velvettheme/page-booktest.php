<?php
/**
 * 
 * Template Name: Books
 * @package velvettheme
 */

get_header(); ?>

<?php
    $args = array(  
      'post_type' => 'books',
      'post_status' => 'publish',
      'posts_per_page' => -1, 
      'order' => 'DESC', 
      'tax_query' => array(
        array(
          'taxonomy' => 'course',
          'terms' => [6]
      )
   )
  );

  $loop = new WP_Query( $args ); 
      
  while ( $loop->have_posts() ) : $loop->the_post(); 
      ?>
      <h1>
     <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
    </h1>
    <p><?php  the_excerpt(); ?></p>
    <?php if ( has_post_thumbnail( $post->ID ) ) {
        echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '">';
        echo get_the_post_thumbnail( $post->ID, 'thumbnail' );
        echo '</a>'; }
        ?>
 <?php endwhile;

  wp_reset_postdata(); 
  ?>

<?php
get_footer();
