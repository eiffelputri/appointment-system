<?php 
session_start();

session_destroy();

echo "<meta http-equiv='refresh' content='0; url=http://" . $_SERVER['HTTP_HOST'] . "/bk-poliklinik'>";
die();
?>