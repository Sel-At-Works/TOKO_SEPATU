<?php
session_start();
session_unset();   // Hapus semua variabel session
session_destroy(); // Hancurkan sesi
header("Location: index.php"); // Arahkan ke halaman utama
exit;
?>
