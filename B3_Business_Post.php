<?php
defined('ABSPATH') or die("No script kiddies please");
require_once __DIR__ . '/B3_Refactor.php';
  class Business_Post{
    private static $post_type = 'business';
    private static $post_status = 'publish';
    private $name = '';
    private $city = '';
    private $state = '';
    private $sma = '';
    private $cats = NULL;
    private $tags = NULL;
    private $about ='';
    private $notes ='';
    private $burl ='';
    public function get_cats(){
      return $this->cats;
    }
    public function get_tags(){
      return $this->tags;
    }
    public function get_name(){
      return $this->name;
    }
    public function get_notes(){
      return $this->notes;
    }
    public function get_url(){
      return $this->burl;
    }
    public function get_about(){
      return $this->about;
    }
    public function get_city(){
      return $this->city;
    }
    public function get_state(){
      return $this->state;
    }
    public function get_sma(){
      return $this->sma;
    }
    public function get_content(){

      $content = '<p class="B3_City"><div class="B3_City_Label"> City:</div> ';
      echo "In content city = " . $this->city;
      $content .= $this->city;
      $content .= '</p>';
      $content .= '<p class="B3_State"><div class="B3_State_Label"> State:</div> ';
      $content .= $this->state;
      $content .= '</p>';
      $content .= '<p class="B3_SMA"><div class="B3_SMA_Label"> Products Made In the USA:</div> ';
      $content .= $this->sma;
      $content .= '</p>';
      $content .= '<p class="B3_AboutUs"><div class="B3_About_Label"> About Us:</div> ';
      $content .= $this->about;
      $content .= '</p>';
      $content .= '<p class="B3_URL"><div class="B3_URL_Label"> URL:</div> <a href=\''.$this->burl.'\'>';
      $content .= $this->burl;
      $content .= '</a></p>';
      return $content;

    }
    public function add_cats($cat){
      if($this->cats == NULL){
        $this->cats = array((int)get_cat_ID($cat));
      }else{
       $this->cats[]=(int)get_cat_ID($cat);
     }
    }
    public function add_tags($tag){
      if($this->tags == NULL){
        $this->tags = array($tag);
      }else{
      $this->tags[]=$tag;
      }
    }
    public function set_name($newname){
      $this->name = $newname;
    }
    public function set_notes($newnotes){
      $this->notes = $newnotes;
    }
    public function set_url($newurl){
      $this->burl = $newurl;
    }
    public function set_about($newabout){
      $this->about = $newabout;
    }
    public function set_city($newcity){
      $this->city = $newcity;
    }
    public function set_sma($sma_val){
      $this->sma = $sma_val;
    }
    public function set_state($newstate){
      $this->state = $newstate;
    }
    public function add_update(){
      if(post_exists($this->name)){
        $thispostobject = get_page_by_title( $this->name, 'OBJECT', 'business');
        $thispostid = $thispostobject->ID;
        if(post_exists($this->name, $this->get_content())){
          wp_set_post_categories( $thispostid, $this->get_cats());
          wp_set_post_tags($thispostid, $this->get_tags());
          echo "Update Successful";
        }
        else{
          echo "updating";
          $postupdatearray = array(
            'ID'=> $thispostid,
            'post_title'   => $this->get_name(),
            'post_content' => $this->get_content(),
          );
          if($this->cats){
            $postupdatearray['post_category'] = $this->get_cats();
          }
          if($this->tags){
            $postupdatearray['tags_input'] = $this->get_tags();
          }

          if(!is_wp_error(wp_update_post($postupdatearray))){
            echo "Update Successful";
          }
          else{
            echo "Update Failed";
          }
        }
      }else{
        echo "<pre>";
        var_dump($this);
        $postinsertarray = array(
          'post_title'   => $this->get_name(),
          'post_content' => $this->get_content(),
          'post_status' => self::$post_status,
          'post_type' => self::$post_type,
        );
        if($this->cats){
          echo "here 1997";
          $postinsertarray['post_category'] = $this->get_cats();
        }
        if($this->tags){
          $postinsertarray['tags_input'] = $this->get_tags();
        }
        $thisupload = wp_insert_post($postinsertarray);
        if(is_wp_error($thisupload)){
          die($thisupload->get_error_message());
        }
      }
    }

  }
?>
