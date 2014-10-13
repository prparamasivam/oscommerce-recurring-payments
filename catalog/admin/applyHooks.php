<?php
 ini_set('display_errors',1);
 ini_set('display_startup_errors',1);
 error_reporting(-1);
  // plugin directory
  $hook_dir = "../hooks/admin/catalog/";
  
  // get all plugin files
  $hook_files = glob($hook_dir."*.php");
  
  // array saving all our hooks on 'the_content'
  $hooks['the_content'] = array();
  
  // add_filter functionality
  function add_filter($on, $func) {
    global $hooks;
    array_push($hooks[$on], $func);
  }
  
  // include plugin files
  foreach($hook_files as $hook_file) {
  echo $hook_file;
    require_once($hook_file);
  }
  $content = "Default text";
  // implement the hooks
  foreach($hooks['the_content'] as $hook) {
    $content = call_user_func($hook, $content);
  }
  
  echo $content;
  
?>
