<?php session_start(); ?>
<?php
$koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");
$produk = mysqli_query($koneksi, "SELECT * FROM toko_sepatu ORDER BY id_produk DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marsel_Shoes</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #f2f2f2;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
        }

        .logout-btn {
            background-color: transparent;
            border: none;
            color: #e63946;
            text-decoration: none;
            font-weight: 500;
            margin-left: 8px;
            transition: color 0.2s ease;
        }

        .logout-btn:hover {
            color: #b71c1c;
        }

        .login-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background-color: #333;
            color: #fff;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .login-btn:hover {
            background-color: #555;
        }

        /* Tambahkan atau ganti di style.css atau di <style> pada index.php */
        /* Perbesar gambar tentang kami di desktop */
        .about .row .about-img img {
            width: 100%;
            max-width: 500px;
            /* Lebih besar di desktop */
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(182, 137, 91, 0.10);
        }

        @media (max-width: 900px) {
            .about .row .about-img img {
                max-width: 350px;
            }
        }

        @media (max-width: 768px) {
            .about .row .about-img img {
                max-width: 90vw;
                min-width: 0;
            }
        }
    </style>
</head>

<body>
    <!-- navbar start -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">Marsel<span>Shoes</span>.</a>
        <div class="navbar-menu">
            <a href="#">Home</a>
            <a href="#about">Tentang Kami</a>
            <a href="#produk">Produk</a>
            <a href="#contact">Kontak</a>
        </div>
        <div class="nav-extra">
            <a href="#" id="search"><i data-feather="search"></i></a>
            <a href="keranjang.php" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
            <a href="#" id="menu"><i data-feather="menu"></i></a>
        </div>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                <div class="auth-buttons">
                    <a href="profil.php" class="login-btn">
                        <i data-feather="user"></i>
                        <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                    </a>
                    <a href="logout.php" class="logout-btn" onclick="return confirmLogout()">Logout</a>

                    <script>
                        function confirmLogout() {
                            return confirm("Apakah Anda yakin ingin logout?");
                        }
                    </script>


                </div>
            <?php else: ?>
                <a href="login.php" class="login-btn">
                    <i data-feather="user"></i>
                    <span>Login</span>
                </a>
            <?php endif; ?>

        </div>


    </nav>
    <!-- navbar end -->

    <!-- hero section start -->
    <section class="hero" id="home">
        <main class="content">
            <h1>Mari Cobakan <span>Produk</span> Kami</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam, itaque.</p>
            <a href="#" class="cta">Beli Sekarang</a>
        </main>
    </section>
    <!-- hero section end-->

    <!-- About Section Start -->
    <section class="about" id="about">
        <h2><span>Tentang</span> Kami</h2>
        <div class="row">
            <div class="about-img">
                <img src="img/tentang-kami.jpg" alt="Tentang Kami">
            </div>
            <div class="content">
                <h3>Kenapa Memilih Produk Kami?</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Libero eius quasi ducimus odit quas voluptatibus!</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime modi totam, in minus laboriosam magni cum voluptates magnam perspiciatis provident.</p>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Menu Section start -->
    <section class="produk" id="produk">
        <h2><span>Produk</span> Kami</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero totam, nihil maiores, pariatur qui necessitatibus ducimus recusandae quo, doloribus reprehenderit error fugiat laboriosam explicabo ipsa autem assumenda nostrum. At quam odio nesciunt doloribus sit adipisci quo quia nisi dolorum quaerat!</p>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($produk)): ?>
                <div class="produk-card">
                    <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>" class="produk-card-img">
                    <h3 class="produk-card-title"><?= htmlspecialchars($row['nama_produk']) ?></h3>
                    <p class="produk-card-price">IDR <?= number_format($row['harga'], 0, ',', '.') ?></p>
                    <a href="detail.php?id_produk=<?= $row['id_produk'] ?>" class="detail">Detail Produk</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <!-- Menu Section end -->

    <section id="contact" class="contact">
        <h2><span>Kontak</span> Kami</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam, odit!</p>
        <div class="row">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.401662208277!2d106.93480477475043!3d-6.210637293777245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698b608c9360fd%3A0x4b5d5658d4a1ecd2!2sSMP%20Assyairiyah%20Attahiriyah!5e0!3m2!1sid!2sid!4v1748958048282!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>
            <form action="proses_kontak.php" method="POST">
                <div class="input-grup">
                    <i data-feather="user"></i>
                    <input type="text" name="nama_pengguna" placeholder="Nama Anda" required>
                </div>
                <div class="input-grup">
                    <i data-feather="mail"></i>
                    <input type="text" name="email_pengguna" placeholder="Email Anda" required>
                </div>
                <div class="input-grup">
                    <i data-feather="phone"></i>
                    <input type="text" name="nomor_pengguna" placeholder="Nomor Handphone" required>
                </div>
                <div class="input-grup">
                    <i data-feather="edit"></i>
                    <input type="text" name="deskripsi" placeholder="pesan" required>
                </div>
                <button type="submit" class="btn">Kirim Pesan</button>
            </form>
        </div>
    </section>
    <!-- contact section end -->

    <!-- footer start -->
    <footer>
        <div class="socials">
            <a href="#"><i data-feather="facebook"></i></a>
            <a href="#"><i data-feather="instagram"></i></a>
            <a href="#"><i data-feather="twitter"></i></a>
        </div>
        <div class="links">
            <a href="#home">Home</a>
            <a href="#about">Tentang Kami</a>
            <a href="#produk">Produk</a>
            <a href="#contact">Kontak</a>
        </div>
        <div class="credit">
            <p>Created by <a href="">MarselShoes</a>. | &copy; 2025.</p>
        </div>
    </footer>
    <!-- footer end -->

    <script>
        feather.replace();
    </script>
    <script src="js/script.js"></script>
</body>

</html>