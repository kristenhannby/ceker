<?php
require_once 'session_checker.php';
checkRole(['admin']);

require_once 'koneksi.php';

// Proses tambah akun pelanggan
if (isset($_POST['tambah_akun'])) {
    $pelanggan_id = $_POST['pelanggan_id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $cek_username = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_username) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        // Ambil data pelanggan
        $query_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id = '$pelanggan_id'");
        $pelanggan = mysqli_fetch_assoc($query_pelanggan);

        // Tambah user baru
        $query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES 
                  ('$username', '$password', '{$pelanggan['nama_pelanggan']}', 'pelanggan')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Akun pelanggan berhasil dibuat!";
        } else {
            $error = "Gagal membuat akun: " . mysqli_error($conn);
        }
    }
}

// Proses reset password
if (isset($_POST['reset_password'])) {
    $user_id = $_POST['user_id'];
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);

    $query = "UPDATE users SET password = '$password_baru' WHERE id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        $success = "Password berhasil direset!";
    } else {
        $error = "Gagal reset password: " . mysqli_error($conn);
    }
}

// Proses nonaktifkan/aktifkan akun
if (isset($_POST['ubah_status'])) {
    $user_id = $_POST['user_id'];
    $status_baru = $_POST['status_baru'];

    $query = "UPDATE users SET status = '$status_baru' WHERE id = '$user_id'";
    if (mysqli_query($conn, $query)) {
        $success = "Status akun berhasil diubah!";
    } else {
        $error = "Gagal mengubah status akun: " . mysqli_error($conn);
    }
}

// Ambil daftar pelanggan yang belum memiliki akun
$query_pelanggan_tanpa_akun = "
    SELECT p.id, p.nama_pelanggan, p.no_telp 
    FROM pelanggan p 
    LEFT JOIN users u ON u.username = p.no_telp 
    WHERE u.id IS NULL
";
$result_pelanggan_tanpa_akun = mysqli_query($conn, $query_pelanggan_tanpa_akun);

// Ambil daftar akun pelanggan
$query_akun_pelanggan = "
    SELECT u.id, u.username, u.status, u.nama_lengkap, p.no_telp 
    FROM users u
    LEFT JOIN pelanggan p ON u.nama_lengkap = p.nama_pelanggan
    WHERE u.role = 'pelanggan'
