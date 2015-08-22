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
        $to_emailaddress = get_field('to_emailaddress');        
        if(!empty($to_emailaddress)) :             
            ?>
        <div class="entry-content">                   
            <h1>To</h1>      
            <?php the_field('to_emailaddress'); ?>           
        </div>
        <br /> <br />   
        <?php endif; ?>
        
        <?php         
        $subject = get_field('subject');        
        if(!empty($subject)) :             
            ?>
        <div class="entry-content">                   
            <h1>Subject</h1>      
            <?php the_field('subject'); ?>           
        </div>
        <br /> <br />   
        <?php endif; ?>
        
               
        <?php $message_header=get_field('message_header'); 
        if(!empty($message_header)) :?>  
        <div class="entry-content">
            <h1>Message (Body)</h1>
             <?php the_field('message_header'); ?>            
            </div>
        <br /> <br />
        <?php endif; ?>
        
        
        <?php $message_footer=get_field('message_footer'); 
        if(!empty($message_footer)) :?>  
        <div class="entry-content">
            <h1>Message (Footer)</h1>
             <?php the_field('message_footer'); ?>            
            </div>
        <br /> <br />
        <?php endif; ?>
        