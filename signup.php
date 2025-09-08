<?php
$pesan = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

   $sql = "INSERT INTO member (username, password) VALUES ('$username', '$password_hash')";
if (mysqli_query($koneksi, $sql)) {
    $pesan = "<div class='signup-success'>Akun berhasil dibuat! <a href='login.php'>Login di sini</a></div>";
} else {
    $pesan = "<div class='signup-error'>Gagal menyimpan ke database!</div>";
}

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up Member | Marsel Shoes</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      padding: 0;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Roboto', sans-serif;
    }
    .signup-container {
      width: 100%;
      max-width: 370px;
      padding: 36px 30px 30px 30px;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(182,137,91,0.15);
      border: 1.5px solid #ffd700;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      animation: fadeIn 0.8s;
    }
    .signup-title {
      text-align: center;
      color: #b6895b;
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 28px;
      letter-spacing: 1px;
    }
    .signup-form {
      width: 100%;
    }
    .signup-form label {
      display: block;
      margin-bottom: 7px;
      color: #444;
      font-weight: 500;
    }
    .signup-form input[type="text"],
    .signup-form input[type="password"],
    .signup-form input[type="file"] {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 18px;
      border: 1.5px solid #ffd700;
      border-radius: 8px;
      font-size: 1rem;
      background: #f9f9f9;
      transition: border 0.2s;
    }
    .signup-form input[type="text"]:focus,
    .signup-form input[type="password"]:focus,
    .signup-form input[type="file"]:focus {
      border-color: #b6895b;
      outline: none;
    }
    .signup-form button {
      width: 100%;
      padding: 12px 0;
      background: linear-gradient(90deg, #ffd700 0%, #ffc107 100%);
      color: #222;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      font-size: 1.08rem;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
      margin-top: 8px;
      box-shadow: 0 2px 8px rgba(182,137,91,0.10);
    }
    .signup-form button:hover {
      background: #fff;
      color: #b6895b;
    }
    .signup-link {
      display: block;
      text-align: center;
      margin-top: 18px;
      font-size: 0.97rem;
      color: #444;
    }
    .signup-link a {
      color: #b6895b;
      text-decoration: underline;
      transition: color 0.2s;
    }
    .signup-link a:hover {
      color: #ffd700;
    }
    .signup-error {
      color: #e74c3c;
      background: #fffbe6;
      border: 1px solid #ffd700;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 16px;
      text-align: center;
    }
    .signup-success {
      color: #388e3c;
      background: #eaffea;
      border: 1px solid #b6e6b6;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 16px;
      text-align: center;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to   { opacity: 1; transform: translateY(0);}
    }
    @media (max-width: 500px) {
      .signup-container {
        padding: 24px 10px 18px 10px;
        max-width: 95vw;
      }
    }
  </style>
</head>
<body>
  <div class="signup-container">
    <div class="signup-title">Sign Up Member</div>
    <?php if($pesan) echo $pesan; ?>
    <form class="signup-form" method="post" enctype="multipart/form-data">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
<!-- 
      <label for="foto">Foto</label>
      <input type="file" id="foto" name="foto" accept="image/*" required> -->

      <button type="submit">Sign Up</button>
    </form>
    <div class="signup-link">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </div>
  </div>
</body>
</html>