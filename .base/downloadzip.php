<?
  $zip = new ZipArchive();
  $filename = "./tracks.zip";
  if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
  }
  foreach(glob("./tracks/*") as $file){
    $zip->addFromString(basename($file),  file_get_contents($file));
  }
  $zip->close();
  echo json_encode([true]);
?>
