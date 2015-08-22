<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php twentyfourteen_post_thumbnail(); ?>

	<header class="entry-header">
		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && twentyfourteen_categorized_blog() ) : ?>
		<div class="entry-meta">
			<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfourteen' ) ); ?></span>
		</div>
		<?php
			endif;

			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

            
		<div class="entry-meta">
			<?php
				if ( 'post' == get_post_type() )
					twentyfourteen_posted_on();

				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '1 Comment', 'twentyfourteen' ), __( '% Comments', 'twentyfourteen' ) ); ?></span>
			<?php
				endif;

				edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
        <?php
      if('area-attractions' == get_post_type()){
          include 'content-area-attractions.php';
      }
       if('shopping' == get_post_type()){
          include 'content-shopping.php';
      }
      if('night-life' == get_post_type()){
          include 'content-night-life.php';
      }
      if('fitness-center' == get_post_type()){
          include 'content-fitness-center.php';
      }
      if('promotions' == get_post_type()){
          include 'content-promotions.php';
      }
      if('pool' == get_post_type()){
          include 'content-pool.php';
      }
      if('spa' == get_post_type()){
          include 'content-spa.php';
      }
      if('email-template' == get_post_type()){
          include 'content-email-template.php';
      }
      if('restaurants-and-bars' == get_post_type()){
          include 'content-restaurants-and-bars.php';
      }
      if('inroom-dining-menu' == get_post_type()){
          include 'content-inroom-dining-menu.php';
      }     
      if('in-room-ordering' == get_post_type()){
          include 'content-in-room-ordering.php';
      }
      if('activity-guide' == get_post_type()){
          include 'content-activity-guide.php';
      }      
      if('planning-a-meeting' == get_post_type()){
          include 'content-planning-a-meeting.php';
      }
      if('todays-events' == get_post_type()){
          include 'content-todays-events.php';
      }   
      if('banner-image' == get_post_type()){
          include 'content-banner-image.php';
      }  
      if('menu-category' == get_post_type()){
          include 'content-menu-category.php';
      } 
      
      
      $post_type1 = array('area-map','property-map');
       if( in_array( get_post_type(), $post_type1) ){
          include 'content-map.php';
      }     
      
      $post_type = array('slots','table-games','responsible-gaming','players-club','tutorials');
       if( in_array( get_post_type(), $post_type) ){
          include 'content-games.php';
      }
//Edited by Sajjad    
      if('tram-schedule' == get_post_type()){
          include 'content-tram-schedule.php';
      }
  */     
        
        
	if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ) );
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>
       
	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
