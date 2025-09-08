<?php
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

// Update status jika ada parameter
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status']; // 'Paid' atau 'Expired'
    mysqli_query($koneksi, "UPDATE transaksi SET status='$status' WHERE id_transaksi='$id'");
}

// Ambil semua transaksi
$query = mysqli_query($koneksi, "SELECT * FROM transaksi");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Transaksi</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f7f9;
        margin: 0;
        padding: 20px;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    thead {
        background: #007bff;
        color: #fff;
    }
    th, td {
        padding: 12px 15px;
        text-align: center;
        font-size: 14px;
        border-bottom: 1px solid #ddd;
    }
    tr:hover {
        background: #f1f5ff;
    }
    img {
        border-radius: 8px;
        max-width: 90px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    img:hover {
        transform: scale(1.05);
    }
    .status {
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: bold;
        color: #fff;
    }
    .status.Pending { background: #ffc107; }
    .status.Paid { background: #28a745; }
    .status.Expired { background: #dc3545; }
    .btn {
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
        color: #fff;
        transition: 0.3s;
    }
    .btn.paid { background: #28a745; }
    .btn.expired { background: #dc3545; }
    .btn:hover { opacity: 0.8; }
    .no-upload {
        color: #888;
        font-style: italic;
    }
    .container {
        max-width: 900px;
        margin: auto;
    }

    /* Modal Style */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
    }
    .modal img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.5);
    }
    .modal-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #fff;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Daftar Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Total</th>
                <th>Status</th>
                <th>Bukti Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= htmlspecialchars($row['kode_pembayaran']); ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                <td><span class="status <?= $row['status']; ?>"><?= $row['status']; ?></span></td>
                <td>
                    <?php if (!empty($row['bukti_pembayaran'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['bukti_pembayaran']); ?>" alt="Bukti" onclick="openModal(this.src)">
                    <?php else: ?>
                        <span class="no-upload">Belum upload</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="?id=<?= $row['id_transaksi']; ?>&status=Paid" class="btn paid">✔ Mark Paid</a>
                    <a href="?id=<?= $row['id_transaksi']; ?>&status=Expired" class="btn expired">✖ Expire</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal untuk preview gambar -->
<div class="modal" id="imgModal">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" alt="Preview">
</div>

<script>
    function openModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imgModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('imgModal').style.display = 'none';
    }
</script>

</body>
</html>
