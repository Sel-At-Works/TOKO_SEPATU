<?php
session_start();

// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

//Update jumlah di session sebelum checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_jumlah'])) {
    foreach ($_POST['jumlah'] as $index => $jumlah) {
        $_SESSION['keranjang'][$index]['jumlah'] = max(1, intval($jumlah));
    }
}


// Tambahkan ke keranjang jika ada POST dari detail.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'])) {
  $id_produk = intval($_POST['id_produk']);

  // Ambil data produk dari database
  $query = "SELECT * FROM toko_sepatu WHERE id_produk = $id_produk";
  $result = mysqli_query($koneksi, $query);
  $produk = mysqli_fetch_assoc($result);

  if ($produk) {
    // Pastikan session keranjang ada
    if (!isset($_SESSION['keranjang'])) {
      $_SESSION['keranjang'] = [];
    }

    // Cek apakah produk sudah ada di keranjang
    $sudah_ada = false;
    foreach ($_SESSION['keranjang'] as &$item) {
      if ($item['id_produk'] == $produk['id_produk']) {
        $item['jumlah'] += 1;
        $sudah_ada = true;
        break;
      }
    }

    if (!$sudah_ada) {
      $_SESSION['keranjang'][] = [
        'id_produk' => $produk['id_produk'],
        'nama' => $produk['nama_produk'],
        'harga' => $produk['harga'],
        'gambar' => $produk['gambar'],
        'jumlah' => 1
      ];
    }

    // Redirect agar tidak re-submit saat refresh
    header("Location: keranjang.php");
    exit;
  }
}


// Hapus produk dari keranjang
if (isset($_GET['hapus'])) {
  $index = $_GET['hapus'];
  if (isset($_SESSION['keranjang'][$index])) {
    unset($_SESSION['keranjang'][$index]);
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Re-index
  }
  header("Location: keranjang.php");
  exit;
}

// Update jumlah barang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_jumlah'])) {
  foreach ($_POST['jumlah'] as $index => $jumlah) {
    $_SESSION['keranjang'][$index]['jumlah'] = max(1, intval($jumlah)); // Minimal 1
  }
}

