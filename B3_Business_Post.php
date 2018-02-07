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
    private $cats = array();
    private $tags = array();
    private $about ='';
    private $notes ='';
    private $burl ='';
    public function get_cats(){
      return $this->$cats;
    }
    public function get_tags(){
      return $this->$tags;
    }
    public function get_name(){
      return $this->$name;
    }
    public function get_notes(){
      return $this->$notes;
    }
    public function get_url(){
      return $this->$burl;
    }
    public function get_about(){
      return $this->$about;
    }
    public function get_city(){
      return $this->$city;
    }
    public function get_state(){
      return $this->$state;
    }
    public function get_sma(){
      return $this->$sma;
    }
    public function get_content(){

      $content = '<p class="B3_City"><div class="B3_City_Label"> City:</div> ';
      $content .= $this->$city;
      $content .= '</p>';
      $content .= '<p class="B3_State"><div class="B3_State_Label"> State:</div> ';
      $content .= $this->$state;
      $content .= '</p>';
      $content .= '<p class="B3_SMA"><div class="B3_SMA_Label"> Products Made In the USA:</div> ';
      $content .= $this->$sma;
      $content .= '</p>';
      $content .= '<p class="B3_AboutUs"><div class="B3_About_Label"> About Us:</div> ';
      $content .= $this->$about;
      $content .= '</p>';
      $content .= '<p class="B3_Notes"><div class="B3_Notes_Label"> Notes:</div> ';
      $content .= $this->$notes;
      $content .= '</p>';
      $content .= '<p class="B3_URL"><div class="B3_URL_Label"> URL:</div> <a href=\''.$this->$url.'\'>';
      $content .= $this->$url;
      $content .= '</a></p>';

    }
    public function add_cats($cat){
      array_push($this->$cats, $cat);
    }
    public function add_tags($tag){
      array_push($this->$tags, $tag);
    }
    public function set_name($newname){
      $this->$name = $newname;
    }
    public function set_notes($newnotes){
      $this->$notes = $newnotes;
    }
    public function set_url($newurl){
      $this->$burl = $newurl;
    }
    public function set_about($newabout){
      $this->$about = $newabout;
    }
    public function set_city($newcity){
      $this->$city = $newcity;
    }
    public function set_sma($sma_val){
      $this->$sma = $sma_val;
    }
    public function add_update(){
      if(post_exists($this->$name)){
        if(post_exists($this->$name, $this->get_content())){
          $thispostobject = get_page_by_title( $this->$name, 'OBJECT', 'business');
          $thispostid = $thispostobject->$ID;

        }else{

        }
      }
    }
  }
?>
