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
    <p><?php echo get_the_content(); ?></p>
    <?php  $value = get_post_meta( $post->ID, '_velvet_meta_key', true );
    echo "<h5> $value </h5>";
    ?>
   <?php endwhile;
else :
    _e( 'Sorry, no posts were found.', 'velvettheme' );
endif;

?>

<?php
get_footer();
