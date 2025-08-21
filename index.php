<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Theme_Package
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	if ( have_posts() ) :

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			/*
			 * Include the template for the content.
			 */
			the_content();

		endwhile;

		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>
</main><!-- #primary -->

<?php
get_footer();
?>