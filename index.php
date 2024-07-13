<?php
session_start();

$muncul = false;
$arah = null;

if (isset($_SESSION['login'])) {
  $muncul = true;
  $arah = $_SESSION['akses'];
}if (isset($_SESSION['signup'])) {
  $muncul = true;
  $arah = $_SESSION['akses'];
}
?>

<?php
$title = 'Poliklinik';
if ($muncul) :
  // ob_start();
  ?>
  <!-- <h1>haloo</h1> -->
  <?php
  // $content = ob_get_clean();
  include_once './layouts/welcome.php';
else:
  include_once './layouts/welcome.php';
endif ?>
