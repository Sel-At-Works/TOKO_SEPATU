<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

// Ambil id_produk dari URL
$id = isset($_GET['id_produk']) ? intval($_GET['id_produk']) : 0;

// Query data produk
$sql = "SELECT * FROM toko_sepatu WHERE id_produk = $id";
$result = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk | Marsel Shoes</title>
  <link rel="stylesheet" href="detail.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<!-- Tombol kembali di samping div detail-container -->
<!-- Tombol kembali ke produk: panah + tulisan "Kembali" warna putih -->
<div class="page-container">
  <a href="index.php#produk" style="max-width: 120px; text-align: center; padding: 14px 0; font-size: 2rem; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 8px;">
  <span style="font-size:2rem; color:#000000;">&#8592;</span>
    <span style="font-size:1.1rem; color:#000000; font-weight:bold;">Kembali</span>
  </a>
  <div class="detail-container">
    <div class="detail-card">
      <?php if($data): ?>
        <img src="img/<?php echo htmlspecialchars($data['gambar']); ?>" alt="<?php echo htmlspecialchars($data['nama_produk']); ?>" class="detail-img">
        <div class="detail-info">
          <h1 class="detail-title"><?php echo htmlspecialchars($data['nama_produk']); ?></h1>
          <?php if(!empty($data['size'])): ?>
            <div class="detail-size"><span class="label">Ukuran:</span> <?php echo htmlspecialchars($data['size']); ?></div>
          <?php endif; ?>
          <div class="detail-divider" style="width:100%;height:2px;background:linear-gradient(to right,#ffd700,#fff);margin:18px 0;opacity:0.25;border-radius:2px;"></div>
          <p class="detail-price"><span class="label">Harga:</span> Rp.<?php echo number_format($data['harga'],0,',','.'); ?></p>
          <div class="detail-divider" style="width:100%;height:2px;background:linear-gradient(to right,#ffd700,#fff);margin:18px 0;opacity:0.25;border-radius:2px;"></div>
          <p class="detail-desc" id="deskripsi">
            <span class="label">Deskripsi:</span><br>
            <?php echo htmlspecialchars($data['deskripsi']); ?>
          </p>
          <button class="lihat-lainnya" id="lihatLainnyaBtn" onclick="toggleDeskripsi()">Lihat lainnya</button>
          <div style="display: flex; gap: 12px; margin-top: 8px;">
           <form action="keranjang.php" method="POST" class="form-keranjang">
            <input type="hidden" name="id_produk" value="<?php echo $data['id_produk']; ?>">
            <button type="submit" class="back-btn">Masukan Keranjang</button>
          </form>
            <a href="https://wa.me/6281219657898?text=Halo%20saya%20ingin%20memesan%20produk%20<?php echo urlencode($data['nama_produk']); ?>" class="order-btn" target="_blank">Pesan Sekarang</a>
          </div>
        </div>
      <?php else: ?>
        <div class="detail-info">
          <p>Produk tidak ditemukan.</p>
          <a href="index.html" class="back-btn">Kembali ke Produk</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    function toggleDeskripsi() {
      const deskripsi = document.getElementById('deskripsi');
      const btnLihatLainnya = document.getElementById('lihatLainnyaBtn');
      if (deskripsi.style.maxHeight) {
        deskripsi.style.maxHeight = null;
        btnLihatLainnya.innerText = 'Lihat lainnya';
      } else {
        deskripsi.style.maxHeight = deskripsi.scrollHeight + 'px';
        btnLihatLainnya.innerText = 'Tampilkan lebih sedikit';
      } 
    }
  </script>
</body>
</html>


<style>
body {
  margin: 0;
  padding: 0;
  font-family: 'Roboto', sans-serif;
  background-color: #f9f9f9;
  color: #222;
}

.page-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 40px 20px;
  max-width: 960px;
  margin: 0 auto;
}

.detail-container {
  width: 100%;
}

.detail-card {
  display: flex;
  flex-direction: row;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.06);
  overflow: hidden;
  width: 100%;
  transition: 0.3s ease;
}

.detail-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.detail-img {
  width: 50%;
  object-fit: cover;
  height: auto;
}

.detail-info {
  padding: 24px;
  width: 50%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  background-color: #ffffff;
}

.detail-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 12px;
  color: #333;
}

.detail-size,
.detail-price {
  font-size: 1rem;
  margin-bottom: 12px;
}

.detail-divider {
  height: 1px;
  background: #e0e0e0;
  margin: 16px 0;
}

.detail-desc {
  font-size: 0.95rem;
  color: #444;
  max-height: 80px;
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.detail-desc.expanded {
  max-height: 1000px;
}

.lihat-lainnya {
  margin: 12px 0;
  background: none;
  border: none;
  color: #007bff;
  font-weight: 500;
  cursor: pointer;
  font-size: 0.95rem;
  text-decoration: underline;
  align-self: flex-start;
}

.label {
  font-weight: 600;
  color: #555;
}

.back-btn,
.order-btn {
  display: inline-block;
  padding: 10px 20px;
  border-radius: 6px;
  font-weight: 500;
  font-size: 0.95rem;
  text-align: center;
  text-decoration: none;
  transition: all 0.3s ease;
  margin-top: 12px;
}

.back-btn {
  background: #f1f1f1;
  color: #333;
  border: 1px solid #ccc;
}

.back-btn:hover {
  background: #e2e2e2;
}

.order-btn {
  background: #25d366;
  color: #fff;
  margin-left: 10px;
}

.form-keranjang {
  margin: 0;
    display: inline-block;
}


.order-btn:hover {
  background: #1ebe5d;
}

/* Tombol Kembali di atas */
.page-container > a {
  align-self: flex-start;
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 20px;
  text-decoration: none;
  color: #007bff;
  font-size: 1rem;
}

.page-container > a:hover {
  text-decoration: underline;
}
</style>