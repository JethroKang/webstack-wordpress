<?php
 /*Template Name: New Template
 */
get_header(); ?>



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



<div id="primary">
    <div id="content" role="main">
     <?php $mypost = array( 'post_type' => 'movie_reviews', );
      $loop = new WP_Query( $mypost ); ?>
	  <!-- Cycle through all posts -->
      <?php while ( $loop->have_posts() ) : $loop->the_post();?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	          <header class="entry-header">
                <!-- Display featured image in top-aligned floating div -->
                 <div style="float: top; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                 </div>
				 <!-- Display Title and Author Name -->
				 <strong>Title: </strong><?php the_title(); ?><br />
                 <strong>Director: </strong>
                 <?php echo esc_html( get_post_meta( get_the_ID(), 'movie_director', true ) ); ?>
                 <br />
				<strong>Genre: </strong>
                <?php  
				the_terms( $post->ID, 'movie_reviews_movie_genre' ,  ' ' );
                    ?>
<br />
                 <!-- Display yellow stars based on rating -->
                <strong>Rating: </strong>
                <?php
                $nb_stars = intval( get_post_meta( get_the_ID(), 'movie_rating', true ) );
                for ( $star_counter = 1; $star_counter <= 5; $star_counter++ ) {
                    if ( $star_counter <= $nb_stars ) {
                        echo '<img src="' . plugins_url( 'Movie-Reviews/images/icon.png' ) . '" />';
                    } else {
                        echo '<img src="' . plugins_url( 'Movie-Reviews/images/grey.png' ). '" />';
                    }
                }
                ?>
	          </header>
			  <!-- Display movie review contents -->
	          <div class="entry-content">
	               <?php the_content(); ?>
              </div>
	          <hr/>
	     </article>
   <?php endwhile;  ?>
   </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>


