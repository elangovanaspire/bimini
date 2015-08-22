<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

 <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php
  // Page thumbnail and title.
  twentyfourteen_post_thumbnail();
  the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
  ?>

  <? php /*
  <div class="entry-content">
  <?php 
  the_content();
  wp_link_pages( array(
  'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
  'after'       => '</div>',
  'link_before' => '<span>',
  'link_after'  => '</span>',
  ) );

  edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
  ?>
  </div><!-- .entry-content -->
  </article><!-- #post-## -->
 */ ?>

<?php
$screen_id = get_field('screen_id');
if (!empty($screen_id)) :
    ?>  
    <div class="entry-content">
        <h1>Screen ID</h1>
    <?php the_field('screen_id'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>    

<?php
$image = get_field('carousel_image');
if ($image) :
    ?>
    <div class="entry-content">                   
        <h1>Carousel Image</h1>      
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />      
    </div>
    <br /> <br />   
<?php endif; ?>

<?php
$image = get_field('slider_image');
if ($image) :
    ?>
    <div class="entry-content"> 
        <h1>Slider Image</h1>        
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />         
    </div>
    <br /> <br />  
<?php endif; ?>


<?php
$image = get_field('screen_image');
if ($image) :
    ?>
    <div class="entry-content">             
        <h1>Screen Image</h1>        
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />                  
    </div>
    <br /> <br />
<?php endif; ?>

<?php
$ios_tag_name = get_field('ios_tag_name');
if (!empty($ios_tag_name)) :
    ?>  
    <div class="entry-content">
    <?php the_field('ios_tag_name'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>