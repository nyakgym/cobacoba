<?php
session_start();
include "koneksi.php";

// Dapatkan data user dari form register
$user = [
    'nama' => $_POST['nama'],
    'username' => $_POST['username'],
    'password' => $_POST['password'],
    'password_confirmation' => $_POST['password_confirmation'],
];

// Validasi jika password & password_confirmation sama
if ($user['password'] != $user['password_confirmation']) {
    $_SESSION['error'] = 'Password yang anda masukkan tidak sama dengan password confirmation.';
    $_SESSION['nama'] = $_POST['nama'];
    $_SESSION['username'] = $_POST['username'];
    header("Location: /register.php");
    exit; // Penting untuk keluar setelah mengatur header lokasi
}

// Check apakah user dengan username tersebut ada di tabel users
$query = "SELECT * FROM users WHERE username = ? LIMIT 1";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $user['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array(MYSQLI_ASSOC);

// Jika username sudah ada, kembali ke halaman register
if ($row != null) {
    $_SESSION['error'] = 'Username: ' . $user['username'] . ' yang anda masukkan sudah ada di database.';
    $_SESSION['nama'] = $_POST['nama'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['password_confirmation'] = $_POST['password_confirmation'];
    header("Location: /register.php");
    exit; // Penting untuk keluar setelah mengatur header lokasi
} else {
    // Username unik, simpan di database
    $query = "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sss', $user['nama'], $user['username'], $user['password']);
    $stmt->execute();
    $_SESSION['message'] = 'Berhasil register ke dalam sistem. Silakan login dengan username dan password yang sudah dibuat.';
    header("Location: register.php");
    exit; // Penting untuk keluar setelah mengatur header lokasi
}
?>
