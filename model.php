<?php
function prepare_output($number_of_posts, $identifier) { // returns massive, which contains posts, media and answers
  
  $connection = mysql_connect('localhost', 'root', '');
  $db = mysql_select_db('mindstream', $connection);
  
  //First, get data from table
  
  if($identifier == NULL) {
  $rssql = "SELECT t1.PostID, t1.Text, t1.Email, t1.Time, t1.Karma, media.Link, media.Preview, post_answer.AnswerID
       FROM (SELECT * FROM post ORDER BY post.PostID DESC LIMIT " . $number_of_posts . ") t1
       LEFT JOIN post_media ON (post_media.PostID = t1.PostID)
       LEFT JOIN media ON (post_media.PostID = t1.PostID AND post_media.MediaID = media.MediaID)
       LEFT JOIN post_answer ON (post_answer.PostID = t1.PostID)
       ORDER BY t1.PostID ASC"; 
  } else {
    $rssql = "SELECT post.PostID, post.Text, post.Email, post.Time, post.Karma, media.Preview, post_answer.AnswerID
       FROM post 
       LEFT JOIN post_media ON (post_media.PostID = post.PostID)
       LEFT JOIN media ON (post_media.PostID = post.PostID AND post_media.MediaID = media.MediaID)
       LEFT JOIN post_answer ON (post_answer.PostID = post.PostID)
           WHERE post.PostID = " . $identifier;
  }
   //Warning! The result will contain duplicates of fields!
  $qresult = mysql_query($rssql, $connection);
  
  //Ok, now we have data, but it's too raw. Let's prepare it for output
  

  if(mysql_affected_rows($connection) == 0) {
    $result['posts'][] = array('PostID' => 'Error 404', 'Text' => 'Post not found!');
  } else {
    
    $media = NULL;
    $posts = NULL;
    $previous_id = NULL;
    $result = NULL;
    $answers = NULL;

    while($post = mysql_fetch_array($qresult, MYSQL_BOTH)) {
    
      //id of post from last operation
      $post_id = $post['PostID'];

      if ($post['Preview'] != NULL) {
        $media[$post_id]['Links'][] = $post['Link'];
        $media[$post_id]['Previews'][] = $post['Preview'];
      }
      
      if (!empty($post['AnswerID'])) {
        $answers[$post_id][] = $post['AnswerID'];
      }
    
      /*The result may contain duplicates of fields, which are required only once for each post.
       *So we compare the IDs of posts from last operations to exclude such duplicates. 
       */
      if ($post['PostID'] != $previous_id) {
        $values['PostID'] = $post['PostID'];
        $values['Text'] = $post['Text'];
        $values['Email'] = $post['Email'];
        $values['Time'] = $post['Time'];
        $values['Karma'] = $post['Karma'];
        $posts[] = $values;
      }
    	
      $previous_id = $post_id;
    }
    
    //Exclude duplicates of fields that are required not once
    
    $lim = count($answers);
    for ($i = 0; $i < $lim; $i ++ ) {
      if(!empty($answers[$i])) {
        $answers[$i] = array_unique($answers[$i]);
      }
    }
    
    if(!empty($media)) {
      foreach ($media as &$med) {
        if(!empty($med['Previews'])) {
          $med['Previews'] = array_unique($med['Previews']);
      
          $med['Links'] = array_unique($med['Links']);
        }
      }
    }
 
    $result['answers'] = $answers;
    $result['posts'] = $posts;
    $result['media'] = $media;
    
    mysql_close($connection);
    
  }
  return $result;
}