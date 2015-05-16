<?php
  require_once 'header.php';

  $error = $text = $title = $saveto = "";
  $query1 = queryMysql("SELECT * FROM user WHERE netid='$netid'"); //Getting userid of logged on user
  $row = mysqli_fetch_array($query1);
  $user = $row['userid'];	  	

  if (!$loggedin) die();

  if (isset($_GET['view']))
  {
     $view = sanitizeString($_GET['view']);
  }
  else
  {                      
     $view = $netid;
  }
 
  if(isset($_FILES['userfile']))
  {
  	try{
         $image = upload();
         echo '<p>Thank you for submitting.</p>';
      }   
      catch(Exception $e)
      {
         echo '<h4>'.$e->getMessage().'</h4>';
      }   
        if (isset($_POST['title'])){
            $title = sanitizeString($_POST['title']);
            $text = sanitizeString($_POST['text']);
     
            if ($title == "" || $text == "")
                 $error = "*Not all fields were entered*<br><br>";
            else
            {
                 $time = time('Y-m-d');
                 $course = $_POST['course'];
                 queryMysql("INSERT INTO post (userid, courseid, title, imagename, timestamp, text, likes) VALUES('$user', '$course', '$title', '$image', '$time', '$text', '0')");
                 die("<h4>Your post has been submitted!</h4><br><br>");
            }
        }
     
  }
  else
  {
      $error = "*Please select a file*<br><br>";
  }

  if ($view != "")
  {
    if ($view == $netid) 
    {
    	$name1 = $name2 = "Your";
    }
    else
    {
      $name1 = "<a href='user.php?view=$view'>$view</a>'s";
      $name2 = "$view's";
    }

    echo "<div class='main'><h3>$name1 Posts</h3>";
    showProfile($view);
    echo "<form method='post' action='post.php?view=$view' enctype='multipart/form-data'>" .
         "Post artwork to be critiqued: <br><br>$error" .
         "<span class='fieldname'>Artwork</span>" .
         "<input type='hidden' name='MAX_FILE_SIZE' value='99999999' />" .
         "<input name='userfile' type='file' /><br>" .
         "<span class='fieldname'>Title</span>" .
         "<input type='text' maxlength='20' name='title' value='$title'><br>" .
         "<span class='fieldname'>Class</span>" .
         "<select name='course'>";
     
    if (isset($_POST['delete']))
    { 
       $toerase = $_POST['id'];
       queryMysql("DELETE FROM post WHERE artid=$toerase");
    } 
     
    $query1 = "SELECT * FROM class, roster WHERE class.courseid = roster.courseid AND roster.studentid = '$user'";
    $result1 = queryMysql($query1);

    if($result1){
       while( $row = mysqli_fetch_array($result1)){
       	$option = $row['coursename'];
       	$value = $row['courseid'];
       	echo "<option value='$value'>$option</option>";
      }
    }
    
    echo "</select><br><br>".
         "<textarea name='text' cols='40' rows='3'></textarea><br>".
         "<input type='submit' name='post' value='Post'></form><br>";
   }
    
    $query  = "SELECT * FROM post WHERE userid = $user ORDER BY timestamp DESC";
    $result = queryMysql($query);
    $num    = $result->num_rows;
    
    date_default_timezone_set('America/New_York');
    
    for ($j = 0 ; $j < $num ; ++$j)
    {
      $row = mysqli_fetch_array($result);
      $erase = $row['artid'];
      echo date('Y-m-d', $row['time']);
      echo "<br><b>" .
           $row['title'] ."</b><br>".
           $row['text'] ."</span> ";
      echo "<td><form action='post.php?view=$view' method='post'>".     
           "<input type='hidden' name='id' value='$erase'>".
           "<input type='submit' name='delete' value='delete'></form></td>"; 
      echo "<br><a href='viewpost.php?view=".$row['artid']."'>".
           "View</a><br><br>";        
             
    }
 

  if (!$num) echo "<br><span class='info'>No posts yet</span><br><br>";
  echo "<br><a class='button' href='post.php?view=$view'>Refresh posts</a>";
  
  function upload(){
  	
  if(is_uploaded_file($_FILES['userfile']['tmp_name']))
  {
  	$saveto="newpicture.jpg";
    $typeok = TRUE;
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    switch($finfo)
    {
      case "image/gif": break;
      case "image/jpeg": break;
      case "image/pjpeg": break;
      case "image/png": break;
      default: $typeok = FALSE; break;
    }
    
    if($typeok)
    {
       $imgfp = fopen($_FILES['userfile']['tmp_name'], 'rb');
       $name = $_FILES['userfile']['name'];

       if($_FILES['userfile']['size'] < $maxsize )
       {
     	     queryMysql("INSERT INTO picture(imagename) VALUES('$saveto')");
    	     return $saveto;
       }
    }
    else
    {
    throw new Exception("Unsupported Image Format!");
    } 
  }
}

?>


    </div><br>
  </body>
</html>
