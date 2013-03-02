<?php
//for showing highlighted post
header("Content-Type: text/html; charset=UTF-8");

include_once 'model.php';
include_once 'view.php';

$id = $_GET['post_ID'];

$result = prepare_output(1, $id);

if(empty($result)) {
  echo 'Post not found';
} else {
  draw_body($result);
}

?>