<?php // Example 26-8: profile.php
  require_once 'header.php';

 
 
  if (isset($_SESSION['netid']))
  {
     $sessionid = $_SESSION['netid'];
  }

  if (!$loggedin) die();
 
  if (isset($_GET['view']))
  {
     $view = sanitizeString($_GET['view']);
     $netid = $view;
  } 

  $query = queryMysql("SELECT * FROM user WHERE netid='$netid'"); //Getting userid of logged on user
  $row = mysqli_fetch_array($query);
  $user = $row['userid'];	  	
  $email = $row['email'];
  $name = $row['name'];
  
  $prof = $TA = $student = FALSE;
  
  echo "<div id='profile-top'><div id='pic-background'><img id='default-pic' src='profile.png'></div>";

  echo "<div id='profile'>".
       "<p>$email</p>" .
       "<p id='profile-name'>$name</p>" ;
  
  if($row['isProfessor']!=NULL) 
  {
    echo "<p id='status'>Professor</p></div></div>";	
    $query  = "SELECT * FROM class WHERE profid = $user";
       $result = queryMysql($query);
       $num    = $result->num_rows;
    
       echo "<div id='existing-class-center' style='margin-top: -40px; left: 0; margin-left: 3%' >" ;
       for ($j = 0 ; $j < $num ; ++$j)
       {
    	     echo "<div id='existing-class'>" ;
    	     echo "<div class='addclasslist'>";
           $row = mysqli_fetch_array($result);
           $erase = $row['courseid'];
           echo "<br>" .
                $row['coursename']. "<br>".$row['semester']."<br></div></div>" ;           
       }
       echo "</div>";	
  }
  else if($row['isTA']!=NULL)
  {
  	echo "<p id='status'>Teaching Assistant</p>";	
  }
  else
  {
    echo "<p id='status'>Student</p>";
  }
  
  echo "</div></div>"; 
    
  if (isset($_POST['delete']))
  { 
       $toerase = $_POST['id'];
       queryMysql("DELETE FROM post WHERE artid=$toerase");
  } 
    
    $query  = "SELECT * FROM picture, post, class WHERE post.userid = $user AND post.courseid = class.courseid AND picture.timestamp = post.timestamp ORDER BY post.timestamp DESC";
    $result = queryMysql($query);
    $num    = $result->num_rows;
    
    date_default_timezone_set('America/New_York');
    
      echo "<div style='margin-left: 3%;'>";
      
    	    for ($j = 0 ; $j < $num ; ++$j)
    	    {
              $row = mysqli_fetch_array($result);
              $like = $row['artid'];
      	      $image = $row['image'];
              $type = $row['imagetype'];
              $imagename = $row['imagename'];
      	      $erase = $row['artid'];
      	      
      	      $query5=  "SELECT COUNT(*) as comments FROM comment WHERE artid = '$like' ORDER BY artid DESC";
      	      $result5 = queryMysql($query5);
      	      $row5 = mysqli_fetch_array($result5);
      	      
       	      echo "<div id='post'>" ;
              echo "<p id='title'>".$row['coursename']."</p>";
              echo '<div class="pic-container"><img src="data:$type;base64,' . base64_encode($image).'"/>';
              
              if($sessionid == $netid)
              {
                 echo "<td><form action='profile.php?view=$view' method='post'>".     
                      "<input type='hidden' name='id' value='$erase'>".
                      "<input type='submit' name='delete' value='delete' style='display: block; background: red; margin-top: 20px; border-radius: 10px; color: white; border: 0px; padding: 15px;	 margin-left: 65px;'></form></td>".
                      "<a style='margin-top: 10px;height: 20px; display: block; background: #dddddd; width: 45px; border-radius: 10px;  color: white; border: 0px; padding: 15px;	 margin-left: 65px; text-align: center; text-decoration: none;' href='viewpost.php?view=".$row['artid']."'>view</a>"; 
              }     
              else
              {
                 echo "<a style='margin-top: 10px;height: 20px; display: block; background: #dddddd; width: 45px; border-radius: 10px;  color: white; border: 0px; padding: 15px;	 margin-left: 65px; text-align: center; text-decoration: none;' href='viewpost.php?view=".$row['artid']."'>view</a>"; 
              }    
               
              echo "</div>".
                   "<div id='comments-likes'>".
                   "<div id='comment-section'>".
                   "<img id='comment-pic' src='paint.png' style='width:15px;'><p id='comment-count'>".$row5['comments']."</p></div>".
                   "<div id='like-section'>".
                   "<td><form action='home.php?view=$view' method='post'>".     
                   "<input type='hidden' name='liked' value='$like'>".
                   "<input id='like-button' type='submit' name='like' value=''></form></td>".
   		             "<p id='likes'>".$row['likes']."</p></div></div>";
                   
                   
                     
              echo "</div>";
          }
          
      echo "</div></div>";
      

?>

  </body>
</html>
