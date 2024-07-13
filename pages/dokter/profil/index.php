<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id = $_SESSION['id'];

if ($akses != 'dokter') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

$dokter = query("SELECT * FROM dokter WHERE id = $id")[0];

if (isset($_POST["submit"])) {
  // cek apakah data berhasil di ubah atau tidak
  if (ubahDokter($_POST) > 0) {
    $_SESSION['username'] = $_POST['nama'];

    echo "
        <script>
            alert('Data berhasil diubah');
            document.location.href = '../profil';
        </script>
    ";
    session_write_close();
    header("Refresh:0"); // Me-refresh halaman setelah perubahan data
    exit;
  } else {
    echo "
        <script>
            alert('Data Gagal diubah');
            document.location.href = '../profil';
        </script>
    ";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= getenv('APP_NAME') ?> | Profil</title>

  <?php include "../../../layouts/plugin_header.php" ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="http://<?= $_SERVER['HTTP_HOST'] ?>/bk-poliklinik/dist/img/Logo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <?php include "../../../layouts/header.php" ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Profil <?= ucwords($_SESSION['akses']) ?></h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit <small>Data Dokter</small></h3>
          </div>
          <form id="editForm" action="" method="POST">
            <input type="hidden" name="id" value="<?= $dokter["id"]; ?>">
            <div class="card-body">
              <div class="form-group">
                <label for="nama">Nama Dokter</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= $dokter['nama']; ?>">
              </div>
              <div class="form-group">
                <label for="alamat">Alamat Dokter</label>
                <input type="text" id="alamat" name="alamat" class="form-control" value="<?= $dokter['alamat']; ?>">
              </div>
              <div class="form-group">
                <label for="no_hp">Telepon Dokter</label>
                <input type="number" id="no_hp" name="no_hp" class="form-control" value="<?= $dokter['no_hp']; ?>">
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" name="submit" id="submitButton" class="btn btn-primary" disabled>Simpan Perubahan</button>
              </div>
            </div>
          </form>
        </div>
      </section>

      <script>
        const form = document.getElementById('editForm');
        const inputs = form.querySelectorAll('input');

        const checkChanges = () => {
          let changes = false;
          inputs.forEach(input => {
            if (input.defaultValue !== input.value) {
              changes = true;
            }
          });
          return changes;
        };

        const toggleSubmit = () => {
          const submitButton = document.getElementById('submitButton');
          if (checkChanges()) {
            submitButton.disabled = false;
          } else {
            submitButton.disabled = true;
          }
        };

        inputs.forEach(input => {
          input.addEventListener('input', toggleSubmit);
        });
      </script>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include "../../../layouts/footer.php"; ?>
  </div>
  <!-- ./wrapper -->
  <?php include "../../../layouts/pluginsexport.php"; ?>
</body>

</html>