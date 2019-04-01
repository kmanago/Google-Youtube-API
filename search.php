<?php

//gets the values from google map and assigns them to the php values
$q = $_GET['keyword'];
$lat = $_GET['latitude'];
$lng= $_GET['longitude'];
$locationRadius = $_GET['radius'];
$maxResults=$_GET['max'];
$location = $lat . ",".$lng;

//testing to see if it prints out all values it received
//can be deleted after testing
//echo "<h3>Values passed over</h3>";
//echo "Keyword: ". $q ."<br>";
//echo "Latitude: ".$lat ."<br>";
//echo "Longitude: ".$lng ."<br>";
//echo "Complete location (lat, long): ".$location ."<br>";
//echo "Location Radius: ".$locationRadius."<br>";
//echo "Max Results: ".$maxResults ."<br><br>";


//makes the assumption that you have the google-api-php-client-2.1.1_PHP54.zip files installed into the 
//project folder in XAMPP or you have composer up and running properly
require_once __DIR__ . '/google-api-php-client-2.0.0-RC8/vendor/autoload.php';
$htmlBody = null;
//$htmlBody = <<<END
//<form method="GET">
//  <div>
//    Search Term: <input type="search" id="q" name="q" placeholder="Enter Search Term">
//  </div>
//  <div>
//    Location: <input type="text" id="location" name="location" placeholder="37.42307,-122.08427">
//  </div>
//  <div>
//    Location Radius: <input type="text" id="locationRadius" name="locationRadius" placeholder="5km">
//  </div>
//  <div>
//    Max Results: <input type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="25">
//  </div>
//  <input type="submit" value="Search">
//</form>
//END;
// This code executes if the user enters a search query in the form
// and submits the form. Otherwise, the page displays the form above.
//if (isset($_GET['q']) && isset($_GET['maxResults'])) {

  /*
   * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
  * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
  * Please ensure that you have enabled the YouTube Data API for your project.
  */
  $DEVELOPER_KEY = 'AIzaSyC9A2a6sTMO8RS1RcE4yHEsSr24I1FKcD8';
  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);
  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);
  try {
    // Call the search.list method to retrieve results matching the specified
    // query term.
//    $searchResponse = $youtube->search->listSearch('id,snippet', array(
//        'type' => 'video',
//        'q' => $_GET['q'],
//        'location' =>  $_GET['location'],
//        'locationRadius' =>  $_GET['locationRadius'],
//        'maxResults' => $_GET['maxResults'],
//    ));
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
        'type' => 'video',
        'q' => $q,
        'location' =>  $location,
        'locationRadius' =>  $locationRadius,
        'maxResults' => $maxResults
    ));
    $videoResults = array();
   //   echo json_encode($videoResults);
    # Merge video ids
    foreach ($searchResponse['items'] as $searchResult) {
      array_push($videoResults, $searchResult['id']['videoId']);
    }
    $videoIds = join(',', $videoResults);
    # Call the videos.list method to retrieve location details for each video.
    $videosResponse = $youtube->videos->listVideos('snippet, recordingDetails', array(
    'id' => $videoIds,
    ));
    $videos = '';
    $videoURL = 'https://www.youtube.com/embed/';
    // Display the list of matching videos.
    foreach ($videosResponse['items'] as $videoResult) {
      //$videoId = $videoResult['id'];
      $videos .= sprintf('<b>%s</b><br><li><iframe src = "%s%s"></iframe></li>', $videoResult['snippet']['title'],$videoURL, $videoResult['id']);
      //$thumbnails .= sprintf('<div></div>');
      //$videos .= sprintf('<li><iframe src = "https://www.youtube.com/embed/'$videoId'"></iframe></li>');
      /*$videos .= sprintf('<li>%s (%s,%s)</li>',
          $videoResult['snippet']['title'],
          $videoResult['recordingDetails']['location']['latitude'],
          $videoResult['recordingDetails']['location']['longitude']);*/
    }
      
    $htmlBody .= <<<END
    <h3>Videos</h3>
    <div>$videos</div>
    <u1></u1>
END;
  } catch (Google_Service_Exception $e) {
  //  $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
  //      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
 //   $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
 //       htmlspecialchars($e->getMessage()));
  }
//}
  //  header('Location: index.php');

?>


<!doctype html>
<html>
<head>
<title>YouTube Geolocation Search</title>
</head>
<body>
  <?=$htmlBody?>
</body>
</html>