";
$result_akun_pelanggan = mysqli_query($conn, $query_akun_pelanggan);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Manajemen Akun Pelanggan - Bengkel Watro Mulyo Joyo</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard_admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-tools fa-fw"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Bengkel Watro Mulyo Joyo</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard_admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Master Data
            </div>

            <!-- Nav Item - Data Pelanggan Collapse Menu -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePelanggan"
                aria-expanded="true" aria-controls="collapsePelanggan">
                <i class="fas fa-users"></i>
                <span>Data Pelanggan</span>
                </a>
                <div id="collapsePelanggan" class="collapse show" aria-labelledby="headingPelanggan" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Pelanggan:</h6>
                        <a class="collapse-item" href="pelanggan.php">Daftar Pelanggan</a>
                        <a class="collapse-item active" href="manajemenpelanggan.php">Manajemen Data Pelanggan</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Data Mekanik -->
            <li class="nav-item">
                <a class="nav-link" href="mekanik.php">
                    <i class="fas fa-wrench fa-fw"></i>
                    <span>Data Mekanik</span></a>
            </li>

            <!-- Nav Item - Data Supplier -->
            <li class="nav-item">
                <a class="nav-link" href="supplier.php">
                    <i class="fas fa-truck fa-fw"></i>
                    <span>Data Supplier</span></a>
            </li>

            <!-- Nav Item - Manajemen Data User -->
            <li class="nav-item">
                <a class="nav-link" href="manajemenuser.php">
                    <i class="fas fa-user fa-fw"></i>
                    <span>Manajemen Data User</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Transaksi
            </div>

            <!-- Nav Item - Stok Sparepart Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSparepart"
                    aria-expanded="true" aria-controls="collapseSparepart">
                    <i class="fas fa-tools fa-fw"></i>
                <span>Stok Sparepart</span>
            </a>
            <div id="collapseSparepart" class="collapse" aria-labelledby="headingSparepart" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu Sparepart:</h6>
                <a class="collapse-item" href="daftar_sparepart.php">Daftar Sparepart</a>
                <a class="collapse-item" href="stok_masuk.php">Stok Masuk</a>
                <a class="collapse-item" href="stok_keluar.php">Stok Keluar</a>
                <a class="collapse-item" href="kategori_sparepart.php">Kategori Sparepart</a>
            </div>
        </div>
    </li>

            <!-- Nav Item - Service -->
            <li class="nav-item">
                <a class="nav-link" href="layanan_servis.php">
                    <i class="fas fa-cogs fa-2x"></i>
                    <span>Layanan Servis</span></a>
            </li>

            <!-- Nav Item - Transaksi Servis -->
            <li class="nav-item">
                <a class="nav-link" href="transaksi_servis.php">
                <i class="fas fa-cash-register fa-fw"></i>
                    <span>Transaksi Servis</span></a>
            </li>

            <!-- Nav Item - Riwayat Servis -->
            <li class="nav-item">
                <a class="nav-link" href="riwayat_servis.php">
                <i class="fas fa-history fa-fw"></i>
                    <span>Riwayat Servis</span></a>
            </li>

            <!-- Nav Item - keuangan -->
            <li class="nav-item">
                <a class="nav-link" href="keuangan.php">
                    <i class="fas fa-fw fa-money-bill"></i>
                    <span>Keuangan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nama_lengkap']; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Manajemen Akun Pelanggan</h1>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addAccountModal">
                            <i class="fas fa-user-plus fa-sm"></i> Tambah Akun Pelanggan
                        </button>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Daftar Akun Pelanggan Card -->
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Daftar Akun Pelanggan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Username</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>No. Telepon</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $no = 1;
                                                while($akun = mysqli_fetch_assoc($result_akun_pelanggan)): ?>
                                                    <tr>
                                                        <td><?php echo $no++; ?></td>
                                                        <td><?php echo $akun['username']; ?></td>
                                                        <td><?php echo $akun['nama_lengkap']; ?></td>
                                                        <td><?php echo $akun['no_telp'] ?? '-'; ?></td>
                                                        <td>
                                                            <?php 
                                                            $status_badge = $akun['status'] == 'aktif' ? 'badge-success' : 'badge-danger';
                                                            echo "<span class='badge {$status_badge}'>" . ucfirst($akun['status']) . "</span>"; 
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="aksiDropdown<?php echo $akun['id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Aksi
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="aksiDropdown<?php echo $akun['id']; ?>">
                                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#resetPasswordModal<?php echo $akun['id']; ?>">
                                                                        <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                                                                        Reset Password
                                                                    </a>
                                                                    <form method="POST" class="d-inline">
                                                                        <input type="hidden" name="user_id" value="<?php echo $akun['id']; ?>">
                                                                        <input type="hidden" name="status_baru" value="<?php echo $akun['status'] == 'aktif' ? 'nonaktif' : 'aktif'; ?>">
                                                                        <button type="submit" name="ubah_status" class="dropdown-item">
                                                                            <i class="fas fa-<?php echo $akun['status'] == 'aktif' ? 'lock' : 'unlock'; ?> fa-sm fa-fw mr-2 text-gray-400"></i>
                                                                            <?php echo $akun['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan'; ?> Akun
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Reset Password Modal -->
                                                    <div class="modal fade" id="resetPasswordModal<?php echo $akun['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel<?php echo $akun['id']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="resetPasswordModalLabel<?php echo $akun['id']; ?>">Reset Password</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form method="POST">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="user_id" value="<?php echo $akun['id']; ?>">
                                                                        <div class="form-group">
                                                                            <label>Password Baru</label>
                                                                            <input type="password" name="password_baru" class="form-control" required placeholder="Masukkan password baru">
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                                        <button type="submit" name="reset_password" class="btn btn-primary">Reset Password</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Bengkel Watro Mulyo Joyo <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Tambah Akun Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">Tambah Akun Pelanggan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Pelanggan</label>
                            <select name="pelanggan_id" class="form-control" required>
                                <option value="">Pilih Pelanggan</option>
                                <?php 
                                // Reset pointer untuk query
                                mysqli_data_seek($result_pelanggan_tanpa_akun, 0);
                                while($pelanggan = mysqli_fetch_assoc($result_pelanggan_tanpa_akun)): ?>
                                    <option value="<?php echo $pelanggan['id']; ?>">
                                        <?php echo $pelanggan['nama_pelanggan'] . " - " . $pelanggan['no_telp']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_akun" class="btn btn-primary">Tambah Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah jika Anda yakin ingin mengakhiri sesi Anda saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    
    <script>
    $(document).ready(function() {
    $('#dataTable').DataTable({
        // Improve search responsiveness
        search: {
            return: true,
            smart: true
        },
        // Add proper search box styling
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        // Improve mobile responsiveness
        responsive: true,
        // Add proper language localization
        language: {
            search: "Pencarian:",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
            infoFiltered: "(disaring dari _MAX_ total entri)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

    <!-- Custom CSS override -->
    <style>
        .sidebar.bg-gradient-primary {
            background-color: #0e1b2a !important;
            background-image: linear-gradient(180deg, #0e1b2a 10%, #0a1520 100%) !important;
        }
        
        .sidebar-dark .nav-item.active .nav-link {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-dark .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>

</body>

</html>