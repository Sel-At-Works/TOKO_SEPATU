<?php
require_once 'vendor/autoload.php';

\Midtrans\Config::$serverKey = "SB-Mid-server-xxxxxxxxxx"; // pakai server key dari Midtrans Sandbox
\Midtrans\Config::$isProduction = false; // true kalau sudah live
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;
?>