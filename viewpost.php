<?php
  require_once 'header.php';
  
  date_default_timezone_set("America/New_York");
  
  $error = $text = "";
  $query1 = queryMysql("SELECT * FROM user WHERE netid='$netid'"); //Getting userid of logged on user
  $row = mysqli_fetch_array($query1);
  $user = $row['userid'];	
  


  if (!$loggedin) die();

  if (isset($_GET['view']))
  {
     $view = sanitizeString($_GET['view']);
  } 
  
  if (isset($_POST['text']))
  {
      $text = sanitizeString($_POST['text']);
     
      if ($text != "")
      {
         queryMysql("INSERT INTO comment (artid, userid, timestamp, text, likes) VALUES('$view', '$user', now(), '$text', '0')");
      }
  }
     

  if ($view != "")
  {
  	  echo "<div id='post-center'>";
  	  
  	  $query2 = queryMysql("SELECT * FROM post WHERE artid='$view'");
      $row2 = mysqli_fetch_array($query2);
      $title = $row2['title'];	
      $posterid = $row2['userid'];
      $descr = $row2['text'];
      $posttime = $row2['timestamp'];
      $query3 = queryMysql("SELECT * FROM user WHERE userid='$posterid'");
      $row3 = mysqli_fetch_array($query3);
      $name = $row3['name'];	
  	
      echo "<h1>$title</h1>";
      echo "<p id='post-subhead'>By $name</p>";
      echo "<p class='timestamp'>".date('M j, Y g:i A',strtotime($posttime))."</p>";

      $imagequery = "SELECT * FROM picture, post WHERE picture.timestamp = post.timestamp AND post.artid = $view ORDER BY picture.timestamp DESC";
      $imageresult = queryMysql($imagequery);
      $imagerow = mysqli_fetch_array($imageresult);
      $image = $imagerow['image'];
      $type = $imagerow['imagetype'];
      $imagename = $imagerow['imagename'];

      echo '<img src="data:$type;base64,' . base64_encode($image) . '" width="550px"/>';

      echo "<p>$descr</p>";
      showProfile($view);
      
      echo "<div id='comments'>";
      
      $query  = "SELECT * FROM comment, user WHERE comment.artid = $view AND comment.userid = user.userid ORDER BY timestamp ASC";
      $result = queryMysql($query);
      $num    = $result->num_rows;
       
      for ($j = 0 ; $j < $num ; ++$j)
      {
        $prof = $TA = FALSE;
        $row = mysqli_fetch_array($result);
        if($row['isProfessor']!=NULL) 
  	     $prof = TRUE;
        else if($row['isTA']!=NULL)
  	     $TA = TRUE;  	
  	     
        if($prof == TRUE)
        {
           echo "<div id='comment-prof'>".
                "<p class='special'>PROF</p><p><b>".$row['name']."</b></p>".
                "<p>".$row['text']."</p>".  
                "<p class='timestamp'>".date('M j, Y g:i A',strtotime($row['timestamp']))."</p>".
                "</div>";
        }
        else if($TA == TRUE)
        {
        	 echo "<div id='comment-TA'>".
                "<p class='special'>TA</p><p><b>".$row['name']."</b></p>".
                "<p>".$row['text']."</p>".  
                "<p class='timestamp'>".date('M j, Y g:i A',strtotime($row['timestamp']))."</p>".
                "</div>";
        }
        else
        {
           echo "<div id='comment'>".
                "<p><b>".$row['name']."</b></p>".
                "<p>".$row['text']."</p>".  
                "<p class='timestamp'>".date('M j, Y g:i A',strtotime($row['timestamp']))."</p>".
                "</div>";	
        }
      }
      
      echo "<form method='post' action='viewpost.php?view=$view' enctype='multipart/form-data'>" .
           "<textarea id='comment-box' name='text' cols='76' rows='3' placeholder='Write a critique...'></textarea><br>".
           "<input type='submit' name='post' value='Post Critique'></form><br></form></div></div>";
   }

?>

    </div><br>
  </body>
</html>
