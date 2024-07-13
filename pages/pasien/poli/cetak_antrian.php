<?php
// cetak_antrian.php

// Include necessary files and start the session
include_once("../../../config/conn.php");
require_once("tcpdf/tcpdf.php"); // Adjust the path accordingly
session_start();

// Check if the user is logged in as a patient
if (!isset($_SESSION['login']) || $_SESSION['akses'] !== 'pasien') {
    header("Location: ../"); // Redirect to the login page or another appropriate page
    exit();
}

// Get the poli_id from the query parameter
$poli_id = isset($_GET['poli_id']) ? intval($_GET['poli_id']) : 0;

// Fetch relevant data for printing
$antrianData = getAntrianData($poli_id);

// Check if the data is valid
if (!$antrianData) {
    echo "Invalid data or no data found.";
    exit();
}

// Create a PDF instance
$pdf = new TCPDF();
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "ANTRIAN POLIKLINIK", 0, 1, 'C'); // Centered cell
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY(), array('width' => 0.5, 'color' => array(0, 0, 0))); // Solid line
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "Nama Poli", 0, 1, 'C'); // Centered cell
$pdf->SetFont('helvetica', '', 14); // Set font size for the content
$pdf->Cell(0, 10, $antrianData['poli_nama'], 0, 1, 'C'); // Centered cell
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY(), array('width' => 0.5, 'color' => array(0, 0, 0))); // Solid line

// Generate and display other information in centered cells
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(95, 10, "Nama Pasien", 0, 0, 'C'); // Centered cell
$pdf->Cell(95, 10, "Nama Dokter", 0, 1, 'C'); // Centered cell
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(95, 10, $antrianData['username'], 0, 0, 'C'); // Centered cell
$pdf->Cell(95, 10, $antrianData['dokter_nama'], 0, 1, 'C'); // Centered cell
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY(), array('width' => 0.5, 'color' => array(0, 0, 0))); // Solid line

// Generate and display the printed queue number in the PDF
$pdf->SetFont('helvetica', 'B', 16); // Set font size and style for the queue number
$pdf->Cell(0, 10, "Nomor Antrian", 0, 1, 'C'); // Centered cell
$pdf->SetFont('helvetica', '', 50); // Set font size for the content
$pdf->Cell(0, 10, $antrianData['antrian'], 0, 1, 'C'); // Centered cell
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY(), array('width' => 0.5, 'color' => array(0, 0, 0))); // Solid line

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "Jadwal", 0, 1, 'C'); // Centered cell
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(0, 10, "{$antrianData['jadwal_hari']}, {$antrianData['jadwal_mulai']} - {$antrianData['jadwal_selesai']}", 0, 1, 'C'); // Centered cell
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY(), array('width' => 0.5, 'color' => array(0, 0, 0))); // Solid line


// Output the PDF to the browser
$pdf->Output('cetak_antrian.pdf', 'I');
exit();

// Function to get antrian data based on poli_id
function getAntrianData($poli_id)
{
    global $pdo;

    $poli = $pdo->prepare("SELECT d.nama_poli as poli_nama,
                                    c.nama as dokter_nama,
                                    b.hari as jadwal_hari,
                                    b.jam_mulai as jadwal_mulai,
                                    b.jam_selesai as jadwal_selesai,
                                    a.no_antrian as antrian,
                                    e.nama as username
                                    FROM daftar_poli as a
                                    INNER JOIN jadwal_periksa as b
                                    ON a.id_jadwal = b.id
                                    INNER JOIN dokter as c
                                    ON b.id_dokter = c.id
                                    INNER JOIN poli as d
                                    ON c.id_poli = d.id
                                    INNER JOIN pasien as e
                                    ON a.id_pasien = e.id
                                    WHERE a.id = :poli_id");
    $poli->bindParam(':poli_id', $poli_id, PDO::PARAM_INT);
    $poli->execute();

    if ($poli->rowCount() > 0) {
        return $poli->fetch(PDO::FETCH_ASSOC);
    }

    return null;
}
?>
