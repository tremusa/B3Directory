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
        // Reset postdata
        wp_reset_postdata();
        // Create a new query, search by exact title
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
    // If the user uploaded a file via POST request
    if (!empty($_FILES['CSV_to_parse']['name'])) {
        // If there is an error uploading the file
        if ($_FILES['CSV_to_parse']['error'] > 0) {
            //stop everything, give an error
            die('An error ocurred when uploading.');
        }
        // If this point is reached data is recieved
        echo "Data Recieved..<br>";
        // Get the full name of the file
        $csvfile = $_FILES['CSV_to_parse']['name'];
        // take everything after the . and make it lower case (this is the file ext)
        $FileType = strtolower(pathinfo($csvfile, PATHINFO_EXTENSION));
        // Check that this is a csv
        if ($FileType!="csv") {
            // If this isn't a csv, die with an error
            die("<br> Filetype not supported. Make sure you are uploading a .csv file <br>");
        } else {
            // The file is supported and we can now upload, use an if to make sure this went properly
            if (move_uploaded_file(($_FILES['CSV_to_parse']['tmp_name']), plugin_dir_path(__FILE__).'/uploads/file.csv')) {
                echo "File Upload Successful <br>";
            } else {
                // If there is an issue moving the file out of tmp die
                die("File Upload Failed <br>");
            }
            // Read whole file into a string
            $filestring = file_get_contents (plugin_dir_path(__FILE__).'/uploads/file.csv');
            echo(nl2br($filestring)); 
        }
    }
}
?>
