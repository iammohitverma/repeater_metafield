<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package justcoheader
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->


<?php

	global $wpdb;

	$table_name = $wpdb->prefix . 'wp_postmeta';
	$cutomMetaKey = 'custom_repeater_item';
	$cutomMetaKey_values = get_post_meta( get_the_ID(), $cutomMetaKey, true);

	echo get_the_ID();
	echo "<pre>";
	print_r($cutomMetaKey_values);
	echo "</pre>";
	
	if($cutomMetaKey_values){
		foreach($cutomMetaKey_values as $value)
		{
			print_r($value['title']);
		}
	}
	echo "</pre>";


get_footer();


