<?php
session_start();
include "koneksi.php";

//dapatkan data user dari form
$user = [
    'username' => $_POST['username'],
    'password' => $_POST['password'],
];

//check apakah user tersebut ada di table users
$query = "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss', $user['username'], $user['password']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array(MYSQLI_ASSOC);

if($row != null){
    $_SESSION['login'] = true;
    $_SESSION['username'] =  $user['username'];
    if (isset($row['nama'])) {
        $_SESSION['nama'] =  $row['nama'];
    } else {
        $_SESSION['nama'] =  '';
    }
    $_SESSION['terakhir_login'] =  date('Y-m-d H:i:s');
    $_SESSION['message']  = 'Berhasil login ke dalam sistem.';
    header("Location: index.php");
    exit; // Penting untuk keluar setelah mengatur header lokasi
}else{
    $_SESSION['error'] = 'Username dan password anda tidak ditemukan.';
    header("Location: login.php");
    exit; // Penting untuk keluar setelah mengatur header lokasi
}
?>
