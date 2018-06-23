 <?php
/*
Template Name: Test

*/
?>
<?php get_header(); ?>



 <?php
 $taxonomy = 'movie_reviews_movie_genre';
 $orderby = 'name';
 $show_count = 0; // 1 为是, 0 为否
 $pad_counts = 0; // 1 为是, 0 为否
 $hierarchical = 1; // 1 为是, 0 为否
 $title = '';

 $args = array(
     'taxonomy' => $taxonomy,
     'orderby' => $orderby,
     'show_count' => $show_count,
     'pad_counts' => $pad_counts,
     'hierarchical' => $hierarchical,
     'title_li' => $title
 );
 ?>

 <ul>
     <?php wp_list_categories( $args ); ?>
 </ul>



<section id="primary">
<div id="content" role="main" style="width: 70%">
<?php if ( have_posts() ) : ?>
          <header class="page-header">
          <h1 class="page-title">Movie Reviews</h1>
         </header>
         <table>
        <!-- Display table headers -->
        <tr>
        <th style="width: 200px"><strong>Title</strong></th>
        <th><strong>Director</strong></th>
        </tr>
        <!-- Start the Loop -->
        <?php while ( have_posts() ) : the_post(); ?>
                 <!-- Display review title and author -->
                <tr>
               <td><a href="<?php the_permalink(); ?>">
               <?php the_title(); ?></a></td>
              <td><?php echo esc_html( get_post_meta( get_the_ID(), 'movie_director', true ) ); ?></td>
              </tr>
         <?php endwhile; ?>
		 
<!-- Display page navigation -->
		 
</table>
<?php global $wp_query;
if ( isset( $wp_query->max_num_pages ) && $wp_query->max_num_pages > 1 ) { ?>
    <nav id="<?php echo $nav_id; ?>">
   <div class="nav-previous"><?php next_posts_link( '<span class="meta-nav">&larr;</span> Older reviews'); ?></div>
   <div class="nav-next"><?php previous_posts_link( 'Newer reviews <span class= "meta-nav">&rarr;</span>' ); ?></div>
  </nav>
 <?php };
endif; ?>
  </div>
</section>
<?php get_footer(); ?>