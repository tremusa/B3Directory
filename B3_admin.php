<!--Make Compatible with fusion builder-->
<style>
  .fusion-builder-update-buttons{
    display: none;
  }
  #footer-thankyou{
    display:none;
  }
  #footer-upgrade{
    display: none;
  }
</style>

<?php
defined('ABSPATH') or die("No script kiddies please");
require_once __DIR__ . '/B3_Parser.php';
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
  <form method="post">
   <input type="text" name="query" placeholder="Your Search Query">
    <input type="submit" value="Submit">
    </form>
    <?php
    if (!empty($_POST["query"])) {
        $_POST["query"] = sanitize_text_field((htmlspecialchars(trim($_POST["query"]))));
        // Reset postdata
        wp_reset_postdata();
        // Create a new query, search by exact title
        $businesslist  = new WP_Query(array("post_type"=>"business","title"=>$_POST[query]));
        if ($businesslist->have_posts()) {
            while ($businesslist->have_posts()) {
                $businesslist->the_post();
                echo "<h1>" . get_the_title() . "</h1>";
                echo "<h3> Categories: </h3>";
                foreach (get_the_category() as $cat) {
                    echo "$cat->category_nicename, ";
                }
                echo "<h3> Tags: </h3>";
                foreach (get_tags() as $tag) {
                    echo "$tag->name ";
                }
            } ?>
            <br>
            <br>

            <form method="POST">
              <strong> Add Category: </strong>
              <input type="text" name="AddCat">
              <input type="hidden" name="query" value="<?php echo(stripslashes($_POST["query"])); ?>">
              <input type="submit" value="Add">
            </form>
            <form method="POST">
              <strong> Remove Category: </strong>
              <input type="text" name="RemoveCat">
              <input type="hidden" name="query" value="<?php echo(stripslashes($_POST["query"])); ?>">
              <input type="submit" value="Remove">
            </form>

            <form method="POST">
              <br>
              <strong> Reassign Category: </strong><br>
              <strong> Category to reassign: </strong>
              <input type="text" name="ReassignCat"><br>
              <strong> Category to assign to: </strong>
              <input type="text" name="AssignCat"><br>
              <input type="hidden" name="query" value="<?php echo(stripslashes($_POST["query"])); ?>">
              <input type="submit" value="Reassign">
            </form>
            <br>
            <br>
            <form method="POST">
              <strong> Add Tag: </strong>
              <input type="text" name="AddTag">
              <input type="hidden" name="query" value="<?php echo(stripslashes($_POST["query"])); ?>">
              <input type="submit" value="Add">
            </form>
            <form method="POST">
              <strong> Remove Tag: </strong>
              <input type="text" name="RemoveTag">
              <input type="hidden" name="query" value="<?php echo(stripslashes($_POST["query"])); ?>">
              <input type="submit" value="Remove">
            </form>
            <form method="POST">
              <br>
              <strong> Reassign Tag: </strong><br>
              <strong> Tag to reassign: </strong>
              <input type="text" name="ReassignTag"><br>
              <strong> Tag to assign to: </strong>
              <input type="text" name="AssignTag"><br>
              <input type="hidden" name="query" value="<?php echo(stripslashes($_POST["query"])); ?>">
              <input type="submit" value="Reassign">
            </form>

            <?php
        } else {
            echo "<h2> No Businesses Found </h2>";
        }
    } ?>
    <h2> Import from CSV </h2>
    <form method="POST" enctype="multipart/form-data">
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
        // Get the full name of the fileecho "<br>";
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
            read_upload();
        }
    }
}
?>
