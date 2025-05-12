<?php
// session_checker.php
session_start();

function checkLogin() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: login.php");
        exit();
    }
}

function checkRole($allowed_roles) {
    // Pastikan user sudah login
    checkLogin();
    
    // Periksa apakah role user ada dalam array role yang diizinkan
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        // Redirect ke dashboard sesuai role
        switch($_SESSION['role']) {
            case 'pemilik':
                header("Location: dashboard_pemilik.php");
                break;
            case 'admin':
                header("Location: dashboard_admin.php");
                break;
            case 'mekanik':
                header("Location: dashboard_mekanik.php");
                break;
            default:
                header("Location: logout.php");
        }
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isPemilik() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'pemilik';
}

function isMekanik() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'mekanik';
}
?>