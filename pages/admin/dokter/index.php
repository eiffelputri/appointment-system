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
Tambah / Edit Dokter
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm" onsubmit="return(validate());">
        <?php
        $nama = '';
        $alamat = '';
        $no_hp = '';
        $id_poli = 0;
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM dokter WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nama = $row['nama'];
                    $alamat = $row['alamat'];
                    $no_hp = $row['no_hp'];
                    $id_poli = $row['id_poli'];
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        ?>
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
        <?php
        }
        ?>
        <div class="row mt-3">
            <label for="nama" class="form-label fw-bold">
                Nama Dokter
            </label>
            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Dokter" value="<?php echo $nama ?>">
        </div>
        <div class="row mt-3">
            <label for="alamat" class="form-label fw-bold">
                alamat
            </label>
            <input type="text" class="form-control" name="alamat" id="alamat" placeholder="alamat" value="<?php echo $alamat ?>">
        </div>
        <div class="row mt-3">
            <label for="no_hp" class="form-label fw-bold">
                no_hp
            </label>
            <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="no_hp" value="<?php echo $no_hp ?>">
        </div>

        <div class="row mt-3">
            <label for="id_poli" class="form-label fw-bold">
                Poli
            </label>
            <select class="form-control" name="id_poli" id="id_poli">
                <?php
                $stmt = $pdo->query("SELECT * FROM poli");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($id_poli == $row['id']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['nama_poli'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="row d-flex mt-3 mb-3">
          <button type="submit" class="btn btn-primary" style="width: 3cm;" name="simpan">Simpan</button>
        </div>
</form>

<div class="row d-flex mt-3 mb-3">
  <a href="<?= $base_admin.'/dokter' ?>">
    <button class="btn btn-secondary ml-2" style="width: 3cm;">Reset</button>
  </a>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Dokter</h3>
  </div>
  <div class="card-body">
    <table id="example1" class="table table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama</th>
          <th scope="col">Alamat</th>
          <th scope="col">No. Hp</th>
          <th scope="col">Poli</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $pdo->query("SELECT * FROM dokter");
        $no = 1;
        while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data['nama'] ?></td>
              <td><?php echo $data['alamat'] ?></td>
              <td><?php echo $data['no_hp'] ?></td>
              <td>
                <?php
                $id_poli = $data['id_poli'];
                $poli = $pdo->query("SELECT * FROM poli WHERE id = $id_poli");
                $no = 1;
                while ($data_poli = $poli->fetch(PDO::FETCH_ASSOC)) {
                  echo $data_poli['nama_poli'];
                }
                ?>
                
              </td>
              <td>
                  <a class="btn btn-success" href="index.php?page=obat&id=<?php echo $data['id'] ?>">Ubah</a>
                  <a class="btn btn-danger" href="index.php?page=obat&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
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
            $stmt = $pdo->prepare("UPDATE dokter SET 
                                    nama = :nama,
                                    alamat = :alamat,
                                    no_hp = :no_hp,
                                    id_poli = :id_poli
                                    WHERE
                                    id = :id");

            $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
            $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->bindParam(':id_poli', $_POST['id_poli'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location:index.php');

        } else {
            $stmt = $pdo->prepare("INSERT INTO dokter(nama, alamat, no_hp, id_poli) 
                                    VALUES (:nama, :alamat, :no_hp, :id_poli)");

            $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
            $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
            $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_INT);
            $stmt->bindParam(':id_poli', $_POST['id_poli'], PDO::PARAM_INT);
            $stmt->execute();

            header('Location:index.php');
        }
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $stmt = $pdo->prepare("DELETE FROM dokter WHERE id = :id");
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