<?php

//Function for drawing all posts

function draw_body($result) {
  
$preview_dir = "Thumbnails/";
  
  $posts = $result['posts'];
  $media = $result['media'];
  $answers = $result['answers'];
  
  
  foreach ($posts as $post) {
    $post_id = $post['PostID'];

    echo '<div id="'. $post_id .'" class = "post-container">';
    echo '<p class="postHeader">' . $post['Email'] . ' ' . $post['Time'] . ' <span class="postNumber"># ' . $post_id . '</span> <img src="Style/Expand.png" class = "Expand" alt="Expand all images"/></p>';
  	
    //put images
    if(!empty($media[$post_id]['Previews'])) {

      foreach ($media[$post_id]['Previews'] as $picture) {
      	
        echo '<div class="image-container"> <img src="' . strstr($picture, $preview_dir) . '" class="UsrImg"/></div>';
      }
    }

    //And now we can draw text part

    echo '<p class="post-text">' . nl2br($post['Text']) . '</p>';
    
    if(!empty($answers[$post_id])) {
      
      $ansoutput = NULL;
      
      foreach($answers[$post_id] as $answer) {
        $ansoutput .= '<a class="post-link" href="#' . $answer . '">>>' . $answer . ' </a>, ';
      }
      
      $ansoutput = substr($ansoutput, 0, -2);
      echo '<p> Answers: ' . $ansoutput . '</p>';
    }
    
    echo '</div>';

  } 
} 
?>
