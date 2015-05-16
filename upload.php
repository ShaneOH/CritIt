<?php
  require_once 'header.php';
  if(!isset($_FILES['userfile']))
  {
     $error = "*Please select a file*<br><br>";
  }
  else
  {
      try{
         upload();
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
                 queryMysql("INSERT INTO post (userid, courseid, title, timestamp, text, likes) VALUES('$user', '$course', '$title', '$time', '$text', '0')");
                 die("<h4>Your post has been submitted!</h4><br><br>");
            }
        }
  }

function upload(){
  if(is_uploaded_file($_FILES['userfile']['tmp_name']) && getimagesize($_FILES['userfile']['tmp_name']) != false)
  {
    $size = getimagesize($_FILES['userfile']['tmp_name']);
    $type = $size['mime'];
    $imgfp = fopen($_FILES['userfile']['tmp_name'], 'rb');
    $size = $size[3];
    $name = $_FILES['userfile']['name'];
    $maxsize = 99999999;


    /***  check the file is less than the maximum file size ***/
    if($_FILES['userfile']['size'] < $maxsize )
        {
        $stmt = $dbh->prepare("INSERT INTO picture (imagetype, imagesize, imagename) VALUES (? ,?, ?)");

        /*** bind the params ***/
        $stmt->bindParam(1, $type);
        $stmt->bindParam(3, $size);
        $stmt->bindParam(4, $name);

        /*** execute the query ***/
        $stmt->execute();
        }
    else
        {
        throw new Exception("File Size Error");
        }
    }
  else
  {
    throw new Exception("Unsupported Image Format!");
  }
}

?>
