<?php
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");
$produk = mysqli_query($koneksi, "SELECT * FROM produk");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Produk</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background: #ffd700; }
    .btn { padding: 6px 12px; border-radius: 6px; text-decoration: none; }
    .edit { background: #ffc107; color: #222; }
    .hapus { background: #e74c3c; color: #fff; }
  </style>
</head>
<body>
  <h2>Daftar Produk</h2>
  <a href="tambah_produk.php" class="btn edit">Tambah Produk</a>
  <table>
    <tr>
      <th>No</th>
      <th>Nama Produk</th>
      <th>Harga</th>
      <th>Gambar</th>
      <th>Aksi</th>
    </tr>
    <?php $no=1; while($row = mysqli_fetch_assoc($produk)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nama_produk']) ?></td>
      <td>Rp.<?= number_format($row['harga'],0,',','.') ?></td>
      <td><img src="img/<?= htmlspecialchars($row['gambar']) ?>" width="60"></td>
      <td>
        <a href="edit_produk.php?id=<?= $row['id_produk'] ?>" class="btn edit">Edit</a>
        <a href="hapus_produk.php?id=<?= $row['id_produk'] ?>" class="btn hapus" onclick="return confirm('Yakin hapus?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>