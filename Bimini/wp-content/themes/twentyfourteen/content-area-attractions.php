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
        $image = get_field('front_banner_image');        
        if($image) :             
            ?>
        <div class="entry-content">                   
            <h1>List Page - Image</h1>      
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />      
        </div>
        <br /> <br />   
        <?php endif; ?>
        
        <?php         
        $image = get_field('map');        
        if($image) :             
            ?>
        <div class="entry-content"> 
                  <h1>View Map</h1>        
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />         
        </div>
        <br /> <br />  
        <?php endif; ?>
        
        <?php $description=get_field('description'); 
        if(!empty($description)) :?>  
        <div class="entry-content">
            <h1>Description</h1>
             <?php the_field('description'); ?>            
            </div>
        <br /> <br />
        <?php endif; ?>
        
        <?php         
        $image = get_field('banner_image');        
        if($image) :             
            ?>
        <div class="entry-content">             
            <h1>Detailed Page - Banner Image</h1>        
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />                  
        </div>
        <br /> <br />
        <?php endif; ?>
        
        <?php         
        $image = get_field('logo');        
        if($image) :             
            ?>
        <div class="entry-content"> 
                  <h1>Detailed Page - Logo</h1>        
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />         
        </div>
        <br /> <br />  
        <?php endif; ?>
        
         <?php $external_link=get_field('external_link'); 
        if(!empty($external_link)) :?>  
        <div class="entry-content">
             <?php the_field('external_link'); ?>            
            </div>
        <br /> <br />
        <?php endif; ?>
        
                
        <?php         
        $image = get_field('menu');        
        if($image) :             
            ?>
        <div class="entry-content"> 
                  <h1>Menu</h1>        
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />         
        </div>
        <br /> <br />  
        <?php endif; ?>
        
        