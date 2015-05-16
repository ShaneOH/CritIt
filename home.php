<?php
  require_once 'header.php';
  $prof = $TA = FALSE;

  
  /** Get the userid of whoever is logged in **/
  $error = $text = $title = $saveto = "";
  $query1 = queryMysql("SELECT * FROM user WHERE netid='$netid'"); //Getting userid of logged on user
  $row = mysqli_fetch_array($query1);
  $user = $row['userid'];	 
  
  /** Figure out if logged in user is a student, professor, TA **/
  if($row['isProfessor']!=NULL) 
  	$prof = TRUE;
  else if($row['isTA']!=NULL)
  	$TA = TRUE;


  if (!$loggedin) die();

  
  if (isset($_GET['view']))
     $view = sanitizeString($_GET['view']);
  else                     
     $view = $netid;
     
  /** MAIN PART **/

      if (isset($_POST['like']))
      { 
        $like = $_POST['liked'];
        queryMysql("UPDATE post SET likes=likes+1 WHERE artid=$like");
      } 
 
  if(!$prof) /** If it is a student or a TA **/
  {   	
      $query = "SELECT * FROM class, roster WHERE class.courseid = roster.courseid AND roster.studentid = '$user'";
      $result = queryMysql($query);
      
      /** Posting list of courses**/
      if($result)
      {
      	 echo "<div id='sidebar'>".
      	      "<div id='right-border'></div>".
      	      "<a href='post.php' id='post-button'>POST ARTWORK</a>" .
      	      "<div id='classes'".
      	      "<p class='small-title list-1'>COURSES</p>" .
              "<form id='form' method='post'>";
              
         while($row = mysqli_fetch_array($result))
         {
       	     $coursename = $row['coursename'];
       	     $courseid = $row['courseid'];
       	      echo "<input id='$courseid' class='checkbox-toggle gray' type='checkbox' value='$courseid' checked>".
                   "<label for='$courseid' data-on='$coursename'></label><br><br>";
         }
         
         echo 
              "<script>" .
              "function change(){" .
              "document.getElementById('form').submit();" . 
              "}".
              "</script>".
              "</div>";
      }
      	
      $query = "SELECT DISTINCT user.name, user.netid FROM class, roster, user WHERE class.courseid = roster.courseid AND roster.studentid = '$user' AND class.profid != user.userid AND user.userid != '$user'";
      $result = queryMysql($query);
      
      /** Posting list of students **/
      if($result) 
      {
      	echo "<p class='small-title list-2'>PEERS</p><p>";
         while($row = mysqli_fetch_array($result))
         {
       	   $peername = $row['name'];
       	   $id = $row['netid'];
       	   echo "<a href='profile.php?view=$id'>".$peername."</a><br>";
         }
        echo "</p></div>";
      }
      
      /** Posting all posts from courses **/  
      if($_POST['course'] == '')
      {
    	   $query  = "SELECT DISTINCT * FROM picture, post, roster, class, user WHERE picture.timestamp = post.timestamp AND post.userid = user.userid AND post.courseid = roster.courseid AND class.courseid = roster.courseid AND roster.studentid = '$user' ORDER BY artid DESC";
    	   $result = queryMysql($query);
    	   $num    = $result->num_rows;
      }
      else{
      	 $query  = "SELECT DISTINCT * FROM picture, post, roster, class, user WHERE picture.timestamp = post.timestamp AND post.userid = user.userid AND post.courseid = roster.courseid AND class.courseid = roster.courseid AND roster.courseid = '$courseid' ORDER BY artid DESC";
    	   $result = queryMysql($query);
    	   $num    = $result->num_rows;
      }
      
      echo "<div style='position:absolute; margin-left: 270px;'>";
    	    for ($j = 0 ; $j < $num ; ++$j)
    	    {
    	    	  $row = mysqli_fetch_array($result);
      	      $like = $row['artid'];
      	      $image = $row['image'];
              $type = $row['imagetype'];
              $imagename = $row['imagename'];
              
      	      $query5=  "SELECT COUNT(*) as comments FROM comment WHERE artid = '$like' ORDER BY artid DESC";
      	      $result5 = queryMysql($query5);
      	      $row5 = mysqli_fetch_array($result5);
      	      
       	      echo "<div id='post'>" ;
              echo "<p id='title'>".$row['coursename']."</p>";
              echo '<div class="pic-container"><img src="data:$type;base64,' . base64_encode($image).'"/>';
              echo "<br><a href='viewpost.php?view=".$row['artid']."'><b>".$row['title']."</b><br>By ".$row['name']."</a>".
                   "</div>".
                   "<div id='comments-likes'>".
                   "<div id='comment-section'>".
                   "<img id='comment-pic' src='paint.png' style='width:15px;'><p id='comment-count'>".$row5['comments']."</p></div>".
                   "<div id='like-section'>".
                   "<td><form action='home.php?view=$view' method='post'>".     
                   "<input type='hidden' name='liked' value='$like'>".
                   "<input id='like-button' type='submit' name='like' value=''></form></td>".
   		             "<p id='likes'>".$row['likes']."</p></div></div>".
                   "</div>" ;             
          }
      echo "</div>";
      
    }

    else /** If it is the professor **/
    {
      $query1 = "SELECT * FROM class WHERE profid = '$user'";
      $result1 = queryMysql($query1);

      /** Printing list of courses **/
      if($result1)
      {
      	 echo "<div id='sidebar'>".
      	      "<div id='right-border'></div>".
      	      "<a href='addclass.php' class='add-button' style='margin-bottom: -15px;'><b> + </b> COURSES</a><br><br><br>" .
      	      "<a href='addstudents.php' class='add-button'><b> + </b>STUDENTS</a>" .
      	      "<div id='classes'".
      	      "<p class='small-title list-1'>COURSES</p>" .
              "<form id='form' method='post'>";
              
         while($row1 = mysqli_fetch_array($result1))
         {
       	     $coursename = $row1['coursename'];
       	     $courseid = $row1['courseid'];
       	      echo "<input id='$courseid' class='checkbox-toggle gray' type='checkbox' value='$courseid' checked>".
                   "<label for='$courseid' data-on='$coursename'></label><br><br>";
         }
         
         echo 
              "<script>" .
              "function change(){" .
              "document.getElementById('form').submit();" . 
              "}".
              "</script>".
              "</div>";
      }
  
  
      $query2 = "SELECT DISTINCT user.name, user.netid FROM class, roster, user WHERE class.courseid = roster.courseid AND class.profid = '$user' AND user.userid != '$user'";
      $result2 = queryMysql($query2);

      /** Printing list of students in all courses **/
      if($result2)
      {
      	echo "<h3>STUDENTS</h3>";
      	echo "<p>";
         while($row2 = mysqli_fetch_array($result2))
         {
       	   $peername = $row2['name'];
       	   $id = $row2['netid'];
       	   echo "<a href='profile.php?view=$id'>".$peername."</a><br>";
        }
        echo "</p></div>";
      }
      
    	$query  = "SELECT * FROM picture, post, class, user WHERE picture.timestamp = post.timestamp AND post.userid = user.userid AND post.courseid = class.courseid AND class.profid = '$user' ORDER BY artid DESC";
    	$result = queryMysql($query);
    	$num    = $result->num_rows;
    
      /** Posting all posts from courses **/
    	date_default_timezone_set('America/New_York');
      echo "<div style='position:absolute; margin-left: 270px;'>";
    	    for ($j = 0 ; $j < $num ; ++$j)
    	    {
    	    	  $row = mysqli_fetch_array($result);
      	      $like = $row['artid'];
      	      $image = $row['image'];
              $type = $row['imagetype'];
              $imagename = $row['imagename'];
              
      	      $query5=  "SELECT COUNT(*) as comments FROM comment WHERE artid = '$like' ORDER BY artid DESC";
      	      $result5 = queryMysql($query5);
      	      $row5 = mysqli_fetch_array($result5);
      	      
       	      echo "<div id='post'>" ;
              echo "<p id='title'>".$row['coursename']."</p>";
              echo '<div class="pic-container"><img src="data:$type;base64,' . base64_encode($image).'"/>';
              echo "<br><a href='viewpost.php?view=".$row['artid']."'><b>".$row['title']."</b><br>By ".$row['name']."</a>".
                   "</div>".
                   "<div id='comments-likes'>".
                   "<div id='comment-section'>".
                   "<img id='comment-pic' src='paint.png' style='width:15px;'><p id='comment-count'>".$row5['comments']."</p></div>".
                   "<div id='like-section'>".
                   "<td><form action='home.php?view=$view' method='post'>".     
                   "<input type='hidden' name='liked' value='$like'>".
                   "<input id='like-button' type='submit' name='like' value=''></form></td>".
   		             "<p id='likes'>".$row['likes']."</p></div></div>".
                   "</div>" ;   
                   
                   
          }
      echo "</div>";
    }



?>


    </div><br>
  </body>
</html>
