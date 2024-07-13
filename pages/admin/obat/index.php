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
Obat
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm" onsubmit="return(validate());">
        <?php
        $nama_obat = '';
        $kemasan = '';
        $harga = '';
        if (isset($_GET['id'])) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM obat WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nama_obat = $row['nama_obat'];
                    $kemasan = $row['kemasan'];
                    $harga = $row['harga'];
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
            <label for="nama_obat" class="form-label fw-bold">
                Nama Obat
            </label>
            <input type="text" class="form-control" name="nama_obat" id="nama_obat" placeholder="Nama Obat" value="<?php echo $nama_obat ?>">
        </div>
        <div class="row mt-3">
            <label for="kemasan" class="form-label fw-bold">
                Kemasan
            </label>
            <input type="text" class="form-control" name="kemasan" id="kemasan" placeholder="Kemasan" value="<?php echo $kemasan ?>">
        </div>
        <div class="row mt-3">
            <label for="harga" class="form-label fw-bold">
                Harga
            </label>
            <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" value="<?php echo $harga ?>">
        </div>
        <div class="row d-flex mt-3 mb-3">
            <button type="submit" class="btn btn-primary rounded-pill" style="width: 3cm;" name="simpan">Simpan</button>
        </div>
</form>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Obat</h3>
  </div>
  <div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama obat</th>
          <th scope="col">kemasan</th>
          <th scope="col">Harga</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $pdo->query("SELECT * FROM obat");
        $no = 1;
        while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data['nama_obat'] ?></td>
              <td><?php echo $data['kemasan'] ?></td>
              <td>Rp. <?php echo $data['harga'] ?></td>
              <td>
                  <a class="btn btn-success rounded-pill px-3" href="index.php?page=obat&id=<?php echo $data['id'] ?>">Edit</a>
                  <a class="btn btn-danger rounded-pill px-3" href="index.php?page=obat&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
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
            $stmt = $pdo->prepare("UPDATE obat SET 
                                    nama_obat = :nama_obat,
                                    kemasan = :kemasan,
                                    harga = :harga
                                    WHERE
                                    id = :id");

            $stmt->bindParam(':nama_obat', $_POST['nama_obat'], PDO::PARAM_STR);
            $stmt->bindParam(':kemasan', $_POST['kemasan'], PDO::PARAM_STR);
            $stmt->bindParam(':harga', $_POST['harga'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmt->execute();

        } else {
            $stmt = $pdo->prepare("INSERT INTO obat(nama_obat, kemasan, harga) 
                                    VALUES (:nama_obat, :kemasan, :harga)");

            $stmt->bindParam(':nama_obat', $_POST['nama_obat'], PDO::PARAM_STR);
            $stmt->bindParam(':kemasan', $_POST['kemasan'], PDO::PARAM_STR);
            $stmt->bindParam(':harga', $_POST['harga'], PDO::PARAM_INT);

            $stmt->execute();
        }
    }
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $stmt = $pdo->prepare("DELETE FROM obat WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
      
    }
    ?>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>