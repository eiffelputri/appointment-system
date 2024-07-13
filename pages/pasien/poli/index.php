<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['signup']) || isset($_SESSION['login'])) {
  $_SESSION['signup'] = true;
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}
$id_pasien = $_SESSION['id'];
$no_rm = $_SESSION['no_rm'];
$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'pasien') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

if (isset($_POST['submit'])) {

  if ($_POST['id_jadwal'] == "900") {
    echo "
        <script>
            alert('Jadwal tidak boleh kosong!');
        </script>
    ";
    echo "<meta http-equiv='refresh' content='0>";
  }

  if (daftarPoli($_POST) > 0) {
    echo "
        <script>
            alert('Berhasil mendaftar poli');
        </script>
    ";
} else {
    echo "
        <script>
            alert('Gagal mendaftar poli');
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
  <title><?= getenv('APP_NAME') ?> | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../../plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


  <?php include "../../../layouts/header.php"?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Daftar Poli</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Daftar Poli</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

      <div class="row">
        <div class="col-4">
          <!-- Registrarion poli -->
          <div class="card">
            <h5 class="card-header bg-primary">Daftar Poli</h5>
            <div class="card-body">

              <form action="" method="POST">
                <input type="hidden" value="<?= $id_pasien ?>" name="id_pasien">
                <div class="mb-3">
                  <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                  <input type="text " class="form-control" id="no_rm" placeholder="nomor rekam medis" name="no_rm" value="<?= $no_rm ?>" disabled>
                </div>

                <div class="mb-3">
                  <label for="inputPoli" class="form-label">Pilih Poli</label>
                  <select id="inputPoli" class="form-control">
                    <option>Open this select menu</option>
                    <?php
                    $data = $pdo->prepare("SELECT * FROM poli");
                    $data->execute();
                    if ($data->rowCount() == 0) {
                      echo "<option>Tidak ada poli</option>";
                    } else {
                      while($d = $data->fetch()) {
                    ?>
                      <option value="<?= $d['id'] ?>"><?= $d['nama_poli'] ?></option> 
                    <?php
                      }
                    }
                    ?>
                  </select>
                </div>
                
                <div class="mb-3">
                  <label for="inputJadwal" class="form-label">Pilih Jadwal</label>
                  <select id="inputJadwal" class="form-control" name="id_jadwal">
                    <option value="900">Open this select menu</option>
                    <?php
                    $data = $pdo->prepare("SELECT * FROM jadwal_periksa");
                    $data->execute();
                    if ($data->rowCount() == 0) {
                      echo "<option>Tidak ada poli</option>";
                    } else {
                      while($d = $data->fetch()) {
                    ?>
                      <option value="<?= $d['id'] ?>"><?= $d['hari'] ?>,<?= $d['jam_mulai'] ?> - <?= $d['jam_selesai'] ?></option> 
                    <?php
                      }
                    }
                    ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="keluhan" class="form-label">Keluhan</label>
                  <textarea class="form-control" id="keluhan" rows="3" name="keluhan"></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Daftar</button>
              </form>
              
            </div>
          </div>
          <!-- End registrarion poli -->
        </div>

        <div class="col-8">
          <!-- Registration poli history -->
          <div class="card">
            <h5 class="card-header bg-primary">Riwayat daftar poli</h5>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Poli</th>
                    <th scope="col">Dokter</th>
                    <th scope="col">Hari</th>
                    <th scope="col">Mulai</th>
                    <th scope="col">Selesai</th>
                    <th scope="col">Antrian</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>

                    <?php
                    $poli = $pdo->prepare("SELECT d.nama_poli as poli_nama,
                                                  c.nama as dokter_nama, 
                                                  b.hari as jadwal_hari, 
                                                  b.jam_mulai as jadwal_mulai, 
                                                  b.jam_selesai as jadwal_selesai,
                                                  a.no_antrian as antrian,
                                                  a.id as poli_id

                                                  FROM daftar_poli as a

                                                  INNER JOIN jadwal_periksa as b
                                                    ON a.id_jadwal = b.id
                                                  INNER JOIN dokter as c
                                                    ON b.id_dokter = c.id
                                                  INNER JOIN poli as d
                                                    ON c.id_poli = d.id
                                                  WHERE a.id_pasien = $id_pasien
                                                  ORDER BY a.id desc");
                    $poli->execute();
                    $no = 0;
                    if ($poli->rowCount() == 0) {
                      echo "Tidak da data";
                    } else {
                      while($p = $poli->fetch()) {
                    ?>
                    <tr>
                      <th scope="row">

                        <?php
                          ++$no;
                          if ($no == 1) {
                            echo "<span class='badge badge-info'>New</span>";
                          } else {
                            echo $no;
                          }
                        ?>
                      </th>
                      <td><?= $p['poli_nama']?></td>
                      <td><?= $p['dokter_nama']?></td>
                      <td><?= $p['jadwal_hari']?></td>
                      <td><?= $p['jadwal_mulai']?></td>
                      <td><?= $p['jadwal_selesai']?></td>
                      <td><?= $p['antrian']?></td>
                      <td>
                        <a href="detail_poli.php/<?= $p['poli_id']?>">
                            <button class="btn btn-success btn-sm">Detail</button>
                        </a>
                      </td>
                    </tr>
                    <?php
                      }
                    }
                    ?>

                </tbody>
              </table>
            </div>
          </div>
        <!-- End registration poli history -->
        </div>
      </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include "../../../layouts/footer.php"; ?>
</div>
<!-- ./wrapper -->
<?php include "../../../layouts/pluginsexport.php"; ?>

<script>
  document.getElementById('inputPoli').addEventListener('change', function() {
    var poliId = this.value; // Ambil nilai ID poli yang dipilih
    loadJadwal(poliId); // Panggil fungsi untuk memuat jadwal dokter
  });

  function loadJadwal(poliId) {
    // Buat objek XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Konfigurasi permintaan Ajax
    xhr.open('GET', 'http://localhost/bk-poliklinik/pages/pasien/poli/get_jadwal.php?poli_id=' + poliId, true);

    // Atur header untuk menentukan bahwa respons yang diharapkan adalah HTML
    xhr.setRequestHeader('Content-Type', 'text/html');

    // Atur fungsi callback ketika permintaan selesai
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Jika permintaan berhasil, perbarui opsi pada select pilih jadwal
        document.getElementById('inputJadwal').innerHTML = xhr.responseText;
      }
    };

    // Kirim permintaan
    xhr.send();
  }
</script>
</body>
</html>