<?php
include_once("../../../config/conn.php");

// Ambil ID poli dari parameter GET
$poliId = isset($_GET['poli_id']) ? $_GET['poli_id'] : null;

// Query untuk mendapatkan jadwal dokter berdasarkan ID poli
$dataJadwal = $pdo->prepare("SELECT a.nama as nama_dokter, 
                                    b.hari as hari, 
                                    b.id as id_jp,
                                    b.jam_mulai as jam_mulai,
                                    b.jam_selesai as jam_selesai

                                    FROM dokter as a

                                    INNER JOIN jadwal_periksa as b
                                    ON a.id = b.id_dokter
                                    WHERE a.id_poli = :poli_id");
$dataJadwal->bindParam(':poli_id', $poliId);
$dataJadwal->execute();

// Bangun opsi-opsi jadwal dokter
if ($dataJadwal->rowCount() == 0) {
    echo '<option>Tidak ada jadwal</option>';
} else {
  while ($jd = $dataJadwal->fetch()) {
    echo '<option value="' . $jd['id_jp'] . '"> Dokter ' . $jd['nama_dokter'] . ' | ' . $jd['hari'] . ' | ' . $jd['jam_mulai'] . ' - ' . $jd['jam_selesai'] .'</option>';
  }
}
?>
