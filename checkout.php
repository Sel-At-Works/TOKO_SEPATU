<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

// Pastikan user login
if (!isset($_SESSION['id_member'])) {
    header("Location: login.php");
    exit;
}

$id_member = $_SESSION['id_member'];

// Ambil data member
$query_member = mysqli_query($koneksi, "SELECT * FROM member WHERE id_member = $id_member");
$member = mysqli_fetch_assoc($query_member);

// Pastikan data keranjang dikirim lewat POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'])) {
    $id_produk = $_POST['id_produk'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    $total_harga = 0;
    $produk_gambar = [];

    for ($i = 0; $i < count($id_produk); $i++) {
        $total_harga += $harga[$i] * $jumlah[$i];

        // Ambil gambar produk
        $id = (int)$id_produk[$i];
        $query_gambar = mysqli_query($koneksi, "SELECT gambar FROM toko_sepatu WHERE id_produk = $id LIMIT 1");
        $row_gambar = mysqli_fetch_assoc($query_gambar);
        $produk_gambar[$i] = $row_gambar ? $row_gambar['gambar'] : 'no-image.png';

        // Pastikan file gambar ada
        if (!file_exists("img/" . $produk_gambar[$i])) {
            $produk_gambar[$i] = 'no-image.png';
        }
    }
} else {
    header("Location: keranjang.php");
    exit;
}

// Proses pembayaran jika form disubmit
if (isset($_POST['proses_bayar'])) {
    $metode = $_POST['metode_pembayaran'];
    $total = (int)$_POST['total'];

    if ($metode === 'debit') {
        // Generate kode pembayaran
        $kode_pembayaran = 'VA' . rand(1000000000, 9999999999);

        // Simpan ke database
        mysqli_query($koneksi, "INSERT INTO transaksi (kode_pembayaran, id_member, total, status) 
                                VALUES ('$kode_pembayaran', '$id_member', '$total', 'Pending')");

        // Redirect ke konfirmasi
        header("Location: konfirmasi.php?kode=$kode_pembayaran");
        exit;

    } elseif ($metode === 'cash') {
        $uang_dibayar = (int)$_POST['uang_dibayar'];
        if ($uang_dibayar >= $total) {
            $kembalian = $uang_dibayar - $total;
            echo "<script>alert('Pembayaran berhasil! Kembalian: Rp " . number_format($kembalian, 0, ',', '.') . "');</script>";
        } else {
            echo "<script>alert('Uang tidak cukup!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout - Toko Sepatu</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 0;
    }

    .checkout-container {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        padding: 30px;
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }

    .alamat {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-size: 15px;
        border-left: 5px solid #28a745;
    }

    .produk-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .produk-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: #fafafa;
        border: 1px solid #ddd;
        border-radius: 10px;
        transition: 0.3s;
    }

    .produk-card:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .produk-card img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 15px;
        border: 1px solid #ccc;
    }

    .produk-info {
        flex: 1;
        margin-left: 10px;
    }

    .produk-info strong {
        font-size: 16px;
        color: #222;
    }

    .produk-info span {
        font-size: 14px;
        color: #666;
    }

    .produk-price {
        text-align: right;
    }

    .produk-price p {
        margin: 4px 0;
        font-size: 14px;
    }

    .total-section {
        margin-top: 25px;
        text-align: right;
        font-size: 22px;
        font-weight: bold;
        color: #333;
        border-top: 2px solid #ddd;
        padding-top: 15px;
    }

    form {
        margin-top: 30px;
    }

    .form-input {
        margin-bottom: 20px;
    }

    .form-input label {
        display: block;
        font-size: 15px;
        margin-bottom: 8px;
        font-weight: 500;
    }

    select, input[type="number"] {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: 0.3s;
    }

    select:focus, input[type="number"]:focus {
        border-color: #28a745;
        outline: none;
        box-shadow: 0 0 8px rgba(40,167,69,0.2);
    }

    button {
        width: 100%;
        background: #28a745;
        color: #fff;
        font-size: 18px;
        font-weight: bold;
        border: none;
        padding: 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #218838;
    }

    @media (max-width: 768px) {
        .produk-card {
            flex-direction: column;
            text-align: center;
        }
        .produk-card img {
            margin-bottom: 10px;
        }
        .produk-price {
            margin-top: 10px;
        }
    }
</style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout Pesanan Anda</h2>

    <div class="alamat">
        <p><strong>Nama: <?= htmlspecialchars($member['username']); ?></strong></p>
        <p><strong>Email: <?= htmlspecialchars($member['email']); ?></strong></p>
        <p><strong>No HP: <?= htmlspecialchars($member['no_hp']); ?></strong></p>
        <p><strong>Alamat: <?= htmlspecialchars($member['profile']); ?></strong></p>
    </div>

    <div class="produk-list">
        <?php for ($i = 0; $i < count($id_produk); $i++): ?>
            <div class="produk-card">
                <img src="img/<?= htmlspecialchars($produk_gambar[$i]); ?>" alt="Gambar Produk">
                <div class="produk-info">
                    <strong><?= htmlspecialchars($nama[$i]); ?></strong>
                    <span>Kuantitas: x<?= $jumlah[$i]; ?></span>
                </div>
                <div class="produk-price">
                    <p>Harga: Rp<?= number_format($harga[$i], 0, ',', '.'); ?></p>
                    <p><strong>Subtotal: Rp<?= number_format($harga[$i] * $jumlah[$i], 0, ',', '.'); ?></strong></p>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <div class="total-section">
        Total: Rp<?= number_format($total_harga, 0, ',', '.'); ?>
    </div>

    <form method="POST">
        <?php for ($i = 0; $i < count($id_produk); $i++): ?>
            <input type="hidden" name="id_produk[]" value="<?= $id_produk[$i]; ?>">
            <input type="hidden" name="nama[]" value="<?= htmlspecialchars($nama[$i]); ?>">
            <input type="hidden" name="harga[]" value="<?= $harga[$i]; ?>">
            <input type="hidden" name="jumlah[]" value="<?= $jumlah[$i]; ?>">
        <?php endfor; ?>

        <input type="hidden" name="total" value="<?= $total_harga; ?>">

        <div class="form-input">
            <label>Pilih Metode Pembayaran:</label>
            <select name="metode_pembayaran" id="metode" required onchange="toggleCashInput()">
                <option value="">-- Pilih --</option>
                <option value="debit">Debit (Virtual Account)</option>
                <option value="cash">Cash</option>
            </select>
        </div>

        <div class="form-input" id="cashInput" style="display:none;">
            <label>Masukkan Uang Anda:</label>
            <input type="number" name="uang_dibayar" placeholder="Masukkan jumlah uang">
        </div>

        <button type="submit" name="proses_bayar">Bayar Sekarang</button>
    </form>
</div>

<script>
function toggleCashInput() {
    const metode = document.getElementById('metode').value;
    document.getElementById('cashInput').style.display = (metode === 'cash') ? 'block' : 'none';
}
</script>

</body>
</html>
