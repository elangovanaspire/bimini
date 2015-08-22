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
<?php $month = get_field('month');
if (!empty($month)) :
    ?>  
    <div class="entry-content">
        <h1>Month</h1>
    <?php the_field('month'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>

<?php $year = get_field('year');
if (!empty($year)) :
    ?>  
    <div class="entry-content">
        <h1>Year</h1>
    <?php the_field('year'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>

<?php $day = get_field('day');
if (!empty($day)) :
    ?>  
    <div class="entry-content">
        <h1>Day</h1>
    <?php the_field('day'); ?>            
    </div>
    <br /> <br />
<?php endif; ?>