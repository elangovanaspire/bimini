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
<?php $category = get_field('category');
if (!empty($category)) :
    ?> 
    <div class="entry-content">
        <h1>Food Category</h1>
    <?php echo $category->post_title; ?>              
    </div>
    <br /> <br />
<?php endif; ?>

<?php $description = get_post_meta($post->ID,'description',true);
if (!empty($description)) :
    ?>  
    <div class="entry-content">
        <h1>Description</h1>
    <?php echo strip_tags($description); 
    //strip_tags($description); ?>            
    </div>
    <br /> <br />
<?php endif; ?> 

<?php $price = get_field('price');
if (!empty($price)) :
    ?>  
    <div class="entry-content">
        <h1>Price</h1>
    <?php echo "$";the_field('price'); ?>            
        
    </div>
    <br /> <br />
<?php endif; ?>
        




