<?php 

// Uber-minimalist page template

// @see http://codex.wordpress.org/The_Loop


$post_id = get_the_ID();

// check if page == blog
$blog = get_page_by_title('Blog');
$blog_id = $blog->ID;
if ($post_id == $blog->ID) {
    include('blog.php');
    return;
}


?>


<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="page">
    <div class="fbspinedot"></div>
    
    <h2><?php the_title(); ?></h2>
    
    <div class="entry">
        <?php the_content(); ?>
    </div>

</div>

<?php endwhile; else: ?>

<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
