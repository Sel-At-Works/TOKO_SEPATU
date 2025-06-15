<?php
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email_pengguna']);
    $nomor = mysqli_real_escape_string($koneksi, $_POST['nomor_pengguna']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $sql = "INSERT INTO kontak_kami (nama_pengguna, email_pengguna, nomor_pengguna, deskripsi)
            VALUES ('$nama', '$email', '$nomor', '$deskripsi')";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Pesan berhasil dikirim!');window.location='index.html';</script>";
    } else {
        echo "<script>alert('Gagal mengirim pesan!');window.location='index.html';</script>";
    }
}
?>