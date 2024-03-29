<!--20000820-->
<!--Pham Gia Huy Nguyen -->
<!--Wednesday 9:00AM-->
<?php
  session_start();
  if (isset($_POST["submit"])){
    $choice = $_POST["choice"];

    if ($choice == "server"){
      require_once("conn.php");
      $today = date("Y-m-d");   
      $sql = "SELECT * FROM weeks ";
      $results = $dbConn->query($sql);
      while ($row = $results->fetch_assoc()){
        if ($row["endDate"]>$today) {
          if ($today>$row["startDate"]){
          $week = $row["weekID"];
          break;
          }
          else $week = $row["weekID"] - 1;
          break;
        }
      }
      $dbConn->close();
    }
    else {
      $week = $_POST["weekNum"];
    }
    $_SESSION["week"] = $week;
    header("location: fixtures.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>A-League Assignment - Choose Week</title>
    <link rel="stylesheet" href="css/projectMaster.css">

    <script>
      function changeSelectionList(){
      if (document.getElementById("weekForm").choice.value == "server")
        document.getElementById("weekNum").disabled = true;
      else
        document.getElementById("weekNum").disabled = false;
      }
    </script>

  </head>

  <body>
  <nav>
    <ul> 
        <a class="alogo" href="index.php"><img class="navlogo" src="images/ALeague.png"></a>
        <li><a href="ladder.php">Ladder</a></li>
        <li><a href="fixtures.php">Fixtures</a></li>
        <li><a href="scoreEntry.php">Enter Results</a></li>
        <li><a href="logoff.php">Logoff</a></li>
    </ul>
</nav>
    <h1>A-League Ladder Assignment</h1>

    <form id="weekForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <p>Do you want to use the Server Date or User Input for the current week?</p>

      <p>
        <label for="Server">Server Date</label>
        <input type="radio" id="Server" name="choice" value="server" onclick="changeSelectionList();">
      </p>

      <p>
        <label for="User">User Input</label>
        <input type="radio" id="User" name="choice" value="user" onclick="changeSelectionList();">
      </p>

      <p>
        <label for="weekNum">Week Number:</label>
        <select id="weekNum" name="weekNum" size="1" disabled>
          <script>
             for (i = 1; i <= 24; i++)
               document.write('<option value="' + i + '">' + i + '</option>');
          </script>
        </select>
      </p>
      <p><input type="submit" name="submit" value="submit"></p>
    </form>

  </body>
</html>
