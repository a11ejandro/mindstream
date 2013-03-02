<?php
$domain = "/domains/mindstream/";
$file_dir = "UsrImages/";
$preview_dir = "Thumbnails/";

$connection = mysql_connect('localhost', 'root', '');
$db = mysql_select_db('mindstream', $connection);



$in_text = array("[b]", "[/b]", "[i]", "[/i]", "[spoiler]", "[/spoiler]");
$replace_on = array("<b>", "</b>", "<i>", "</i>", '<span class = "spoiler">', '</span>');

//function for creating previews
include_once 'makepreview.php';

if($_POST['send'] == "Отправить") {
  

  
  $email = $_POST['E-mail'];
  $safe_text = (htmlspecialchars($_POST['Text'],ENT_QUOTES));
  $new_text = str_replace($in_text, $replace_on, $safe_text);

  //find quotes for adding their numbers to DB
  preg_match_all('/&gt;&gt;(\d+)/', $new_text, $answers_on);
  
  //convert quotes into links
  $new_text = preg_replace('/&gt;&gt;(\d+)/', '<a class="post-link" href="#$1">>>$1</a>', $new_text);
  
  
  $insql = "INSERT INTO post (Text, Email) VALUES ('$new_text', '$email')";
  mysql_query($insql, $connection);

  $current_post = mysql_insert_id();

  //process files in post
  if($_FILES['uploadFile']['size'] !== 0) {
    if($_FILES['uploadFile'] ['error'] > 0)
    {
      switch ($_FILES['uploadFile'] ['error'])
      {

        case 1: $warning = 'Файл превышает максимальный серверный размер для пересылки';
        break;

        case 2: $warning = 'Файл превышает максимальный размер файла';
        break;

        case 3: $warning = 'Файл загрузился только частично';
        break;

        case 4: $warning = 'Никакой файл не загрузился';
        break;

      }

    }
     
    $file_name = $_FILES['uploadFile']['name'];
    // I wanted the ID of file to become it's name in folder


    //add user name of file to database
    $insql = "INSERT INTO media (Name) VALUE('$file_name')";
    mysql_query($insql, $connection);
    $current_file = mysql_insert_id();

    //new file name
    $new_file_name = $current_file . "." . end(explode(".", $file_name));
    //create link with new file name
    $new_file_src = $domain . $file_dir . $new_file_name;
    //and preview
    $new_file_preview = $domain . $preview_dir . $new_file_name;

    //move and rename
    move_uploaded_file ($_FILES['uploadFile']['tmp_name'], $new_file_src);

    //create preview

    image_resize($new_file_src, $new_file_preview, 160);


    //add link on file to current record
    $insql = "UPDATE media SET Link = '$new_file_src', Preview = '$new_file_preview' WHERE MediaID = '$current_file'" ;
    mysql_query($insql, $connection);

    //Create association between post and file. Add association to post-media table.
    $insql = "INSERT INTO post_media VALUES ('$current_post', '$current_file')";
    mysql_query($insql, $connection);
   
  }
  
  if(!empty($answers_on[1])) {
    
    $answers = array_unique($answers_on[1]);
    $sql_string = "INSERT INTO post_answer (AnswerID, PostID) VALUES ";
    
    foreach($answers as $answer) {
      $sql_string .= '(' . $current_post . ', ' . $answer . '), ';
    }
    
    //delete last comma from query
    $sql_string = substr($sql_string, 0, -2);
    
    mysql_query($sql_string, $connection);
    
  }
}

mysql_close($connection);
header('location: index.php');
?>