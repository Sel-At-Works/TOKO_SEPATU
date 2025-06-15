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
            <a href="index.html" class="back-btn">Kembali ke Produk</a>
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
  background: linear-gradient(to right, #1c1c1c, #2e2e2e);
  color: #ffffff;
  transition: background 0.5s ease-in-out;
}

.detail-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
  min-height: 100vh;
  animation: fadeIn 1.2s ease-in-out;
}

.detail-card {
  display: flex;
  flex-direction: row;
  background: #121212;
  border-radius: 20px;
  box-shadow: 0 15px 40px rgba(255, 215, 0, 0.15);
  overflow: hidden;
  max-width: 1000px;
  width: 100%;
  transition: transform 0.6s ease, box-shadow 0.6s ease;
  border: 1px solid rgba(255, 215, 0, 0.08);
}

.detail-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 50px rgba(255, 215, 0, 0.2);
}

.detail-img {
  width: 50%;
  object-fit: cover;
  height: auto;
  filter: brightness(0.95);
  transition: filter 0.6s ease;
}

.detail-img:hover {
  filter: brightness(1);
}

.detail-info {
  padding: 40px;
  width: 50%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  transition: opacity 0.6s ease;
  background: rgba(255,255,255,0.04);
  border-radius: 0 20px 20px 0;
  box-shadow: 0 0 0 1.5px rgba(255,215,0,0.08);
}

.detail-title {
  font-size: 2.1rem;
  font-weight: 700;
  color: #ffd700;
  margin-bottom: 12px;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.detail-size {
  font-size: 1.1rem;
  color: #fff;
  margin-bottom: 18px;
  font-weight: 500;
}

.detail-price {
  font-size: 1.3rem;
  font-weight: 600;
  color: #ffd700;
  margin: 18px 0;
}

.detail-desc {
  max-height: 80px;         /* tinggi maksimal sebelum dipotong */
  overflow: hidden;
  position: relative;
  transition: max-height 0.3s;
}

.detail-desc.expanded {
  max-height: 1000px;       /* cukup besar agar seluruh teks tampil */
}

.lihat-lainnya {
  display: inline-block;
  margin: 16px 0 24px 0; /* atas 16px, bawah 24px */
  background: none;
  border: none;
  color: #ffd700;
  font-weight: bold;
  cursor: pointer;
  font-size: 1rem;
  text-decoration: underline;
  margin-left: 0; /* pastikan tidak ada auto */
  align-self: flex-start; /* jika parent flex, ini akan ratakan ke kiri */
}

.label {
  color: #ffd700;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.back-btn {
  display: inline-block;
  padding: 14px 28px;
  background: linear-gradient(to right, #ffd700, #ffc107);
  color: #121212;
  font-weight: bold;
  border-radius: 40px;
  text-decoration: none;
  text-align: center;
  transition: all 0.4s ease;
  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
  max-width: 250px;
}

.back-btn:hover {
  background: linear-gradient(to right, #e6c200, #e0ac00);
  color: #ffffff;
  box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
  transform: translateY(-3px);
}

.order-btn {
  display: inline-block;
  padding: 14px 28px;
  background: linear-gradient(to right, #25d366, #128c7e);
  color: #fff;
  font-weight: bold;
  border-radius: 40px;
  text-decoration: none;
  text-align: center;
  transition: all 0.3s;
  box-shadow: 0 4px 12px rgba(37, 211, 102, 0.18);
}

.order-btn:hover {
  background: linear-gradient(to right, #128c7e, #25d366);
  color: #fff;
  transform: translateY(-2px) scale(1.04);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.produk-card-img {
  width: 100%;
  height: auto;         /* biarkan tinggi mengikuti proporsi asli gambar */
  object-fit: contain;  /* gambar tampil penuh, tidak terpotong */
  display: block;
  margin: 0 auto;
  background: #fff;     /* opsional, agar area kosong tampak jelas */
  border-radius: 12px;  /* opsional */
}
</style>