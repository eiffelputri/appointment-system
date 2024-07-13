<?php
require __DIR__ . '/url.php';
$host = 'localhost';
$dbname = 'poliklinik_bk';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$conn = mysqli_connect($host, $username, $password, $dbname);

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function ubahDokter($data)
{
    global $conn;

    $id = $data["id"];
    $nama = mysqli_real_escape_string($conn, $data["nama"]);
    $alamat = mysqli_real_escape_string($conn, $data["alamat"]);
    $no_hp = mysqli_real_escape_string($conn, $data["no_hp"]);

    $query = "UPDATE dokter SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp' WHERE id = $id ";

    if (mysqli_query($conn, $query)) {
        return mysqli_affected_rows($conn); // Return the number of affected rows
    } else {
        // Handle the error
        echo "Error updating record: " . mysqli_error($conn);
        return -1; // Or any other error indicator
    }
}

// Jadwal Periksa Sisi Dokter
function tambahJadwalPeriksa($data)
{
    try {
        global $conn;

        $id_dokter = $data["id_dokter"];
        $hari = mysqli_real_escape_string($conn, $data["hari"]);
        $jam_mulai = mysqli_real_escape_string($conn, $data["jam_mulai"]);
        $jam_selesai = mysqli_real_escape_string($conn, $data["jam_selesai"]);
        $aktif = 'T';

        // Check if the jadwal periksa already exists for another dokter
        // Check as well if the time range is already taken by another dokter
        $existing_data = mysqli_query($conn, "SELECT * FROM jadwal_periksa WHERE id_dokter != $id_dokter AND hari = '$hari'");
        if (mysqli_num_rows($existing_data) > 0) {
            while ($row = mysqli_fetch_assoc($existing_data)) {
                $query_existing = "SELECT * FROM jadwal_periksa WHERE id_dokter != $id_dokter AND hari = '$hari' AND jam_mulai <= '$jam_mulai' AND jam_selesai >= '$jam_mulai' OR jam_mulai <= '$jam_selesai' AND jam_selesai >= '$jam_selesai' OR jam_mulai >= '$jam_mulai' AND jam_selesai <= '$jam_selesai'";
                $checkJadwalPeriksa = mysqli_query($conn, $query_existing);
            }
            if (mysqli_num_rows($checkJadwalPeriksa) > 0) {
                return -2; // Return -2 if the jadwal periksa already exists
            }
        }

        $query = "INSERT INTO jadwal_periksa VALUES (null, '$id_dokter', '$hari', '$jam_mulai', '$jam_selesai', '$aktif')";
        if (mysqli_query($conn, $query)) {
            return mysqli_affected_rows($conn); // Return the number of affected rows
        } else {
            // Handle the error
            echo "Error updating record: " . mysqli_error($conn);
            return -1; // Or any other error indicator
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
}

function updateJadwalPeriksa($data, $id)
{
    try {
        global $conn;

        $hari = mysqli_real_escape_string($conn, $data["hari"]);
        $jam_mulai = mysqli_real_escape_string($conn, $data["jam_mulai"]);
        $jam_selesai = mysqli_real_escape_string($conn, $data["jam_selesai"]);
        $aktif = mysqli_real_escape_string($conn, $data["aktif"] );

        if ($aktif == 'Y') {
            // Make jadwal apapun yang sudah aktif menjadi tidak aktif
            $query = "UPDATE jadwal_periksa SET aktif = 'T' WHERE aktif = 'Y'";
            mysqli_query($conn, $query);
        }

        $query = "UPDATE jadwal_periksa SET hari = '$hari', jam_mulai = '$jam_mulai', jam_selesai = '$jam_selesai', aktif = '$aktif' WHERE id = $id ";

        if (mysqli_query($conn, $query)) {
            return mysqli_affected_rows($conn); // Return the number of affected rows
        } else {
            // Handle the error
            echo "Error updating record: " . mysqli_error($conn);
            return -1; // Or any other error indicator
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
        die();
    }
}

function hapusJadwalPeriksa($id)
{
    try {
        global $conn;

        $query = "DELETE FROM jadwal_periksa WHERE id = $id";

        if (mysqli_query($conn, $query)) {
            return mysqli_affected_rows($conn); // Return the number of affected rows
        } else {
            // Handle the error
            echo "Error updating record: " . mysqli_error($conn);
            return -1; // Or any other error indicator
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
}

function TambahPeriksa($data)

{
    global $conn;
     // ambil data dari tiap elemen dalam form
     $tgl_periksa = htmlspecialchars($data["tgl_periksa"]);
     $catatan = htmlspecialchars($data["catatan"]);
     

    // query insert data
    $query = "INSERT INTO periksa
                VALUES
                ('', '$tgl_periksa','$catatan');
            ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// ini belum selesai mau dilanjutin vander :v
function TambahDetailPeriksa($data){
    global $conn;
     // ambil data dari tiap elemen dalam form
     $tgl_periksa = htmlspecialchars($data["tgl_periksa"]);
     $catatan = htmlspecialchars($data["catatan"]);
     

      // query insert data
    $query = "INSERT INTO detail_periksa
                VALUES
                ('', '$tgl_periksa','$catatan');
            ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function daftarPoli($data)
{
    global $pdo;

    try {
        $id_pasien = $data["id_pasien"];
        $id_jadwal = $data["id_jadwal"];
        $keluhan = $data["keluhan"];
        $no_antrian = getLatestNoAntrian($id_jadwal, $pdo) + 1;
        $status = 0;

        $query = "INSERT INTO daftar_poli VALUES (NULL, :id_pasien, :id_jadwal, :keluhan, :no_antrian, :status_periksa)";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_pasien', $id_pasien);
        $stmt->bindParam(':id_jadwal', $id_jadwal);
        $stmt->bindParam(':keluhan', $keluhan);
        $stmt->bindParam(':no_antrian', $no_antrian);
        $stmt->bindParam(':status_periksa', $status);
        if ($stmt->execute()) {
            
            return $stmt->rowCount(); // Return the number of affected rows
        } else {
            // Handle the error
            echo "Error updating record: " . $stmt->errorInfo()[2];
            return -1; // Or any other error indicator
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
}

function getLatestNoAntrian($id_jadwal, $pdo)
{
    // Ambil nomor antrian terbaru untuk jadwal tertentu
    $latestNoAntrian = $pdo->prepare("SELECT MAX(no_antrian) as max_no_antrian FROM daftar_poli WHERE id_jadwal = :id_jadwal");
    $latestNoAntrian->bindParam(':id_jadwal', $id_jadwal);
    $latestNoAntrian->execute();

    $row = $latestNoAntrian->fetch();
    return $row['max_no_antrian'] ? $row['max_no_antrian'] : 0;
}

function formatRupiah($angka)
{
    return "Rp" . number_format($angka, 0, ',', '.');
}