// Data keranjang
$keranjang = $_SESSION['keranjang'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <style>
    body {
      font-family: Roboto, sans-serif;
      background: #f9f9f9;
      padding: 40px;
      color: #222;
    }
    h1 {
      margin-bottom: 20px;
    }
    form {
      max-width: 800px;
      margin: auto;
    }
    .item {
      background: #fff;
      border: 1px solid #ddd;
      padding: 16px;
      margin-bottom: 12px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .item img {
      width: 80px;
      border-radius: 4px;
    }
    .item-info {
      flex: 1;
    }
    .item-info h3 {
      margin: 0 0 6px;
    }
    input[type='number'] {
      width: 60px;
      padding: 4px;
      text-align: center;
    }
    .hapus-btn {
      background: #ff4d4d;
      color: #fff;
      border: none;
      padding: 6px 10px;
      border-radius: 6px;
      cursor: pointer;
    }
    .hapus-btn:hover {
      background: #d93636;
    }
    .total {
      font-weight: bold;
      text-align: right;
      margin-top: 20px;
    }
    .checkout-btn {
      background: #25d366;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 16px;
    }
    .checkout-btn:hover {
      background: #1ebe5d;
    }
    .item input[type="checkbox"] {
      transform: scale(1.2);
    }
  </style>
</head>
<body>
  <h1 style="text-align: center;">Keranjang Belanja Anda</h1>


  <?php if (!empty($keranjang)): ?>
  <form method="POST" action="checkout.php" id="keranjangForm">
      <?php
        $total_harga = 0;
        foreach ($keranjang as $index => $item):
          $sub_total = $item['harga'] * $item['jumlah'];
          $total_harga += $sub_total;
      ?>
        <div class="item">
          <input type="checkbox" class="checkbox-item" name="pilih[]" value="<?php echo $index; ?>" checked data-subtotal="<?php echo $sub_total; ?>">
          <img src="img/<?php echo htmlspecialchars($item['gambar']); ?>" alt="">
          <div class="item-info">
            <h3><?php echo htmlspecialchars($item['nama']); ?></h3>
            <p>Harga: Rp.<?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
            <label>Jumlah:
              <input type="number" name="jumlah[<?php echo $index; ?>]" value="<?php echo $item['jumlah']; ?>" min="1">
            </label>
            <p>Subtotal: Rp.<?php echo number_format($sub_total, 0, ',', '.'); ?></p>
          </div>
          <a href="?hapus=<?php echo $index; ?>" class="hapus-btn">Hapus</a>
        </div>
         <!-- ✅ Hidden inputs -->
    <input type="hidden" name="id_produk[]" value="<?php echo $item['id_produk']; ?>" data-index="<?php echo $index; ?>">
    <input type="hidden" name="nama[]" value="<?php echo htmlspecialchars($item['nama']); ?>" data-index="<?php echo $index; ?>">
    <input type="hidden" name="harga[]" value="<?php echo $item['harga']; ?>" data-index="<?php echo $index; ?>">
    <input type="hidden" name="jumlah_hidden[]" value="<?php echo $item['jumlah']; ?>" data-index="<?php echo $index; ?>">
  </div>
      <?php endforeach; ?>
     <div class="total">Total: <span id="totalHarga">Rp.0</span></div>
      <button type="submit" class="checkout-btn">Lanjutkan Transaksi</button>
    </form>
  <?php else: ?>
    <p style="text-align: center;">Keranjang kamu masih kosong.</p>
    <div style="text-align: center; margin-bottom: 20px;">
  <a href="index.php" style="
    background: #007bff;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 1rem;
  ">← Kembali Belanja</a>
</div>

<h1 style="text-align: center; font-size: 5rem; color: #ccc;">0</h1>

  <?php endif; ?>

  <!-- javascripst untuk checkbox --><script>
  function updateTotal() {
    let total = 0;
    document.querySelectorAll('.checkbox-item').forEach(cb => {
      if (cb.checked) {
        total += parseInt(cb.dataset.subtotal);
      }
    });
    document.getElementById('totalHarga').innerText = formatRupiah(total);
  }

  function formatRupiah(angka) {
    return 'Rp.' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  // Update subtotal & total saat quantity berubah
  function updateSubtotal(index, harga) {
    let qtyInput = document.querySelector(`input[name="jumlah[${index}]"]`);
    let newQty = parseInt(qtyInput.value);
    if (isNaN(newQty) || newQty < 1) newQty = 1;

    // Hitung subtotal baru
    let newSubtotal = newQty * harga;

    // Update tampilan subtotal di UI
    let subtotalElement = qtyInput.closest('.item-info').querySelector('p:last-child');
    subtotalElement.innerText = 'Subtotal: ' + formatRupiah(newSubtotal);

    // Update dataset checkbox
    let checkbox = document.querySelector(`.checkbox-item[value="${index}"]`);
    checkbox.dataset.subtotal = newSubtotal;

    // Update total keseluruhan
    updateTotal();
  }

  // Event untuk checkbox
  document.querySelectorAll('.checkbox-item').forEach(cb => {
    cb.addEventListener('change', updateTotal);
  });

  // Event untuk quantity
  document.querySelectorAll('input[type="number"]').forEach((input, i) => {
    let harga = parseInt(input.closest('.item-info').querySelector('p').innerText.replace(/\D/g, '')); 
    input.addEventListener('input', function() {
      updateSubtotal(i, harga);
    });
  });

  // Jalankan pertama kali saat halaman dimuat
  updateTotal();
  document.getElementById('keranjangForm').addEventListener('submit', function() {
    document.querySelectorAll('input[name^="jumlah["]').forEach(input => {
        let index = input.name.match(/\d+/)[0]; // Ambil index
        document.querySelector(`input[name="jumlah_hidden[]"][data-index="${index}"]`).value = input.value;
    });
});

</script>
</body>
</html>
