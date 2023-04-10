<?
  @unlink('tracks.zip');
  shell_exec('php downloadzip.php');
  echo json_encode([true]);
?>
