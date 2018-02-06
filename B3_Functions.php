<?php
defined('ABSPATH') or die("No script kiddies please");
require_once __DIR__ . '/B3_admin.php';
require_once __DIR__ . '/B3_Search_Request.php';

/*
Plugin Name:  B3 Directory
Plugin URI:   https://github.com/tstopak/B3directory/
Description:  The Blog Based Business Directory. This plugin allows for blog posts
              to be used as listings for businesses. More to come...
Version:      .1
Author:       Thomas Stopak
Author URI:   http://thomasstopak.com
License:      GNU GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:
Domain Path:
*/
// Register Activation function with activation hook
register_activation_hook(__FILE__, 'activate_b3()');
function activate_b3()
{
}
// On init, create custom post type for B3
add_action('init', 'create_business_post_type');
add_action('init', 'register_default_shortcode');
add_action('admin_menu', 'b3_menu');
//Function to create Business Post type
function create_business_post_type()
{
    register_post_type(
      'business',
      [
  'labels'=> ['name' => __('Businesses'),
              'singular_name' => __('Business'),],
  'public'=> true,'has_archive' => true,
  'supports'=> array('title', 'editor', 'thumbnail'),
  'taxonomies' => array('post_tag', 'category')
  ]
   );
}

// Create Admin Menu
function b3_menu()
{
    add_menu_page('B3', 'B3 Directory Menu', 'update_core', 'b3mainmenu', 'b3_menu_html');
}

// Register our Admin Menu with the Hook that creates the menu

function new_excerpt_more($more)
{
    return '...';
}

add_filter('excerpt_more', 'new_excerpt_more');

function call_wp_query($atts)
{
    // Convert All Input to lower case
    $atts = array_change_key_case($atts, CASE_LOWER);
    // Sanitize input going into the query to prevent php injection
    $atts['tag'] = sanitize_title_for_query($atts['tag']);
    $atts['cat'] = sanitize_title_for_query($atts['cat']);
    $atts['post_per_page'] = sanitize_title_for_query($atts['post_per_page']);
    $atts['search'] = sanitize_title_for_query($atts['search']);
    //Set the supported attributes, and assign them to the attributes provided
    $a = shortcode_atts(array(
      'tag'=>'',
      'cat'=>'',
      'post_per_page' => '-1',
      'search' => ''

    ), $atts);
    $args=array('post_type'=> 'business', 'posts_per_page'=>-1);

    //If the tag is not empty, use it to create a query with tag support
    if ($a['tag']!='') {
        $args['tag'] = $a['tag'];
    }
    if ($a['cat']!='') {
        $cat_id = get_cat_ID($a['cat']);
        $args['cat'] = (int) $cat_id;
    }
    if ($atts['post_per_page'] != '' && $a ['post_per_page'] != '-1') {
        $args['posts_per_page'] = (int) $a['post_per_page'];
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args['paged']=$paged;
    }
    if($a['search']='true'){
      search_menu();
    }
    if(isset($_GET['per_page']) && $_GET['per_page'] != 'all'){
      $args['posts_per_page'] = (int) htmlspecialchars($_GET['per_page'], ENT_QUOTES);
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $args['paged']=$paged;
    }
    if(isset($_GET['sort'])){
      if($_GET['sort'] == 'asc'){
        $args['order'] = 'ASC';
        $args['orderby'] = 'title';
      }
      if($_GET['sort'] == 'desc'){
        $args['order'] = 'DESC';
        $args['orderby'] = 'title';
      }
    }
    if(isset($_GET['search'])){
      $search_term = htmlspecialchars($_GET['search'], ENT_QUOTES);
      $args['s'] = $search_term;
    }
    //use constructed args array to query businesses
    query_businesses($args);
}

function query_businesses($args)
{
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post(); ?><div class="b3-post"><a href='<?php the_permalink()?>'<?php
            echo '<h1 class="b3 title">' . get_the_title() . '</h1>'; ?>
            </a>
            <div class="b3-logo">
            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail();
            } ?>
          </div>
            <?php
            echo '<p class="b3-excerpt">' . the_excerpt(); ?>
            <a class ='b3-link' href='<?php the_permalink()?>'>Read More...</a></p></div>
            <?php
        }
        previous_posts_link('&laquo; Previous ', $query->max_num_pages);
        next_posts_link('Next &raquo;', $query->max_num_pages);
        //Restore original Post Data
        wp_reset_postdata();
    } else {
        echo '<h1> No Posts Found </h1>';
        // no posts found
    }
}
function register_default_shortcode()
{
    add_shortcode('businessdirectory', 'call_wp_query');
}
function search_menu()
{
  $request_origin = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES);
  $request_origin = strtok($request_origin, '?');
  $request_origin .= "?";
  $this_request = new B3_Search_Request();
  $this_request->parse_this_request($_GET);
  ?>
  <form method="get">
    <h2> Search: </h2>
    <input type="text" name="search" <?php if($this_request->param_is_set('search'))
      echo "value='" . $this_request->get_param('search') . "'" ;?>><br><br>
    <input type="submit" value="Search"/>
    <?php if (isset($_GET['sort'])): ?>
    <input type ="hidden" name="sort" value='<?php echo htmlspecialchars($_GET['sort'],  ENT_QUOTES);?>'>
  <?php endif; ?>
    Posts Per Page:
    <select name="per_page">
      <?php if(!isset($_GET['per_page'])|| $_GET['per_page']==''): ?>
      <option value="" <?php if($_GET['per_page']=='') echo "selected='selected'";?>> </option>
      <?php endif; ?>
      <option value="5" <?php if($_GET['per_page'] == 5) echo "selected='selected'";?>> 5 </option>
      <option value="10" <?php if($_GET['per_page'] == 10) echo "selected='selected'";?>> 10 </option>
      <option value="15" <?php if($_GET['per_page'] == 15) echo "selected='selected'";?>> 15 </option>
      <option value="20" <?php if($_GET['per_page'] == 20) echo "selected='selected'";?>> 20 </option>
      <option value="all"  <?php if($_GET['per_page'] == 'all') echo "selected='selected'";?>> All </option>
    </select>
  </form>
  <p> Sort:
  <a href='<?php $this_request->add_param("sort", "asc");
    echo $request_origin . $this_request->construct_params(); ?>'>
     A-Z
  </a>
  <a href='<?php $this_request->add_param("sort", "desc");
    echo $request_origin . $this_request->construct_params(); ?>'>
     Z-A
  </a>
</p>
  <?php
}
?>
