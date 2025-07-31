<?php
session_start();
$pesan = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcode admin
    if ($username === "MARSELSHOES23" && $password === "123") {
        $_SESSION['login'] = true;
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $username;
        header("Location: admin/admin_dashboard.php");
        exit;
    }

    // Cek member di database
    $koneksi = mysqli_connect("localhost", "root", "", "toko_sepatu");
    $username_db = mysqli_real_escape_string($koneksi, $username);
    $password_db = mysqli_real_escape_string($koneksi, $password);

    $sql_member = "SELECT * FROM member WHERE username='$username_db'";
    $result_member = mysqli_query($koneksi, $sql_member);
    if ($row_member = mysqli_fetch_assoc($result_member)) {
        if (password_verify($password_db, $row_member['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['role'] = 'member';
            $_SESSION['username'] = $row_member['username'];
            header("Location: index.php");
            exit;
        }
    }

    $pesan = "<div class='login-error'>Username atau password salah!</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Admin | Marsel Shoes</title>
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
    .login-container {
      width: 100%;
      max-width: 350px;
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
    .login-title {
      text-align: center;
      color: #b6895b;
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 28px;
      letter-spacing: 1px;
    }
    .login-form {
      width: 100%;
    }
    .login-form label {
      display: block;
      margin-bottom: 7px;
      color: #444;
      font-weight: 500;
    }
    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 18px;
      border: 1.5px solid #ffd700;
      border-radius: 8px;
      font-size: 1rem;
      background: #f9f9f9;
      transition: border 0.2s;
    }
    .login-form input[type="text"]:focus,
    .login-form input[type="password"]:focus {
      border-color: #b6895b;
      outline: none;
    }
    .login-form button {
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
    .login-form button:hover {
      background: #fff;
      color: #b6895b;
    }
    .login-form .login-error {
      color: #e74c3c;
      text-align: center;
      margin-bottom: 12px;
      font-size: 0.98rem;
    }
    .reset-link {
      display: block;
      text-align: right;
      margin-top: 6px;
      margin-bottom: 12px;
      font-size: 0.97rem;
    }
    .reset-link a {
      color: #b6895b;
      text-decoration: underline;
      transition: color 0.2s;
    }
    .reset-link a:hover {
      color: #ffd700;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to   { opacity: 1; transform: translateY(0);}
    }
    @media (max-width: 500px) {
      .login-container {
        padding: 24px 10px 18px 10px;
        max-width: 95vw;
      }
    }
    .reset-signup-row {
      display: flex;
      justify-content: space-between;
      width: 100%;
      margin-top: 12px;
      font-size: 0.95rem;
      color: #444;
    }
    .reset-signup-separator {
      margin: 0 8px;
      color: #b6895b;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-title">Login</div>
    <form class="login-form" method="post">
      <?php if($pesan) echo $pesan; ?>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <div class="reset-signup-row">
        <a href="reset_password.php" class="reset-link">Lupa password?</a>
        <span class="reset-signup-separator"></span>
        <a href="signup.php" class="signup-link">Sign Up</a>
      </div>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>