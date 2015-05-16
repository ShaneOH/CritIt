<?php
  ob_start();
  require_once 'header.php';

  $dbhost = 'localhost';
  $dbname = 'test';
  $dbuser = 'root';
  $dbpass = 'kojack94';
  $link = mysql_connect($dbhost, $dbuser, $dbpass);
  $error = $text = $title = $saveto = "";
  $query1 = queryMysql("SELECT * FROM user WHERE netid='$netid'"); //Getting userid of logged on user
  $row = mysqli_fetch_array($query1);
  $user = $row['userid'];	  	

  mysql_select_db($dbname, $link);

  if (!$loggedin) die();

  if (isset($_GET['view']))
  {
     $view = sanitizeString($_GET['view']);
  }
  else{
  	 $view = $netid;
  }
 
  if(isset($_FILES['userfile']))
  {

	   // Check for errors
	   if($_FILES['userfile']['error'] > 0){
		   die('An error occurred when uploading (error greater than zero).');
	   }

	   // Check filetype
	   //echo '<p>The filetype is' . $_FILES['userfile']['type'] . 'to test</p>'

	   if($_FILES['userfile']['type'] == 'image/png'){}
	   else if($_FILES['userfile']['type'] == 'image/jpg'){}
	   else if($_FILES['userfile']['type'] == 'image/jpeg'){}
	   else if($_FILES['userfile']['type'] == 'image/gif'){}
	   else
	   {
		   die('Invalid file type (' . $_FILES['userfile']['type'] . ')! Please submit png/jpg/jpeg/gif');
	   }

	   // Check filesize
	   if($_FILES['userfile']['size'] > 16777215){
		   die('Error: This file exceeds the 16 MB limit.');
	   }

	   // Check if the file already exists
	   if(file_exists('upload/' . $_FILES['userfile']['name'])){
		   die('File with that name already exists.');
	   }

	   // Last error check
	   if(!getimagesize($_FILES['userfile']['tmp_name'])){
		   die('Please ensure you are uploading an image.');
	   }
	
	   $fileName = $_FILES['userfile']['name'];
	   $tmpName = $_FILES['userfile']['tmp_name'];
	   $fileSize = $_FILES['userfile']['size']; 
	   $fileType = $_FILES['userfile']['type'];

	   $fp = fopen($tmpName, 'r');
	   $image = fread($fp, filesize($tmpName));
	   $image = addslashes($image);
	   fclose($fp);

	   if(!get_magic_quotes_gpc()){
	   	$fileName = addslashes($fileName);
	   }

	   // Upload file
     if (isset($_POST['title'])){
        $title = sanitizeString($_POST['title']);
        $text = sanitizeString($_POST['text']);
     
        if ($title == "" || $text == "")
          $error = "*Not all fields were entered*<br><br>";
        else
        {
          $course = $_POST['course'];
          $imagequery = "INSERT INTO picture (imagename, imagesize, imagetype, image, timestamp) VALUES ('$fileName', '$fileSize', '$fileType', '$image', now())";
          $postquery = "INSERT INTO post (userid, courseid, title, text, likes, timestamp) VALUES('$user', '$course', '$title', '$text', '0', now())";
          queryMysql($imagequery);
          queryMysql($postquery);
          header:('Location: home.php?view=$netid');
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
    
    showProfile($view);
    echo "<div id='poststyle'><div id='poststyle-center'>";
    echo "<form method='post' action='post.php?view=$view' enctype='multipart/form-data'>" .
         "Post artwork to be critiqued: <br><br>$error" .
         "<span class='fieldname'>Artwork</span>" .
         "<input type='hidden' name='MAX_FILE_SIZE' value='99999999' />" .
         "<input name='userfile' type='file' /><br>" .
         "<input type='text' maxlength='20' name='title' value='$title' placeholder='Title'><br><br>" .
         "<span class='fieldname'>Class </span>" .
         "<select name='course'>";
     
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
         "<textarea name='text' cols='49' rows='3' placeholder='Enter a description...'></textarea><br>".
         "<input type='submit' name='post' value='Post'></form><br></div></div>";
   }
   
  
?>


    </div><br>
  </body>
</html>
