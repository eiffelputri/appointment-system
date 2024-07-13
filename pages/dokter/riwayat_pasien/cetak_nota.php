<?php
include_once("../../../config/conn.php");
require_once("tcpdf/tcpdf.php"); // Adjust the path accordingly
session_start();

// Function to fetch invoice data from the database
function fetchInvoiceData($conn, $selectedNumber)
{
    $sql = "SELECT dp.id, dp.keluhan, dp.no_antrian, dp.status_periksa, p.tgl_periksa, p.catatan, p.biaya_periksa,
            ps.nama AS nama_pasien, d.nama AS nama_dokter
            FROM daftar_poli dp
            JOIN periksa p ON dp.id = p.id_daftar_poli
            JOIN pasien ps ON dp.id_pasien = ps.id
            JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
            JOIN dokter d ON jp.id_dokter = d.id
            WHERE dp.no_antrian = $selectedNumber";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        $data = array(); // No data found for the selected invoice number
    }

    return $data;
}

// Function to generate PDF
function generateInvoicePDF($conn, $selectedNumber)
{
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 10);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Fetch invoice data from the database
    $invoiceData = fetchInvoiceData($conn, $selectedNumber);

    // Add content to the PDF
    if (!empty($invoiceData)) {
        $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C'); // Title
        $pdf->Ln(); // Add a new line

        foreach ($invoiceData as $key => $value) {
            $pdf->Cell(40, 10, $key, 1);
            $pdf->Cell(0, 10, $value, 1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, 'No data available for the selected invoice number.', 0, 1, 'C');
    }

    // Output the PDF as a file (you can also use 'I' to show it directly in the browser)
    $pdf->Output('invoice.pdf', 'D');
}

// Example: Call the function with a selected invoice number
$selectedNumber = $_GET['selectedNumber']; // You need to get this from your dropdown selection
generateInvoicePDF($conn, $selectedNumber);
