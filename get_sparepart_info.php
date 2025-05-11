<?php
require_once 'session_checker.php';
require_once 'koneksi.php';

if (isset($_POST['sparepart_id'])) {
    $sparepart_id = mysqli_real_escape_string($conn, $_POST['sparepart_id']);
    
    $query = "SELECT id, nama_sparepart, harga_jual, stok 
              FROM sparepart 
              WHERE id = '$sparepart_id' AND status = 'aktif'";
    
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(array(
            'id' => $row['id'],
            'nama_sparepart' => $row['nama_sparepart'],
            'harga_jual' => $row['harga_jual'],
            'stok' => $row['stok']
        ));
    } else {
        echo json_encode(array('error' => 'Sparepart not found'));
    }
} else {
    echo json_encode(array('error' => 'No sparepart ID provided'));
}
?>