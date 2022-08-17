<!--20000820-->
<!--Pham Gia Huy Nguyen -->
<!--Wednesday 9:00AM-->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>A-League Assignment - Venue</title>
    <link rel="stylesheet" href="css/projectMaster.css">
  </head>
  <body>
  <nav>
    <ul> 
        <a class="alogo" href="index.html"><img class="navlogo" src="images/ALeague.png"></a>
        <li><a href="ladder.html">Ladder</a></li>
        <li><a href="fixtures.html">Fixtures</a></li>
        <li><a href="scoreEntry.html">Enter Results</a></li>
        <li><a href="logoff.html">Logoff</a></li>
    </ul>
</nav>
    <h1>Venue Info</h1>
      <table>
          <tr>
            <th>Venue Name</th>
            <th>Address</th>
          </tr>
          <?php while ($row = $results->fetch_assoc()) { ?>
          <tr>           
              <td><?php echo $row["venueName"]?></td>
              <td><?php echo $row["address"]?></td>
          </tr>
          
          <script>
              function initMap() {
                venue = { lat: <?php echo $row["latitude"]?>, lng: <?php echo $row["longitude"]?> };
                const map = new google.maps.Map(document.getElementById("map"), {
                  zoom: 15,
                  center: venue,
                });
                const marker = new google.maps.Marker({
                  position: venue,
                  map: map,
                });
              }
            </script>
            
            
          <?php } ?>
      </table>
      <div id="map">
        <script
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdMX-Spep1HtXRcdvngO1pCD3Rzccrlvk&callback=initMap&libraries=&v=weekly"
          async
          ></script>
      </div>  
  </body>
</html>