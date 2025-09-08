<?php
require 'vendor/autoload.php';
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

$kode = $_GET['kode'] ?? '';
$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE kode_pembayaran='$kode'");
$transaksi = mysqli_fetch_assoc($query);

if(!$transaksi){
    echo "<h3>Kode pembayaran tidak ditemukan!</h3>";
    exit;
}

// Isi QR Code = kode pembayaran + total dari database
$dataQR = "KODE: {$transaksi['kode_pembayaran']} | TOTAL: Rp ".number_format($transaksi['total'],0,',','.');

$options = new QROptions([
    'outputType' => QRCode::OUTPUT_MARKUP_SVG, // gunakan SVG agar tidak butuh GD
    'eccLevel'   => QRCode::ECC_L,
    'scale'      => 5,
]);

$qrImage = (new QRCode($options))->render($dataQR);
// echo "<pre>";
// print_r($qrImage);
// echo "</pre>";
// exit;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .card {
      background: #fff;
      border-radius: 15px;
      padding: 30px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      text-align: center;
    }
    .qr-code svg {
      max-width: 200px;
      background: #fff;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Konfirmasi Pembayaran</h2>
    <div>Kode Pembayaran: <b><?= $transaksi['kode_pembayaran']; ?></b></div>
    <div>Total: <b>Rp <?= number_format($transaksi['total'],0,',','.'); ?></b></div>

    <p>Scan QR Code untuk membayar:</p>
   <div class="qr-code">
    <?php
    // tampilkan QR Code di sini
    echo '<img src="' . $qrImage . '" alt="QR Code">';
    ?>
</div>
  </div>
</body>
</html>
