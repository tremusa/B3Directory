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

  function ReassignCategory($assignfrom, $assignto)
  {
    if(get_cat_ID($assignfrom) == 0 | get_cat_ID($assignto) == 0){
        return false;
    }
    $postfindparams = array(
      'numberposts'=>-1,
      'post_type'=>'business',
      'category'=> get_cat_ID($assignfrom)
    );
    $posts_array = get_posts($postfindparams);
    $id_array = array();
    foreach($posts_array as $post){
      array_push($id_array, $post->ID);
    }
    if($id_array == array()){
      return false;
    }
    foreach($id_array as $thisid){
      RemoveCategory($assignfrom, $thisid);
      AddCategory($assignto, $thisid);
    }
    return true;
  }

  function AddTag($tagname, $postid)
  {
      echo "here" . $postid; 
      if(wp_set_post_tags($postid, $tagname, true)!=NULL){
      return true;
      }
      return false;
  }

  function RemoveTag($tagname, $postid)
  { $thistag = get_term_by('name', $tagname, 'post_tag');
    if(wp_remove_object_terms($postid, (int)$thistag->term_id, 'post_tag')){
      return true;
    }
    return false;
  }

  function ReassignTag($assignfrom, $assignto)
  {
    $assignfromid = (int) get_term_by('name', $assignfrom, 'post_tag')->term_id;

    $tagfindparams = array(
      'post_type'=>'business',
      'tag_id'=>$assignfromid
    );
    $tagquery = new WP_Query($tagfindparams);
    if($tagquery->have_posts()){
      $tagposts = $tagquery->posts;
      $postids = wp_list_pluck($tagposts, 'ID');
      foreach($postids as $thisid){
        RemoveTag($assignfrom, $thisid);
        AddTag($assignto, $thisid);
      }
      return true;
    }
    return false;
  }
