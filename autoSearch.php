<?
  function iso8601ToSeconds($input) {
    $duration = new DateInterval($input);
    $hours_to_seconds = $duration->h * 60 * 60;
    $minutes_to_seconds = $duration->i * 60;
    $seconds = $duration->s;
    return $hours_to_seconds + $minutes_to_seconds + $seconds;
  }

  $APIKEY='AIzaSyC5lOr7yMZPnNmc2zNyRc1hLcrUHP7DPsQ';
  //$APIKEY='AIzaSyC5lOr7yMZPnNmc2zNyRc1hLcrUHP7DPsQ';
  $maxSeconds=600;

  require('db.php');

  /*
  $data = json_decode(file_get_contents('php://input'));
  $sparam = mysqli_real_escape_string($link, $data->{'sparam'});
  $exact = mysqli_real_escape_string($link, $data->{'exact'});
  $allWords = mysqli_real_escape_string($link, $data->{'allWords'});
  */

  if(sizeof($argv) > 1){
    array_shift($argv);
    $sparam = implode(' ', $argv);
  }else{
    $sparam = $_GET['sparam'];
  }

  $exact = false;
  $allWords = false;

  if(!$sparam) die();
  $sparam = urlencode($sparam);
  $searchURL = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=50&q=$sparam&key=$APIKEY";
  $res = file_get_contents($searchURL);
  $data=json_decode($res);
  $v_ids=[];
  $i=0;
  forEach($data->{'items'} as $item){
    $v_ids[]=($i?'&':'?').'id='.($item->{'id'}->{'videoId'});
    $i++;
  }
  $v_ids=implode('',$v_ids);
  $detailsURL = "https://www.googleapis.com/youtube/v3/videos?part=contentDetails&part=snippet&id=$v_ids&key=$APIKEY";
  $details=file_get_contents($detailsURL);
  $detailsData=json_decode($details)->{'items'};
  forEach($detailsData as $details){
    $v_id=$details->{'id'}->{'videoId'};
    $title = $details->{'snippet'}->{'title'};
    $duration=iso8601ToSeconds($details->{'contentDetails'}->{'duration'});
    $details->{'contentDetails'}->{'duration'}=$duration<=$maxSeconds?$duration:0;
  }
  $detailsData = array_filter($detailsData, function($v) { return !!$v->{'contentDetails'}->{'duration'}; });
  $ret=[];
  forEach($detailsData as $details){
    $ret[]=$details;
  }
  $detailsData=$ret;

  echo json_encode([true, $detailsData[0]->{'id'}]);

?>
