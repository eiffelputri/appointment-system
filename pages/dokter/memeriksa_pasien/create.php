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
$obat = query("SELECT * FROM obat");

$pasiens = query("SELECT
                    p.nama AS nama_pasien,
                    dp.id AS id_daftar_poli
                FROM pasien p
                INNER JOIN daftar_poli dp ON p.id = dp.id_pasien
                WHERE p.id = '$id'")[0];


$biaya_periksa = 150000;
$total_biaya_obat = 0;

?>

<?php
$title = 'Poliklinik | Periksa Pasien';

// Breadcrumb section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="<?= $base_dokter; ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= $base_dokter . '/memeriksa_pasien'; ?>">Daftar Periksa</a></li>
    <li class="breadcrumb-item active">Periksa Pasien</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Periksa Pasien
<?php
$main_title = ob_get_clean();
ob_flush();
// Content section
ob_start();
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Periksa Pasien</h3>
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
                <input type="datetime-local" class="form-control" id="tgl_periksa" name="tgl_periksa">
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>
                <input type="text" class="form-control" id="catatan" name="catatan">
            </div>

            <div class="form-group">
                <label for="nama_pasien">Obat</label>
                <select class="form-control" name="obat[]" multiple id="id_obat">
                    <?php foreach ($obat as $obats) : ?>
                        <option value="<?= $obats['id']; ?>|<?= $obats['harga'] ?>"><?= $obats['nama_obat']; ?> - <?= $obats['kemasan']; ?> - Rp.<?= $obats['harga']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="total_harga">Total Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" readonly>
            </div>

            <!-- Tombol untuk mengirim form -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" id="simpan_periksa" name="simpan_periksa">
                    <i class="fa fa-save"></i> Simpan</button>
            </div>
        </form>
        <?php
        if (isset($_POST['simpan_periksa'])) {
            $tgl_periksa = $_POST['tgl_periksa'];
            $catatan = $_POST['catatan'];
            $obat = $_POST['obat'];
            $id_daftar_poli = $pasiens['id_daftar_poli'];
            $id_obat = [];
            for ($i = 0; $i < count($obat); $i++) {
                $data_obat = explode("|", $obat[$i]);
                $id_obat[] = $data_obat[0];
                $total_biaya_obat += $data_obat[1];
            }
            $total_biaya = $biaya_periksa + $total_biaya_obat;

            $query = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) VALUES
                    ($id_daftar_poli, '$tgl_periksa', '$catatan', '$total_biaya')";
            $result = mysqli_query($conn, $query);

            $query2 = "INSERT INTO detail_periksa (id_obat, id_periksa) VALUES ";
            $periksa_id = mysqli_insert_id($conn);
            for ($i = 0; $i < count($id_obat); $i++) {
                $query2 .= "($id_obat[$i], $periksa_id),";
            }
            $query2 = substr($query2, 0, -1);
            $result2 = mysqli_query($conn, $query2);

            $query3 = "UPDATE daftar_poli SET status_periksa = '1'
                        WHERE id = $id_daftar_poli";
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

// // JS Section
// ob_start();
?>

<?php include_once "../../../layouts/index.php"; ?>