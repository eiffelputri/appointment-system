<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'dokter') {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id = $url[count($url) - 1];

// Hapus data
// if (hapusJadwalPeriksa($id) > 0) {
//   echo "
//     <script>
//         alert('Data berhasil dihapus');
//         document.location.href = '../';
//     </script>
//   ";
// } else {
//   echo "
//     <script>
//         alert('Data gagal dihapus');
//         document.location.href = '../';
//     </script>
//   ";
// }

?>