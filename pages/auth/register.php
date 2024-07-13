<?php
session_start();
include_once("../../config/conn.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Mendapatkan nilai dari form -- atribut name di input
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $no_ktp = $_POST['no_ktp'];
  $no_hp = $_POST['no_hp'];

  //   -------   SITUASI 1 -------

  // Cek apakah pasien sudah terdaftar berdasarkan nomor KTP
  $query_check_pasien = "SELECT id, nama ,no_rm FROM pasien WHERE no_ktp = '$no_ktp'";
  $result_check_pasien = mysqli_query($conn, $query_check_pasien);

  if (mysqli_num_rows($result_check_pasien) > 0) {
    $row = mysqli_fetch_assoc($result_check_pasien);

    if ( $row['nama'] != $nama) {
      // ketika nama tidak sesuai dengan no_ktp
      echo "<script>alert(`Nama pasien tidak sesuai dengan nomor KTP yang terdaftar.`);</script>";
      echo "<meta http-equiv='refresh' content='0; url=register.php'>";
      die();
  }
    $_SESSION['signup'] = true;
    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $nama;
    $_SESSION['no_rm'] = $row['no_rm'];
    $_SESSION['akses'] = 'pasien';

    echo "<meta http-equiv='refresh' content='0; url=../pasien'>";
    die();
  }
  

  //   -------   SITUASI 2 -------

  // Query untuk mendapatkan nomor pasien terakhir - YYYYMM-XXX - 202312-004
  $queryGetRm = "SELECT MAX(SUBSTRING(no_rm, 8)) as last_queue_number FROM pasien";
  $resultRm = mysqli_query($conn, $queryGetRm);

  // Periksa hasil query
  if (!$resultRm) {
      die("Query gagal: " . mysqli_error($conn));
  }

  // Ambil nomor antrian terakhir dari hasil query
  $rowRm = mysqli_fetch_assoc($resultRm);
  $lastQueueNumber = $rowRm['last_queue_number'];

  // Jika tabel kosong, atur nomor antrian menjadi 0
  $lastQueueNumber = $lastQueueNumber ? $lastQueueNumber : 0;

  // ---

  // Mendapatkan tahun saat ini (misalnya, 202312)
  $tahun_bulan = date("Ym");

  // Membuat nomor antrian baru dengan menambahkan 1 pada nomor antrian terakhir
  $newQueueNumber = $lastQueueNumber + 1;

  // Menyusun nomor rekam medis dengan format YYYYMM-XXX
  $no_rm = $tahun_bulan . "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);


  // ---

  // Lakukan operasi INSERT
  $query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm')";

  // Eksekusi query
  if (mysqli_query($conn, $query)) {
    // Set session variables
    $_SESSION['signup'] = true;  //Menandakan langsung ke dashboard
    $_SESSION['id'] = mysqli_insert_id($conn); //mengambil id terakhir
    $_SESSION['username'] = $nama;
    $_SESSION['no_rm'] = $no_rm;
    $_SESSION['akses'] = 'pasien';

    // Redirect ke halaman dashboard
    echo "<meta http-equiv='refresh' content='0; url=../pasien'>";
    die();
  } else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
  }


  // Tutup koneksi database
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | Registration Page (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>Poli</b>klinik</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new account</p>

      <!-- nama -->
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" required placeholder="Full name" name="nama" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <!-- alamat -->
        <div class="input-group mb-3">
          <input type="text" class="form-control" required placeholder="alamat" name="alamat" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fa fa-map-marker"></span>
            </div>
          </div>
        </div>
        <!-- no ktp -->
        <div class="input-group mb-3">
          <input type="number" class="form-control" required placeholder="No ktp" name="no_ktp" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fa fa-address-book"></span>
            </div>
          </div>
        </div>
        <!-- no hp -->
        <div class="input-group mb-3">
          <input type="number" class="form-control" required placeholder="NO HP" name="no_hp" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone-square"></span>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" required name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

</body>
</html>
