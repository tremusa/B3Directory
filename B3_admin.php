<!--Make Compatible with fusion builder-->
<style>
  .fusion-builder-update-buttons{
    display: none;
  }
</style>

<?php

//HTML output for Admin Menu
function b3_menu_html()
{

  //Create a Query to pull up all posts with business post type
    $menu_query = new WP_Query(array('post_type'=>'business'));
    // Create a variable to refer to the post we just got with that query
    $businessposts = $menu_query->posts;
    // Save all business post IDS to run them through wp_get_object_terms
    $businesspostids = wp_list_pluck($businessposts, 'ID');
    // Save all tags to a variable
    $businesstagterms = wp_get_object_terms($businesspostids, 'post_tag');
    // If the user isn't admin they get nothing... and like it.
    if (!current_user_can('update_core')) {
        return;
    } ?>
    <div class="b3menuwrapper">
    <h1> B3 Directory settings </h1>
    <h2> Your Business Tags </h2>
    <?php
      echo '<ul>';
    foreach ($businesstagterms as $term) {
        echo '<li>' . get_term($term)->name . '</li>';
    }
    echo '</ul>'; ?>
    <h2> Your Business Categories </h2>
    <?php
      wp_reset_postdata();
    //Create a Query to pull up all posts with business post type
    $cat_query = new WP_Query(array('post_type'=>'business'));
    // Create a variable to refer to the post we just got with that query
    $catposts = $cat_query->get_posts();
    // Save all business post IDS to run them through wp_get_object_terms
    $catpostids = wp_list_pluck($catposts, 'ID');
    // Save all tags to a variable
    $businesscatterms = wp_get_object_terms($catpostids, 'category');
    echo '<ul>';
    foreach ($businesscatterms as $t2) {
        echo '<li>' . get_term($t2)->name . '</li>';
    }
    echo '</ul>'; ?>
    <h2> Search Your Businesses </h2><br>
    <form action="" method="POST">
    <input type="text" name="query" placeholder="Your Search Query">
    <input type="submit" value="Submit">
    </form>
    <?php
    if (!empty($_POST[query])) {
        wp_reset_postdata();
        $businesslist  = new WP_Query(array("post_type"=>"business","title"=>$_POST[query]));
        if ($businesslist->have_posts()) {
            while ($businesslist->have_posts()) {
                $businesslist->the_post();
                echo "<h1>" . get_the_title() . "</h1><br>";
            }
        } else {
            echo "<h2> No Businesses Found </h2>";
        }
    } ?>
    <h2> Import from CSV </h2>
    <form action = "" method="POST" enctype="multipart/form-data">
      <input type="file" name="CSV_to_parse" id="CSV" /><br><br>
      <input type="submit" value="Submit" />
    </form>
    <?php
    if(!empty($_FILES["CSV_to_parse"]['name'])){
      echo "Data Recieved..<br>";
      $csvfile = $_FILES['CSV_to_parse']['name'];
      $FileType = strtolower(pathinfo($csvfile,PATHINFO_EXTENSION));
      if($FileType!="csv"){
        echo "<br> Filetype not supported. Make sure you are uploading a .csv file <br>";
      }
    }
}

?>
