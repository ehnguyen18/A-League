<!--20000820-->
<!--Pham Gia Huy Nguyen -->
<!--Wednesday 9:00AM-->
<?php
    require_once("conn.php");   
    $sql = "SELECT * FROM teams ORDER BY points DESC, goalDiff DESC";
    $results = $dbConn->query($sql)
    or die ('Problem with query: ' . $dbConn->error);       
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>A-League Assignment - Ladder</title>
    <link rel="stylesheet" href="css/projectMaster.css">
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
    <h1>A-League 2020/2021</h1>
    <p>*W = Win, L = Lost, D = Drawn</p>
    <table class="ladder">
        <tr>
            <th>#</th>
            <th>CLUB</th>
            <th>P</th>
            <th>W</th>
            <th>D</th>
            <th>L</th>
            <th>GF</th>
            <th>GA</th>
            <th>GD</th>
            <th>PTS</th>
            <th>LAST 5</th>
        </tr>
        <?php
            $rank = 0;
            while ($row = $results->fetch_assoc()) { 
                $rank++; $teamID=$row["teamID"];
                ?>
                <tr>
                <td><?php echo $rank?></td>
                <td><img src="images/<?php echo $row["emblem"]?>" width="23" height="23" alt="<?php echo $row["nickname"]?>">
                <span><?php echo $row["teamName"]?></span>
                </td>
                <td><?php echo $row["played"]?></td>
                <td><?php echo $row["won"]?></td>
                <td><?php echo $row["lost"]?></td>
                <td><?php echo $row["drawn"]?></td>
                <td><?php echo $row["goalsFor"]?></td>
                <td><?php echo $row["goalsAgainst"]?></td>
                <td><?php echo $row["goalDiff"]?></td>
                <td><strong><?php echo $row["points"]?></strong></td>
                <td>
                    <?php
                    require_once("conn.php");   
                    $sql2 = "SELECT homeTeam, awayTeam, score1, score2 FROM fixtures ";
                    $sql2 = $sql2 . "WHERE (('$teamID' = homeTeam) OR ('$teamID' = awayTeam)) AND (score1 IS NOT NULL) ";
                    $sql2 = $sql2 . "ORDER BY matchDate DESC, matchTime DESC ";
                    $rs = $dbConn->query($sql2)
                    or die ('Problem with query: ' . $dbConn->error); 
                    $count = 0; $str = "";
                    while ($r = $rs->fetch_assoc()){
                        if ($count==5) {
                            echo rtrim($str," - ");
                            break;
                        }
                        if ($teamID = $r["homeTeam"])
                        {
                            if ($r["score1"] > $r["score2"]) $str = $str . "W - ";
                            else if ($r["score1"] < $r["score2"]) $str = $str . "L - ";
                                else $str = $str . "D - ";
                            $count++;
                        }
                        else {
                            if ($r["score1"] > $r["score2"]) $str = $str . "L - ";
                            else if ($r["score1"] < $r["score2"]) $str = $str . "W - ";
                                else $str = $str . "D - ";
                            $count++;
                        }
                    }                  
                    ?>
                </td>
            </tr>
        <?php } 
        $dbConn->close(); ?>
    </table>
</body>
</html>