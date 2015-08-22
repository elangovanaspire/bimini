<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

get_header();



$post_object = get_field('post_object');
echo "page display";

if( $post_object ): 

	// override $post
	$post = $post_object;
	setup_postdata( $post ); 

	?>
    <div>
    	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    	<span>Post Object Custom Field: <?php the_field('description'); ?></span>
    </div>
    <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
<?php endif; 

get_footer();

?>