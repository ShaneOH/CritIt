<?php
  session_start();

  echo "<!DOCTYPE html>\n<html><head>";

  require_once 'functions.php';

  $userstr = '(Guest)';

  if (isset($_SESSION['netid']))
  {
    $netid = $_SESSION['netid'];
    $loggedin = TRUE;
    $userstr  = " ($netid)";
  }
  else $loggedin = FALSE;

  echo "<title>$appname$userstr</title> " .
       "<link href='normalize.css' rel='stylesheet'/>" .
       "<link href='col.css' rel='stylesheet'/>" .
       "<link href='grid.css' rel='stylesheet'/>" .
       "<link href='ui/checkbox.css' rel='stylesheet'/>" .
       "<link href='style.css' rel='stylesheet'/>" .
       "<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>" .
       "</head>" .
       "<body>";

  if ($loggedin)
  {
      echo "<div id='nav'>".
	         "<div class = 'col'>" .
	   	     "<a href='home.php?view=$netid'><img id='logo' src='logo.png' alt='logo'/></a>" .
	         "</div> ".
	         "<div id='right'>".
	         "<ul class='menu'>" ;
 
      echo "<li><img id='heart' src='heart.png'></li>".
           "<li><img id='paint' src='paint.png'></li>".
           "<div id='you'><li><img id='profile-icon' src='profile.png'><a href='profile.php?view=$netid''>You</a><div id='arrow'></div></li>" .
             "<ul>" .
               "<li class='subnav'><a href='profile.php?view=$netid''>Edit</a></li>".
               "<li class='subnav'><a href='logout.php'>Log out</a></li>".
	           "</ul></div>".
	         "</ul>" .  
	         "</div>".
	         "</div>";     
  }
  else
  {
    	 echo "<div id='nav'>".
	          "<div class='col'>" .
	       	  "<a href='index.php'><img id='logo' src='logo.png' alt='logo'/></a>" .
	          "</div></div></nav> ";
	} 
?>


