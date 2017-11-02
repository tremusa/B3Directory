<?php
require_once __DIR__ . '/B3_admin.php';
/*
Plugin Name:  B3 Directory
Plugin URI:   # None At the Moment
Description:  The Blog Based Business Directory. This plugin allows for blog posts
              to be used as listings for businesses. More to come...
Version:      .1
Author:       Thomas Stopak
Author URI:   http://thomasstopak.com
License:      GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:
Domain Path:
*/
// Register Activation function with activation hook
register_activation_hook(__FILE__, 'activate_b3()');
function activate_b3(){
}
// On init, create custom post type for B3
add_action('init', 'create_business_post_type');
add_action('init', 'register_default_shortcode');
add_action('admin_menu', 'b3_menu');
//Function to create Business Post type
function create_business_post_type(){
  register_post_type('business',[
  'labels'=> ['name' => __('Businesses'),
              'singular_name' => __('Business'),],
  'public'=> true,'has_archive' => true,
  'supports'=> array('title', 'editor', 'thumbnail'),
  'taxonomies' => array('post_tag', 'category')
  ]
   );
}

// Create Admin Menu
function b3_menu(){
  add_menu_page( 'B3', 'B3 Directory Menu', 'update_core', 'b3mainmenu', 'b3_menu_html');
}

// Register our Admin Menu with the Hook that creates the menu

function new_excerpt_more( $more ) {
    return '...';
}

add_filter('excerpt_more', 'new_excerpt_more');

function call_wp_query($atts){
  // Convert All Input to lower case
    $atts = array_change_key_case($atts, CASE_LOWER);
  // Sanitize input going into the query to prevent XSS
    $atts['tag'] = sanitize_title_for_query($atts['tag']);
    //Set the supported attributes, and assign them to the attributes provided
    $a = shortcode_atts( array(
      'tag'=>''
    ), $atts);
    //If the tag is not empty, use it to create a query with tag support
    if($a['tag']!=''){
    $args = array('post_type' => 'business', 'tag' => $a['tag']);
    query_businesses($args);
  }
  //else give the default back.
  else{
    $args = array('post_type' => 'business');
    query_businesses($args);
    }
}

function query_businesses($args){
  $query = new WP_Query($args);
  if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            ?><div class="b3-post"><a href='<?php the_permalink()?>'<?php
            echo '<h1 class="b3 title">' . get_the_title() . '</h1>';
            ?>
            </a>
            <div class="b3-logo">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail();
            }
            ?>
          </div>
            <?php
            echo '<p class="b3-excerpt">' . the_excerpt();
            ?>
            <a class ='b3-link' href='<?php the_permalink()?>'>Read More...</a></p></div>
            <?php
           }
   //Restore original Post Data
  wp_reset_postdata();
  } else {
    echo '<h1> Query failed </h1>';
  // no posts found
  }
}

function register_default_shortcode(){
    add_shortcode('businessdirectory','call_wp_query');
}

?>
