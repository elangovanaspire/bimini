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
        $image = get_field('map');        
        if($image) :             
            ?>
        <div class="entry-content"> 
                  <h1>Map</h1>        
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />         
        </div>
        <br /> <br />  
        <?php endif; ?>