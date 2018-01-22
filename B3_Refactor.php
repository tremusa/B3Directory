<?php
  defined('ABSPATH') or die("No script kiddies please");
  function AddCategory($catname, $postid){
    // Create Category if it does not exist
      if(get_cat_ID($catname)==0){
        echo "<br> Category Not Found,";
        if (wp_create_category($catname) !=0) {
            echo "<br> Category $catname Successfully Created <br>";
        }else{
           die("Error Creating Category");
        }
      }
      // At this point the category is created
    $category = array(get_cat_ID($catname));
    // pass to wordpress with append flag
    $category_set  = wp_set_post_categories($postid, $category, true);
    if($category_set==array()){
      die("Error Adding Category");
    }
  }
  function RemoveCategory(){

  }
  function ReassignCategory(){

  }
  function AddTag(){

  }
  function RemoveTag(){

  }
  function ReassignTag(){

  }
?>
