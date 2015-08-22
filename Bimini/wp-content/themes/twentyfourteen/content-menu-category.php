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

<?php $description = get_post_meta($post->ID,'content',true);
if (!empty($description)) :
    ?>  
    <div class="entry-content">
        <h1>Description</h1>
    <?php echo strip_tags($description); 
    //strip_tags($description); ?>            
    </div>
    <br /> <br />
<?php endif; ?> 

<?php
$image = get_field('image');
if ($image) :
    ?>
    <div class="entry-content">             
        <h1>Image</h1>        
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />                  
    </div>
    <br /> <br />
<?php endif; ?>      




