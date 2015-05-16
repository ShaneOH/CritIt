<?php // Example 26-4: index.php
  require_once 'header.php';

  if ($loggedin)
  {
  	 echo " $user, you are logged in.";
  }
  else
  {
  	 echo "<div class='text-centered'>" .
	        "<h3> Crit It is a critiquing platform for art students.</h3>" .
	        "<p> It's a place where students help students grow.</p>" .
	        "<img src='big-logo.png' style='position: absolute; width: 125px;'>" .
	        "<ul style='margin-left: 150px;'>" .
	        "<li>Students can critique other students' artwork</li> " .
	        "<li>Instructors and teaching assistants can endorse critiques to help guide the class</li>" .
	        "<li>Easy to navigate through the posted artworks</li>" .
	        "<li>Able to get updates in real time</li>" .
	        "</div>".
	        
          "<ul class='menu landing centered' >" .
          "<li style='background-color: #470b41;'><a href='signup.php'>Sign up</a></li>" .
          "<li style='background-color: #470b41;'><a href='login.php'>Log In</a></li></ul>" ;
	  
  }
  
  echo "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>" .
       "<script type='text/javascript' src='lightbox.js'></script>";
  
?>
 
    </span><br><br>
  </body>
</html>


