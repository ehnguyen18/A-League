<!--20000820-->
<!--Pham Gia Huy Nguyen -->
<!--Wednesday 9:00AM-->
<?php
    require_once("nocache.php");
    $errorMessage = '';

    if (isset($_POST["submit"])) {
        if(empty($_POST['username']) || empty($_POST['password']))
            $errorMessage = "Both username and password are required";
        else {
        require_once("conn.php");
        $username = $dbConn->escape_string($_POST["username"]);
        $password = $dbConn->escape_string($_POST["password"]);
        $hashedPassword = hash('sha256', $password);
        $sql = "SELECT * FROM leagueadmin WHERE email = '$username' and password = '$hashedPassword'";
        $results = $dbConn->query($sql)
        or die ('Problem with query: ' . $dbConn->error);
        
        if($results->num_rows) {
            session_start();
            $_SESSION['logged'] = "YES";
            header("location: scoreEntry.php");
        }
        else $errorMessage = "Invalid Username or Password";
        }
    }          
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>A-League Assignment - Login</title>
    <link rel="stylesheet" href=" css/projectMaster.css">
    <style>
      input[type="text"] {border: 1px solid black;}
    </style>
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
    <form id="exercise4Form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h1>Administrator Login</h1>
        <p style="color:red;"><?php echo $errorMessage;?></p>
        <label for="username" class="loginlabel">Username: </label>
        <input type="text" name="username" size="25" id="username" value="<?php if (isset($_POST["submit"])) print $username ?>"><br>
        
        <label for="password" class="loginlabel">Password: </label>       
        <input type="password" name="password" size="25" id="password" value="<?php if (isset($_POST["submit"])) print $password ?>">
      <p><input type="submit" name="submit"></p>
    </form>
</body>
</html>