<?php
  defined('ABSPATH') or die("No script kiddies please");
  function AddCategory($catname, $postid)
  {
      // Create Category if it does not exist
      if (get_cat_ID($catname)==0) {
          echo "<br> Category Not Found,";
          if (wp_create_category($catname) !=0) {
              echo "<br> Category $catname Successfully Created <br>";
          } else {
              return false;
          }
      }
      // At this point the category is created
      $category = array(get_cat_ID($catname));
      // pass to wordpress with append flag
      $category_set  = wp_set_post_categories($postid, $category, true);
      if ($category_set==array()) {
          return false;
      }
      return true;
  }


  function RemoveCategory($catname, $postid)
  {
      if (!has_category($catname, $postid)) {
          return false;
      }
      if (wp_remove_object_terms($postid, get_cat_ID($catname), 'category')) {
          return true;
      }
  }

  function ReassignCategory($bcat)
  {
  }

  function AddTag($tagname, $postid)
  {
      if(wp_set_post_tags($postid, $tagname, true)!=NULL){
      return true;
      }
      return false;
  }

  function RemoveTag($tagname, $postid)
  { $thistag = get_term_by('name', $tagname, 'post_tag');
    echo "here2";
    if(wp_remove_object_terms($postid, (int)$thistag->term_id, 'post_tag')){
      return true;
    }

    return false;
  }

  function ReassignTag()
  {
  }
