<?php
/**
 * Created by IntelliJ IDEA.
 * User: paulk
 * Date: 30/10/15
 * Time: 3:26 PM
 */

$action = null;
$alias = null;
$message = null;

if(!empty($_GET['action'])) {
  $action = $_GET['action'];
  if(!empty($_GET['alias'])) {
    $alias = $_GET['alias'];
  }
  if($action == 'login') {
    if($alias != null) {
      $output = shell_exec($command . "@$alias uli");
      $message = "Click the following link to login <a href='$output' target='_blank'>$output</a>";
    }
  }

  if($action == 'status') {
    if($alias != null) {
      $output = shell_exec($command . "@$alias status");
      $message = "$output";
    }
  }

  if($action == 'clear') {
    if($alias != null) {
      $output = shell_exec($command . "@$alias cc all");
      $message = "$output";
    }
  }

  if($action == 'update_prod_list') {
    $api_url = $base_api_url."/api/v1/sites?limit=999";
    $api_creds = " -v -u \"".$user_name."\":".$api_key;
    $url = $api_url.$api_creds;
    $output = shell_exec("curl -s -L".$api_creds." ".$api_url);
    $decoded_output = json_decode($output);
    $decoded_output = $decoded_output->sites;
    $output = json_encode($decoded_output);
    $fp = fopen(getcwd() . '/../drush/aliases.json', 'w');
    fwrite($fp, $output);
    fclose($fp);
    $temp_array = array();
    foreach($decoded_output as $site) {
      $api_url = $base_api_url."/api/v1/sites/".$site->id;
      $api_creds = " -v -u \"".$user_name."\":".$api_key;
      $url = $api_url.$api_creds;
      $output = shell_exec("curl -s -L".$api_creds." ".$api_url);
      $output = json_decode($output);
      array_push($temp_array, $output);
    }
    $temp_output = json_encode($temp_array);
    $fp = fopen(getcwd() . '/../drush/sites.json', 'w');
    fwrite($fp, $temp_output);
    fclose($fp);


  }




}


function cmp($a, $b)
{
  return strcmp($a->domain, $b->domain);
}