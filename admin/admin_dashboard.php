<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin | Marsel Shoes</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #fff;
      font-family: 'Roboto', sans-serif;
      min-height: 100vh;
      display: flex;
    }
    .sidebar {
      width: 230px;
      background: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 32px 0 0 0;
      box-shadow: 2px 0 12px rgba(182,137,91,0.10);
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      z-index: 100;
    }
    .sidebar .brand {
      font-size: 1.5rem;
      font-weight: 700;
      color: #b6895b;
      letter-spacing: 1.5px;
      margin-left: 32px;
      margin-bottom: 36px;
      text-shadow: 0 2px 8px #fffbe6;
    }
    .sidebar-menu {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 40px;
    }
    .sidebar-link {
      display: block;
      width: 100%;
      padding: 16px 32px;
      color: #b6895b;
      font-size: 1.08rem;
      font-weight: 600;
      text-decoration: none;
      border-left: 4px solid transparent;
      background: #fff;
      position: relative;
      transition: background 0.2s, color 0.2s, border 0.2s;
      overflow: hidden;
    }
    .sidebar-link::after {
      content: "";
      display: block;
      position: absolute;
      left: 32px;
      right: 32px;
      bottom: 10px;
      height: 3px;
      background: #b6895b;
      border-radius: 2px;
      transform: scaleX(0);
      transition: transform 0.3s cubic-bezier(.4,0,.2,1);
      transform-origin: left;
    }
    .sidebar-link.active::after,
    .sidebar-link:focus::after,
    .sidebar-link:active::after {
      transform: scaleX(1);
    }
    .sidebar-link:hover {
      background: #f5f5f5;
      color: #222;
      border-left: 4px solid #b6895b;
    }
    .sidebar-link:hover::after {
      transform: scaleX(1);
    }
    .sidebar-logout {
      margin-top: auto;
      width: 100%;
      padding: 16px 32px;
      border: none;
      background: #b6895b;
      color: #fff;
      font-size: 1rem;
      font-weight: 500;
      border-radius: 0;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
      text-align: left;
    }
    .sidebar-logout:hover {
      background: #fff;
      color: #b6895b;
      border-left: 4px solid #b6895b;
    }
    .main-content {
      margin-left: 230px;
      width: 100%;
      padding: 48px 38px 38px 38px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      animation: fadeIn 0.8s;
      background: #fff;
    }
    .dashboard-title {
      font-size: 2.1rem;
      color: #b6895b;
      font-weight: 800;
      margin-bottom: 12px;
      letter-spacing: 1.5px;
      text-align: center;
      text-shadow: 0 2px 8px #fffbe6;
    }
    .dashboard-welcome {
      font-size: 1.15rem;
      color: #444;
      margin-bottom: 32px;
      text-align: center;
    }
    @media (max-width: 900px) {
      .sidebar {
        width: 100px;
        padding-left: 0;
        padding-right: 0;
      }
      .sidebar .brand {
        font-size: 1.1rem;
        margin-left: 10px;
        margin-bottom: 20px;
      }
      .sidebar-link, .sidebar-logout {
        padding: 12px 10px;
        font-size: 0.98rem;
      }
      .main-content {
        margin-left: 100px;
        padding: 24px 8px 24px 8px;
      }
      .sidebar-link::after {
        left: 10px;
        right: 10px;
      }
    }
    @media (max-width: 600px) {
      .sidebar {
        position: static;
        width: 100vw;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        height: auto;
        min-height: unset;
        padding: 0;
        box-shadow: none;
      }
      .sidebar .brand {
        margin: 0 0 0 10px;
      }
      .sidebar-menu {
        flex-direction: row;
        gap: 0;
        margin: 0;
      }
      .sidebar-link, .sidebar-logout {
        padding: 10px 8px;
        font-size: 0.95rem;
        border-left: none;
        border-bottom: 2px solid transparent;
      }
      .sidebar-link::after {
        left: 8px;
        right: 8px;
        bottom: 6px;
      }
      .sidebar-link:hover, .sidebar-link.active, .sidebar-logout:hover {
        border-left: none;
        border-bottom: 2px solid #b6895b;
        background: #f5f5f5;
      }
      .main-content {
        margin-left: 0;
        padding: 16px 2vw 16px 2vw;
      }
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to   { opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="brand">Marsel Shoes<br><span style="font-size:1rem;font-weight:400;">Tampilan Admin</span></div>
    <div class="sidebar-menu">
      <a href="admin_dashboard.php" class="sidebar-link active">Dashboard</a>
      <a href="data_produk.php" class="sidebar-link">Kelola Produk</a>
      <a href="data_member.php" class="sidebar-link">Kelola Member</a>
      <a href="data_transaksi.php" class="sidebar-link">Kelola Transaksi</a>
      <a href="data_admin.php" class="sidebar-link">Kelola Admin</a>
    </div>
    <form method="post" action="../logout.php" style="width:100%;">
      <button type="submit" class="sidebar-logout">Logout</button>
    </form>
  </div>
  <div class="main-content">
    <div class="dashboard-title">Dashboard Admin</div>
    <div class="dashboard-welcome">
      Selamat datang, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>!<br>
      Silakan pilih menu di sidebar untuk mengelola toko sepatu.
    </div>
    <!-- Tambahkan konten dashboard di sini -->
  </div>
</body>
</html>