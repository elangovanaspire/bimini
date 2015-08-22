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
<?php
$description = get_field('description');
if (!empty($description)) :
    ?>  
    <div class="entry-content">
        <h1>Description</h1>
    <?php the_field('description'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>

<?php
$image = get_field('banner_image');
if ($image) :
    ?>
    <div class="entry-content">             
        <h1>Detailed Page - Banner Image</h1>        
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />                  
    </div>
    <br /> <br />
<?php endif; ?>

<?php
$speed_dial = get_field('speed_dial');
if (!empty($speed_dial)) :
    ?>  
    <div class="entry-content">
        <h1>Speed Dial</h1>
    <?php the_field('speed_dial'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>