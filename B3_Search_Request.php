<?php
class B3_Search_Request{
  private $map = array();
  public function get_map(){
    return $this->$map;
  }
  public function construct_params(){
    $constructed = '';
    foreach($this->$map as $param=>$val){
      $constructed .='&'.$param.'='.$val;
    }
    return $constructed;
  }
  public function parse_this_request($request){
    foreach($request as $thiskey=>$thisval){
      $this->$map[htmlspecialchars($thiskey, ENT_QUOTES)] = htmlspecialchars($thisval, ENT_QUOTES);
    }
  }
  public function add_param($param, $val){
    $this->$map[htmlspecialchars($param, ENT_QUOTES)] = htmlspecialchars($val, ENT_QUOTES);
  }
  public function param_is_set($param){
    if(isset($this->$map["$param"])){
      return true;
    }
    return false;
  }
  public function get_param($param){
    return $this->$map[$param];
  }
}
?>
