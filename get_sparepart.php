<?php
require_once 'session_checker.php';
require_once 'koneksi.php';

// Get search term from request
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

// Prepare query to get active spareparts with stock > 0
$query = "SELECT id, nama_sparepart, harga_jual, stok 
          FROM sparepart 
          WHERE status = 'aktif' 
          AND stok > 0 
          AND (nama_sparepart LIKE ? OR kode_sparepart LIKE ?)
          ORDER BY nama_sparepart ASC 
          LIMIT 10";

$search = "%$searchTerm%";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $search, $search);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
        'id' => $row['id'],
        'text' => $row['nama_sparepart'] . ' (Stok: ' . $row['stok'] . ')',
        'harga_jual' => $row['harga_jual']
    );
}

// Return results as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>