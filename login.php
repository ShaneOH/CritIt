<?php // Example 26-7: login.php
  ob_start();
  require_once 'header.php';
  
  $error = $netid = $pass = "";

  if (isset($_POST['netid']))
  {
    $netid = sanitizeString($_POST['netid']);
    $pass = sanitizeString($_POST['pass']);
    
    if ($netid == "" || $pass == "")
        $error = "Please enter all fields!<br><br>";
    else
    {
      $result = queryMySQL("SELECT netid, password FROM user
        WHERE netid='$netid' AND password='$pass'");

      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>NetId/Password
                  invalid</span><br>";
      }
      else
      {
        $_SESSION['netid'] = $netid;
        $_SESSION['pass'] = $pass;
        header('Location: home.php?view=$netid');
      }
    }
  }

  echo <<<_END
  <div class="ear-left"></div>
  <div class="ear-right"></div>
  <div class="avatar">
  	<div class="eyes">
  		<div class="eye-left"><div class='pupil'><div class='sparkle-left'></div></div></div>
 		 	<div class="eye-right"><div class='pupil'></div><div class='sparkle-right'></div></div>
 		    <div class="nose">
 		      <div class="nostril"></div>
 		      <div class="nostril"></div>
 		    </div> 
    </div> 	
	</div>
	<div class="arms">
    	<div class="arm"><div class="toes"></div><div class="toes-right"></div></div>
 			<div class="arm"><div class="toes"></div><div class="toes-right"></div></div>
  </div>
		
  <div id='login'>
      <div id='login-center'>
          <form method='post' action='login.php'>$error
          <input type='text' placeholder='NetId' maxlength='6' name='netid' value='$netid' required><br>
          <input type='password' placeholder='Password' maxlength='16' name='pass' value='$pass' required><br>
          
_END;

?>
    <br>
    <input type='submit' value='Login'></div>
    </form><br></div></div>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
  </body>
</html>

