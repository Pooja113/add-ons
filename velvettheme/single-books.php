<?php
/**
 * Single File template
 *
 *
 * @package velvettheme
 */

get_header(); ?>

<?php if ( have_posts() ) : 
    while ( have_posts() ) : the_post(); ?>
    <h1>
     <?php the_title(); ?>
    </h1>
    <p> <?php 
 the_terms( $post->ID, 'course', 'Categories: '); ?></p>

    <p><?php the_content(); 

    
    ?></p>
   <?php endwhile;
else :
    _e( 'Sorry, no posts were found.', 'velvettheme' );
endif;

?>

<?php
get_footer();
