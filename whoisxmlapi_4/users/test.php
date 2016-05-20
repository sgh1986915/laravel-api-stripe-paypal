<?php
foreach (glob("permissions/*.conf") as $filename) {
  echo "$filename size " . filesize($filename) . "\n";
}
?>
