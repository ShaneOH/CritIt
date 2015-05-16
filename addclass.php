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
 
        if (isset($_POST['name'])){
            $name = sanitizeString($_POST['name']);
            $semester = sanitizeString($_POST['semester']);
            $school = sanitizeString($_POST['school']);
     
            if ($name == "" || $semester == "" || $school == "")
                 $error = "*Not all fields were entered*<br><br>";
            else
            {
                 queryMysql("INSERT INTO class (profid, coursename, semester, school) VALUES ('$user', '$name', '$semester', '$school')");
            	   $success=" Class added!";
            }
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
    echo "<div id='class'>" .
         "<div id='class-center'>".
         "<form method='post' action='addclass.php?view=$view' enctype='multipart/form-data'>" .
         "Add a new class: <br><br>$error" .
         "<input type='text' maxlength='20' name='name' value='$name' placeholder='Name'><br>" .
         "<input type='text' maxlength='20' name='semester' value='$semester' placeholder='Semester (Example: Fall 2015)'><br>" .
         "<input type='text' maxlength='20' name='school' value='$school' placeholder='School'><br><br>" .
         "<input type='submit' name='post' value='Add'>$success</form><br></div></div>";     

    if (isset($_POST['delete']))
    { 
       $toerase = $_POST['id'];
       queryMysql("DELETE FROM class WHERE courseid=$toerase");
    }

    
    $query  = "SELECT * FROM class WHERE profid = $user";
    $result = queryMysql($query);
    $num    = $result->num_rows;
    
    echo "<div id='existing-class-center'>" ;
    for ($j = 0 ; $j < $num ; ++$j)
    {
    	
    	echo "<div id='existing-class'>" ;
    	echo "<div class='addclasslist'>";
      $row = mysqli_fetch_array($result);
      $erase = $row['courseid'];
      echo "<br>" .
           $row['coursename']."<br>".$row['semester']."<br>" ;
      echo "<td><form action='addclass.php?view=$view' method='post'>".     
           "<input type='hidden' name='id' value='$erase'>".
           "<input type='submit' name='delete' value='delete'></form></td></div></div>"; 
                   
    }
    echo "</div>";
  }

?>
    <br>
  </body>
</html>
