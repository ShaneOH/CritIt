<?php
  ob_start();
  require_once 'header.php';

  echo <<<_END
  <script>
    function checkUser(user)
    {
      if (user.value == '')
      {
        O('info').innerHTML = ''
        return
      }

      params  = "user=" + user.value
      request = new ajaxRequest()
      request.open("POST", "checkuser.php", true)
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
      request.setRequestHeader("Content-length", params.length)
      request.setRequestHeader("Connection", "close")

      request.onreadystatechange = function()
      {
        if (this.readyState == 4)
          if (this.status == 200)
            if (this.responseText != null)
              O('info').innerHTML = this.responseText
      }
      request.send(params)
    }

    function ajaxRequest()
    {
      try { var request = new XMLHttpRequest() }
      catch(e1) {
        try { request = new ActiveXObject("Msxml2.XMLHTTP") }
        catch(e2) {
          try { request = new ActiveXObject("Microsoft.XMLHTTP") }
          catch(e3) {
            request = false
      } } }
      return request
    }
  </script>
_END;

  $error = $name = $email = $netid =$pass = "";
  if (isset($_SESSION['netid'])) destroySession();

  if (isset($_POST['name']))
  {
    $name = sanitizeString($_POST['name']);
    $email = sanitizeString($_POST['email']);
    $netid = sanitizeString($_POST['netid']);
    $pass = sanitizeString($_POST['pass']);


    if ($name == "" || $email == "" || $netid == "" || $pass == "" )
      $error = "Not all fields were entered<br><br>";
    else
    {
      $result1 = queryMysql("SELECT * FROM user WHERE email='$email'");
      $result2 = queryMysql("SELECT * FROM user WHERE netid='$netid'");
      if ($result1->num_rows)
        $error = "An account with email '$email' already exists.<br><br>";
      else if($result2->num_rows)
	$error = "An account with netid '$netid' already exists.<br><br>";
      else
      {
      	  if($_POST['status'] == 0)
      	  {
						queryMysql("INSERT INTO user (name, email, password, netid) VALUES ('$name', '$email', '$pass', '$netid');");
						$_SESSION['netid'] = $netid;
						$_SESSION['pass'] = $pass;
						header('Location: home.php?view=$netid');
					}
					else if($_POST['status'] == 1)
					{
						queryMysql("INSERT INTO user (name, email, password, netid, isTA) VALUES ('$name', '$email', '$pass', '$netid',1);");
						$_SESSION['netid'] = $netid;
						$_SESSION['pass'] = $pass;
					}
					else if($_POST['status'] == 2)
					{
						queryMysql("INSERT INTO user (name, email, password, netid, isProfessor) VALUES ('$name', '$email', '$pass', '$netid',1);");
						$_SESSION['netid'] = $netid;
						$_SESSION['pass'] = $pass;
						header('Location: home.php?view=$netid');
					}
      }
    }
  }

  echo <<<_END
    <div id='signup'>
      <div id='signup-center'>
           <form method='post' action='signup.php'>$error
           <input type='text' placeholder='Name' maxlength='25' name='name' value='$name'  required><br>
           <input type='text' placeholder='Email' maxlength='30' name='email' value='$email'  required><br>
           <input type='text' placeholder='NetId' maxlength='6' name='netid' value='$netid' onBlur='checkUser(this)' required><br>
           <input type='password' placeholder='Password' maxlength='16' name='pass' value='$pass' required><br><br>

           <label>Student  <input type="radio" name="status" value="0" checked="checked"><br></label>
           <label>Teaching Assistant  <input type="radio" name="status" value="1"><br></label>
           <label>Professor <input type="radio" name="status" value="2"></label> <br><br>
_END;
?>
    <input type='submit' value='Sign up'>
  </form></div></div></div><br>
  </body>
</html>
