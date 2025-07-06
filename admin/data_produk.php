<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

// Proses tambah produk
$pesan = "";
if (isset($_POST['tambah'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $size = mysqli_real_escape_string($koneksi, $_POST['size']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "../img/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $gambar_baru = uniqid() . '_' . basename($gambar);
    $path = $folder . $gambar_baru;

    if (move_uploaded_file($tmp, $path)) {
        $sql = "INSERT INTO toko_sepatu (nama_produk, size, harga, deskripsi, gambar) VALUES ('$nama_produk', '$size', '$harga', '$deskripsi', '$gambar_baru')";
        if (mysqli_query($koneksi, $sql)) {
            $pesan = "<div class='alert success'>✅ Produk berhasil ditambahkan!</div>";
        } else {
            $pesan = "<div class='alert error'>❌ Gagal menambah produk!</div>";
        }
    } else {
        $pesan = "<div class='alert error'>⚠️ Upload gambar gagal!</div>";
    }
}

// Proses hapus produk
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    // Hapus gambar dari folder
    $q = mysqli_query($koneksi, "SELECT gambar FROM toko_sepatu WHERE id_produk=$id");
    $d = mysqli_fetch_assoc($q);
    if ($d && file_exists("../img/" . $d['gambar'])) {
        unlink("../img/" . $d['gambar']);
    }
    mysqli_query($koneksi, "DELETE FROM toko_sepatu WHERE id_produk=$id");
    header("Location: data_produk.php");
    exit;
}

// Ambil data produk
$produk = mysqli_query($koneksi, "SELECT * FROM toko_sepatu ORDER BY id_produk DESC");

// Ambil data produk yang akan diedit
$edit_produk = null;
if (isset($_GET['edit'])) {
    $id_edit = intval($_GET['edit']);
    $q_edit = mysqli_query($koneksi, "SELECT * FROM toko_sepatu WHERE id_produk=$id_edit");
    $edit_produk = mysqli_fetch_assoc($q_edit);
}

// Proses update produk
if (isset($_POST['update'])) {
    $id_produk = intval($_POST['id_produk']);
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $size = mysqli_real_escape_string($koneksi, $_POST['size']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Cek apakah ada gambar baru
    $gambar_baru = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    if ($gambar_baru) {
        $folder = "../img/";
        $gambar_baru_name = uniqid() . '_' . basename($gambar_baru);
        $path = $folder . $gambar_baru_name;
        if (move_uploaded_file($tmp, $path)) {
            // Hapus gambar lama
            $q = mysqli_query($koneksi, "SELECT gambar FROM toko_sepatu WHERE id_produk=$id_produk");
            $d = mysqli_fetch_assoc($q);
            if ($d && file_exists("../img/" . $d['gambar'])) {
                unlink("../img/" . $d['gambar']);
            }
            $gambar_sql = ", gambar='$gambar_baru_name'";
        } else {
            $pesan = "<div class='alert error'>⚠️ Upload gambar gagal!</div>";
            $gambar_sql = "";
        }
    } else {
        $gambar_sql = "";
    }

    $sql = "UPDATE toko_sepatu SET nama_produk='$nama_produk', size='$size', harga='$harga', deskripsi='$deskripsi' $gambar_sql WHERE id_produk=$id_produk";
    if (mysqli_query($koneksi, $sql)) {
        $pesan = "<div class='alert success'>✅ Produk berhasil diupdate!</div>";
    } else {
        $pesan = "<div class='alert error'>❌ Gagal update produk!</div>";
    }
    // Redirect agar form edit hilang setelah update
    header("Location: data_produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin | Marsel Shoes</title>
    <link rel="stylesheet" href="../style.css">
    <style>
   body {
    font-family: 'Poppins', sans-serif;
    background: #f1f3f6;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    padding: 30px 40px;
}

h2 {
    color: #b6895b;
    text-align: center;
    margin-bottom: 30px;
    font-size: 2rem;
}

.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 1.05rem;
}
.alert.success {
    background: #e8f5e9;
    color: #2e7d32;
    border-left: 5px solid #2e7d32;
}
.alert.error {
    background: #ffebee;
    color: #c62828;
    border-left: 5px solid #c62828;
}

form {
    margin-bottom: 40px;
    background: #fffefc;
    border: 1px solid #ffe0b2;
    border-radius: 10px;
    padding: 20px 25px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #444;
}

input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 1rem;
    background: #f9f9f9;
    transition: all 0.3s ease;
}
input:focus, textarea:focus {
    border-color: #b6895b;
    outline: none;
    background: #fff;
}

button[type="submit"],
a.btn-hapus,
a.btn-detail {
    background: #b6895b;
    color: #fff;
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.3s ease;
}
button[type="submit"]:hover,
a.btn-detail:hover {
    background: #a07448;
}
a.btn-hapus {
    background: #e57373;
}
a.btn-hapus:hover {
    background: #d32f2f;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 1rem;
    background: #fff;
}
th, td {
    border: 1px solid #f0e68c;
    padding: 12px 15px;
    text-align: center;
    vertical-align: middle;
}
th {
    background: #fffbe6;
    color: #b6895b;
    font-weight: 700;
}
tr:nth-child(even) {
    background: #f9f9f9;
}
img {
    max-width: 60px;
    border-radius: 6px;
}

.deskripsi-singkat {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

#popupDeskripsi {
    display: none;
    position: fixed;
    top: 25%;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px;
    width: 380px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.3);
    z-index: 1000;
}
#popupDeskripsi h4 {
    margin-top: 0;
    color: #b6895b;
}
#popupDeskripsi button {
    margin-top: 15px;
    background: #b6895b;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.95rem;
}

