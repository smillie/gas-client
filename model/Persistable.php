<?php

interface Persistable {
  
  static public function get($id);
  
  static public function getAll();
  
  public function delete();
  
  public function save();
  
}

?>