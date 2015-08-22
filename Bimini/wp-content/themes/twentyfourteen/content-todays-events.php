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
<?php $date = get_field('start_date');
if (!empty($date)) :
    ?>  
    <div class="entry-content">
        <h1>Date</h1>
    <?php //the_field('start_date');     
    $date_arr = explode('-',$date);
    echo $date_arr[1].'/'.$date_arr[2].'/'.$date_arr[0];
    ?>            
    </div>
    <br /> <br />
<?php endif; ?>

<?php $start_time = get_field('start_time');
if (!empty($start_time)) :
    ?>  
    <div class="entry-content">
        <h1>Start Time</h1>
    <?php the_field('start_time'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>

<?php $end_time = get_field('end_time');
if (!empty($end_time)) :
    ?>  
    <div class="entry-content">
        <h1>End Time</h1>
    <?php the_field('end_time'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>       

    <?php $venue = get_field('venue');
    if (!empty($end_time)) :
        ?>  
    <div class="entry-content">
        <h1>Venue</h1>
    <?php the_field('venue'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>       