/* Responsif */
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }
    table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
    }
    th, td {
        text-align: left;
        padding: 10px;
    }
    th {
        background: #b6895b;
        color: #fff;
    }
    td {
        border: none;
        border-bottom: 1px solid #eee;
    }
}
</style>
</head>
<body>
    <div class="container">
        <h2>👟 Kelola Produk - Marsel Shoes</h2>
        <?= $pesan ?>

        <?php if ($edit_produk): ?>
            <!-- Tampilkan hanya form edit jika sedang edit -->
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_produk" value="<?= $edit_produk['id_produk'] ?>">

                <label for="nama_produk">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" value="<?= isset($edit_produk) ? htmlspecialchars($edit_produk['nama_produk']) : '' ?>" required>

                <label for="size">Size</label>
                <input type="text" id="size" name="size" value="<?= isset($edit_produk) ? htmlspecialchars($edit_produk['size']) : '' ?>" required>

                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" value="<?= isset($edit_produk) ? htmlspecialchars($edit_produk['harga']) : '' ?>" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" required><?= isset($edit_produk) ? htmlspecialchars($edit_produk['deskripsi']) : '' ?></textarea>

                <label for="gambar">Ganti Gambar (opsional)</label>
                <input type="file" id="gambar" name="gambar" accept="image/*">

                <?php if (isset($edit_produk)): ?>
                    <p>Gambar Saat Ini:</p>
                    <img src="../img/<?= htmlspecialchars($edit_produk['gambar']) ?>" alt="<?= htmlspecialchars($edit_produk['nama_produk']) ?>" style="max-width: 100px; margin-bottom: 10px;">
                <?php endif; ?>

                <button type="submit" name="update">Update Produk</button>
                <a href="data_produk.php">Batal</a>
            </form>
        <?php else: ?>
            <!-- Tampilkan hanya form tambah jika tidak edit -->
            <form method="post" enctype="multipart/form-data">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" value="<?= isset($edit_produk) ? htmlspecialchars($edit_produk['nama_produk']) : '' ?>" required>

                <label for="size">Size</label>
                <input type="text" id="size" name="size" value="<?= isset($edit_produk) ? htmlspecialchars($edit_produk['size']) : '' ?>" required>

                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" value="<?= isset($edit_produk) ? htmlspecialchars($edit_produk['harga']) : '' ?>" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" required><?= isset($edit_produk) ? htmlspecialchars($edit_produk['deskripsi']) : '' ?></textarea>

                <label for="gambar">Gambar Produk</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" <?= !isset($edit_produk) ? 'required' : '' ?>>

                <button type="submit" name="tambah">Tambah Produk</button>
            </form>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Size</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($produk)): ?>
                <tr>
                    <td><?= $row['id_produk'] ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($row['size']) ?></td>
                    <td>IDR <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>
                        <div class="deskripsi-singkat"><?= htmlspecialchars($row['deskripsi']) ?></div>
                        <button class="btn-detail" onclick="showDeskripsi('<?= htmlspecialchars(addslashes($row['deskripsi'])) ?>')">Lihat Detail</button>
                    </td>
                    <td>
                        <?php if($row['gambar']): ?>
                            <img src="../img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="data_produk.php?hapus=<?= $row['id_produk'] ?>" class="btn-hapus" onclick="return confirm('Yakin hapus produk ini?')">🗑 Hapus</a>
                            <a href="data_produk.php?edit=<?= $row['id_produk'] ?>" class="btn-detail">✏️ Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="popupDeskripsi">
        <h4>Deskripsi Lengkap</h4>
        <p id="isiDeskripsi"></p>
        <button onclick="closeDeskripsi()">Tutup</button>
    </div>

    <script>
    function showDeskripsi(teks) {
        document.getElementById('isiDeskripsi').innerText = teks;
        document.getElementById('popupDeskripsi').style.display = 'block';
    }
    function closeDeskripsi() {
        document.getElementById('popupDeskripsi').style.display = 'none';
    }
    </script>
</body>
</html>
