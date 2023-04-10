<?
  $url = urldecode($_GET['url']);
  if(!$url){
    echo 'no file specified!';
    die();
	}
  $cwd = ($l=explode('/', shell_exec('pwd')))[sizeof($l)-2];
?>
<!DOCTYPE html>
<html>
  <head>
    <style>
      body, html{
        background: #000;
        margin: 0;
        overflow: hidden;
        height: 100vh;
      }
      #player{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: scale(1) translate(-50%, -50%);
      }
    </style>
  </head>
  <body>
    <iframe id="player" style="width:640px;height:290px;border:none;vertical-align:top;"></iframe>
    <script>
      function fixedEncodeURIComponent (str) {
        return encodeURIComponent(str).replace(/[!'()*]/g, escape);
      }
		  player=document.querySelector('#player')
			file = (l=window.location.href.split("/t/"))[l.length-1]
      console.log(file)
      player.src="https://audioplayer.rotoblaster.com/https://audiobot.rotoblaster.com/<?=$cwd?>/tracks/" + file
		</script>
  </body>
<html>
