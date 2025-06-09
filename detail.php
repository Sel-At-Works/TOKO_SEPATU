
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
          <p class="detail-price">Rp.<?php echo number_format($data['harga'],0,',','.'); ?></p>
          <p class="detail-desc"><?php echo htmlspecialchars($data['deskripsi']); ?></p>
          <a href="index.html" class="back-btn">Kembali ke Produk</a>
        </div>
      <?php else: ?>
        <p>Produk tidak ditemukan.</p>
        <a href="index.html" class="back-btn">Kembali ke Produk</a>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

<style>
  /* detail.css */

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
}

.detail-title {
  font-size: 2.6rem;
  font-weight: 700;
  color: #ffffff;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: color 0.5s ease;
}

.detail-price {
  font-size: 2rem;
  font-weight: 600;
  color: #ffffff;
  margin-bottom: 25px;
}

.detail-desc {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #e0e0e0;
  margin-bottom: 35px;
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

/* Smooth fade-in animation */
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

</style>