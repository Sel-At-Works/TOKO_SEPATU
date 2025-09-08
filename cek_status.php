<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // composer require phpmailer/phpmailer

$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");

// Inisialisasi variabel
$transaksi = null;
$error = "";
$success = "";

// Cari transaksi berdasarkan kode
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cari'])) {
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE kode_pembayaran='$kode'");
    $transaksi = mysqli_fetch_assoc($query);

    if (!$transaksi) {
        $error = "Kode pembayaran tidak ditemukan!";
    }
}

// Upload bukti pembayaran + kirim email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $kode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['kode_pembayaran']);

    // Cek transaksi valid
    $query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE kode_pembayaran='$kode_pembayaran'");
    $transaksi = mysqli_fetch_assoc($query);

    if (!$transaksi) {
        $error = "Kode pembayaran tidak valid!";
    } else {
        if (isset($_FILES['bukti']['name']) && $_FILES['bukti']['error'] == 0) {
            $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
            $filename = "bukti_" . time() . "." . $ext;
            $upload_path = "uploads/" . $filename;

            if (move_uploaded_file($_FILES['bukti']['tmp_name'], $upload_path)) {
                // Update database
                mysqli_query($koneksi, "UPDATE transaksi SET bukti_pembayaran='$filename', status='Menunggu Konfirmasi' WHERE kode_pembayaran='$kode_pembayaran'");

                // Kirim Email pakai Mailtrap
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'c6e92c53cc1737'; // isi dari Mailtrap
                    $mail->Password   = '****3e89';       // isi dari Mailtrap
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    // From & To
                    $mail->setFrom('no-reply@tokosepatu.com', 'Toko Sepatu');
                    $mail->addAddress('admin@tokosepatu.com'); // email admin
                    if (!empty($transaksi['email'])) {
                        $mail->addAddress($transaksi['email']); // email pembeli
                    }

                    // Konten Email
                    $mail->isHTML(true);
                    $mail->Subject = "Konfirmasi Pembayaran - Kode $kode_pembayaran";
                    $mail->Body    = "
                        <h3>Bukti Pembayaran Berhasil Diupload</h3>
                        <p>Kode Pembayaran: <b>$kode_pembayaran</b></p>
                        <p>Total: Rp " . number_format($transaksi['total'], 0, ',', '.') . "</p>
                        <p>Status saat ini: <b>Menunggu Konfirmasi</b></p>
                    ";

                    $mail->send();
                    $success = "Bukti pembayaran berhasil diupload & email notifikasi terkirim!";
                } catch (Exception $e) {
                    $error = "Bukti tersimpan, tapi email gagal dikirim: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Gagal mengupload bukti pembayaran!";
            }
        } else {
            $error = "Silakan pilih file bukti pembayaran!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cek Status Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-3 text-center">Cek Status Pembayaran</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>

        <!-- Form cari kode pembayaran -->
        <form method="POST" class="mb-3">
            <div class="input-group">
                <input type="text" name="kode" class="form-control" placeholder="Masukkan Kode Pembayaran" required>
                <button type="submit" name="cari" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <?php if ($transaksi): ?>
            <div class="border p-3 mb-3 rounded bg-white">
                <h5>Detail Transaksi</h5>
                <p><strong>Kode Pembayaran:</strong> <?= htmlspecialchars($transaksi['kode_pembayaran']); ?></p>
                <p><strong>Total:</strong> Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($transaksi['status']); ?></p>
            </div>

            <!-- Form upload bukti pembayaran -->
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="kode_pembayaran" value="<?= htmlspecialchars($transaksi['kode_pembayaran']); ?>">
                <div class="mb-3">
                    <label class="form-label">Upload Bukti Pembayaran:</label>
                    <input type="file" name="bukti" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" name="upload" class="btn btn-success">Upload Bukti</button>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
