<?php
/**
 * Blog Page Template
 *
 *
 * @package velvettheme
 */

get_header(); ?>

<?php if ( have_posts() ) :
    while ( have_posts() ) : the_post(); ?>
    <h1>
     <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
    </h1>
    
    <p><?php the_content(); ?></p>
  
   <?php endwhile;
else :
    _e( 'Sorry, no posts were found.', 'textdomain' );
endif;


?>

<?php
get_footer();
