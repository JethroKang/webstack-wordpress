<?php
/*
Plugin Name: Movie Reviews
Plugin URI: http://wp.tutsplus.com/
Description: Declares a plugin that will create a custom post type
Version: 1.0
Author: Soumitra Chakraborty
Author URI: http://wp.tutsplus.com/
License: GPLv2
*/

// CREATE CUSTOM POST TYPES

add_action( 'init', 'create_movie_review' );


function create_movie_review() {
register_post_type( 'movie_reviews',
array(
'labels' => array(
'name' => 'Movie Reviews',
'singular_name' => 'Movie Review',
'add_new' => 'Add New',
'add_new_item' => 'Add New Movie Review',
'edit' => 'Edit',
'edit_item' => 'Edit Movie Review',
'new_item' => 'New Movie Review',
'view' => 'View',
'view_item' => 'View Movie Review',
'search_items' => 'Search Movie Reviews',
'not_found' => 'No Movie Reviews found',
'not_found_in_trash' =>
'No Movie Reviews found in Trash',
'parent' => 'Parent Movie Review'
),
'public' => true,
'menu_position' => 15,
'supports' =>
array( 'title', 'editor', 'comments',
'thumbnail',  ),
'taxonomies' => array( '' ),
'menu_icon' =>
plugins_url( 'images/image.png', __FILE__ ),
'has_archive' => true
)
);


//CREATE CUSTOM TAXONOMIES

}

add_action( 'init', 'create_my_taxonomies', 0 );


function create_my_taxonomies(){
register_taxonomy(
          'movie_reviews_movie_genre',
          'movie_reviews', array(
                                        'labels' => array(
                                                                'name' => 'Movie Genre',
                                                                'add_new_item' => 'Add New Movie Genre',
                                                                'new_item_name' => "New Movie Type Genre"
                                                                  ),
                                       'show_ui' => true,
                                       'show_tagcloud' => true,
                                       'hierarchical' => true
                                               )
	                           );
}




//CREATE CUSTOM META BOXES


add_action( 'admin_init', 'my_admin' );




function my_admin() {
add_meta_box( 'movie_review_meta_box',
'Movie Review Details',
'display_movie_review_meta_box',
'movie_reviews', 'normal', 'high' );
}

function display_movie_review_meta_box( $movie_review ) {
// Retrieve current name of the Director and Movie Rating based on review ID
$movie_director =
esc_html( get_post_meta( $movie_review->ID,
'movie_director', true ) );
$movie_rating =
intval( get_post_meta( $movie_review->ID,
'movie_rating', true ) );
?>
<table>
<tr>
<td style="width: 100%">Movie Director</td>
<td><input type="text" size="80"
name="movie_review_director_name"
value="<?php echo $movie_director; ?>" /></td>
</tr>
<tr>
<td style="width: 150px">Movie Rating</td>
<td>
<select style="width: 100px"
name="movie_review_rating">
<?php
// Generate all items of drop-down list
for ( $rating = 5; $rating >= 1; $rating -- ) {
?>
<option value="<?php echo $rating; ?>"
<?php echo selected( $rating,
$movie_rating ); ?>>
<?php echo $rating; ?> stars
<?php } ?>
</select>
</td>
</tr>
</table>
<?php }


add_action( 'save_post',
'add_movie_review_fields', 10, 2 );


function add_movie_review_fields( $movie_review_id,
$movie_review ) {
// Check post type for movie reviews
if ( $movie_review->post_type == 'movie_reviews' ) {
// Store data in post meta table if present in post data
if ( isset( $_POST['movie_review_director_name'] ) &&
$_POST['movie_review_director_name'] != '' ) {
update_post_meta( $movie_review_id, 'movie_director',
$_POST['movie_review_director_name'] );
}
if ( isset( $_POST['movie_review_rating'] ) &&
$_POST['movie_review_rating'] != '' ) {
update_post_meta( $movie_review_id, 'movie_rating',
$_POST['movie_review_rating'] );
}
}
}

//INCLUDE  CUSTOM TEMPLATE FILE


