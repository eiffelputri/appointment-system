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

if ($akses != 'admin') {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}
?>
<?php
$title = 'Poliklinik | Obat';
// Breadcrumb section
ob_start();?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
  <li class="breadcrumb-item active">Obat</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start();?>
Tambah / Edit Pasien
<?php
$main_title = ob_get_clean();
ob_flush();


// Content section
ob_start();

?>
<form class="form col" method="POST" action="" required name="myForm" onsubmit="return(validate());">
<?php
    $nama = '';
    $alamat = '';
    $no_hp = '';
    $no_ktp = '';
    $no_rm = '';
    if (isset($_GET['id'])) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM pasien WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nama = $row['nama'];
                $alamat = $row['alamat'];
                $no_hp = $row['no_hp'];
                $no_ktp = $row['no_ktp'];
                $no_rm = $row['no_rm'];
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    ?>
        <input type="hidden" required name="id" value="<?php echo $_GET['id'] ?>">

    <?php
    }
    
    // Jika sedang dalam mode ubah, isi Nomor RM sesuai data yang diubah
    if (isset($_GET['id'])) {
        $no_rm = $no_rm;
    } else {
        // Jika menambahkan data baru, hitung Nomor RM sesuai format
        $tahun_bulan = "20" . date("ym");
        $query_last_id = "SELECT MAX(id) as max_id FROM pasien";
        $result_last_id = $pdo->query($query_last_id);
        $row_last_id = $result_last_id->fetch(PDO::FETCH_ASSOC);
        $last_inserted_id = $row_last_id['max_id'] ? $row_last_id['max_id'] : 0;

        $no_rm = $tahun_bulan . "-" . ($last_inserted_id + 1);
    }
    ?>
        <div class="row mt-3">
            <label for="nama" class="form-label fw-bold">
                Nama Pasien
            </label>
            <input type="text" class="form-control" required name="nama" id="nama" placeholder="Nama Pasien" value="<?php echo $nama ?>">
        </div>
        <div class="row mt-3">
            <label for="alamat" class="form-label fw-bold">
                Alamat
            </label>
            <input type="text" class="form-control" required name="alamat" id="alamat" placeholder="alamat" value="<?php echo $alamat ?>">
        </div>
        <div class="row mt-3">
            <label for="no_ktp" class="form-label fw-bold">
                Nomor KTP
            </label>
            <input type="number" class="form-control" required name="no_ktp" id="no_ktp" placeholder="no_ktp" value="<?php echo $no_ktp ?>">
        </div>

        <div class="row mt-3">
            <label for="no_hp" class="form-label fw-bold">
                Nomor Hp
            </label>
            <input type="number" class="form-control" required name="no_hp" id="no_hp" placeholder="no_hp" value="<?php echo $no_hp ?>">
        </div>

        <div class="row mt-3">
            <label for="no_rm" class="form-label fw-bold">
                Nomor RM
            </label>
            <input type="text" class="form-control" required name="no_rm" id="no_rm" placeholder="no_rm" value="<?php echo $no_rm ?>">
        </div>


        <div class="row d-flex mt-3 mb-3">
          <button type="submit" class="btn btn-primary" style="width: 3cm;" required name="simpan">Simpan</button>
        </div>
</form>

<div class="row d-flex mt-3 mb-3">
  <a href="<?= $base_admin.'/Pasien' ?>">
    <button class="btn btn-secondary ml-2" style="width: 3cm;">Reset</button>
  </a>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Pasien</h3>
  </div>
  <div class="card-body">
    <table id="example1" class="table table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama</th>
          <th scope="col">Alamat</th>
          <th scope="col">No. KTP</th>
          <th scope="col">No. Hp</th>
          <th scope="col">No. RM</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $pdo->query("SELECT * FROM pasien");
        $no = 1;
        while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data['nama'] ?></td>
              <td><?php echo $data['alamat'] ?></td>
              <td><?php echo $data['no_ktp'] ?></td>
              <td><?php echo $data['no_hp'] ?></td>
              <td><?php echo $data['no_rm'] ?></td>
              <td>
                  <a class="btn btn-success" href="index.php?id=<?php echo $data['id'] ?>">Ubah</a>
                  <a class="btn btn-danger" href="index.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
              </td>
          </tr>
      <?php
      }
      ?>
      </tbody>
    </table>
    <?php
      if (isset($_POST['simpan'])) {
        if (isset($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE pasien SET 
                                    nama = :nama,
                                    alamat = :alamat,
                                    no_ktp = :no_ktp,
                                    no_hp = :no_hp,
                                    no_rm = :no_rm
                                    WHERE
                                    id = :id");

            $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
            $stmt->bindParam(':no_ktp', $_POST['no_ktp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_rm', $_POST['no_rm'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location:index.php');

        } else {
            $stmt = $pdo->prepare("INSERT INTO pasien(nama, alamat, no_ktp, no_hp, no_rm) 
                                    VALUES (:nama, :alamat, :no_ktp, :no_hp, :no_rm)");

            $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
            $stmt->bindParam(':no_ktp', $_POST['no_ktp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_INT);
            $stmt->bindParam(':no_rm', $_POST['no_rm'], PDO::PARAM_STR);
            $stmt->execute();

            header('Location:index.php');
        }
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $stmt = $pdo->prepare("DELETE FROM pasien WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    header('Location:index.php');
    
    }
    ?>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>