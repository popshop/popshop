<?php

// Uber-minimalist blog template


$args = array('post_type' => 'post',
              'posts_per_page' => 10);

$wp_query = new WP_Query($args);

while (have_posts())
{
    the_post();
    
    include('post.php');
}