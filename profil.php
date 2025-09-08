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
    $no_hp = $_POST['no_hp'];


    // Upload foto jika ada
    $foto = $user['foto']; // default tetap yang lama
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $newName = "foto_" . time() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $newName);
        $foto = $newName;
    }

    // Update data ke database
   $update = mysqli_query($koneksi, "UPDATE member 
    SET email = '$email', 
        no_hp = '$no_hp', 
        profile = '$profile', 
        foto = '$foto' 
    WHERE username = '$username'");

    if ($update) {
        $_SESSION['foto'] = $foto;
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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profil-container {
            background-color: #fff;
            display: flex;
            flex-direction: row;
            gap: 40px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            max-width: 900px;
            width: 100%;
            align-items: center;
        }

        .profil-photo {
            flex: 1;
            text-align: center;
        }

        .profil-photo img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            border: 3px solid #eee;
        }

        .profil-photo p {
            margin-top: 10px;
            font-size: 14px;
            color: #888;
        }

        .profil-form {
            flex: 2;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            color: #444;
        }

        input[type="email"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        input:focus,
        textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 6px rgba(76, 175, 80, 0.4);
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #43a047;
        }

        .success-msg {
            color: green;
            margin-top: 10px;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #4CAF50;
            font-weight: 600;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profil-container {
                flex-direction: column;
                text-align: center;
            }

            .profil-photo img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="profil-container">
        <div class="profil-photo">
            <?php if (!empty($user['foto'])): ?>
                <img src="uploads/<?= htmlspecialchars($user['foto']) ?>" alt="Foto Profil">
            <?php else: ?>
                <img src="https://via.placeholder.com/200" alt="Foto Default">
                <p><i>Belum ada foto profil</i></p>
            <?php endif; ?>
            <p><strong><?= htmlspecialchars($user['username']) ?></strong></p>
        </div>
        <div class="profil-form">
            <h2>Edit Profil</h2>
            <form method="post" enctype="multipart/form-data">
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div>
                    <label>No Telepon</label>
                  <input type="text" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
                </div>
                <div>
                    <label for="alamat">Alamat:</label>
                    <textarea id="alamat" name="profile" placeholder="Masukkan alamat lengkap Anda"><?= htmlspecialchars($user['profile']) ?></textarea>
                </div>
                <div>
                    <label>Foto Profil (jpg/png):</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
                <button type="submit">Simpan Perubahan</button>
            </form>
            <?php if (isset($_GET['success'])): ?>
                <p class="success-msg">Profil berhasil diperbarui!</p>
            <?php endif; ?>
            <a href="index.php" class="back-link">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
