<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Full Content Template
 *
   Template Name:  Create Poll Page (no sidebar)
 *
 * @file           createpoll-page.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2011 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/dropbox-page.php
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */
if(is_user_logged_in()):
setcookie("TestCookie", $value, time()+3600, "/~rasmus/", "example.com", 1);
get_header(); ?>

<div id="content-full" class="grid col-940">
        
	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
        <?php get_template_part( 'loop-header' ); ?>
        
			<?php responsive_entry_before(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>       
				<?php responsive_entry_top(); ?>

                <?php get_template_part( 'post-meta-page-without-author' ); ?>
                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'responsive')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>


<div>
    <fieldset>
        <form action="" id="pd_form">
            <label for="pd_title"><?php echo CPTITLE; ?></label> <?php echo CPTITLEDESC; ?>
            <input class="required" size="40" name="pd_title" type="text" id="pd_title"/>
            <label for="pd_location"><?php echo CPWHERE; ?></label> <?php echo CPWHEREDESC; ?>
            <input size="40" name="pd_location" type="text" id="pd_location"/>
            <label for="pd_description"><?php echo CPDESC; ?></label> <?php echo CPDESCDESC; ?>
            <textarea name="pd_description" type="text" id="pd_description" cols="70"></textarea>
            <label for="pd_title"><?php echo CPSELDATES; ?></label> <?php echo CPSELDATESDESC; ?>
            <table style="margin: 3px 0px;">
                <tr><th></th><th><?php echo CPDATES; ?></th></tr>
                <tr><td style="width: 300px;"><div id="multiple-date-picker"></div></td><td><div id="selected-dates"></div></td></tr>
		  <tr>
			
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><b><?php echo CPTIME; ?></b><?php echo CPTIMEDESC; ?></td>
		<td><?php echo CPDAY; ?> 1</td>
		<td><?php echo CPDAY; ?> 2</td>
		<td><?php echo CPDAY; ?> 3</td>
		<td><?php echo CPDAY; ?> 4</td>
		<td><?php echo CPDAY; ?> 5</td>
		<td><?php echo CPDAY; ?> 6 </td>
		<td><?php echo CPDAY; ?> 7</td>
		<td><?php echo CPDAY; ?> 8</td>
	</tr>
	<tr>
		<td>hh:mm</td>
		<td><input size="5" name="pd_d1" type="time" id="pd_d1"/></td>
		<td><input size="5" name="pd_d2" type="text" id="pd_d2"/></td>
		<td><input size="5" name="pd_d3" type="text" id="pd_d3"/></td>
		<td><input size="5" name="pd_d4" type="text" id="pd_d4"/></td>
		<td><input size="5" name="pd_d5" type="text" id="pd_d5"/></td>
		<td><input size="5" name="pd_d6" type="text" id="pd_d6"/></td>
		<td><input size="5" name="pd_d7" type="text" id="pd_d7"/></td>
		<td><input size="5" name="pd_d8" type="text" id="pd_d8"/></td>
	</tr>
	
</table>
 	
		  </tr>
            </table>  
	     
          

            <input type="submit" value="<?php echo CPSUBMIT; ?>" name="pd_form_submit" id="pd_form_submit"/>
            <input type="hidden" name="action_name" value="create_poll"/>
        </form>    
    </fieldset>

</div>


                </div><!-- end of .post-entry -->
            
				<?php get_template_part( 'post-data' ); ?>
				               
				<?php responsive_entry_bottom(); ?>      
			</div><!-- end of #post-<?php the_ID(); ?> -->       
			<?php responsive_entry_after(); ?>
            
			<?php //responsive_comments_before(); ?>
			<?php //comments_template( '', true ); ?>
			<?php //responsive_comments_after(); ?>
            
        <?php 
		endwhile; 

		get_template_part( 'loop-nav' ); 

	else : 

		get_template_part( 'loop-no-posts' ); 

	endif; 
	?>  
      
</div><!-- end of #content-full -->

<?php get_footer(); ?>

<?php

else:
get_header();

?>
<div id="content" class="<?php echo implode( ' ', responsive_get_content_classes() ); ?>">
        
We're sorry, you must first <b><a href=".././wp-login.php">log in</a></b> to view this page.

</div><!-- end of #content -->
<?php
get_footer();

endif;?>

