<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil data user dari database
$query = mysqli_query($koneksi, "SELECT * FROM member WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);

// Update data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $profile = $_POST['profile'];

    // Upload foto jika ada
    $foto = $user['foto']; // default tetap yang lama
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $newName = "foto_" . time() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $newName);
        $foto = $newName;
    }

    // Update data ke database
    $update = mysqli_query($koneksi, "UPDATE member SET email = '$email', profile = '$profile', foto = '$foto' WHERE username = '$username'");
    if ($update) {
        header("Location: profil.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
</head>
<body>
    <div class="profil-box">
        <h2>Profil Pengguna</h2>

        <!-- Tampilkan Foto Profil -->
        <?php if (!empty($user['foto'])): ?>
            <img src="uploads/<?= htmlspecialchars($user['foto']) ?>" alt="Foto Profil" width="150" style="border-radius: 10px;"><br>
        <?php else: ?>
            <p><i>Belum ada foto profil.</i></p>
        <?php endif; ?>

        <!-- Tampilkan Username -->
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>

        <form method="post" enctype="multipart/form-data">
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

            <label>Profil / Deskripsi:</label><br>
            <textarea name="profile" rows="4"><?= htmlspecialchars($user['profile']) ?></textarea><br><br>

            <label>Foto Profil (jpg/png):</label><br>
            <input type="file" name="foto" accept="image/*"><br><br>

            <button type="submit">Simpan Perubahan</button>
        </form>

        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;">Profil berhasil diperbarui!</p>
        <?php endif; ?>

        <a href="index.php">Kembali ke Beranda</a>
    </div>
</body>

</html>


<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f0f2f5;
        padding: 40px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    .profil-box {
        background-color: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    form {
        margin-top: 20px;
    }

    label {
        font-weight: 600;
        display: block;
        margin-top: 15px;
    }

    input[type="email"],
    input[type="file"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
    }

    button {
        margin-top: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }

    button:hover {
        background-color: #45a049;
    }

    img {
        display: block;
        margin: 0 auto 20px auto;
        border: 2px solid #ddd;
        padding: 5px;
        background-color: #fff;
        border-radius: 10px;
        max-width: 150px;
    }

    p {
        margin-top: 10px;
        text-align: center;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 20px;
        text-decoration: none;
        color: #4CAF50;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

