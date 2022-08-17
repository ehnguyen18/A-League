<!--20000820-->
<!--Pham Gia Huy Nguyen -->
<!--Wednesday 9:00AM-->
<?php
    session_start();
    require_once("conn.php");
    
    if(isset($_GET["display"])){
      $week = htmlspecialchars($_GET["weekNum"]);
    }
    else if(!isset($_SESSION["week"])) {
    $today = date("Y-m-d");   
      $sql1 = "SELECT * FROM weeks ";
      $rs = $dbConn->query($sql1);
      while ($row = $rs->fetch_assoc()){
        if ($row["endDate"]>$today) {
          if ($today>$row["startDate"]){
          $week = $row["weekID"];
          break;
          }
          else $week = $row["weekID"] - 1;
          break;
        }
      }
    }
    else $week = $_SESSION["week"];

    $sql = "SELECT f.*, t1.teamName AS homeName,  t1.emblem as homeEm, ";
    $sql = $sql."t2.teamName AS awayName,  t2.emblem as awayEm, ";
    $sql = $sql."v.venueName  ";
    $sql = $sql."FROM fixtures f JOIN teams t1 ON f.homeTeam = t1.teamID ";
    $sql = $sql."JOIN teams t2 ON f.awayTeam = t2.teamID ";
    $sql = $sql."JOIN venues v ON f.venueID = v.venueID ";
    $sql = $sql."WHERE weekID = $week ORDER BY matchDate , matchTime ";
    $results = $dbConn->query($sql)
    or die ('Problem with query: ' . $dbConn->error);

    $sql1 = "SELECT teamID, teamName FROM teams";
    $results1 = $dbConn->query($sql1)
    or die ('Problem with query: ' . $dbConn->error);
    $teamID = 1;
    $allWeek = 'off';
    if (isset($_POST["teamSubmit"])){
      if(isset($_POST["allWeek"])){
        $teamID = $dbConn->escape_string($_POST["tName"]);
        $allWeek = 'on';
        $sql2 = "SELECT f.*, t1.teamName AS homeName,  t1.emblem as homeEm, ";
        $sql2 = $sql2."t2.teamName AS awayName,  t2.emblem as awayEm, ";
        $sql2 = $sql2."v.venueName  ";
        $sql2 = $sql2."FROM fixtures f JOIN teams t1 ON f.homeTeam = t1.teamID ";
        $sql2 = $sql2."JOIN teams t2 ON f.awayTeam = t2.teamID ";
        $sql2 = $sql2."JOIN venues v ON f.venueID = v.venueID ";
        $sql2 = $sql2."WHERE (awayTeam='$teamID') OR (homeTeam='$teamID') ";
        $sql2 = $sql2."ORDER BY matchDate , matchTime ";
        $results2 = $dbConn->query($sql2)
        or die ('Problem with query: ' . $dbConn->error);
      }
      else{
        $teamID = $dbConn->escape_string($_POST["tName"]);
        $sql2 = "SELECT f.*, t1.teamName AS homeName,  t1.emblem as homeEm, ";
        $sql2 = $sql2."t2.teamName AS awayName,  t2.emblem as awayEm, ";
        $sql2 = $sql2."v.venueName  ";
        $sql2 = $sql2."FROM fixtures f JOIN teams t1 ON f.homeTeam = t1.teamID ";
        $sql2 = $sql2."JOIN teams t2 ON f.awayTeam = t2.teamID ";
        $sql2 = $sql2."JOIN venues v ON f.venueID = v.venueID ";
        $sql2 = $sql2."WHERE (weekID >= $week) AND ((awayTeam='$teamID') OR (homeTeam='$teamID')) ";
        $sql2 = $sql2."ORDER BY matchDate , matchTime ";
        $results2 = $dbConn->query($sql2)
        or die ('Problem with query: ' . $dbConn->error);
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>A-League Assignment - Choose Week</title>
    <link rel="stylesheet" href="css/projectMaster.css">
    <script src="javascript/tabs.js"></script>
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
  <?php 
  if (isset($_POST["teamSubmit"])){
    echo "<script>window.onload = function loadTab(){ ";
    echo  "document.getElementById(\"secondOpen\").click();";
    echo  "}</script>";
    }
  else{
    echo "<script>window.onload = function loadTab(){ ";
    echo  "document.getElementById(\"defaultOpen\").click();";
    echo  "}</script>";
  }?>

<div class="tab">
  <button class="tablinks" id="defaultOpen" onclick="openView(event, 'weeklyView')"><strong>Weekly Fixtures</strong></button>
  <button class="tablinks" id="secondOpen" onclick="openView(event, 'teamView')"><strong>Team Fixtures</strong></button>
</div>  

  <div id="weeklyView" class="tabcontent">
    <h1>Fixtures for Week <?php echo "$week"?></h1>
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
      <label for="weekNum">Week Number:</label>
      <select id="weekNum" name="weekNum" size="1">
        <script>
          for (i = 1; i <= 24; i++)
            document.write('<option value="' + i + '">' + i + '</option>');
        </script>
      </select>
      <script> document.getElementById('weekNum').value = "<?php echo $week?>"; </script>
      <input type="submit" name="display" value="Display">
    </form>
    <?php
    while ($row = $results->fetch_assoc()) {?>
      <div class="matchresult">
        <table class="fixtures">
          <tr>
          <td colspan="3"><?php echo $row["matchDate"]." | ".$row["matchTime"]?></td>
          </tr>
          
          <tr>
          <td class="teams">
            <span><img src="images/<?php echo $row["homeEm"]?>" width="26" height="26">
            <?php echo $row["homeName"]?></span><br>

            <span><img src="images/<?php echo $row["awayEm"]?>" width="26" height="26">
            <?php echo $row["awayName"]?></span>
          </td>
          <td class="scorebox">  
            
            <span ><strong><?php echo $row["score1"]?></strong></span><br>
            <span ><strong><?php echo $row["score2"]?></strong></span>
            
          </td>
          </td> 
            <td class="venue">
            <div class="venuediv">
              <span>A-LEAGUE | MW<?php echo $week?></span><br>
              <a href="venue.php?venueID=<?php echo htmlspecialchars($row["venueID"])?>">
              <?php echo $row["venueName"]?></a>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <?php } ?>
  </div>
  
  <div id="teamView" class="tabcontent">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <label for="tName">Team Name: </label>
      <select name="tName" id="tName" class="teamList"><br>
      <?php while ($row1 = $results1->fetch_assoc()) {
         echo "<option value=" .$row1['teamID']. ">" .$row1['teamName']. "</option>";
      }
      ?>
      <script> document.getElementById('tName').value = "<?php echo $teamID?>"; </script>
      </select>
  
      <label for="allWeek">Display all week? </label>       
      <input type="checkbox" name="allWeek" id="allWeek" <?php if(isset($_POST['allWeek'])) echo "checked='checked'"; //https://stackoverflow.com/a/12541453?>><br>
      <input type="submit" name="teamSubmit" value="Display">
    </form>
    <?php if (isset($_POST["teamSubmit"])){ 
      if (isset($_POST["allWeek"])) 
        echo "<h1>All Weeks Fixtures</h1>";
      while ($row2 = $results2->fetch_assoc()) {?>
        <div class="matchresult">
          <table>
            <tr>
            <td colspan="3"><?php echo $row2["matchDate"]." | ".$row2["matchTime"]?></td>
            </tr>
            
            <tr>
            <td class="teams">
              <span><img src="images/<?php echo $row2["homeEm"]?>" width="26" height="26">
              <?php echo $row2["homeName"]?></span><br>
  
              <span><img src="images/<?php echo $row2["awayEm"]?>" width="26" height="26">
              <?php echo $row2["awayName"]?></span>
            </td>
            <td class="scorebox">  
              <span ><strong><?php echo $row2["score1"]?></strong></span><br>
              <span ><strong><?php echo $row2["score2"]?></strong></span>
            </td>
            </td> 
              <td class="venue">
              <div class="venuediv">
                <span>A-LEAGUE | MW<?php echo $row2["weekID"]?></span><br>
                <a href="venue.php?venueID=<?php echo htmlspecialchars($row2["venueID"])?>">
                <?php echo $row2["venueName"]?></a>
              </div>
              </td>
            </tr>
          </table>
        </div>
        <?php }
        } ?>   
  </div>
  <?php unset($_SESSION['week']); ?>
  </body>
</html>