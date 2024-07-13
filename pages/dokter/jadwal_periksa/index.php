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
?>

<?php
$title = 'Poliklinik | Jadwal Periksa';
// Breadcrumb section
ob_start();?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_dokter; ?>">Home</a></li>
  <li class="breadcrumb-item active">Jadwal Periksa</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start();?>
Jadwal Periksa
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-6">
        <h3 class="card-title">Daftar Jadwal Periksa</h3>
      </div>
      <div class="col-6">
        <a href="create.php" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Jadwal Periksa</a>
      </div>
    </div>
  </div>
  <div class="card-body">
    <table id="tabel-jadwal-periksa" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Dokter</th>
          <th>Hari</th>
          <th>Jam Mulai</th>
          <th>Jam Selesai</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $data = $pdo->prepare("SELECT 
                                d.nama as nama_dokter, 
                                p.id as id,
                                p.hari as hari,
                                p.jam_mulai as jam_mulai,
                                p.jam_selesai as jam_selesai,
                                p.aktif as aktif
                                FROM jadwal_periksa p INNER JOIN dokter d ON p.id_dokter = d.id
                                WHERE d.id = '$id'");
        $data->execute();
        if ($data->rowCount() == 0) {
          echo "<tr><td colspan='7' align='center'>Tidak ada data</td></tr>";
        } else {
          while($d = $data->fetch()) {
        ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= $d['nama_dokter'] ?></td>
          <td><?= $d['hari'] ?></td>
          <td><?= $d['jam_mulai'] ?></td>
          <td><?= $d['jam_selesai'] ?></td>
          <td><?= $d['aktif'] == 'Y' ? 'Aktif' : 'Tidak Aktif' ?></td>
          <td>
            <a href="edit.php/<?= $d['id']?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
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
<?php
$content = ob_get_clean();
ob_flush();

// JS Section
ob_start();?>
<script>
  $(document).ready(function() {
    $('.delete-button').on('click', function(e) {
      return confirm('Apakah anda yakin ingin menghapus data ini?');
    });
  });
</script>
<?php
$js = ob_get_clean();
ob_flush();
?>

<?php include_once("../../../layouts/index.php"); ?>