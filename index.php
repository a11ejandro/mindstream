<?php
$domain = "/domains/mindstream/";
$file_dir = "UsrImages/";
$preview_dir = "Thumbnails/";

include_once 'header.html';
include_once 'qreply.html';
//include 'AnswerForm.html';

include_once 'model.php';
include_once 'view.php';

$to_output = prepare_output(40, NULL); //Prepare output of 40 posts

draw_body($to_output);

include_once 'footer.html';

?>
