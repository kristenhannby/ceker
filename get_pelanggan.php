<?php
// get_pelanggan.php
require_once 'session_checker.php';
checkRole(['admin', 'staff']);

// Connect to database
include 'koneksi.php';

// Get search term
$searchTerm = isset($_GET['term']) ? mysqli_real_escape_string($conn, $_GET['term']) : '';

// Search query
$query = "SELECT id, nama_pelanggan, no_telp FROM pelanggan 
          WHERE nama_pelanggan LIKE '%$searchTerm%' OR no_telp LIKE '%$searchTerm%'
          ORDER BY nama_pelanggan ASC
          LIMIT 10";
$result = mysqli_query($conn, $query);

// Generate JSON response
$response = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Format: Name - Phone
    $text = $row['nama_pelanggan'] . ' - ' . $row['no_telp'];
    
    $response[] = [
        'id' => $row['id'],
        'text' => $text
    ];
}

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($response);