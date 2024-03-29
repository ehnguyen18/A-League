<!--20000820-->
<!--Pham Gia Huy Nguyen -->
<!--Wednesday 9:00AM-->
<?php
    session_start();
    require_once("conn.php");
    if (!isset($_SESSION["logged"]))
        header("location: login.php");
    else {      
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

    $sql = "SELECT f.*, t1.teamName AS homeName,  t1.emblem as homeEm, ";
    $sql = $sql."t2.teamName AS awayName,  t2.emblem as awayEm, ";
    $sql = $sql."v.venueName ";
    $sql = $sql."FROM fixtures f JOIN teams t1 ON f.homeTeam = t1.teamID ";
    $sql = $sql."JOIN teams t2 ON f.awayTeam = t2.teamID ";
    $sql = $sql."JOIN venues v ON f.venueID = v.venueID ";
    $sql = $sql."WHERE weekID = $week AND score1 IS NULL ";
    $sql = $sql."ORDER BY matchDate , matchTime";
    $results = $dbConn->query($sql)
    or die ('Problem with query: ' . $dbConn->error);

    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>A-League Assignment - Enter Score</title>
    <link rel="stylesheet" href="css/projectMaster.css">
    <script src="javascript/scoreValidation.js"></script>
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
    if (isset($_POST["set"])){
        $matchID = $dbConn->escape_string($_POST["matchID"]);
        $s1 = $dbConn->escape_string($_POST["s1"]);
        $s2 = $dbConn->escape_string($_POST["s2"]);
        $homeID = $dbConn->escape_string($_POST["homeID"]);
        $awayID = $dbConn->escape_string($_POST["awayID"]);
        $sdiff = $s1 - $s2;
        
        $sql1 = "UPDATE fixtures SET score1='$s1', score2='$s2' WHERE matchID = '$matchID'";
            if ($dbConn->query($sql1) === TRUE) {
                echo "Match updated successfully <br>";
            } else {
                echo "Error updating record: " . $dbConn->error."<br>";
            }
        if ($sdiff > 0) { //homeTeam win            
            $sql2 = "UPDATE teams SET played=played+1, won=won+1, goalsFor = goalsFor + '$s1', ";
            $sql2 = $sql2."goalsAgainst= goalsAgainst + '$s2', goalDiff=goalDiff + '$sdiff', points=points + 3 ";
            $sql2 = $sql2."WHERE teamID = '$homeID'";

            $sql3 = "UPDATE teams SET played=played+1, lost=lost+1, goalsFor = goalsFor + '$s2', ";
            $sql3 = $sql3 . "goalsAgainst = goalsAgainst +'$s1', goalDiff=goalDiff - '$sdiff' ";
            $sql3 = $sql3 . "WHERE teamID = '$awayID'";
            
            if ($dbConn->query($sql2) === TRUE) {
                echo "HomeTeam updated successfully <br>";
            } else {
                echo "Error updating record: " . $dbConn->error."<br>";
            }
            if ($dbConn->query($sql3) === TRUE) {
            echo "AwayTeam updated successfully <br>";
            header("location: scoreentry.php");
            } else {
            echo "Error updating record: " . $dbConn->error."<br>";
            }
        } 
        else if ($sdiff < 0) { //awayTeam win
            $sql2 = "UPDATE teams SET played=played+1, lost=lost+1, goalsFor = goalsFor + '$s1', ";
            $sql2 = $sql2."goalsAgainst= goalsAgainst + '$s2', goalDiff=goalDiff - '$sdiff' ";
            $sql2 = $sql2."WHERE teamID = '$homeID'";
            
            $sql3 = "UPDATE teams SET played=played+1, won=won+1, goalsFor = goalsFor + '$s2', ";
            $sql3 = $sql3 . "goalsAgainst= goalsAgainst + '$s1', goalDiff=goalDiff + '$sdiff', points=points + 3 ";
            $sql3 = $sql3 . "WHERE teamID = '$awayID'";
            
            if ($dbConn->query($sql2) === TRUE) {
                echo "HomeTeam updated successfully <br>";
            } else {
                echo "Error updating record: " . $dbConn->error."<br>";
            }
            if ($dbConn->query($sql3) === TRUE) {
            echo "AwayTeam updated successfully <br>";
            header("location: scoreentry.php");
            } else {
            echo "Error updating record: " . $dbConn->error."<br>";
            }
        }
        else { //draw
            $sql2 = "UPDATE teams SET played=played+1, drawn=drawn+1, goalsFor =goalsFor + '$s1', ";
            $sql2 = $sql2."goalsAgainst= goalsAgainst + '$s2', points=points + 1 ";
            $sql2 = $sql2."WHERE teamID = '$homeID'";
            
            $sql3 = "UPDATE teams SET played=played+1, drawn=drawn+1, goalsFor =goalsFor + '$s2', ";
            $sql3 = $sql3 . "goalsAgainst= goalsAgainst + '$s1', points=points + 1  ";
            $sql3 = $sql3 . "WHERE teamID = '$awayID'";
            
            if ($dbConn->query($sql2) === TRUE) {
                echo "HomeTeam updated successfully <br>";
            } else {
                echo "Error updating record: " . $dbConn->error."<br>";
            }
            if ($dbConn->query($sql3) === TRUE) {
            echo "AwayTeam updated successfully <br>";
            header("location: scoreentry.php");
            } else {
            echo "Error updating record: " . $dbConn->error."<br>";
            }
        }
    }   
  ?>
  <h1>Current Week: <?php echo "$week"?></h1>
  <span class="error" id="final-error">Please fill out all the red coloured boxes \n Or refresh the page</span>
  <?php 
  $count=0;
  while ($row = $results->fetch_assoc()) {?>
      <div class="matchresult">
        <table class="fixtures">
          <tr>
          <td colspan="3"><?php echo "Match ID: ". $row["matchID"]." | ". $row["matchDate"]." | ".$row["matchTime"]?></td>
          </tr>
          
          <tr>
          <td class="teams"><span><img src="images/<?php echo $row["homeEm"]?>" width="26" height="26">
            <?php echo $row["homeName"]?></span><br>

            <span><img src="images/<?php echo $row["awayEm"]?>" width="26" height="26">
            <?php echo $row["awayName"]?></span>
          </td>
          <td class="scoreentry">  
            <form name="match<?php echo $count?>" action="#" method="post" onsubmit="return validateForm(<?php echo $count?>);">
                <input type="submit" name="set" value="SET" ><br>
                <label for="s1" class="scorelabel">Score1: </label>
                <input type="text" name="s1" size="1" id="s1" onblur="checkS1(<?php echo $count?>)" >
                <span class="error" id="s1-error" name="s1-error">Score must be zero or positive whole number</span>
                <br>
            
                <label for="s2" class="scorelabel">Score2: </label>       
                <input type="text" name="s2" size="1" id="s2" onblur="checkS2(<?php echo $count?>)" >
                <span class="error" id="s2-error" name="s2-error">Score must be zero or positive whole number</span>
                <input type="hidden" name="matchID" value="<?php echo htmlspecialchars($row["matchID"])?>">
                <input type="hidden" name="homeID" value="<?php echo htmlspecialchars($row["homeTeam"])?>">
                <input type="hidden" name="awayID" value="<?php echo htmlspecialchars($row["awayTeam"])?>">
            </form>
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

  </body>
</html>


