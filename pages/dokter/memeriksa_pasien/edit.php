<?php
include_once "../../../config/conn.php";
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id_dokter = $_SESSION['id'];

if ($akses != 'dokter') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id = $url[count($url) - 1];

$pasiens = query("SELECT
                          pasien.id AS id_pasien,
                          periksa.biaya_periksa AS biaya_periksa,
                          pasien.nama AS nama_pasien,
                          periksa.catatan AS catatan,
                          periksa.tgl_periksa AS tgl_periksa,
                          daftar_poli.id AS id_daftar_poli,
                          daftar_poli.no_antrian AS no_antrian,
                          daftar_poli.keluhan AS keluhan,
                          daftar_poli.status_periksa AS status_periksa
                        FROM pasien
                        INNER JOIN daftar_poli ON pasien.id = daftar_poli.id_pasien
                        INNER JOIN periksa ON daftar_poli.id = periksa.id_daftar_poli
                        WHERE periksa.id = '$id'")[0];

$obat = query("SELECT * FROM obat");

$selected_obat = [];
$detail_periksa = query("SELECT * FROM detail_periksa WHERE id_periksa='" . $id . "'");
foreach ($detail_periksa as $dp) {
  $selected_obat[] = $dp['id_obat'];
}
?>

<?php
$title = 'Poliklinik | Edit Periksa Pasien';

// Breadcrumb section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_dokter; ?>">Home</a></li>
  <li class="breadcrumb-item"><a href="<?= $base_dokter . '/memeriksa_pasien'; ?>">Daftar Periksa</a></li>
  <li class="breadcrumb-item active">Edit Periksa</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Edit Periksa Pasien
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Periksa</h3>
  </div>
  <div class="card-body">
    <form action="" method="POST">
      <!-- Kolom input untuk menambahkan data -->
      <div class="form-group">
        <label for="nama_pasien">Nama Pasien</label>
        <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?= $pasiens["nama_pasien"] ?>" disabled>
      </div>

      <div class="form-group">
        <label for="tgl_periksa">Tanggal Periksa</label>
        <input type="datetime-local" class="form-control" id="tgl_periksa" name="tgl_periksa" value="<?= $pasiens["tgl_periksa"] ?>">
      </div>

      <div class="form-group">
        <label for="catatan">Catatan</label>
        <input type="text" class="form-control" id="catatan" name="catatan" value="<?= $pasiens["catatan"] ?>">
      </div>

      <div class="form-group">
        <label for="nama_pasien">Obat</label>
        <select multiple="" class="form-control" name="obat[]" id="id_obat" multiple>
          <?php foreach ($obat as $obats) : ?>
            <?= var_dump($selected_obat); ?>
            <?php if (in_array($obats['id'], $selected_obat)) : ?>
              <option value="<?= $obats['id']; ?>|<?= $obats['harga'] ?>" selected><?= $obats['nama_obat']; ?> - <?= $obats['kemasan']; ?> - Rp.<?= $obats['harga']; ?></option>
            <?php else : ?>
              <option value="<?= $obats['id']; ?>|<?= $obats['harga'] ?>"> <?= $obats['nama_obat']; ?> - <?= $obats['kemasan']; ?> - Rp.<?= $obats['harga']; ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
          <label for="total_harga">Total Harga</label>
          <input type="text" class="form-control" id="harga" name="harga" readonly value="<?= $pasiens["biaya_periksa"] ?>">
      </div>

      <!-- Tombol untuk mengirim form -->
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" id="simpan_periksa" name="simpan_periksa">
          <i class="fa fa-save"></i> Simpan</button>
      </div>
    </form>

    <?php
    if (isset($_POST['simpan_periksa'])) {
      $biaya_periksa = 150000;
      $total_biaya_obat = 0;
      $obat = $_POST['obat'];
      $tgl_periksa = $_POST['tgl_periksa'];
      $catatan = $_POST['catatan'];
      $id_obat = [];
      for ($i = 0; $i < count($obat); $i++) {
        $data_obat = explode("|", $obat[$i]);
        // var_dump($data_obat);
        $id_obat[] = $data_obat[0];
        $total_biaya_obat += $data_obat[1];
      }
      $total_biaya = $biaya_periksa + $total_biaya_obat;
      // var_dump($total_biaya);
      // die();

      $id_daftar_poli = $pasiens['id_daftar_poli'];
      $query = "UPDATE periksa SET
                    tgl_periksa = '$tgl_periksa',
                    catatan = '$catatan',
                    biaya_periksa = '$total_biaya'
                  WHERE id_daftar_poli = $id_daftar_poli";
      $query2 = "DELETE FROM detail_periksa WHERE id_periksa = $id";
      $query3 = "INSERT INTO detail_periksa (id_obat, id_periksa) VALUES ";

      for ($i = 0; $i < count($id_obat); $i++) {
        $query3 .= "($id_obat[$i], $id),";
      }

      $query3 = substr($query3, 0, -1);

      $result = mysqli_query($conn, $query);
      $result2 = mysqli_query($conn, $query2);
      $result3 = mysqli_query($conn, $query3);

      if ($result && $result2 && $result3) {
        echo "
          <script>
            alert('Data berhasil diubah');
            document.location.href = '../ ';
          </script>
        ";
      } else {
        echo "
          <script>
            alert('Data gagal diubah');
            alert('$query');
            document.location.href = '../edit.php/$id';
          </script>
        ";
      }
    }
    ?>

  </div>
</div>
<script>
    $(document).ready(function() {
        $('#id_obat').select2();
        $('#id_obat').on('change.select2', function (e) {
            var selectedValuesArray = $(this).val();
            
            // Calculate the sum
            var sum = 150000;
            if (selectedValuesArray) {
                for (var i = 0; i < selectedValuesArray.length; i++) {
                    // Split the value and get the second part after "|"
                    var parts = selectedValuesArray[i].split("|");
                    console.log(parts);
                    if (parts.length === 2) {
                    sum += parseFloat(parts[1]);
                    }
                }
            }
            
            $('#harga').val(sum); 
        });
    });
</script>
<?php
$content = ob_get_clean();
ob_flush();

// JS Section
ob_start();
?>
<script>
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
  });
</script>
<?php
$js = ob_get_clean();
ob_flush();
?>

<?php include_once "../../../layouts/index.php"; ?>