add_filter( 'template_include',
'include_template_function', 1 );


function include_template_function( $template_path ) {
if ( get_post_type() == 'movie_reviews' ) {
if ( is_single() ) {
// checks if the file exists in the theme first,
// otherwise serve the file from the plugin
if ( $theme_file = locate_template( array
( 'single-movie_reviews.php' ) ) ) {
$template_path = $theme_file;
} else {
$template_path = plugin_dir_path( __FILE__ ) . '/single-movie_reviews.php';
     }
  } 
    elseif ( is_archive() ) {
                if ( $theme_file = locate_template( array ( 'archive-movie_reviews.php' ) ) ) {
$template_path = $theme_file;
}    else { $template_path = plugin_dir_path( __FILE__ ) . '/archive-movie_reviews.php';

           }
      }
}
return $template_path;
}

// CREATE COLUMNS IN CUSTOM POST TYPE LISTING

add_filter( 'manage_edit-movie_reviews_columns', 'my_columns' );

function my_columns( $columns ) {
          $columns['movie_reviews_director'] = 'Director';
          $columns['movie_reviews_rating'] = 'Rating';
		 
unset( $columns['comments'] );
return $columns;
}
add_action( 'manage_posts_custom_column', 'populate_columns' );

function populate_columns( $column ) 
{
                     if ( 'movie_reviews_director' == $column ) {
                                 $movie_director = esc_html( get_post_meta( get_the_ID(),'movie_director', true ) );
                                 echo $movie_director;
                                                                                             } 
					 elseif ( 'movie_reviews_rating' == $column ) {
                                  $movie_rating = get_post_meta( get_the_ID(), 'movie_rating', true );
                                 echo $movie_rating . ' stars';
                                                                                                     } 
					
}

//SORT COLUMNS
add_filter( 'manage_edit-movie_reviews_sortable_columns', 'sort_me' );


function sort_me($columns) {

             $columns['movie_reviews_director'] = 'movie_reviews_director';
             $columns['movie_reviews_rating'] = 'movie_reviews_rating';
			 
			 
return $columns;
}


add_filter( 'request', 'column_orderby' );

function column_orderby ($vars ) {
                if ( !is_admin() )
                return $vars;
               if ( isset( $vars['orderby'] ) && 'movie_reviews_director' == $vars['orderby'] ) {
                     $vars = array_merge( $vars, array( 'meta_key' => 'movie_director', 'orderby' => 'meta_value' ) );
                                                                                                                                         } 
			  elseif ( isset( $vars['orderby'] ) && 'movie_reviews_rating' == $vars['orderby'] ) {
			         $vars = array_merge( $vars, array( 'meta_key' => 'movie_rating', 'orderby' => 'meta_value_num' ) );
}


return $vars;
}


// CREATE FILTERS WITH CUSTOM TAXONOMIES


add_action( 'restrict_manage_posts','my_filter_list' );


function my_filter_list() {
               $screen = get_current_screen();
                global $wp_query;
                if ( $screen->post_type == 'movie_reviews' ) {
                          wp_dropdown_categories(array(
						'show_option_all' => 'Show All Movie Genres',
						'taxonomy' => 'movie_reviews_movie_genre',
						'name' => 'movie_reviews_movie_genre',
						'orderby' => 'name',
						'selected' =>( isset( $wp_query->query['movie_reviews_movie_genre'] ) ?
						$wp_query->query['movie_reviews_movie_genre'] : '' ),
					  'hierarchical' => false,
					  'depth' => 3,
					  'show_count' => false,
					 'hide_empty' => true,
																								)
					);
			}
}

add_filter( 'parse_query','perform_filtering' );

function perform_filtering( $query )
 {
              $qv = &$query->query_vars;
             if (( $qv['movie_reviews_movie_genre'] ) && is_numeric( $qv['movie_reviews_movie_genre'] ) ) {
                    $term = get_term_by( 'id', $qv['movie_reviews_movie_genre'], 'movie_reviews_movie_genre' ); 
					$qv['movie_reviews_movie_genre'] = $term->slug;
}
}