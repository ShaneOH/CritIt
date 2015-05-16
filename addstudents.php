<?php
  require_once 'header.php';
  
  $success = $error = $text = $title = $saveto = "";
  $query1 = queryMysql("SELECT * FROM user WHERE netid='$netid'"); //Getting userid of logged on user
  $row = mysqli_fetch_array($query1);
  $user = $row['userid'];	  	

  if (!$loggedin) die();

  if (isset($_GET['view']))
     $view = sanitizeString($_GET['view']);
  else           
     $view = $netid;
 
  if (isset($_POST['id']))
  {
       $id = sanitizeString($_POST['id']);
       $course = $_POST['course'];
       if ($id == "")
       {
           $error = "*Not all fields were entered*<br><br>";
       }
       else
       {
       					 $query1  = "SELECT userid FROM user WHERE netid = '$id'";
    						 $result1 = queryMysql($query1);
    						 $num1    = $result1->num_rows;
    						 
    						 if($num1 != 0)
    						 {
     						    $row1 = mysqli_fetch_array($result1);		
     						    $id = $row1['userid'];
     						    queryMysql("INSERT INTO roster (studentid, courseid) VALUES('$id', '$course')");
                    $success=" Your user has been added! <br><br>";	
                    		
       			     }
       			     else
       			     {
       			        $error = "That user doesn't exist. Please tell them to create an account before adding them!";	
       			     }
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
      $name1 = "<a href='addstudents.php?view=$view'>$view</a>'s";
      $name2 = "$view's";
    }

    showProfile($view);
    echo "<div id='student'>" .
         "<div id='student-center'>".
         "<form method='post' action='addstudents.php?view=$view' enctype='multipart/form-data'>" .
         "Add students: <br><br>$error" .
         "<input type='text' maxlength='6' name='id' value='$id' placeholder='NetId'<br><br><br>" .
         "<span class='fieldname'>Class </span>" .
         "<select name='course'>";
     
    $query1 = "SELECT * FROM class WHERE profid = '$user'";
    $result1 = queryMysql($query1);

    if($result1){
       while( $row = mysqli_fetch_array($result1)){
       	$option = $row['coursename'];
       	$value = $row['courseid'];
       	echo "<option value='$value'>$option</option>";
      }
    }
    
    echo "</select><br><br>".
         "<input type='submit' name='post' value='Add'>$success</form><br></div></div>";
   }
    
    $query1  = "SELECT DISTINCT class.courseid,coursename,semester FROM class,roster WHERE class.courseid = roster.courseid AND class.profid = '$user'";
    $result1 = queryMysql($query1);
    $num1    = $result1->num_rows;
    
    echo "<div id='existing-student-center'>" ;
    for ($j = 0 ; $j < $num1 ; ++$j)
    {
    	echo "<div id='student-list'>" ;
    	echo "<div class='addstudentlist'>";
      $row1 = mysqli_fetch_array($result1);
      echo "<br><br><b>". $row1['coursename']."</b><br><em>".$row1['semester']."</em><br>" ;
      $courseid = $row1['courseid'];
      echo $coursid;
      $query2  = "SELECT DISTINCT name FROM class,roster,user WHERE class.courseid = '$courseid' AND class.courseid = roster.courseid AND class.profid = '$user' AND roster.studentid = user.userid";  
      $result2 = queryMysql($query2); 
      $num2    = $result2->num_rows;
      
      for ($k = 0 ; $k < $num2 ; ++$k)
      {    
          $row2 = mysqli_fetch_array($result2);
          echo "<br>" . $row2['name'];
      }        
      echo "</div></div>";
    }
?>

    </div><br>
  </body>
</html>
