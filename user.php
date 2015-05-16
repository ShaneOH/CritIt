<?php // Example 26-9: user.php
  require_once 'header.php';

  if (!$loggedin) die();

  echo "<div class='main'>";

  if (isset($_GET['view']))
  {
    $view = sanitizeString($_GET['view']);
    
    if ($view == $netid) $name = "Your";
    else                $name = "$view's";
    
    echo "<h3>$name Profile</h3>";
    showProfile($view);
    echo "<a class='button' href='messages.php?view=$view'>" .
         "View $name messages</a><br><br>";
    die("</div></body></html>");
  }

  if (isset($_GET['add']))
  {
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends WHERE netid='$add' AND friend='$netid'");
    if (!$result->num_rows)
      queryMysql("INSERT INTO friends VALUES ('$add', '$netid')");
  }
  elseif (isset($_GET['remove']))
  {
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE netid='$remove' AND friend='$netid'");
  }

  $result = queryMysql("SELECT netid FROM user ORDER BY netid");
  $num    = $result->num_rows;

  echo "<h3>Other user</h3><ul>";

  for ($j = 0 ; $j < $num ; ++$j)
  {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['netid'] == $netid) continue;
    
    echo "<li><a href='user.php?view=" .
      $row['netid'] . "'>" . $row['netid'] . "</a>";
    $follow = "follow";

    $result1 = queryMysql("SELECT * FROM friends WHERE
      netid='" . $row['netid'] . "' AND friend='$netid'");
    $t1      = $result1->num_rows;
    $result1 = queryMysql("SELECT * FROM friends WHERE
      netid='$netid' AND friend='" . $row['netid'] . "'");
    $t2      = $result1->num_rows;

    if (($t1 + $t2) > 1) echo " &harr; is a mutual friend";
    elseif ($t1)         echo " &larr; you are following";
    elseif ($t2)       { echo " &rarr; is following you";
      $follow = "recip"; }
    
    if (!$t1) echo " [<a href='user.php?add="   .$row['netid'] . "'>$follow</a>]";
    else      echo " [<a href='user.php?remove=".$row['netid'] . "'>drop</a>]";
  }
?>

    </ul></div>
  </body>
</html>
